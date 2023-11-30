<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ZohoAuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\FieldController;

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
    return view('home');
})->name('home');



Route::get('/modules', [ModuleController::class, 'index'])->name('zoho.modules');
Route::get('/modules/request/{moduleId}', [ModuleController::class, 'makeRequest'])->name('zoho.modules.request');
Route::get('/modules/request/status/{moduleId}', [ModuleController::class, 'checkRequestStatus'])->name('zoho.modules.request.status');
Route::get('/modules/request/download/{moduleId}', [ModuleController::class, 'downloadRequest'])->name('zoho.modules.request.download');

Route::get('/modules/sync-modules', [ModuleController::class, 'syncModules'])->name('zoho.modules.sync');

Route::get('/fields', [FieldController::class, 'index'])->name('zoho.fields');
Route::get('/sync-fields/{moduleId}', [FieldController::class, 'syncfields'])->name('zoho.fields.sync');


Route::get('/zoho/oauth', [ZohoAuthController::class, 'redirectToZoho'])->name('zoho.oauth');
Route::get('/zoho/auth-callback', [ZohoAuthController::class, 'handleZohoCallback'])->name('zoho.oauth.callback'); 
Route::get('/zoho/bulk-callback', [ZohoAuthController::class, 'bulkCallback'])->name('zoho.bulk.callback'); 

Route::get('/zoho/bulk-contacts', [ZohoAuthController::class, 'bulkContacts'])->name('zoho.bulk.contacts'); 
Route::get('/zoho/bulk-lead', [ZohoAuthController::class, 'bulkLeads'])->name('zoho.bulk.lead'); 

