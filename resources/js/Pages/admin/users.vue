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
    users: Object,
    search: String,
});

// Reactive data for search query
const searchQuery = ref(props.search);

// Search function triggered by form submission
function search() {
    Inertia.get(
        route("admin.users"),
        { search: searchQuery.value },
        { preserveState: true }
    );
}

// Reset pagination when input is cleared
function handleInputChange() {
    if (searchQuery.value === "") {
        Inertia.get(route("admin.users"), {}, { preserveState: true });
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
// Function to open delete confirmation modal
const confirmDelete = (user) => {
    currentUser.value = user;
    Swal.fire({
        title: "Are you sure?",
        text: `You are about to delete ${user.name}. This action cannot be undone!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with the deletion
            deleteUser(user);
        }
    });
};

// Function to handle user deletion
const deleteUser = (user) => {
    // Make the delete request
    form.delete(route("users.destroy", user.id), {
        onSuccess: () => {
            Swal.fire(
                "Deleted!",
                `User ${user.name} has been deleted.`,
                "success"
                
            );
        },
    });
};

const dropdownVisible = ref({});
const currentDropdown = ref(null);

// Toggle the dropdown visibility for each user
function toggleDropdown(userId) {
    dropdownVisible.value[userId] = !dropdownVisible.value[userId];
    currentDropdown.value = dropdownVisible.value[userId] ? userId : null;
}

// Close the dropdown when clicking outside
function closeDropdown() {
    if (currentDropdown.value !== null) {
        dropdownVisible.value[currentDropdown.value] = false;
        currentDropdown.value = null;
    }
}

// Detect clicks outside the dropdown to close it
function handleClickOutside(event) {
    const dropdownElement = document.getElementById(
        "dropdown-" + currentDropdown.value
    );
    const buttonElement = document.getElementById(
        "dropdown-button-" + currentDropdown.value
    );

    if (
        dropdownElement &&
        !dropdownElement.contains(event.target) &&
        !buttonElement.contains(event.target)
    ) {
        closeDropdown();
    }
}

onMounted(() => {
    // Attach the click event listener to the document
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    // Remove the click event listener when the component is destroyed
    document.removeEventListener("click", handleClickOutside);
});

const isAddUser = ref(false);
const dialogVisible = ref(false);
const currentUser = ref(null); // Track the current user being edited

// Form for adding and editing users
const form = useForm({
    name: "",
    email: "",
    role: "",
    password: "",
    phone: "",
    address: "",
});

// Open modal for adding a new user
const openAddModal = () => {
    isAddUser.value = true;
    dialogVisible.value = true;
    form.reset(); // Clear form for adding a new user
};

// Open modal for editing an existing user
const openEditModal = (user) => {
    isAddUser.value = false;
    dialogVisible.value = true;
    currentUser.value = user;

    // Pre-fill form with user's existing data
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    form.phone = user.phone;
    form.address = user.address;
};

// Submit form (handles both add and edit)
const submit = () => {
    if (isAddUser.value) {
        // Handle add user
        form.post(route("users.store"), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "User has been created successfully.",
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
        form.patch(route("users.update", currentUser.value.id), {
            onSuccess: () => {
                closeModal();
                Swal.fire({
                    title: "Success!",
                    text: "User has been updated successfully.",
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
    form.reset(); // Clear form on close
    currentUser.value = null; // Reset current user
};

// Modal visibility state
const viewDialogVisible = ref(false);

// Open the modal to view user details
const openViewModal = (user) => {
    currentUser.value = user; // Store the user information
    viewDialogVisible.value = true; // Open the modal
};

// Close the view modal
const closeViewModal = () => {
    viewDialogVisible.value = false;
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <!-- User View Modal -->
    <el-dialog
        v-model="viewDialogVisible"
        title="View User"
        width="500"
        @close="closeViewModal"
    >
        <template v-if="currentUser">
            <p><strong>Full Name:</strong> {{ currentUser.name }}</p>
            <p><strong>Email:</strong> {{ currentUser.email }}</p>
            <p><strong>Role:</strong> {{ currentUser.role }}</p>
            <p><strong>Phone:</strong> {{ currentUser.phone }}</p>
            <p><strong>Address:</strong> {{ currentUser.address }}</p>
        </template>
    </el-dialog>

    <el-dialog
        v-model="dialogVisible"
        :title="isAddUser ? 'Add User' : 'Edit User'"
        width="500"
        :before-close="handleClose"
    >
        <!--form start-->

        <form @submit.prevent="submit" class="max-w-md mx-auto">
            <div class="relative z-0 w-full mb-5 group">
                <input
                    id="name"
                    type="text"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    name="name"
                    v-model="form.name"
                    autofocus
                    autocomplete="name"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.name" />
                <label
                    for="name"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Full name</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                    v-model="form.email"
                    autofocus
                    autocomplete="email"
                />
                <InputError class="mt-1" :message="form.errors.email" />
                <label
                    for="email"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Email address</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <label
                    for="role"
                    class="block mb-2 text-sm font-medium text-gray-500 dark:text-white"
                    >Select role</label
                >

                <select
                    id="role"
                    v-model="form.role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option value="">Choose role</option>
                    <option value="doctor">DOCTOR</option>
                    <option value="patient">PATIENT</option>
                    <option value="admin">ADMIN</option>
                </select>
                <InputError class="mt-1" :message="form.errors.role" />
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    type="password"
                    name="floating_password"
                    id="password"
                    v-model="form.password"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.password" />
                <label
                    for="floating_password"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Password</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    type="text"
                    name="phone"
                    id="phone"
                    v-model="form.phone"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.phone" />
                <label
                    for="phone"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Contact no.</label
                >
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <input
                    type="text"
                    name="address"
                    id="address"
                    v-model="form.address"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    placeholder=" "
                />
                <InputError class="mt-1" :message="form.errors.address" />
                <label
                    for="address"
                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                    >Address</label
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
                    USERS
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
                                @click="openAddModal"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                                Add User
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
                                    <th scope="col" class="px-4 py-3">#</th>
                                    <th scope="col" class="px-4 py-3">Email</th>

                                    <th scope="col" class="px-4 py-3">
                                        Address
                                    </th>
                                    <th scope="col" class="px-4 py-3">
                                        Registered Date
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-4 py-3 flex items-center justify-end"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- Check if users exist -->
                                <tr v-if="users.data.length === 0">
                                    <td
                                        colspan="5"
                                        class="text-center text-gray-500"
                                    >
                                        Nothing found
                                    </td>
                                </tr>
                                <tr
                                    v-for="user in users.data"
                                    :key="user.id"
                                    class="border-b dark:border-gray-700"
                                >
                                    <th
                                        scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    >
                                        {{ user.id }}
                                    </th>
                                    <td class="px-4 py-3">{{ user.email }}</td>

                                    <td class="px-4 py-3">
                                        {{ user.address }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ user.created_at }}
                                    </td>
                                    <td
                                        class="px-4 py-3 flex items-center justify-end"
                                    >
                                        <!-- Dropdown Button -->
                                        <button
                                            @click="toggleDropdown(user.id)"
                                            :id="'dropdown-button-' + user.id"
                                            class="inline-flex items-center p-0.5 text-sm font-medium text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100"
                                            type="button"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                aria-hidden="true"
                                                fill="currentColor"
                                                viewbox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"
                                                />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div
                                            v-show="dropdownVisible[user.id]"
                                            :id="'dropdown-' + user.id"
                                            class="absolute right-0 mt-36 z-10 w-12 bg-white rounded-lg shadow-lg divide-y divide-gray-100 dark:bg-gray-700 dark:divide-gray-600 border border-gray-200"
                                        >
                                            <ul
                                                class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                                :aria-labelledby="
                                                    'dropdown-button-' + user.id
                                                "
                                            >
                                                <li>
                                                    <a
                                                        @click.prevent="
                                                            openViewModal(user)
                                                        "
                                                        href="#"
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    >
                                                        <i
                                                            class="fas fa-eye text-blue-500"
                                                        ></i
                                                    ></a>
                                                </li>
                                                <li>
                                                    <button
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                        type="button"
                                                        @click="
                                                            openEditModal(user)
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-edit text-green-500"
                                                        ></i>
                                                    </button>
                                                </li>

                                                <li>
                                                    <button
                                                        class="block py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                                        type="button"
                                                        @click="
                                                            confirmDelete(user)
                                                        "
                                                    >
                                                        <i
                                                            class="fas fa-trash text-red-500"
                                                        ></i>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Pagination Links -->
                        <div v-if="users.links.length > 1">
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
                                        >{{ users.from }}-{{ users.to }}</span
                                    >
                                    of
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white"
                                        >{{ users.total }}</span
                                    >
                                </span>
                                <ul
                                    class="inline-flex items-stretch -space-x-px"
                                >
                                    <li
                                        v-for="(link, index) in users.links"
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
