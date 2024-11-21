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
    services: Object,
    search: String,
});

// Reactive data for search query
const searchQuery = ref(props.search);

// Search function triggered by form submission
function search() {
    Inertia.get(
        route("admin.services"),
        { search: searchQuery.value },
        { preserveState: true }
    );
}

// Reset pagination when input is cleared
function handleInputChange() {
    if (searchQuery.value === "") {
        Inertia.get(route("admin.services"), {}, { preserveState: true });
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

const isAddS = ref(false);
const dialogVisible = ref(false);
const currentService = ref(null); // track current service

// add edit form
const form = useForm({
    image: null,
    stitle: "",
    description: "",
});

const openAddS = () => {
    isAddS.value = true;
    dialogVisible.value = true;
    form.reset();
};

// Open modal for editing an existing user
const openEditS = (service) => {
    isAddS.value = false;
    dialogVisible.value = true;
    currentService.value = service;

    // Pre-fill form with service's existing data
    form.stitle = service.stitle;
    form.description = service.description;
    form.image = null;
};

// Submit form (handles both add and edit)
const submit = () => {
    if (isAddS.value) {
        // Handle add user
        form.post(route("service.store"), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Service has been created successfully.",
                    icon: "success",
                    toast: true,
                    position: "bottom-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            },
        });
    } else {
        // Handle edit user
        form.post(route("service.update", currentService.value.id), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Service has been updated successfully.",
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
};

const closeModal = () => {
    dialogVisible.value = false;
    form.reset();
    currentService.value = null;
};

// Delete Service
const deleteService = (service) => {
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
            form.delete(route("service.destroy", service.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Service has been deleted.",
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
    <Head title="Admin Dashboard" />

    <el-dialog
        v-model="dialogVisible"
        :title="isAddS ? 'Add Services' : 'Edit Services'"
        width="500"
        :before-close="handleClose"
    >
        <!--form start-->

        <form @submit.prevent="submit" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="stitle"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="stitle"
                    v-model="form.stitle"
                    autofocus
                    autocomplete="stitle"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.stitle" />
                <label
                    for="stitle"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >stitle</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="image"
                    type="file"
                    @input="form.image = $event.target.files[0]"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="image"
                    autofocus
                    autocomplete="image"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.image" />
                <label
                    for="image"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >image</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <textarea
                    id="description"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="description"
                    v-model="form.description"
                    autofocus
                    autocomplete="description"
                    placeholder=" "
                ></textarea>
                <InputError class="mt-1" :message="form.errors.description" />
                <label
                    for="description"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >description</label
                >
            </div>

            <button
                type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Submit
            </button>
        </form>
        <!--form end-->
    </el-dialog>

    <AdminLayout>
        <div class="py-5">
    
                <div
                    class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2"
                >
                    SERVICES
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
                                        v-model="searchQuery"
                                        id="simple-search"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Search"
                                        required=""
                                        @input="handleInputChange"
                                    />
                                </div>
                            </form>
                        </div>

                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0"
                        >
                            <!-- Add User Button -->
                            <button
                                type="button"
                                @click="openAddS"
                                class="ml-2 mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                                Add Services
                            </button>
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
                                    <th scope="col" class="px-4 py-3">image</th>
                                    <th scope="col" class="px-4 py-3">
                                        stitle
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        description
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- Check if services exist -->
                                <tr v-if="services.data.length === 0">
                                    <td
                                        colspan="5"
                                        class="text-center text-gray-500"
                                    >
                                        Nothing found
                                    </td>
                                </tr>
                                <tr
                                    v-for="service in services.data"
                                    :key="service.id"
                                    class="border-b dark:border-gray-700"
                                >
                                    <td class="px-4 py-3">
                                        <img
                                            :src="
                                                '/serviceimage/' + service.image
                                            "
                                            alt="Logo"
                                            class="h-10 w-10 rounded-full me-3"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ service.stitle }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ service.description }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <!-- Edit button -->
                                        <button
                                            @click="openEditS(service)"
                                            class="py-1 px-3 rounded ml-2 py-2 px-4"
                                        >
                                            <i
                                                class="fas fa-edit text-green-500"
                                            ></i>
                                        </button>

                                        <!-- Delete button -->
                                        <button
                                            @click="deleteService(service)"
                                            class="py-1 px-3 rounded ml-2 py-2 px-4"
                                        >
                                            <i
                                                class="fas fa-trash text-red-500"
                                            ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div v-if="services.links.length > 1">
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
                                        >{{ services.from }}-{{
                                            services.to
                                        }}</span
                                    >
                                    of
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white"
                                        >{{ services.total }}</span
                                    >
                                </span>
                                <ul
                                    class="inline-flex items-stretch -space-x-px"
                                >
                                    <li
                                        v-for="(link, index) in services.links"
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
