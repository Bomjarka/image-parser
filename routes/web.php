<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('url');
});
Route::post('/', [App\Http\Controllers\HomeController::class, 'parse'])->name('parse.url');
Route::get('/parsed/{web}', [App\Http\Controllers\HomeController::class, 'parsed'])->name('parsed.url');
Route::post('/parsed/{web}/destroy', [App\Http\Controllers\HomeController::class, 'destroyParsedSite'])->name('destroy.url');
Route::get('/parsed-sites', [App\Http\Controllers\HomeController::class, 'parsedSites'])->name('parsed.sites');
