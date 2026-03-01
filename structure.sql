CREATE DATABASE todo_app_dev;
USE todo_app_dev;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE todos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,

    title VARCHAR(255) NOT NULL,
    due_date DATE NOT NULL,
    due_time TIME NULL,

    status ENUM('pending', 'done', 'giveup') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX (user_id),
    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

ALTER TABLE todos
ADD COLUMN priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium';

CREATE TABLE user_stats (
    user_id INT UNSIGNED PRIMARY KEY,

    todos_done INT UNSIGNED NOT NULL DEFAULT 0,
    todos_giveup INT UNSIGNED NOT NULL DEFAULT 0,

    updated_at TIMESTAMP 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);