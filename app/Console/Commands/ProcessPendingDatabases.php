<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessPendingDatabases extends Command
{
    protected $signature = 'process:pending-databases';
    protected $description = 'Process pending database assignments';

    public function handle()
    {
        $pending = DB::table('pending_databases')
                    ->where('completed', false)
                    ->get();

        if ($pending->isEmpty()) {
            $this->info('No pending databases to process');
            return;
        }

        foreach ($pending as $request) {
            try {
                // Verify database exists first
                $dbExists = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA 
                                       WHERE SCHEMA_NAME = ?", [$request->db_name]);
                
                if (empty($dbExists)) {
                    Log::error("Database does not exist: {$request->db_name}");
                    continue;
                }

                // Grant privileges (will work if user already has all-db access)
                DB::statement("GRANT ALL PRIVILEGES ON `{$request->db_name}`.* TO '{$request->username}'@'%'");
                
                // Mark as completed
                DB::table('pending_databases')
                    ->where('id', $request->id)
                    ->update(['completed' => true]);
                    
                Log::info("Successfully assigned database: {$request->db_name} to {$request->username}");
                
            } catch (\Exception $e) {
                Log::error("Failed to assign DB {$request->db_name}: " . $e->getMessage());
            }
        }
        
        $this->info('Processed ' . count($pending) . ' pending databases');
    }
}
