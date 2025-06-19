<?php

use App\Http\Controllers\Invoices\InvoiceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
  Route::get('reminders', function () {
    return Inertia::render('Reminder');
  })->name('reminders');

  Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoices', 'index')->name('invoices.index');
  });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::any('{any}', function () {
    return Inertia::render('NotFound');
})->where('any', '.*');
