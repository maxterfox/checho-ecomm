-- ============================================================================
-- Urban Sports Ecommerce — MariaDB Schema
-- Engine: MariaDB 10.3+ | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================================

CREATE DATABASE IF NOT EXISTS urban_sports
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE urban_sports;

-- ----------------------------------------------------------------------------
-- 1. ROLES
-- ----------------------------------------------------------------------------
CREATE TABLE roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100)   NOT NULL,
    slug        VARCHAR(100)   NOT NULL UNIQUE,
    description TEXT           NULL DEFAULT NULL,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. USERS
-- ----------------------------------------------------------------------------
CREATE TABLE users (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name              VARCHAR(200)   NOT NULL,
    email             VARCHAR(200)   NOT NULL UNIQUE,
    email_verified_at TIMESTAMP      NULL DEFAULT NULL,
    password          VARCHAR(255)   NOT NULL,
    role_id           INT UNSIGNED   NULL DEFAULT NULL,
    access_granted    TINYINT(1)     NOT NULL DEFAULT 1,
    status            ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    remember_token    VARCHAR(100)   NULL DEFAULT NULL,
    created_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at        TIMESTAMP      NULL DEFAULT NULL,

    INDEX idx_users_role_id (role_id),
    INDEX idx_users_status (status),
    INDEX idx_users_deleted_at (deleted_at),

    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. PERMISSIONS — registry of available system modules
-- ----------------------------------------------------------------------------
CREATE TABLE permissions (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_name   VARCHAR(100)   NOT NULL UNIQUE,
    display_name  VARCHAR(200)   NOT NULL,
    description   TEXT           NULL DEFAULT NULL,
    created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. ROLE_PERMISSIONS — which role can access which module and at what level
-- Levels: view  → read-only access
--         modify → read + write access
-- ----------------------------------------------------------------------------
CREATE TABLE role_permissions (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id       INT UNSIGNED   NOT NULL,
    permission_id INT UNSIGNED   NOT NULL,
    level         ENUM('view', 'modify') NOT NULL DEFAULT 'view',
    created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_role_permission (role_id, permission_id),

    INDEX idx_rp_role_id (role_id),
    INDEX idx_rp_permission_id (permission_id),

    CONSTRAINT fk_rp_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_rp_permission
        FOREIGN KEY (permission_id) REFERENCES permissions(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. ROLE_FIELD_PERMISSIONS — field-level edit control per module per role
-- ----------------------------------------------------------------------------
CREATE TABLE role_field_permissions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id     INT UNSIGNED   NOT NULL,
    module_name VARCHAR(100)   NOT NULL,
    field_name  VARCHAR(200)   NOT NULL,
    can_edit    TINYINT(1)     NOT NULL DEFAULT 0,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uq_role_module_field (role_id, module_name, field_name),

    INDEX idx_rfp_role_id (role_id),
    INDEX idx_rfp_module (module_name),

    CONSTRAINT fk_rfp_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 6. ACTIVITY_LOGS
-- ----------------------------------------------------------------------------
CREATE TABLE activity_logs (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id      INT UNSIGNED   NULL DEFAULT NULL,
    action       VARCHAR(100)   NOT NULL,
    module       VARCHAR(100)   NOT NULL,
    description  TEXT           NULL DEFAULT NULL,
    reference_id INT UNSIGNED   NULL DEFAULT NULL,
    ip_address   VARCHAR(45)    NULL DEFAULT NULL,
    user_agent   TEXT           NULL DEFAULT NULL,
    created_at   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_al_user_id (user_id),
    INDEX idx_al_module (module),
    INDEX idx_al_action (action),
    INDEX idx_al_created_at (created_at),
    INDEX idx_al_reference_id (reference_id),

    CONSTRAINT fk_al_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. CATEGORIES
-- ----------------------------------------------------------------------------
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(200)   NOT NULL,
    slug        VARCHAR(200)   NOT NULL UNIQUE,
    description TEXT           NULL DEFAULT NULL,
    parent_id   INT UNSIGNED   NULL DEFAULT NULL,
    status      ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at  TIMESTAMP      NULL DEFAULT NULL,

    INDEX idx_cat_parent_id (parent_id),
    INDEX idx_cat_status (status),
    INDEX idx_cat_slug (slug),
    INDEX idx_cat_deleted_at (deleted_at),

    CONSTRAINT fk_cat_parent
        FOREIGN KEY (parent_id) REFERENCES categories(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 8. PRODUCTS
-- ----------------------------------------------------------------------------
CREATE TABLE products (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(300)   NOT NULL,
    slug          VARCHAR(300)   NOT NULL UNIQUE,
    description   TEXT           NULL DEFAULT NULL,
    price         DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    discount_price DECIMAL(10, 2) NULL DEFAULT NULL,
    compare_price DECIMAL(10, 2) NULL DEFAULT NULL,
    category_id   INT UNSIGNED   NULL DEFAULT NULL,
    stock         INT            NOT NULL DEFAULT 0,
    sku           VARCHAR(100)   NULL DEFAULT NULL UNIQUE,
    main_image    VARCHAR(500)   NULL DEFAULT NULL,
    status        ENUM('active', 'inactive', 'draft') NOT NULL DEFAULT 'draft',
    created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at    TIMESTAMP      NULL DEFAULT NULL,

    INDEX idx_prod_category_id (category_id),
    INDEX idx_prod_status (status),
    INDEX idx_prod_price (price),
    INDEX idx_prod_slug (slug),
    INDEX idx_prod_deleted_at (deleted_at),

    CONSTRAINT fk_prod_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 9. PRODUCT_IMAGES
-- ----------------------------------------------------------------------------
CREATE TABLE product_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id  INT UNSIGNED   NOT NULL,
    image_path  VARCHAR(500)   NOT NULL,
    is_primary  TINYINT(1)     NOT NULL DEFAULT 0,
    sort_order  INT            NOT NULL DEFAULT 0,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_pi_product_id (product_id),
    INDEX idx_pi_primary (product_id, is_primary),

    CONSTRAINT fk_pi_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 10. CARTS
-- ----------------------------------------------------------------------------
CREATE TABLE carts (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED   NULL DEFAULT NULL,
    session_id  VARCHAR(255)   NULL DEFAULT NULL,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_cart_user_id (user_id),
    INDEX idx_cart_session_id (session_id),

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 11. CART_ITEMS
-- ----------------------------------------------------------------------------
CREATE TABLE cart_items (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cart_id     INT UNSIGNED   NOT NULL,
    product_id  INT UNSIGNED   NOT NULL,
    quantity    INT            NOT NULL DEFAULT 1,
    price       DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_ci_cart_id (cart_id),
    INDEX idx_ci_product_id (product_id),

    CONSTRAINT fk_ci_cart
        FOREIGN KEY (cart_id) REFERENCES carts(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_ci_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 12. ORDERS
-- ----------------------------------------------------------------------------
CREATE TABLE orders (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id           INT UNSIGNED   NULL DEFAULT NULL,
    order_number      VARCHAR(50)    NOT NULL UNIQUE,
    subtotal          DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    tax               DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    shipping_cost     DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    total             DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    status            ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded')
                      NOT NULL DEFAULT 'pending',
    shipping_address  TEXT           NULL DEFAULT NULL,
    billing_address   TEXT           NULL DEFAULT NULL,
    notes             TEXT           NULL DEFAULT NULL,
    created_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at        TIMESTAMP      NULL DEFAULT NULL,

    INDEX idx_ord_user_id (user_id),
    INDEX idx_ord_status (status),
    INDEX idx_ord_order_number (order_number),
    INDEX idx_ord_created_at (created_at),
    INDEX idx_ord_deleted_at (deleted_at),

    CONSTRAINT fk_ord_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 13. ORDER_ITEMS
-- ----------------------------------------------------------------------------
CREATE TABLE order_items (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id     INT UNSIGNED   NOT NULL,
    product_id   INT UNSIGNED   NULL DEFAULT NULL,
    product_name VARCHAR(300)   NOT NULL,
    price        DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    quantity     INT            NOT NULL DEFAULT 1,
    subtotal     DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at   TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_oi_order_id (order_id),
    INDEX idx_oi_product_id (product_id),

    CONSTRAINT fk_oi_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_oi_product
        FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- MIGRATIONS — add columns to existing tables
-- ----------------------------------------------------------------------------
ALTER TABLE products
  ADD COLUMN IF NOT EXISTS discount_price DECIMAL(10,2) NULL DEFAULT NULL AFTER price,
  ADD COLUMN IF NOT EXISTS main_image VARCHAR(500) NULL DEFAULT NULL AFTER sku;
