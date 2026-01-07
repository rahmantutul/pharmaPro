<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DemurrageController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LeafController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('clear-compiled');
    Artisan::call('optimize:clear');
    return redirect()->back()->with('success', 'Cache cleared successfully!');
})->name('cache.clear');

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('admin.login');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');


Route::middleware('auth:admin')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    // Start User/Admin Routs 
    Route::get('/admins', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admins', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admins/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admins/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admins/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admins/data', [AdminController::class, 'getAdminsData'])->name('admin.data');

   //--------------Start RoleRoute-------------------------------------
    Route::get('/role/index/{id?}', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create',[RoleController::class, 'create'])->name('role.create');
    Route::post('/role/store',[RoleController::class, 'store'])->name('role.store');
    Route::get('/role/edit/{id}',[RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/update/{id}',[RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/destroy/{id}',[RoleController::class, 'destroy']);
    Route::post('/role-access/store',[RoleController::class, 'access_store'])->name('role.access.store');

   //--------------Start SettingsRoute-------------------------------------
    Route::get('/setting/index', [SettingController::class, 'setting_index'])->name('settings.index');
    Route::post('/setting/update', [SettingController::class, 'setting_update'])->name('settings.update');

    //--------------Start CustomerRoute-------------------------------------
    Route::group(['prefix'=>'customer','as'=>'customer.'], function(){
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        Route::get('/history/{id}', [CustomerController::class, 'history'])->name('history');
        Route::get('data', [CustomerController::class, 'getCustomersData'])->name('data');
        Route::put('transaction', [CustomerController::class, 'transaction'])->name('transaction');
    });

    //--------------Start SupplierRoute-------------------------------------
    Route::group(['prefix'=>'supplier','as'=>'supplier.'], function(){
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::put('/update/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [SupplierController::class, 'destroy'])->name('destroy');
        Route::get('/history/{id}', [SupplierController::class, 'history'])->name('history');
        Route::get('data', [SupplierController::class, 'getSuppliersData'])->name('data');
        Route::put('transaction', [SupplierController::class, 'transaction'])->name('transaction');
    });

    //--------------Start VendorRoute-------------------------------------
    Route::group(['prefix'=>'vendor','as'=>'vendor.'], function(){
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::put('/update/{id}', [VendorController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [VendorController::class, 'destroy'])->name('destroy');
        Route::get('data', [VendorController::class, 'getVendorsData'])->name('data');
    });

    //--------------Start ExpenseRoute-------------------------------------
    Route::group(['prefix'=>'expense','as'=>'expense.'], function(){
        // Expense Category Route
        Route::get('category/', [ExpenseCategoryController::class, 'index'])->name('category.index');
        Route::post('category/', [ExpenseCategoryController::class, 'store'])->name('category.store');
        Route::put('category/update/{id}', [ExpenseCategoryController::class, 'update'])->name('category.update');
        Route::delete('category/destroy/{id}', [ExpenseCategoryController::class, 'destroy'])->name('category.destroy');
        Route::get('category/data', [ExpenseCategoryController::class, 'getExpenseCategoryData'])->name('category.data');

        // Expense Route 
        Route::get('/', [ExpenseController::class, 'index'])->name('index');
        Route::post('/', [ExpenseController::class, 'store'])->name('store');
        Route::put('/update/{id}', [ExpenseController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [ExpenseController::class, 'destroy'])->name('destroy');
        Route::get('data', [ExpenseController::class, 'getExpensesData'])->name('data');
    });

    //--------------Start PaymentMethodRoute-------------------------------------
    Route::group(['prefix'=>'method','as'=>'method.'], function(){
        Route::get('/', [PaymentMethodController::class, 'index'])->name('index');
        Route::post('/', [PaymentMethodController::class, 'store'])->name('store');
        Route::put('/update/{id}', [PaymentMethodController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [PaymentMethodController::class, 'destroy'])->name('destroy');
        Route::get('data', [PaymentMethodController::class, 'getPaymentMethodsData'])->name('data');
    });
    
    Route::group(['prefix'=>'medicine','as'=>'medicine.'], function(){
        Route::get('/med/{id?}', [MedicineController::class, 'index'])->name('index');
        Route::post('/', [MedicineController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MedicineController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [MedicineController::class, 'destroy'])->name('destroy');
        route::get('/search-medicines', [MedicineController::class, 'search'])->name('search');
        route::get('/single-medicine-details/{id?}', [MedicineController::class, 'getMedicineDetails'])->name('getMedicineDetails');

        Route::group(['prefix'=>'category','as'=>'category.'], function(){
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
            Route::get('data', [CategoryController::class, 'getCategoryData'])->name('data');
        });
        Route::group(['prefix'=>'unit','as'=>'unit.'], function(){
            Route::get('/', [UnitController::class, 'index'])->name('index');
            Route::post('/', [UnitController::class, 'store'])->name('store');
            Route::put('/update/{id}', [UnitController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [UnitController::class, 'destroy'])->name('destroy');
            Route::get('data', [UnitController::class, 'getUnitData'])->name('data');
        });
        Route::group(['prefix'=>'leaf','as'=>'leaf.'], function(){
            Route::get('/', [LeafController::class, 'index'])->name('index');
            Route::post('/', [LeafController::class, 'store'])->name('store');
            Route::put('/update/{id}', [LeafController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [LeafController::class, 'destroy'])->name('destroy');
            Route::get('data', [LeafController::class, 'getLeafData'])->name('data');
        });
        Route::group(['prefix'=>'type','as'=>'type.'], function(){
            Route::get('/', [TypeController::class, 'index'])->name('index');
            Route::post('/', [TypeController::class, 'store'])->name('store');
            Route::put('/update/{id}', [TypeController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [TypeController::class, 'destroy'])->name('destroy');
            Route::get('data', [TypeController::class, 'getTypeData'])->name('data');
        });

    });

    Route::group(['prefix'=>'purchase','as'=>'purchase.'], function(){
        Route::group(['prefix'=>'order','as'=>'order.'], function(){
            Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
            Route::get('/create', [PurchaseOrderController::class, 'create'])->name('create');
            Route::post('/store', [PurchaseOrderController::class, 'store'])->name('store');
            Route::get('/destroy/{id}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
            Route::get('data', [PurchaseOrderController::class, 'getCategoryData'])->name('data');
            
            // Invoice Section 
            Route::get('/invoice/make/{id}', [PurchaseOrderController::class, 'invoice'])->name('invoice');
            Route::post('/invoice/store', [PurchaseOrderController::class, 'invoice_store'])->name('invoice.store');
            Route::get('/invoice/list', [PurchaseOrderController::class, 'invoice_list'])->name('invoice.list');
            Route::get('/invoice/print/{id}', [PurchaseOrderController::class, 'print_invoice'])->name('print.invoice');
            Route::get('direct/invoice', [PurchaseOrderController::class, 'direct_invoice'])->name('direct.invoice');
            Route::get('download/invoice/{id}', [PurchaseOrderController::class, 'download_invoice'])->name('invoice.download');
        });
    });
    Route::group(['prefix'=>'sales','as'=>'sales.'], function(){
        Route::get('/create', [SalesController::class, 'filter'])->name('medicines.filter');
        Route::get('/cart', [SalesController::class, 'addToCart'])->name('cart.add');
        
        Route::group(['prefix'=>'order','as'=>'order.'], function(){
            Route::get('/index', [SalesController::class, 'index'])->name('index');
            Route::get('/create', [SalesController::class, 'create'])->name('create');
            Route::post('/store', [SalesController::class, 'store'])->name('store');
            Route::get('/print_invoice', [SalesController::class, 'print_invoice'])->name('invoice');
            Route::get('/download_invoice', [SalesController::class, 'downloadInvoicePDF'])->name('invoice.download');
            Route::get('/details/{id}', [SalesController::class, 'details'])->name('details');
            Route::get('/destroy/{id}', [SalesController::class, 'destroy'])->name('destroy');
        });
    });

    Route::group(['prefix'=>'report','as'=>'report.'], function(){
        Route::get('/sales_report', [ReportController::class, 'sales_report'])->name('sales');
        Route::get('/purchase_report', [ReportController::class, 'purchase_report'])->name('purchase');
        Route::get('/customer_due_report', [ReportController::class, 'customer_due_report'])->name('customer_due');
        Route::get('/supplier_due_report', [ReportController::class, 'supplier_due_report'])->name('supplier_due');
    });
    //--------------End ReportController-------------------------------------

    Route::group(['prefix'=>'return','as'=>'return.'], function(){
        
        // Sales Return Routes
        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [ReturnController::class, 'sales_return_index'])->name('index');
            Route::get('/create', [ReturnController::class, 'sales_return_create'])->name('create');
            Route::post('/store', [ReturnController::class, 'sales_return_store'])->name('store');
            Route::get('/{id}', [ReturnController::class, 'sales_return_show'])->name('show');
            Route::delete('/{id}', [ReturnController::class, 'sales_return_destroy'])->name('destroy');
        });

        // Purchase Return Routes
        Route::prefix('purchase')->name('purchase.')->group(function () {
            Route::get('/', [ReturnController::class, 'purchase_return_index'])->name('index');
            Route::get('/create', [ReturnController::class, 'purchase_return_create'])->name('create');
            Route::post('/store', [ReturnController::class, 'purchase_return_store'])->name('store');
            Route::get('/{id}', [ReturnController::class, 'purchase_return_show'])->name('show');
            Route::delete('/{id}', [ReturnController::class, 'purchase_return_destroy'])->name('destroy');
        });

        // API Routes for AJAX calls
        Route::get('/statistics', [ReturnController::class, 'getReturnStatistics'])->name('statistics');
        Route::post('/check-eligibility', [ReturnController::class, 'checkReturnEligibility'])->name('check.eligibility');
    });

    // API Route for stock check
    Route::get('/api/medicine/stock/{id}', function($id) {
        $stock = \App\Models\Stock::where('medicineId', $id)->sum('qty');
        return response()->json(['stock' => $stock]);
    })->middleware('auth');
    //--------------End ReturnController-------------------------------------
    
    Route::group(['prefix'=>'stock','as'=>'stock.'], function(){

        Route::get('/expired_medicine', [StockController::class, 'expired_medicine'])->name('expired_medicine');
        Route::get('/upcoming_expired', [StockController::class, 'upcoming_expired'])->name('upcoming_expired');
        Route::get('/low_stock', [StockController::class, 'low_stocks'])->name('low_stock');
        Route::get('/in_stock', [StockController::class, 'in_stocks'])->name('in_stock');
        Route::get('/stock_out', [StockController::class, 'stock_out'])->name('stock_out');
    });

    Route::group(['prefix'=>'demurrage','as'=>'demurrage.'], function(){
        Route::get('/', [DemurrageController::class, 'index'])->name('index');
        Route::get('/create', [DemurrageController::class, 'create'])->name('create');
        Route::post('/', [DemurrageController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DemurrageController::class, 'edit'])->name('edit');
        Route::post('/update', [DemurrageController::class, 'update'])->name('update');
        Route::get('/destroy/{id}', [DemurrageController::class, 'destroy'])->name('destroy');
    });
    //--------------End Demurrage-------------------------------------
});