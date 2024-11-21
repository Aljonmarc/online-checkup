<script setup>
import PatientLayout from "@/Layouts/PatientLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, defineProps } from "vue";
import { Link as InertiaLink } from "@inertiajs/inertia-vue3";

import { Head, Link, useForm } from "@inertiajs/vue3";

import Swal from "sweetalert2";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

import NavLink from "@/Components/NavLink.vue";

import { router } from "@inertiajs/vue3";

const goToDepartment = (departmentId) => {
    router.get(`/departments/${departmentId}`);
};

// Props
defineProps({
    headers: Array,
    footers: Array,
    services: Array,
    departments: Array,
    doctors: Array,
    testimonials: Array,

    doctorCount: {
        type: Number,
        required: true,
    },

    departmentCount: {
        type: Number,
        required: true,
    },

    researchlabCount: {
        type: Number,
        required: true,
    },

    awardCount: {
        type: Number,
        required: true,
    },
});

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// Reactive state for feedback form
const feedback = useForm({
    message: "",
});

// Method to handle feedback form submission
const submitFeedback = () => {
    // Submit the form using Inertia
    feedback.post("/submit-feedback", {
        onSuccess: () => {
            // Clear the form or show a success message
            feedback.reset();
            Swal.fire({
                icon: "success",
                title: "Thank you!",
                text: "Your feedback has been submitted successfully.",
            });
        },
        onError: (errors) => {
            // Handle error (e.g., show error messages)
            console.log(errors);
            Swal.fire({
                icon: "error",
                title: "Something went wrong",
                text: "Please try again later.",
            });
        },
    });
};





