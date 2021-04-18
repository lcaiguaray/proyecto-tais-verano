<?php

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

Auth::routes(['register' => false]);

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

        Route::prefix('componentes')->group(function() {
            Route::get('/{id}', 'Gestion\EmpresaController@componentes')->name('empresas.componentes');

            // MAPA DE PROCESOS
            Route::prefix('mapa-procesos')->group(function() {
                Route::get('/{id}', 'Componentes\MapaProcesoController@index')->name('mapa_procesos');
                Route::post('/registrar/{id}', 'Componentes\MapaProcesoController@store')->name('mapa_procesos.store');
                Route::put('/actualizar/{id}', 'Componentes\MapaProcesoController@update')->name('mapa_procesos.update');
                Route::put('/deshabilitar/{id}', 'Componentes\MapaProcesoController@delete')->name('mapa_procesos.delete');
                Route::put('/habilitar/{id}', 'Componentes\MapaProcesoController@active')->name('mapa_procesos.active');
                // DATATABLE
                Route::get('/datatable/{id}', 'Componentes\MapaProcesoController@datatable_mapa_procesos')->name('mapa_procesos.datatable_datos');
            });
    
            // PROCESOS
            Route::prefix('procesos')->group(function() {
                Route::get('/{id}', 'Componentes\ProcesoController@index')->name('procesos');
                Route::post('/registrar', 'Componentes\ProcesoController@store')->name('procesos.store');
                Route::put('/actualizar/{id}', 'Componentes\ProcesoController@update')->name('procesos.update');
                Route::put('/deshabilitar/{id}', 'Componentes\ProcesoController@delete')->name('procesos.delete');
                Route::put('/habilitar/{id}', 'Componentes\ProcesoController@active')->name('procesos.active');
                // DATATABLE
                Route::get('/datatable/{id}', 'Componentes\ProcesoController@datatable_procesos')->name('procesos.datatable_datos');
    
                Route::prefix('subprocesos')->group(function() {
                    Route::get('/{id}', 'Componentes\SubprocesoController@index')->name('subprocesos');
                    Route::post('/registrar/{id}', 'Componentes\SubprocesoController@store')->name('subprocesos.store');
                    Route::put('/actualizar/{id}', 'Componentes\SubprocesoController@update')->name('subprocesos.update');
                    Route::put('/deshabilitar/{id}', 'Componentes\SubprocesoController@delete')->name('subprocesos.delete');
                    Route::put('/habilitar/{id}', 'Componentes\SubprocesoController@active')->name('subprocesos.active');
                    // DATATABLE
                    Route::get('/datatable/{id}', 'Componentes\SubprocesoController@datatable_subprocesos')->name('subprocesos.datatable_datos');
                });
            });
        });
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
});


