<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateDynamicTableRequest;
use App\Models\BranchTree;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Company\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

use App\Models\Store;
use COM;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Store::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        
        // Validate database name
        if (empty($user_general->db_name)) {
            session()->flash("error", "No database assigned to this user.");
            return redirect()->back();
        }

        // Store original database
        $originalDb = config('database.connections.mysql.database');
        
        try {
            // Switch to company database
            config(['database.connections.mysql.database' => $user_general->db_name]);
            DB::purge('mysql');
            DB::reconnect('mysql');
            
            // Explicitly select the database
            DB::statement("USE `{$user_general->db_name}`");
            
            // Start transaction
            DB::beginTransaction();
            
            $stores= Store::where('table_name','like','%store%')->get();
            $storenum=[];
            foreach ($stores as $key => $value) {
                array_push($storenum , $this->extractNumbers($value->table_name)) ;
            }
            $lastStore_num =  max($storenum);
            $newStore_num =$lastStore_num + 1;
            $new_id= Store::create([
                'name'=>  $request->name,
                'location'=>$request->location,
                'address'=> $request->adress,
                'tel01'=> $request->tel01,
                'tel02'=>$request->tel02,
                'note'=> $request->desc,
                'table_name'=> "store".$newStore_num,
                'accountant_number' => 0 ,
                'safe_accountant_number' => 0,
                'unit_id' => $request->unit_id
            ])->id;
            $stor_data = Store::where('id','=',$new_id)->first();
            $parentid= BranchTree::where('accountant_number',131)->first()->id;
            $lastchildAccNo_store = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
            $string = (string)$lastchildAccNo_store;
            $sequence = "131";
            $result = $this->extractNumbersAfterSequence($string, $sequence);
            $accId_store = BranchTree::create([
                'name' =>   ' مخزن -'.$stor_data->name,
                'en_name' => $stor_data->name,
                'parent_id' =>  $parentid,
                'accountant_number'=>$sequence.IntVal($result)+1
            ])->id;
            $parentid= BranchTree::where('accountant_number',181)->first()->id;
            $lastchildAccNo_safe = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
            $string = (string)$lastchildAccNo_safe;
            $sequence = "181";
            $result =  $this->extractNumbersAfterSequence($string, $sequence);

            $accId_safe = BranchTree::create([
                'name' =>   ' صندوق -'.$stor_data->name,
                'en_name' => $stor_data->name,
                'parent_id' =>  $parentid,
                'accountant_number'=>$sequence.IntVal($result)+1
            ])->id;
            Store::where('id','=',$new_id)->update([
                'accountant_number' =>  $lastchildAccNo_store+1 ,
                'safe_accountant_number' => $lastchildAccNo_safe+1
            ]);

            $request = (object) [
                "table_name" => "store".$newStore_num,
                "columns" => [
                    (object) [
                        "name" => "part_id",
                        "type" => "integer",
                        "nullable" => true,
                    ],
                    (object) [
                        "name" => "amount",
                        "type" => "integer",
                        "nullable" => true,
                        "default" => 0,
                    ],
                    (object) [
                        "name" => "supplier_order_id",
                        "type" => "integer",
                        "nullable" => true,
                    ],
                    (object) [
                        "name" => "notes",
                        "type" => "text",
                        "nullable" => true,
                    ],
                    (object) [
                        "name" => "type_id",
                        "type" => "integer",
                        "nullable" => true,
                        "default" => 0,
                    ],
                    (object) [
                        "name" => "store_log_id",
                        "type" => "integer",
                        "nullable" => true,
                    ],
                    (object) [
                        "name" => "date",
                        "type" => "datetime",
                        "nullable" => true,
                    ],
                    (object) [
                        "name" => "unit_id",
                        "type" => "integer",
                        "nullable" => true,
                    ],
                ],
                "foreign_keys" => [
                    (object) [
                        "column" => "supplier_order_id",
                        "references" => "id",
                        "on" => "order_supplier",
                        "onDelete" => "cascade",
                    ],
                    (object) [
                        "column" => "store_log_id",
                        "references" => "id",
                        "on" => "stores_log",
                        "onDelete" => "cascade",
                    ],
                    (object) [
                        "column" => "unit_id",
                        "references" => "id",
                        "on" => "unit",
                        "onDelete" => "set null",
                    ],
                ],
            ];

            $tableName = $request->table_name;
            $columns = $request->columns;
            $foreignKeys = $request->foreign_keys;

            // First create the table without foreign keys
            Schema::create($tableName, function (Blueprint $table) use ($columns) {
                $table->id();
                foreach ($columns as $column) {
                    $type = $column->type;
                    $name = $column->name;
                    $columnBlueprint = $table->$type($name);

                    if (isset($column->nullable) && $column->nullable) {
                        $columnBlueprint->nullable();
                    }

                    if (isset($column->default)) {
                        $columnBlueprint->default($column->default);
                    }
                }
            });

            // Then add foreign keys in a separate step
            Schema::table($tableName, function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $foreignKey) {
                    try {
                        $table->foreign($foreignKey->column)
                            ->references($foreignKey->references)
                            ->on($foreignKey->on)
                            ->onDelete($foreignKey->onDelete);
                    } catch (\Exception $e) {
                        // Log the error but continue with other foreign keys
                        \Log::error("Failed to add foreign key {$foreignKey->column}: " . $e->getMessage());
                    }
                }
            });

            // Generate the model
            $modelName = ucfirst(Str::singular($tableName));
            Artisan::call('make:model', ['name' => $modelName]);

            $modelName = ucfirst(Str::singular($tableName));
            $modelClass = "App\\Models\\$modelName";
            $this->generateModelWithRelationships($modelClass, $foreignKeys, $modelName, $tableName);

            // Create role if it doesn't exist
            $role = DB::table('roles')->where('name', $tableName)->first();
            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $tableName,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $roleId = $role->id;
            }

            // Create permission if it doesn't exist
            $permission = DB::table('permissions')->where('name', $tableName)->first();
            if (!$permission) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => $tableName,
                    'perm_desc_ar' => substr($tableName, 0, 191),
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $permissionId = $permission->id;
            }

            // Get admin role
            $adminRole = DB::table('roles')->where('name', 'admin')->first();
            if (!$adminRole) {
                $adminRoleId = DB::table('roles')->insertGetId([
                    'name' => 'admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $adminRoleId = $adminRole->id;
            }

            // Assign permission to admin role
            $roleHasPermission = DB::table('role_has_permissions')
                ->where('role_id', $adminRoleId)
                ->where('permission_id', $permissionId)
                ->first();

            if (!$roleHasPermission) {
                DB::table('role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRoleId
                ]);
            }

            // Commit the transaction
            DB::commit();
            
            // Restore original database connection
            config(['database.connections.mysql.database' => $originalDb]);
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::statement("USE `{$originalDb}`");
            
            session()->flash("success", "تم إنشاء مخزن ".$tableName."  بنجاح");
            return redirect()->back();
        } catch (\Exception $e) {
            // Only rollback if we're in a transaction
            try {
                if (DB::transactionLevel() > 0) {
                    DB::rollBack();
                }
            } catch (\Exception $rollbackError) {
                // Log the rollback error but continue with cleanup
                \Log::error("Rollback error: " . $rollbackError->getMessage());
            }
            
            // Restore original database connection
            try {
                config(['database.connections.mysql.database' => $originalDb]);
                DB::purge('mysql');
                DB::reconnect('mysql');
                DB::statement("USE `{$originalDb}`");
            } catch (\Exception $connectionError) {
                // Log the connection error but continue
                \Log::error("Connection restore error: " . $connectionError->getMessage());
            }
            
            // If we got here and everything was created, show success message
            if (isset($tableName) && Schema::hasTable($tableName)) {
                session()->flash("success", "تم إنشاء مخزن ".$tableName."  بنجاح");
            } else {
                session()->flash("error", "لم يتم الإضافة: " . $e->getMessage());
            }
            
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        // return $request;
        $store=Store::where('id',$request->store_id)->first();
        $store->update([
            'name'=>$request->name,
            'location'=>$request->location,
            'address'=>$request->adress,
            'tel01'=>$request->tel01,
            'tel02'=>$request->tel02,
            'note'=>$request->desc
        ]);
        session()->flash("success", "تم التعديل بنجاح");
        return redirect()->back();

        return redirect()->route('/all_Stores');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // Debug store model
        $store = Store::where('id', $request->store_id)->first();
        if (!$store) {
            session()->flash("error", "المخزن غير موجود");
            return redirect()->back();
        }

        \Log::info("Store model before deletion:", [
            'store_id' => $store->id,
            'table_name' => $store->table_name,
            'store_name' => $store->name,
            'store_attributes' => $store->getAttributes()
        ]);

        // Get the authenticated user
        $user = auth()->user();
        $user_general = User::on('mysql_general')->where('email', $user->email)->first();
        
        // Validate database name
        if (empty($user_general->db_name)) {
            session()->flash("error", "No database assigned to this user.");
            return redirect()->back();
        }

        // Store original database
        $originalDb = config('database.connections.mysql.database');
        
        try {
            // Switch to company database
            config(['database.connections.mysql.database' => $user_general->db_name]);
            DB::purge('mysql');
            DB::reconnect('mysql');
            
            // Explicitly select the database
            DB::statement("USE `{$user_general->db_name}`");
            
            // Start transaction
            DB::beginTransaction();

            // Get the table name from the store model
            $tableName = $store->table_name;
            
            // Validate table name
            if (empty($tableName)) {
                throw new \Exception('اسم الجدول غير صالح - جدول المخزن غير موجود');
            }

            \Log::info("Attempting to delete store with table: " . $tableName);

            // Check for parts in the store's table
            $hasParts = DB::select("SELECT EXISTS(SELECT 1 FROM `{$tableName}` WHERE amount > 0) as has_parts")[0]->has_parts;

            if ($hasParts) {
                throw new \Exception('لا يمكن حذف المخزن لوجود أجزاء به');
            }

            // Check for parts in store sections
            $hasSections = DB::table('store_section')
                ->where('store_id', $store->id)
                ->where('amount', '>', 0)
                ->exists();

            if ($hasSections) {
                throw new \Exception('لا يمكن حذف المخزن لوجود أجزاء في الأقسام');
            }

            // Drop the store's table
            Schema::dropIfExists($tableName);

            // Delete the store record
            $store->delete();

            // Remove associated roles and permissions
            Role::where('name', $tableName)->delete();
            Permission::where('name', $tableName)->delete();

            // Commit the transaction
            DB::commit();

            // Restore original database connection
            config(['database.connections.mysql.database' => $originalDb]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            session()->flash("success", "تم حذف المخزن بنجاح");
            return redirect()->back();

        } catch (\Exception $e) {
            // Only rollback if we're in a transaction
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            // Restore original database connection
            try {
                config(['database.connections.mysql.database' => $originalDb]);
                DB::purge('mysql');
                DB::reconnect('mysql');
            } catch (\Exception $connectionError) {
                \Log::error("Error restoring database connection: " . $connectionError->getMessage());
            }

            \Log::error("Store deletion error: " . $e->getMessage());
            session()->flash("error", $e->getMessage());
            return redirect()->back();
        }
    }
    public function get_all_store()
    {

         return Store::all();
    }
    public function all_Stores()
    {

       $all_Stores = Store::all();
        return view('store.new_store',compact('all_Stores'));
    }
  
    function extractNumbersAfterSequence($string, $sequence) {
        // Use a regular expression to find the sequence followed by digits
        if (preg_match('/' . preg_quote($sequence, '/') . '(\d+)/', $string, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    function extractNumbers($string) {
        // Use a regular expression to find all numbers in the string
        preg_match_all('/\d+/', $string, $matches);

        // Concatenate all matches to form a single number
        $numberString = implode('', $matches[0]);

        // Convert the concatenated string to an integer
        $number = (int)$numberString;

        return $number;
    }
    
    public function createTable()
    {
          // Define the table structure as an object
        return "mohamed";
          DB::beginTransaction();
          try {

           $stores= Store::where('table_name','like','%store%')->get();
           $storenum=[];
           foreach ($stores as $key => $value) {
             // return (string)$value->table_name;
               array_push($storenum , $this->extractNumbers($value->table_name)) ;
           }
            $lastStore_num =  max($storenum);
           $newStore_num =$lastStore_num + 1;
          $new_id= Store::create([
            'name'=> 'مخزن جديد',
            'location'=> 'مكان جديد',
            'address'=> 'عنوان جديد',
            'tel01'=> '',
            'tel02'=> '',
            'note'=> '',
            'table_name'=> "store".$newStore_num,
            'accountant_number' => 0 ,
            'safe_accountant_number' => 0
           ])->id;
            $stor_data = Store::where('id','=',$new_id)->first();
           $parentid= BranchTree::where('accountant_number',131)->first()->id;
           $lastchildAccNo_store = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
           $string = (string)$lastchildAccNo_store;
           $sequence = "131";
           $result = $this->extractNumbersAfterSequence($string, $sequence);
           $accId_store = BranchTree::create([
               'name' =>   ' مخزن -'.$stor_data->name,
               'en_name' => $stor_data->name,
               'parent_id' =>  $parentid,
               'accountant_number'=>$sequence.IntVal($result)+1
           ])->id;
           $parentid= BranchTree::where('accountant_number',181)->first()->id;
           $lastchildAccNo_safe = BranchTree::where('parent_id',$parentid)->orderBy('id','DESC')->first()->accountant_number;
           $string = (string)$lastchildAccNo_safe;
           $sequence = "181";
           $result =  $this->extractNumbersAfterSequence($string, $sequence);


           $accId_safe = BranchTree::create([
            'name' =>   ' صندوق -'.$stor_data->name,
            'en_name' => $stor_data->name,
            'parent_id' =>  $parentid,
            'accountant_number'=>$sequence.IntVal($result)+1
        ])->id;
        Store::where('id','=',$new_id)->update([
            'accountant_number' =>  $lastchildAccNo_store+1 ,
            'safe_accountant_number' => $lastchildAccNo_safe+1
        ]);

        // return 'done';
          $request = (object) [
            "table_name" => "store".$newStore_num,
            "columns" => [
                (object) [
                    "name" => "part_id",
                    "type" => "integer",
                    "nullable" => true,
                ],
                (object) [
                    "name" => "amount",
                    "type" => "integer",
                    "nullable" => true,
                    "default" => 0,
                ],
                (object) [
                    "name" => "supplier_order_id",
                    "type" => "integer",
                    "nullable" => true,
                ],
                (object) [
                    "name" => "notes",
                    "type" => "text",
                    "nullable" => true,
                ],
                (object) [
                    "name" => "type_id",
                    "type" => "integer",
                    "nullable" => true,
                    "default" => 0,
                ],
                (object) [
                    "name" => "store_log_id",
                    "type" => "integer",
                    "nullable" => true,
                ],
                (object) [
                    "name" => "date",
                    "type" => "datetime",
                    "nullable" => true,
                ],
            ],
            "foreign_keys" => [
                (object) [
                    "column" => "supplier_order_id",
                    "references" => "id",
                    "on" => "order_supplier",
                    "onDelete" => "cascade",
                ],
                (object) [
                    "column" => "store_log_id",
                    "references" => "id",
                    "on" => "stores_log",
                    "onDelete" => "cascade",
                ],
            ],
        ];

        $tableName = $request->table_name;
        $columns = $request->columns;
        $foreignKeys = $request->foreign_keys;

        Schema::create($tableName, function (Blueprint $table) use ($columns, $foreignKeys) {
            $table->id();
            foreach ($columns as $column) {
                $type = $column->type;
                $name = $column->name;
                $columnBlueprint = $table->$type($name);

                if (isset($column->nullable) && $column->nullable) {
                    $columnBlueprint->nullable();
                }

                if (isset($column->default)) {
                    $columnBlueprint->default($column->default);
                }
            }

            foreach ($foreignKeys as $foreignKey) {
                $table->foreign($foreignKey->column)
                      ->references($foreignKey->references)
                      ->on($foreignKey->on)
                      ->onDelete($foreignKey->onDelete);
            }

            // $table->timestamps();
        });
            // Generate the model
            $modelName = ucfirst(Str::singular($tableName));
            Artisan::call('make:model', ['name' => $modelName]);

            $modelName = ucfirst(Str::singular($tableName));
            $modelClass = "App\\Models\\$modelName";
            $this->generateModelWithRelationships($modelClass, $foreignKeys ,$modelName,$tableName);

            // Create role if it doesn't exist
            $role = DB::table('roles')->where('name', $tableName)->first();
            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => $tableName,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $roleId = $role->id;
            }

            // Create permission if it doesn't exist
            $permission = DB::table('permissions')->where('name', $tableName)->first();
            if (!$permission) {
                DB::table('permissions')->insert([
                    'name' => $tableName,
                    'perm_desc_ar' => substr($tableName, 0, 191),
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();
        return response()->json(['message' => "Table $tableName has been created successfully with foreign keys."]);
        }catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "");
            return $e;
            // return redirect()->back();
        }
    }
    
    protected function generateModelWithRelationships($modelClass, $foreignKeys, $modelName, $tableName)
    {
        $modelContent = "<?php\n\n";
        $modelContent .= "namespace App\\Models;\n\n";
        $modelContent .= "use Illuminate\\Database\\Eloquent\\Model;\n";
        $modelContent .= "use OwenIt\\Auditing\\Contracts\\Auditable;\n\n";

        // Start defining the class
        $modelContent .= "class " . $modelName . " extends Model implements Auditable\n";
        $modelContent .= "{\n";
        $modelContent .= "\tuse \\OwenIt\\Auditing\\Auditable;\n\n";
        $modelContent .= "\tprotected \$table = '" . $tableName . "';\n\n";
        $modelContent .= "\tpublic \$timestamps = false;\n\n";

        // Add fillable properties
        $fillableColumns = [];
        foreach ($foreignKeys as $foreignKey) {
            $fillableColumns[] = $foreignKey->column;
        }
        $fillableColumns[] = 'part_id';
        $fillableColumns[] = 'amount';
        $fillableColumns[] = 'notes';
        $fillableColumns[] = 'type_id';
        $fillableColumns[] = 'date';

        $modelContent .= "\tprotected \$fillable = [\n";
        foreach ($fillableColumns as $column) {
            $modelContent .= "\t\t'" . $column . "',\n";
        }
        $modelContent .= "\t];\n\n";

        // Add relationships
        foreach ($foreignKeys as $foreignKey) {
            $relatedModel = ucfirst(Str::singular($foreignKey->on));
            $relationName = Str::camel(Str::singular($foreignKey->on));
            
            $modelContent .= "\tpublic function " . $relationName . "()\n";
            $modelContent .= "\t{\n";
            $modelContent .= "\t\treturn \$this->belongsTo(" . $relatedModel . "::class, '" . $foreignKey->column . "');\n";
            $modelContent .= "\t}\n\n";
        }

        $modelContent .= "}\n";

        // Write the model file
        $modelPath = app_path("Models/{$modelName}.php");
        file_put_contents($modelPath, $modelContent);
    }
}