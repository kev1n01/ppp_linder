<x-filament-panels::page>
  <div class="max-w-6xl w-full h-dvh mx-auto p-6  text-slate-500 shadow-md rounded mt-10 border font-sans">
    <!-- Header -->
    <h1 class="font-bold text-slate-400 uppercase text-center" style="padding-bottom: 15px; font-size: 20px;">{{ $info->set_name_business }}</h1>
    <div class="border-b mb-8 flex items-center justify-between" style="padding-bottom: 20px;">
      <div class="w-1/2 text-left">
        <p class="text-sm text-slate-500" style="text-transform: capitalize;"><strong>Dirección:</strong>{{ $info->set_address }} - {{ $info->set_district }}, {{ $info->set_province }}, {{ $info->set_department }}</p>
        <p class="text-sm text-slate-500"><strong>RUC:</strong> {{ $info->set_ruc }}</p>
        <p class="text-sm text-slate-500"><strong>Telf:</strong> {{ $info->set_phone }}</p>
        <p class="text-sm text-slate-500"><strong>Email:</strong> contact@tiburon.com</p>
      </div>
        @if($info->set_logo)
          <img src="{{ asset('storage/' . $info->set_logo) }}" alt="Logo" class="w-32 object-cover">
        @endif
    </div>

    <div class="pb-4 mb-4 flex items-start">
      <!-- Customer Information -->
      <div class="mb-4">
        <h2 class="font-semibold text-slate-700">Detalles de cliente</h2>
        <hr>
        <p class="text-slate-600">{{ $record->customer->cu_name }}</p>
        <p class="text-sm text-slate-500">N° doc: {{ $record->customer->cu_num_doc }}</p>
        <p class="text-sm text-slate-500">Dirección: {{ $record->customer->address ?? 'No registrado' }}</p>
        <p class="text-sm text-slate-500">Email: {{ $record->customer->cu_email ?? 'No registrado' }}</p>
        <p class="text-sm text-slate-500">Telf: {{ $record->customer->cu_phone ?? 'No registrado' }}</p>
      </div>

      <!-- Invoice Information -->  
      <div class="mb-4 text-right flex-1">
        <h2 class="font-semibold text-slate-700">Detalles de venta</h2>
        <hr>
        <p class="text-sm text-slate-500">Codigo: {{ getCodFromUUID($record->uuid) }}</p>
        <p class="text-sm text-slate-500">Fecha: {{ \Carbon\Carbon::parse($record->sal_date)->format('d/m/Y') }}</p>
        <p class="text-sm text-slate-500">Atencion: {{ $record->user->name }}</p>
      </div>
    </div>

    <!-- Invoice Table -->
    <table class="w-full table-auto mb-24">
      <thead>
        <tr class="border">
          <th class="px-4 py-2 text-left">#</th>
          <th class="px-4 py-2 text-center">Nombre</th>
          <th class="px-4 py-2 text-center">Precio unitario</th>
          <th class="px-4 py-2 text-center">Cantidad</th>
          <th class="px-4 py-2 text-center">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($record->saleDetails as $index => $detail)
          <tr>
            <td class="border px-4 py-2">{{ $index + 1 }}</td>
            <td class="border px-4 py-2">{{ $detail->item->ite_name }}</td>
            <td class="border px-4 py-2 text-center">S/ {{ number_format($detail->sald_price, 2) }}</td>
            <td class="border px-4 py-2 text-center">{{ $detail->sald_quantity }}</td>
            <td class="border px-4 py-2 text-center">S/ {{ number_format($detail->sald_subtotal, 2) }}</td>
          </tr>
        @endforeach
        <tr class="font-bold">
          <td colspan="4" class="border px-4 py-2 text-right">Total</td>
          <td class="border px-4 py-2 text-center">S/ {{ number_format($record->sal_total_amount, 2) }}</td>
        </tr>
      </tbody>
    </table>

    <div class="mb-20" style="padding-bottom: 30px;"></div>

    <!-- Footer -->
    <div class="text-sm text-slate-500 border-t pt-8 mt-8 flex justify-center items-center flex-col space-y-5">
      <p>Servicio y productos a un justo razonable</p>
      <p>Bank Account: 123456789, Routing Number: 000111222</p>
      <p>Gracias por visitarnos!</p>
    </div>
  </div>
</x-filament-panels::page>
