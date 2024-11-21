<script setup>
import PatientLayout from "@/Layouts/PatientLayout.vue";
import { ref } from "vue";

import { Head, Link, useForm } from "@inertiajs/vue3";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

import NavLink from "@/Components/NavLink.vue";

// Props
defineProps({
    headers: Array,
    footers: Array,


    
    doctorCount: {
    type: Number,
    required: true
},

departmentCount: {
    type: Number,
    required: true
},

researchlabCount: {
    type: Number,
    required: true
},

awardCount: {
    type: Number,
    required: true
},
});

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

</script>

<template>
    <Head title="Patient Dashboard" />

    <header>
        <nav
            class="bg-white border-red-200 dark:bg-red-900 fixed top-0 w-full z-50"
        >
            <div
                class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4"
            >
            <Link
                    v-for="header in headers"
                    :key="header.id"
                    :href="route('dashboard')"
                    class="flex items-center space-x-3 rtl:space-x-reverse" >
                    <img
                        :src="'/headerlogo/' + header.logo"
                        alt="Logo"
                        class="h-12 w-12 rounded-full"
                    />
                    <span
                        class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"
                    >
                        {{ header.title }}
                    </span>
                </Link>
                <div
                    class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                >
                    <button
                        @click="toggleMenu"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-red-500 rounded-lg md:hidden hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-200 dark:text-red-400 dark:hover:bg-red-700 dark:focus:ring-red-600"
                        aria-controls="navbar-cta"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                        <svg
                            class="w-5 h-5"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 17 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15"
                            />
                        </svg>
                    </button>

                    <!-- Settings Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white-500 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 hover:text-white-700 focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.name }}

                                    <svg
                                        class="ms-2 -me-0.5 h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </button>
                            </span>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('patient.profile')"
                                ><font-awesome-icon
                                    :icon="['fas', 'user']"
                                    class="me-2"
                                />Profile</DropdownLink
                            >
                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                                ><font-awesome-icon
                                    :icon="['fas', 'right-from-bracket']"
                                    class="me-2"
                                />Log Out</DropdownLink
                            >
                        </template>
                    </Dropdown>
                </div>
                <div
                    :class="{ hidden: !isMenuOpen, flex: isMenuOpen }"
                    class="items-center justify-between w-full md:flex md:w-auto md:order-1"
                    id="navbar-cta"
                >
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-red-100 rounded-lg bg-red-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-red-800 md:dark:bg-red-900 dark:border-red-700"
                    >
                        <li>
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Home
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('patient.about')"
                                :active="route().current('patient.about')"
                            >
                                About
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('patient.services')"
                                :active="route().current('patient.services')"
                            >
                                Services
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('patient.departments')"
                                :active="route().current('patient.departments')"
                            >
                            Departments
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('patient.doctors')"
                                :active="route().current('patient.doctors')"
                            >
                                Doctors
                            </NavLink>
                        </li>

                        <li>
                            <NavLink
                                :href="route('patient.contact')"
                                :active="route().current('patient.contact')"
                            >
                                Contact
                            </NavLink>
                        </li>

                        <li>
                            <NavLink
                                :href="route('patient.myappointment')"
                                :active="
                                    route().current('patient.myappointment')
                                "
                            >
                                My Appointment
                            </NavLink>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <PatientLayout>
        <!-- About Us Section -->
        <section id="about" class="bg-gray-50 dark:bg-gray-100 py-28">
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center"
                >
                    About Us
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 mb-8 text-center"
                >
                    Learn more about our mission, vision, and the core values
                    that drive us to provide the best healthcare services in our
                    community.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex items-center justify-center">
                        <img
                            src="/about.jpg"
                            class="w-full h-auto rounded-lg max-w-xs md:max-w-md lg:max-w-lg"
                        />
                    </div>
                    <div class="flex flex-col justify-center">
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-4"
                        >
                            Our Mission
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            At Surigao Health Specialists' Inc., our mission is
                            to provide patient-centered, compassionate care,
                            promoting health and well-being in our community.
                        </p>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-4"
                        >
                            Our Vision
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            To be the leading healthcare provider in the region,
                            offering innovative and comprehensive medical
                            services through our dedicated team of specialists.
                        </p>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-4"
                        >
                            Our Values
                        </h3>
                        <ul
                            class="list-disc pl-4 text-gray-600 dark:text-gray-400"
                        >
                            <li>Compassionate Care</li>
                            <li>Innovation in Treatment</li>
                            <li>Excellence in Service</li>
                            <li>Community Engagement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section with its own background -->
        <section class="bg-blue-100 dark:bg-blue-900 py-20">
            <div class="max-w-screen-xl mx-auto px-4">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center"
                >
                    <div
                        class="bg-white dark:bg-gray-200 p-6 rounded-lg shadow-md"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center"
                        >
                            <i
                                class="fas fa-user-md text-blue-500 text-3xl"
                            ></i>
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-gray-800"
                        >
                            Doctors
                        </h3>
                        <p class="text-gray-600 dark:text-gray-700">
                            {{ doctorCount }} specialists
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-200 p-6 rounded-lg shadow-md"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center"
                        >
                            <i
                                class="fas fa-hospital text-green-500 text-3xl"
                            ></i>
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-gray-800"
                        >
                            Departments
                        </h3>
                        <p class="text-gray-600 dark:text-gray-700">
                            {{ departmentCount }}  specialized departments
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-200 p-6 rounded-lg shadow-md"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center"
                        >
                            <i
                                class="fas fa-flask text-yellow-500 text-3xl"
                            ></i>
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-gray-800"
                        >
                            Research Labs
                        </h3>
                        <p class="text-gray-600 dark:text-gray-700">
                            {{ researchlabCount }}  advanced labs
                        </p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-200 p-6 rounded-lg shadow-md"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center"
                        >
                            <i class="fas fa-award text-red-500 text-3xl"></i>
                        </div>
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-gray-800"
                        >
                            Awards
                        </h3>
                        <p class="text-gray-600 dark:text-gray-700">
                            {{ awardCount }}  excellence awards
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </PatientLayout>
       
    <footer
    v-for="footer in footers"
    :key="footer.id"
    class="bg-red-700 text-white py-6"
>
    <div class="max-w-screen-xl mx-auto px-16">
        <div class="flex justify-between items-center">
            <p class="text-sm">
                <i class="fas fa-copyright"></i> 2024 Surigao Health. All rights reserved.
            </p>
            <div class="space-x-4">
                <a href="#" class="text-white hover:underline">
                    <i class="fas fa-shield-alt me-1"></i> Privacy Policy
                </a>
                <a href="#" class="text-white hover:underline">
                    <i class="fas fa-file-alt me-1"></i> Terms of Service
                </a>
            </div>
        </div>
        <div class="flex justify-between items-center mt-4">
            <p class="text-sm">
                <i class="fas fa-phone-alt me-1"></i> Contact: {{ footer.contact }}
            </p>
            <p class="text-sm">
                <i class="fab fa-facebook me-1"></i>
                Facebook:
                <a href="#" class="hover:underline"> {{ footer.fbname }}</a>
            </p>
        </div>
        <div class="flex justify-between items-center mt-4">
            <p class="text-sm">
                <i class="fas fa-map-marker-alt me-1"></i> {{ footer.place }}
            </p>
        </div>
    </div>
</footer>
</template>
