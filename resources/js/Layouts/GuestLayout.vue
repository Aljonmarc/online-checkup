<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-blue-100">
     

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-red-800 shadow-md overflow-hidden sm:rounded-lg"
        >
        <div align="center">
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500 dark:text-gray-300" />
            </Link>
       
        </div>
            <slot />
        </div>
    </div>
</template>

<style scoped>
/* You can customize the background to match your welcome page theme */
body {
    background-color: #f8f9fa;
    /* Add a similar animation effect to the welcome page if needed */
}
</style>
