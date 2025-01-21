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

Route::get('home/dashboard', [Home::class, 'dashboard'])->name('dashboard');

Route::get('home/login',  [Home::class, 'login'])->name('login');

Route::post('home/aksi_login', [Home::class, 'aksi_login'])->name('aksi_login');

Route::get('/setting', [Home::class, 'setting'])->name('setting');

Route::post('/aksi_e_setting', [Home::class, 'aksi_e_setting'])->name('aksi_e_setting');

Route::get('/error404', [Home::class, 'error404'])->name('error404');

Route::get('/logout', [Home::class, 'logout'])->name('logout');

Route::get('/activity', [Home::class, 'activity'])->name('activity');

Route::get('/hapus_activity/{id}', [Home::class, 'hapus_activity'])->name('hapus_activity');
