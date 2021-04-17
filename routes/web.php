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
        // DATATABLE
        Route::get('/datatable', 'Gestion\EmpresaController@datatable_empresas')->name('empresas.datatable_datos');
    });

    // USUARIOS
    Route::prefix('usuarios')->group(function() {
        Route::get('/', 'Gestion\UsuarioController@index')->name('usuarios');
        Route::get('/registrar', 'Gestion\UsuarioController@create')->name('usuarios.create');
        Route::post('/registrar', 'Gestion\UsuarioController@store')->name('usuarios.store');
        Route::get('/actualizar/{id}', 'Gestion\UsuarioController@edit')->name('usuarios.edit');
        Route::put('/actualizar/{id}', 'Gestion\UsuarioController@update')->name('usuarios.update');
        Route::put('/deshabilitar/{id}', 'Gestion\UsuarioController@delete')->name('usuarios.delete');
        Route::put('/habilitar/{id}', 'Gestion\UsuarioController@active')->name('usuarios.active');
        // DATATABLE
        Route::get('/datatable', 'Gestion\UsuarioController@datatable_usuarios')->name('usuarios.datatable_datos');
    });

    // USUARIOS
    Route::prefix('procesos')->group(function() {
        Route::get('/', 'Gestion\ProcesoController@empresa')->name('procesos');
        Route::get('/empresa/{id}', 'Gestion\ProcesoController@index')->name('procesos.index');
        Route::post('/registrar/{id}', 'Gestion\ProcesoController@store')->name('procesos.store');
        Route::put('/actualizar/{id}', 'Gestion\ProcesoController@update')->name('procesos.update');
        Route::put('/deshabilitar/{id}', 'Gestion\ProcesoController@delete')->name('procesos.delete');
        Route::put('/habilitar/{id}', 'Gestion\ProcesoController@active')->name('procesos.active');
        // DATATABLE
        Route::get('/datatable/empresas', 'Gestion\ProcesoController@datatable_empresas')->name('procesos.datatable_empresas');
        Route::get('/datatable/{id}', 'Gestion\ProcesoController@datatable_procesos')->name('procesos.datatable_datos');
    });
});


