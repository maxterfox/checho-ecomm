# Urban Sports Ecommerce

A modern, urban-themed sportswear ecommerce platform built with **PHP 8.1**, vanilla **MVC architecture**, and **MySQL**.

> Built without frameworks — clean, lightweight, and fully custom.

---

## Purpose

Urban Sports is a full-featured online store for selling clothes and shoes, designed with an urban/sports aesthetic. It includes a public storefront, product catalog, shopping cart, user authentication, and a complete admin panel with role-based access control and activity logging.

### Target audience

- Small to medium sportswear businesses
- Developers learning or extending vanilla PHP MVC
- Projects requiring a lightweight, customizable ecommerce backend

---

## Folder Structure

```
ecommerce/
├── public/                    # Web root (document root)
│   ├── index.php             # Front controller — all requests enter here
│   ├── .htaccess             # URL rewriting (Apache)
│   └── assets/               # CSS, JS, images, fonts
├── config/
│   ├── app.php               # Application constants (name, URL, timezone)
│   ├── database.php          # MySQL connection credentials
│   └── permissions.php       # Module & permission definitions
├── routes/
│   └── web.php               # All route definitions
├── app/
│   ├── Core/                 # MVC framework core
│   │   ├── Router.php       # URI matching with middleware support
│   │   ├── Controller.php   # Base controller (view rendering, redirects)
│   │   ├── Model.php        # Base model (CRUD, pagination)
│   │   ├── View.php         # Template rendering with layouts
│   │   ├── Database.php     # PDO wrapper (singleton)
│   │   ├── Session.php      # Session + flash messages
│   │   ├── Auth.php         # Authentication & permission checks
│   │   └── Request.php      # Input handling & CSRF protection
│   ├── Controllers/          # Application controllers
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── CartController.php
│   │   └── Admin/            # Admin panel controllers
│   ├── Models/               # Active record-style models
│   ├── Views/                # PHP template files
│   │   ├── layouts/          # main.php (public), admin.php (panel)
│   │   ├── partials/         # header, footer, sidebar, alerts
│   │   ├── auth/             # login, register
│   │   ├── products/         # catalog, product detail
│   │   ├── cart/
│   │   ├── checkout/
│   │   └── admin/            # dashboard, CRUD screens
│   ├── Middleware/            # Auth, Admin, Permission middleware
│   ├── Helpers/               # Global helper functions, validation
│   └── Traits/                # ActivityLogger trait
├── migrations/
│   └── 001_create_tables.sql # Database schema
└── storage/logs/              # Application logs
```

### Key architectural decisions

| Decision | Rationale |
|---|---|
| **No framework** | Full control, zero bloat, educational value |
| **MVC** | Clean separation of concerns |
| **PDO** | Secure parameterized queries, MySQL abstraction |
| **JSON permissions** | Flexible role config stored in a single column |
| **Session cart** | Lightweight, no DB writes until checkout |

---

## Permission System

Three-layer access control:

1. **Access** — whether a user can log into the system at all
2. **Modules** — which modules a role can access (products, users, roles, orders, etc.)
3. **View / Modify** — within each module, can the user only view data or also modify it?

Permissions are stored as JSON in the `roles` table, making them easy to configure from the admin panel without schema changes.

---

## Local Installation

### Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache with `mod_rewrite` (or equivalent Nginx config)
- Composer (for autoloading)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/maxterfox/checho-ecomm.git
cd checho-ecomm

# 2. Install Composer dependencies (generates autoloader)
composer install

# 3. Configure the database connection
#    Edit config/database.php with your MySQL credentials

# 4. Create the database and import the schema
#    Open your MySQL client and run:
```

### Database Setup

Create a new MySQL database and import the migration file:

```sql
CREATE DATABASE IF NOT EXISTS urban_sports
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE urban_sports;

SOURCE migrations/001_create_tables.sql;
```

Or import using the command line:

```bash
mysql -u root -p < migrations/001_create_tables.sql
```

After importing, a default **Superadmin** role is created. You will need to register a user via the app, then manually promote them in the database:

```sql
UPDATE users SET role_id = 1 WHERE email = 'your@email.com';
```

### Running the development server

```bash
php -S localhost:8000 -t public
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

### Apache (production)

Point your document root to the `public/` directory. The `.htaccess` file handles URL rewriting.

---

## Tech Stack

- **Backend:** PHP 8.1 (vanilla, no frameworks)
- **Database:** MySQL + PDO
- **Frontend:** HTML5, CSS3, vanilla JavaScript
- **Server:** Apache / PHP built-in server
- **Auth:** Session-based with role-permission matrix

---

## License

MIT
