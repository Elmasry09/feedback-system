<template>
    <GuestLayout>
        <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 px-6">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img class="mx-auto h-10 w-auto" src="https://www.svgrepo.com/show/301692/login.svg" alt="Workflow">
                <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                    Sign in to your account
                </h2>
            </div>
            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <form class="space-y-6" @submit.prevent="submit">
                        <div>
                            <label for="email" class="block text-sm font-medium leading-5  text-gray-700">Email
                                address</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input id="email" name="email" v-model="form.email" placeholder="user@example.com"
                                    type="email" required="" value=""
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="password"
                                class="block text-sm font-medium leading-5 text-gray-700">Password</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input id="password" name="password" v-model="form.password" type="password" required=""
                                    placeholder="Password"
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            </div>
                        </div>
                        <div class="mt-6">
                            <span class="block w-full rounded-md shadow-sm">
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                                    Sign in
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import GuestLayout from './Layouts/GuestLayout.vue';

const auth = useAuthStore()

const router = useRouter()
const form = ref({
    email: '',
    password: ''
})

const submit = async () => {
    const success = await auth.login(form.value);
    if (success === 200) {
        router.push({ name: "dashboard" });
    }
}

</script>