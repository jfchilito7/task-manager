<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white p-8 shadow-md rounded-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            Inicia Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
            Gestiona tus tareas de forma colaborativa
            </p>
        </div>
        
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ errorMessage }}</span>
        </div>

        <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
            <div class="-space-y-px rounded-md shadow-sm">
            <div>
                <label for="email-address" class="sr-only">Email</label>
                <input 
                v-model="email" 
                id="email-address" 
                name="email" 
                type="email" 
                required 
                class="relative block w-full rounded-t-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" 
                placeholder="Correo electrónico"
                >
            </div>
            <div>
                <label for="password" class="sr-only">Contraseña</label>
                <input 
                v-model="password" 
                id="password" 
                name="password" 
                type="password" 
                required 
                class="relative block w-full rounded-b-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" 
                placeholder="Contraseña"
                >
            </div>
            </div>

            <div>
            <button 
                type="submit" 
                class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                Ingresar
            </button>
            </div>
            
            <div class="text-sm text-center">
            <router-link to="/register" class="font-medium text-indigo-600 hover:text-indigo-500">
                ¿No tienes cuenta? Regístrate aquí
            </router-link>
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
    errorMessage.value = ''; // Limpiar errores previos
    
    try {
        // Hacemos la petición al backend
        const response = await axiosClient.post('/login', {
            email: email.value,
            password: password.value
        });

        // Guardamos el token
        localStorage.setItem('token', response.data.access_token);
        
        // Redirigimos al Dashboard 
        router.push('/dashboard');

    } catch (error) {
        console.error(error);
        if (error.response && error.response.data.message) {
            errorMessage.value = error.response.data.message;
        } else {
            errorMessage.value = 'Ocurrió un error al iniciar sesión';
        }
    }
};
</script>