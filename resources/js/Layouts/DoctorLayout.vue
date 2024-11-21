<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";

const showingSidebar = ref(false);
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-blue-100">
        <!-- Sidebar -->
        <aside
            :class="{
                '-translate-x-full': !showingSidebar,
                'translate-x-0': showingSidebar,
            }"
            class="transform top-0 left-0 w-64 bg-blue border-r border-blue-100 p-4 fixed h-full transition-transform duration-300 ease-in-out sm:relative sm:translate-x-0 overflow-y-auto bg-blue-100 dark:bg-blue-800 z-50"
        >
        <div class="flex items-center justify-between mb-5">
                <Link :href="route('doctor.dashboard')"  class="flex items-center ps-2.5">
                    <ApplicationLogo />
         
         <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">S.H.S.I</span>
                </Link>

                <!-- Close button for sidebar on mobile -->
                <button @click="showingSidebar = false" class="text-gray-500 hover:text-red-500 sm:hidden">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <ul class="space-y-2 font-medium">
                <li :class="{'bg-blue-300 rounded-lg': route().current('doctor.dashboard')}">
                    <Link :href="route('doctor.dashboard')" class="flex items-center p-2 text-red-900 rounded-lg dark:text-white hover:bg-blue-300 dark:hover:bg-red-700 group">
                        <span class="ms-3">    <i class="fas fa-gauge me-2"></i> Dashboard</span>
                    </Link>
                </li>
                <li :class="{'bg-blue-300 rounded-lg': route().current('doctor.appointment')}">
                    <Link :href="route('doctor.appointment')" class="flex items-center p-2 text-red-900 rounded-lg dark:text-white hover:bg-blue-300 dark:hover:bg-red-700 group">
                        <span class="ms-3">      <i class="fas fa-calendar-alt me-2"></i> appointment</span>
                    </Link>
                </li>
                <li :class="{'bg-blue-300 rounded-lg': route().current('doctor.schedule')}">
                    <Link :href="route('doctor.schedule')" class="flex items-center p-2 text-red-900 rounded-lg dark:text-white hover:bg-blue-300 dark:hover:bg-red-700 group">
                        <span class="ms-3">   <i class="fas fa-clock me-2"></i> Schedule</span>
                    </Link>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar and Header -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center">
                        <!-- Hamburger Button -->
                        <button @click="showingSidebar = !showingSidebar" class="text-gray-500 hover:text-gray-900 sm:hidden">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- Header Slot -->
                        <div class="flex-grow">
                            <slot name="header" />
                        </div>

                        <!-- Settings Dropdown -->
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}

                                        <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('doctor.profile')"><font-awesome-icon :icon="['fas', 'user']" class="me-2" />Profile</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button"><font-awesome-icon :icon="['fas', 'right-from-bracket']" class="me-2" />Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <!-- Page Content (Scrollable) -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-100">
                <slot />
            </main>

             <!-- Footer -->
            <footer class="bg-white shadow-inner py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-gray-500 text-sm">
                        <p>&copy; 2024 Surigao Health. All rights reserved.</p>
                        <p>
                            <a href="#" class="text-blue-500 hover:underline">Privacy Policy</a> | 
                            <a href="#" class="text-blue-500 hover:underline">Terms of Service</a>
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
