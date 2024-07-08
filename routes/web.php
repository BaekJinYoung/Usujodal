<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AnnouncementController::class)->group(function () {
    Route::get('/', 'index')->name("admin.announcementIndex");
});

Route::controller(QuestionController::class)->group(function () {
    Route::get('/question', 'index')->name("admin.questionIndex");
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
