# E-Commerce Catalog & Admin Manager

A modern, highly optimized Laravel application featuring a public storefront catalog and a secure admin panel for managing categories, subcategories, and products. The project enforces best practices in design (Tailwind CSS v4), input validation (Livewire Form Objects), security (Secure Headers Middleware), performance (N+1 query prevention), and Test-Driven Development (Pest PHP).

---

## Visual Previews

### Storefront
![Storefront Preview](fi.png)

### Admin Panel
![Admin Panel Preview](ai.png)

---

## Features

- **Public Storefront**: A responsive product catalog displaying all active items with filtering by category and subcategory.
- **Admin Dashboard**: A secure back-office CRUD management dashboard for Categories, Subcategories, and Products.
- **Livewire Form Objects**: All form validations are completely decoupled from controllers/components and encapsulated inside Livewire Form Objects (`CategoryForm`, `SubcategoryForm`, and `ProductForm`).
- **N+1 Query Prevention**: Eager-loads all model relationships on listing views and counts (e.g. eager-loading parent categories and product counters).
- **Slug Generation**: Dynamic, unique, conflict-free slugs automatically generated and updated via a reusable `HasUniqueSlug` trait.
- **Secure Headers Middleware**: Custom HTTP security headers (`X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`) applied automatically to all responses.
- **Full Test Suite**: High test coverage using Pest PHP testing CRUD actions, authentication middleware, validation constraints, and file uploads.

---

## Technology Stack

- **Framework**: Laravel 13 & PHP 8.5
- **Frontend / Reactivity**: Livewire 4 & Alpine.js
- **Styling**: Tailwind CSS v4 (Sleek UI with dark-mode sidebar layouts, glassmorphism, and smooth hover micro-animations)
- **Database**: SQLite (using UUIDs for primary keys)
- **Testing**: Pest PHP 4
- **Formatter**: Laravel Pint

---

## Getting Started

### 1. Installation

Clone the repository, install Composer dependencies, and set up your environment:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Database Setup & Seeding

Run database migrations and seed the database with categories, subcategories, products, and a default admin user:

```bash
php artisan migrate:fresh --seed
```

### 3. Default Admin Credentials

For easy access and testing, the login screen is pre-filled with the default credentials:

* **Email**: `admin@admin.com`
* **Password**: `password`

### 4. Running the Project

Run the Vite development server to bundle assets:

```bash
npm install
npm run dev
```

The application is served by Laravel Herd (or your local PHP web server) at:
* **Storefront**: `http://bpc-assesment.test`
* **Admin Categories**: `http://bpc-assesment.test/admin/categories`

---

## Testing & Quality Assurance

### Running Tests

We use Pest PHP to verify backend controllers, middleware, and components. Run the test suite:

```bash
php artisan test --compact
```

### Code Formatting

Check and enforce coding standards using Laravel Pint:

```bash
vendor/bin/pint --format agent
```
