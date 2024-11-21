<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, watch, defineProps } from "vue";
import InputError from "@/Components/InputError.vue";
import Swal from "sweetalert2";
import { useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";



// Define props passed from the controller
const props = defineProps({
    bookings: Object,
    search: String,
});


// Reactive data for search query
const searchQuery = ref(props.search);


// Search function triggered by form submission
function search() {
    Inertia.get(
        route("admin.appointment"),
        { search: searchQuery.value },
        { preserveState: true }
    );
}

// Reset pagination when input is cleared
function handleInputChange() {
    if (searchQuery.value === "") {
        Inertia.get(route("admin.appointment"), {}, { preserveState: true });
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
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="py-5">
     
                <div
                    class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2"
                >
                    APPOINTMENTS
                </div>
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg"
                >
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4"
                    >
                        <div class="w-full md:w-1/2">
                            <form
                               @submit.prevent="search"
                             class="flex items-center">
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

                    <div class="overflow-x-auto">
                    <table
                        class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                        >
                            <tr>
                                <th scope="col" class="px-4 py-3">
                                    Patient name
                                </th>
                                <th scope="col" class="px-4 py-3">Schedule</th>

                                <th scope="col" class="px-4 py-3">Status</th>

                                <th scope="col" class="px-4 py-3">Doctor</th>

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
                                    {{ booking.patient_name }}
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
                                    {{ booking.doc_name }}
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
                                    >{{ bookings.from }}-{{ bookings.to }}</span
                                >
                                of
                                <span
                                    class="font-semibold text-gray-900 dark:text-white"
                                    >{{ bookings.total }}</span
                                >
                            </span>
                            <ul class="inline-flex items-stretch -space-x-px">
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
    </AdminLayout>
</template>
