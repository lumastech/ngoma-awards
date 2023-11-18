<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\AwardsCategoryController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// login
Route::get('/', function () {
    return Inertia::render('Auth/Login');
})->name('home');

// register
Route::get('/register', function () {
    return redirect()->route('login');
})->name('register');
Route::post('/register', function () {
    return redirect()->route('login');
})->name('register');



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // search
    Route::get('dashboard/search', [DashboardController::class, 'search']);
    Route::get('users/search', [UserController::class, 'search']);
    Route::get('artists/search', [ArtistController::class, 'search']);
    Route::get('awards/search', [AwardController::class, 'search']);
    Route::get('categories/search', [AwardsCategoryController::class, 'search']);
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('artists', ArtistController::class);
    Route::resource('awards', AwardController::class);
    Route::resource('categories', AwardsCategoryController::class);

});
