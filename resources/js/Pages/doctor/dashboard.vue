<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({

scheduleCount: {
    type: Number,
    required: true
},

bookCount: {
    type: Number,
    required: true
},


})
</script>

<template>
    <Head title="Doctor Dashboard" />

    <DoctorLayout>
        <div class="py-5">
            <div
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2"
            >
                DASHBOARD
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Appointments Card -->
                <Link :href="route('doctor.appointment')"
                    class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl"
                >
                    <div>
                        <h2 class="text-lg font-semibold mb-2">
                            Total Appointments
                        </h2>
                        <p class="text-4xl font-bold">{{ bookCount }}</p>
                    </div>
                    <i class="fas fa-calendar-alt text-4xl text-blue-500"></i>
                    <!-- Doctor icon with blue -->
                </Link>

                <!-- Total Schedule Card -->
                <Link :href="route('doctor.schedule')"
                    class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl"
                >
                    <div>
                        <h2 class="text-lg font-semibold mb-2">
                            Total Schedule
                        </h2>
                        <p class="text-4xl font-bold">{{ scheduleCount }}</p>
                    </div>
                    <i class="fas fa-clock text-4xl text-green-500"></i>
                    <!-- Calendar icon with green -->
                </Link>
            </div>
        </div>
    </DoctorLayout>
</template>

<style scoped>
.grid {
    margin: 0 auto;
    max-width: 1200px;
}
.bg-white {
    background-color: #fff;
}
.shadow {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.rounded-lg {
    border-radius: 0.5rem;
}
.p-6 {
    padding: 1.5rem;
}
.font-bold {
    font-weight: 700;
}
.text-gray-400 {
    color: #cbd5e0;
}
</style>
