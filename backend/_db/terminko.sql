-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS terminko;
USE terminko;



# create table if not exists in mysql

-- Tenants table
CREATE TABLE IF NOT EXISTS tenants
(
    id                   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique identifier for tenant (business)',
    name                 VARCHAR(255) NOT NULL COMMENT 'Business name (e.g., "Alex\'s Barbershop")',
    slug                 VARCHAR(255) UNIQUE COMMENT 'Optional; used for vanity URLs',
    phone                VARCHAR(50),
    email                VARCHAR(255),
    address              VARCHAR(255),
    city                 VARCHAR(100),
    country              VARCHAR(100),
    postal_code          VARCHAR(20),
    operating_hours_json JSON COMMENT 'JSON: daily open/close hours, holidays, etc.',
    created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- Users table
CREATE TABLE IF NOT EXISTS users
(
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique user identifier',
    tenant_id     BIGINT UNSIGNED COMMENT 'Relates to specific tenant if staff; null if customer',
    name          VARCHAR(255)                        NOT NULL,
    email         VARCHAR(255)                        NOT NULL UNIQUE,
    password_hash VARCHAR(255)                        NOT NULL,
    phone         VARCHAR(50),
    role          ENUM ('owner', 'staff', 'customer') NOT NULL DEFAULT 'customer' COMMENT 'User role in the system',
    created_at    TIMESTAMP                                    DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP                                    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants (id) ON DELETE SET NULL,
    INDEX idx_users_tenant_id (tenant_id)
) ENGINE = InnoDB;

-- Services table
CREATE TABLE IF NOT EXISTS services
(
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique service identifier',
    tenant_id        BIGINT UNSIGNED NOT NULL,
    name             VARCHAR(255)    NOT NULL COMMENT 'Service name (e.g., Haircut)',
    description      TEXT,
    price            DECIMAL(10, 2)  NOT NULL COMMENT 'Base cost of the service',
    duration_minutes INT             NOT NULL COMMENT 'Length of service in minutes',
    is_active        BOOLEAN         NOT NULL DEFAULT TRUE COMMENT 'Used to hide services without deleting',
    created_at       TIMESTAMP                DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP                DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants (id) ON DELETE CASCADE,
    INDEX idx_services_tenant_id (tenant_id)
) ENGINE = InnoDB;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings
(
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique booking identifier',
    tenant_id   BIGINT UNSIGNED                                        NOT NULL,
    user_id     BIGINT UNSIGNED                                        NOT NULL COMMENT 'Customer who made the booking',
    status      ENUM ('pending', 'confirmed', 'canceled', 'completed') NOT NULL DEFAULT 'pending',
    start_time  DATETIME                                               NOT NULL,
    end_time    DATETIME                                               NOT NULL,
    total_price DECIMAL(10, 2)                                         NOT NULL COMMENT 'Total price of the booking',
    created_at  TIMESTAMP                                                       DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP                                                       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (tenant_id) REFERENCES tenants (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    INDEX idx_bookings_tenant_id (tenant_id),
    INDEX idx_bookings_user_id (user_id)
) ENGINE = InnoDB;

-- Booking Services (pivot)
CREATE TABLE IF NOT EXISTS booking_services
(
    id               BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Link between booking and services',
    booking_id       BIGINT UNSIGNED NOT NULL,
    service_id       BIGINT UNSIGNED NOT NULL,
    service_price    DECIMAL(10, 2)  NOT NULL COMMENT 'Snapshot of price at booking time',
    service_duration INT             NOT NULL COMMENT 'Snapshot of duration at booking time',
    FOREIGN KEY (booking_id) REFERENCES bookings (id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE,
    INDEX idx_booking_services_booking_id (booking_id),
    INDEX idx_booking_services_service_id (service_id)
) ENGINE = InnoDB;

-- Notifications
CREATE TABLE IF NOT EXISTS notifications
(
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique notification record',
    user_id    BIGINT UNSIGNED,
    tenant_id  BIGINT UNSIGNED,
    channel    ENUM ('email', 'sms', 'push') NOT NULL COMMENT 'Notification channel',
    subject    VARCHAR(255),
    message    TEXT COMMENT 'Notification content (could be JSON)',
    is_sent    BOOLEAN   DEFAULT FALSE,
    sent_at    TIMESTAMP                     NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    FOREIGN KEY (tenant_id) REFERENCES tenants (id) ON DELETE SET NULL,
    INDEX idx_notifications_user_id (user_id),
    INDEX idx_notifications_tenant_id (tenant_id)
) ENGINE = InnoDB;

-- Ratings
CREATE TABLE IF NOT EXISTS ratings
(
    id                BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Unique rating entry',
    rater_user_id     BIGINT UNSIGNED NOT NULL COMMENT 'User giving the rating',
    rated_user_id     BIGINT UNSIGNED NOT NULL COMMENT 'User being rated',
    booking_id        BIGINT UNSIGNED NOT NULL,
    rating_value      TINYINT         NOT NULL CHECK (rating_value BETWEEN 1 AND 5),
    rating_comment    TEXT,
    attendance_status ENUM ('show', 'no_show', 'late') DEFAULT 'show',
    created_at        TIMESTAMP                        DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP                        DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (rater_user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (rated_user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (booking_id) REFERENCES bookings (id) ON DELETE CASCADE,
    INDEX idx_ratings_rater_user_id (rater_user_id),
    INDEX idx_ratings_rated_user_id (rated_user_id),
    INDEX idx_ratings_booking_id (booking_id)
) ENGINE = InnoDB;
