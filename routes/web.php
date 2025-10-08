<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/offline', function () {
    return view('registrasion.offline');
});

Route::get('/validatenik', [PendaftaranController::class, 'validatenik'])->name('validatenik');
Route::get('/online', [PendaftaranController::class, 'online'])->name('online');
Route::post('/online-registration', [PendaftaranController::class, 'onlineRegistration'])->name('online.registration');
Route::get('/online-registration/card/{encoded_id}', [PendaftaranController::class, 'showCard'])
    ->name('online.registration.card');
//offline
Route::get('/offline', [PendaftaranController::class, 'offline'])->name('offline');
Route::post('/offline-registration', [PendaftaranController::class, 'offlineRegistration'])->name('offline.registration');
Route::get('/offline-registration/card/{encoded_id}', [PendaftaranController::class, 'showCard'])
    ->name('offline.registration.card');

Route::get('/call', [DashboardController::class, 'call'])->name('call');

// API Polling Status (5s)
Route::get('/api/dashboard/status', [DashboardController::class, 'getQueueStatus'])->name('api.dashboard.status');

// Aksi Panggil Antrean Berikutnya
Route::post('/api/dashboard/call-next', [DashboardController::class, 'callNext'])->name('api.dashboard.call_next');

// Aksi Selesaikan Layanan
Route::post('/api/dashboard/complete', [DashboardController::class, 'completeService'])->name('api.dashboard.complete');

// Aksi Panggil Ulang
Route::post('/api/dashboard/reissue', [DashboardController::class, 'reissueCall'])->name('api.dashboard.reissue');

// Aksi Lewati Antrean
Route::post('/api/dashboard/skip', [DashboardController::class, 'skipCall'])->name('api.dashboard.skip');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


require __DIR__ . '/auth.php';


// --- ROUTE MONITOR TV PUBLIK ---
// Route untuk menampilkan View Monitor
Route::get('/monitor/display', [MonitorController::class, 'showDisplay'])->name('monitor.display');
Route::get('/monitor/public', [MonitorController::class, 'showDisplayPublic'])->name('monitor.public');

// API untuk Display Processor (Mengambil panggilan baru & Mark Announced)
Route::prefix('api/display')->group(function () {
    Route::get('/processor', [MonitorController::class, 'processDisplay'])->name('api.display.processor');
    Route::post('/processor', [MonitorController::class, 'processDisplay']); // Menerima sinyal POST dari JS
});

// API Status Loket (Digunakan oleh Monitor Display untuk status Loket 1-4)
Route::get('/api/public/loket-status', [ApiController::class, 'getLoketStatus'])->name('api.public.loket_status');
Route::get('/api/public/last-active-call', [ApiController::class, 'getLastActiveCall'])->name('api.public.last_active_call');
Route::get('/api/public/personal-status', [ApiController::class, 'getPersonalStatus'])->name('api.public.personal_status');
// Route::get('/personal-status', [ApiController::class, 'getPersonalStatus'])->name('api.public.personal_status');

Route::get('users/data', [UserController::class, 'getUsersData'])->name('users.data');
Route::resource('users', UserController::class);

Route::get('stats/harian', [DashboardController::class, 'getDailyStats'])->name('stats.harian');
Route::get('stats/weekly-trend', [DashboardController::class, 'getWeeklyTrend'])->name('stats.weekly_trend');
Route::get('stats/loket-dist', [DashboardController::class, 'getServiceDistribution'])->name('stats.loket_dist');
Route::get('stats/top-services', [DashboardController::class, 'getTopServices'])->name('stats.top_services');

Route::get('reports/visits', [ReportController::class, 'index'])->name('reports.visits.index');
Route::get('reports/visits/data', [ReportController::class, 'getVisitsData'])->name('reports.visits.data');
Route::post('reports/send-survey/{id}', [ReportController::class, 'sendSurvey'])->name('reports.send_survey');