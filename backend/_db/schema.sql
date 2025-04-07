-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS terminko;
USE terminko;

-- Tenants table
CREATE TABLE `tenants` (
    `id` VARCHAR(36) PRIMARY KEY COMMENT 'Unique identifier (UUID)',
    `name` VARCHAR(255) NOT NULL COMMENT 'Business name (e.g., ''Alex''s Barbershop'')',
    `slug` VARCHAR(255) UNIQUE COMMENT 'Optional; used for vanity URLs',
    `phone` VARCHAR(50),
    `email` VARCHAR(255),
    `address` VARCHAR(255),
    `city` VARCHAR(100),
    `country` VARCHAR(100),
    `postal_code` VARCHAR(20),
    `operating_hours_json` JSON COMMENT 'JSON to store daily open/close hours, holidays, etc.',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users table
CREATE TABLE `users` (
    `id` VARCHAR(36) PRIMARY KEY COMMENT 'Unique user identifier',
    `tenant_id` VARCHAR(36) COMMENT 'Relates to specific tenant if staff; null if customer',
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(50) COMMENT 'Optional contact number for SMS notifications',
    `role` ENUM('owner', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services table
CREATE TABLE `services` (
    `id` VARCHAR(36) PRIMARY KEY COMMENT 'Unique service identifier',
    `tenant_id` VARCHAR(36) NOT NULL COMMENT 'Links service to specific tenant',
    `name` VARCHAR(255) NOT NULL COMMENT 'e.g., ''Haircut'', ''Car Wash'', ''Nail Polish''',
    `description` TEXT,
    `price` DECIMAL(10,2) NOT NULL COMMENT 'Base cost of the service',
    `duration_minutes` INT NOT NULL COMMENT 'Length of service in minutes (15, 30, 45, etc.)',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Boolean to ''hide'' services without deleting them',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings table
CREATE TABLE `bookings` (
    `id` VARCHAR(36) PRIMARY KEY COMMENT 'Unique booking identifier',
    `tenant_id` VARCHAR(36) NOT NULL,
    `service_id` VARCHAR(36) NOT NULL,
    `customer_id` VARCHAR(36) NOT NULL COMMENT 'References users.id',
    `staff_id` VARCHAR(36) COMMENT 'Optional; specific staff assignment',
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `status` ENUM('pending', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `notes` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT,
    FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
ALTER TABLE `users` ADD INDEX `idx_tenant_role` (`tenant_id`, `role`);
ALTER TABLE `services` ADD INDEX `idx_tenant_active` (`tenant_id`, `is_active`);
ALTER TABLE `bookings` ADD INDEX `idx_tenant_dates` (`tenant_id`, `start_time`, `end_time`);
ALTER TABLE `bookings` ADD INDEX `idx_customer` (`customer_id`);
ALTER TABLE `bookings` ADD INDEX `idx_staff` (`staff_id`);
