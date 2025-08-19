<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| 
*/

// ================== LOGIN ==================

Route::get('/login', [LoginController::class, 'formClient'])->name('login');
Route::post('/login/client', [LoginController::class, 'loginClient'])->name('login.client');

Route::get('/login/salon', [LoginController::class, 'formSalon'])->name('login.salon');
Route::post('/login/salon', [LoginController::class, 'loginSalon'])->name('login.salon.post');

// ================== REGISTER ==================

Route::get('/register', [RegisterController::class, 'formClient'])->name('register');
Route::post('/register', [RegisterController::class, 'registerClient'])->name('register.client');

Route::get('/register/salon', [RegisterController::class, 'formSalon'])->name('register.salon');
Route::post('/register/salon', [RegisterController::class, 'registerSalon'])->name('register.salon.post');

// ================== LOGOUT ==================

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ================== HOME ==================

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', [ClientController::class, 'homePage'])->name('home');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::get('/salons', [SalonController::class, 'lists'])->name('salons.lists');
Route::get('/salon/salon_detail/{salon_id}', [SalonController::class, 'detail'])->name('salon.detail');

Route::middleware('auth:client')->group(function() {
    Route::get('/client/profile', [ClientController::class, 'profile'])->name('client.profile');
    Route::get('/client/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/client/update', [ClientController::class, 'update'])->name('client.update');
    Route::post('/client/logout', [LoginController::class, 'logout'])->name('client.logout');

    Route::get('/reservation/create/{salon}', [ReservationController::class, 'create'])->name('reservation.create');
    Route::post('/reservation/store', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/reservation/getBookedDates/{salon_id}', [ReservationController::class, 'getBookedDates']);
    Route::get('/reservation/getBukaDates/{salon_id}/{year}/{month}', [ReservationController::class, 'getBukaDates']);
    Route::get('/reservation/getslots/{date}/{salon_id}', [ReservationController::class, 'getSlots']);
    Route::post('/reservation/validate-voucher', [ReservationController::class, 'validateVoucher'])->name('reservation.validate-voucher');
    Route::get('/reservation/{id}/detail', [ReservationController::class, 'showDetail'])->name('reservation.detail');

    Route::get('/reservation/history', [ReservationController::class, 'history'])->name('reservation.history');
    Route::get('/reservation/filter', [ReservationController::class, 'filterByStatus'])->name('reservation.filter');
});

// ================== SALON DASHBOARD ==================
Route::middleware('auth:salon')->group(function () {
    Route::get('/salon/home', [SalonController::class, 'homePage'])->name('salon.home');

    Route::get('/salon/clients', [SalonController::class, 'listClients'])->name('salon.clients');

    Route::get('/salon/getSalonReservations', [SalonController::class, 'getSalonReservations'])->name('salon.getReservations');
    Route::get('/salon/reservation/{id}/detail', [ReservationController::class, 'salonDetail'])->name('salon.reservation.detail');
    Route::post('/reservation/update-status', [ReservationController::class, 'updateStatus'])->name('reservation.updateStatus');

    Route::get('/salon', [SalonController::class, 'account'])->name('salon.account');
    Route::get('/salon/edit', [SalonController::class, 'edit'])->name('salon.edit');
    Route::post('/salon/update', [SalonController::class, 'update'])->name('salon.update');

    Route::get('/gallery/add', [GalleryController::class, 'create'])->name('salon.gallery.add');
    Route::post('/salon/gallery/store', [GalleryController::class, 'store'])->name('salon.gallery.store');
    Route::get('/gallery/edit/{id}', [GalleryController::class, 'edit'])->name('salon.gallery.edit');
    Route::post('/gallery/update', [GalleryController::class, 'update'])->name('salon.gallery.update');
    Route::post('/gallery/delete', [GalleryController::class, 'delete'])->name('salon.gallery.delete');


    Route::get('/treatment/add', [TreatmentController::class, 'create'])->name('treatment.add');
    Route::post('/treatment/store', [TreatmentController::class, 'store'])->name('treatment.store');
    Route::get('/treatment/edit/{id}', [TreatmentController::class, 'edit'])->name('treatment.edit');
    Route::post('/treatment/update', [TreatmentController::class, 'update'])->name('treatment.update');
    Route::post('/treatment/delete', [TreatmentController::class, 'delete'])->name('treatment.delete');

    Route::get('/voucher/add', [VoucherController::class, 'create'])->name('voucher.add');
    Route::post('/voucher/store', [VoucherController::class, 'store'])->name('voucher.store');
    Route::get('/voucher/edit/{id}', [VoucherController::class, 'edit'])->name('voucher.edit');
    Route::post('/voucher/update', [VoucherController::class, 'update'])->name('voucher.update');
    Route::post('/voucher/delete', [VoucherController::class, 'delete'])->name('voucher.delete');

    Route::get('/salon/schedule/form', [ScheduleController::class, 'form'])->name('salon.schedule.form');
    Route::post('/schedule/save', [ScheduleController::class, 'save'])->name('schedule.save');
    Route::post('/salon/schedule/fetch', [ScheduleController::class, 'fetchSchedule'])->name('salon.schedule.fetch');
    
    Route::post('/salon/logout', [LoginController::class, 'logout'])->name('salon.logout');
});