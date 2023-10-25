<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoAuthController;

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

Route::get('/', function () {
    return view('welcome');
})->name('home'); 

Route::get('/zoho/oauth', [ZohoAuthController::class, 'redirectToZoho'])->name('zoho.oauth');
Route::get('/zoho/auth-callback', [ZohoAuthController::class, 'handleZohoCallback'])->name('zoho.oauth.callback'); 
Route::get('/zoho/bulk-callback', [ZohoAuthController::class, 'bulkCallback'])->name('zoho.bulk.callback'); 

Route::get('/zoho/bulk-contacts', [ZohoAuthController::class, 'bulkContacts'])->name('zoho.bulk.contacts'); 
Route::get('/zoho/bulk-lead', [ZohoAuthController::class, 'bulkLeads'])->name('zoho.bulk.lead'); 

