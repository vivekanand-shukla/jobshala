-- ============================================================
--  Jobshala Database Setup
--  Run this in phpMyAdmin or MySQL CLI:
--  mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS jobshala CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jobshala;

-- -------------------------------------------------------
-- USERS TABLE (job seekers + job providers)
-- -------------------------------------------------------
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    full_name   VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('seeker','provider') NOT NULL,
    company_name VARCHAR(150) DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- -------------------------------------------------------
-- ADMIN TABLE
-- -------------------------------------------------------
CREATE TABLE admin (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password        VARCHAR(255) NOT NULL,
    master_key      VARCHAR(255) NOT NULL
);

-- Default admin (password: admin123 | master: master123)
INSERT INTO admin (email, password, master_key)
VALUES (
    'admin@jobshala.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

-- -------------------------------------------------------
-- JOBS TABLE
-- -------------------------------------------------------
CREATE TABLE jobs (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    provider_id     INT NOT NULL,
    title           VARCHAR(150) NOT NULL,
    description     TEXT NOT NULL,
    skills_required TEXT,
    qualification   VARCHAR(255),
    salary_min      INT DEFAULT 0,
    salary_max      INT DEFAULT 0,
    location        VARCHAR(150),
    job_type        ENUM('full-time','part-time','internship','freelance') DEFAULT 'full-time',
    work_mode       ENUM('on-site','remote','hybrid') DEFAULT 'on-site',
    category        VARCHAR(100),
    status          ENUM('active','inactive') DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
);

-- -------------------------------------------------------
-- APPLICATIONS TABLE
-- -------------------------------------------------------
CREATE TABLE applications (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    job_id          INT NOT NULL,
    seeker_id       INT NOT NULL,
    resume_link     VARCHAR(500),
    portfolio_link  VARCHAR(500),
    contact_info    VARCHAR(255),
    has_experience  TINYINT(1) DEFAULT 0,
    status          ENUM('pending','selected','rejected') DEFAULT 'pending',
    applied_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id)     REFERENCES jobs(id)  ON DELETE CASCADE,
    FOREIGN KEY (seeker_id)  REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (job_id, seeker_id)
);
