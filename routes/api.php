<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\NewsAgencyController;
use App\Http\Controllers\Api\V1\SourceController;
use Illuminate\Support\Facades\Route;



// articles endpoints
Route::group(['prefix' => 'v1'], function () {
    Route::get('articles', [ArticleController::class, 'filter']);
    //
    Route::get('categories', [CategoryController::class, 'index']);
    //
    Route::get('sources', [SourceController::class, 'index']);
    //
    Route::get('authors', [AuthorsController::class, 'index']);
    //
    Route::get('news-agencies', [NewsAgencyController::class, 'index']);
});
