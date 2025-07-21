<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface LinkItem {
  url: string | null
  label: string
  active: boolean
}

interface Props {
  links: LinkItem[]
}

const props = defineProps<Props>()
</script>

<template>
  <nav class="flex gap-1" role="navigation" aria-label="Pagination">
    <template v-for="link in links" :key="link.label">
      <Link
        v-if="link.url"
        :href="link.url"
        v-html="link.label"
        class="px-3 py-1.5 rounded text-sm border transition"
        :class="[
          link.active
            ? 'bg-red-600 text-white border-red-600'
            : 'bg-white text-gray-700 hover:bg-gray-200 border-gray-300'
        ]"
        preserve-state
        preserve-scroll
      />
      <span
        v-else
        v-html="link.label"
        class="px-3 py-1.5 rounded text-sm border bg-gray-400 text-gray-800 cursor-not-allowed"
      />
    </template>
  </nav>
</template>
