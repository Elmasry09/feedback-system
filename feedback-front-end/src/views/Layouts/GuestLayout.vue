<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
const auth = useAuthStore();
</script>
<template>

    <body class="bg-white text-black font-framer antialiased overflow-x-hidden">
        <!-- Navigation -->
        <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-sm border-b border-black/5">
            <div class="max-w-7xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-12">
                        <span class="text-2xl font-bold tracking-tighter">Feedback</span>
                        <div class="hidden md:flex space-x-8">
                            <router-link :to="{ name: 'home' }">
                                <a
                                    class="text-sm font-medium hover:text-black/60 transition-colors duration-300 relative group">
                                    Home
                                    <span
                                        class="absolute bottom-0 left-0 w-0 h-px bg-black transition-all duration-300 group-hover:w-full"></span>
                                </a>
                            </router-link>
                            <router-link :to="{ name: 'feedback' }">
                                <a
                                    class="text-sm font-medium hover:text-black/60 transition-colors duration-300 relative group">
                                    Feedback
                                    <span
                                        class="absolute bottom-0 left-0 w-0 h-px bg-black transition-all duration-300 group-hover:w-full"></span>
                                </a>
                            </router-link>
                        </div>
                    </div>
                    <div v-if="!auth.isLoggedIn" class="hidden md:block">
                        <router-link :to="{ name: 'login' }">
                            <button
                                class="px-6 py-2 border border-black rounded-full text-sm font-medium hover:bg-black hover:text-white transition-all duration-300">
                                Login
                            </button>
                        </router-link>
                    </div>
                    <button class="md:hidden">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 12H21" stroke="black" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M3 6H21" stroke="black" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M3 18H21" stroke="black" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </nav>


        <div class="p-4 mt-14 m-auto dark:bg-gray-800 min-h-screen pb-20">
            <slot />
        </div>


        <!-- Footer -->
        <footer class="bg-white py-10 w-full">
            <div class="max-w-7xl mx-auto">
                <div class="pt-3 border-t border-black/5 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-xs text-black/40">© 2023 Studio. All rights reserved.</p>
                    <div class="mt-4 md:mt-0 flex space-x-6">
                        <a href="#"
                            class="text-xs text-black/40 hover:text-black transition-colors duration-300">Privacy</a>
                        <a href="#"
                            class="text-xs text-black/40 hover:text-black transition-colors duration-300">Terms</a>
                        <a href="#"
                            class="text-xs text-black/40 hover:text-black transition-colors duration-300">Cookies</a>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</template>
