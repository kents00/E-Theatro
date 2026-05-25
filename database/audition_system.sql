SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ========================================================
-- AUDITION SYSTEM DATABASE STRUCTURE
-- ========================================================

-- --------------------------------------------------------
-- TABLE: auditionees
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `auditionees` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `school_id` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(100) NOT NULL,
  `middle_initial` CHAR(1) DEFAULT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `year_level` VARCHAR(20) NOT NULL,
  `department` VARCHAR(50) NOT NULL,
  `talent` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('student','admin') NOT NULL DEFAULT 'student',
  `status` ENUM(
      'Pending',
      'Ready to Audition',
      'Approved',
      'Rejected'
  ) NOT NULL DEFAULT 'Pending',
  `audition_date` DATETIME DEFAULT NULL,
  `feedback` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  UNIQUE KEY `school_id` (`school_id`),
  UNIQUE KEY `email` (`email`)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- TABLE: announcements
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `message` TEXT NOT NULL,
  `created_by` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`created_by`) REFERENCES `auditionees`(`id`)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- TABLE: notifications
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `auditionee_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('status_update', 'announcement', 'schedule') NOT NULL,
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),
  FOREIGN KEY (`auditionee_id`) REFERENCES `auditionees`(`id`) ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- SAMPLE ADMIN / STUDENT DATA
-- --------------------------------------------------------

INSERT INTO `auditionees`
(
  `school_id`,
  `first_name`,
  `middle_initial`,
  `last_name`,
  `year_level`,
  `department`,
  `talent`,
  `email`,
  `password`,
  `role`,
  `status`
)
VALUES
(
  '2024-001',
  'Juan',
  'D',
  'Cruz',
  '1st Year',
  'BSIT',
  'Singing',
  'juan@example.com',
  '$2y$10$samplehashedpassword1',
  'student',
  'Pending'
),
(
  'ADMIN-001',
  'Admin',
  'A',
  'User',
  'N/A',
  'Administration',
  'Management',
  'admin@example.com',
  '$2y$10$samplehashedpassword2',
  'admin',
  'Approved'
);

-- --------------------------------------------------------
-- SAMPLE ANNOUNCEMENTS
-- --------------------------------------------------------

INSERT INTO `announcements` (`message`, `created_by`)
VALUES
('Welcome to the Etheatro Audition System!', 2),
('Auditions will start next week. Please prepare your talent presentation.', 2),
('Reminder: Submit your requirements before the audition date.', 2);

COMMIT;
