CREATE DATABASE IF NOT EXISTS gestion_tech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_tech;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS logs;
DROP TABLE IF EXISTS support;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS reports;
DROP TABLE IF EXISTS settings;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prenom VARCHAR(100) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'technicien', 'utilisateur') NOT NULL DEFAULT 'technicien',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE tasks (
    id_task INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('en attente', 'en cours', 'terminée') NOT NULL DEFAULT 'en cours',
    date_echeance DATE NULL,
    technicien_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_tasks_user
        FOREIGN KEY (technicien_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE support (
    id_sup INT AUTO_INCREMENT PRIMARY KEY,
    sujet VARCHAR(255) NOT NULL,
    status ENUM('ouvert', 'ferme') NOT NULL DEFAULT 'ouvert',
    technicien_id INT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_support_user
        FOREIGN KEY (technicien_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    categorie VARCHAR(100) DEFAULT 'Général',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_logs_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comptes de test
-- Admin : admin@gestion.com / admin123
-- Technicien : tech@gestion.com / tech123
INSERT INTO users (prenom, nom, email, password, role) VALUES
('Admin', 'System', 'admin@gestion.com', '$2y$10$2LYo62h3qrqBHUDRi/DPHO5vzpxchbB97XFZITDHEkPklvAsXN49O', 'admin'),
('Ali', 'Technicien', 'tech@gestion.com', '$2y$10$jfEge1RlzdhD86XeZvS46../Wgp8dcNRJdfmj90CIEtSuNqkxbZa2', 'technicien');

INSERT INTO tasks (titre, description, status, date_echeance, technicien_id) VALUES
('Installation caméra', 'Installer les nouvelles caméras de surveillance.', 'en cours', '2026-05-25', 2),
('Maintenance réseau', 'Vérifier le réseau interne et tester la connexion.', 'en attente', '2026-05-30', 2),
('Contrôle alarme', 'Vérifier le bon fonctionnement du système d’alarme.', 'terminée', '2026-05-20', 2);

INSERT INTO support (sujet, status, technicien_id) VALUES
('Problème de connexion internet', 'ouvert', 2),
('Demande de matériel', 'ferme', 2);

INSERT INTO reports (titre, description, categorie) VALUES
('Rapport maintenance', 'Maintenance effectuée avec succès.', 'Maintenance'),
('Rapport installation', 'Installation réalisée et testée.', 'Installation');

INSERT INTO settings (`key`, `value`) VALUES
('site_name', 'Gestion Techniciens'),
('email_contact', 'contact@gestion-tech.local'),
('modules_enabled', 'tasks,support,reports');
