<?php

declare(strict_types=1);

// Cada sentencia DDL puede confirmar implícitamente: el runner registra progreso.
return [
    <<<'SQL'
CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
    name VARCHAR(80) NOT NULL
) ENGINE=InnoDB
SQL,
    "INSERT INTO roles (code, name) VALUES ('ADMINISTRADOR', 'Administrador'), ('USUARIO', 'Usuario')",
    <<<'SQL'
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(254) COLLATE utf8mb4_0900_as_ci NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT,
    CONSTRAINT ck_users_active CHECK (is_active IN (0, 1))
) ENGINE=InnoDB
SQL,
    <<<'SQL'
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(120) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT ck_categories_active CHECK (is_active IN (0, 1))
) ENGINE=InnoDB
SQL,
    <<<'SQL'
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR(160) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
    name VARCHAR(180) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    starts_at DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    latitude DECIMAL(9,6) NULL,
    longitude DECIMAL(9,6) NULL,
    capacity INT UNSIGNED NOT NULL,
    price DECIMAL(12,2) NOT NULL,
    status ENUM('BORRADOR', 'PUBLICADO', 'CANCELADO', 'FINALIZADO') NOT NULL DEFAULT 'BORRADOR',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    CONSTRAINT ck_events_capacity CHECK (capacity > 0),
    CONSTRAINT ck_events_price CHECK (price >= 0),
    CONSTRAINT ck_events_latitude CHECK (latitude IS NULL OR latitude BETWEEN -90 AND 90),
    CONSTRAINT ck_events_longitude CHECK (longitude IS NULL OR longitude BETWEEN -180 AND 180),
    INDEX ix_events_status_date (status, starts_at),
    INDEX ix_events_category_date (category_id, starts_at)
) ENGINE=InnoDB
SQL,
    <<<'SQL'
CREATE TABLE reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    code CHAR(32) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
    quantity INT UNSIGNED NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total DECIMAL(22,2) NOT NULL,
    status ENUM('CONFIRMADA', 'CANCELADA') NOT NULL DEFAULT 'CONFIRMADA',
    cancelled_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    active_event_id BIGINT UNSIGNED GENERATED ALWAYS AS (
        CASE WHEN status = 'CONFIRMADA' THEN event_id ELSE NULL END
    ) STORED,
    CONSTRAINT fk_reservations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_reservations_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE RESTRICT,
    CONSTRAINT ck_reservations_quantity CHECK (quantity > 0),
    CONSTRAINT ck_reservations_price CHECK (unit_price >= 0 AND total = unit_price * quantity),
    CONSTRAINT ck_reservations_cancellation CHECK (
        (status = 'CONFIRMADA' AND cancelled_at IS NULL) OR
        (status = 'CANCELADA' AND cancelled_at IS NOT NULL)
    ),
    UNIQUE KEY uq_reservations_active_user_event (user_id, active_event_id),
    INDEX ix_reservations_event_status (event_id, status),
    INDEX ix_reservations_user_date (user_id, created_at),
    INDEX ix_reservations_status (status)
) ENGINE=InnoDB
SQL,
    <<<'SQL'
CREATE TABLE tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    reservation_id BIGINT UNSIGNED NOT NULL UNIQUE,
    code CHAR(64) CHARACTER SET ascii COLLATE ascii_bin NOT NULL UNIQUE,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tickets_reservation FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE RESTRICT
) ENGINE=InnoDB
SQL,
    <<<'SQL'
CREATE TABLE demo_seed_runs (
    seed_key VARCHAR(80) CHARACTER SET ascii COLLATE ascii_bin PRIMARY KEY,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
SQL,
];
