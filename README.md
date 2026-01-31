# **Gestor de tareas (Backend)** 🏗️🚀

---

🔹 **API REST** en **Laravel 12** + **PHP 8.2**  para servicios de **autenticación JWT** y **gestión de tareas** (CRUD). 

La aplicación permite:

- **Registro e inicio de sesión** con correo y contraseña (JWT).
- **Listado, creación, edición y eliminación** de tareas por usuario.
- **Estados de tarea:** Por hacer (`todo`), En progreso (`progress`), Completado (`done`).
- **Refresh y logout** de token para mantener sesiones seguras.

---

## **🛠️ Tecnologías Utilizadas**

Estas son las tecnologías principales con las que se construyó este proyecto:

| Tecnología | Uso |
|------------|-----|
| **PHP** (v8.2+) | Lenguaje del backend |
| **Laravel** (v12) | Framework web y API REST |
| **Laravel Sanctum** | Autenticación API (complementario) |
| **JWT** (`php-open-source-saver/jwt-auth`) | Tokens para autenticación |
| **Eloquent** | ORM y modelos (User, Task) |
| **Vite** | Build de assets (CSS/JS) |
| **Composer** | Gestión de dependencias PHP |

Opcional para base de datos:

- **SQLite** (por defecto en desarrollo).
- **PostgreSQL** (vía Docker con `compose.yaml`).

---

## **⚙️ Requisitos Previos**

Asegúrate de tener instalado lo siguiente antes de empezar:

- **PHP** 8.2 o superior 📦
- **Composer** (gestión de paquetes PHP) 🌐
- **Node.js** (v18+ recomendado, para Vite) 🟢
- **npm** (incluido con Node) 📦
- **Docker** (opcional, para PostgreSQL) 🐳

---

## **⚡ Configuración Rápida**

Sigue estos pasos para levantar el proyecto en tu entorno local:

1. **Clona el repositorio:**

   ```bash
   git clone <URL_DE_TU_REPOSITORIO>
   cd nextline-backend
   ```

2. **Configura las variables de entorno:**

   - Copia el archivo de ejemplo y genera la clave de aplicación:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   - Edita `.env` y configura al menos:

   ```env
   # Base de datos (por defecto SQLite)
   DB_CONNECTION=sqlite
   # Para PostgreSQL con Docker, descomenta y usa:
   # DB_CONNECTION=pgsql
   # DB_HOST=127.0.0.1
   # DB_PORT=5433
   # DB_DATABASE=laravel
   # DB_USERNAME=postgres
   # DB_PASSWORD=secret

   # JWT (generar con: php artisan jwt:secret)
   JWT_SECRET=tu_clave_secreta_jwt
   ```

   - Genera el secreto JWT:

   ```bash
   php artisan jwt:secret
   ```

3. **Instala las dependencias:**

   ```bash
   composer install
   npm install
   ```

4. **Base de datos (opcional con Docker):**

   - **SQLite:** crea el archivo de base de datos (si no existe):

   ```bash
   touch database/database.sqlite
   ```

   - **PostgreSQL con Docker:**

   ```bash
   docker compose up -d
   ```

   Ajusta en `.env`: `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, etc., según tu `compose.yaml`.

5. **Ejecuta las migraciones:**

   ```bash
   php artisan migrate
   ```

6. **Inicia el proyecto:**

   ```bash
   php artisan serve
   ```

   La API quedará disponible en `http://localhost:8000`. Las rutas de la API están bajo el prefijo `/api`.

   Para desarrollo con colas, logs y Vite en paralelo:

   ```bash
   composer run dev
   ```

---

## **🔐 Variables de Entorno y JWT**

El backend utiliza **JWT** para autenticación. En `.env` debes definir:

| Variable | Descripción |
|----------|-------------|
| `JWT_SECRET` | Clave para firmar tokens (generar con `php artisan jwt:secret`) |
| `JWT_TTL` | Tiempo de vida del token en minutos (por defecto 60) |
| `JWT_REFRESH_TTL` | Ventana de refresh en minutos (por defecto 20160 ≈ 2 semanas) |

