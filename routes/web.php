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

            // MAPAS ESTRATÉGICOS
            Route::prefix('estrategias')->group(function() {
                Route::get('/{id}', 'Componentes\EstrategiaController@index')->name('estrategias');
                Route::get('/registrar/{id}', 'Componentes\EstrategiaController@create')->name('estrategias.create');
                Route::post('/registrar', 'Componentes\EstrategiaController@store')->name('estrategias.store');
                Route::get('/actualizar/{id}', 'Componentes\EstrategiaController@edit')->name('estrategias.edit');
                Route::put('/actualizar/{id}', 'Componentes\EstrategiaController@update')->name('estrategias.update');
                Route::put('/deshabilitar/{id}', 'Componentes\EstrategiaController@delete')->name('estrategias.delete');
                Route::get('/visualizar/{tipo}/{id}', 'Componentes\EstrategiaController@show')->name('estrategias.show');
                // DATATABLE
                Route::get('/datatable/{id}', 'Componentes\EstrategiaController@datatable_estrategias')->name('estrategias.datatable_datos');
            });

            // INDICADORES
            Route::prefix('indicadores')->group(function() {
                Route::get('/{id}', 'Componentes\IndicadorController@index')->name('indicadores');
                Route::get('/registrar/{id}', 'Componentes\IndicadorController@create')->name('indicadores.create');
                Route::post('/registrar', 'Componentes\IndicadorController@store')->name('indicadores.store');
                Route::get('/actualizar/{id}', 'Componentes\IndicadorController@edit')->name('indicadores.edit');
                Route::put('/actualizar/{id}', 'Componentes\IndicadorController@update')->name('indicadores.update');
                Route::get('/visualizar/{id}', 'Componentes\IndicadorController@show')->name('indicadores.show');
                Route::post('/cargar_datos_objeto', 'Componentes\IndicadorController@cargar_datos_objeto')->name('indicadores.cargar_datos_objeto');
                // DATATABLE
                Route::get('/datatable/{id}', 'Componentes\IndicadorController@datatable_indicadores')->name('indicadores.datatable_datos');

                // DATA FUENTE
                Route::prefix('data-fuente')->group(function() {
                    Route::post('/registrar{id}', 'Componentes\DataFuenteController@store')->name('datafuente.store');
                    Route::put('/actualizar/{id}', 'Componentes\DataFuenteController@update')->name('datafuente.update');
                    Route::put('/deshabilitar/{id}', 'Componentes\DataFuenteController@delete')->name('datafuente.delete');
                    // DATATABLE
                    Route::get('/datatable/{id}', 'Componentes\DataFuenteController@datatable_datos')->name('datafuente.datatable_datos');
                });
            });
        });
    });

    Route::group(['middleware' => ['role:Administrador']], function () {
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

        // ASIGNACIONES
        Route::prefix('asignaciones')->group(function() {
            Route::get('/', 'Gestion\AsignarController@index')->name('asignaciones');
            Route::get('/registrar', 'Gestion\AsignarController@create')->name('asignaciones.create');
            Route::post('/registrar', 'Gestion\AsignarController@store')->name('asignaciones.store');
            Route::get('/actualizar/{id}', 'Gestion\AsignarController@edit')->name('asignaciones.edit');
            Route::put('/actualizar/{id}', 'Gestion\AsignarController@update')->name('asignaciones.update');
            Route::put('/deshabilitar/{id}', 'Gestion\AsignarController@delete')->name('asignaciones.delete');
            Route::put('/habilitar/{id}', 'Gestion\AsignarController@active')->name('asignaciones.active');
            Route::post('/cargar_usuarios', 'Gestion\AsignarController@cargar_usuarios')->name('asignaciones.cargar_usuarios');
            // DATATABLE
            Route::get('/datatable', 'Gestion\AsignarController@datatable_asignaciones')->name('asignaciones.datatable_datos');
        });
    });
});


