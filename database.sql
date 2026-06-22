-- ============================================================
-- Bus Ticket System Database
-- Engine: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS bus_ticket_system
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE bus_ticket_system;

-- ============================================================
-- Users
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
-- Buses
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
-- Routes
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
-- Schedules
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
-- Bookings
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
-- Seed: default administrator and user accounts
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

INSERT INTO users (username, fullname, email, contact, password, role)
VALUES (
    'user',
    'Regular Account',
    'user@gmail.com',
    '09171234568',
    '$2y$10$m7ueYyVCArtdsLawv/KD2enomfi0ElZ6.qfVwbNwqBL68EttwUWSy', -- hash of 'UserPass123!'
    'user'
);

-- ============================================================
-- Seed: Bus Ticketing System
-- Buses, Routes, and Sample Schedules
-- ============================================================

USE bus_ticket_system;

-- ============================================================
-- Buses
-- Palawan's intercity buses typically seat 45–55 passengers
-- ============================================================
INSERT INTO buses (bus_num, seats) VALUES
    ('PLW-001', 45),
    ('PLW-002', 45),
    ('PLW-003', 50),
    ('PLW-004', 50),
    ('PLW-005', 55),
    ('PLW-006', 55),
    ('PLW-007', 45),
    ('PLW-008', 50);

-- ============================================================
-- Routes
-- Major intercity routes in Palawan
-- ============================================================
INSERT INTO routes (source, destination) VALUES
    -- From Puerto Princesa
    ('Puerto Princesa', 'El Nido'),
    ('Puerto Princesa', 'Coron'),
    ('Puerto Princesa', 'Roxas'),
    ('Puerto Princesa', 'San Vicente'),
    ('Puerto Princesa', 'Brooke\'s Point'),
    ('Puerto Princesa', 'Quezon'),
    ('Puerto Princesa', 'Narra'),
    ('Puerto Princesa', 'Aborlan'),

    -- To Puerto Princesa
    ('El Nido', 'Puerto Princesa'),
    ('Coron', 'Puerto Princesa'),
    ('Roxas', 'Puerto Princesa'),
    ('San Vicente', 'Puerto Princesa'),
    ('Brooke\'s Point', 'Puerto Princesa'),
    ('Quezon', 'Puerto Princesa'),
    ('Narra', 'Puerto Princesa'),
    ('Aborlan', 'Puerto Princesa'),

    -- Inter-town routes (north Palawan)
    ('El Nido', 'San Vicente'),
    ('San Vicente', 'El Nido'),
    ('El Nido', 'Roxas'),
    ('Roxas', 'El Nido'),
    ('Roxas', 'San Vicente'),
    ('San Vicente', 'Roxas'),

    -- Inter-town routes (south Palawan)
    ('Narra', 'Brooke\'s Point'),
    ('Brooke\'s Point', 'Narra'),
    ('Quezon', 'Narra'),
    ('Narra', 'Quezon'),
    ('Aborlan', 'Narra'),
    ('Narra', 'Aborlan');

-- ============================================================
-- Schedules
-- Fares based on approximate real distances/rates in Palawan
-- Puerto Princesa <-> El Nido  ~420 km  => ~650 PHP
-- Puerto Princesa <-> Coron    ~240 km  => ~450 PHP
-- Puerto Princesa <-> San Vicente ~180 km => ~350 PHP
-- Puerto Princesa <-> Roxas    ~130 km  => ~250 PHP
-- Puerto Princesa <-> Narra     ~80 km  => ~150 PHP
-- Puerto Princesa <-> Aborlan   ~70 km  => ~130 PHP
-- Puerto Princesa <-> Quezon    ~90 km  => ~160 PHP
-- Puerto Princesa <-> Brooke's Point ~190 km => ~320 PHP
-- ============================================================

-- Puerto Princesa -> El Nido  (bus_id=1, route_id=1)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (1, 1, 650.00, '2025-07-01 06:00:00'),
    (2, 1, 650.00, '2025-07-01 08:00:00'),
    (1, 1, 650.00, '2025-07-02 06:00:00'),
    (2, 1, 650.00, '2025-07-02 08:00:00');

-- El Nido -> Puerto Princesa  (bus_id=3, route_id=9)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (3, 9, 650.00, '2025-07-01 07:00:00'),
    (4, 9, 650.00, '2025-07-01 09:00:00'),
    (3, 9, 650.00, '2025-07-02 07:00:00'),
    (4, 9, 650.00, '2025-07-02 09:00:00');

-- Puerto Princesa -> San Vicente  (bus_id=5, route_id=4)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (5, 4, 350.00, '2025-07-01 07:00:00'),
    (5, 4, 350.00, '2025-07-01 13:00:00'),
    (5, 4, 350.00, '2025-07-02 07:00:00');

-- San Vicente -> Puerto Princesa  (bus_id=6, route_id=12)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (6, 12, 350.00, '2025-07-01 06:00:00'),
    (6, 12, 350.00, '2025-07-01 12:00:00'),
    (6, 12, 350.00, '2025-07-02 06:00:00');

-- Puerto Princesa -> Roxas  (bus_id=7, route_id=3)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (7, 3, 250.00, '2025-07-01 08:00:00'),
    (7, 3, 250.00, '2025-07-01 14:00:00'),
    (7, 3, 250.00, '2025-07-02 08:00:00');

-- Roxas -> Puerto Princesa  (bus_id=8, route_id=11)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (8, 11, 250.00, '2025-07-01 07:30:00'),
    (8, 11, 250.00, '2025-07-01 13:30:00'),
    (8, 11, 250.00, '2025-07-02 07:30:00');

-- Puerto Princesa -> Narra  (bus_id=1, route_id=7)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (1, 7, 150.00, '2025-07-03 06:00:00'),
    (2, 7, 150.00, '2025-07-03 12:00:00');

-- Puerto Princesa -> Brooke's Point  (bus_id=3, route_id=5)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (3, 5, 320.00, '2025-07-03 06:30:00'),
    (4, 5, 320.00, '2025-07-03 11:00:00');

-- Puerto Princesa -> Aborlan  (bus_id=5, route_id=8)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (5, 8, 130.00, '2025-07-03 07:00:00'),
    (6, 8, 130.00, '2025-07-03 13:00:00');

-- Puerto Princesa -> Quezon  (bus_id=7, route_id=6)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (7, 6, 160.00, '2025-07-03 07:00:00'),
    (8, 6, 160.00, '2025-07-03 12:00:00');

-- El Nido -> Roxas  (bus_id=1, route_id=19)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (1, 19, 420.00, '2025-07-04 07:00:00');

-- Roxas -> El Nido  (bus_id=2, route_id=20)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (2, 20, 420.00, '2025-07-04 08:00:00');

-- Narra -> Brooke's Point  (bus_id=3, route_id=23)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (3, 23, 180.00, '2025-07-04 09:00:00');

-- Brooke's Point -> Narra  (bus_id=4, route_id=24)
INSERT INTO schedules (bus_id, route_id, fare, depart_time) VALUES
    (4, 24, 180.00, '2025-07-04 10:00:00');