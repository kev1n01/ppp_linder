<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use CodersFree\LaravelGreenter\Facades\Greenter;
use CodersFree\LaravelGreenter\Facades\GreenterReport;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InvoiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index() {
    return Inertia::render('invoice/Invoices');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $data = [
      "ublVersion" => "2.1",
      "tipoOperacion" => "0101", // Catálogo 51 Venta lnterna/exportacion/anticipos/etc
      "tipoDoc" => "01", // Catálogo 01 tipo de documento factura/boleta/etc
      "serie" => "F001",
      "correlativo" => "1",
      "fechaEmision" => now(),
      "formaPago" => [
        'tipo' => 'Contado',
      ],
      "tipoMoneda" => "PEN", // Catálogo 02
      "client" => [
        "tipoDoc" => "6", // Catálogo 06
        "numDoc" => "20000000001",
        "rznSocial" => "EMPRESA X",
      ],
      "mtoOperGravadas" => 100.00,
      "mtoIGV" => 18.00,
      "totalImpuestos" => 18.00,
      "valorVenta" => 100.00,
      "subTotal" => 118.00,
      "mtoImpVenta" => 118.00,
      "details" => [
        [
          "codProducto" => "P001",
          "unidad" => "NIU", // Catálogo 03
          "cantidad" => 2,
          "mtoValorUnitario" => 50.00,
          "descripcion" => "PRODUCTO 1",
          "mtoBaseIgv" => 100,
          "porcentajeIgv" => 18.00,
          "igv" => 18.00,
          "tipAfeIgv" => "10",
          "totalImpuestos" => 18.00,
          "mtoValorVenta" => 100.00,
          "mtoPrecioUnitario" => 59.00,
        ],
      ],
      "legends" => [
        [
          "code" => "1000", // Catálogo 15
          "value" => "SON CIENTO DIECIOCHO CON 00/100 SOLES",
        ],
      ],
    ];

    try {
      $response = Greenter::send('invoice', $data);

      $name = $response->getDocument()->getName();
      Storage::put("sunat/xml/{$name}.xml", $response->getXml());
      Storage::put("sunat/cdr/{$name}.zip", $response->getCdrZip());

      $html = GreenterReport::setParams([
        'system' => [
          'logo' => public_path('images/logo.webp'),
          'hash' => '',
        ],
        'user' => [
          'header' => 'Telf: <b>(01) 123456</b>',
          'extras' => [
            ['name' => 'CONDICIÓN DE PAGO', 'value' => 'Contado'],
            ['name' => 'VENDEDOR', 'value' => 'VENDEDOR SECUNDARIO'],
          ],
          'footer' => '<p>Nro Resolución: <b>123456789</b></p>',
        ]
      ])->generateHtml($response->getDocument());

      return $html;
    } catch (\Throwable $e) {
      return response()->json([
        'success' => false,
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Invoice $invoice)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Invoice $invoice)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Invoice $invoice)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Invoice $invoice)
  {
    //
  }
}
