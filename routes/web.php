<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
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
//view

Route::get('home/dashboard', [Home::class, 'dashboard'])->name('dashboard');

Route::get('home/login',  [Home::class, 'login'])->name('login');

Route::get('home/setting', [Home::class, 'setting'])->name('setting');

Route::get('home/error404', [Home::class, 'error404'])->name('error404');

Route::get('home/logout', [Home::class, 'logout'])->name('logout');

Route::get('home/activity', [Home::class, 'activity'])->name('activity');

Route::get('home/hapus_activity/{id}', [Home::class, 'hapus_activity'])->name('hapus_activity');

Route::get('home/profile', [Home::class, 'profile'])->name('profile');

Route::get('home/event', [Home::class, 'event'])->name('event');

Route::get('home/hapus_event/{id}', [Home::class, 'hapus_event'])->name('hapus_event');

Route::get('home/jadwal', [Home::class, 'jadwal'])->name('jadwal');

Route::get('home/hapus_jadwal/{id}', [Home::class, 'hapus_jadwal'])->name('hapus_jadwal');

//aksi

Route::post('home/aksi_login', [Home::class, 'aksi_login'])->name('aksi_login');

Route::post('home/aksi_e_setting', [Home::class, 'aksi_e_setting'])->name('aksi_e_setting');

Route::post('home/editfoto', [Home::class, 'editfoto'])->name('editfoto');

Route::post('home/aksi_t_event', [Home::class, 'aksi_t_event'])->name('aksi_t_event');

Route::post('home/aksi_e_event', [Home::class, 'aksi_e_event'])->name('aksi_e_event');

Route::post('home/aksi_t_jadwal', [Home::class, 'aksi_t_jadwal'])->name('aksi_t_jadwal');

Route::post('home/aksi_e_jadwal', [Home::class, 'aksi_e_jadwal'])->name('aksi_e_jadwal');