<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

</script>

<template>
    <AppLayout title="404">

        <Head title="404" />

        <div class="flex h-full items-center justify-center">
            <div class="text-center">
                <h1 class="mb-4 text-5xl font-bold">404</h1>
                <p class="mb-4 text-muted-foreground">La página que buscas no existe.</p>
            </div>
        </div>
    </AppLayout>
</template>