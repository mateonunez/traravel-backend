<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Authorization routes
 */
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.'
], function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
});

// Travels
Route::group([
    'prefix' => 'travels',
    'as' => 'travels.'
], function () {
    Route::get('/search', [\App\Http\Controllers\TravelController::class, 'search']);

    Route::get('/', [\App\Http\Controllers\TravelController::class, 'index']);
    Route::get('/{id}', [\App\Http\Controllers\TravelController::class, 'show']);

    Route::group([
        'middleware' => 'auth:api',
        'as' => 'auth.'
    ], function () {
        Route::post('/', [\App\Http\Controllers\TravelController::class, 'store'])->middleware('admin');
        Route::put('/{id}', [\App\Http\Controllers\TravelController::class, 'update'])->middleware('editor');
    });
});

// Tours
// Route::group([
//     'prefix' => 'tours',
//     'as' => 'tours.'
// ], function () {
//     Route::get('/search', [\App\Http\Controllers\TourController::class, 'search']);

//     Route::get('/', [\App\Http\Controllers\TourController::class, 'index']);
//     Route::get('/{id}', [\App\Http\Controllers\TourController::class, 'show']);

//     Route::group([
//         'middleware' => 'auth:api',
//         'as' => 'auth.'
//     ], function () {
//         Route::post('/', [\App\Http\Controllers\TourController::class, 'store'])->middleware('admin');
//         Route::put('/{id}', [\App\Http\Controllers\TourController::class, 'update'])->middleware('editor');
//     });
// });

/**
 * Authenticated routes
 */
Route::group([
    'middleware' => 'auth:api',
    'as' => 'app.'
], function () {

    // Users
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        // Profile
        Route::get('/me', [\App\Http\Controllers\UserController::class, 'me']);
    });

    /**
     * Admin routes
     */
    Route::group([
        'middleware' => ['admin']
    ], function () {
        Route::apiResources([
            '/users' => \App\Http\Controllers\UserController::class,
        ]);
    });
});
