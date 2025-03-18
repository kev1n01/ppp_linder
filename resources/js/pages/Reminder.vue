<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { Skeleton } from '@/components/ui/skeleton'
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Recordatorios',
        href: '/reminders',
    },
];

const set = ref(true)

setTimeout(() => {
    set.value = false
}, 3000)
</script>

<template>

    <Head title="Recordatorios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <template v-if="set">
            <div class="h-full px-4 2xl:px-10 py-4">
                <div class="flex flex-col justify-around w-full">
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-5">
                        <Skeleton class="h-24 rounded-sm bg-white/20" />
                        <Skeleton class="h-24 rounded-sm bg-white/20" />
                        <Skeleton class="h-24 rounded-sm bg-white/20" />
                        <Skeleton class="h-24 rounded-sm bg-white/20" />
                    </div>
                </div>
                <Skeleton class="h-dvh w-full rounded-sm bg-white/20 mt-5" />
            </div>

        </template>
        <template v-else>
            <div class="h-full px-4 2xl:px-10 py-4">
                <div class="flex flex-col justify-around w-full">
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 sm:gap-5">
                        <div class="relative rounded-lg dark:bg-black border-[1px] bg-white h-24">
                            <PlaceholderPattern />
                        </div>
                        <div class="relative rounded-lg dark:bg-black border-[1px] bg-white h-24">
                            <PlaceholderPattern />
                        </div>
                        <div class="relative rounded-lg dark:bg-black border-[1px] bg-white h-24">
                            <PlaceholderPattern />
                        </div>
                        <div class="relative rounded-lg dark:bg-black border-[1px] bg-white h-24">
                            <PlaceholderPattern />
                        </div>
                    </div>
                </div>

                <div
                    class="mt-5 w-full dark:bg-black bg-white dark:text-white text-black rounded-lg h-full border-[1px]">
                    <div class="p-5 flex w-full justify-between border-b-[1px] dark:border-white/20 border-black/20">
                        <div class="flex gap-5 items-center w-1/2">
                            <select name="" id=""
                                class="rounded-lg py-2 px-2 dark:bg-white/10 bg-slate-100 outline-none">
                                <option value="" class="dark:bg-black bg-slate-100">5</option>
                                <option value="" class="dark:bg-black bg-slate-100">10</option>
                                <option value="" class="dark:bg-black bg-slate-100">15</option>
                            </select>

                            <input type="text"
                                class="w-full rounded-lg py-2 px-4 dark:bg-white/10 bg-slate-100 outline-none"
                                placeholder="Buscar...">
                        </div>
                        <div class="flex gap-4 items-center w-1/3 justify-end">
                            <button
                                class="dark:bg-black bg-white border-[1px] dark:border-white/30 border-black/30 rounded-lg p-2">Filtros</button>
                            <button
                                class="dark:bg-black bg-white border-[1px] dark:border-white/30 border-black/30 rounded-lg p-2">Agregar</button>
                            <button
                                class="dark:bg-black bg-white border-[1px] dark:border-white/30 border-black/30 rounded-lg p-2">Acciones</button>
                        </div>
                    </div>
                    <div class="p-2">
                        <table class="w-full">
                            <thead class="bg-white/5 rounded-lg">
                                <tr class="border-b-[1px] border-white/20 uppercase text-sm">
                                    <th class="py-3">
                                        <input type="checkbox" name="" id="" class="size-4 rounded-xl cursor-pointer">
                                    </th>
                                    <th>Titulo</th>
                                    <th>Descripcion</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr class="border-b-[1px] border-black/20 hover:bg-white/5 cursor-pointer">
                                    <td class="py-3">
                                        <input type="checkbox" name="" id="" class="size-4 rounded-xl cursor-pointer">
                                    </td>
                                    <td class="py-3">Something</td>
                                    <td class="py-3">Something</td>
                                    <td class="py-3">Something</td>
                                    <td class="py-3">Something</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </template>
    </AppLayout>
</template>
