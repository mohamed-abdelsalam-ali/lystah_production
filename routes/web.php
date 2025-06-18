<?php
use App\Http\Controllers\AcountantReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\accountantController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\PartsController;
use App\Http\Controllers\KitsController;
use App\Http\Controllers\NewPosController;
use App\Http\Controllers\NumbersController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\WheelsController;
use App\Http\Controllers\CoastController;
use App\Http\Controllers\WheelDimensionController;
use App\Http\Controllers\WheelModelController;
use App\Http\Controllers\WheelMaterialController;
use App\Http\Controllers\WheelSpecController;
use App\Http\Controllers\RelatedWheelController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StoreManageController;
use App\Http\Controllers\SubGroupsController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\PartNumberController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ArdasarController;
use App\Http\Controllers\Store\TransactionController;
use App\Http\Controllers\Store\StoreTransactionController;
use App\Http\Controllers\ItemLiveController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\coaController;
use App\Http\Controllers\TalabeaController;
use App\Http\Controllers\Store\StoreCoverController;
use App\Http\Controllers\StorePartsController;
use App\Models\PartNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\kitCollectionController;
use App\Http\Controllers\AllPartsController;
use App\Http\Controllers\BankSafeMoneyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\accountingController;
use App\Http\Controllers\TractorsController;
use App\Http\Controllers\ClarksController;
use App\Http\Controllers\EquipsController;
use App\Http\Controllers\allItemsDetailsController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Console\Input\Input;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandTypeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\Company\CompanyController;
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
use App\Http\Controllers\ClientHesapController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\SafeMoneyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteSafeMoneyController;
use App\Http\Controllers\SolfaController;
use App\Http\Controllers\BankTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\sellInvoiceController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\PartReportController;
use App\Http\Controllers\QaydController;
use App\Http\Controllers\QaydTypeController;
use App\Http\Controllers\ServiceInvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RefundNewController;
use App\Http\Controllers\Company\CompanyRegesterController;
use App\Http\Controllers\Company\NewCompanyController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\SupplierHesapController;
use App\Http\Controllers\Company\PaymentMethodController;
use App\Http\Controllers\Company\SubscriptionPlanController;
use App\Http\Controllers\Company\SubscriptionController;
use App\Http\Controllers\Company\AdminController;
use App\Http\Controllers\ProfileController;

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
    Auth::logout();
    return view('auth.login');
});
// Route::get('/getuser/{user}', function () {
//    $user=Auth::user();
//     $user_general = \App\Models\User::on('mysql_general')->where('email', $user->email)->first();

//     return $user_general->getRole();
// });
Route::GET('inventory', [InventoryController::class, 'inventory'])->name('inventory');


Route::get('company_reg', [CompanyRegesterController::class, 'showRegistrationForm'])->name('register.company');
Route::post('regestercompany', [CompanyRegesterController::class, 'register'])->name('regestercompany');

// Route::resource('payment-methods', PaymentMethodController::class);
// Route::resource('subscription-plans', SubscriptionPlanController::class);


// Company admin routes - accessible by super_admin from general DB or company admin
Route::middleware(['auth', 'company.admin'])->group(function () {
    Route::get('/company/dashboard', [CompanyController::class, 'dashboard'])->name('company.dashboard');
    Route::get('/subscription/renew', [SubscriptionController::class, 'showRenewalPage'])->name('subscription.renew');
    Route::post('/subscription/renew', [SubscriptionController::class, 'processRenewal'])->name('subscription.process_renewal');
    
    // Company Configuration
    Route::get('/company/config', [NewCompanyController::class, 'config'])
        ->name('company.config');

    // Add New User
    Route::post('/company/users', [NewCompanyController::class, 'addUser'])
        ->name('company.users.store');
});

