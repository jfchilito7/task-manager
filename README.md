# 🚀 Gestor de Tareas Colaborativo (Task Manager)

> **Prueba Técnica Full Stack Developer**

Este repositorio contiene una aplicación web robusta para la gestión de tareas, desarrollada siguiendo estándares de la industria, arquitectura limpia y contenedores para un despliegue agnóstico al entorno.

La solución implementa un Backend API RESTful con **Laravel** y un Frontend reactivo con **Vue 3**, comunicándose de manera segura mediante autenticación basada en tokens.

---

## 📸 Vistazo General

El proyecto ha sido personalizado con un diseño **Dark Mode** con acentos corporativos en rojo, asegurando una experiencia de usuario moderna y profesional.

### Características Principales

- **🔐 Autenticación Segura:** Registro e Inicio de Sesión utilizando Laravel Sanctum (Tokens Bearer).
- **📋 CRUD Completo:** Gestión total de tareas (Crear, Leer, Actualizar, Eliminar).
- **🚦 Sistema de Prioridades:** Clasificación visual de tareas (Baja 🟢, Media 🟡, Alta 🔴).
- **🔍 Filtros Reactivos:** Filtrado instantáneo por estado (Pendiente, En Progreso, Completada) sin recargar la página.
- **🐳 Entorno Dockerizado:** Configuración `docker-compose` lista para levantar todo el stack con un solo comando.
- **🎨 UI/UX Moderna:** Uso de TailwindCSS para un diseño responsivo y adaptado.
- **🛡️ Manejo de Errores:** Interceptores globales en Axios para gestionar tokens expirados (401) y validaciones de servidor (422).

---

## 🛠️ Stack Tecnológico

### Backend

- **Framework:** Laravel 11
- **Base de Datos:** SQLite (Seleccionada por su portabilidad y rapidez de configuración para pruebas).
- **Seguridad:** Laravel Sanctum & CORS configurado.
- **Arquitectura:** API Resources, FormRequests para validación y Controladores limpios.

### Frontend

- **Framework:** Vue.js 3 (Composition API + `<script setup>`).
- **Estilos:** Tailwind CSS (Vite Plugin).
- **Estado & Rutas:** Vue Router y Reactividad nativa (`ref`, `computed`).
- **HTTP:** Axios con configuración modular e interceptores de Request/Response.

### DevOps

- **Docker:** Contenedores personalizados para PHP 8.2 y Node 20.
- **Docker Compose:** Orquestación de servicios y redes.

---

## 🚀 Guía de Instalación y Ejecución

Tienes dos formas de iniciar el proyecto. Se recomienda la **Opción A (Docker)** para garantizar que el entorno sea idéntico al de desarrollo.

### Opción A: Ejecución con Docker (Recomendada) 🐳

No necesitas instalar PHP, Composer ni Node.js en tu máquina local, solo Docker Desktop.

1.  **Clonar el repositorio:**

    ```bash
    git clone <URL_DE_TU_REPOSITORIO>
    cd task-manager
    ```

2.  **Levantar el entorno:**
    Asegúrate de que Docker Desktop esté corriendo y ejecuta:

    ```bash
    docker-compose up --build
    ```

    _Este comando instalará las dependencias de backend y frontend, ejecutará las migraciones y levantará los servidores._

3.  **Acceder a la aplicación:**

    - **Frontend:** [http://localhost:5173](http://localhost:5173) (o http://127.0.0.1:5173 si tienes problemas en Mac).
    - **Backend API:** [http://localhost:8000](http://localhost:8000).

4.  **Detener la aplicación:**
    Presiona `Ctrl + C` en la terminal.

---

### Opción B: Ejecución Manual (Local) 🔧

Si prefieres correrlo nativamente en tu sistema operativo.

#### 1. Configuración del Backend (Laravel)

Requiere PHP 8.2+ y Composer.

```bash
cd backend

# Instalar dependencias
composer install

# Configurar variables de entorno
cp .env.example .env
# NOTA: Asegúrate de que en .env la base de datos sea: DB_CONNECTION=sqlite

# Crear base de datos física
# (En Mac/Linux)
touch database/database.sqlite
# (En Windows Powershell)
New-Item database/database.sqlite

# Correr migraciones
php artisan migrate

# Generar llave de aplicación
php artisan key:generate

# Iniciar servidor (Puerto 8000)
php artisan serve
```

### Decisiones Técnicas y Solución de Problemas
# ¿Por qué SQLite?
Para esta prueba técnica, se seleccionó SQLite ya que elimina la necesidad de configurar un servidor de base de datos externo (como MySQL o PostgreSQL). Esto permite que el evaluador clone y ejecute el proyecto inmediatamente sin configuraciones complejas de credenciales o puertos.

### Manejo de CORS y Puertos
El proyecto está configurado para permitir peticiones desde cualquier origen (*) en entorno de desarrollo.

Razón: Esto previene bloqueos de CORS si Docker o Vite asignan puertos dinámicos (ej: cambiar de 5173 a 5174 si el puerto está ocupado) o si se accede a la aplicación mediante IP local (127.0.0.1) en lugar de localhost.

### Interceptor de Axios
Se implementó una lógica inteligente en frontend/src/axios.js para detectar respuestas 401 Unauthorized.

Funcionamiento: Si el token expira o la base de datos se reinicia (invalidando los tokens existentes), el usuario es redirigido automáticamente al Login.

Beneficio: Mejora la Experiencia de Usuario (UX) y evita que la aplicación quede en estados inconsistentes o pantallas de carga infinitas.
### 2. Configuración del Frontend (Vue)
Requiere **Node.js 18+** y **NPM**.

```bash
# En una nueva terminal
cd frontend

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev


