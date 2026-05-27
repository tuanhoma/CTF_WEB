-- ============================================================
-- CTF Web Vuln Lab — Database Init
-- MySQL 8.0
-- ============================================================

CREATE DATABASE IF NOT EXISTS corpdb;
USE corpdb;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email         VARCHAR(100),
    role          ENUM('admin','staff','user') DEFAULT 'user',
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Support tickets
CREATE TABLE IF NOT EXISTS tickets (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT,
    title      VARCHAR(200),
    message    TEXT,
    status     ENUM('open','closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Personal notes
CREATE TABLE IF NOT EXISTS notes (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT,
    content    TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Sessions table (for reference — PHP files save to filesystem)
CREATE TABLE IF NOT EXISTS sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    user_id    INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA — passwords stored as MD5 (weak, by design)
-- ============================================================

INSERT INTO users (username, password_hash, email, role) VALUES
('admin',   MD5('admin123'),   'admin@corp.local',   'admin'),
('alice',   MD5('alice123'),   'alice@corp.local',   'user'),
('bob',     MD5('bob123'),     'bob@corp.local',     'user'),
('support', MD5('support123'), 'support@corp.local', 'staff');

-- Seed tickets (normal content — attacker will add XSS via support.php)
INSERT INTO tickets (user_id, title, message) VALUES
(2, 'Cannot login', 'I keep getting an error when I try to login from Firefox.'),
(3, 'Feature request', 'Can you please add a dark mode to the portal?'),
(2, 'Bug report', 'The export button is broken on Firefox v115.');

-- Seed notes
INSERT INTO notes (user_id, content) VALUES
(2, '# Meeting notes\n\nDiscuss Q1 targets with team.'),
(3, 'Remember to update password before end of month.');
