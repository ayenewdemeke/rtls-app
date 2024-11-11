<script setup>
import Navbar from '@/Partials/Navbar.vue'
import Footer from '@/Partials/Footer.vue'
</script>

<template>
    <Navbar />
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-3 sm:pt-0 bg-gray-100">

        <div class="w-full sm:max-w-md pb-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
            <Footer></Footer>
        </div>
    </div>
</template>