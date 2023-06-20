<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('register');
});
Route::post('/register', [UserController::class, 'register']);

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'HomeController@index')->name('home.index');

    Route::group(['middleware' => ['guest']], function () {
        Route::get('/register', 'RegisterController@show')->name('register.show');
        Route::post('/register', 'RegisterController@register')->name('register.perform');
        Route::get('/login', 'LoginController@show')->name('login');
        Route::post('/login', 'LoginController@login');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');
    });

    Route::get('/shared-with-me', [FileController::class, 'sharedWithMe'])->name('file.shared');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/file-management', [FileController::class, 'index'])->middleware('auth')->name('file.management');
    Route::get('/file/{file}/download', [FileController::class, 'download'])->name('file.download');
    Route::post('/upload', [FileController::class, 'upload'])->name('file.upload');
    Route::post('/share/{file}', [FileController::class, 'share'])->name('file.share');

    Route::get('/file/{file}/download', [FileController::class, 'download'])->name('file.download');
    Route::post('/file/{file}/replace', [FileController::class, 'replace'])->name('file.replace');
    Route::get('/search', [FileController::class, 'search'])->name('file.search');

});
