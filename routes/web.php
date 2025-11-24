<?php

use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// login
Route::get('/', [MovieController::class, 'home'])->name('home');
Route::get('/movies/active', [MovieController::class, 'homeMovies'])->name('home.movies.all');

// user login
Route::middleware('isUser')->group(function () {
    Route::get('/schedule/{scheduleId}/hours/{hourId}', [ScheduleController::class, 'showSeats'])
        ->name('schedules.show_seats');

    Route::prefix('/tickets')->name('tickets.')->group(function() {
        Route::get('/', [TIcketController::class, 'index'])->name('index');
        Route::post('/', [TicketController::class, 'store'])->name('store');
        Route::get('/{ticketId}/order', [TicketController::class, 'orderPage'])->name('order');
        Route::post('/qrcode', [TicketController::class, 'createQrcode'])->name('qrcode');
        Route::get('/{ticketId}/payment', [TicketController::class, 'paymentPage'])->name('payment');
        Route::patch('/{ticketId}/payment/status', [TicketController::class, 'updateStatusPayment'])
            ->name('payment.status');

        Route::get('/{ticketId}/payment/proof', [TicketController::class, 'proofPayment'])->name('payment.proof');
        Route::get('/{ticketId}/pdf', [TicketController::class, 'exportPdf'])->name('export_pdf');
    });

    Route::get('/cinemas/list', [CinemaController::class, 'cinemalist'])->name('cinemas.list');
    Route::get('/cinemas/{cinema_id}/schedules', [CinemaController::class, 'cinemaSchedules'])->name('cinemas.schedule');
    Route::get('/schedules/detail/{movie_id}', [MovieController::class, 'moviesSchedule'])->name('schedules.detail');
});


// admin
Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {

    Route::get('/tickets/chart', [TicketController::class, 'chart'])->name('tickets.chart');
    // Dashboard Admin
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // ----- CINEMAS -----
    Route::prefix('cinemas')->name('cinemas.')->group(function () {
        Route::get('/index', [CinemaController::class, 'index'])->name('index');
        Route::get('/create', [CinemaController::class, 'create'])->name('create');
        Route::post('/store', [CinemaController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
        Route::get('/trash', [CinemaController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [CinemaController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [CinemaController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [CinemaController::class, 'export'])->name('export');
        Route::get('/datatables', [CinemaController::class, 'datatables'])->name('datatables');
    });

    // ----- USERS -----
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('istaf');
        Route::get('/create', [UserController::class, 'create'])->name('cstaff');
        Route::post('/store', [UserController::class, 'store'])->name('sstaff');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('estaff');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('ustaff');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('dstaff');
        Route::get('/trash', [UserController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [UserController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::get('/datatables', [UserController::class, 'datatables'])->name('datatables');
    });

    // ----- MOVIES -----
    Route::prefix('movies')->name('movies.')->group(function () {
        Route::get('/chart', [MovieController::class, 'chart'])->name('chart');
        Route::get('/', [MovieController::class, 'index'])->name('index');
        Route::get('/create', [MovieController::class, 'create'])->name('create');
        Route::post('/store', [MovieController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [MovieController::class, 'destroy'])->name('destroy');
        Route::put('/nonaktif/{id}', [MovieController::class, 'nonaktif'])->name('nonaktif');
        Route::get('/trash', [MovieController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [MovieController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [MovieController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [MovieController::class, 'export'])->name('export');
        Route::get('/datatables', [MovieController::class, 'datatables'])->name('datatables');
    });
});


// staff
Route::middleware('isStaff')->prefix('staff')->name('staff.')->group(function () {

    // Dashboard Staff
    Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');

    // ----- PROMOS -----
    Route::prefix('promos')->name('promos.')->group(function () {
        Route::get('/index', [PromoController::class, 'index'])->name('index');
        Route::get('/create', [PromoController::class, 'create'])->name('create');
        Route::post('/store', [PromoController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
        Route::get('/trash', [PromoController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [PromoController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [PromoController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [PromoController::class, 'export'])->name('export');
        Route::get('/datatables', [PromoController::class, 'datatables'])->name('datatables');
    });

    // ----- SCHEDULES -----
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/index', [ScheduleController::class, 'index'])->name('index');
        Route::post('/store', [ScheduleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
        Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
        Route::get('/trash', [ScheduleController::class, 'trash'])->name('trash');
        Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
        Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete_permanent');
        Route::get('/export', [ScheduleController::class, 'export'])->name('export');
        Route::get('/datatables', [ScheduleController::class, 'datatables'])->name('datatables');
    });
});

Route::middleware('isGuest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::get('/signup', fn() => view('auth.signup'))->name('signup');
    Route::post('/signup', [UserController::class, 'register'])->name('signup.send_data');
    Route::post('/auth', [UserController::class, 'authentication'])->name('auth');

});
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
