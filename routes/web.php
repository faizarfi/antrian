<?php

use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuideController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\OnlineTicketController;
use App\Http\Controllers\OperatorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Kiosk & TV Display)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('kiosk.index');
});

// Ticket Kiosk (Touchscreen / Visitor)
Route::prefix('kiosk')->name('kiosk.')->group(function () {
    Route::get('/', [KioskController::class, 'index'])->name('index');
    Route::post('/take', [KioskController::class, 'takeTicket'])->name('take');
    Route::get('/print/{ticket}', [KioskController::class, 'print'])->name('print');
    Route::get('/standee', [KioskController::class, 'standee'])->name('standee');
});

// Shortcut Route: Standee / Cetak Poster QR Meja (Non-touchscreen solution)
Route::get('/standee', [KioskController::class, 'standee'])->name('standee');
Route::get('/cetak-qr', [KioskController::class, 'standee'])->name('cetak_qr');

// TV Display Fullscreen & Real-time
Route::prefix('display')->name('display.')->group(function () {
    Route::get('/', [DisplayController::class, 'tv'])->name('tv');
    Route::get('/data', [DisplayController::class, 'data'])->name('data');
    Route::get('/stream', [DisplayController::class, 'stream'])->name('stream');
});

// Paperless Mobile / Online Ticket System (Tanpa Kertas)
Route::get('/online', [OnlineTicketController::class, 'index'])->name('ticket.online');
Route::post('/online', [OnlineTicketController::class, 'store'])->name('ticket.online.store');
Route::get('/tiket/{token}', [OnlineTicketController::class, 'live'])->name('ticket.live');
Route::get('/tiket/{token}/status', [OnlineTicketController::class, 'status'])->name('ticket.live.status');
Route::post('/tiket/{token}/feedback', [OnlineTicketController::class, 'submitFeedback'])->name('ticket.feedback');

/*
|--------------------------------------------------------------------------
| Authentication Routes (Custom Native Laravel Auth)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Operator / Petugas Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:petugas,admin'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('dashboard');
    Route::post('/preferences', [OperatorController::class, 'setPreferences'])->name('preferences');
    Route::post('/call-next', [OperatorController::class, 'callNext'])->name('call-next');
    Route::post('/recall/{ticket}', [OperatorController::class, 'recall'])->name('recall');
    Route::post('/complete/{ticket}', [OperatorController::class, 'complete'])->name('complete');
    Route::post('/skip/{ticket}', [OperatorController::class, 'skip'])->name('skip');
    Route::get('/refresh', [OperatorController::class, 'refreshData'])->name('refresh');
});

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users Management
    Route::resource('users', UserController::class)->except(['show']);

    // Services Management
    Route::resource('services', ServiceController::class)->except(['show']);

    // Counters Management
    Route::resource('counters', CounterController::class)->except(['show']);

    // Announcements Management
    Route::resource('announcements', AnnouncementController::class)->except(['show']);

    // Settings (General, Audio, TV)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Reports & Export
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

    // User Guide / Panduan Penggunaan
    Route::get('/guide', [GuideController::class, 'index'])->name('guide.index');
});

// Shortcut Route: Panduan Penggunaan
Route::get('/panduan', [GuideController::class, 'index'])->name('panduan')->middleware('auth');
