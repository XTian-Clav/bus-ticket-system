-- ============================================================
-- Bus Ticket System Database
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS bus_ticket_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bus_ticket_system;

-- ============================================================
-- 1. Users
-- ============================================================
CREATE TABLE users (
    id          INT UNSIGNED         NOT NULL AUTO_INCREMENT,
    username    VARCHAR(50)          NOT NULL,
    fullname    VARCHAR(100)         NOT NULL,
    email       VARCHAR(100)         NOT NULL,
    contact     VARCHAR(20)          NOT NULL,
    password    VARCHAR(255)         NOT NULL,
    role        ENUM('admin','user') NOT NULL DEFAULT 'user',
    avatar      VARCHAR(255)         NULL,
    created_at  TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email    (email),
    UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. Buses
-- ============================================================
CREATE TABLE buses (
    id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    bus_num     VARCHAR(50)      NOT NULL,
    seats       TINYINT UNSIGNED NOT NULL,
    created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_buses_bus_num (bus_num)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. Routes
-- ============================================================
CREATE TABLE routes (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    source      VARCHAR(100)  NOT NULL,
    destination VARCHAR(100)  NOT NULL,
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_routes_pair  (source, destination),
    INDEX idx_routes_source    (source)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. Schedules
-- ============================================================
CREATE TABLE schedules (
    id          INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    bus_id      INT UNSIGNED   NOT NULL,
    route_id    INT UNSIGNED   NOT NULL,
    fare        DECIMAL(10,2)  NOT NULL,
    depart_time DATETIME       NOT NULL,
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_schedules_bus_depart (bus_id, depart_time),
    INDEX idx_schedules_route          (route_id),
    INDEX idx_schedules_depart         (depart_time),

    CONSTRAINT fk_schedules_bus
        FOREIGN KEY (bus_id)   REFERENCES buses(id)  ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_schedules_route
        FOREIGN KEY (route_id) REFERENCES routes(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. Bookings
-- ============================================================
CREATE TABLE bookings (
    id          INT UNSIGNED                  NOT NULL AUTO_INCREMENT,
    schedule_id INT UNSIGNED                  NOT NULL,
    user_id     INT UNSIGNED                  NOT NULL,
    seat_num    TINYINT UNSIGNED              NOT NULL,
    status      ENUM('confirmed','cancelled') NOT NULL DEFAULT 'confirmed',
    created_at  TIMESTAMP                     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_bookings_user      (user_id),
    INDEX idx_bookings_status    (status),

    CONSTRAINT fk_bookings_schedule
        FOREIGN KEY (schedule_id) REFERENCES schedules(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_bookings_user
        FOREIGN KEY (user_id)     REFERENCES users(id)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Seed: default administrator account
-- ============================================================
INSERT INTO users (username, fullname, email, contact, password, role)
VALUES (
    'admin',
    'System Administrator',
    'admin@gmail.com',
    '09171234567',
    '$2y$10$kV85SVvaP.EACBCNr0rJxuhh33jjtBVYcPW9pA8rX14Hg34a8Y9ES', -- hash of 'AdminPass123!'
    'admin'
);

-- ============================================================
-- Migration: run this instead if the `users` table already exists
-- ============================================================
-- ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER role;