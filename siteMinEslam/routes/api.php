<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\urls\InvoiceMiddleware;
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


Route::controller(RegisterApiController::class)->group(function(){
    Route::post('register' , 'register');
    Route::post('login' , 'login');
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('admin/invoice/type', [InvoiceApiController::class, 'type']);
Route::get('/admin/invoice/create', [InvoiceApiController::class, 'create']);
