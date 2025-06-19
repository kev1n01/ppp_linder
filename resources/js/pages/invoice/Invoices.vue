<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref } from 'vue';
import { getDataToDoc } from '@/lib/sunat'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Facturación',
    href: '#',
  },
  {
    title: 'Facturas',
    href: '/invoices',
  },
];

const ruc = ref('')
const loading = ref(false)
const customfind = ref({})

const searchCustomer = async () => {
  try {
    loading.value = true
    const getdata = await getDataToDoc(ruc.value);
    customfind.value = getdata;
  } catch (error) {
    console.error(error)
  } finally {
    loading.value = false
    ruc.value = '';
  }
}
</script>

<template>

  <Head title="Facturas" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="h-full px-4 2xl:px-10 py-4">
      <!-- section data customer -->
      <div class="flex flex-col gap-2">
        <div class="flex gap-2">
          <input type="text" placeholder="Ingrese RUC del cliente" class="bg-white/10 px-5 py-2 rounded-lg"
            v-model="ruc" 
            :disabled="loading" 
            @input="ruc = ruc.replace(/\D/g, '')" 
            maxlength="11">
          <button 
          :disabled="loading" 
          class="" 
          @click="searchCustomer">
            {{ !loading ? 'buscar' : 'buscando..' }}
          </button>
        </div>
        <!-- data searched here -->
        <div class="flex-col flex">
          <span>Numero de documento: {{ customfind.ruc }}</span>
          <span>Razon social: {{ customfind.razonSocial }}</span>
          <span>Estado: {{ customfind.estado }}</span>
          <span>Condicion: {{ customfind.condicion }}</span>
          <span>Direccion: {{ customfind.direccion }}</span>
        </div>
      </div>

      <!-- <select name="typedoc" id="typedoc" class="rounded-lg py-2 px-2 dark:bg-white/10 bg-slate-100 outline-none uppercase animate-fade-in-up">
        <option value="01" class="dark:bg-black bg-slate-100">factura</option>
        <option value="06" class="dark:bg-black bg-slate-100">ruc</option>
      </select> -->
    </div>
  </AppLayout>
</template>