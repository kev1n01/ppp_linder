<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Detalle de Venta - {{ $sale->customer->cu_name }} - {{ getCodFromUUID($sale->uuid) }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-6">

    <!-- ENCABEZADO: Datos del negocio -->
    <header class="p-4 mb-6 flex items-center justify-center">
        <div class="flex gap-4">
            @if($infoBusiness->set_logo)
              <div class="w-1/2">
                <img src="{{ public_path('storage/' . $infoBusiness->set_logo) }}" alt="Logo" class="w-36 object-cover">
              </div>
            @endif
            <div> 
                <h1 class="text-xl font-bold">{{ $infoBusiness->set_name_business }}</h1>
                <p class="text-sm">{{ $infoBusiness->set_address }} - {{ $infoBusiness->set_district }}, {{ $infoBusiness->set_province }}, {{ $infoBusiness->set_department }}</p>
                <p class="text-sm text-gray-600"><strong>RUC:</strong> {{ $infoBusiness->set_ruc }}</p>
                <p class="text-sm"><strong>Tel:</strong> {{ $infoBusiness->set_phone }}</p>
            </div>
        </div>
    </header>

    <!-- DETALLES DE LA VENTA -->
    <section class="bg-white p-4 rounded-2xl shadow mb-3">
        <h2 class="text-lg font-bold mb-2 border-b-2">Detalles de la venta</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <p><span class="font-semibold">Codigo:</span> {{ getCodFromUUID($sale->uuid) }}</p>
            <p><span class="font-semibold">Fecha:</span> {{ \Carbon\Carbon::parse($sale->sal_date)->format('d/m/Y') }}</p>
            <p><span class="font-semibold">Método de pago:</span> {{ ucfirst($sale->sal_payment_method) }}</p>
            <p><span class="font-semibold">Atendido por:</span>  {{ $sale->employee->user->name }}</p>
        </div>
    </section>

    <!-- DATOS DEL CLIENTE -->
    <section class="bg-white p-4 rounded-2xl shadow mb-3">
        <h2 class="text-lg font-bold mb-2 border-b-2">Datos del cliente</h2>
        <div class="text-sm grid grid-cols-2 gap-4">
            <p><span class="font-semibold">Nombre:</span> {{ $sale->customer->cu_name }}</p>
            <p><span class="font-semibold">DNI/RUC:</span> {{ $sale->customer->cu_num_doc }}</p>
            <p><span class="font-semibold">Correo:</span> {{ $sale->customer->cu_email ?? 'No registrado' }}</p>
            <p><span class="font-semibold">Teléfono:</span> {{ $sale->customer->cu_phone ?? 'No registrado' }}</p>
        </div>
    </section>

    <!-- TABLA DE DETALLES -->
    <section class="bg-white p-4 rounded-2xl shadow">
        <h2 class="text-lg font-bold mb-4 border-b-2">Servicios y/o productos</h2>
        <div class="overflow-x-auto">
            <table class="w-full border-y-2 border-gray-300 rounded">
                <thead>
                    <tr class="bg-gray-100 text-left text-sm">
                        <th class="p-2 border">#</th>
                        <th class="p-2 border">Servicio</th>
                        <th class="p-2 border">Precio unitario</th>
                        <th class="p-2 border">Cantidad</th>
                        <th class="p-2 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleDetails as $index => $detail)
                        <tr class="text-sm">
                            <td class="p-2">{{ $index + 1 }}</td>
                            <td class="p-2">{{ $detail->item->ite_name }}</td>
                            <td class="p-2">S/ {{ number_format($detail->sald_price, 2) }}</td>
                            <td class="p-2">{{ $detail->sald_quantity }}</td>
                            <td class="p-2">S/ {{ number_format($detail->sald_subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                  <tr class="bg-gray-50 font-bold text-sm">
                      <td colspan="4" class="p-2 text-right">TOTAL:</td>
                      <td class="p-2">S/ {{ number_format($sale->sal_total_amount, 2) }}</td>
                  </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="mt-6 text-center text-sm text-gray-500">
        <p>Documento generado automáticamente por el sistema | {{ now()->format('d/m/Y H:i') }}</p>
    </footer>

</body>
</html>