<?php

use App\Http\Controllers\admin\AnnouncementController;
use App\Http\Controllers\admin\BannerController;
use App\Http\Controllers\admin\CompanyController;
use App\Http\Controllers\admin\ConsultantController;
use App\Http\Controllers\admin\HistoryController;
use App\Http\Controllers\admin\InquiryController;
use App\Http\Controllers\admin\PopupController;
use App\Http\Controllers\admin\QuestionController;
use App\Http\Controllers\admin\ShareController;
use App\Http\Controllers\admin\YoutubeController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::prefix('popup')->group(function () {
        Route::controller(PopupController::class)->group(function () {
            Route::get('/', 'index')->name("admin.popupIndex");
            Route::get('/create', 'create')->name("admin.popupCreate");
            Route::post('/store', 'store')->name("admin.popupStore");
            Route::get('/{popup}/edit', 'edit')->name("admin.popupEdit");
            Route::patch('/{popup}', 'update')->name("admin.popupUpdate");
            Route::delete('/{popup}', 'delete')->name("admin.popupDelete");
        });
    });

    Route::prefix('banner')->group(function () {
        Route::controller(BannerController::class)->group(function () {
            Route::get('/', 'index')->name("admin.bannerIndex");
            Route::get('/create', 'create')->name("admin.bannerCreate");
            Route::post('/store', 'store')->name("admin.bannerStore");
            Route::get('/{banner}/edit', 'edit')->name("admin.bannerEdit");
            Route::patch('/{banner}', 'update')->name("admin.bannerUpdate");
            Route::delete('/{banner}', 'delete')->name("admin.bannerDelete");
        });
    });

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

            Route::get('/check-image/{year}', 'checkImage')->name('admin.checkImage');
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

    Route::prefix('consultant')->group(function () {
        Route::controller(ConsultantController::class)->group(function () {
            Route::get('/', 'index')->name("admin.consultantIndex");
            Route::get('/create', 'create')->name("admin.consultantCreate");
            Route::post('/store', 'store')->name("admin.consultantStore");
            Route::get('/{consultant}/edit', 'edit')->name("admin.consultantEdit");
            Route::patch('/{consultant}', 'update')->name("admin.consultantUpdate");
            Route::delete('/{consultant}', 'delete')->name("admin.consultantDelete");
        });
    });

    Route::prefix('inquiry')->group(function () {
        Route::controller(InquiryController::class)->group(function () {
            Route::get('/', 'index')->name("admin.inquiryIndex");
            Route::get('/{inquiry}/edit', 'edit')->name("admin.inquiryEdit");
            Route::delete('/{inquiry}', 'delete')->name("admin.inquiryDelete");
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
