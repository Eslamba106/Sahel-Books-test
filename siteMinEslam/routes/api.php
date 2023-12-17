<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\urls\InvoiceMiddleware;
use App\Http\Controllers\Api\Auth\LoginApiController;
use App\Http\Controllers\Api\Auth\RegisterApiController;
use App\Http\Controllers\Api\admin\invoice\InvoiceApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// auth route
Route::controller(RegisterApiController::class)->group(function(){
    Route::post('register' , 'register');
});

Route::controller(LoginApiController::class)->group(function(){
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// invoice route
Route::controller(InvoiceApiController::class)->group(function(){
    Route::get('admin/invoice', 'index')->middleware('auth:sanctum');
    Route::get('admin/invoice/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/admin/invoice', 'store')->middleware('auth:sanctum');
    Route::post('/admin/invoice/item', 'store_invoice_items')->middleware('auth:sanctum');
    Route::put('/admin/invoice/',  'update')->middleware('auth:sanctum');
    Route::post('/admin/invoice/delete/{id}', 'destroy')->middleware('auth:sanctum');
    Route::get('archive', 'archive')->middleware('auth:sanctum');
    Route::post('invoice/restore/{id}', 'restore_invoice')->middleware('auth:sanctum');

});

// Route::get('archive' , function(){
//     return "Eslam badawy";
// });