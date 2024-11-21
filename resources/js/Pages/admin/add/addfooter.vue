<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import InputError from "@/Components/InputError.vue";
import Swal from "sweetalert2";

const isAddF = ref(true); // Changed to handle add and edit modes
const dialogVisible = ref(false);

const currentFooter = ref(null); // Track the current user being edited

const openAddF = () => {
    isAddF.value = true;
    dialogVisible.value = true;
    form.reset(); // Clear form for adding a new user
};

// Form for adding and editing footers
const form = useForm({
    fbname: "",
    contact: "",
    place: "",
});

// Open Edit Modal
const editFooter = (footer) => {
    isAddF.value = false;
    dialogVisible.value = true;
    currentFooter.value = footer;

    // Populate form with existing data
    form.fbname = footer.fbname;
    form.contact = footer.contact;
    form.place = footer.place;
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
                    position: "top-end",
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
                    text: "footer has been updated successfully.",
                    icon: "success",
                    toast: true,
                    position: "top-end",
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
    form.reset(); // Clear form on close
    currentFooter.value = null; // Reset current user
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <el-dialog
        v-model="dialogVisible"
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

    <button style="display: none;"
        type="button" 
        @click="openAddF"
        class="ml-2 mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
    >
        Add
    </button>

 
</template>
