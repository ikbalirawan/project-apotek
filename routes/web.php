<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('IsGuest')->group(function () {
Route::get('/', function () {
    return view('login');
})->name('login');
Route::post('/login', [AccountController::class, 'authLogin'])->name('auth-login');
});

Route::middleware('IsLogin')->group(function () {
    Route::get('/logout', [AccountController::class, 'logout'])->name('auth-logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    //prefix : awalan (mengelompokan url path sesuai dengan fiturnya)
    //name prefix : awalan name route pada kelompok url path 
    //group : mengelompokan url path
    //::get -> menampilkan halaman, ::post -> menambah data ke db, ::patch -> mengubah data ke db, ::delete -> menghapus data ke db
    //NamaController::class, 'namafunction'
    //name() -> nama route yang dipanggil di href/action

    Route::middleware('IsAdmin')->group(function () {
        Route::prefix('/medicine')->name('medicine.')->group(function () {
            Route::get('/data', [MedicineController::class, 'index'])->name('data');
            Route::get('/create', [MedicineController::class, 'create'])->name('create');
            Route::post('/store', [MedicineController::class, 'store'])->name('store');
            //path dinamis/parameter path : untuk mengirim data identitas yang akan diambil, datanya harus diisi ketika pemanggilan name route
            Route::get('/edit/{id}', [MedicineController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [MedicineController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [MedicineController::class, 'destroy'])->name('delete');
            Route::get('/data/stock', [MedicineController::class, 'stock'])->name('stock');
            Route::get('/{id}', [MedicineController::class, 'show'])->name('show');
            Route::get('/data/stock/{id}', [MedicineController::class, 'stockEdit'])->name('stock.edit');
            Route::patch('/data/stock/{id}', [MedicineController::class, 'stockUpdate'])->name('stock.update');
        });

        Route::prefix('/account')->name('account.')->group(function () {
            Route::get('/user/data', [AccountController::class, 'index'])->name('user');
            Route::get('/user/create', [AccountController::class, 'create'])->name('create');
            Route::post('/user/store', [AccountController::class, 'store'])->name('store');
            Route::get('/user/edit/{id}', [AccountController::class, 'edit'])->name('edit');
            Route::patch('/user/update/{id}', [AccountController::class, 'update'])->name('update');
            Route::delete('/user/delete/{id}', [AccountController::class, 'destroy'])->name('delete');
        });

        Route::prefix('/data/order')->name('data.order.')->group(function(){
            Route::get('/', [OrderController::class, 'admin'])->name('index');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDFAdmin'])->name('download');
            Route::get('/search', [OrderController::class, 'searchAdmin'])->name('search');
            Route::get('/download-excel', [OrderController::class, 'downloadExcel'])->name('download-excel');
        });

    });
    
    Route::middleware('IsKasir')->group(function () {
        Route::prefix('/order')->name('order.')->group(function(){
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/create', [OrderController::class, 'create'])->name('create');
            Route::post('/store', [OrderController::class, 'store'])->name('store');
            Route::get('/print/{id}', [OrderController::class, 'show'])->name('print');
            Route::get('/download/{id}', [OrderController::class, 'downloadPDF'])->name('download');
            Route::get('/search', [OrderController::class, 'search'])->name('search');
        });
    });
});