Las respuestas de login y refresh devuelven:

- `access_token`: token JWT.
- `token_type`: `bearer`.
- `expires_in`: segundos hasta que expira el token.

Para las rutas protegidas, envía en la cabecera:

```http
Authorization: Bearer <access_token>
```

---

## **📡 Endpoints de la API**

Base URL: `http://localhost:8000/api` (o la que definas en `APP_URL`).

Las respuestas exitosas siguen esta estructura:

```json
{
  "success": true,
  "message": "Mensaje descriptivo",
  "data": { ... }
}
```

En errores (p. ej. 401, 422) se devuelve un JSON con `message` y, si aplica, detalles de validación.

---

### Autenticación 🔑

- **`POST /api/register`** — Registro de nuevos usuarios.

  **Body:**

  ```json
  {
    "name": "Usuario Nuevo",
    "email": "user@email.com",
    "password": "mi-password-seguro",
    "password_confirmation": "mi-password-seguro"
  }
  ```

  **Respuesta (201):**

  ```json
  {
    "success": true,
    "message": "User registered successfully",
    "data": {
      "id": 1,
      "name": "Usuario Nuevo",
      "email": "user@email.com"
    }
  }
  ```

- **`POST /api/login`** — Inicio de sesión (devuelve JWT).

  **Body:**

  ```json
  {
    "email": "user@email.com",
    "password": "mi-password-seguro"
  }
  ```

  **Respuesta (200):**

  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
      "token_type": "bearer",
      "expires_in": 3600
    }
  }
  ```

Rutas que requieren **Authorization: Bearer &lt;token&gt;**:

- **`POST /api/logout`** — Cerrar sesión (invalida el token).
- **`POST /api/refresh`** — Refrescar el token (devuelve nuevo `access_token`, `token_type`, `expires_in`).
- **`POST /api/profile`** — Perfil del usuario (si está implementado en el controlador).

---

### Gestión de Tareas (TODOs) ✅

Todas las rutas de tareas requieren autenticación (`Authorization: Bearer <token>`).

- **`GET /api/tasks`** — Lista las tareas del usuario autenticado.

  **Respuesta (200):**

  ```json
  {
    "success": true,
    "message": "Tasks retrieved successfully",
    "data": [
      {
        "id": 1,
        "title": "Curso de Laravel",
        "description": "Completar el curso de Laravel 12",
        "status": "progress",
        "due_date": "2026-02-15T00:00:00.000000Z",
        "comments": null,
        "tags": ["laravel", "php"],
        "created_at": "2026-01-31T12:00:00.000000Z"
      }
    ]
  }
  ```

- **`POST /api/tasks`** — Crear una tarea.

  **Body:**

  ```json
  {
    "title": "Nueva tarea",
    "description": "Descripción de la tarea",
    "status": "todo",
    "due_date": "2026-02-20",
    "comments": "Comentario opcional",
    "tags": ["etiqueta1", "etiqueta2"]
  }
  ```

  `status` opcional: `todo` | `progress` | `done`. `comments` y `tags` son opcionales.

  **Respuesta (201):** mismo formato de objeto tarea dentro de `data`.

- **`GET /api/tasks/{id}`** — Obtener una tarea por ID (del usuario autenticado).
- **`PUT /api/tasks/{id}`** — Actualizar una tarea (mismos campos que en POST).
- **`DELETE /api/tasks/{id}`** — Eliminar una tarea.

  **Respuesta (200) típica para delete:**

  ```json
  {
    "success": true,
    "message": "Task deleted successfully"
  }
  ```

---

## **✅ Scripts Disponibles**

En la raíz del proyecto puedes ejecutar:

| Comando | Descripción |
|---------|-------------|
| `composer run setup` | Instala dependencias, copia `.env`, genera key, migra y build de assets |
| `composer run dev` | Servidor PHP + queue + logs (Pail) + Vite en paralelo |
| `php artisan serve` | Inicia solo el servidor HTTP |
| `php artisan migrate` | Ejecuta migraciones |
| `php artisan jwt:secret` | Genera `JWT_SECRET` en `.env` |
| `npm run dev` | Servidor de desarrollo Vite |
| `npm run build` | Build de assets para producción |

---

## **💡 Decisiones Técnicas**

### 1. Autenticación (JWT + AuthService)

- Se usa **php-open-source-saver/jwt-auth** para emitir y validar tokens.
- **AuthService** centraliza: registro, login, logout y refresh. El controlador delega en el servicio y devuelve respuestas estándar (`SuccessfulResponse` / `UnauthorizedResponse`).
- El token se envía en cabecera `Authorization: Bearer <token>`; las rutas protegidas usan el guard `auth:api` (JWT).

### 2. Respuestas API (ApiResponse)

- Todas las respuestas JSON siguen un formato común: `success`, `message` y opcionalmente `data` (clase `ApiResponse` y subclases como `SuccessfulResponse`, `UnauthorizedResponse`, etc.).
- Facilita un contrato uniforme para el frontend o clientes móviles.

### 3. Capa de aplicación (DTOs, Requests, Resources)

- **DTOs** (`RegisterUserDTO`, `LoginDTO`, `TaskDTO`): transforman la entrada en objetos de dominio.
- **Form Requests** (`CreateUserRequest`, `LoginUserRequest`, `CreateTaskRequest`, `UpdateTaskRequest`): validación y autorización en la capa HTTP.
- **Resources** (`UserResource`, `TaskResource`): formatean modelos a JSON (fechas ISO 8601, inclusión condicional de `user` en tareas).

### 4. Servicios (AuthService, TaskService)

- La lógica de negocio está en servicios; los controladores son delgados y orquestan request → DTO/Service → Response.
- **TaskService** gestiona el CRUD de tareas asociadas al usuario autenticado (scope por `user_id`).

### 5. Base de datos

- **PostgreSQL** disponible vía `compose.yaml` para entornos más cercanos a producción.
- Migraciones para `users`, `tasks` (con estados `todo` | `progress` | `done`), cache, jobs y personal access tokens (Sanctum).

---

## **📂 Estructura del Proyecto**

```text
nextline-backend/
├── app/
│   ├── DTOs/
│   │   ├── LoginDTO.php
│   │   ├── RegisterUserDTO.php
│   │   └── TaskDTO.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php    # register, login, logout, refresh
│   │   │   ├── Controller.php
│   │   │   └── TaskController.php    # CRUD tareas
│   │   ├── Requests/
│   │   │   ├── Task/                 # CreateTaskRequest, UpdateTaskRequest
│   │   │   └── User/                 # CreateUserRequest, LoginUserRequest
│   │   ├── Resources/
│   │   │   ├── TaskResource.php
│   │   │   └── UserResource.php
│   │   └── Responses/
│   │       ├── ApiResponse.php
│   │       ├── SuccessfulResponse.php
│   │       ├── UnauthorizedResponse.php
│   │       └── ...
│   ├── Models/
│   │   ├── Task.php
│   │   └── User.php
│   ├── Services/
│   │   ├── Auth/AuthService.php
│   │   └── Task/TaskService.php
│   └── Providers/
├── config/
│   ├── auth.php
│   ├── jwt.php
│   └── ...
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php                       # Rutas /api/*
│   ├── web.php
│   └── console.php
├── tests/
├── .env.example
├── compose.yaml                      # PostgreSQL (Docker)
├── composer.json
├── package.json
└── README.md
```

---

## **📋 Resumen para Integración con Frontend**

- **URL base API:** `APP_URL` + `/api` (ej: `http://localhost:8000/api`).
- **Login:** `POST /api/login` con `email` y `password`; guardar `data.access_token`.
- **Cabecera en rutas protegidas:** `Authorization: Bearer <access_token>`.
- **Tareas:** `GET /api/tasks`, `POST /api/tasks`, `PUT /api/tasks/{id}`, `DELETE /api/tasks/{id}`.
- **Estados de tarea:** `todo`, `progress`, `done`.
- **Refresh de sesión:** `POST /api/refresh` con el token actual para obtener un nuevo token.

---
