# Task Management System

A Laravel application for managing projects and tasks with separate Admin and Employee access. Admins manage projects, assign tasks, and review task statistics. Employees see and update the status of their assigned tasks.

## Requirements

- PHP 8.3 or newer with the required Laravel extensions
- Composer
- Node.js and npm
- MySQL (for example, the MySQL service included with WampServer)

## Local setup

Run these commands from the project directory:

```powershell
composer install
npm install
```

Create a local environment file if one does not already exist:

```powershell
if (-not (Test-Path .env)) { Copy-Item .env.example .env }
```

Create a MySQL database. You can create it in phpMyAdmin or run:

```sql
CREATE DATABASE task_mngt_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Set the database values in `.env` to match your MySQL installation:

```dotenv
APP_NAME="Task Management System"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_mngt_system
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

Change the host, port, username, or password if your local MySQL configuration differs. `SESSION_DRIVER=file` keeps web login sessions in local files and does not require a sessions table.

Generate the app key, clear cached configuration, then create the tables and sample records:

```powershell
php artisan key:generate
php artisan optimize:clear
php artisan migrate --seed
```

The seeders create an Admin, seven Employees, six projects, and six tasks. Rerunning `php artisan db:seed` is safe for project and task records; seeded user accounts are updated to the sample password each time.

## Run the application

Start Laravel in one terminal:

```powershell
php artisan serve
```

Start Vite in a second terminal so frontend assets are built and refreshed during development:

```powershell
npm run dev
```

Open `http://127.0.0.1:8000`. For a production asset build, use `npm run build`.

## Sample accounts

All seeded accounts use the password `password`.

| Role | Email |
| --- | --- |
| Admin | `admin@example.com` |
| Employee | `employee@example.com` |
| Employee | `jane@example.com` |
| Employee | `alex@example.com` |
| Employee | `sam@example.com` |
| Employee | `riley@example.com` |
| Employee | `casey@example.com` |
| Employee | `morgan@example.com` |

These credentials are for local development only. Change them before using the application in any shared or production environment.

## Main features

- Admin dashboard with project and task statistics
- Project creation, editing, searching, and filtering
- Task creation and assignment with priority, status, and due date
- Task search and filters for status and priority
- Employee task lists scoped to the signed-in employee
- AJAX status updates on employee task pages
- Laravel session authentication and role-based access control

## Task API

The API uses Laravel Sanctum bearer tokens. First request a token:

```http
POST /api/login
Content-Type: application/json
Accept: application/json

{
  "email": "employee@example.com",
  "password": "password"
}
```

Use the returned token as `Authorization: Bearer <token>` for authenticated API requests.

| Method | Endpoint | Access |
| --- | --- | --- |
| `POST` | `/api/login` | Public; returns a token |
| `POST` | `/api/logout` | Authenticated |
| `GET` | `/api/tasks` | Admin sees all tasks; employees see their assigned tasks |
| `GET` | `/api/tasks/{id}` | Admin or the assigned employee |
| `POST` | `/api/tasks` | Admin |
| `PUT/PATCH` | `/api/tasks/{id}` | Admin; employees may update status on their own tasks |
| `DELETE` | `/api/tasks/{id}` | Admin |

Task API requests and responses use JSON. Invalid input returns validation errors, and unauthorized access returns an HTTP error response.

## Useful commands

```powershell
php artisan route:list
php artisan migrate:status
php artisan db:seed
npm run build
```
