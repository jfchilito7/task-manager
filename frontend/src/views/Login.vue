<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4">
        <div class="w-full max-w-md space-y-8 bg-white p-8 shadow-md rounded-lg">
        <h2 class="text-center text-3xl font-bold text-gray-900">Inicia Sesión</h2>
        
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ errorMessage }}
        </div>

        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
            <div class="space-y-4">
            <input v-model="email" type="email" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" placeholder="Correo electrónico">
            <input v-model="password" type="password" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" placeholder="Contraseña">
            </div>

            <button type="submit" class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Ingresar
            </button>
            
            <div class="text-center">
                <router-link to="/register" class="text-sm text-indigo-600 hover:text-indigo-500">¿No tienes cuenta? Regístrate</router-link>
            </div>
        </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axiosClient from '../axios';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleLogin = async () => {
    errorMessage.value = '';
    console.log("Intentando login..."); // <--- Agregué esto para ver si sale en consola
    
    try {
        const response = await axiosClient.post('/login', {
            email: email.value,
            password: password.value
        });
        localStorage.setItem('token', response.data.access_token);
        router.push('/dashboard');
    } catch (error) {
        console.error(error);
        errorMessage.value = 'Error al iniciar sesión. Verifica tus credenciales.';
    }
};
</script>