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

\Illuminate\Support\Facades\Auth::routes(['register' => false]);

Route::middleware(['web', 'auth:web'])->group(function () {
    Route::get('/home', function () {
        return redirect(\route('licence-users.index'));
    });

    Route::get("/", function(){
        return redirect(\route('licence-users.index'));
    });

    Route::resource('licence-users', \App\Http\Controllers\LicenceUsersController::class)->except('show');
    Route::get('licence-users/{licence_user}/servers', [\App\Http\Controllers\LicenceUsersController::class, 'show'])->name('licence-users.show');
    Route::post('licence-users/{licence_user}/toggle', [\App\Http\Controllers\LicenceUsersController::class, 'toggle'])->name('licence-users.toggle');
    Route::post('licence-users/{licence_user}/lock', [\App\Http\Controllers\LicenceUsersController::class, 'lock'])->name('licence-users.lock');

    Route::resource('licence-users.servers', \App\Http\Controllers\ServersController::class)->except(['index', 'update', 'edit']);
    Route::post('licence-users/{licence_user}/servers/{server}/toggle', [\App\Http\Controllers\ServersController::class, 'toggle'])->name('licence-users.servers.toggle');

    Route::resource(
        'licence-users.servers.applications',
        \App\Http\Controllers\LicenceApplicationsController::class
    )->only(['create', 'store', 'destroy']);
    Route::resource('licence-users.servers.applications', \App\Http\Controllers\LicenceApplicationsController::class);
    Route::post('licence-users/{licence_user}/server/{server}/applications/{application}/toggle', [\App\Http\Controllers\LicenceApplicationsController::class, 'toggle'])->name('licence-users.server.applications.toggle');
});





