<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    /** Public Auth routes */
    Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
        // Surveys
        Route::get('surveys/minimal', [\App\Http\Controllers\API\SurveyController::class, 'minimalList']); // minimal list as select options
        Route::apiResource('surveys', \App\Http\Controllers\API\SurveyController::class);

        // Users
        Route::apiResource('users', \App\Http\Controllers\API\UserController::class);

        // Survey Sections - Nested routes under surveys
        Route::group(['prefix' => 'surveys/{survey}/sections'], function () {
            Route::get('/', [\App\Http\Controllers\API\SectionController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\API\SectionController::class, 'store']);
        });
        // Individual Section routes
        Route::group(['prefix' => 'sections'], function () {
            Route::get('{section}', [\App\Http\Controllers\API\SectionController::class, 'show']);
            Route::put('{section}', [\App\Http\Controllers\API\SectionController::class, 'update']);
            Route::delete('{section}', [\App\Http\Controllers\API\SectionController::class, 'destroy']);
        });

        // Survey Options - Nested routes under surveys
        Route::group(['prefix' => 'surveys/{survey}/options'], function () {
            Route::get('/', [\App\Http\Controllers\API\OptionController::class, 'index']);
            Route::post('/', [\App\Http\Controllers\API\OptionController::class, 'store']);
        });
        // Individual Option routes
        Route::group(['prefix' => 'options'], function () {
            Route::get('types', [\App\Http\Controllers\API\OptionController::class, 'types']);
            Route::get('{option}', [\App\Http\Controllers\API\OptionController::class, 'show']);
            Route::put('{option}', [\App\Http\Controllers\API\OptionController::class, 'update']);
            Route::delete('{option}', [\App\Http\Controllers\API\OptionController::class, 'destroy']);
        });
    });
});
