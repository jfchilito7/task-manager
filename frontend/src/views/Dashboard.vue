<template>
    <div class="min-h-screen bg-gray-50 pb-10">
        <nav class="bg-white shadow sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-indigo-600">Task Manager</h1>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Prueba Técnica</span>
            </div>
            <div class="flex items-center">
                <button @click="logout" class="text-sm text-gray-600 hover:text-red-600 font-medium transition border border-gray-300 rounded px-3 py-1 hover:bg-gray-50">
                Cerrar Sesión
                </button>
            </div>
            </div>
        </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white shadow-lg sm:rounded-lg mb-8 p-6 border-t-4" :class="isEditing ? 'border-yellow-500' : 'border-indigo-600'">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold leading-6 text-gray-900">
                {{ isEditing ? '✏️ Editar Tarea' : '✨ Nueva Tarea' }}
            </h3>
            <button v-if="isEditing" @click="cancelEdit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                Cancelar edición
            </button>
            </div>

            <form @submit.prevent="saveTask" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input v-model="form.title" type="text" placeholder="Ej: Revisar documentación" required class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm px-3">
            </div>
            
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select v-model="form.status" class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm px-3">
                <option value="pendiente">🕒 Pendiente</option>
                <option value="en_progreso">🚀 En Progreso</option>
                <option value="completada">✅ Completada</option>
                </select>
            </div>
            
            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea v-model="form.description" rows="2" placeholder="Detalles adicionales..." class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm px-3"></textarea>
            </div>
            
            <div class="sm:col-span-6 text-right">
                <button type="submit" 
                class="inline-flex justify-center rounded-md py-2 px-6 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition-colors"
                :class="isEditing ? 'bg-yellow-600 hover:bg-yellow-500' : 'bg-indigo-600 hover:bg-indigo-500'">
                {{ isEditing ? 'Actualizar Tarea' : 'Guardar Tarea' }}
                </button>
            </div>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <div class="flex gap-2">
                <button @click="filterStatus = 'all'" :class="filterStatus === 'all' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border hover:bg-gray-50 transition">Todo</button>
                <button @click="filterStatus = 'pendiente'" :class="filterStatus === 'pendiente' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border hover:bg-gray-50 transition">Pendientes</button>
                <button @click="filterStatus = 'completada'" :class="filterStatus === 'completada' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-white text-gray-700'" class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border hover:bg-gray-50 transition">Completadas</button>
            </div>
            <p class="text-sm text-gray-500">
                Mostrando {{ filteredTasks.length }} tareas
            </p>
        </div>

        <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
            <ul role="list" class="divide-y divide-gray-200">
            <li v-for="task in filteredTasks" :key="task.id" class="group hover:bg-indigo-50 transition duration-150 ease-in-out">
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                
                <div class="flex-1 min-w-0 mr-4">
                    <div class="flex items-center gap-3 mb-1">
                        <p class="text-md font-semibold text-indigo-700 truncate">{{ task.title }}</p>
                        <span :class="{
                        'bg-yellow-100 text-yellow-800': task.status === 'pendiente',
                        'bg-blue-100 text-blue-800': task.status === 'en_progreso',
                        'bg-green-100 text-green-800': task.status === 'completada'
                        }" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-gray-500/10">
                        {{ formatStatus(task.status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 wrap-break-words">{{ task.description || 'Sin descripción' }}</p>
                    <p class="text-xs text-gray-400 mt-1">Creado: {{ new Date(task.created_at).toLocaleDateString() }}</p>
                </div>

                <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                    <button @click="loadTaskForEdit(task)" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-full" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    </button>
                    <button @click="deleteTask(task.id)" class="p-2 text-red-600 hover:bg-red-100 rounded-full" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    </button>
                </div>

                </div>
            </li>
            <li v-if="filteredTasks.length === 0" class="px-4 py-12 text-center text-gray-400 bg-gray-50">
                <p class="text-lg">📭 No hay tareas en esta lista.</p>
            </li>
            </ul>
        </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import axiosClient from '../axios';

const router = useRouter();
const tasks = ref([]);
const filterStatus = ref('all'); // 'all', 'pendiente', 'completada'

// Variables para edición
const isEditing = ref(false);
const editingId = ref(null);

const form = ref({
    title: '',
    description: '',
    status: 'pendiente'
});

// --- CARGAR DATOS ---
const loadTasks = async () => {
    try {
        const response = await axiosClient.get('/tasks');
        tasks.value = response.data;
    } catch (error) {
        if (error.response && error.response.status === 401) router.push('/login');
    }
};

onMounted(() => {
    loadTasks();
});

// --- LÓGICA DE FILTROS (COMPUTADA) ---
// Esto filtra las tareas en tiempo real sin llamar a la API de nuevo
const filteredTasks = computed(() => {
    if (filterStatus.value === 'all') return tasks.value;
    return tasks.value.filter(task => task.status === filterStatus.value);
});

const formatStatus = (status) => {
    const map = { 'pendiente': 'Pendiente', 'en_progreso': 'En Progreso', 'completada': 'Completada' };
    return map[status] || status;
};

// --- CRUD: CREAR O ACTUALIZAR ---
const saveTask = async () => {
    try {
        if (isEditing.value) {
        // ACTUALIZAR (PUT)
        await axiosClient.put(`/tasks/${editingId.value}`, form.value);
        // Opcional: Mostrar notificación de éxito
        } else {
        // CREAR (POST)
        await axiosClient.post('/tasks', form.value);
    }
    
    // Resetear formulario y recargar
    cancelEdit();
    await loadTasks();
    
    } catch (error) {
        console.error("Error guardando tarea:", error);
        alert("Hubo un error al guardar. Revisa los datos.");
    }
};

// --- PREPARAR EDICIÓN ---
const loadTaskForEdit = (task) => {
  // Rellenamos el formulario con los datos de la tarea seleccionada
    form.value = { 
        title: task.title, 
        description: task.description, 
        status: task.status 
    };
    editingId.value = task.id;
    isEditing.value = true;
    
    // Scroll suave hacia arriba para ver el formulario
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
    form.value = { title: '', description: '', status: 'pendiente' };
    isEditing.value = false;
    editingId.value = null;
};

// --- ELIMINAR ---
const deleteTask = async (id) => {
    if (!confirm('¿Estás seguro de eliminar esta tarea? No hay vuelta atrás.')) return;
    
    try {
        await axiosClient.delete(`/tasks/${id}`);
        await loadTasks();
    } catch (error) {
        console.error(error);
    }
};

const logout = () => {
    localStorage.removeItem('access_token');
    router.push('/login');
};
</script>