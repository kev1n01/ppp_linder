<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  name: string
  description?: string
  price: number
  image?: string | null
  type?: string
  discount: number
}

const props = defineProps<Props>()

const finalPrice = computed(() => {
  if (props.discount && props.discount > 0) {
    return props.price - (props.price * props.discount / 100)
  }
  return props.price
})
</script>

<template>
  <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-4 h-full flex flex-col relative">
    <div class="absolute text-white bg-[#3b82f6] px-4 py-1 rounded-md rounded-bl-xl rounded-tr-xl top-1 left-1">
      {{ discount }} %
    </div>
    <div class="w-full h-auto mb-3 overflow-hidden rounded">
      <img :src="image ? `/storage/${image}` : '/images/no-image.jpg'" :alt="name" class="aspect-auto object-cover" />
    </div>
    <h3 class="font-semibold text-xl text-gray-800 mb-1">{{ name }}</h3>
    <p v-if="description" class="text-gray-500 text-base mb-1 line-clamp-2 h-12">{{ description }}</p>
    <div class="flex gap-2 text-gray-500 items-center">
      <p class="text-red-600 font-bold text-2xl">S/ {{ finalPrice.toFixed(2) }}</p>
      <small>Oferta</small>
    </div>
    <p class="text-gray-500 font-normal text-lg line-through">S/ {{ price.toFixed(2) }}</p>
  </div>
</template>