</script>
<template>
    <Head title="Dashboard" />

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
        <!-- Hero Section -->
        <section
            id="hero"
            class="hero-section bg-gray-50 dark:bg-gray-50 py-56"
        >
            <div class="max-w-screen-xl mx-auto px-4 text-center">
                <h1
                    class="text-4xl font-bold text-gray-900 dark:text-gray mb-8"
                >
                    Welcome to Surigao Health Specialists' Inc.
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray">
                    Your trusted partner in healthcare services. Schedule
                    appointments, consult with specialists, and get the best
                    care at your convenience.
                </p>

                <p class="text-lg text-gray-600 dark:text-gray mb-16">
                    Streamline your healthcare experience with our user-friendly
                    Chatbot for instant support.
                </p>
                <Link
                        :href="route('patient.doctors')"
                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-lg px-6 py-3 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                >
                    Schedule an Appointment
            </Link>
            </div>
        </section>

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
                            {{ departmentCount }} specialized departments
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
                            {{ researchlabCount }} advanced labs
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
                            {{ awardCount }} excellence awards
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services Section -->
        <section id="services" class="bg-gray-100 dark:bg-gray-100 py-28">
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center"
                >
                    Services
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 mb-8 text-center"
                >
                    We offer a wide range of healthcare services tailored to
                    meet your needs, from routine checkups to specialized
                    treatments.
                </p>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <div
                        v-for="service in services.slice(0, 6)"
                        :key="service.id"
                        class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg"
                    >
                        <img
                            :src="'/serviceimage/' + service.image"
                            alt="Logo"
                            class="w-full h-20 object-contain rounded mb-4"
                        />
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-2"
                        >
                            {{ service.stitle }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ service.description }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- "Browse All" link -->
            <div class="mt-12 text-center">
                <Link
                    :href="route('patient.services')"
                    class="text-red-700 hover:text-red-800 font-medium rounded-lg text-lg px-6 py-3 underline focus:outline-none dark:focus:ring-red-800"
                >
                    Browse All Services
                </Link>
            </div>
        </section>

        <section id="departments" class="bg-gray-50 dark:bg-gray-100 py-28">
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center"
                >
                    Departments
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 mb-8 text-center"
                >
                    Our departments are staffed by experienced professionals
                    dedicated to providing the best care in their fields.
                </p>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8"
                >
                    <div
                        v-for="department in departments.slice(0, 10)"
                        :key="department.id"
                        @click="goToDepartment(department.id)"
                        class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl cursor-pointer"
                    >
                        <img
                            :src="'/departmentimage/' + department.imagedep"
                            alt="Logo"
                            class="w-full h-20 object-contain rounded mb-4"
                        />
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-2 text-center"
                        >
                            {{ department.titledep }}
                        </h3>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <Link
                        :href="route('patient.departments')"
                    class="text-red-700 hover:text-red-800 font-medium rounded-lg text-lg px-6 py-3 underline focus:outline-none dark:focus:ring-red-800"
                    >
                        Browse All Departments
                    </Link>
                </div>
            </div>
        </section>

        <!-- Doctors Section -->
        <section id="doctors" class="bg-gray-100 dark:bg-gray-100 py-28">
            <div class="max-w-screen-xl mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-8 text-center"
                >
                    Our Doctors
                </h2>
                <p
                    class="text-lg text-gray-600 dark:text-gray-400 mb-8 text-center"
                >
                    Meet our team of dedicated medical professionals, committed
                    to providing the highest quality care.
                </p>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8"
                >
                    <div
                        v-for="doctor in doctors.slice(0, 8)"
                        :key="doctor.id"
                        class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105 hover:shadow-xl"
                    >
                        <img
                            :src="'/doctorimage/' + doctor.docimage"
                            alt="Doctor Image"
                            class="w-28 h-28 mx-auto rounded-full mb-4"
                        />
                        <h3
                            class="text-xl font-semibold text-gray-900 dark:text-white mb-2 text-center"
                        >
                            {{ doctor.docname }}
                        </h3>
                        <h3
                            class="text-xl font-semibold text-yellow-500 dark:text-yellow mb-2 text-center"
                        >
                            <i class="fas fa-building mr-2 text-gray-500"></i>
                            {{ doctor.docroom }}
                        </h3>

                        <!-- Specialization -->
                        <h3
                            class="text-gray-600 dark:text-gray-400 text-sm mb-2 text-center"
                        >
                            <i class="fas fa-heartbeat mr-2 text-red-500"></i>
                            {{ doctor.docspec }}
                        </h3>

                        <h3
                            class="text-gray-600 dark:text-gray-400 text-sm text-center"
                        >
                            <i class="fas fa-phone mr-2 text-blue-500"></i>
                            {{ doctor.docphone }}
                        </h3>

                        <!-- Link to Doctor Profile -->
                        <inertia-link
                            :href="`/doctor/${doctor.docname}`"
                            class="block text-center text-blue-500 mt-4"
                        >
                            View Schedule
                        </inertia-link>
                    </div>
                </div>
                <div class="mt-12 text-center">
                    <Link
                        :href="route('patient.doctors')"
                    class="text-red-700 hover:text-red-800 font-medium rounded-lg text-lg px-6 py-3 underline focus:outline-none dark:focus:ring-red-800"
                    >
                        Browse All Doctors
                    </Link>
                </div>
            </div>
        </section>

        <!-- Support Section (Feedback Form) -->
        <section id="contact" class="bg-gray-50 dark:bg-gray-700 py-28">
            <div class="max-w-screen-lg mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left: Introductory Text -->
                    <div class="flex flex-col justify-center">
                        <h2
                            class="text-3xl font-bold text-gray-900 dark:text-white mb-8"
                        >
                            We Value Your Feedback
                        </h2>

                        <!-- Circle Image -->
                        <img
                            src="/logo.png"
                            alt="Feedback"
                            class="w-32 h-32 rounded-full mb-8 object-cover"
                        />
                        <p
                            class="text-lg text-gray-600 dark:text-gray-400 mb-8"
                        >
                            Please let us know how we can improve our services
                            and your experience with us. Your feedback is
                            important in helping us serve you better.
                        </p>
                    </div>

                    <!-- Right: Feedback Form -->
                    <div>
                        <form
                            @submit.prevent="submitFeedback"
                            class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg"
                        >
                            <div class="mb-6">
                                <label
                                    for="message"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                    >Message</label
                                >
                                <textarea
                                    v-model="feedback.message"
                                    id="message"
                                    rows="5"
                                    class="block w-full p-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm rounded-lg bg-gray-100 dark:bg-gray-700 dark:border-gray-600 text-gray-900 dark:text-white"
                                    required
                                ></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-red-700 text-white p-3 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800"
                            >
                                Submit Feedback
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="bg-gray-100 dark:bg-gray-900 py-28">
            <div class="max-w-screen-lg mx-auto px-4">
                <h2
                    class="text-3xl font-bold text-gray-900 dark:text-white mb-12 text-center"
                >
                    What Our Patients Say
                </h2>
                <div class="flex overflow-x-auto space-x-8">
                    <!-- Testimonial 1 -->
                    <div
                        v-for="testimonial in testimonials"
                        :key="testimonial.id"
                        class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-lg min-w-[300px] mb-2"
                    >
                        <div class="flex items-center mb-4">
                            <div>
                                <h3
                                    class="text-xl font-semibold text-gray-900 dark:text-white"
                                >
                                    {{ testimonial.user.name }}
                                </h3>
                                <p
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >
                                    {{ testimonial.user.role }}
                                </p>
                            </div>
                        </div>
                        <!-- Testimonial Message -->
                        <p
                            class="testimonial-message text-gray-600 dark:text-gray-400"
                        >
                            "{{ testimonial.message }}"
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

<style scoped>
/* Hero Section Styles */
.hero-section {
    background-image: url("/bglogo.jpg");
    /* Path to your background image */
    background-size: cover;
    background-position: center;
    animation: backgroundLoop 30s infinite linear;
    /* Add keyframe animation */
}

/* Keyframes for background loop */
@keyframes backgroundLoop {
    0% {
        background-position: 0% 50%;
    }

    50% {
        background-position: 100% 50%;
    }

    100% {
        background-position: 0% 50%;
    }
}

.testimonial-message {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

/* Apply to all sections or specific sections */
section {
    word-wrap: break-word;
    overflow-wrap: break-word;
    white-space: normal;
}

/* Or for specific elements */
section p {
    word-wrap: break-word;
    overflow-wrap: break-word;
}
</style>
