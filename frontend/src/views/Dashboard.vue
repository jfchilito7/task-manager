<template>
    <div class="min-h-screen pb-10">
        
        <nav class="bg-gray-800 shadow-lg sticky top-0 z-10 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-white">Task Manager</h1>
            </div>
            <div class="flex items-center">
                <button @click="logout" class="text-sm text-gray-300 hover:text-red-400 font-medium transition border border-gray-600 rounded px-3 py-1 hover:border-red-400">
                Cerrar Sesión
                </button>
            </div>
            </div>
        </div>
        </nav>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        
        <div class="bg-gray-800 shadow-xl sm:rounded-lg mb-8 p-6 border-t-4" :class="isEditing ? 'border-yellow-500' : 'border-red-600'">
            <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold leading-6 text-white">
                {{ isEditing ? '✏️ Editar Tarea' : '✨ Nueva Tarea' }}
            </h3>
            <button v-if="isEditing" @click="cancelEdit" class="text-sm text-gray-400 hover:text-white underline">
                Cancelar edición
            </button>
            </div>

            <form @submit.prevent="saveTask" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label class="block text-sm font-medium text-gray-300 mb-1">Título</label>
                <input 
                v-model="form.title" 
                type="text" 
                placeholder="Ej: Revisar documentación" 
                required 
                class="block w-full rounded-md border border-gray-600 bg-gray-700 text-white py-2 px-3 shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm placeholder-gray-400"
                >
            </div>
            
            <div class="sm:col-span-1 sm:col-start-4">
                <label class="block text-sm font-medium text-gray-300 mb-1">Estado</label>
                <select v-model="form.status" class="block w-full rounded-md border border-gray-600 bg-gray-700 text-white py-2 px-3 shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="pendiente">🕒 Pendiente</option>
                <option value="en_progreso">🚀 En Progreso</option>
                <option value="completada">✅ Completada</option>
                </select>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-1">Prioridad</label>
                <select v-model="form.priority" class="block w-full rounded-md border border-gray-600 bg-gray-700 text-white py-2 px-3 shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm">
                <option value="low">🟢 Baja</option>
                <option value="medium">🟡 Media</option>
                <option value="high">🔴 Alta</option>
                </select>
            </div>
            
            <div class="sm:col-span-6">
                <label class="block text-sm font-medium text-gray-300 mb-1">Descripción</label>
                <textarea 
                v-model="form.description" 
                rows="2" 
                placeholder="Detalles adicionales..." 
                class="block w-full rounded-md border border-gray-600 bg-gray-700 text-white py-2 px-3 shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm placeholder-gray-400"
                ></textarea>
            </div>
            
            <div class="sm:col-span-6 text-right">
                <button type="submit" 
                class="inline-flex justify-center rounded-md py-2 px-6 text-sm font-semibold text-white shadow-sm focus-visible:outline focus-visible:outline-offset-2 transition-colors"
                :class="isEditing ? 'bg-yellow-600 hover:bg-yellow-500' : 'bg-red-600 hover:bg-red-500'">
                {{ isEditing ? 'Actualizar Tarea' : 'Guardar Tarea' }}
                </button>
            </div>
            </form>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <div class="flex gap-2 flex-wrap">
                <button @click="filterStatus = 'all'" 
                    :class="filterStatus === 'all' ? 'bg-gray-700 text-white border-red-500' : 'bg-gray-800 text-gray-400 border-gray-700 hover:bg-gray-700'" 
                    class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border transition">
                    Todo
                </button>
                
                <button @click="filterStatus = 'pendiente'" 
                    :class="filterStatus === 'pendiente' ? 'bg-yellow-900/50 text-yellow-200 border-yellow-500' : 'bg-gray-800 text-gray-400 border-gray-700 hover:bg-gray-700'" 
                    class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border transition">
                    Pendientes
                </button>

                <button @click="filterStatus = 'en_progreso'" 
                    :class="filterStatus === 'en_progreso' ? 'bg-blue-900/50 text-blue-200 border-blue-500' : 'bg-gray-800 text-gray-400 border-gray-700 hover:bg-gray-700'" 
                    class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border transition">
                    En Progreso
                </button>

                <button @click="filterStatus = 'completada'" 
                    :class="filterStatus === 'completada' ? 'bg-green-900/50 text-green-200 border-green-500' : 'bg-gray-800 text-gray-400 border-gray-700 hover:bg-gray-700'" 
                    class="px-4 py-2 rounded-full text-sm font-medium shadow-sm border transition">
                    Completadas
                </button>
            </div>
            <p class="text-sm text-gray-300 bg-gray-800 px-3 py-1 rounded-full">
                Mostrando {{ filteredTasks.length }} tareas
            </p>
        </div>

        <div class="bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden border border-gray-700">
            <ul role="list" class="divide-y divide-gray-700">
            <li v-for="task in filteredTasks" :key="task.id" class="group hover:bg-gray-700/50 transition duration-150 ease-in-out">
                <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                
                <div class="flex-1 min-w-0 mr-4">
                    <div class="flex items-center gap-3 mb-1">
                        <p class="text-md font-semibold text-white truncate">{{ task.title }}</p>
                        
                        <span :class="{
                        'bg-yellow-900/60 text-yellow-200 border-yellow-700': task.status === 'pendiente',
                        'bg-blue-900/60 text-blue-200 border-blue-700': task.status === 'en_progreso',
                        'bg-green-900/60 text-green-200 border-green-700': task.status === 'completada'
                        }" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium border ring-1 ring-inset ring-white/10">
                        {{ formatStatus(task.status) }}
                        </span>

                        <span :class="{
                        'bg-green-900/60 text-green-200 border-green-600': task.priority === 'low',
                        'bg-yellow-900/60 text-yellow-200 border-yellow-600': task.priority === 'medium',
                        'bg-red-900/60 text-red-200 border-red-600': task.priority === 'high',
                        }" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-bold border uppercase tracking-wide">
                        {{ formatPriority(task.priority) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-400 wrap-break-words">{{ task.description || 'Sin descripción' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Creado: {{ new Date(task.created_at).toLocaleDateString() }}</p>
                </div>

                <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                    <button @click="loadTaskForEdit(task)" class="p-2 text-blue-400 hover:bg-blue-900/30 rounded-full" title="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                    </button>
                    <button @click="deleteTask(task.id)" class="p-2 text-red-400 hover:bg-red-900/30 rounded-full" title="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                    </button>
                </div>

                </div>
            </li>
            
            <li v-if="filteredTasks.length === 0" class="px-4 py-12 text-center text-gray-500">
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
const filterStatus = ref('all');
const isEditing = ref(false);
const editingId = ref(null);

const form = ref({
    title: '',
    description: '',
    status: 'pendiente',
    priority: 'medium'
});

const loadTasks = async () => {
    try {
        const response = await axiosClient.get('/tasks');
        tasks.value = response.data;
    } catch (error) {
        if (error.response && error.response.status === 401) {
            localStorage.removeItem('token');
            router.push('/login');
        }
    }
};

onMounted(() => {
    loadTasks();
});

const filteredTasks = computed(() => {
    if (filterStatus.value === 'all') return tasks.value;
    return tasks.value.filter(task => task.status === filterStatus.value);
});

const formatStatus = (status) => {
    const map = { 
        'pendiente': 'Pendiente', 
        'en_progreso': 'En Progreso', 
        'completada': 'Completada' 
    };
    return map[status] || status;
};

const formatPriority = (priority) => {
    const map = { 'low': 'Baja', 'medium': 'Media', 'high': 'Alta' };
    return map[priority] || 'Normal';
};

const saveTask = async () => {
    try {
        if (isEditing.value) {
        await axiosClient.put(`/tasks/${editingId.value}`, form.value);
        } else {
        await axiosClient.post('/tasks', form.value);
        }
        cancelEdit();
        await loadTasks();
    } catch (error) {
        alert("Error al guardar. Verifica que el título no esté vacío.");
    }
};

const loadTaskForEdit = (task) => {
  form.value = { 
    title: task.title, 
    description: task.description, 
    status: task.status,
    priority: task.priority || 'medium'
  };
  editingId.value = task.id;
  isEditing.value = true;
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const cancelEdit = () => {
  form.value = { title: '', description: '', status: 'pendiente', priority: 'medium' };
  isEditing.value = false;
  editingId.value = null;
};

const deleteTask = async (id) => {
  if (!confirm('¿Estás seguro de borrar esta tarea?')) return;
  try {
    await axiosClient.delete(`/tasks/${id}`);
    await loadTasks();
  } catch (error) {
    console.error("Error borrando", error);
  }
};

const logout = () => {
  localStorage.removeItem('token');
  router.push('/login');
};
</script>