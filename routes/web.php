<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

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
    return view('index');
});

Route::get('/booking/create', [BookingController::class, 'create']);
Route::get('/booking/patient-login', [BookingController::class, 'patientLogin']);
Route::get('/booking/my-bookings/{firstname}/{secondname}/{email}', [BookingController::class, 'myBookings'])->name('myBookings');
Route::post('/booking/cancel-booking', [BookingController::class, 'cancelBooking']);
Route::get('/booking/get-config', [BookingController::class, 'getConfig']);
Route::get('/booking/get-fully-booked-dates', [BookingController::class, 'getFullyBookedDates']);
Route::get('/booking/get-booked-slots-by-date', [BookingController::class, 'getBookedSlotsByDate']);
Route::post('/booking', [BookingController::class, 'store']);