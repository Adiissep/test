<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Routes untuk Master Items
    Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index']);
    Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
    Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
    Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);
    Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
    Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);
    Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);
    Route::get('/master-items/export-excel', [App\Http\Controllers\MasterItemsController::class, 'exportExcel']);

    // Routes untuk Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/search', [CategoryController::class, 'search']);
    Route::get('/categories/form/{method}/{id?}', [CategoryController::class, 'formView']);
    Route::post('/categories/form/{method}/{id?}', [CategoryController::class, 'formSubmit']);
    Route::get('/categories/view/{kode}', [CategoryController::class, 'singleView']);
    Route::get('/categories/delete/{id}', [CategoryController::class, 'delete']);
    Route::get('/categories/export-pdf/{kode}', [CategoryController::class, 'exportPdf']);
});
