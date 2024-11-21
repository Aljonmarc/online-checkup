<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({
    name: '',
    email: '',
    role: "patient", // make sure this is present
    phone: '',
    address: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {

        onSuccess: () => {
            Swal.fire({
                title: 'Successfully Registered!',
                text: 'You have been successfully created your account.',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <!-- Name -->
            <h1 class="text-4xl font-bold text-yellow-500 text-center mb-6 dark:text-yellow-400">
            Register 
        </h1>

            <div>
                <InputLabel for="name" value="Name" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                     placeholder="Enter your name..."
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <InputLabel for="phone" value="Phone" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.phone"
                    required
                    autocomplete="phone"
                     placeholder="Enter your contact#..."
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <!-- Address -->
            <div class="mt-4">
                <InputLabel for="address" value="Address" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="address"
                    type="text"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.address"
                    required
                    autocomplete="address"
                     placeholder="Enter your address..."
                />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.email"
                    required
                    autocomplete="username"
                     placeholder="Enter your email..."
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    
                    id="password"
                    type="password"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                     placeholder="Enter your password..."
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                     placeholder="Repeat password..."
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4 bg-red-500 hover:bg-red-600 text-white focus:ring-red-500"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
