<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\KitsController;
use App\Http\Controllers\NumbersController;
use App\Http\Controllers\WheelsController;
use App\Http\Controllers\WheelDimensionController;
use App\Http\Controllers\WheelModelController;
use App\Http\Controllers\WheelMaterialController;
use App\Http\Controllers\WheelSpecController;
use App\Http\Controllers\RelatedWheelController;
use App\Http\Controllers\StoreManageController;
use App\Http\Controllers\SubGroupsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UnitController;

use App\Http\Controllers\POSController;
use App\Http\Controllers\PricingController;
use App\Models\Supplier;
use App\Http\Controllers\Store\TransactionController;
use App\Http\Controllers\Store\StoreTransactionController;
use App\Http\Controllers\Store\StoreCoverController;
use App\Models\PartNumber;
use Illuminate\Http\Request;


use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RolePermissionController;




use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandTypeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CurrencyTypeController;
use App\Http\Controllers\DriveController;
use App\Http\Controllers\GearboxController;
use App\Http\Controllers\ModelController;
use App\Http\Controllers\PartQualityController;
use App\Http\Controllers\PricingTypeController;
use App\Http\Controllers\SeriesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SupplierBankController;
use App\Http\Controllers\TaxesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', function () {

    return view('auth.login');
});

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/home', function () {

        return view('index');
    });

    Route::get('parts', function () {
        return view('parts');
    });

    Route::Resource('part' , PartsController::class);
    Route::GET('partIdData/{partId}', [PartsController::class, 'indexWithID'])->name('partIdData');


    Route::POST('checkout', [PartsController::class, 'checkout'])->name('checkout');
    Route::GET('partsData', [PartsController::class, 'indexWithRequest'])->name('partsData');
    Route::GET('partsSearch', [PartsController::class, 'partsSearch'])->name('partsSearch');
    Route::GET('partsSearchNumber/{num}', [PartsController::class, 'partsSearchNumber'])->name('partsSearchNumber');
    Route::GET('partsSearchName/{name}', [PartsController::class, 'partsSearchName'])->name('partsSearchName');
    Route::GET('partSearchWithId/{id}', [PartsController::class, 'partSearchWithId'])->name('partSearchWithId');
    Route::GET('partUnderSubGroup/{subgroupid}', [PartsController::class, 'partUnderSubGroup'])->name('partUnderSubGroup');

    Route::GET('partspecs', [PartsController::class, 'partspecs'])->name('partspecs');
    Route::GET('partBrand', [PartsController::class, 'partBrand'])->name('partBrand');
    Route::GET('partsubgroup/{groupId}', [PartsController::class, 'partsubgroup'])->name('partsubgroup');

    Route::GET('kits', function () {
        return view('kitIndex');
    });
    Route::GET('kitIdData/{kitId}', [KitsController::class, 'indexWithID'])->name('kitIdData');
    Route::GET('kitsdata', [KitsController::class, 'indexWithRequest'])->name('kitsdata');
    Route::POST('kitStore', [KitsController::class, 'store'])->name('kitStore');
    Route::Resource('kit' , KitsController::class);
    Route::GET('kitspecs', [KitsController::class, 'kitspecs'])->name('kitspecs');
    Route::GET('kitBrand', [KitsController::class, 'kitBrand'])->name('kitBrand');
    Route::GET('part' , [PartsController::class, 'index'])->name('part');


    Route::GET('wheels', function () {
        return view('wheelIndex');
    });
    Route::GET('wheelIdData/{wheelId}', [WheelsController::class, 'indexWithID'])->name('wheelIdData');
    Route::GET('wheelsdata', [WheelsController::class, 'indexWithRequest'])->name('wheelsdata');
    Route::POST('wheelStore', [WheelsController::class, 'store'])->name('wheelStore');
    Route::Resource('wheel' , WheelsController::class);
    Route::resource('wheeldimensions', WheelDimensionController::class);
    Route::resource('wheelmodel', WheelModelController::class);
    Route::resource('wheelmaterial', WheelMaterialController::class);
    Route::resource('wheelspecs', WheelSpecController::class);
    Route::resource('relatedwheel', RelatedWheelController::class);

    Route::GET('tractors', function () {
        return view('tractorIndex');
    });
    Route::GET('tractorsdata', [KitsController::class, 'indexWithRequest'])->name('kitsdata');




    Route::GET('brandUnderSupplier/{supplierId}/{type_id}', [PartsController::class, 'brandUnderSupplier'])->name('brandUnderSupplier');
    Route::GET('brandTypeUnderSupplier/{supplierId}', [PartsController::class, 'brandTypeUnderSupplier'])->name('brandTypeUnderSupplier');
    Route::GET('ModelUnderSupplier/{supplierId}/{modelId}', [PartsController::class, 'ModelUnderSupplier'])->name('ModelUnderSupplier');
    Route::GET('SeriesUnderSupplier/{supplierId}/{seriesId}', [PartsController::class, 'SeriesUnderSupplier'])->name('SeriesUnderSupplier');
    Route::GET('PartUnderSupplier/{supplierId}', [PartsController::class, 'PartUnderSupplier'])->name('PartUnderSupplier');


    Route::GET('source', [StoreManageController::class, 'source'])->name('source');
    Route::GET('status', [StoreManageController::class, 'status'])->name('status');
    Route::GET('quality', [StoreManageController::class, 'quality'])->name('quality');


    Route::GET('partmodel/{Bid}/{ptypeid}', [PartsController::class, 'partmodel'])->name('partmodel');
    Route::GET('partseries/{Mid}', [PartsController::class, 'partseries'])->name('partseries');
    Route::GET('partSearchSeries/{sid}', [PartsController::class, 'partSearchSeries'])->name('partSearchSeries');
    Route::post('store-part', [PartsController::class, 'store']);


    Route::resource('groups', GroupsController::class);
    Route::resource('subgroups', SubGroupsController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('client', ClientController::class);

    Route::resource('pos', POSController::class);
    Route::GET('shop', [POSController::class, 'shop'])->name('shop');
    Route::POST('printpos', [POSController::class, 'printpos'])->name('printpos');
    Route::POST('saveMadyonea', [POSController::class, 'saveMadyonea'])->name('saveMadyonea');
    Route::GET('printBarcode/{barcodeTxt}/{name}', [POSController::class, 'printBarcode'])->name('printBarcode');




    Route::GET('newClientInline/{telNumber}', [POSController::class, 'newClientInline'])->name('newClientInline');
    Route::GET('storeSections/{storeId}', [POSController::class, 'storeSections'])->name('storeSections');
    Route::GET('getAllDataInSection/{sectionId}/{storeId}', [POSController::class, 'getAllDataInSection'])->name('getAllDataInSection');
    Route::POST('saveNewSection', [POSController::class, 'saveNewSection'])->name('saveNewSection');
    Route::GET('Clientinvoice/{clientId}/{storeId}', [POSController::class, 'Clientinvoice'])->name('Clientinvoice');


    Route::GET('shoptest', [POSController::class, 'shoptest'])->name('shoptest');

    // Route::get('shop', function () {


    //     return view('ecommerce.shop-grid');
    // });


    Route::GET('stores', [StoreManageController::class, 'stores'])->name('stores');
    Route::GET('gard/{storeid}', [StoreManageController::class, 'gard']);

    Route::POST('storeSend1', [StoreManageController::class, 'storeSend1'])->name('storeSend1');

    Route::resource('pricing', PricingController::class); // التسعيرة

    Route::resource('storeManage', StoreManageController::class); // فاتورة الشراء
    Route::GET('buyInvData', [StoreManageController::class, 'indexWithRequest'])->name('buyInvData');
    Route::GET('lastInvId', [StoreManageController::class, 'lastInvId'])->name('lastInvId');
    Route::GET('customSearch', [PartsController::class, 'customSearch'])->name('customSearch');
    Route::POST('customSearchResult', [PartsController::class, 'customSearchResult'])->name('customSearchResult');
    Route::POST('saveInv', [PartsController::class, 'saveInv'])->name('saveInv');

    Route::GET('printInvoice/{id}', [PartsController::class, 'printInvoice']);
    Route::GET('printBuyInvoice/{id}', [StoreManageController::class, 'printBuyInvoice']);
    Route::GET('storeManageItems/{id}', [StoreManageController::class, 'storeManageItems']);


    // Route::get('/file-import',[StoreManageController::class,'importView'])->name('import-view');
    // Route::post('importExcel',[StoreManageController::class,'import'])->name('importExcel');



    ///////////////////////////// SALAM //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////

        Route::get('/event', function () {

            event(new \App\Events\NewTrade('new message for new event'));

        });
        // move new parts and confirm transactions ------------------------------
        Route::get('/all_buy_invs',[TransactionController::class,'index']);
        Route::get('/items_inv/{id}',[TransactionController::class,'show'])->name('items_inv');
        Route::POST('/saveTransaction',[TransactionController::class,'send_new_parts'])->name('saveTransaction');
        Route::POST('/confirmStoreTrans',[TransactionController::class,'confirm_store_trans'])->name('confirmStoreTrans');
        Route::POST('/confirmStore',[POSController::class,'confirm_store'])->name('confirmStore');
        Route::POST('/refuseStoreTrans',[TransactionController::class,'refuse_store_trans'])->name('refuseStoreTrans');
        Route::POST('/hideStoreTrans',[TransactionController::class,'hide_store_trans'])->name('hideStoreTrans');
        Route::get('/inboxAdmin',[TransactionController::class,'inbox_admin_history'])->name('inboxAdmin');
        Route::get('/inboxAdmin/list',[TransactionController::class,'inbox_admin_history1'])->name('inboxAdmin.list');
        Route::get('/inboxStore',[POSController::class,'store_inbox_history'])->name('inboxStore');
        Route::get('/inboxStore/list/{storeId}',[POSController::class,'store_inbox_history1'])->name('inboxStore.list');

        Route::get('/itemsStore',[POSController::class,'store_items_history'])->name('itemsStore');
        Route::get('/itemsStore/list/{storeId}',[POSController::class,'store_items_history1'])->name('itemsStore.list');
        Route::get('/sendToOtherStore',[POSController::class,'send_to_other_store'])->name('sendToOtherStore');

        // End move new parts and confirm transactions ------------------------------
        Route::get('/store/{id}',[StoreTransactionController::class,'show'])->name('store');
        Route::get('/store_cover',[StoreCoverController::class,'index']);
        Route::Resource('numbers' , NumbersController::class);
        Route::GET('dtNumbers', [NumbersController::class, 'indexWithRequest'])->name('dtNumbers');





        Route::get('/items_inv', function () {

            event(new \App\Events\SaveTransaction('inbox'));

        });
        Route::get('/confirmStore', function () {

            event(new \App\Events\StoreTranaction('inbox'));

        });
        // Route::get('/inboxAdmin', function () {

        //     event(new \App\Events\SaveTransaction('inbox'));

        // });
        Route::get('/listen',function(){
            return view('test_event');
        });

      //////////////////////////////////Adel////////////////////////////////////////////////////////

    Route::resource('company', CompanyController::class);
    Route::resource('all_status', StatusController::class);
    Route::resource('supplier_bank', SupplierBankController::class);
    Route::GET('show_account_bank/{id}', [SupplierController::class, 'show_account_bank'])->name('show_account_bank');
    Route::resource('currency_type', CurrencyTypeController::class);
    Route::GET('show_currency/{id}', [CurrencyTypeController::class, 'show_currency'])->name('show_currency');
    Route::resource('currency', CurrencyController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('brand_type', BrandTypeController::class);
    Route::resource('model', ModelController::class);
    Route::resource('series', SeriesController::class);
    Route::resource('group', GroupsController::class);
    Route::resource('sub_group', SubGroupsController::class);
        /////////////////////////////////UserController///////////////////////////
        Route::resource('users', UserController::class);
        Route::get('/update_password', [UserController::class, 'update_password'])->name('update_password');
            /////////////////////////////////Adel_new///////////////////////////

        Route::resource('gearbox', GearboxController::class);
    Route::GET('show_catalog/{id}', [SubGroupsController::class, 'show_catalog'])->name('show_catalog');
    Route::resource('catalog', CatalogController::class);
    Route::resource('drive', DriveController::class);
    Route::resource('part_quality', PartQualityController::class);
    Route::resource('pricing_type', PricingTypeController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('all_source', SourceController::class);
    Route::resource('taxes', TaxesController::class);
    Route::resource('client', ClientController::class);
    Route::resource('section', SectionController ::class);

      ////////////////////////////////////////////////////////////////////////////////////////////

      //////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////
      Route::resource('/role', RoleController::class);
      Route::resource('/user_role', UserRoleController::class);
      Route::get('/save_user_role', [UserRoleController::class, 'save_user_role'])->name('save_user_role');
      Route::resource('/role_perm', RolePermissionController::class);
      Route::get('/save_role_perm', [RolePermissionController::class, 'save_role_perm'])->name('save_role_perm');
});

require __DIR__.'/auth.php';
