# Survey App

A full‑stack survey application built with **Laravel** (backend service) and **React.js** (frontend client).  
The backend provides a RESTful API, while the React frontend consumes it to manage surveys, questions, and responses.
Backend is A modular, scalable Survey Management API built with Laravel using clean architecture patterns (Actions, DTOs, Resources).

---

##  Overview

This project provides a RESTful API for managing surveys with a nested structure:

Survey → Sections → Questions → Options

It is designed with separation of concerns and extensibility in mind.

##  Architecture

### 🔹 Actions
Encapsulate business logic.
- Example: `CreateAction`
- Located in: `app/Actions`

### 🔹 DTOs (Data Transfer Objects)
Transport structured data from requests to actions.
- Located in: `app/DTOs`

### 🔹 Requests
Handle validation logic.
- Located in: `app/Http/Requests`

### 🔹 Resources
Transform models into API responses.
- Located in: `app/Http/Resources`

### 🔹 Helpers
Standardize responses (e.g. `ResponseFormatter`)
- Located in: `app/Helpers`

### 🔹 Models
Eloquent models with relationships:
- Survey → hasMany Sections
- Section → hasMany Questions
- Survey → hasMany Options


---
## Features
- Create and manage surveys
- Nested structure:
  - Survey → Sections → Questions → Options
- Dynamic question ordering
- Custom API response formatting
- Cursor-based / Offset pagination support
- Clean architecture using:
  - Actions
  - DTOs
  - Resources
  - Responsive UI built with React  

## Tech Stack

### Backend (`survey-service/`)
- **Laravel** 13 (PHP)  
- Postgres  
- Laravel Sanctum for API authentication  
- API resources and pagination support
- PHP (Laravel)
- MySQL
- RESTful API
- Laravel Resources
- DTO Pattern
- Action Pattern

### Frontend (`client-react/`)
- **React.js** 18+  
- Axios for API calls  
- React Router for navigation  
- Tailwind CSS (or other styling – inferred from typical setup)  

### 1. Clone the repository
```bash
git clone https://github.com/sherifnabil/survey.git
cd survey

# 2. Backend Setup (survey-service)
cd survey-service
cp .env.example .env
composer install
php artisan key:generate

Configure your database in .env:

DB_CONNECTION=pgsql // recommended  postgres
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
