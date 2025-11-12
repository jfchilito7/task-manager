import axios from "axios";

const axiosClient = axios.create({
    baseURL: "http://localhost:8000/api",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

// --- INTERCEPTOR DE REQUEST (EL MÁS IMPORTANTE) ---
// Esto se ejecuta ANTES de CADA petición.
axiosClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("token"); // 1. Busca el token
        if (token) {
        // 2. Si existe, lo "pega" en la cabecera
        config.headers.Authorization = `Bearer ${token}`; 
        }
        return config; // 3. Deja que la petición continúe
    },
    (error) => {
        return Promise.reject(error);
    }
);


// --- INTERCEPTOR DE RESPONSE (La "vacuna") ---
// Esto se ejecuta DESPUÉS de recibir una respuesta con ERROR
axiosClient.interceptors.response.use(
    (response) => {
        return response; // Si la respuesta es 200 (OK), no hace nada
    },
    (error) => {
        // Si el error es 401 (Token inválido o vencido)
        if (error.response && error.response.status === 401) {
        localStorage.removeItem("token"); // Borra el token malo
        window.location.href = '/login';  // Te manda al login
        }
        return Promise.reject(error); // Deja que el componente reciba el error
    }
);

export default axiosClient;