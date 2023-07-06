<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('domainfilter');
// Route::post('/update', [App\Http\Controllers\DomainController::class, 'update'])->name('domain.update')->middleware('domainfilter');
// Route::post('/domains', [App\Http\Controllers\DomainController::class, 'store'])->name('domain.store')->middleware('domainfilter');

Route::any('/maintenance', [DomainController::class, 'index'])->where('any', '.*');


Route::domain('localhost')->group(function () {
    Auth::routes();
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/update/{projectId}', [App\Http\Controllers\DomainController::class, 'update'])->name('domain.update');
    Route::post('/domains', [App\Http\Controllers\DomainController::class, 'store'])->name('domain.store');
});
// Auth::routes();


Route::any('/{any}', [DomainController::class, 'index'])->where('any', '.*')->middleware('domainfilter');

