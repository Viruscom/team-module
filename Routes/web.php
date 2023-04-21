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

use App\Http\Controllers\Front\FrontHomeController;
use Illuminate\Support\Facades\Route;
use Modules\Team\Http\Controllers\FrontTeamController;
use Modules\Team\Http\Controllers\TeamController;
use Modules\Team\Http\Controllers\TeamDivisionController;

Route::group(['prefix' => '/', 'middleware' => ['lockedSite', 'underMaintenance']], static function () {
    Route::get('/', [FrontHomeController::class, 'index'])->name('front.index');

    /* With language */
    Route::group(['prefix' => '{languageSlug}', 'where' => ['languageSlug' => '[a-zA-Z]{2}']], static function () {
        Route::get('/team/{division}', [FrontTeamController::class, 'index'])->name('front.team.division');
    });
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

    /* Team Division */
    Route::group(['prefix' => 'team-division'], static function () {
        Route::get('/', [TeamDivisionController::class, 'index'])->name('admin.team.division.index');
        Route::get('/create', [TeamDivisionController::class, 'create'])->name('admin.team.division.create');
        Route::post('/store', [TeamDivisionController::class, 'store'])->name('admin.team.division.store');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [TeamDivisionController::class, 'activeMultiple'])->name('admin.team.division.active-multiple');
            Route::get('delete', [TeamDivisionController::class, 'deleteMultiple'])->name('admin.team.division.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [TeamDivisionController::class, 'edit'])->name('admin.team.division.edit');
            Route::post('update', [TeamDivisionController::class, 'update'])->name('admin.team.division.update');
            Route::get('delete', [TeamDivisionController::class, 'delete'])->name('admin.team.division.delete');
            Route::get('show', [TeamDivisionController::class, 'show'])->name('admin.team.division.show');
            Route::get('/active/{active}', [TeamDivisionController::class, 'active'])->name('admin.team.division.changeStatus');
            Route::get('position/up', [TeamDivisionController::class, 'positionUp'])->name('admin.team.division.position-up');
            Route::get('position/down', [TeamDivisionController::class, 'positionDown'])->name('admin.team.division.position-down');
            Route::get('image/delete', [TeamDivisionController::class, 'deleteImage'])->name('admin.team.division.delete-image');
        });
    });
});
