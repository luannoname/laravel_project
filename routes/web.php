<?php

use App\Http\Controllers\Ajax\DashboardController as AjaxDashboardController;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LanguageController;
use App\Http\Controllers\Backend\PostCatalogueController;
use App\Http\Controllers\Backend\PostController;
use App\Http\Controllers\Backend\UserCatalogueController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\AuthenticateMiddleware;
use Illuminate\Support\Facades\Route;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['admin', 'locale']], function () {
    // BACKEND ROUTES
    Route::get('dashboard/index', [DashboardController::class, 'index'])
    ->name('dashboard.index')
    ;

    // USER
    Route::prefix('user/')->name('user.')->group(function () {
        Route::get('index', [UserController::class, 'index'])
        ->name('index');
        Route::get('create', [UserController::class, 'create'])
        ->name('create');
        Route::post('store', [UserController::class, 'store'])
        ->name('store');
        Route::get('{id}/edit', [UserController::class, 'edit'])
        ->name('edit');
        Route::post('{id}/update', [UserController::class, 'update'])
        ->name('update');
        Route::get('{id}/delete', [UserController::class, 'delete'])
        ->name('delete');
        Route::delete('{id}/destroy', [UserController::class, 'destroy'])
        ->name('destroy');
    });
    // USER CATALOGUE
    Route::prefix('user/catalogue/')->name('user.catalogue.')->group(function () {
        Route::get('index', [UserCatalogueController::class, 'index'])
        ->name('index');
        Route::get('create', [UserCatalogueController::class, 'create'])
        ->name('create');
        Route::post('store', [UserCatalogueController::class, 'store'])
        ->name('store');
        Route::get('{id}/edit', [UserCatalogueController::class, 'edit'])
        ->name('edit');
        Route::post('{id}/update', [UserCatalogueController::class, 'update'])
        ->name('update');
        Route::get('{id}/delete', [UserCatalogueController::class, 'delete'])
        ->name('delete');
        Route::delete('{id}/destroy', [UserCatalogueController::class, 'destroy'])
        ->name('destroy');
    });
    // LANGUAGE
    Route::prefix('language/')->name('language.')->group(function () {
        Route::get('index', [LanguageController::class, 'index'])
        ->name('index')->middleware('locale');
        Route::get('create', [LanguageController::class, 'create'])
        ->name('create');
        Route::post('store', [LanguageController::class, 'store'])
        ->name('store');
        Route::get('{id}/edit', [LanguageController::class, 'edit'])
        ->name('edit');
        Route::post('{id}/update', [LanguageController::class, 'update'])
        ->name('update');
        Route::get('{id}/delete', [LanguageController::class, 'delete'])
        ->name('delete');
        Route::delete('{id}/destroy', [LanguageController::class, 'destroy'])
        ->name('destroy');
        Route::get('{id}/switch', [LanguageController::class, 'swithBackendLanguage'])
        ->name('switch');
    });
    // POST CATALOGUE
    Route::prefix('post/catalogue/')->name('post.catalogue.')->group(function () {
        Route::get('index', [PostCatalogueController::class, 'index'])
        ->name('index');
        Route::get('create', [PostCatalogueController::class, 'create'])
        ->name('create');
        Route::post('store', [PostCatalogueController::class, 'store'])
        ->name('store');
        Route::get('{id}/edit', [PostCatalogueController::class, 'edit'])
        ->name('edit');
        Route::post('{id}/update', [PostCatalogueController::class, 'update'])
        ->name('update');
        Route::get('{id}/delete', [PostCatalogueController::class, 'delete'])
        ->name('delete');
        Route::delete('{id}/destroy', [PostCatalogueController::class, 'destroy'])
        ->name('destroy');
    });

    // POST CATALOGUE
    Route::prefix('post/')->name('post.')->group(function () {
        Route::get('index', [PostController::class, 'index'])
        ->name('index');
        Route::get('create', [PostController::class, 'create'])
        ->name('create');
        Route::post('store', [PostController::class, 'store'])
        ->name('store');
        Route::get('{id}/edit', [PostController::class, 'edit'])
        ->name('edit');
        Route::post('{id}/update', [PostController::class, 'update'])
        ->name('update');
        Route::get('{id}/delete', [PostController::class, 'delete'])
        ->name('delete');
        Route::delete('{id}/destroy', [PostController::class, 'destroy'])
        ->name('destroy');
    });

    // AJAX
    Route::get('ajax/location/getLocation', [LocationController::class, 'getLocation'])
    ->name('ajax.location.index');
    Route::post('ajax/dashboard/changeStatus', [AjaxDashboardController::class, 'changeStatus'])
    ->name('ajax.dashboard.changeStatus');
    Route::post('ajax/dashboard/changeStatusAll', [AjaxDashboardController::class, 'changeStatusAll'])
    ->name('ajax.dashboard.changeStatusAll');
});




Route::get('admin', [AuthController::class, 'index'])
->name('auth.admin')
->middleware('login');

Route::post('login', [AuthController::class, 'login'])
->name('auth.login');

Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');