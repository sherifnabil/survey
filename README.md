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

- Create, edit, and delete surveys  
- Add multiple question types (text, multiple choice, etc.)  
- Collect and analyze survey responses  
- Pagination for survey lists and results  
- Responsive UI built with React  
- RESTful API with Laravel Sanctum for authentication (if implemented)  

## Tech Stack

### Backend (`survey-service/`)
- **Laravel** 13 (PHP)  
- MySQL / SQLite (configurable)  
- Laravel Sanctum for API authentication  
- API resources and pagination support  

### Frontend (`client-react/`)
- **React.js** 18+  
- Axios for API calls  
- React Router for navigation  
- Tailwind CSS (or other styling – inferred from typical setup)  

## Project Structure
```shell
survey/
├── survey-service/ # Laravel backend API
│ ├── app/
│ ├── routes/
│ ├── database/
│ └── ...
└── client-react/ # React frontend
├── src/
├── public/
└── ...
```

## Prerequisites

- PHP >= 8.1
- Composer
- Node.js >= 16 & npm / yarn
- MySQL (or SQLite for development)

## Setup Instructions

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
