-- CREATE DATABASE IF NOT EXISTS notes_app
-- USE notes_app

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    title VARCHAR(255),
    body TEXT NULL,
    is_done TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (email, password_hash) VALUES
    ('example@mail.com', 'sdfkfsdjokwefyu7u498onjgloho54u0ui54600p0ph54'),
    ('example1@mail.com', 'sdfkfsdjokwefyu7sdgsfgdfu498onjgloho54u0ui54600p0ph54');

INSERT INTO notes (user_id, title, body) VALUES
    (1, 'Первая заметка', 'Привет! Эти данные создал файл init.sql при первом запуске базы'),
    (2, 'Купить продукты', 'Хлеб, молоко, чай, кофе');