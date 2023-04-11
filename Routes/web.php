<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\TeamController;

Route::prefix('team')->group(function () {
    Route::get('/', 'TeamController@index');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], static function () {
    /* Team */
    Route::group(['prefix' => 'team'], static function () {
        Route::get('/', [TeamController::class, 'index'])->name('admin.team.index');
        Route::get('/create', [TeamController::class, 'create'])->name('admin.team.create');
        Route::post('/store', [TeamController::class, 'store'])->name('admin.team.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [TeamController::class, 'activeMultiple'])->name('admin.team.active-multiple');
            Route::get('delete', [TeamController::class, 'deleteMultiple'])->name('admin.team.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [TeamController::class, 'edit'])->name('admin.team.edit');
            Route::post('update', [TeamController::class, 'update'])->name('admin.team.update');
            Route::get('delete', [TeamController::class, 'delete'])->name('admin.team.delete');
            Route::get('show', [TeamController::class, 'show'])->name('admin.team.show');
            Route::get('/active/{active}', [TeamController::class, 'active'])->name('admin.team.changeStatus');
            Route::get('position/up', [TeamController::class, 'positionUp'])->name('admin.team.position-up');
            Route::get('position/down', [TeamController::class, 'positionDown'])->name('admin.team.position-down');
            Route::get('image/delete', [TeamController::class, 'deleteImage'])->name('admin.team.delete-image');
        });
    });
});
