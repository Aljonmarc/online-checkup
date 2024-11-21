<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {

   
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ status }}
        </div>

        <h1 class="text-4xl font-bold text-yellow-500 text-center mb-6 dark:text-yellow-400">
            Log in
        </h1>


        <form @submit.prevent="submit">
            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 dark:text-gray-300" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full bg-white-100 dark:bg-white-900 text-white-700 dark:text-white-300 border border-white-300 dark:border-white-600 focus:border-red-500 focus:ring-red-500"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Enter your email here.."
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
                    autocomplete="current-password"
                     placeholder="Enter your password here.."
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ms-4 bg-red-500 hover:bg-red-600 text-white focus:ring-red-500"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
