-- Tạo database nếu chưa có
CREATE DATABASE IF NOT EXISTS myapp_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE myapp_db;

-- =============================================
-- Table: users
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    username        VARCHAR(50) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    email           VARCHAR(100) UNIQUE NOT NULL,
    role            ENUM('admin', 'user', 'moderator') DEFAULT 'user',
    avatar          VARCHAR(255) NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (1, 'admin', '123456', 'admin@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (2, 'tuan', '123456', 'tuan@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (3, 'meow', '123456', 'meow@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (4, 'bo8', '123456', 'bo8@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (5, 'tu', '123456', 'tu@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (6, 'tung', '123456', 'tung@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (7, 'trong', '123456', 'trong@gmail.com');
INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES (8, 'anh', '123456', 'anh@gmail.com');


-- =============================================
-- Table: tickets
-- =============================================
CREATE TABLE IF NOT EXISTS tickets (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    title           VARCHAR(255) NOT NULL,
    message         TEXT NOT NULL,
    attachment      VARCHAR(255) NULL,
    status          ENUM('open', 'in_progress', 'closed', 'resolved') DEFAULT 'open',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: notes
-- =============================================
CREATE TABLE IF NOT EXISTS notes (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    title           VARCHAR(255) NOT NULL,
    content         TEXT NOT NULL,
    is_private      TINYINT(1) DEFAULT 1,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_private (user_id, is_private)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: reports
-- =============================================
CREATE TABLE IF NOT EXISTS reports (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    owner_id        BIGINT NOT NULL,
    filename        VARCHAR(255) NOT NULL,
    report_data     JSON NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_owner (owner_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: uploads
-- =============================================
CREATE TABLE IF NOT EXISTS uploads (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    filename        VARCHAR(255) NOT NULL,
    path            VARCHAR(512) NOT NULL,
    mime_type       VARCHAR(100) NULL,
    uploaded_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_upload (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: api_keys
-- =============================================
CREATE TABLE IF NOT EXISTS api_keys (
    id              BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id         BIGINT NOT NULL,
    service         VARCHAR(100) NOT NULL,
    api_key         VARCHAR(255) UNIQUE NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uk_user_service (user_id, service)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo user test (tùy chọn)
INSERT IGNORE INTO users (username, email, password, role) 
VALUES ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');