<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\LayananStatsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- 1. ROUTE PUBLIK / PENDAFTARAN ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/validatenik', [PendaftaranController::class, 'validatenik'])->name('validatenik');

// Pendaftaran Online
Route::get('/online', [PendaftaranController::class, 'online'])->name('online');
Route::post('/online-registration', [PendaftaranController::class, 'onlineRegistration'])->name('online.registration');
Route::get('/online-registration/card/{encoded_id}', [PendaftaranController::class, 'showCard'])
    ->name('online.registration.card');
//offline
Route::get('/offline', [PendaftaranController::class, 'offline'])->name('offline');
Route::post('/offline-registration', [PendaftaranController::class, 'offlineRegistration'])->name('offline.registration');
Route::get('/offline-registration/card/{encoded_id}', [PendaftaranController::class, 'showCard'])
    ->name('offline.registration.card');

// --- 2. ROUTE MONITOR & PUBLIC API (Tanpa Otentikasi) ---
Route::prefix('monitor')->name('monitor.')->group(function () {
    // Tampilan Monitor Display (TV)
    Route::get('/display', [MonitorController::class, 'showDisplay'])->name('display');
    Route::get('/public', [MonitorController::class, 'showDisplayPublic'])->name('public');
});

// API untuk Display Processor (Mengambil panggilan baru & Mark Announced)
Route::prefix('api/display')->group(function () {
    Route::get('/processor', [MonitorController::class, 'processDisplay'])->name('api.display.processor');
    Route::post('/processor', [MonitorController::class, 'processDisplay']); // Menerima sinyal POST dari JS
});

// API Publik (Diakses oleh Monitor Display, Kartu Antrean, dll.)
Route::prefix('api/public')->name('api.public.')->group(function () {
    Route::get('/loket-status', [ApiController::class, 'getLoketStatus'])->name('loket_status');
    Route::get('/last-active-call', [ApiController::class, 'getLastActiveCall'])->name('last_active_call');
    Route::get('/personal-status', [ApiController::class, 'getPersonalStatus'])->name('personal_status');

    // API Grid Status (untuk halaman status Kanwil JATIM style)
    Route::get('/grid-status', [ApiController::class, 'getGridServiceStatus'])->name('grid_status');
});

// --- 3. ROUTE DASHBOARD PETUGAS (Dilindungi Middleware 'auth') ---
Route::middleware(['auth'])->group(function () {
    // Tampilan Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Tampilan Dashboard Panggilan (Alternatif index)
    Route::get('/call', [DashboardController::class, 'call'])->name('call');
    // --- API PENGENDALI PANGGILAN (Dashboard) ---
    Route::prefix('api/dashboard')->name('api.dashboard.')->group(function () {
        Route::get('/status', [DashboardController::class, 'getQueueStatus'])->name('status');
        Route::post('/call-next', [DashboardController::class, 'callNext'])->name('call_next');
        Route::post('/complete', [DashboardController::class, 'completeService'])->name('complete');
        Route::post('/reissue', [DashboardController::class, 'reissueCall'])->name('reissue');
        Route::post('/skip', [DashboardController::class, 'skipCall'])->name('skip');
    });

    Route::get('users/data', [UserController::class, 'getUsersData'])->name('users.data');
    Route::resource('users', UserController::class);


    // A. LAPORAN KUNJUNGAN
    Route::prefix('reports')->name('reports.')->group(function () {
        // Laporan Tabel (Index)
        Route::get('/visits', [ReportController::class, 'index'])->name('visits.index');
        // API Datatables (HARUS DI ATAS RESOURCE ROUTE)
        Route::get('/visits/data', [ReportController::class, 'getVisitsData'])->name('visits.data');
        // Aksi Pengiriman Survei
        Route::post('/send-survey/{id}', [ReportController::class, 'sendSurvey'])->name('send_survey');
        // Generate PDF
        Route::get('/pdf', [ReportController::class, 'generatePdfReport'])->name('generate_pdf');

        // API Detail Entry untuk Modal (Dipindahkan ke dalam Auth/Admin)
    });
    Route::get('/get-entry-details/{id}', [ApiController::class, 'getEntryDetails'])->name('get_entry_details');

    // B. MONITORING & STATISTIK (Grafik)
    Route::prefix('stats')->name('stats.')->group(function () {
        Route::get('/layanan', [LayananStatsController::class, 'index'])->name('layanan.index');
        // Route::get('/layanan/api', [LayananStatsController::class, 'getLayananData'])->name('layanan.api');

        // API Global Dashboard
        Route::get('/harian', [DashboardController::class, 'getDailyStats'])->name('harian');
        Route::get('/weekly-trend', [DashboardController::class, 'getWeeklyTrend'])->name('weekly_trend');
        Route::get('/loket-dist', [DashboardController::class, 'getServiceDistribution'])->name('loket_dist');
        Route::get('/top-services', [DashboardController::class, 'getTopServices'])->name('top_services');
    });
    Route::get('stats/layanan/api', [LayananStatsController::class, 'getLayananData'])->name('api.stats.layanan');

    Route::resource('survey', SurveyController::class);

    Route::get('admin/notif/recap', [AdminController::class, 'showRecapForm'])->name('admin.notif.form');
    Route::post('admin/notif/send', [AdminController::class, 'sendRecapReport'])->name('admin.notif.send');
});

// Halaman baru untuk input HP saja
Route::get('/offline-kanwil', [PendaftaranController::class, 'showWAGetLink'])->name('get.wa.link.form');
Route::post('/send-wa-link', [PendaftaranController::class, 'sendWAGetLink'])->name('send.wa.link.submit');

// Route untuk form pendaftaran detail (yang diakses dari link WA)
Route::get('/register/detail/{token}', [PendaftaranController::class, 'showDetailForm'])->name('register.detail.form');

// --- 4. FILE AUTH BAWAAN LARAVEL BREEZE ---
require __DIR__ . '/auth.php';
