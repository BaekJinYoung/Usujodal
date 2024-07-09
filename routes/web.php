<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('announcement')->group(function () {
    Route::controller(AnnouncementController::class)->group(function () {
        Route::get('/', 'index')->name("admin.announcementIndex");
        Route::get('/create', 'create')->name("admin.announcementCreate");
    });
});


Route::prefix('question')->group(function () {
    Route::controller(QuestionController::class)->group(function () {
        Route::get('/', 'index')->name("admin.questionIndex");
        Route::get('/create', 'create')->name("admin.questionCreate");
        Route::post('/store', 'store')->name("admin.questionStore");
    });
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
