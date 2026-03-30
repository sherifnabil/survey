<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1'], function () {
    // Surveys
    Route::get('surveys/minimal', [\App\Http\Controllers\API\SurveyController::class, 'minimalList']); // minimal list as select options
    Route::apiResource('surveys', \App\Http\Controllers\API\SurveyController::class);

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
