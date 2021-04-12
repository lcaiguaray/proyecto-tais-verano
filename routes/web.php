<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // EMPRESA
    Route::prefix('empresas')->group(function() {
        Route::get('/', 'Gestion\EmpresaController@index')->name('empresas');
        Route::get('/registrar', 'Gestion\EmpresaController@create')->name('empresas.create');
        Route::post('/registrar', 'Gestion\EmpresaController@store')->name('empresas.store');
        Route::get('/actualizar/{id}', 'Gestion\EmpresaController@edit')->name('empresas.edit');
        Route::put('/actualizar/{id}', 'Gestion\EmpresaController@update')->name('empresas.update');
        Route::put('/deshabilitar/{id}', 'Gestion\EmpresaController@delete')->name('empresas.delete');
        Route::put('/habilitar/{id}', 'Gestion\EmpresaController@active')->name('empresas.active');
    });
});


