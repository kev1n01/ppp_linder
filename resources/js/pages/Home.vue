<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Navbar from '@/components/home/Navbar.vue'
import Footer from '@/Components/home/Footer.vue'
import WhatsAppButton from '@/Components/home/WhatsAppButton.vue'
import ItemCard from '@/Components/home/ItemCard.vue'
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

interface Props {
  featuredItems: Item[],
  settings: {
    set_name: string,
    set_address: string,
    set_phone: string,
    set_email: string,
    set_logo?: string | null
  }
}

const props = defineProps<Props>()
</script>

<template>
  <div>

    <Head title="Home" />
    <Navbar id="home" :settings="props.settings" />

    <!-- HERO -->
    <section
      class="relative h-[60vh] md:h-[70vh] flex items-center justify-center bg-[url('/images/grifo-hero.webp')] bg-cover bg-center">
      <div class="absolute inset-0 bg-black/30"></div>
      <div class="relative z-10 text-center px-4">
        <h1 class="text-3xl md:text-8xl font-bold text-gray-200 mb-4">Grifo Tiburón 555</h1>
        <p class="text-white/90 max-w-xl mx-auto mb-6 text-xl">Combustibles de calidad, atención rápida y servicios para
          tu vehículo.</p>
        <div class="flex flex-wrap justify-center gap-4">
          <Link :href="route('market.index')"
            class="px-6 py-3 rounded bg-red-600 text-white font-semibold hover:bg-red-700 transition">Ver productos y
          servicios</Link>
          <a href="#contacto"
            class="px-6 py-3 rounded bg-[#3b82f6] text-white font-semibold hover:bg-[#2f6ed4] transition">Contáctanos</a>
        </div>
      </div>
    </section>

    <!-- SERVICIOS DESTACADOS -->
    <section class="max-w-7xl mx-auto px-4 py-16" id="productos">
      <h2 class="text-3xl font-bold text-gray-100 mb-8 text-center">Productos y servicios destacados</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <ItemCard v-for="item in featuredItems" :key="item.id" :name="item.ite_name" :description="item.ite_description"
          :price="item.ite_price" :image="item.ite_image" :discount="item.ite_discount" />
      </div>
      <div class="text-center mt-8">
        <Link :href="route('market.index')" class="text-white rounded-lg bg-red-500 py-2 px-3 hover:bg-red-600">Ver
        catálogo completo</Link>
      </div>
    </section>

    <!-- CONTACTO -->
    <section id="contacto" class="bg-gray-100 py-16">
        <h2 class="text-3xl font-bold text-center mb-6 text-black">Contáctanos</h2>
        <div class="flex gap-10 max-w-7xl mx-auto px-4 sm:flex-row flex-col text-black">
          <form action="#" method="POST" class="space-y-4">
            <input type="text" placeholder="Nombre" class="w-full border rounded p-2" required>
            <input type="email" placeholder="Correo electrónico" class="w-full border rounded p-2" required>
            <textarea rows="4" placeholder="Mensaje" class="w-full border rounded p-2" required></textarea>
            <button type="submit"
              class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded">Enviar</button>
          </form>
          <div class="w-full">
            <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d1488.311985458321!2d-76.51515673837568!3d-10.491188827509975!3m2!1i1024!2i768!4f13.1!5e1!3m2!1ses!2spe!4v1753807614240!5m2!1ses!2spe" class="w-full h-72 sm:h-full rounded" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
    </section>

    <Footer :settings="props.settings" />
    <WhatsAppButton :settings="props.settings" />
  </div>
</template>