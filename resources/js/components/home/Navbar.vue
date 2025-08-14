<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage();

defineProps(
  {
    settings: {
      type: Object,
      default: () => ({})
    }
  }
)
</script>

<template>
  <header class="sticky top-0 z-50 w-full bg-black/90 backdrop-blur-2xl border-gray-100/50 shadow-sm" id="header">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <img v-if="settings?.set_logo" :src="`/storage/${settings.set_logo}`" alt="logo" class="h-10 w-auto" />
        <span class="text-xl font-bold">Grifo Tiburón 555</span>
      </div>
      <nav class="hidden md:flex items-center gap-6 text-base font-medium text-white">
        <Link href="/" class="hover:text-red-400" v-if="page.url !== '/'">Inicio</Link>
        <a href="#home" class="hover:text-red-400" v-else>Inicio</a>
        <Link :href="route('market.index')" class="hover:text-red-400">Productos y servicios</Link>
        <a href="#contacto" class="hover:text-red-400">Contacto</a>
        <a href="/market"
          class="px-3 py-1.5 rounded bg-red-500 text-white hover:bg-red-600 transition">Ingresar</a>
      </nav>
      <!-- Mobile -->
      <div class="md:hidden">
        <a href="/market" class="px-3 py-1.5 rounded bg-red-400 text-white text-sm">Ingresar</a>
      </div>
    </div>
  </header>
</template>