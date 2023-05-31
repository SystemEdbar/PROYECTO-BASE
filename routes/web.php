<?php

use App\Http\Controllers\Administracion\RolesController;
use App\Http\Controllers\Administracion\UsersController;

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
    Route::prefix('/usuarios')->group(function () {
        Route::get('', [UsersController::class, 'index'])->middleware('can:show administracion/usuarios')->name('usuarios');
        Route::post('/save', [UsersController::class, 'store'])->middleware('can:create administracion/usuarios')->name('usuarios.store');
        Route::post('/update/{id}', [UsersController::class, 'update'])->middleware('can:update administracion/usuarios')->name('usuarios.update');
        Route::delete('/suspended/{id}',[UsersController::class, 'suspended'])->middleware('can:update administracion/usuarios')->name('usuarios.supended');
        Route::delete('/delete/{id}',[UsersController::class, 'destroy'])->middleware('can:delete administracion/usuarios')->name('usuarios.delete');
        Route::get('/roles', [UsersController::class, 'roles'])->middleware('can:show administracion/usuarios')->name('usuarios.roles');
        Route::post('/roles/update/{id}', [UsersController::class, 'updateRole'])->middleware('can:update administracion/usuarios')->name('usuarios.roles.update');
    });
    Route::prefix('/roles')->group(function () {
        Route::get('', [RolesController::class, 'index'])->middleware('can:show administracion/roles')->name('roles');
        Route::post('/save', [RolesController::class, 'store'])->middleware('can:create administracion/roles')->name('roles.store');
        Route::delete('/delete/{id}',[RolesController::class, 'destroy'])->middleware('can:delete administracion/roles')->name('roles.delete');
        Route::get('/permisos', [RolesController::class, 'permission'])->middleware('can:show administracion/roles')->name('roles.permisos');
        Route::post('/permisos/update/{id}', [RolesController::class, 'updatePermission'])->middleware('can:update administracion/roles')->name('roles.permisos.update');
    });
});
