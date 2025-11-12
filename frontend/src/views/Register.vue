<template>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 bg-white p-8 shadow-md rounded-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            Crea tu cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
            Empieza a organizar tus tareas hoy mismo
            </p>
        </div>
        
        <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm">
            <span class="font-bold">Error: </span>
            <span class="block sm:inline">{{ errorMessage }}</span>
        </div>

        <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
            <div class="space-y-4 rounded-md shadow-sm">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                <input 
                v-model="name" 
                id="name" 
                name="name" 
                type="text" 
                required 
                class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3 shadow-sm" 
                placeholder="Ej: Juan Pérez"
                >
            </div>

            <div>
                <label for="email-address" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input 
                v-model="email" 
                id="email-address" 
                name="email" 
                type="email" 
                required 
                class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3 shadow-sm" 
                placeholder="correo@ejemplo.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input 
                v-model="password" 
                id="password" 
                name="password" 
                type="password" 
                required 
                class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3 shadow-sm" 
                placeholder="Mínimo 8 caracteres"
                >
            </div>
            </div>

            <div>
            <button 
                type="submit" 
                class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150 ease-in-out"
            >
                Registrarse
            </button>
            </div>
            
            <div class="text-sm text-center">
            <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                ¿Ya tienes cuenta? Inicia sesión
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

const name = ref('');
const email = ref('');
const password = ref('');
const errorMessage = ref('');
const router = useRouter();

const handleRegister = async () => {
    errorMessage.value = '';
    
    try {
        // Enviamos los datos al backend
        const response = await axiosClient.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value
        });

        // ÉXITO: Guardamos el token y entramos
        localStorage.setItem('token', response.data.access_token);
        router.push('/dashboard');

    } catch (error) {
        console.error(error);
        // Manejo de errores detallado
        if (error.response && error.response.data.message) {
            errorMessage.value = error.response.data.message; // Ej: "El email ya ha sido registrado"
        } else {
            errorMessage.value = 'Ocurrió un error inesperado al registrarse.';
        }
    }
};
</script>