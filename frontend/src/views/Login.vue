<template>
    <div class="min-h-screen flex items-center justify-center p-4"> <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-xl p-8 space-y-6"> <div class="text-center">
        <h2 class="text-3xl font-extrabold text-white">Inicio Sesión</h2> <p class="mt-2 text-sm text-gray-300">Gestiona tus tareas de forma colaborativa</p> </div>
      <form @submit.prevent="login" class="space-y-4">
        <div>
          <label for="email" class="sr-only">Correo electrónico</label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required
            v-model="email"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-400 text-white bg-gray-700 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
            placeholder="Correo electrónico"
          />
        </div>
        <div>
          <label for="password" class="sr-only">Contraseña</label>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
            v-model="password"
            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-400 text-white bg-gray-700 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm"
            placeholder="Contraseña"
          />
        </div>
        <div>
          <button
            type="submit"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
          >
            Ingresar
          </button>
        </div>
      </form>
      <div class="text-center text-sm">
        <router-link to="/register" class="font-medium text-red-400 hover:text-red-300">
          ¿No tienes cuenta? Regístrate aquí
        </router-link>
      </div>
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