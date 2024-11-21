<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({

userCount: {
    type: Number,
    required: true
},

bookCount: {
    type: Number,
    required: true
},

servicesCount: {
    type: Number,
    required: true
},

departmentsCount: {
    type: Number,
    required: true
},


doctorsCount: {
    type: Number,
    required: true
},

research_labsCount: {
    type: Number,
    required: true
},

awardsCount: {
    type: Number,
    required: true
},

roomsCount: {
    type: Number,
    required: true
},

specialtyCount: {
    type: Number,
    required: true
},


scheduleCount: {
    type: Number,
    required: true
},


})
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        
        <div class="py-5">
            <div
                class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-2"
            >
                DASHBOARD
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Total Appointments Card -->
                <Link :href="route('admin.appointment')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Appointments</h2>
                        <p class="text-4xl font-bold">{{ bookCount }}</p>
                    </div>
                    <i class="fas fa-calendar-check text-4xl text-blue-400"></i>
                </Link>


                      <!-- Total Users Card -->
                      <Link :href="route('admin.users')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Users</h2>
                        <p class="text-4xl font-bold">{{ userCount }}</p>
                    </div>
                    <i class="fas fa-users text-4xl text-yellow-400"></i>
                </Link>

                <!-- Total Services Card -->
                <Link :href="route('admin.services')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Services</h2>
                        <p class="text-4xl font-bold">{{ servicesCount }}</p>
                    </div>
                    <i class="fas fa-concierge-bell text-4xl text-red-400"></i>
                </Link>

                <!-- Total Departments Card -->
                <Link :href="route('admin.departments')"class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Departments</h2>
                        <p class="text-4xl font-bold">{{ departmentsCount }}</p>
                    </div>
                    <i class="fas fa-building text-4xl text-green-400"></i>
                </Link>

                     <!-- Total Doctors Card -->
                     <Link :href="route('admin.doctors')"class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Doctors</h2>
                        <p class="text-4xl font-bold">{{ doctorsCount }}</p>
                    </div>
                    <i class="fas fa-user-md text-4xl text-purple-400"></i>
                </Link>

                
                     <!-- Total Research Labs Card -->
                     <Link :href="route('admin.research_labs')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Research Labs</h2>
                        <p class="text-4xl font-bold">{{ research_labsCount }}</p>
                    </div>
                    <i class="fas fa-flask text-4xl text-pink-400"></i>
                </Link>

                        <!-- Total Awards Labs Card -->
                        <Link :href="route('admin.awards')"class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Awards</h2>
                        <p class="text-4xl font-bold">{{ awardsCount }}</p>
                    </div>
                    <i class="fas fa-trophy text-4xl text-red-400"></i>
                </Link>


                      <!-- Total Rooms Labs Card -->
                      <Link :href="route('admin.rooms')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Rooms</h2>
                        <p class="text-4xl font-bold">{{ roomsCount }}</p>
                    </div>
                    <i class="fas fa-bed text-4xl text-blue-400"></i>
                </Link>


                      <!-- Total Specialty Labs Card -->
                      <Link :href="route('admin.specialtys')" class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Specialty</h2>
                        <p class="text-4xl font-bold">{{ specialtyCount }}</p>
                    </div>
                    <i class="fas fa-heartbeat text-4xl text-red-400"></i>
                </Link>
          

                <!-- Total Time Ins Card -->
                <Link :href="route('admin.schedule')"class="bg-white shadow rounded-lg p-6 flex justify-between items-center transition-transform transform hover:scale-105 hover:bg-gray-100 hover:shadow-xl">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Total Schedules</h2>
                        <p class="text-4xl font-bold">{{ scheduleCount }}</p>
                    </div>
                    <i class="fas fa-clock text-4xl text-blue-400"></i>
                </Link>
            </div>
        </div>
    </AdminLayout>
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
