<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { cn } from '@/lib/utils';
import { ChevronsDown } from 'lucide-vue-next';
import type { HTMLAttributes } from 'vue';
import { useSidebar } from './utils';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();

const { toggleSidebar, open } = useSidebar();
</script>

<template>
    <Button data-sidebar="trigger" variant="ghost" size="icon" :class="cn('h-7 w-7', props.class)" @click="toggleSidebar">
        <ChevronsDown :class="[open ? 'rotate-90' : '-rotate-90', 'transition-all delay-200']" />
        <span class="sr-only">Toggle Sidebar</span>
    </Button>
</template>
