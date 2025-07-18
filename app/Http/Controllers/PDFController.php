<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Facades\Pdf;

class PDFController extends Controller
{
    public function verPdfVenta(Sale $sale)
    {
      $infoBusiness = Setting::first();
      return Pdf::view('pdf.venta', ['sale' => $sale, 'infoBusiness' => $infoBusiness])
        ->format(Format::A4);
    }

    public function downloadPdfVenta(Sale $sale)
    {
      $infoBusiness = Setting::first();
      return Pdf::view('pdf.venta', ['sale' => $sale, 'infoBusiness' => $infoBusiness])
        ->name('venta_' . strtoupper(explode('-', $sale->uuid)[0]) .'.pdf')
        ->format(Format::A4)
        ->download();
    }
}