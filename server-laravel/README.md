# Bluumfall 2025 - Laravel Server

A Laravel-based server application for managing patient care tasks and nurse workflows.

## Overview

This application provides a task management system for healthcare environments, allowing nurses to track and manage patient tasks, schedules, and completions.

## Current Flow
Nurses will have access to the nurses portal, which is currently served as blade files. They will initiate patients from this portal, which will generate a code that will be input onto a tablet with the React Native kid's app installed. That tablet will be linked for that patient on that device until they leave.

Nurses will have the ability to select tasks for each patient, creating a schedule that will then repeat automatically. Once a patient has completed a task, it will be verified by the nurse in the nurses portal.

## Features

- **Patient Management**: Track patients with usernames, avatars, experience points, and gems
- **Avatar System**:
  - Layered avatar system with multiple avatar types (Axolotl, Corgi)
  - Each avatar has multiple compositable layers for rendering
  - Default avatar assignment (Corgi)
- **Inventory System**: Patients can own and equip items purchased with gems
- **Nurse Authentication**: Secure authentication system for nursing staff
- **Task System**:
  - Task templates with XP and gem rewards
  - Task subscriptions with recurring schedules
  - Task completions with status tracking (pending, completed, skipped, failed)
- **Web Interface**: Blade-based UI for home dashboard and patient management
- **API**: RESTful API endpoints for mobile/frontend integration

## Requirements

- PHP 8.2+
- Composer
- Docker (for database)
- Node.js & NPM (for asset compilation)

## Installation

### Database Setup
1. Install Docker
2. Use the .env.example and fill out the 'DB' related values
3. Navigate to server-laravel folder, then run:
```bash
docker compose up -d
```
4. Docker container will take a minute to initialize
5. Seed the db
```bash
php artisan migrate:fresh --seed
```

### Application Setup

1. Install dependencies
```bash
composer install
npm install
```

2. Configure environment
```bash
cp .env.example .env
php artisan key:generate
```

3. Compile assets
```bash
npm run dev
```

4. Start the server
```bash
php artisan serve
```

## Database Schema

### Main Tables

- **patients**: Patient records with username, avatar_id, experience, and gems
- **avatars**: Avatar types (Axolotl, Corgi) with metadata
- **avatar_layers**: Individual layer images for each avatar type
- **items**: Purchasable items with price, image, and category
- **patient_item**: Inventory junction table linking patients to owned items
- **nurses**: Nursing staff with authentication credentials
- **tasks**: Task templates with name, description, and reward values
- **task_subscriptions**: Links patients to tasks with scheduling info (start_at, interval_days, timezone)
- **task_completions**: Individual task instances with scheduled_for, completed_at, and status

### Key Relationships

- Patient → Avatar (many-to-one)
- Avatar → AvatarLayers (one-to-many)
- Patient → PatientItems (one-to-many via patient_item pivot)
- PatientItem → Item (many-to-one)
- Patient → TaskSubscriptions (one-to-many)
- TaskSubscription → Task (many-to-one)
- TaskSubscription → TaskCompletions (one-to-many)

## API Routes

See `openapi.yaml` for the complete OpenAPI 3.1 specification of the patient-facing API.

You can view the API documentation using tools like:
- [Swagger Editor](https://editor.swagger.io/) - Paste the YAML content
- [Stoplight Studio](https://stoplight.io/studio/)
- VS Code extensions: "OpenAPI (Swagger) Editor" or "Swagger Viewer"

Key patient-facing endpoints:
- `POST /api/login` - Patient authentication via pairing code
- `GET /api/me` - Get authenticated patient info (includes avatar with layers)
- `GET /api/patient/profile` - Get patient profile with stats
- `PATCH /api/patient/profile` - Update patient profile
- `GET /api/patient/tasks/current` - Get today's tasks
- `PATCH /api/patient/tasks/completions/{id}` - Update task completion
- `GET /api/patient/inventory` - Get patient's inventory items
- `GET /api/items` - Get shop items available for purchase

## Web Routes

- `/` - Login page
- `/home` - Nurse dashboard showing recent patients and inbox
- `/patients` - Patient list with task details

## Development

### Running Tests
```bash
php artisan test
```

### Code Structure

- `app/Models/` - Eloquent models
- `app/Http/Controllers/` - Request handlers
- `app/Http/Resources/` - API response transformers
- `app/Enums/` - Enumerations (e.g., TaskStatus)
- `database/migrations/` - Database schema definitions
- `database/seeders/` - Database seeders
- `resources/views/` - Blade templates

## Authentication

The application uses Laravel's built-in authentication with separate guard configurations for:
- **Nurses**: Web-based authentication for staff
- **Patients**: API authentication for patient portal/mobile app

## Task System

The task system uses a subscription-based model:

1. **Tasks** are templates that define what needs to be done
2. **TaskSubscriptions** link a patient to a task with scheduling rules (start date, interval)
3. **TaskCompletions** are individual instances of tasks scheduled for specific times

Each completion tracks:
- `scheduled_for` - When the task should be completed
- `completed_at` - When it was actually completed
- `status` - Current state (pending, completed, skipped, failed)
