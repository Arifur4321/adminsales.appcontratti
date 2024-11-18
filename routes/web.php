<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'contractList'])->name('root');
 
Route::get('/Admin-List', [App\Http\Controllers\HomeController::class, 'contractList'])->name('contract.list');

Route::get('/Admin-List', [App\Http\Controllers\ContractController::class, 'index'])->name('contracts.index');

 
Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.list');
 
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');

Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

 
Route::post('/save-project', [App\Http\Controllers\ProjectController::class, 'saveProject']);

Route::post('/edit-variable/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('edit-variable');


// routes/web.php not needed
Route::get('/arifurtable', [App\Http\Controllers\ProjectController::class, 'showProjects']);

// for Contractlist page  in office -----------------

// super admin Login routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
 
Route::post('/users/update/{id}', [App\Http\Controllers\ContractController::class, 'updateUser']);

Route::get('/sales-details/{userId}', [App\Http\Controllers\ContractController::class, 'getSalesDetails']);
Route::post('/sales-details/activate/{id}', [App\Http\Controllers\ContractController::class, 'activateSales']);
Route::post('/sales-details/deactivate/{id}', [App\Http\Controllers\ContractController::class, 'deactivateSales']);
Route::post('/sales-details/activate', [App\Http\Controllers\ContractController::class, 'activateSales']);
Route::post('/sales-details/deactivate', [App\Http\Controllers\ContractController::class, 'deactivateSales']);
Route::get('/sales-details/activeCount/{userId}', [App\Http\Controllers\ContractController::class, 'getActiveSalesCount']);

 
Route::post('/users/activate/{id}', [App\Http\Controllers\ContractController::class, 'activateUser']);
Route::post('/users/deactivate/{id}', [App\Http\Controllers\ContractController::class, 'deactivateUser']);
Route::delete('/users/delete/{id}', [App\Http\Controllers\ContractController::class, 'deleteUser'])->name('users.delete');

Route::get('admin/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

 
// Final user registration route (this stays outside middleware)
Route::get('/register', [App\Http\Controllers\CompanyController::class, 'showRegisterPage'])->name('register');

//Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register.submit');

// Route::get('/registration-success', function () {
//     return view('auth.registration_success');
// })->name('registration.success');

Route::post('/companies/updateMaxSales/{id}', [App\Http\Controllers\ContractController::class, 'updateMaxSales']);


Route::middleware(['adminlogin'])->group(function () {

Route::get('/company/registercompany', [App\Http\Controllers\CompanyController::class, 'create'])->name('registercompany');
Route::post('/company/registercompany', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');

//Route::get('/register', [App\Http\Controllers\CompanyController::class, 'showRegisterPage'])->name('register');  
 
});



 
 
// Admin login routes
//Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');

 
// Protect the /company/registercompany and /register routes with the admin login middleware
// Route::middleware(['adminlogin'])->group(function () {
//     Route::get('/company/registercompany', [App\Http\Controllers\CompanyController::class, 'create'])->name('registercompany');
//     Route::post('/company/registercompany', [App\Http\Controllers\CompanyController::class, 'store'])->name('company.store');
//     Route::get('/register', [App\Http\Controllers\CompanyController::class, 'showRegisterPage'])->name('register'); // Ensure to use the correct controller and method
// });


Route::get('/Contract-History', [App\Http\Controllers\ContractHistoryController::class, 'showAll']);

Route::delete('/sales-list-draft/{id}', [App\Http\Controllers\ContractHistoryController::class, 'destroy']);

Route::get('/insert-mandatory-status', [App\Http\Controllers\EditContractListController::class, 'insertMandatoryStatus']);

Route::get('/check-unique', [App\Http\Controllers\SalesDetailsController::class, 'checkUnique']);

Route::get('/display-checked-products', [App\Http\Controllers\SalesDetailsController::class, 'displayChecked']);
 
Route::get('/sales.details.displayChecked', [App\Http\Controllers\SalesDetailsController::class, 'displayChecked']);

 
Route::get('/contract/save-shareable-link/{id}', [App\Http\Controllers\ContractHistoryController::class, 'saveShareableLink']);


Route::get('/contract/get-signed-pdf-url/{id}', [App\Http\Controllers\ContractHistoryController::class, 'getSignedPdfUrl']);


//-------
Route::post('/update-product-status', [App\Http\Controllers\SalesDetailsController::class, 'updateProductStatus']);

Route::get('/Sales-Details', [App\Http\Controllers\SalesDetailsController::class, 'Productshow']);

Route::get('/Sales-Lists', [App\Http\Controllers\SalesDetailsController::class, 'show']);

Route::post('/save-product-to-sales', [App\Http\Controllers\SalesDetailsController::class, 'saveProductToSales']);
 
Route::post('/save-sales-details/{id?}', [App\Http\Controllers\SalesDetailsController::class, 'save']);


Route::get('/Sales-Details/{id}', [App\Http\Controllers\SalesDetailsController::class, 'editSales']);

Route::delete('/delete-sales/{id}', [App\Http\Controllers\SalesDetailsController::class,'destroy']);


Route::get('/createpricewithupdate', [App\Http\Controllers\PriceListController::class, 'createpricewithupdate'])->name('createpricewithupdate');

Route::post('/getMandatoryFieldValues', [App\Http\Controllers\EditContractListController::class, 'getMandatoryFieldValues']);
 
Route::post('/delete-selected-product', [App\Http\Controllers\EditContractListController::class, 'deleteSelectedProduct']);

Route::post('/save-selected-product', [App\Http\Controllers\EditContractListController::class, 'saveSelectedProduct']);

Route::get('/get-product-id', [App\Http\Controllers\EditContractListController::class, 'getProductId']);

Route::get('/get-price-id', [App\Http\Controllers\EditContractListController::class, 'getPriceId'])->name('get.price.id');

Route::post('/insert-price-id', [App\Http\Controllers\EditContractListController::class, 'insertPriceId'])->name('insert.price.id');
Route::post('/delete-price-id', [App\Http\Controllers\EditContractListController::class, 'deletePriceId'])->name('delete.price.id');

Route::delete('/price-lists/{id}', [App\Http\Controllers\PriceListController::class,'destroy'])->name('price-lists.destroy');

Route::get('/edit-price/{id}', [App\Http\Controllers\PriceListController::class, 'editPrice'])->name('edit.price');

Route::post('/update-price/{id}', [App\Http\Controllers\PriceListController::class, 'updatePrice'])->name('update.price');

Route::get('/Price-List', [App\Http\Controllers\PriceListController::class, 'getpricedata'])->name('get.data');

Route::post('/save-price-list', [App\Http\Controllers\PriceListController::class, 'savePriceList'])->name('save.price.list');
 
//land on new update page when create new contract
Route::get('/createcontractwithupdatepage', [App\Http\Controllers\ContractController::class, 'createContractWithUpdatePage'])->name('createcontractwithupdatepage');

//to see checked variable from database
Route::post('/checkedVariable', [App\Http\Controllers\EditContractListController::class, 'checkedVariable']);

Route::post('/HowmanyVariable', [App\Http\Controllers\VariableListController::class,'countVariableIDs']);

// to insert into contractvariablecheckbox when variable pop up is checked 
Route::post('/insert-contract-variable', [App\Http\Controllers\EditContractListController::class, 'insertContractVariable']);
 
Route::post('/delete-contract-variable', [App\Http\Controllers\EditContractListController::class,'deleteContractVariable']);

Route::get('/Sales-Performence', [App\Http\Controllers\SalesPerformanceController::class, 'index']);
 
Route::post('/header-and-footer/save', [App\Http\Controllers\HeaderAndFooterController::class, 'save'])->name('header-and-footer.save');

Route::post('/header-and-footer/{id}', [App\Http\Controllers\HeaderAndFooterController::class, 'deleteContract'])->name('entry.delete');
 
Route::post('/header-and-footer/update/{id}', [App\Http\Controllers\HeaderAndFooterController::class, 'update'])->name('header-and-footer.update');

Route::get('/HeaderAndFooter', [App\Http\Controllers\HeaderAndFooterController::class, 'show']);

//for generate preview pdf 

Route::post('/generate-pdf', [App\Http\Controllers\createContractController::class, 'generatePDF']);
 
 Route::delete('contracts/{id}', [ App\Http\Controllers\ContractController::class, 'destroy'])->name('contracts.destroy');
 
Route::get('/edit-contract-list/{id}', [App\Http\Controllers\EditContractListController::class, 'edit']);
 
Route::get('/edit-contract-list', [App\Http\Controllers\EditContractListController::class, 'showvariable']);

Route::post('/get-pdf-sales', [App\Http\Controllers\ProductController::class, 'generatePdfforSales']);

Route::post('/saveHeaderFooter', [App\Http\Controllers\HeaderAndFooterController::class, 'saveHeaderFooter']);
 

Route::post('/get-pdf-sales-new', [App\Http\Controllers\HeaderAndFooterController::class, 'generatePdf']);


Route::post('/delete-pdf', [App\Http\Controllers\ProductController::class, 'deletePdf']);
 
Route::post('/edit-contract-list/update', [App\Http\Controllers\EditContractListController::class, 'updateContract'])->name('edit-contract-list.update');
 
Route::post('/contract-history/fetch-history', [App\Http\Controllers\ContractHistoryController::class, 'fetchHistory'])->name('contract.history.fetch');


Route::get('/download-pdf/{id}', [App\Http\Controllers\ContractHistoryController::class, 'downloadPdf'])->name('contract.history.download');

Route::post('/update-variable/{id}', [App\Http\Controllers\VariableListController::class, 'updateVariable']);
 
// for Product list page  in office 
Route::get('/Product-List', [App\Http\Controllers\ProductController::class, 'index'])->name('product.index');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'productforcreatepage'])->name('product.index');

Route::post('/save-product', [App\Http\Controllers\ProductController::class, 'saveProduct']);

//Variable-List page  to show all variable
Route::get('/Variable-List', [ App\Http\Controllers\VariableListController::class, 'index'])->name('variable.index');

// for delete variable list row
 
Route::post('/delete-contract/{id}', [App\Http\Controllers\VariableListController::class, 'deleteContract'])->name('contract.delete');

Route::post('/product-contract/{id}', [App\Http\Controllers\ProductController::class, 'deleteproduct'])->name('product.delete');

Route::post('/save-variable', [App\Http\Controllers\VariableListController::class, 'saveVariable']);

Route::get('/check-variable-name', [App\Http\Controllers\VariableListController::class, 'checkVariableName']);

//Route::post('/save-variable', [App\Http\Controllers\VariableListController::class, 'saveProduct']);
Route::get('/fetch-variables', [App\Http\Controllers\VariableListController::class, 'fetchVariables']);

//to pass variables to createcontract.blade.php
Route::get('/createcontract', [App\Http\Controllers\createContractController::class, 'show'])->name('createcontract.show');

 //header footer entries

Route::get('/createvariablecontract', [App\Http\Controllers\ContractController::class, 'show']);

// For close.io api key save in database 
 
Route::get('/AppConnections', [App\Http\Controllers\CloseController::class, 'index'])->name('close.index');
Route::post('/save-api-key', [App\Http\Controllers\CloseController::class, 'saveApiKey'])->name('save.api.key');
Route::get('/get-leads', [App\Http\Controllers\CloseController::class, 'getLeads'])->name('get.leads');
Route::post('/add-lead', [App\Http\Controllers\CloseController::class, 'addLead'])->name('add.lead');
Route::post('/add-comment/{leadId}', [App\Http\Controllers\CloseController::class, 'addComment'])->name('add.comment');

 

Route::post('/save-sms-toggle', [App\Http\Controllers\CloseController::class, 'saveSMSToggle'])->name('save.sms.toggle');

// Add this route for the Sales SMS toggle functionality
Route::post('/save-sales-sms-toggle', [App\Http\Controllers\CloseController::class, 'saveSalesSmsToggle'])->name('save.sales.sms.toggle');


Route::get('/products', [App\Http\Controllers\createContractController::class, 'productforcreatepage'])->name('createcontract.productforcreatepage');

Route::post('/createcontract', [App\Http\Controllers\CreateContractController::class, 'store'])->name('createcontract.store');

//main one for save contract
Route::post('/savecontract', [App\Http\Controllers\ContractController::class, 'savecontract']);

//for image 
Route::post('/upload', [App\Http\Controllers\ContractController::class, 'upload'])->name('ckeditor.upload');

// View Contract History
Route::get('/contracts/{id}/history', [App\Http\Controllers\ContractController::class, 'history'])->name('contracts.history');

Route::post('/save', [App\Http\Controllers\createcontractController::class, 'save']);

Route::post('/updatecontract', [App\Http\Controllers\ContractController::class, 'updatecontract']);
 
Route::get('list',[App\Http\Controllers\MemberController::class,'show']);

Route::post('/update-project/{id}', [App\Http\Controllers\ProjectController::class, 'updateProject']);

Route::get('/delete/{id}', 'App\Http\Controllers\ProjectController@deleteProject');
 
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
 
