CREATE DATABASE IF NOT EXISTS `qr_attendance` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `qr_attendance`;

CREATE TABLE IF NOT EXISTS `students` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_identifier` VARCHAR(64) NOT NULL,
    `full_name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(160) NOT NULL,
    `qr_token` VARCHAR(64) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_student_identifier` (`student_identifier`),
    UNIQUE KEY `uniq_student_email` (`email`),
    UNIQUE KEY `uniq_qr_token` (`qr_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendance` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `student_id` INT UNSIGNED NOT NULL,
    `recorded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_student_recorded_at` (`student_id`, `recorded_at`),
    CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

