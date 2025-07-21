<script setup lang="ts">
import { reactive, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Navbar from '@/Components/home/Navbar.vue'
import Footer from '@/Components/home/Footer.vue'
import WhatsAppButton from '@/Components/home/WhatsAppButton.vue'
import ItemCard from '@/Components/home/ItemCard.vue'
import Pagination from '@/Components/home/Pagination.vue'
import { Head } from '@inertiajs/vue3';

interface Item {
  id: number
  ite_name: string
  ite_description?: string
  ite_price: number
  ite_image?: string | null
  ite_type?: string
  ite_discount?: number
}

interface Paginator<T> {
  data: T[]
  links: Array<{ url: string|null; label: string; active: boolean }>
  current_page: number
  last_page: number
  per_page: number
  total: number
}

interface Props {
  items: Paginator<Item>
  filters: {
    search?: string | null
    type?: string | null
    min_price?: number | null
    max_price?: number | null
    per_page?: number | null
  }
}

const props = defineProps<Props>()

// Estado local de filtros (editable en UI)
const state = reactive({
  search: props.filters.search ?? '',
  type: props.filters.type ?? '',
  min_price: props.filters.min_price ?? '',
  max_price: props.filters.max_price ?? '',
  per_page: props.filters.per_page ?? 6,
})

// Debounce manual
let debounceTimer: number | undefined
watch(state, () => {
  clearTimeout(debounceTimer)
  debounceTimer = window.setTimeout(() => {
    router.get(route('market.index'), { ...state }, {
      preserveState: true,
      replace: true,
      preserveScroll: true,
    })
  }, 500)
}, { deep: true })

function resetFilters() {
  state.search = ''
  state.type = ''
  state.min_price = ''
  state.max_price = ''
  state.per_page = 6
  
  // Recargar con filtros limpios
  router.get(route('market.index'), {}, {
    preserveState: false,
    replace: true,
    preserveScroll: true,
  })
}
</script>

<template>
  <div>
    <Head title="Market" />

    <Navbar />

    <section class="max-w-7xl mx-auto px-4 py-8">
      <div class="flex justify-between">
        <h1 class="text-2xl font-bold mb-6 text-white">Catálogo de productos y servicios</h1>
        <button @click="resetFilters" class="hover:underline">Limpiar filtros</button>
      </div>

      <!-- Barra de filtros -->
      <div class="grid gap-4 md:grid-cols-5 mb-8 items-end text-white">
        <div class="md:col-span-2">
          <label class="block text-sm font-semibold mb-1">Buscar</label>
          <input v-model="state.search" type="text" placeholder="Buscar por nombre del producto" class="w-full border rounded p-2 text-black"/>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Tipo</label>
          <select v-model="state.type" class="w-full border rounded p-2 text-black">
            <option value="">Todos</option>
            <option value="producto">Producto</option>
            <option value="servicio">Servicio</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Precio mín.</label>
          <input v-model.number="state.min_price" type="number" min="0" step="0.01" class="w-full border rounded p-2 text-black" placeholder="Ingresa precio mínimo"/>
        </div>
        <div>
          <label class="block text-sm font-semibold mb-1">Precio máx.</label>
          <input v-model.number="state.max_price" type="number" min="0" step="0.01" class="w-full border rounded p-2 text-black" placeholder="Ingresa precio máximo"/>
        </div>
      </div>

      <!-- Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <ItemCard
          v-for="item in items.data"
          :key="item.id"
          :name="item.ite_name"
          :description="item.ite_description"
          :price="item.ite_price"
          :image="item.ite_image"
          :discount="item.ite_discount"
        />
      </div>

      <!-- Paginación -->
      <div class="mt-8 flex justify-center">
        <Pagination :links="items.links" />
      </div>
    </section>

    <Footer />
    <WhatsAppButton />
  </div>
</template>