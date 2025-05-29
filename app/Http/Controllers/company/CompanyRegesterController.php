<?php

namespace App\Http\Controllers\Company;



use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company\SubscriptionPlan;
use App\Models\Company\PaymentMethod;
use App\Models\Company\UserSubscription;
use App\Models\Company\UserPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
class CompanyRegesterController extends Controller
{
    public function showRegistrationForm()
    {
        $plans = \App\Models\Company\SubscriptionPlan::on('mysql_general')->where('is_active', true)->get();
        $paymentMethods = \App\Models\Company\PaymentMethod::on('mysql_general')->where('is_active', true)->get();
        return view('general.companyRegester', compact('plans', 'paymentMethods'));
    }
    protected function validator(array $data)
    {
        $rules = [
            'company_name' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s\-&]+$/'],
            'company_logo' => ['nullable', 'string', 'mimes:jpeg,png,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'subscription_plan_id' => ['required', 'exists:mysql_general.subscription_plans,id'],
        ];

        // Add payment method validation for paid plans
        if (!empty($data['subscription_plan_id'])) {
            $plan = SubscriptionPlan::on('mysql_general')->find($data['subscription_plan_id']);
            if ($plan && !$plan->is_free) {
                $rules['selected_payment_method_id'] = ['required', 'exists:mysql_general.payment_methods,id'];
            }
        }

        return validator($data, $rules, [
            'company_name.regex' => 'Company name can only contain letters, numbers, spaces, hyphens, and ampersands.',
            'company_logo.max' => 'Company logo must not be larger than 2MB.',
            'company_logo.mimes' => 'Company logo must be a JPEG, PNG, or SVG file.',
            'subscription_plan_id.required' => 'Please select a subscription plan.',
            'selected_payment_method_id.required' => 'Please select a payment method.',
        ]);
    }

