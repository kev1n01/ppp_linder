<script setup lang="ts">
const props  = defineProps(
  {
    settings: {
      type: Object,
      default: () => ({})
    }
  }
)

const phone = props .settings?.set_phone ? props .settings.set_phone.replace(/[^0-9]/g, '') : ''
const waUrl = phone ? `https://wa.me/51${phone}` : '#'
</script>

<template>
  <a :href="waUrl" target="_blank" rel="noopener" aria-label="WhatsApp"
    class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center shadow-lg shadow-green-500/40">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-7 h-7 fill-current">
      <path
        d="M16.027 3C9.404 3 4 8.402 4 15.023c0 2.658.863 5.121 2.332 7.133L4 29l7.086-2.293A12.036 12.036 0 0 0 16.027 27C22.65 27 28 21.598 28 14.977 28 8.355 22.65 3 16.027 3zm7.027 17.383c-.297.828-1.734 1.59-2.383 1.641-.609.047-1.383.086-4.781-1.547-4.016-1.898-6.57-5.5-6.766-5.758-.195-.258-1.617-2.152-1.617-4.109 0-1.957 1.039-2.93 1.406-3.336.367-.406.797-.512 1.063-.512.266 0 .532.004.766.015.246.012.578-.094.906.688.328.781 1.113 2.703 1.215 2.898.102.195.168.422.031.68-.133.258-.203.422-.398.648-.195.227-.422.508-.602.684-.195.195-.398.41-.172.801.227.39 1.004 1.652 2.156 2.676 1.48 1.324 2.723 1.734 3.121 1.93.398.195.633.164.867-.098.234-.262 1.016-1.18 1.289-1.586.273-.406.531-.336.906-.203.375.132 2.391 1.125 2.797 1.328.406.203.68.305.781.469.102.164.102.953-.195 1.781z" />
    </svg>
  </a>
</template>