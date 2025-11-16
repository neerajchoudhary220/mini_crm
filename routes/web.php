<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::controller(AuthController::class)->group(function () {
    Route::get('login', 'showLogin')->name('login');
    Route::post('login', 'login')->name('login');
});

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');
    });

    //Contact
    Route::controller(ContactController::class)->prefix('contacts')->name('contacts')->group(function () {
        Route::get('/', 'index');
        Route::get('/list', 'list')->name('.list');
        Route::get('/simple-list/{id}', 'simpleList')->name('.simplelist');
        Route::post('/', 'store')->name('.store');
        Route::get('/edit/{contact}', 'edit')->name('.edit');
        Route::post('/update/{contact}', 'update')->name('.update');
        Route::post('/merge', 'merge')->name('.merge');
    });

    //Custom Field
    Route::controller(CustomFieldController::class)->prefix('custom-fields')->name('custom.fields')->group(function () {
        Route::get('/', 'index');
        Route::get('/list', 'list')->name('.list');
        Route::post('/store', 'store')->name('.store');
        Route::delete('/{contactCustomField}', 'destroy')->name('.destroy');
        Route::post('/update/{contactCustomField}', 'update')->name('.update');
        Route::get('/items', 'fields')->name('.items');
        Route::get('/show/{contactCustomField?}', 'show')->name('.show');
    });

    Route::get('logout', function () {
        Auth::logout();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
