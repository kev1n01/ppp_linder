<div class="p-4">
  <!-- Filtro dinámico -->
  <div class="mb-4 flex justify-end items-center gap-4">
    <label class="block mb-2 font-semibold">Filtrar por:</label>
    <x-filament::input.wrapper>
        <x-filament::input.select wire:model.live="selectedType">
            <option value="">Todos</option>
            <option value="producto">Producto</option>
            <option value="servicio">Servicio</option>
        </x-filament::input.select>
    </x-filament::input.wrapper>
    
    <!-- Spinner: visible solo mientras cambia el filtro -->
    {{-- <div wire:loading wire:target="selectedType">
      <x-filament::loading-indicator class="h-5 w-5 text-primary-600" />
    </div> --}}
  </div>


  <div class="grid grid-cols-1 md:grid-cols-3 gap-4" wire:loading.remove wire:target="selectedType, page">
      @foreach($items as $item)
        <x-filament::card>
            <x-filament::avatar
                class="mb-4"
                :circular="false"
                src="{{ $item->ite_image ? asset('storage/'.$item->ite_image) : asset('images/no-image.jpg') }}" 
                alt="{{ $item->ite_name }}"
                size="w-40 h-40"
            />

            <x-filament::section>
                <x-slot name="heading">
                  <h3 class="text-lg font-bold">
                    {{ $item->ite_name }}
                  </h3>
                </x-slot>

                <x-slot name="description">
                  {{ $item->ite_description }}
                </x-slot>

                {{-- Content --}}
                <x-filament::badge>
                  <span class="text-lg">S/. {{ number_format($item->ite_price, 2) }}</span>
                </x-filament::badge>
            </x-filament::section>
        </x-filament::card>
      @endforeach
  </div>

  <div
      wire:loading.flex
      wire:target="selectedType, page"
      class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm z-10 justify-center items-center"
  >
      <x-filament::loading-indicator class="h-8 w-8 text-primary-300" />
  </div>
  <div class="mt-6 flex justify-center items-center gap-2" wire:loading.remove wire:target="selectedType">
    <x-filament::pagination :paginator="$items" extreme-links />
  </div>
</div>  