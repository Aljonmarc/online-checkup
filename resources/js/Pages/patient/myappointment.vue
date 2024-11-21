<script setup>
import PatientLayout from "@/Layouts/PatientLayout.vue";
import { ref, defineProps } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import NavLink from "@/Components/NavLink.vue";

// Define props passed from the controller
const props = defineProps({
    bookings: Object,
    search: String,
    headers: Array,
    footers: Array,
});

// Reactive data for search query
const searchQuery = ref(props.search);

// Search function triggered by form submission
function search() {
    Inertia.get(
        route("patient.myappointment"),
        { search: searchQuery.value },
        { preserveState: true }
    );
}

// Reset pagination when input is cleared
function handleInputChange() {
    if (searchQuery.value === "") {
        Inertia.get(
            route("patient.myappointment"),
            {},
            { preserveState: true }
        );
    }
}

// Handle pagination click
function goToPage(url) {
    if (url) {
        Inertia.get(
            url,
            { search: searchQuery.value },
            { preserveState: true }
        );
    }
}

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// Delete Doctor
const deleteAppointment = (booking) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the delete request
            Inertia.delete(route("booking.destroy", booking.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Booking  has been deleted.",
                        icon: "success",
                        toast: true,
                        position: "bottom-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                },
            });
        }
    });
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
                    class="flex items-center space-x-3 rtl:space-x-reverse"
                >
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
        <section class="bg-gray-100 dark:bg-gray-100 py-28">
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center"
                >
                    My appointment
                </h2>

                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <div class="w-full md:w-1/2">
                            <form
                                @submit.prevent="search"
                                class="flex items-center"
                            >
                                <label for="simple-search" class="sr-only"
                                    >Search</label
                                >
                                <div class="relative w-full">
                                    <div
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"
                                    >
                                        <svg
                                            aria-hidden="true"
                                            class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor"
                                            viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>
                                    <input
                                        type="text"
                                        id="simple-search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Search"
                                        required=""
                                        v-model="searchQuery"
                                        @input="handleInputChange"
                                    />
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- appointment Table -->
                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3">
                                        Doctor name
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Schedule
                                    </th>

                                    <th scope="col" class="px-4 py-3">
                                        Doctor Status
                                    </th>

                                    <th scope="col" class="px-4 py-3">Reply</th>

                                    <th scope="col" class="px-4 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-if="bookings.data.length === 0">
                                    <td
                                        colspan="5"
                                        class="text-center text-gray-500"
                                    >
                                        Nothing found
                                    </td>
                                </tr>
                                <tr
                                    v-for="booking in bookings.data"
                                    :key="booking.id"
                                    class="border-b dark:border-gray-700"
                                >
                                    <td class="px-4 py-3">
                                        {{ booking.doc_name }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span>{{
                                            new Date(
                                                booking.start_date
                                            ).toLocaleDateString()
                                        }}</span>
                                        <span class="text-sm text-gray-600"
                                            >({{
                                                new Date(
                                                    booking.start_date
                                                ).toLocaleDateString("en-US", {
                                                    weekday: "long",
                                                })
                                            }})</span
                                        >
                                        <!-- Day Type -->
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-600 px-2 py-1 rounded-full':
                                                    booking.admin_status ===
                                                    'approved',
                                                'bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full':
                                                    booking.admin_status ===
                                                    'pending',
                                                'bg-red-100 text-red-600 px-2 py-1 rounded-full':
                                                    booking.admin_status ===
                                                    'canceled',
                                            }"
                                        >
                                            {{
                                                booking.admin_status
                                                    .charAt(0)
                                                    .toUpperCase() +
                                                booking.admin_status.slice(1)
                                            }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ booking.reply }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <button
                                            @click="deleteAppointment(booking)"
                                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                                        >
                                            Cancel Appointment
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div v-if="bookings.links.length > 1">
                            <nav
                                class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
                                aria-label="Table navigation"
                            >
                                <span
                                    class="text-sm font-normal text-gray-500 dark:text-gray-400"
                                >
                                    Showing
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white"
                                        >{{ bookings.from }}-{{
                                            bookings.to
                                        }}</span
                                    >
                                    of
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white"
                                        >{{ bookings.total }}</span
                                    >
                                </span>
                                <ul
                                    class="inline-flex items-stretch -space-x-px"
                                >
                                    <li
                                        v-for="(link, index) in bookings.links"
                                        :key="index"
                                    >
                                        <a
                                            href="#"
                                            :class="[
                                                'flex items-center justify-center text-sm py-2 px-3 leading-tight border',
                                                link.active
                                                    ? 'bg-primary-50 text-primary-600 border-primary-300 hover:bg-primary-100'
                                                    : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white',
                                            ]"
                                            v-html="link.label"
                                            @click.prevent="goToPage(link.url)"
                                        ></a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
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
                    <i class="fas fa-copyright"></i> 2024 Surigao Health. All
                    rights reserved.
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
                    <i class="fas fa-phone-alt me-1"></i> Contact:
                    {{ footer.contact }}
                </p>
                <p class="text-sm">
                    <i class="fab fa-facebook me-1"></i>
                    Facebook:
                    <a href="#" class="hover:underline"> {{ footer.fbname }}</a>
                </p>
            </div>
            <div class="flex justify-between items-center mt-4">
                <p class="text-sm">
                    <i class="fas fa-map-marker-alt me-1"></i>
                    {{ footer.place }}
                </p>
            </div>
        </div>
    </footer>
</template>
