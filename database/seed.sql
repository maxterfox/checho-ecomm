-- ============================================================================
-- Urban Sports Ecommerce — Seed Data
-- ============================================================================

USE urban_sports;

-- ----------------------------------------------------------------------------
-- PERMISSIONS — available system modules
-- ----------------------------------------------------------------------------
INSERT INTO permissions (module_name, display_name, description) VALUES
    ('users',         'Users',         'Manage user accounts'),
    ('products',      'Products',      'Manage product catalog'),
    ('categories',    'Categories',    'Manage product categories'),
    ('orders',        'Orders',        'Manage customer orders'),
    ('roles',         'Roles',         'Manage roles and permissions'),
    ('activity_logs', 'Activity Logs', 'View system activity logs'),
    ('settings',      'Settings',      'Manage system settings');

-- ----------------------------------------------------------------------------
-- ROLES
-- ----------------------------------------------------------------------------
INSERT INTO roles (name, slug, description) VALUES
    ('Admin',    'admin',    'Full access to all modules with modify permissions'),
    ('Staff',    'staff',    'Limited access — can view products, categories, orders, and logs'),
    ('Customer', 'customer', 'Storefront access only — no admin panel access');

-- ----------------------------------------------------------------------------
-- ROLE PERMISSIONS
-- Admin — full modify on all modules
-- ----------------------------------------------------------------------------
INSERT INTO role_permissions (role_id, permission_id, level)
SELECT
    (SELECT id FROM roles WHERE slug = 'admin'),
    id,
    'modify'
FROM permissions;

-- ----------------------------------------------------------------------------
-- ROLE PERMISSIONS
-- Staff — view-only on products, categories, orders, and activity logs
-- ----------------------------------------------------------------------------
INSERT INTO role_permissions (role_id, permission_id, level)
SELECT
    (SELECT id FROM roles WHERE slug = 'staff'),
    id,
    'view'
FROM permissions
WHERE module_name IN ('products', 'categories', 'orders', 'roles', 'activity_logs');

-- ----------------------------------------------------------------------------
-- Customer — no admin panel permissions (storefront only)
-- (no role_permissions entries needed)

-- ----------------------------------------------------------------------------
-- DEFAULT ADMIN USER
-- Password:  "password"
-- Hash:      bcrypt cost 10
-- Role:      Admin
-- Access:    Granted
-- ----------------------------------------------------------------------------
INSERT INTO users (name, email, password, role_id, access_granted, status)
SELECT
    'Admin',
    'admin@urbansports.test',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    id,
    1,
    'active'
FROM roles
WHERE slug = 'admin';

-- ----------------------------------------------------------------------------
-- ROLE FIELD PERMISSIONS — restrict specific fields per role
-- When no entry exists, all fields are editable (if role has 'modify' level).
-- Only add rows for fields that should NOT be editable.
-- ----------------------------------------------------------------------------
INSERT INTO role_field_permissions (role_id, module_name, field_name, can_edit)
SELECT r.id, 'products', 'slug', 0 FROM roles r WHERE r.slug = 'staff';
INSERT INTO role_field_permissions (role_id, module_name, field_name, can_edit)
SELECT r.id, 'products', 'status', 0 FROM roles r WHERE r.slug = 'staff';
INSERT INTO role_field_permissions (role_id, module_name, field_name, can_edit)
SELECT r.id, 'categories', 'slug', 0 FROM roles r WHERE r.slug = 'staff';
INSERT INTO role_field_permissions (role_id, module_name, field_name, can_edit)
SELECT r.id, 'users', 'role_id', 0 FROM roles r WHERE r.slug = 'staff';
INSERT INTO role_field_permissions (role_id, module_name, field_name, can_edit)
SELECT r.id, 'users', 'access_granted', 0 FROM roles r WHERE r.slug = 'staff';
