<?php

use App\Http\Controllers\admin\BillsController;
use App\Http\Controllers\admin\BusinessController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\EstimateController;
use App\Http\Controllers\admin\ExpenseController;
use App\Http\Controllers\admin\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\LanguageController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\SubscriptionController;
use App\Http\Controllers\admin\TaxController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\VendorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReadonlyController;
use App\Http\Middleware\TransMiddleware;
use App\Http\Middleware\urls\BillsMiddleware;
use App\Http\Middleware\urls\BusinessMiddleware;
use App\Http\Middleware\urls\CategoryMiddleware;
use App\Http\Middleware\urls\CustomerMiddleware;
use App\Http\Middleware\urls\DashboardMiddleware;
use App\Http\Middleware\urls\EstimateMiddleware;
use App\Http\Middleware\urls\ExpenseMiddleware;
use App\Http\Middleware\urls\HomeMiddleware;
use App\Http\Middleware\urls\InvoiceMiddleware;
use App\Http\Middleware\urls\LanguageMiddleware;
use App\Http\Middleware\urls\PaymentMiddleware;
use App\Http\Middleware\urls\ProductMiddleware;
use App\Http\Middleware\urls\ProfileMiddleware;
use App\Http\Middleware\urls\SubscriptionMiddleware;
use App\Http\Middleware\urls\TaxMiddleware;
use App\Http\Middleware\urls\UsersMiddleware;
use App\Http\Middleware\urls\VendorMiddleware;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([TransMiddleware::class])->group(
    function () {

        // Codeigniter can use /auth/login /auth/register
        Route::get('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'register']);
        Route::get('/auth/login', [AuthController::class, 'login']); // if we redirect here
        Route::get('/auth/register', [AuthController::class, 'register']); // if we redirect here
        Route::get('/verify', [AuthController::class, 'verify_email']);
        Route::post('/register_user', [AuthController::class, 'register_user']);
        Route::post('/create-business', [AuthController::class, 'register_business']);
        Route::post('auth/log', [AuthController::class, 'log']);
        Route::get('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/stripe_payment', [AuthController::class, 'stripe_payment']);
        Route::get('/payment-success/{id}', [AuthController::class, 'payment_success']);
        Route::get('/payment-cancel/{id}', [AuthController::class, 'payment_cancel']);
        Route::post('/auth/forgot_password', [AuthController::class, 'forgot_password']);
        Route::post('/auth/resend_link', [AuthController::class, 'resend_link']);
        Route::get('/auth/test_mail', [AuthController::class, 'test_mail']);

        // // home
        Route::get('/', [AuthController::class, 'login'])->name('login')->middleware(HomeMiddleware::class);

        // // Dashboard
        Route::get('/admin/dashboard/business', [DashboardController::class, 'business'])->middleware(DashboardMiddleware::class);
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(DashboardMiddleware::class);
        Route::post('/admin/dashboard/change', [DashboardController::class, 'change'])->middleware(DashboardMiddleware::class);
        Route::get('/change_password', [DashboardController::class, 'change_password'])->middleware(DashboardMiddleware::class);

        Route::get('/readonly/export_pdf/{id}', [ReadonlyController::class, 'export_pdf']);
        Route::get('/readonly/invoice/{type}/{id}', [ReadonlyController::class, 'invoice']);
        Route::get('/readonly/estimate/{type}/{id}', [ReadonlyController::class, 'estimate']);
        Route::get('/readonly/bill/{type}/{id}', [ReadonlyController::class, 'bill']);
        Route::post('/readonly/inv', [ReadonlyController::class, 'inv']);
        Route::get('/readonly/approve/{type}/{id}', [ReadonlyController::class, 'approve']);

        // // home
        // // lang
        Route::get('/home/switch_lang/{lang}', [HomeController::class, 'switch_lang'])->middleware(HomeMiddleware::class);


        // //invoice
        Route::get('/admin/invoice/type/{status}', [InvoiceController::class, 'type'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/send/{id}', [InvoiceController::class, 'send'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/send_all', [InvoiceController::class, 'send_all'])->middleware(InvoiceMiddleware::class);
        Route::get('/admin/invoice/create/{type?}', [InvoiceController::class, 'create'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/add_product/{pid}/{cid}', [InvoiceController::class, 'add_product'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/add', [InvoiceController::class, 'add'])->middleware(InvoiceMiddleware::class);
        Route::get('/admin/invoice/details/{id}', [InvoiceController::class, 'details'])->middleware(InvoiceMiddleware::class);
        Route::get('/admin/invoice/edit/{id}/{type?}', [InvoiceController::class, 'edit'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/convert_recurring/{id}', [InvoiceController::class, 'convert_recurring'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/stop_recurring/{id}', [InvoiceController::class, 'stop_recurring'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/approve_invoice/{id}', [InvoiceController::class, 'approve_invoice'])->middleware(InvoiceMiddleware::class);
        Route::get('/admin/invoice/duplicate/{id}', [InvoiceController::class, 'duplicate'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/delete/{id}', [InvoiceController::class, 'delete'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/record_payment', [InvoiceController::class, 'record_payment'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/set_recurring/{id}', [InvoiceController::class, 'set_recurring'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/payment_bank_status', [InvoiceController::class, 'payment_bank_status'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/convert_currency/{p?}/{currency?}', [InvoiceController::class, 'convert_currency'])->middleware(InvoiceMiddleware::class);
        Route::get('/admin/invoice/convert_currency/{p?}/{currency?}', [InvoiceController::class, 'convert_currency'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/load_tax/{id}', [InvoiceController::class, 'load_tax'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/ajax_add_customer', [InvoiceController::class, 'ajax_add_customer'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/ajax_add_vendor', [InvoiceController::class, 'ajax_add_vendor'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/ajax_add_product/{type?}', [InvoiceController::class, 'ajax_add_product'])->middleware(InvoiceMiddleware::class);
        Route::post('/admin/invoice/preview', [InvoiceController::class, 'preview'])->middleware(InvoiceMiddleware::class);
        // export invoices route
        Route::get('/admin/invoice/export/', [InvoiceController::class, 'export'])->middleware(InvoiceMiddleware::class)->name('export_invoice');
        // import invoice route
        Route::get('/admin/invoice/import' , [InvoiceController::class , 'import_page']);
        Route::post('/admin/invoice/import' , [InvoiceController::class , 'import']);
        // //customer
        Route::get('/admin/customer', [CustomerController::class, 'index'])->middleware(CustomerMiddleware::class);
        Route::post('/admin/customer/load_customer_info/{id}', [CustomerController::class, 'load_customer_info'])->middleware(CustomerMiddleware::class);
        Route::get('admin/customer/load_currency/{id}', [CustomerController::class, 'load_currency'])->middleware(CustomerMiddleware::class);
        Route::post('/admin/customer/add', [CustomerController::class, 'add'])->middleware(CustomerMiddleware::class);
        Route::get('/admin/customer/edit/{id}', [CustomerController::class, 'edit'])->middleware(CustomerMiddleware::class);
        Route::get('/admin/customer/active/{id}', [CustomerController::class, 'active'])->middleware(CustomerMiddleware::class);
        Route::get('/admin/customer/deactive/{id}', [CustomerController::class, 'deactive'])->middleware(CustomerMiddleware::class);
        Route::post('/admin/customer/delete/{id}', [CustomerController::class, 'delete'])->middleware(CustomerMiddleware::class);


        // //products
        Route::get('/admin/product/all/{type}', [ProductController::class, 'all'])->middleware(ProductMiddleware::class);
        Route::post('/admin/product/add', [ProductController::class, 'add'])->middleware(ProductMiddleware::class);
        Route::get('/admin/product/edit/{id}/{type}', [ProductController::class, 'edit'])->middleware(ProductMiddleware::class);
        Route::post('/admin/product/delete/{id}', [ProductController::class, 'delete'])->middleware(ProductMiddleware::class);

        // //expense
        Route::get('/admin/expense', [ExpenseController::class, 'index'])->middleware(ExpenseMiddleware::class);
        Route::post('/admin/expense/add', [ExpenseController::class, 'add'])->middleware(ExpenseMiddleware::class);
        Route::post('/admin/expense/record_payment', [ExpenseController::class, 'record_payment'])->middleware(ExpenseMiddleware::class);
        Route::get('/admin/expense/edit/{id}', [ExpenseController::class, 'edit'])->middleware(ExpenseMiddleware::class);
        Route::get('/admin/expense/download/{id}', [ExpenseController::class, 'download'])->middleware(ExpenseMiddleware::class);
        Route::post('/admin/expense/active/{id}', [ExpenseController::class, 'active'])->middleware(ExpenseMiddleware::class);
        Route::post('/admin/expense/deactive/{id}', [ExpenseController::class, 'deactive'])->middleware(ExpenseMiddleware::class);
        Route::post('/admin/expense/delete/{id}', [ExpenseController::class, 'delete'])->middleware(ExpenseMiddleware::class);
        Route::get('/admin/expense/details/{id}', [ExpenseController::class, 'details'])->middleware(ExpenseMiddleware::class);

        //vendor
        Route::get('/admin/vendor', [VendorController::class, 'index'])->middleware(VendorMiddleware::class);
        Route::post('/admin/vendor/add', [VendorController::class, 'add'])->middleware(VendorMiddleware::class);
        Route::get('/admin/vendor/load_customer_info/{id}', [VendorController::class, 'load_customer_info'])->middleware(VendorMiddleware::class);
        Route::get('/admin/vendor/edit/{id}', [VendorController::class, 'edit'])->middleware(VendorMiddleware::class);
        Route::get('/admin/vendor/download/{id}', [VendorController::class, 'download'])->middleware(VendorMiddleware::class);
        Route::post('/admin/vendor/active/{id}', [VendorController::class, 'active'])->middleware(VendorMiddleware::class);
        Route::post('/admin/vendor/deactive/{id}', [VendorController::class, 'deactive'])->middleware(VendorMiddleware::class);
        Route::post('/admin/vendor/delete/{id}', [VendorController::class, 'delete'])->middleware(VendorMiddleware::class);

        //category
        Route::get('/admin/category', [CategoryController::class, 'index'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/category/add', [CategoryController::class, 'add'])->middleware(CategoryMiddleware::class);
        Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/category/active/{id}', [CategoryController::class, 'active'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/category/deactive/{id}', [CategoryController::class, 'deactive'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/category/delete/{id}', [CategoryController::class, 'delete'])->middleware(CategoryMiddleware::class);

        //tax
        Route::get('/admin/tax', [TaxController::class, 'index'])->middleware(TaxMiddleware::class);
        Route::get('/admin/tax/load_tax_sub_kind/{id}', [TaxController::class, 'load_tax_sub_kind'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/tax/add', [TaxController::class, 'add'])->middleware(CategoryMiddleware::class);
        Route::get('/admin/tax/edit/{id}', [TaxController::class, 'edit'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/tax/add_type', [TaxController::class, 'add_type'])->middleware(CategoryMiddleware::class);
        Route::get('/admin/tax/edit_type/{id}', [TaxController::class, 'edit_type'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/tax/delete/{id}', [TaxController::class, 'delete'])->middleware(CategoryMiddleware::class);
        Route::post('/admin/tax/delete_type/{id}', [TaxController::class, 'delete_type'])->middleware(CategoryMiddleware::class);
        Route::get('/admin/tax/settings', [TaxController::class, 'settings'])->middleware(TaxMiddleware::class);
        Route::post('/admin/tax/edit_tax_settings', [TaxController::class, 'edit_tax_settings'])->middleware(TaxMiddleware::class);

        //estimate
        Route::get('/admin/estimate', [EstimateController::class, 'index'])->middleware(EstimateMiddleware::class);
        Route::get('/admin/estimate/create', [EstimateController::class, 'create'])->middleware(EstimateMiddleware::class);
        Route::post('/admin/estimate/add', [EstimateController::class, 'add'])->middleware(EstimateMiddleware::class);
        Route::get('/admin/estimate/edit/{id}', [EstimateController::class, 'edit'])->middleware(EstimateMiddleware::class);
        Route::get('/admin/estimate/details/{id}', [EstimateController::class, 'details'])->middleware(EstimateMiddleware::class);
        Route::post('/admin/estimate/convert/{id}', [EstimateController::class, 'convert'])->middleware(EstimateMiddleware::class);
        Route::post('/admin/estimate/send/{id}', [EstimateController::class, 'send'])->middleware(EstimateMiddleware::class);
        Route::post('/admin/estimate/delete/{id}', [EstimateController::class, 'delete'])->middleware(EstimateMiddleware::class);

        // //bills
        Route::get('/admin/bills', [BillsController::class, 'index'])->middleware(BillsMiddleware::class);
        Route::get('/admin/bills/create', [BillsController::class, 'create'])->middleware(BillsMiddleware::class);
        Route::post('/admin/bills/add', [BillsController::class, 'add'])->middleware(BillsMiddleware::class);
        Route::get('/admin/bills/edit/{id}', [BillsController::class, 'edit'])->middleware(BillsMiddleware::class);
        Route::get('/admin/bills/details/{id}', [BillsController::class, 'details'])->middleware(BillsMiddleware::class);
        Route::post('/admin/bills/send/{id}', [BillsController::class, 'send'])->middleware(BillsMiddleware::class);
        Route::post('/admin/bills/record_payment', [BillsController::class, 'record_payment'])->middleware(BillsMiddleware::class);
        Route::post('/admin/bills/delete/{id}', [BillsController::class, 'delete'])->middleware(BillsMiddleware::class);

        // // profile
        Route::get('/admin/profile', [ProfileController::class, 'index'])->middleware(ProfileMiddleware::class);
        Route::get('/admin/profile/switch_business/{uid}', [ProfileController::class, 'switch_business'])->middleware(ProfileMiddleware::class);
        Route::post('/admin/profile/update', [ProfileController::class, 'update'])->middleware(ProfileMiddleware::class);
        Route::get('/admin/profile/change_password', [ProfileController::class, 'change_password'])->middleware(ProfileMiddleware::class);

        // // business
        Route::get('/admin/business', [BusinessController::class, 'index'])->middleware(BusinessMiddleware::class);
        Route::get('/admin/business/edit/{id}', [BusinessController::class, 'edit'])->middleware(BusinessMiddleware::class);
        Route::post('/admin/business/add', [BusinessController::class, 'add'])->middleware(BusinessMiddleware::class);
        Route::post('/admin/business/set_primary/{id}', [BusinessController::class, 'set_primary'])->middleware(BusinessMiddleware::class);

        // subscription
        Route::get('/admin/subscription', [SubscriptionController::class, 'index'])->middleware(SubscriptionMiddleware::class);
        Route::get('/admin/subscription/upgrade/{slug?}/{billing_type?}/{status?}', [SubscriptionController::class, 'upgrade'])->middleware(SubscriptionMiddleware::class);
        Route::get('/admin/subscription/payment_success/{billing_type}/{package_id}/{payment_id?}', [SubscriptionController::class, 'payment_success'])->middleware(SubscriptionMiddleware::class);
        Route::get('/admin/subscription/payment_cancel/{billing_type}/{package_id}/{payment_id?}', [SubscriptionController::class, 'payment_cancel'])->middleware(SubscriptionMiddleware::class);


        // payments
        Route::get('/admin/payment', [PaymentController::class, 'index'])->middleware(PaymentMiddleware::class);
        Route::post('/admin/payment/update', [PaymentController::class, 'update'])->middleware(PaymentMiddleware::class);
        Route::post('/admin/payment/offline_payment', [PaymentController::class, 'offline_payment'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/lists', [PaymentController::class, 'lists'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/receipt/{id}', [PaymentController::class, 'receipt'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/online/{type}/{invoice_id}', [PaymentController::class, 'online'])->middleware(PaymentMiddleware::class);
        Route::post('/admin/payment/online/{type}/{invoice_id}', [PaymentController::class, 'online'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/user', [PaymentController::class, 'user'])->middleware(PaymentMiddleware::class);
        Route::post('/admin/payment/user_update', [PaymentController::class, 'user_update'])->middleware(PaymentMiddleware::class);
        Route::post('/admin/payment/stripe_payment/{invoice_id}/{amount}/{cus_amount?}', [PaymentController::class, 'stripe_payment'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/payment_success/{invoice_id}/{amount?}/{cus_amount?}', [PaymentController::class, 'payment_success'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/payment_cancel/{invoice_id}/{amount?}/{cus_amount?}', [PaymentController::class, 'payment_cancel'])->middleware(PaymentMiddleware::class);
        Route::get('/admin/payment/success', [PaymentController::class, 'success'])->middleware(PaymentMiddleware::class);

        // discount
        Route::get('/admin/language', [LanguageController::class, 'index'])->middleware(LanguageMiddleware::class);
        Route::get('/admin/language/values/{type}/{slug}', [LanguageController::class, 'values'])->middleware(LanguageMiddleware::class);
        Route::post('/admin/language/add', [LanguageController::class, 'add'])->middleware(LanguageMiddleware::class);
        Route::get('/admin/language/edit/{id}', [LanguageController::class, 'edit'])->middleware(LanguageMiddleware::class);
        Route::get('/admin/language/active/{id}', [LanguageController::class, 'active'])->middleware(LanguageMiddleware::class);
        Route::get('/admin/language/deactive/{id}', [LanguageController::class, 'deactive'])->middleware(LanguageMiddleware::class);
        Route::post('/admin/language/delete/{id}', [LanguageController::class, 'delete'])->middleware(LanguageMiddleware::class);
        Route::post('/admin/language/update_values/{type}/{page?}', [LanguageController::class, 'update_values'])->middleware(LanguageMiddleware::class);
        Route::post('/admin/language/add_value', [LanguageController::class, 'add_value'])->middleware(LanguageMiddleware::class);

        // users
        Route::get('/admin/users', [UsersController::class, 'index'])->middleware(UsersMiddleware::class);
        Route::post('/admin/users/status_action/{id}', [UsersController::class, 'status_action'])->middleware(UsersMiddleware::class);
        Route::get('/admin/users/status_action/{type}/{id}', [UsersController::class, 'status_action'])->middleware(UsersMiddleware::class);
        Route::post('/admin/users/delete/{id}', [UsersController::class, 'delete'])->middleware(UsersMiddleware::class);
        Route::get('/section/{id}', [InvoiceController::class, 'get_currency']);
    }
);
