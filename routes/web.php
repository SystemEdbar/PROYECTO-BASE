<?php

use App\Http\Controllers\Administracion\RolesController;
use App\Http\Controllers\Administracion\UsersController;
use App\Http\Controllers\Report\ServiceReportController;
use App\Http\Controllers\Servicio\FreightsController;
use App\Http\Controllers\Servicio\LiquidationsController;
use App\Http\Controllers\Catalogos\CustomersController;
use App\Http\Controllers\Catalogos\VendorsController;
use App\Http\Controllers\Catalogos\FeesController;
use App\Http\Controllers\Equipo\PilotsController;
use App\Http\Controllers\Equipo\VehiclesController;

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
    return redirect()->route('login');
});

Route::middleware(['auth'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('/administracion')->middleware('auth')->group(function () {
    Route::get('/usuarios', [UsersController::class, 'index'])->middleware('can:show administracion/usuarios')->name('usuarios');
    Route::post('/usuarios/save', [UsersController::class, 'store'])->middleware('can:create administracion/usuarios')->name('usuarios.store');
    Route::post('/usuarios/update/{id}', [UsersController::class, 'update'])->middleware('can:update administracion/usuarios')->name('usuarios.update');
    Route::delete('/usuarios/delete/{id}',[UsersController::class, 'destroy'])->middleware('can:delete administracion/usuarios')->name('usuarios.delete');
    Route::get('/usuarios/roles', [UsersController::class, 'roles'])->middleware('can:show administracion/usuarios')->name('usuarios.roles');
    Route::post('/usuarios/roles/update/{id}', [UsersController::class, 'updateRole'])->middleware('can:update administracion/usuarios')->name('usuarios.roles.update');

    Route::get('/roles', [RolesController::class, 'index'])->middleware('can:show administracion/roles')->name('roles');
    Route::post('/roles/save', [RolesController::class, 'store'])->middleware('can:create administracion/roles')->name('roles.store');
    Route::delete('/roles/delete/{id}',[RolesController::class, 'destroy'])->middleware('can:delete administracion/roles')->name('roles.delete');
    Route::get('/roles/permisos', [RolesController::class, 'permission'])->middleware('can:show administracion/roles')->name('roles.permisos');
    Route::post('/roles/permisos/update/{id}', [RolesController::class, 'updatePermission'])->middleware('can:update administracion/roles')->name('roles.permisos.update');
});
