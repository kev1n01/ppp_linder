<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('reminders', function () {
    return Inertia::render('Reminder');
})->middleware(['auth', 'verified'])->name('reminders');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

Route::any('{any}', function () {
    return Inertia::render('NotFound');
})->where('any', '.*');
