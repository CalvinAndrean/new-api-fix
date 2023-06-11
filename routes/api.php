<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('/members', App\Http\Controllers\MemberController::class);

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::get('getUserById', 'App\Http\Controllers\AuthController@getUserById');
Route::put('updatePassword/{id_user}', 'App\Http\Controllers\AuthController@updatePassword');

// Route::group(['middleware' => 'auth:api'], function(){
    Route::get('members', 'App\Http\Controllers\MemberController@index');
    Route::post('members', 'App\Http\Controllers\MemberController@store');
    Route::get('members/{id_member}', 'App\Http\Controllers\MemberController@show');
    Route::put('members/{id_member}', 'App\Http\Controllers\MemberController@update');
    Route::put('members/changeExpired/{id_member}', 'App\Http\Controllers\MemberController@changeExpired');
    Route::put('members/addDepo/{id_member}', 'App\Http\Controllers\MemberController@addDepo');
    Route::put('members/resetPassword/{id_member}', 'App\Http\Controllers\MemberController@resetPassword');
    Route::get('members/membercard/{id_member}', 'App\Http\Controllers\MemberController@createMembercard');
    Route::get('members/showExpired/{id_member}', 'App\Http\Controllers\MemberController@showExpired');
    Route::put('members/deactivated/{id_member}', 'App\Http\Controllers\MemberController@deactivated');
    Route::delete('members/{id_member}', 'App\Http\Controllers\MemberController@destroy');
    Route::get('members/showProfile/{id_member}', 'App\Http\Controllers\MemberController@showProfile');

    Route::get('pegawais', 'App\Http\Controllers\PegawaiController@index');
    Route::post('pegawais', 'App\Http\Controllers\PegawaiController@store');
    Route::get('pegawais/{id_pegawai}', 'App\Http\Controllers\PegawaiController@show');
    Route::put('pegawais/{id_pegawai}', 'App\Http\Controllers\PegawaiController@update');
    Route::put('pegawais/updatePassword/{id_pegawai}', 'App\Http\Controllers\PegawaiController@updatePassword');
    Route::delete('pegawais/{id_pegawai}', 'App\Http\Controllers\PegawaiController@destroy');

    Route::get('instructors', 'App\Http\Controllers\InstructorController@index');
    Route::post('instructors', 'App\Http\Controllers\InstructorController@store');
    Route::get('instructors/{id_instructor}', 'App\Http\Controllers\InstructorController@show');
    Route::put('instructors/{id_instructor}', 'App\Http\Controllers\InstructorController@update');
    Route::put('instructors/updatePassword/{id_instructor}', 'App\Http\Controllers\InstructorController@updatePassword');
    Route::put('instructors/resetTotalLate/{id_instructor}', 'App\Http\Controllers\InstructorController@resetTotalLate');
    Route::delete('instructors/{id_instructor}', 'App\Http\Controllers\InstructorController@destroy');

    Route::get('cash_promos', 'App\Http\Controllers\CashPromoController@index');
    Route::post('cash_promos', 'App\Http\Controllers\CashPromoController@store');
    Route::get('cash_promos/{id_cash_promos}', 'App\Http\Controllers\CashPromoController@show');
    Route::put('cash_promos/{id_cash_promos}', 'App\Http\Controllers\CashPromoController@update');
    Route::delete('cash_promos/{id_cash_promos}', 'App\Http\Controllers\CashPromoController@destroy');

    Route::get('class_promos', 'App\Http\Controllers\ClassPromoController@index');
    Route::post('class_promos', 'App\Http\Controllers\ClassPromoController@store');
    Route::get('class_promos/{id_class_promos}', 'App\Http\Controllers\ClassPromoController@show');
    Route::put('class_promos/{id_class_promos}', 'App\Http\Controllers\ClassPromoController@update');
    Route::delete('class_promos/{id_class_promos}', 'App\Http\Controllers\ClassPromoController@destroy');

    Route::get('class_details', 'App\Http\Controllers\ClassDetailsController@index');
    Route::post('class_details', 'App\Http\Controllers\ClassDetailsController@store');
    Route::get('class_details/{id_class}', 'App\Http\Controllers\ClassDetailsController@show');
    Route::put('class_details/{id_class}', 'App\Http\Controllers\ClassDetailsController@update');
    Route::delete('class_details/{id_class}', 'App\Http\Controllers\ClassDetailsController@destroy');

    Route::get('class_runnings', 'App\Http\Controllers\ClassRunningController@index');
    Route::post('class_runnings', 'App\Http\Controllers\ClassRunningController@store');
    Route::get('class_runnings/{id_class_running}', 'App\Http\Controllers\ClassRunningController@show');
    Route::put('class_runnings/{id_class_running}', 'App\Http\Controllers\ClassRunningController@update');
    Route::delete('class_runnings/{id_class_running}', 'App\Http\Controllers\ClassRunningController@destroy');

    Route::get('class_running_dailies', 'App\Http\Controllers\ClassRunningDailyController@index');
    Route::post('class_running_dailies', 'App\Http\Controllers\ClassRunningDailyController@store');
    Route::get('class_running_dailies/{id_instructor}', 'App\Http\Controllers\ClassRunningDailyController@show');
    Route::get('class_running_dailies/showForUmum/{id_class_running_daily}', 'App\Http\Controllers\ClassRunningDailyController@showForUmum');
    Route::get('class_running_dailies/showClassToday/{id_instructor}', 'App\Http\Controllers\ClassRunningDailyController@showClassToday');
    Route::get('class_running_dailies/showClassTodayMO/{id_instructor}', 'App\Http\Controllers\ClassRunningDailyController@showClassTodayMO');
    Route::get('class_running_dailiesAbsent', 'App\Http\Controllers\ClassRunningDailyController@forAbsent');
    Route::put('class_running_dailies/{id_class_running_daily}', 'App\Http\Controllers\ClassRunningDailyController@update');
    Route::put('class_running_dailies/updateStartTime/{id_class_running_daily}', 'App\Http\Controllers\ClassRunningDailyController@updateStartTime');
    Route::put('class_running_dailies/updateEndTime/{id_class_running_daily}', 'App\Http\Controllers\ClassRunningDailyController@updateEndTime');
    Route::delete('class_running_dailies/{id_class_running_daily}', 'App\Http\Controllers\ClassRunningDailyController@destroy');

    Route::get('activation_reports', 'App\Http\Controllers\ActivationReportController@index');
    Route::post('activation_reports/{report_number_activation}', 'App\Http\Controllers\ActivationReportController@store');
    Route::get('activation_reports/{report_number_activation}', 'App\Http\Controllers\ActivationReportController@show');
    Route::get('activation_reports/showActivity/{id_member}', 'App\Http\Controllers\ActivationReportController@showActivity');
    Route::put('activation_reports/{report_number_activation}', 'App\Http\Controllers\ActivationReportController@update');
    Route::get('activation_reports/printReport/{report_number_activation}', 'App\Http\Controllers\ActivationReportController@printReport');
    Route::delete('activation_reports/{report_number_activation}', 'App\Http\Controllers\ActivationReportController@destroy');

    Route::get('deposit_cash_reports', 'App\Http\Controllers\DepositCashReportController@index');
    Route::post('deposit_cash_reports/{report_number_deposit_cash}', 'App\Http\Controllers\DepositCashReportController@store');
    Route::get('deposit_cash_reports/{report_number_deposit_cash}', 'App\Http\Controllers\DepositCashReportController@show');
    Route::get('deposit_cash_reports/showActivity/{id_member}', 'App\Http\Controllers\DepositCashReportController@showActivity');
    Route::put('deposit_cash_reports/{report_number_deposit_cash}', 'App\Http\Controllers\DepositCashReportController@update');
    Route::get('deposit_cash_reports/printReport/{report_number_deposit_cash}', 'App\Http\Controllers\DepositCashReportController@printReport');
    Route::delete('deposit_cash_reports/{report_number_deposit_cash}', 'App\Http\Controllers\DepositCashReportController@destroy');

    Route::get('deposit_class_reports', 'App\Http\Controllers\DepositClassReportController@index');
    Route::post('deposit_class_reports/{report_number_class_deposit}', 'App\Http\Controllers\DepositClassReportController@store');
    Route::get('deposit_class_reports/{report_number_class_deposit}', 'App\Http\Controllers\DepositClassReportController@show');
    Route::get('deposit_class_reports/showActivity/{id_member}', 'App\Http\Controllers\DepositClassReportController@showActivity');
    Route::put('deposit_class_reports/{report_number_class_deposit}', 'App\Http\Controllers\DepositClassReportController@update');
    Route::get('deposit_class_reports/printReport/{report_number_class_deposit}', 'App\Http\Controllers\DepositClassReportController@printReport');
    Route::delete('deposit_class_reports/{report_number_class_deposit}', 'App\Http\Controllers\DepositClassReportController@destroy');
    
    Route::get('deposit_classes', 'App\Http\Controllers\DepositClassController@index');
    Route::post('deposit_classes/{id_member}', 'App\Http\Controllers\DepositClassController@store');
    Route::get('deposit_classes/{id_member}', 'App\Http\Controllers\DepositClassController@show');
    Route::get('deposit_classes/showExpired/{id_deposit_class}', 'App\Http\Controllers\DepositClassController@showExpired');
    Route::put('deposit_classes/resetDepositClass/{id_deposit_class}', 'App\Http\Controllers\DepositClassController@resetDepositClass');

    Route::get('instructor_absents', 'App\Http\Controllers\InstructorAbsentController@index');
    Route::post('instructor_absents', 'App\Http\Controllers\InstructorAbsentController@store');
    Route::get('instructor_absents/{id_instructor}', 'App\Http\Controllers\InstructorAbsentController@show');
    Route::get('instructor_absents/showActivity/{id_instructor}', 'App\Http\Controllers\InstructorAbsentController@showActivity');
    Route::put('instructor_absents/{id_absent}', 'App\Http\Controllers\InstructorAbsentController@confirmAbsent');

    Route::get('instructor_presensis/showActivity/{id_instructor}', 'App\Http\Controllers\InstructorPresensiController@showActivity');

    Route::get('class_bookings', 'App\Http\Controllers\ClassBookingController@index');
    Route::post('class_bookings/{id_member}', 'App\Http\Controllers\ClassBookingController@store');
    Route::get('class_bookings/{id_member}', 'App\Http\Controllers\ClassBookingController@show');
    Route::get('class_bookings/showActivity/{id_member}', 'App\Http\Controllers\ClassBookingController@showActivity');
    Route::get('class_bookings/printReportDepositCash/{id_member}', 'App\Http\Controllers\ClassBookingController@printReportDepositCash');
    Route::get('class_bookings/printReportDepositClass/{id_member}', 'App\Http\Controllers\ClassBookingController@printReportDepositClass');
    Route::get('class_bookings/insideClass/{id_class_running_daily}', 'App\Http\Controllers\ClassBookingController@showInsideClass');
    // Route::get('class_bookings/classToday/{id_instructor}', 'App\Http\Controllers\ClassBookingController@showClassToday');
    Route::put('class_bookings/{id_class_booking}', 'App\Http\Controllers\ClassBookingController@presensi');
    Route::delete('class_bookings/{id_class_booking}', 'App\Http\Controllers\ClassBookingController@destroy');

    Route::get('gym_bookings', 'App\Http\Controllers\GymBookingController@index');
    Route::get('gym_bookings/{id_member}', 'App\Http\Controllers\GymBookingController@show');
    Route::get('gym_bookings/showActivity/{id_member}', 'App\Http\Controllers\GymBookingController@showActivity');
    Route::post('gym_bookings/{id_gym_session}', 'App\Http\Controllers\GymBookingController@store');
    Route::put('gym_bookings/{id_gym_booking}', 'App\Http\Controllers\GymBookingController@update');
    Route::delete('gym_bookings/{id_gym_booking}', 'App\Http\Controllers\GymBookingController@destroy');
    Route::get('gym_bookings/printReport/{id_gym_booking}', 'App\Http\Controllers\GymBookingController@printReport');

    Route::get('gym_sessions', 'App\Http\Controllers\GymSessionController@index');

    Route::get('laporan_pendapatan_tahunan', 'App\Http\Controllers\LaporanPendapatanTahunanController@index');
    Route::get('laporan_pendapatan_tahunan/{tahun}', 'App\Http\Controllers\LaporanPendapatanTahunanController@laporan');

    Route::get('laporan_aktivitas_kelas', 'App\Http\Controllers\LaporanAktivitasKelasController@index');
    Route::get('laporan_aktivitas_kelas/{id_laporan_aktivitas_kelas}', 'App\Http\Controllers\LaporanAktivitasKelasController@laporan');

    Route::get('laporan_aktivitas_gym', 'App\Http\Controllers\LaporanAktivitasGymController@index');
    Route::get('laporan_aktivitas_gym/{id_laporan_aktivitas_gym}', 'App\Http\Controllers\LaporanAktivitasGymController@laporan');

    Route::get('laporan_kinerja_instructor', 'App\Http\Controllers\LaporanKinerjaInstructorController@index');
    Route::get('laporan_kinerja_instructor/{id_laporan_kinerja_instructor}', 'App\Http\Controllers\LaporanKinerjaInstructorController@laporan');

// });
    