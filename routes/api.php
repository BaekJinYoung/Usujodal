<?php

//use App\Http\Controllers\InquiryController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::controller(InquiryController::class)->group(function () {
//    Route::post('/inquiry', 'store');
//});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(IndexController::class)->group(function () {
    Route::get('/history', 'history');
    Route::get('/company', 'company');
    Route::get('/youtube', 'youtube');
    Route::get('/consultant', 'consultant');
    Route::get('/announcement', 'announcement');
    Route::get('/share', 'share');
    Route::get('/question', 'question');
});

Route::controller(DetailController::class)->group(function () {
    Route::get('/company/{id}', 'company_detail');
    Route::get('/youtube/{id}', 'youtube_detail');
    Route::get('/announcement/{id}', 'announcement_detail');
    Route::get('/share/{id}', 'share_detail');
    Route::get('/question/{id}', 'question_detail');
});