    protected function create(array $data)
    {
        try {
            // Generate safe database name
            $dbName = 'company_' . Str::slug($data['company_name'], '_');
            
            // Handle logo based on source
            $logoPath = null;
            if (isset($data['company_logo'])) {
                // Check if the logo is a URL (from Google) or a file upload
                if (filter_var($data['company_logo'], FILTER_VALIDATE_URL)) {
                    // If it's a URL (from Google), store it as is
                    $logoPath = $data['company_logo'];
                } else {
                    // If it's a file upload, store it in the filesystem
                    $logoPath = $data['company_logo']->store("company-logos/{$dbName}", 'public');
                }
            }

            // Create user in the general database first
            $user = User::on('mysql_general')->create([
                'username' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'company_name' => $data['company_name'],
                'profile_img'=> $logoPath,
                'company_logo' => $logoPath,
                'db_name' => $dbName,
                'role_name' => 'Admin',
            ]);

            // Create the company database after user creation
            $this->createCompanyDatabase($dbName);
            \Log::info($user);
            return $user;
        } catch (\Exception $e) {
            // Clean up logo if database creation fails
            if (isset($logoPath) && !filter_var($logoPath, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($logoPath);
            }
            throw $e;
        }
    }

    protected function createCompanyDatabase($dbName)
    {
        $startTime = microtime(true);
        $baseDb = 'u683464483_lystah_db';
        $username = config('database.connections.mysql.backupusername');
        $password = config('database.connections.mysql.backuppassword');
        $host = config('database.connections.mysql.host');

        try {
            $conn = new \PDO(
                "mysql:host=$host",
                $username,
                $password,
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET SESSION sql_mode='NO_AUTO_VALUE_ON_ZERO,ALLOW_INVALID_DATES'"
                ]
            );

            // Create new database if not exists
            $conn->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $conn->exec("USE `$dbName`");

            // Disable foreign key checks and autocommit during creation
            $conn->exec("SET FOREIGN_KEY_CHECKS=0");
            $conn->exec("SET autocommit=0");
            $conn->exec("SET unique_checks=0");
            $conn->exec("SET sql_log_bin=0");

            // Get all tables from source database
            $tables = $conn->query("SHOW TABLES FROM `$baseDb`")->fetchAll(\PDO::FETCH_COLUMN);
            $tableCreationTime = microtime(true);
            //\Log::info("Database creation time: " . ($tableCreationTime - $startTime) . " seconds");

            // Create tables first
            foreach ($tables as $table) {
                $tableStartTime = microtime(true);
                try {
                    // Get the CREATE TABLE statement
                    $createTable = $conn->query("SHOW CREATE TABLE `$baseDb`.`$table`")->fetch();
                    $createSql = $createTable['Create Table'];
                    
                    // Fix timestamp default values in the SQL
                    $createSql = preg_replace(
                        "/`(created_at|updated_at)` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'/",
                        "`$1` timestamp NULL DEFAULT NULL",
                        $createSql
                    );
                    
                    // Execute the modified CREATE TABLE
                    $conn->exec($createSql);
                } catch (\PDOException $e) {
                    throw $e;
                }
                //\Log::info("Table $table creation time: " . (microtime(true) - $tableStartTime) . " seconds");
            }

            $dataCopyStartTime = microtime(true);
            // Copy data for each table
            foreach ($tables as $table) {
                $tableCopyStartTime = microtime(true);
                try {
                    // Get row count and determine if table needs batching
                    $rowCount = $conn->query("SELECT COUNT(*) FROM `$baseDb`.`$table`")->fetchColumn();
                    //\Log::info("Table $table has $rowCount rows");
                    
                    if ($rowCount > 0) {
                        // Get column names
                        $columns = $conn->query("SHOW COLUMNS FROM `$baseDb`.`$table`")->fetchAll(\PDO::FETCH_COLUMN);
                        $columnList = implode(', ', array_map(function($col) { return "`$col`"; }, $columns));
                        
                        if ($rowCount > 1000) {
                            // Use batch processing for large tables
                            $batchSize = 1000;
                            $offset = 0;
                            
                            while ($offset < $rowCount) {
                                $conn->exec("INSERT IGNORE INTO `$table` ($columnList) 
                                           SELECT $columnList FROM `$baseDb`.`$table` 
                                           LIMIT $offset, $batchSize");
                                $offset += $batchSize;
                            }
                        } else {
                            // Copy all data at once for small tables
                            $conn->exec("INSERT IGNORE INTO `$table` ($columnList) 
                                       SELECT $columnList FROM `$baseDb`.`$table`");
                        }
                    }
                } catch (\Exception $e) {
                    //\Log::error("Error copying data for table $table: " . $e->getMessage());
                    throw $e;
                }
                // \Log::info("Table $table data copy time: " . (microtime(true) - $tableCopyStartTime) . " seconds");
            }
            \Log::info("Total data copy time: " . (microtime(true) - $dataCopyStartTime) . " seconds");

            $fkStartTime = microtime(true);
            // Add foreign keys
            foreach ($tables as $table) {
                $tableFkStartTime = microtime(true);
                // $this->addForeignKeys($conn, $baseDb, $table); //this causes loading time
                //\Log::info("Table $table foreign key creation time: " . (microtime(true) - $tableFkStartTime) . " seconds");
            }
            \Log::info("Total foreign key creation time: " . (microtime(true) - $fkStartTime) . " seconds");

            // Re-enable settings
            $conn->exec("SET FOREIGN_KEY_CHECKS=1");
            $conn->exec("SET autocommit=1");
            $conn->exec("SET unique_checks=1");
            $conn->exec("SET sql_log_bin=1");

            //\Log::info("Total database creation time: " . (microtime(true) - $startTime) . " seconds");
            return true;

        } catch (\PDOException $e) {
            if (isset($conn)) {
                $conn->exec("DROP DATABASE IF EXISTS `$dbName`");
            }
            throw new \RuntimeException("Company database creation failed: " . $e->getMessage());
        }
    }

    protected function addForeignKeys($conn, $sourceDb, $table)
    {
        $startTime = microtime(true);
        try {
            // Disable foreign key checks temporarily
            $conn->exec("SET FOREIGN_KEY_CHECKS=0");
            
            // Get all foreign keys in a single optimized query
            $foreignKeys = $conn->query("
                SELECT 
                    kcu.CONSTRAINT_NAME,
                    GROUP_CONCAT(kcu.COLUMN_NAME ORDER BY kcu.ORDINAL_POSITION) as COLUMN_NAMES,
                    kcu.REFERENCED_TABLE_NAME,
                    GROUP_CONCAT(kcu.REFERENCED_COLUMN_NAME ORDER BY kcu.ORDINAL_POSITION) as REFERENCED_COLUMNS,
                    rc.UPDATE_RULE,
                    rc.DELETE_RULE
                FROM 
                    INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
                JOIN 
                    INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
                ON 
                    kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
                    AND kcu.TABLE_SCHEMA = rc.CONSTRAINT_SCHEMA
                WHERE 
                    kcu.TABLE_SCHEMA = '$sourceDb'
                    AND kcu.TABLE_NAME = '$table'
                    AND kcu.REFERENCED_TABLE_NAME IS NOT NULL
                GROUP BY 
                    kcu.CONSTRAINT_NAME,
                    kcu.REFERENCED_TABLE_NAME,
                    rc.UPDATE_RULE,
                    rc.DELETE_RULE
            ")->fetchAll();

            if (empty($foreignKeys)) {
                //\Log::info("No foreign keys found for table $table");
                return;
            }

            // Create a single ALTER TABLE statement for all foreign keys
            $alterStatements = [];
            foreach ($foreignKeys as $fk) {
                // Generate a unique constraint name by adding a random string
                $uniqueConstraintName = $fk['CONSTRAINT_NAME'] . '_' . substr(md5(uniqid()), 0, 8);
                
                $alterStatements[] = "ADD CONSTRAINT `{$uniqueConstraintName}` 
                    FOREIGN KEY ({$fk['COLUMN_NAMES']}) 
                    REFERENCES `{$fk['REFERENCED_TABLE_NAME']}` ({$fk['REFERENCED_COLUMNS']})
                    ON DELETE {$fk['DELETE_RULE']} 
                    ON UPDATE {$fk['UPDATE_RULE']}";
            }

            if (!empty($alterStatements)) {
                $alterSql = "ALTER TABLE `$table` " . implode(", ", $alterStatements);
                $conn->exec($alterSql);
            }

            // Re-enable foreign key checks
            $conn->exec("SET FOREIGN_KEY_CHECKS=1");

            //\Log::info("Foreign key creation for table $table took: " . (microtime(true) - $startTime) . " seconds");
        } catch (\Exception $e) {
            // Ensure foreign key checks are re-enabled even if there's an error
            $conn->exec("SET FOREIGN_KEY_CHECKS=1");
            \Log::error("Error creating foreign keys for table $table: " . $e->getMessage());
            throw $e;
        }
    }

    protected function verifyDatabaseClone($conn, $sourceDb, $targetDb)
    {
        // Verify table counts match
        $sourceTables = $conn->query("SHOW TABLES FROM `$sourceDb`")->fetchAll(\PDO::FETCH_COLUMN);
        $targetTables = $conn->query("SHOW TABLES FROM `$targetDb`")->fetchAll(\PDO::FETCH_COLUMN);
        
        if (count($sourceTables) !== count($targetTables)) {
            throw new \RuntimeException("Table count mismatch between source and target databases");
        }
    }

    protected function cloneDatabaseObjects($conn, $sourceDb, $targetDb, $table)
    {
        // Clone triggers for this table
        $triggers = $conn->query("SHOW TRIGGERS FROM `$sourceDb` LIKE '$table'")->fetchAll();
        foreach ($triggers as $trigger) {
            $triggerName = $trigger['Trigger'];
            $createTrigger = $conn->query("SHOW CREATE TRIGGER `$sourceDb`.`$triggerName`")->fetch();
            $conn->exec($createTrigger['SQL Original Statement']);
        }
    }

    protected function cloneDatabaseRoutines($conn, $sourceDb, $targetDb)
    {
        // Clone stored procedures
        $procedures = $conn->query("SHOW PROCEDURE STATUS WHERE Db = '$sourceDb'")->fetchAll();
        foreach ($procedures as $proc) {
            $createProc = $conn->query("SHOW CREATE PROCEDURE `$sourceDb`.`{$proc['Name']}`")->fetch();
            $conn->exec($createProc['Create Procedure']);
        }

        // Clone functions
        $functions = $conn->query("SHOW FUNCTION STATUS WHERE Db = '$sourceDb'")->fetchAll();
        foreach ($functions as $func) {
            $createFunc = $conn->query("SHOW CREATE FUNCTION `$sourceDb`.`{$func['Name']}`")->fetch();
            $conn->exec($createFunc['Create Function']);
        }

        // Clone views
        $views = $conn->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = '$sourceDb'")->fetchAll();
        foreach ($views as $view) {
            $createView = $conn->query("SHOW CREATE VIEW `$sourceDb`.`{$view['TABLE_NAME']}`")->fetch();
            $conn->exec($createView['Create View']);
        }
    }

    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();
        // return $request->all();
        //   $user = $this->create($request->all());
        // $user = $this->create($request->all());
        try {
            // Validate the request
            // $this->validator($request->all())->validate();
            // Start database operations
            DB::beginTransaction();

            try {
                // First connect to the general database
                config(['database.connections.mysql.database' => 'general']);
                DB::purge('mysql');
                DB::reconnect('mysql');

                // Create user and company
                 $user = $this->create($request->all());

                // Get subscription plan
                 $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);
                 \Log::info($plan);

                // Create user subscription
                $subscription = new UserSubscription([
                    'user_id' => $user->id,
                    'subscription_plan_id' => $plan->id,
                    'starts_at' => Carbon::now(),
                    'ends_at' => Carbon::now()->addDays($plan->duration_in_days),
                    'status' => 'active',
                    'price' => $plan->price
                ]);
                $subscription->save();

                // Create payment record for paid plans
                if (!$plan->is_free) {
                    $payment = new UserPayment([
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                        'payment_method_id' => $request->selected_payment_method_id,
                        'amount' => $plan->price,
                        'status' => 'pending', // You can update this based on payment gateway response
                        'paid_at' => Carbon::now()
                    ]);
                    $payment->save();
                }
            
               
                // Switch to company database and initialize
                $this->initializeCompanyDatabase($user->db_name, $user);
                
                // Store company database in session and user model
                session(['current_database' => $user->db_name]);
                session(['current_company_name' => $user->company_name]);
                $user->db_name = $user->db_name;
                $user->save();
                
                // Login the user
                auth()->login($user);

                DB::commit();

                // Ensure we're using the company database for the next request
                config(['database.connections.mysql.database' => $user->db_name]);
                DB::purge('mysql');
                DB::reconnect('mysql');

                return redirect()->route('company.dashboard')
                       ->with('success', 'Registration successful!');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            session()->flash("error",  $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    protected function initializeCompanyDatabase($dbName, $adminUser)
    {
        
        $startTime = microtime(true);
        try {
            // Switch to company database
            config(['database.connections.mysql.database' => $dbName]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Create admin user in company database
            $user = DB::table('users')->insertGetId([
                'username' => $adminUser->username,
                'email' => $adminUser->email,
                'password' => $adminUser->password,
                'created_on' => now(),
            ]);

            // Create admin role if it doesn't exist
            $role = DB::table('roles')->where('name', 'admin')->first();
            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'admin',
                    'guard_name' => '',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $roleId = $role->id;
            }

            // Assign admin role
            $userModel = User::find($user);
            $userModel->assignRole($roleId);

            //\Log::info("Company database initialization took: " . (microtime(true) - $startTime) . " seconds");
        } finally {
            // Switch back to general database for next request
            config(['database.connections.mysql.database' => 'general']);
            DB::purge('mysql');
            DB::reconnect('mysql');
        }
    }
}
