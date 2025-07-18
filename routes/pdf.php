<?php

use App\Http\Controllers\PDFController;
use Illuminate\Support\Facades\Route;

Route::get('/ver/pdf/venta/{sale}', [PDFController::class, 'verPdfVenta'])->name('ver.pdf.venta');

Route::get('/download/pdf/venta/{sale}', [PDFController::class, 'downloadPdfVenta'])->name('download.pdf.venta');