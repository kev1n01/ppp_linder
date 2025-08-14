<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/market/list', [MarketController::class, 'index'])->name('market.index');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/pdf.php';

Route::any('{any}', function () {
    return Inertia::render('NotFound');
})->where('any', '.*');
