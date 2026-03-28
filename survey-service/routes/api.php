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
});
