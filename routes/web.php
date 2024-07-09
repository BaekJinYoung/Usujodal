<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('announcement')->group(function () {
    Route::controller(AnnouncementController::class)->group(function () {
        Route::get('/', 'index')->name("admin.announcementIndex");
        Route::get('/create', 'create')->name("admin.announcementCreate");
        Route::post('/store', 'store')->name("admin.announcementStore");
    });
});


Route::prefix('question')->group(function () {
    Route::controller(QuestionController::class)->group(function () {
        Route::get('/', 'index')->name("admin.questionIndex");
        Route::get('/create', 'create')->name("admin.questionCreate");
        Route::post('/store', 'store')->name("admin.questionStore");
    });
});

Route::prefix('history')->group(function () {
    Route::controller(HistoryController::class)->group(function () {
        Route::get('/', 'index')->name("admin.historyIndex");
        Route::get('/create', 'create')->name("admin.historyCreate");
        Route::post('/store', 'store')->name("admin.historyStore");
    });
});

Route::prefix('company')->group(function () {
    Route::controller(CompanyController::class)->group(function () {
        Route::get('/', 'index')->name("admin.companyIndex");
        Route::get('/create', 'create')->name("admin.companyCreate");
        Route::post('/store', 'store')->name("admin.companyStore");
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
