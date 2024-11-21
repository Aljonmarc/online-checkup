<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import InputError from "@/Components/InputError.vue";
import addfooter from "./add/addfooter.vue";
import Swal from "sweetalert2";

// Props
defineProps({
    headers: Array,
    footers: Array,
});

const isAddH = ref(true); // Changed to handle add and edit modes
const dialogVisible = ref(false);

const currentHeader = ref(null); // Track the current user being edited

// Open Add Modal
const openAddH = () => {
    isAddH.value = true;
    dialogVisible.value = true;
    form.reset(); // Clear form for adding a new user
};

const isAddF = ref(true); // Changed to handle add and edit modes
const dialogVisibleF = ref(false);

const currentFooter = ref(null); // Track the current user being edited

const openAddF = () => {
    isAddF.value = true;
    dialogVisibleF.value = true;
    form.reset(); // Clear form for adding a new user
};


// Form for adding and editing headers
const form = useForm({
    title: "",
    logo: null,

    fbname: "",
    contact: "",
    place: "",
});

// Open Edit Modal
const editHeader = (header) => {
    isAddH.value = false;
    dialogVisible.value = true;
    currentHeader.value = header;

    // Populate form with existing data
    form.title = header.title;
    form.logo = null; // We don't pre-load the file input for security reasons
};

// Open Edit Modal
const editFooter = (footer) => {
    isAddF.value = false;
    dialogVisibleF.value = true;
    currentFooter.value = footer;

    // Populate form with existing data
    form.fbname = footer.fbname;
    form.contact = footer.contact;
    form.place = footer.place;
};

// Submit form (handles both add and edit)
const submit = () => {
    if (isAddH.value) {
        // Handle add user
        form.post(route("header.store"), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Header has been created successfully.",
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
        form.post(route("header.update", currentHeader.value.id), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Header has been updated successfully.",
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


// Submit form (handles both add and edit)
const submitF = () => {
    if (isAddF.value) {
        // Handle add user
        form.post(route("footer.store"), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Footer has been created successfully.",
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
         form.patch(route("footer.update", currentFooter.value.id), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "Footer has been updated successfully.",
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

// Delete Header
const deleteHeader = (header) => {
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
            form.delete(route("header.destroy", header.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Header has been deleted.",
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

// Delete Footer
const deleteFooter = (footer) => {
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
            form.delete(route("footer.destroy", footer.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Deleted!",
                        text: "Footer has been deleted.",
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

const closeModal = () => {
    dialogVisible.value = false;
    dialogVisibleF.value = false;
    form.reset(); // Clear form on close
    currentHeader.value = null; // Reset current user
    currentFooter.value = null; // Reset current user
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <el-dialog
        v-model="dialogVisible"
        :title="isAddH ? 'Add Header' : 'Edit Header'"
        width="500"
        :before-close="handleClose"
    >
        <!--form start-->

        <form @submit.prevent="submit" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="title"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="title"
                    v-model="form.title"
                    autofocus
                    autocomplete="title"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.title" />
                <label
                    for="title"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Title</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="logo"
                    type="file"
                    @input="form.logo = $event.target.files[0]"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="logo"
                    autofocus
                    autocomplete="logo"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.logo" />
                <label
                    for="logo"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >logo</label
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

    
    <el-dialog
        v-model="dialogVisibleF"
        :title="isAddF ? 'Add footer' : 'Edit footer'"
        width="500"
        :before-close="handleClose"
    >
        <!--form start-->

        <form @submit.prevent="submitF" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="fbname"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="fbname"
                    v-model="form.fbname"
                    autofocus
                    autocomplete="fbname"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.fbname" />
                <label
                    for="fbname"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >fbname</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="contact"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="contact"
                    v-model="form.contact"
                    autofocus
                    autocomplete="contact"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.contact" />
                <label
                    for="contact"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >contact</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="place"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="place"
                    v-model="form.place"
                    autofocus
                    autocomplete="place"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.place" />
                <label
                    for="place"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >place</label
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
                    HEADER
                </div>
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg"
                >
                    <!-- Add User Button -->
                    <button style="display: none;"
                        type="button"
                        @click="openAddH"
                        class="ml-2 mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    >
                        Add
                    </button>

                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Logo</th>
                                    <th scope="col" class="px-4 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="header in headers"
                                    :key="header.id"
                                    class="border-b dark:border-gray-700"
                                >
                                    <td class="px-4 py-3">
                                        {{ header.title }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <img
                                            :src="'/headerlogo/' + header.logo"
                                            alt="Logo"
                                            class="h-10 w-10 rounded-full me-3"
                                        />
                                    </td>
                                    <td class="px-4 py-3">
                                        <!-- Edit button -->
                                        <button 
                                            @click="editHeader(header)"
                                            class="py-1 px-3 rounded ml-2 py-2 px-4"
                                        >
                                            <i
                                                class="fas fa-edit text-green-500"
                                            ></i>
                                        </button>

                                        <!-- Delete button -->
                                        <button style="display: none;"
                                            @click="deleteHeader(header)"
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
                    </div>
                </div>
    
        </div>

        <div class="py-5">
 
                <div
                    class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2"
                >
                    FOOTER
                </div>
                <div
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg"
                >
                    <addfooter />

                    <div class="overflow-x-auto">
                        <table
                            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-4 py-3">
                                        FBNAME
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        CONTACT
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        ADDRESS
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="footer in footers"
                                    :key="footer.id"
                                    class="border-b dark:border-gray-700"
                                >
                                    <td class="px-4 py-3">
                                        {{ footer.fbname }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ footer.contact }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ footer.place }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <!-- Edit button -->
                                        <button
                                          @click="editFooter(footer)"
                                            class="py-1 px-3 rounded ml-2 py-2 px-4"
                                        >
                                            <i
                                                class="fas fa-edit text-green-500"
                                            ></i>
                                        </button>

                                        <!-- Delete button -->
                                        <button style="display: none;"
                                          @click="deleteFooter(footer)"
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
                    </div>
                </div>
     
        </div>
    </AdminLayout>
</template>
