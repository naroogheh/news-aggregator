<?php

use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// articles endpoints
Route::group(['prefix' => 'v1'], function () {
    Route::get('articles', [ArticleController::class, 'filter']);
});

// source list endpoint
Route::group(['prefix' => 'v1'], function () {
    Route::get('sources', [SourceController::class, 'index']);
});
