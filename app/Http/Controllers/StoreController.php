<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateDynamicTableRequest;
use App\Models\BranchTree;
use App\Models\Permission;
use App\Models\Role;
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
        //

        // return $request;
    DB::beginTransaction();
    try {
        DB::beginTransaction();
            $stores= Store::where('table_name','like','%store%')->get();
            $storenum=[];
            foreach ($stores as $key => $value) {
                // return (string)$value->table_name;
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

          Role::create([
              'name' => $tableName,
              'guard_name'=> 'web',
          ]);
        //   return$tableName;
        //   DB::rollBack();
          Permission::create([
              'name' => $tableName,
              'perm_desc_ar'=>substr($tableName, 0, 191),
              'guard_name'=> 'web',
          ]);

          DB::commit();
          session()->flash("success", "تم إنشاء مخزن ".$tableName."  بنجاح");
        return redirect()->back();
        // return response()->json(['message' => "Table $tableName has been created successfully with foreign keys."]);
    }catch (\Exception $e) {
        DB::rollBack();
        session()->flash("error", "لم يتم الإضافة");
        throw $e;
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
    public function destroy(Store $store)
    {
        //
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

            Role::create([
                'name' => $tableName,
                'guard_name'=> 'web',
            ]);
            Permission::create([
                'name' => $tableName,
                'perm_desc_ar'=> 'مخزن جديد',
                'guard_name'=> 'web',
            ]);
            DB::commit();
        return response()->json(['message' => "Table $tableName has been created successfully with foreign keys."]);
        }catch (\Exception $e) {
            DB::rollback();
            session()->flash("success", "");
            return $e;
            // return redirect()->back();
        }
    }
    
    private function generateModelWithRelationships($modelClass, $foreignKeys,$modelName,$tableName)
    {
            // Generate the model content with relationships
            $modelClass = ucfirst(Str::singular($modelName));

            // Generate the model content with relationships and additional configurations
            $modelContent = "<?php\n\n";
            $modelContent .= "namespace App\\Models;\n\n";
            $modelContent .= "use Illuminate\\Database\\Eloquent\\Model;\n";
            $modelContent .= "use OwenIt\Auditing\Contracts\Auditable;\n\n";

            // Start defining the class
            $modelContent .= "class " . $modelName . " extends Model  implements Auditable \n";
            $modelContent .= "{\n";
            $modelContent .= "\t use \OwenIt\Auditing\Auditable;\n\n";
            $modelContent .= "\tprotected \$table = '" . $tableName . "';\n\n";
        $modelContent .= "\t  public \$timestamps = false;\n";
            $modelContent .= "\t protected \$fillable = [\n";
                $modelContent .= "\t 'part_id',   \n";
            $modelContent .= "\t  'amount' ,  \n";
            $modelContent .= "\t  'supplier_order_id' ,  \n";
            $modelContent .= "\t 'notes' ,  \n";
            $modelContent .= "\t  'type_id' ,  \n";
            $modelContent .= "\t  'store_log_id' ,  \n";
            $modelContent .= "\t  'date'   \n";
            $modelContent .= "\t];\n";

            // Loop through foreign keys to define relationships
            foreach ($foreignKeys as $foreignKey) {
                $relationMethodName = Str::camel($foreignKey->on); // Assuming relation method name based on table name
                $relatedModelClass = "App\\Models\\" . ucfirst(Str::singular($foreignKey->on));

                $modelContent .= "\tpublic function $foreignKey->on()\n";
                $modelContent .= "\t{\n";
                $relatedModelClass = str_replace([' ', '_', '-'], "", $relatedModelClass);

                $modelContent .= "\t\treturn \$this->belongsTo('$relatedModelClass', '$foreignKey->column');\n";
                $modelContent .= "\t}\n\n";
            }

            // Close the class definition
            $modelContent .= "}\n";

            // Determine where to store the model
            $path = app_path('Models/' . $modelName . '.php');

            // Write model content to file
            File::put($path, $modelContent);

        return response()->json(['message' => " $modelName has been created successfully."]);
    }
}