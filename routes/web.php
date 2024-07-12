<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\YoutubeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(ClientController::class)->group(function () {
    Route::get('/history', 'history');
    Route::get('/company', 'company');
    Route::get('/company/{id}', 'company_detail');
    Route::get('/youtube', 'youtube');
    Route::get('/youtube/{id}', 'youtube_detail');
    Route::get('/announcement', 'announcement');
    Route::get('/announcement/{id}', 'announcement_detail');
    Route::get('/share', 'share');
    Route::get('/share/{id}', 'share_detail');
    Route::get('/question', 'question');
    Route::get('/question/{id}', 'question_detail');
});

Route::prefix('admin')->group(function () {
    Route::prefix('announcement')->group(function () {
        Route::controller(AnnouncementController::class)->group(function () {
            Route::get('/', 'index')->name("admin.announcementIndex");
            Route::get('/create', 'create')->name("admin.announcementCreate");
            Route::post('/store', 'store')->name("admin.announcementStore");
            Route::get('/{announcement}/edit', 'edit')->name("admin.announcementEdit");
            Route::patch('/{announcement}', 'update')->name("admin.announcementUpdate");
            Route::delete('/{announcement}', 'delete')->name("admin.announcementDelete");
        });
    });

    Route::prefix('share')->group(function () {
        Route::controller(ShareController::class)->group(function () {
            Route::get('/', 'index')->name("admin.shareIndex");
            Route::get('/create', 'create')->name("admin.shareCreate");
            Route::post('/store', 'store')->name("admin.shareStore");
            Route::get('/{share}/edit', 'edit')->name("admin.shareEdit");
            Route::patch('/{share}', 'update')->name("admin.shareUpdate");
            Route::delete('/{share}', 'delete')->name("admin.shareDelete");
        });
    });

    Route::prefix('question')->group(function () {
        Route::controller(QuestionController::class)->group(function () {
            Route::get('/', 'index')->name("admin.questionIndex");
            Route::get('/create', 'create')->name("admin.questionCreate");
            Route::post('/store', 'store')->name("admin.questionStore");
            Route::get('/{question}/edit', 'edit')->name("admin.questionEdit");
            Route::patch('/{question}', 'update')->name("admin.questionUpdate");
            Route::delete('/{question}', 'delete')->name("admin.questionDelete");
        });
    });

    Route::prefix('history')->group(function () {
        Route::controller(HistoryController::class)->group(function () {
            Route::get('/', 'index')->name("admin.historyIndex");
            Route::get('/create', 'create')->name("admin.historyCreate");
            Route::post('/store', 'store')->name("admin.historyStore");
            Route::get('/{history}/edit', 'edit')->name("admin.historyEdit");
            Route::patch('/{history}', 'update')->name("admin.historyUpdate");
            Route::delete('/{history}', 'delete')->name("admin.historyDelete");
        });
    });

    Route::prefix('company')->group(function () {
        Route::controller(CompanyController::class)->group(function () {
            Route::get('/', 'index')->name("admin.companyIndex");
            Route::get('/create', 'create')->name("admin.companyCreate");
            Route::post('/store', 'store')->name("admin.companyStore");
            Route::get('/{company}/edit', 'edit')->name("admin.companyEdit");
            Route::patch('/{company}', 'update')->name("admin.companyUpdate");
            Route::delete('/{company}', 'delete')->name("admin.companyDelete");
        });
    });

    Route::prefix('youtube')->group(function () {
        Route::controller(YoutubeController::class)->group(function () {
            Route::get('/', 'index')->name("admin.youtubeIndex");
            Route::get('/create', 'create')->name("admin.youtubeCreate");
            Route::post('/store', 'store')->name("admin.youtubeStore");
            Route::get('/{youtube}/edit', 'edit')->name("admin.youtubeEdit");
            Route::patch('/{youtube}', 'update')->name("admin.youtubeUpdate");
            Route::delete('/{youtube}', 'delete')->name("admin.youtubeDelete");
        });
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
