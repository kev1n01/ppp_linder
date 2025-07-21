<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Invoices\InvoiceController;
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/market/list', [MarketController::class, 'index'])->name('market.index');

// Route::get('/home', function () {
//     return redirect('login');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//   Route::get('reminders', function () {
//     return Inertia::render('Reminder');
//   })->name('reminders');

//   Route::controller(InvoiceController::class)->group(function () {
//     Route::get('/invoices', 'index')->name('invoices.index');
//   });
// });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/pdf.php';

Route::any('{any}', function () {
    return Inertia::render('NotFound');
})->where('any', '.*');
