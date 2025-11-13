<template>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-xl p-8 space-y-6">
        
        <div class="text-center">
            
            <h2 class="text-3xl font-extrabold text-white">Crea tu cuenta</h2>
            <p class="mt-2 text-sm text-gray-300">
            Empieza a organizar tus tareas hoy mismo
            </p>
        </div>
        
        <div v-if="errorMessage" class="bg-red-900/50 border border-red-500 text-red-200 px-4 py-3 rounded relative text-sm">
            <span class="font-bold">Error: </span>
            <span class="block sm:inline">{{ errorMessage }}</span>
        </div>

        <form class="mt-8 space-y-4" @submit.prevent="handleRegister">
            <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nombre Completo</label>
                <input 
                v-model="name" 
                id="name" 
                name="name" 
                type="text" 
                required 
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-400 text-white bg-gray-700 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                placeholder="Ej: Juan Pérez"
                >
            </div>

            <div>
                <label for="email-address" class="block text-sm font-medium text-gray-300 mb-1">Correo electrónico</label>
                <input 
                v-model="email" 
                id="email-address" 
                name="email" 
        `          type="email" 
                required 
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-400 text-white bg-gray-700 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                placeholder="correo@ejemplo.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Contraseña</label>
                <input 
                v-model="password" 
                id="password" 
                name="password" 
                type="password" 
                required 
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-400 text-white bg-gray-700 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm" 
                placeholder="Mínimo 8 caracteres"
                >
            </div>
            </div>

            <div>
            <button 
                type="submit" 
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
            >
                Registrarse
            </button>
            </div>
            
            <div class="text-sm text-center">
            <router-link to="/login" class="font-medium text-red-400 hover:text-red-300">
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