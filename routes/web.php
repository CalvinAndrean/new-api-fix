<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('members', 'MemberController@index')->middleware('auth', 'admin', 'kasir');
// Route::post('members', 'MemberController@store')->middleware('auth', 'admin', 'kasir');
// Route::get('members/{id_member}', 'MemberController@show')->middleware('auth', 'admin', 'kasir');
// Route::put('members/{id_member}', 'MemberController@update')->middleware('auth', 'admin', 'kasir');
// Route::put('members/resetPassword/{id_member}', 'MemberController@resetPassword')->middleware('auth', 'admin', 'kasir');
// Route::delete('members/{id_member}', 'MemberController@destroy')->middleware('auth', 'admin', 'kasir');

// Route::get('instructors', 'InstructorController@index')->middleware('auth', 'admin');
// Route::post('instructors', 'InstructorController@store')->middleware('auth', 'admin');
// Route::get('instructors/{id_instructor}', 'InstructorController@show')->middleware('auth', 'admin');
// Route::put('instructors/{id_instructor}', 'InstructorController@update')->middleware('auth', 'admin');
// Route::delete('instructors/{id_instructor}', 'InstructorController@destroy')->middleware('auth', 'admin');

// Route::get('pegawais', 'PegawaiController@index')->middleware('auth', 'admin');
// Route::post('pegawais', 'PegawaiController@store')->middleware('auth', 'admin');
// Route::get('pegawais/{id_pegawai}', 'PegawaiController@show')->middleware('auth', 'admin');
// Route::put('pegawais/{id_pegawai}', 'PegawaiController@update')->middleware('auth', 'admin');
// Route::put('pegawais/updatePassword/{id_pegawai}', 'PegawaiController@updatePassword')->middleware('auth', 'admin');
// Route::delete('pegawais/{id_pegawai}', 'PegawaiController@destroy')->middleware('auth', 'admin');

// Route::get('cash_promos', 'CashPromoController@index')->middleware('auth', 'admin');
// Route::post('cash_promos', 'CashPromoController@store')->middleware('auth', 'admin');
// Route::get('cash_promos/{id_cash_promos}', 'CashPromoController@show')->middleware('auth', 'admin');
// Route::put('cash_promos/{id_cash_promos}', 'CashPromoController@update')->middleware('auth', 'admin');
// Route::delete('cash_promos/{id_cash_promos}', 'CashPromoController@destroy')->middleware('auth', 'admin');

// Route::get('class_promos', 'ClassPromoController@index')->middleware('auth', 'admin');
// Route::post('class_promos', 'ClassPromoController@store')->middleware('auth', 'admin');
// Route::get('class_promos/{id_class_promos}', 'ClassPromoController@show')->middleware('auth', 'admin');
// Route::put('class_promos/{id_class_promos}', 'ClassPromoController@update')->middleware('auth', 'admin');
// Route::delete('class_promos/{id_class_promos}', 'ClassPromoController@destroy')->middleware('auth', 'admin');
