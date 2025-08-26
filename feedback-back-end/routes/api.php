<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\SendMessagesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');



Route::controller(LoginController::class)->group(function() {
    Route::post('register','register');
    Route::post('login','login');
    Route::post('logout','logout')->middleware('auth:api');
    Route::post('refresh','refresh')->middleware('auth:api');
    Route::post('update','update')->middleware('auth:api');
});

Route::post('/run-send-messages', SendMessagesController::class)->middleware('auth:api');
