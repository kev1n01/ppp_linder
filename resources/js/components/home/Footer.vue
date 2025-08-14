<script setup lang="ts">
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
  <footer class="mt-16 bg-gray-950 text-gray-300 text-sm">
    <div class="max-w-7xl mx-auto px-4 py-10 gap-8 flex justify-between sm:flex-row flex-col sm:text-start text-center">
      <div>
        <h2 class="font-semibold text-white mb-2">{{ settings?.set_name_business ?? 'Grifo Tiburón' }}</h2>
        <p>{{ settings?.set_address }}</p>
        <p>RUC: {{ settings?.set_ruc ?? 'Sin registro' }}</p>
        <p>{{ settings?.set_district }}, {{ settings?.set_province }}, {{ settings?.set_department }}</p>
        <p>Tel: {{ settings?.set_phone ?? 'Sin registro' }}</p>
      </div>
      <div>
        <h2 class="font-semibold text-white mb-2">Enlaces</h2>
        <ul class="space-y-1">
          <li><a href="#home" class="hover:text-white">Inicio</a></li>
          <li><a href="/market" class="hover:text-white">Productos y servicios</a></li>
          <li><a href="#contacto" class="hover:text-white">Contacto</a></li>
        </ul>
      </div>
    </div>
    <div class="bg-gray-950 text-center py-3 text-xs">© {{ new Date().getFullYear() }} {{ settings?.set_name_business ??
      'Grifo Tiburón' }}. Todos los derechos reservados.</div>
  </footer>
</template>