// Super admin routes - only accessible by super_admin from general DB
Route::middleware(['auth', 'super.admin'])->prefix('admin')->group(function () {
    // Dashboard and Company Management
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/switch-company', [AdminController::class, 'switchToCompany'])->name('admin.switch-company');
    Route::get('/return-to-admin', [AdminController::class, 'returnToAdmin'])->name('admin.return-to-admin');

    // Payment Management
    Route::post('/update-payment-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.update-payment-status');
    Route::post('/process-payment-gateway', [AdminController::class, 'processPaymentGateway'])->name('admin.process-payment-gateway');

    // Subscription Plans Management
    Route::resource('/subscription-plans', SubscriptionPlanController::class)->names([
        'index' => 'subscription-plans.index',
        'create' => 'subscription-plans.create',
        'show' => 'subscription-plans.show',
        'edit' => 'subscription-plans.edit',
        'update' => 'subscription-plans.update',
        'destroy' => 'subscription-plans.destroy',
    ]);

    // Payment Methods Management
    Route::resource('payment-methods', PaymentMethodController::class)->names([
        'index' => 'payment-methods.index',
        'create' => 'payment-methods.create',
        'store' => 'payment-methods.store',
        'show' => 'payment-methods.show',
        'edit' => 'payment-methods.edit',
        'update' => 'payment-methods.update',
        'destroy' => 'payment-methods.destroy',
    ]);
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports.index');

    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('/users/{user}/reset-password', [AdminController::class, 'resetUserPassword'])->name('admin.users.reset-password');
    Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
});

Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('user.users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
    // Subscription Payment Routes
    Route::get('/subscription/payments', [App\Http\Controllers\Company\SubscriptionPaymentController::class, 'index'])
        ->name('subscription.payments');
    Route::get('/subscription/payments/{paymentId}', [App\Http\Controllers\Company\SubscriptionPaymentController::class, 'show'])
        ->name('subscription.payment.show');
    Route::get('/subscription/payments/{paymentId}/retry', [App\Http\Controllers\Company\SubscriptionPaymentController::class, 'showRetry'])
        ->name('subscription.payment.showRetry');
    Route::post('/subscription/payments/{paymentId}/retry', [App\Http\Controllers\Company\SubscriptionPaymentController::class, 'retry'])
        ->name('subscription.payment.retry');

    Route::get('/home', [HomeController::class,'index']);

    Route::get('parts', function () {
        return view('parts');
    });

    Route::Resource('part', PartsController::class);

     Route::GET('partIdData/{partId}', [PartsController::class, 'indexWithID'])->name('partIdData');
     Route::delete('part.destroy/{partId}', [PartsController::class, 'destroy'])->name('part.destroy');




    Route::POST('checkout', [PartsController::class, 'checkout'])->name('checkout');
    Route::GET('partsData', [PartsController::class, 'indexWithRequest'])->name('partsData');
    Route::GET('partsSearch', [PartsController::class, 'partsSearch'])->name('partsSearch');
    Route::GET('sectionsSearch', [SectionController::class, 'sectionsSearch'])->name('sectionsSearch');

    Route::GET('partsSearchNumber/{num}', [PartsController::class, 'partsSearchNumber'])->name('partsSearchNumber');
    Route::GET('allDataForIdPartNumber', [NewPosController::class, 'allDataForIdPartNumber'])->name('allDataForIdPartNumber');
    Route::GET('allDataForIdBrand', [NewPosController::class, 'allDataForIdBrand'])->name('allDataForIdBrand');
    Route::GET('allDataForIdModel', [NewPosController::class, 'allDataForIdModel'])->name('allDataForIdModel');
    Route::GET('allDataForIdSeries', [NewPosController::class, 'allDataForIdSeries'])->name('allDataForIdSeries');
    Route::GET('allDataForIdGroup', [NewPosController::class, 'allDataForIdGroup'])->name('allDataForIdGroup');
    Route::GET('allDataForIdSubGroup', [NewPosController::class, 'allDataForIdSubGroup'])->name('allDataForIdSubGroup');
    Route::GET('allDataForIdSupplier', [NewPosController::class, 'allDataForIdSupplier'])->name('allDataForIdSupplier');
    Route::GET('allDataForIdFilterAll', [NewPosController::class, 'allDataForIdFilterAll'])->name('allDataForIdFilterAll');


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
    Route::Resource('kit', KitsController::class);
    Route::delete('kit.destroy/{kitId}', [KitsController::class, 'destroy'])->name('kit.destroy');
    Route::GET('kit/{kitId}', [KitsController::class, 'update']);

    // Route::PS('kit/{id}', KitsController::class);
    Route::GET('kitspecs', [KitsController::class, 'kitspecs'])->name('kitspecs');
    Route::GET('kitBrand', [KitsController::class, 'kitBrand'])->name('kitBrand');
    Route::GET('part', [PartsController::class, 'index'])->name('part');


    Route::GET('wheels', function () {
        return view('wheelIndex');
    });
    Route::GET('wheelIdData/{wheelId}', [WheelsController::class, 'indexWithID'])->name('wheelIdData');
    Route::GET('wheelsdata', [WheelsController::class, 'indexWithRequest'])->name('wheelsdata');
    Route::POST('wheelStore', [WheelsController::class, 'store'])->name('wheelStore');
    Route::Resource('wheel', WheelsController::class);
    Route::resource('wheeldimensions', WheelDimensionController::class);
    Route::get('dimensions', [WheelDimensionController::class,'index_dim'])->name('dimensions');
    Route::resource('wheelmodel', WheelModelController::class);
    Route::resource('wheelmaterial', WheelMaterialController::class);
    Route::resource('wheelspecs', WheelSpecController::class);
    Route::resource('relatedwheel', RelatedWheelController::class);
    Route::delete('wheel.destroy/{wheelId}', [WheelsController::class, 'destroy'])->name('wheel.destroy');


    // Route::GET('tractorsdata', [KitsController::class, 'indexWithRequest'])->name('kitsdata');




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


    Route::resource('groups', GroupController::class);
    Route::resource('subgroups', SubGroupsController::class);
    Route::resource('supplierindex', SupplierController::class);
    Route::GET('supplier', [SupplierController::class, 'get_all_sup'])->name('get_all_sup');

    Route::GET('supplier', [SupplierController::class, 'get_all_sup'])->name('supplier');


    Route::GET('Selectindex', [SupplierController::class, 'Selectindex'])->name('Selectindex');

    Route::resource('client', ClientController::class);

    Route::resource('pos', POSController::class);
    Route::GET('shop', [POSController::class, 'shop'])->name('shop');
    Route::GET('pos1', [POSController::class, 'pos1'])->name('pos1');
    Route::GET('pos1_items', [POSController::class, 'pos1_items'])->name('pos1_items');

    Route::GET('CardInfo', [POSController::class, 'CardInfo'])->name('CardInfo');
    Route::POST('printpos', [NewPosController::class, 'printpos'])->name('printpos');
    Route::POST('saveMadyonea', [POSController::class, 'saveMadyonea'])->name('saveMadyonea');
    Route::POST('saveMadyoneasup', [POSController::class, 'saveMadyoneasup'])->name('saveMadyoneasup');

    Route::GET('printBarcode/{barcodeTxt}/{name}', [POSController::class, 'printBarcode'])->name('printBarcode');
    Route::POST('saveClientPrice', [POSController::class, 'saveClientPrice'])->name('saveClientPrice');




    Route::GET('newClientInline/{telNumber}', [POSController::class, 'newClientInline'])->name('newClientInline');
    Route::GET('storeSections/{storeId}', [POSController::class, 'storeSections'])->name('storeSections');
    Route::GET('getAllDataInSection/{sectionId}/{storeId}', [POSController::class, 'getAllDataInSection'])->name('getAllDataInSection');
    Route::POST('saveNewSection', [POSController::class, 'saveNewSection'])->name('saveNewSection');
    Route::GET('Clientinvoice/{clientId}/{storeId}', [POSController::class, 'Clientinvoice'])->name('Clientinvoice');
    Route::GET('Clientinvoiceprice/{clientId}/{storeId}', [POSController::class, 'Clientinvoiceprice'])->name('Clientinvoiceprice');
    Route::POST('saveclientinvoiceprice', [POSController::class, 'saveclientinvoiceprice'])->name('saveclientinvoiceprice');
    Route::GET('getquoteData/{id}/{storeId}', [POSController::class, 'getquoteData'])->name('getquoteData');

    Route::GET('GetSections/{store}', [SectionController::class, 'GetSections'])->name('GetSections');

    Route::GET('shoptest', [POSController::class, 'shoptest'])->name('shoptest');
    Route::GET('supplierView', [SupplierController::class, 'supplierView'])->name('supplierView');
    Route::GET('Supplierinvoice/{sup_id}', [SupplierController::class, 'Supplierinvoice'])->name('Supplierinvoice');

    // Route::get('shop', function () {


    //     return view('ecommerce.shop-grid');
    // });


    Route::GET('stores', [StoreManageController::class, 'stores'])->name('stores');

    Route::GET('GetAllstores', [StoreManageController::class, 'GetAllstores'])->name('GetAllstores');
    Route::GET('gard/{storeid}', [StoreManageController::class, 'gard']);

    Route::POST('storeSend1', [StoreManageController::class, 'storeSend1'])->name('storeSend1');
    Route::GET('pricingwith_flag/{id}', [PricingController::class, 'pricingwith_flag'])->name('pricingwith_flag');

    Route::GET('pricingAll', [PricingController::class, 'pricingAll'])->name('pricingAll');
    Route::POST('saveAllPrice', [PricingController::class, 'saveAllPrice'])->name('saveAllPrice');
    Route::POST('saveAllPriceB1', [PricingController::class, 'saveAllPriceB1'])->name('saveAllPriceB1');

    Route::GET('simplePriceList', [PricingController::class, 'simplePriceList'])->name('simplePriceList');
     Route::GET('allprice', [PricingController::class, 'allprice'])->name('allprice');
        Route::POST('saveprice1', [PricingController::class, 'saveprice1'])->name('saveprice1');
        Route::POST('getprice1history', [PricingController::class, 'getprice1history'])->name('getprice1history');


    Route::GET('updatecurrcy', [PricingController::class, 'updatecurrcy'])->name('updatecurrcy');

    Route::resource('pricing', PricingController::class); // التسعيرة

    Route::resource('storeManage', StoreManageController::class); // فاتورة الشراء
    Route::POST('storeManage1', [StoreManageController::class, 'storeManage']); // فاتورة الشراء + ا
    Route::POST('storeManage2', [StoreManageController::class, 'storeManage2']); // فاتورة الشراء + التوزيع
    Route::GET('buyInvData', [StoreManageController::class, 'indexWithRequest'])->name('buyInvData');
    Route::GET('sellInvoices', [sellInvoiceController::class,'index'])->name('sellInvoices'); //  فاتورة البيع

    Route::GET('sellInvData', [sellInvoiceController::class, 'indexWithRequest'])->name('sellInvData');
    Route::GET('lastInvId', [StoreManageController::class, 'lastInvId'])->name('lastInvId');
    Route::GET('customSearch', [PartsController::class, 'customSearch'])->name('customSearch');
    Route::POST('customSearchResult', [PartsController::class, 'customSearchResult'])->name('customSearchResult');
    Route::POST('saveInv', [PartsController::class, 'saveInv'])->name('saveInv');

    Route::GET('printInvoice/{id}', [PartsController::class, 'printInvoice']);
    Route::GET('printBuyInvoice/{id}', [StoreManageController::class, 'printBuyInvoice']);
    Route::GET('storeManageItems/{id}', [StoreManageController::class, 'storeManageItems']);
    Route::POST('editBuyInv', [StoreManageController::class, 'editBuyInv'])->name('editBuyInv');
    Route::POST('editInv', [StoreManageController::class, 'editInv'])->name('editInv');
    Route::GET('buyInv/{id}', [StoreManageController::class, 'buyInv']);
    Route::GET('buyinv2', [StoreManageController::class, 'buyinvnewPage']);
    Route::GET('deleteInv/{id}', [StoreManageController::class, 'deleteInv'])->name('deleteInv');


    // Route::get('/file-import',[StoreManageController::class,'importView'])->name('import-view');
    // Route::post('importExcel',[StoreManageController::class,'import'])->name('importExcel');


    ////////////////////////////////////client_statment/////////////////////////////////////////////

    Route::GET('clientStatment', [ClientHesapController::class, 'index'])->name('clientStatment');
    Route::get('/hesssap_print/{id}/{sdate}/{edate}', [ClientHesapController::class, 'hesssap_print'])->name('hesssap_print');
    Route::get('/hessap/{id}', [ClientHesapController::class, 'hessap'])->name('hessap');

    ///////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////suplier_statment/////////////////////////////////////////////

   Route::GET('supplierStatment', [SupplierHesapController::class, 'index'])->name('supplierStatment');
   Route::get('/hesssap_print2/{id}/{sdate}/{edate}', [SupplierHesapController::class, 'hesssap_print2'])->name('hesssap_print2');
   Route::get('/hessap2/{id}', [SupplierHesapController::class, 'hessap2'])->name('hessap2');

   ///////////////////////////////////////////////////////////////////////////////////////////////

     Route::get('/audit_page', [AuditController::class, 'audit_page'])->name('audit_page');
    Route::get('/audit/getAudits', [AuditController::class, 'getAudits'])->name('audit.getAudits');

    ///////////////////////////// SALAM //////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/event', function () {

        event(new \App\Events\NewTrade('new message for new event'));
    });
    // move new parts and confirm transactions ------------------------------
    // Route::get('/all_buy_invs', [TransactionController::class, 'index']);
    Route::get('/all_data_inbox', [TransactionController::class, 'inbox_admin']);
    Route::get('all_buy_invs', function () {
        return view('store.invs_buy_transaction');
    });
    Route::GET('all_buy_invsData', [TransactionController::class, 'indexWithRequest'])->name('BuyInvData');


    Route::get('/items_inv/{id}', [TransactionController::class, 'show'])->name('items_inv');
    Route::POST('/saveTransaction', [TransactionController::class, 'send_new_parts'])->name('saveTransaction');
    Route::POST('/confirmStoreTrans', [TransactionController::class, 'confirm_store_trans'])->name('confirmStoreTrans');
    Route::POST('/confirmStore', [POSController::class, 'confirm_store'])->name('confirmStore');
    Route::POST('/refuseStoreTrans', [TransactionController::class, 'refuse_store_trans'])->name('refuseStoreTrans');
    Route::POST('/hideStoreTrans', [TransactionController::class, 'hide_store_trans'])->name('hideStoreTrans');
    Route::get('/inboxAdmin', [TransactionController::class, 'inbox_admin_history'])->name('inboxAdmin');
    Route::get('/inboxAdmin/list', [TransactionController::class, 'inbox_admin_history1'])->name('inboxAdmin.list');
    Route::get('/inboxStore', [POSController::class, 'store_inbox_history'])->name('inboxStore');
    Route::get('/inboxStore/list/{storeId}', [POSController::class, 'store_inbox_history1'])->name('inboxStore.list');

    Route::get('/itemsStore', [POSController::class, 'store_items_history'])->name('itemsStore');
    Route::get('/itemsStore/list/{storeId}', [POSController::class, 'store_items_history1'])->name('itemsStore.list');
    Route::get('/sendToOtherStore', [POSController::class, 'send_to_other_store'])->name('sendToOtherStore');

    // End move new parts and confirm transactions ------------------------------
    Route::get('/store/{id}', [StoreTransactionController::class, 'show'])->name('store');
    Route::get('/store_cover', [StoreCoverController::class, 'index']);






    Route::get('/items_inv', function () {

        event(new \App\Events\SaveTransaction('inbox'));
    });
    Route::get('/confirmStore', function () {

        event(new \App\Events\StoreTranaction('inbox'));
    });
    // Route::get('/inboxAdmin', function () {

    //     event(new \App\Events\SaveTransaction('inbox'));

    // });
    Route::get('/listen', function () {
        return view('test_event');
    });

    //////////////////////////////////Adel////////////////////////////////////////////////////////

    Route::POST('/useSupAsClient', [SupplierController::class, 'use_Sup_As_Client'])->name('useSupAsClient');
    Route::POST('/useSupAsSupplier', [SupplierController::class, 'use_client_as_suplier'])->name('useSupAsSupplier');
    Route::POST('/checkSupAsClient', [SupplierController::class, 'check_Sup_As_Client'])->name('checkSupAsClient');
    Route::resource('company', CompanyController::class);
    Route::resource('all_status', StatusController::class);
    Route::resource('supplier_bank', SupplierBankController::class);
    Route::GET('show_account_bank/{id}', [SupplierController::class, 'show_account_bank'])->name('show_account_bank');
    Route::resource('currency_type', CurrencyTypeController::class);
    Route::get('currencyType', [CurrencyTypeController::class, 'get_all_currency'])->name('currencyType');

    Route::GET('show_currency/{id}', [CurrencyTypeController::class, 'show_currency'])->name('show_currency');
    Route::GET('GetAllCurrency', [CurrencyTypeController::class, 'GetAllCurrency'])->name('GetAllCurrency');
    Route::resource('currency', CurrencyController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('brand_type', BrandTypeController::class);
    Route::resource('model', ModelController::class);
    Route::resource('series', SeriesController::class);
    Route::resource('group', GroupsController::class);
    Route::get('getsubgroups/{id}', [GroupsController::class,'getsubgroups'])->name('getsubgroups');
    Route::resource('sub_group', SubGroupsController::class);
    /////////////////////////////////UserController///////////////////////////
    Route::resource('users', UserController::class);
    Route::post('/reset_password_user', [UserController::class, 'reset_password_user'])->name('reset_password_user');
    Route::get('/update_password', [UserController::class, 'update_password'])->name('update_password');
    /////////////////////////////////Adel_new///////////////////////////

    Route::resource('gearbox', GearboxController::class);
    Route::GET('show_catalog/{id}', [SubGroupsController::class, 'show_catalog'])->name('show_catalog');
    Route::resource('catalog', CatalogController::class);
    Route::resource('drive', DriveController::class);
    Route::resource('part_quality', PartQualityController::class);
    Route::get('partQuality', [PartQualityController::class, 'get_all_p_quality'])->name('partQuality');
    Route::resource('pricing_type', PricingTypeController::class);
    Route::resource('service', ServiceController::class);
    Route::GET('servicesIndex', [ServiceController::class, 'servicesIndex'])->name('servicesIndex');
    Route::GET('serviceprint/{Sid}', [ServiceInvoiceController::class, 'printservice'])->name('service.print');
    Route::GET('servicegetItem' , [ServiceController::class, 'getItem'])->name('servicegetItem');
    Route::resource('serviceInvoice', ServiceInvoiceController::class);
    Route::GET('allclient', [ClientController::class , 'indexAll'])->name('allclient');

    Route::GET('services/{store_id}', [ServiceController::class, 'store_service'])->name('store_service');

    // Route::GET('services/{store_id}', function ($store_id) {
    //     return view('services',compact('store_id'));
    // });
    Route::resource('all_source', SourceController::class);
    Route::resource('taxes', TaxesController::class);
    Route::resource('client', ClientController::class);
    Route::GET('allTaxes', [TaxesController::class, 'indexTax'])->name('allTaxes');
    Route::resource('section', SectionController::class);
    Route::GET('/multi_section_add', [SectionController::class,'multi_section_add'])->name('save_user_role');
    Route::get('country', [CountryController::class, 'get_all_country'])->name('country');
    ////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////
    Route::resource('/role', RoleController::class);
    Route::resource('/user_role', UserRoleController::class);
    Route::get('/save_user_role', [UserRoleController::class, 'save_user_role'])->name('save_user_role');
    Route::resource('/role_perm', RolePermissionController::class);
    Route::get('/save_role_perm', [RolePermissionController::class, 'save_role_perm'])->name('save_role_perm');





    /***************************************************************************/


    Route::GET('tractors', function () {
        return view('tractorIndex');
    });
    Route::GET('tractorsdata', [TractorsController::class, 'indexWithRequest'])->name('tractorsdata');
    // Route::POST('tractorStore', [TractorsController::class, 'store'])->name('tractorStore');
    Route::Resource('tractor', TractorsController::class);
    Route::get('gearbox', [GearboxController::class, 'get_all_gearbox'])->name('gearbox');

    Route::get('get_all_drive', [DriveController::class, 'get_all_drive'])->name('get_all_drive');
    Route::get('storedrp', [StoreController::class, 'get_all_store'])->name('storedrp');

    Route::GET('tractorspecs', [TractorsController::class, 'tractorspecs'])->name('tractorspecs');
    Route::GET('tractorBrand', [TractorsController::class, 'tractorBrand'])->name('tractorBrand');
    Route::GET('tractormodel/{Bid}/{ttypeid}', [TractorsController::class, 'tractormodel'])->name('tractormodel');
    Route::GET('tractorseries/{Mid}', [TractorsController::class, 'tractorseries'])->name('tractorseries');
    Route::GET('tractorprint/{Tid}', [TractorsController::class, 'printtractor'])->name('tractor.print');
    Route::GET('gearindexdata', [GearboxController::class, 'indexdata'])->name('gearindexdata');
    Route::GET('tractorIdData/{tractorId}', [TractorsController::class, 'indexWithID'])->name('tractorIdData');
    Route::delete('tractor.destroy/{tractorId}', [TractorsController::class, 'destroy'])->name('tractor.destroy');

    Route::GET('clarks', function () {
        return view('clarkIndex');
    });
    Route::GET('clarksdata', [ClarksController::class, 'indexWithRequest'])->name('clarksdata');
    Route::Resource('clark', ClarksController::class);
    Route::GET('clarkspecs', [ClarksController::class, 'clarkspecs'])->name('clarkspecs');
    Route::GET('clarkBrand', [ClarksController::class, 'clarkBrand'])->name('clarkBrand');
    Route::GET('clarkmodel/{Bid}/{ttypeid}', [ClarksController::class, 'clarkmodel'])->name('clarkmodel');
    Route::GET('clarkseries/{Mid}', [ClarksController::class, 'clarkseries'])->name('clarkseries');
    Route::GET('clarkprint/{Tid}', [ClarksController::class, 'printclark'])->name('clark.print');
    Route::GET('clarkIdData/{clarkId}', [ClarksController::class, 'indexWithID'])->name('clarkIdData');
    Route::delete('clark.destroy/{partId}', [ClarksController::class, 'destroy'])->name('clark.destroy');

    Route::GET('equips', function () {
        return view('equipIndex');
    });
    Route::GET('equipsdata', [EquipsController::class, 'indexWithRequest'])->name('equipsdata');
    Route::Resource('equip', EquipsController::class);
    Route::GET('equipspecs', [EquipsController::class, 'equipspecs'])->name('equipspecs');
    Route::GET('equipBrand', [EquipsController::class, 'equipBrand'])->name('equipBrand');
    Route::GET('equipmodel/{Bid}/{ttypeid}', [EquipsController::class, 'equipmodel'])->name('equipmodel');
    Route::GET('equipseries/{Mid}', [EquipsController::class, 'equipseries'])->name('equipseries');
    Route::GET('equipprint/{Eid}', [EquipsController::class, 'printequip'])->name('equip.print');
    Route::GET('equipIdData/{equipId}', [EquipsController::class, 'indexWithID'])->name('equipIdData');
    Route::delete('equip.destroy/{partId}', [EquipsController::class, 'destroy'])->name('equip.destroy');


    Route::GET('partNumbers', function () {
        return view('partNumbers');
    });
    Route::GET('partNumberData', [PartNumberController::class, 'partNumberIndex'])->name('partNumberData');
    Route::GET('defectsItems', function () {
        return view('defectsItems');
    });
    
    Route::GET('defectsItems/{store_id}', [AllPartsController::class, 'defectsItems'])->name('defectsItems');

    Route::GET('defectsItemsData', [AllPartsController::class, 'defectsItemsIndex'])->name('defectsItemsData');



     //**************************Adel_start************** */


 Route::resource('/safeMoney', SafeMoneyController::class);
     Route::resource('/banksafeMoney', BankSafeMoneyController::class);
     Route::post('/add_money_bank', [BankSafeMoneyController::class, 'add_money_bank'])->name('add_money_bank');
     Route::post('/add_other_bank', [BankSafeMoneyController::class, 'add_other_bank'])->name('add_other_bank');
     Route::get('/show_safe_bank_Money_month', [BankSafeMoneyController::class, 'show_safe_bank_Money_month'])->name('show_safe_bank_Money_month');
     Route::post('/money_bank_send_store', [BankSafeMoneyController::class, 'money_bank_send_store'])->name('money_bank_send_store');


     Route::get('/get_safe_store/{id}', [SafeMoneyController::class, 'get_safe_store'])->name('get_safe_store');

     Route::get('/show_safeMoney_month', [SafeMoneyController::class, 'show_safeMoney_month'])->name('show_safeMoney_month');
     Route::post('/money_send_store', [SafeMoneyController::class, 'money_send_store'])->name('money_send_store');
     Route::post('/money_send_company', [SafeMoneyController::class, 'money_send_company'])->name('money_send_company');
     Route::post('/money_send_bank', [SafeMoneyController::class, 'money_send_bank'])->name('money_send_bank');

     Route::post('/add_money', [SafeMoneyController::class, 'add_money'])->name('add_money');
     Route::post('/add_other', [SafeMoneyController::class, 'add_other'])->name('add_other');
     Route::resource('/employee', EmployeeController::class);
     Route::resource('/employee_salary', EmployeeSalaryController::class);
     Route::get('/get_store_employee_salary/{id}', [EmployeeSalaryController::class, 'get_store_employee_salary'])->name('get_store_employee_salary');

     Route::get('/get_employee_salary', [EmployeeSalaryController::class, 'get_employee_salary'])->name('get_employee_salary');
     Route::get('/get_employee_salary_details', [EmployeeSalaryController::class, 'get_employee_salary_details'])->name('get_employee_salary_details');
     Route::get('/employee_salary_details/{id}', [EmployeeSalaryController::class, 'employee_salary_details'])->name('employee_salary_details');
     Route::post('/pay_salary', [EmployeeSalaryController::class, 'pay_salary'])->name('pay_salary');
     Route::get('/salary_history/{id}', [EmployeeSalaryController::class, 'salary_history'])->name('salary_history');

     Route::resource('/notes', NoteSafeMoneyController::class);


     Route::resource('/solfa', SolfaController::class);
     Route::get('/get_solfa_store/{id}', [SolfaController::class, 'get_solfa_store'])->name('get_solfa_store');

     Route::get('/employee_solfa_details/{id}', [SolfaController::class, 'employee_solfa_details'])->name('employee_solfa_details');
     Route::get('/employee_solfa_history/{id}', [SolfaController::class, 'employee_solfa_history'])->name('employee_solfa_history');
     Route::get('/employee_solfa_history_details/{id}', [SolfaController::class, 'employee_solfa_history_details'])->name('employee_solfa_history_details');
     Route::resource('/bank_type', BankTypeController::class);



    Route::resource('kitsCollection',kitCollectionController::class);
    Route::get('/kitInfo', [kitCollectionController::class, 'kitInfo'])->name('kitInfo');

    Route::POST('/save_kit_collection', [kitCollectionController::class, 'save_kit_collection'])->name('save_kit_collection');

          //**************************Adel_end************** */

    /************************ Acountant ///////////////////////////////// */
    Route::resource('branch', BranchController::class);
    Route::GET('/calcTree/{id}',[BranchController::class,'calcTree']);
    Route::GET('/calcTreeFinal/{id}',[BranchController::class,'calcTreeFinal']);

    Route::get('/getallbranch',[BranchController::class ,'getAll'])->name('profile');;
    Route::post('/save_branch',[BranchController::class,'save_branch']);
    Route::get('/delete_branch',[BranchController::class,'delete_branch']);
    Route::get('/branchtable',[BranchController::class,'getAllwithView']);
    Route::get('/branch_table',[BranchController::class,'getparent']);

    Route::get('/branch_child/{id}',[BranchController::class,'getbranchwithchild']);
    Route::resource('qaydtype', QaydTypeController::class);
    Route::get('/getqaydtype',[QaydTypeController::class,'getAll']);

    Route::get('/getallfinalchild',[BranchController::class,'getAllFinalChild']);
    Route::get('account/search',[QaydController::class,'search']);
    Route::get('qayd/search',[QaydController::class,'search']);
    Route::get('qayd/searchaccount',[QaydController::class,'searchaccount']);
    Route::get('qayd/trialbalance',[QaydController::class,'trialbalance']);
    Route::get('gettrialbalance',[QaydController::class,'gettrialbalance']);
    Route::resource('qayd', QaydController::class);
    Route::post('getallAccount',[QaydController::class,'getallAccount']);
    Route::post('accountstatement',[QaydController::class,'accountstatement']);

    Route::post('/automaticQayd',[QaydController::class,'AutomaticQayd']);

    Route::get('test',[QaydController::class,'test']);
    Route::get('testt/{id}',[BranchController::class,'calcTree']);
    Route::get('calcTree/{id}',[BranchController::class,'calcTree']);

    //////////////////////////////////////////////////////////////////////
     Route::get('getRassed/{id}/{type}', [BankSafeMoneyController::class,'getRassed'])->name('getRassed');

     Route::get('trialBalance', [accountingController::class,'trialBalanceIndex'])->name('trialBalance');
     Route::get('incomeStatement', [accountingController::class,'incomeStatementIndex'])->name('incomeStatement');

     Route::get('motagra', [accountingController::class,'motagra'])->name('motagra');

    Route::get('refund/{invId}/{store_id}', [POSController::class,'refund'])->name('refund');
    Route::POST('saveRefund', [POSController::class,'saveRefund'])->name('saveRefund');
      Route::get('/sales_report', [ReportController::class,'sales_report'])->name('SaleReport');
    Route::get('/sales_report_date', [ReportController::class, 'sales_report_date'])->name('sales_report_date');
        Route::get('/invoice_report_data/{invoice_number}', [ReportController::class, 'invoice_report_data'])->name('invoice_report_data');


    Route::resource('asar',ArdasarController::class);
    Route::get('GetOrderStatus/{orderId}', [ArdasarController::class,'GetOrderStatus'])->name('GetOrderStatus');
    Route::get('preSaleToInvoice/{orderId}', [ArdasarController::class,'preSaleToInvoice'])->name('preSaleToInvoice');
    Route::get('printpreSale/{lang}/{orderId}/{showFlag}', [ArdasarController::class,'printpreSale'])->name('printpreSale');

    // Route::get('printpreSale/{lang}/{orderId}', [ArdasarController::class,'printpreSale'])->name('printpreSale');
    Route::POST('ConvertPresaleInvoice', [ArdasarController::class,'ConvertPresaleInvoice'])->name('ConvertPresaleInvoice');



    Route::get('printgard', function () {
        $paperTitle="جرد المخزن";
        $recordName="المخزن";
        $recordValue="الصحراوي ";
        return view('print.print-gard' ,compact('paperTitle','recordName','recordValue'));
    });

    Route::resource('allparts',PartReportController::class);

    // Route::get('allparts', [PartReportController::class,'allparts'])->name('allparts');

    Route::get('calender', [HomeController::class,'calender'])->name('calender');
    Route::get('allpartReport', [AllPartsController::class,'allpartReport'])->name('allpartReport');
    Route::POST('/sendfromSection', [POSController::class,'sendfromSection'])->name('sendfromSection');

    Route::get('ItemLive', [ItemLiveController::class,'index'])->name('ItemLive');
    Route::get('ItemLivedata', [ItemLiveController::class,'ItemLivedata'])->name('ItemLivedata');
    Route::get('newpos', [POSController::class,'newPos'])->name('newPos');

    Route::get('allData', [POSController::class,'allData'])->name('allData');
    Route::get('posSearch', [POSController::class,'posSearch'])->name('posSearch');
    Route::get('allDataForId', [POSController::class,'allDataForId'])->name('allDataForId');

    Route::get('searchAll', [POSController::class,'searchAll'])->name('searchAll');
    Route::get('partDetails/{type}/{part_id}', [allItemsDetailsController::class,'index'])->name('index');
    Route::GET('updatepart', [allItemsDetailsController::class,'updatepart']);
    Route::GET('storeStructure/{store_id}', [allItemsDetailsController::class,'storeStructure']);

    Route::POST('/correctKit', [allItemsDetailsController::class,'correctKit'])->name('correctKit');
    Route::GET('edit_asar/{id}', [ArdasarController::class, 'edit_asar']);

    Route::GET('availablekitinstore', [kitCollectionController::class,'availablekitinstore']);
    Route::GET('availbleKits', [kitCollectionController::class,'avialbleKits']);

    Route::GET('availbleKits', [kitCollectionController::class,'availbleKits']);
    Route::GET('fakkit/{id}/{source}/{status}/{quality}', [kitCollectionController::class,'fakkit']);
    Route::POST('/savefakkit', [kitCollectionController::class,'savefakkit']);

    Route::POST('/SendToStoreNew', [POSController::class,'SendToStoreNew'])->name('SendToStoreNew');
    Route::GET('/get_pricing_ratio/{type}', [PricingController::class,'get_pricing_ratio'])->name('get_pricing_ratio');
    Route::GET('/getRassedAll/{type}/{id}', [SupplierHesapController::class,'getRassedAll'])->name('getRassedAll');



    Route::GET('clientview', [ClientController::class, 'clientview'])->name('clientview');
    Route::GET('getclientinvoice/{client_id}', [ClientController::class, 'getclientinvoice'])->name('getclientinvoice');

    Route::GET('printkitpartsection/{id}', [kitCollectionController::class, 'printkitpartsection'])->name('printkitpartsection');
    Route::GET('getlastpriceValue', [StoreManageController::class, 'getlastpriceValue'])->name('getlastpriceValue');

    Route::GET('print_sanad_abd/{type}/{id}', [POSController::class, 'print_sanad_abd'])->name('print_sanad_abd');
    Route::GET('print_sanad_sarf/{type}/{id}', [POSController::class, 'print_sanad_sarf'])->name('print_sanad_sarf');
    Route::POST('/save_sanadsarf', [POSController::class,'save_sanadsarf'])->name('save_sanadsarf');

    Route::GET('getAllBankTypes', [BankTypeController::class, 'getAll'])->name('getAllBankTypes');

    Route::GET('allPartUnderSupplier/{supplier_id}', [PartNumberController::class, 'allPartUnderSupplier'])->name('allPartUnderSupplier');
    Route::GET('allpartsUnderSupplier/{supplier_id}', [PartNumberController::class, 'allpartsUnderSupplier'])->name('allpartsUnderSupplier');
    Route::GET('partDetailsArd', [ArdasarController::class, 'partDetailsArd'])->name('partDetailsArd');

    Route::GET('newClientQuick', [POSController::class, 'newClientQuick'])->name('newClientQuick');
    Route::GET('allClients', [ClientController::class, 'indexAll'])->name('allClients');


    // Add new store
    Route::resource('storesv', StoreController::class);
    Route::GET('all_Stores', [StoreController::class, 'all_Stores']);  //to show  stores
    Route::GET('/createTable', [StoreController::class, 'createTable']);  //to c
    Route::GET('/createTable', [StoreController::class, 'createTable']);  //to create new store
    // -----------

    Route::GET('/inv_qayd_check', [QaydController::class, 'inv_qayd_check']);
    Route::GET('/inv_qayd', [QaydController::class, 'inv_qayd']);
    Route::GET('/editqayd/{id}', [QaydController::class, 'editqayd']);
    Route::GET('/print_qayd/{id}', [QaydController::class, 'print_qayd']);

    Route::POST('/save_editqayd', [QaydController::class, 'save_editqayd']);
    Route::GET('/equipPrepare', [sellInvoiceController::class, 'equipPrepare']);
    Route::GET('print_equipPaper/{id}', [sellInvoiceController::class, 'print_equipPaper']);
    Route::GET('get_stores_money/{store_id}', [HomeController::class, 'get_stores_money']);
    Route::GET('get_stores_money_view', [HomeController::class, 'get_stores_money_view']);


        Route::GET('talef/{part_id}/{source_id}/{status_id}/{quality_id}/{type_id}/{store_id}', [POSController::class, 'talef']);
        Route::POST('save_talef', [POSController::class, 'save_talef']);


    Route::GET('tawreedServices', [ServiceController::class, 'tawreedServices']);
    Route::POST('save_tawreedServices', [ServiceController::class, 'save_tawreedServices']);

    Route::GET('getCountServicesParts/{store_id}', [ServiceController::class, 'getCountServicesParts']);
    Route::GET('getCounttalabatParts/{store_id}', [POSController::class, 'getCounttalabatParts']);
    Route::GET('tawreedServicesInbox/{store_id}', [ServiceController::class, 'tawreedServicesInbox']);
    Route::GET('orderpartservice/{id}/{store_id}', [ServiceController::class, 'orderpartserviceShow']);
    Route::POST('service_part_delever', [ServiceController::class, 'service_part_delever']);
    Route::GET('orderpartservicePrint/{id}/{store_id}', [ServiceController::class, 'orderpartservicePrint']);

    Route::GET('getSectionsWithData/{store_id}', [SectionController::class, 'getSectionsWithData']);
    
    Route::GET('getStoreAmount/{type_id}/{part_id}/{source_id}/{status_id}/{quality_id}/{order_sup_id}', [allItemsDetailsController::class, 'getStoreAmount']);

    Route::POST('UpdateAmountss', [allItemsDetailsController::class, 'UpdateAmountss']);

    Route::GET('correctRefund', [allItemsDetailsController::class, 'correctRefund']);

    Route::GET('storeDailyReport/{storeid}', [allItemsDetailsController::class, 'storeDailyReport'])->name('storeDailyReport');

    Route::resource('logs', LogController::class);

    Route::POST('newLog', [LogController::class, 'newLog']);
    Route::get('notifications', [NotificationController::class, 'getNotifications']);
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
   

    Route::get('/deleteBuyTransEfragImage/{id}', [StoreManageController::class, 'deleteBuyTransEfragImage']);

    Route::POST('saveBuyTransactionEfrag', [StoreManageController::class, 'saveBuyTransactionEfrag']);

    Route::get('/presaleAdminConfirm', [ArdasarController::class, 'presaleAdminConfirm']);
    
    Route::get('/allparts', [POSController::class, 'allparts']);


    Route::get('/endYear', [accountantController::class, 'endYear']);
    Route::get('/storegardMaley', [accountantController::class, 'storegardMaley']);
    Route::get('/storegardMaley/{id}', [accountantController::class, 'onestoregardMaley']);

    Route::get('/coa', [coaController::class, 'coa'])->name('coa.index');
    Route::post('/addcoa', [coaController::class, 'addcoa'])->name('coa.add');
    Route::get('/coa/{id}', [coaController::class, 'getcoa'])->name('coa.get');
    Route::POST('/updateCoa', [CoaController::class, 'updateCoa'])->name('coa.update');
    
    Route::delete('/coa/{id}', [CoaController::class, 'deleteCoa'])->name('coa.deleteCoa');

    Route::get('/journal', [coaController::class, 'journal'])->name('coa.journal');
    Route::get('/newjournal', [coaController::class, 'newjournal'])->name('journal.add');
    
  Route::post('/journal/save', [coaController::class, 'storejournal'])->name('journal.save');
  Route::get('/journal/{journalId}', [coaController::class, 'showjournal'])->name('journal.show');
  
  Route::delete('/journals/{journalId}', [coaController::class, 'deletejournal'])->name('journals.delete');
  
  Route::get('allQayds', [coaController::class, 'allQayds'])->name('coa.qayds');
  Route::get('qayds/{id}', [coaController::class, 'showqayds'])->name('coa.showqayds');
  
 Route::get('getJournalData/{id}', [coaController::class, 'getJournalData'])->name('journals.data');
  
Route::get('GeneralLedger', [coaController::class, 'GeneralLedger'])->name('GeneralLedger');
  
Route::get('BalanceSheet', [coaController::class, 'BalanceSheet'])->name('BalanceSheet');

Route::get('manageSection/{store_id}', [SectionController::class, 'manageSection'])->name('manageSection');
 Route::get('changeManageSection', [SectionController::class, 'changeManageSection'])->name('changeManageSection');
 
 
 
 
 
  Route::post('/refund/submit', [RefundNewController::class, 'submittaswya'])->name('refund.submit');
  
 Route::GET('refundCover', [RefundNewController::class, 'refundCover']);
 Route::GET('refundBuyInv/{id}', [RefundNewController::class, 'refundBuyInv']);
 Route::get('searchAllRefund', [RefundNewController::class,'searchAllRefund'])->name('searchAllRefund');
 Route::get('partDetailsRefund/{type}/{part_id}', [RefundNewController::class,'partDetailsRefund'])->name('partDetailsRefund');
 Route::get('partDetailsRefundAll/{type}/{part_id}/{inv_id}/{order_sup_id}', [RefundNewController::class,'partDetailsRefundAll'])->name('partDetailsRefundAll');
 Route::POST('newRefundPart', [RefundNewController::class, 'newRefundPart']);
 Route::POST('newRefundPartAll', [RefundNewController::class, 'newRefundPartAll']);
 Route::GET('refundSattlement', [RefundNewController::class, 'refundSattlement']);
 Route::GET('asksrore_getdata', [NewPOSController::class, 'asksrore_getdata']);
 Route::GET('askfromStoreNew', [NewPOSController::class, 'askfromStoreNew']);
 Route::GET('getCountotherStoreAsk/{store_id}', [NewPOSController::class, 'getCountotherStoreAsk']);
 Route::GET('askStoreInbox/{store_id}', [NewPOSController::class, 'askStoreInbox']);


Route::get('pre0', [TalabeaController::class, 'index'])->name('pre0');
Route::post('/process-selected-items', [TalabeaController::class, 'processSelectedItems'])->name('process_selected_items');
Route::get('get_all_talabeas', [TalabeaController::class, 'get_all_talabeas'])->name('get_all_talabeas');
Route::post('/NewTalabea', [TalabeaController::class, 'NewTalabea'])->name('NewTalabea');

Route::get('get_all_defects', [TalabeaController::class, 'get_all_defects'])->name('get_all_defects');
Route::get('talabea/{id}', [TalabeaController::class, 'get_talabea'])->name('get_talabea');

Route::post('/UpdatetalabeaItemAmount', [TalabeaController::class, 'UpdatetalabeaItemAmount'])->name('UpdatetalabeaItemAmount');
Route::post('/DeleteTalabeaItem', [TalabeaController::class, 'DeleteTalabeaItem'])->name('DeleteTalabeaItem');

Route::GET('/addtoTalabea', [TalabeaController::class, 'addtoTalabea'])->name('addtoTalabea');

Route::GET('/yhome', [SearchController::class, 'index'])->name('yhome');
Route::GET('/inventory', [StorePartsController::class, 'inventory'])->name('inventory');
Route::GET('/inventoryData/{storeId}', [StorePartsController::class, 'inventoryData'])->name('inventoryData');
Route::GET('/itemDetails/{type_id}/{part_id}/{source_id}/{status_id}/{quality_id}', [StorePartsController::class, 'itemDetails'])->name('itemDetails');
Route::GET('/saveStoreAmount', [StorePartsController::class, 'saveStoreAmount'])->name('saveStoreAmount');
    
Route::GET('/coast', [CoastController::class, 'index'])->name('coast.index');
Route::GET('/getBuyInvoice/{id}', [CoastController::class, 'getBuyInvoice'])->name('coast.getBuyInvoice');
Route::POST('/saveNewCoast', [CoastController::class, 'saveNewCoast'])->name('coast.saveNewCoast');
Route::POST('/saveNewCoast2', [CoastController::class, 'saveNewCoast2'])->name('coast.saveNewCoast2');
Route::GET('/savenewcoastitem', [CoastController::class, 'savenewcoastitem'])->name('coast.savenewcoastitem');
Route::GET('/coastitemData/{type_id}/{part_id}/{source_id}/{status_id}/{quality_id}', [CoastController::class, 'coastitemData'])->name('coast.coastitemData');
Route::GET('/check', [StorePartsController::class, 'check'])->name('check');
Route::GET('/checkamounts', [StorePartsController::class, 'checkamounts'])->name('checkamounts');
Route::get('/part-numbers', [PartsController::class, 'importPartNumbersIndex'])->name('part_numbers.index');
Route::post('/part-numbers/import', [PartsController::class, 'importNumbers'])->name('part_numbers.import');
Route::get('/taxReport', [ReportController::class, 'taxReport'])->name('taxReport');
Route::get('/GettaxesReport', [ReportController::class, 'GettaxesReport'])->name('GettaxesReport');
Route::get('/cashflow', [AcountantReportController::class, 'cashflow'])->name('cashflow');
Route::get('/accounts-payable', [AcountantReportController::class, 'payable'])->name('payable');
Route::get('/accounts-receivable', [AcountantReportController::class, 'receivable'])->name('receivable');
Route::get('/trial-balance1', [AcountantReportController::class, 'trial'])->name('newtrialBalance');
Route::get('/newproducts', [PartsController::class, 'newProduct'])->name('newProduct');
Route::get('/newproductsData', [PartsController::class, 'newProductData'])->name('newProductData');
// unit
Route::resource('unit', UnitController::class);
Route::GET('unitVal', [UnitController::class, 'unitVal'])->name('unitVal');
Route::POST('unitValAdd', [UnitController::class, 'unitValAdd'])->name('unitValAdd');
Route::POST('unitValedit', [UnitController::class, 'unitValedit'])->name('unitValedit');
Route::POST('unitValdel', [UnitController::class, 'unitValdel'])->name('unitValdel');
Route::GET('getUnit', [UnitController::class, 'getUnit'])->name('getUnit');
Route::GET('getpart_unit/{part_id}/{type_id}', [StoreManageController::class, 'getpart_unit'])->name('getpart_unit');
Route::get('autoprint', function () {
    return view('autoprint');
});
Route::get('/getratio', function (Request $request) {
    $bigUnit = $request->query('big_unit');
    $smallUnit = $request->query('small_unit');
    // Call your helper function
    $ratiounit = getSmallUnit($bigUnit, $smallUnit);
    return response()->json(['ratiounit' => $ratiounit]);
});

});

// Google Authentication Routes
Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/get-small-unit', function (\Illuminate\Http\Request $request) {
    $selectedunitid = $request->query('unit_id');
    $smallunitid = $request->query('small_unit_id');
    // return  $selectedunitid;
    return response()->json([
        'ratio' => getSmallUnit($selectedunitid, $smallunitid),
    ]);
});
require __DIR__ . '/auth.php';
