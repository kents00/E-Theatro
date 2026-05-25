-- ========================================================
-- AUDITION SYSTEM DATABASE STRUCTURE (PostgreSQL)
-- ========================================================

-- --------------------------------------------------------
-- ENUM TYPES
-- --------------------------------------------------------

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
        CREATE TYPE user_role AS ENUM('student', 'admin');
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'audition_status') THEN
        CREATE TYPE audition_status AS ENUM('Pending', 'Ready to Audition', 'Approved', 'Rejected');
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'notification_type') THEN
        CREATE TYPE notification_type AS ENUM('status_update', 'announcement', 'schedule');
    END IF;
END$$;

-- --------------------------------------------------------
-- TABLE: auditionees
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS auditionees (
  id SERIAL PRIMARY KEY,
  school_id VARCHAR(50) NOT NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  middle_initial CHAR(1) DEFAULT NULL,
  last_name VARCHAR(100) NOT NULL,
  year_level VARCHAR(20) NOT NULL,
  department VARCHAR(50) NOT NULL,
  talent VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role user_role NOT NULL DEFAULT 'student',
  status audition_status NOT NULL DEFAULT 'Pending',
  audition_date TIMESTAMPTZ DEFAULT NULL,
  feedback TEXT DEFAULT NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------
-- TABLE: announcements
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS announcements (
  id SERIAL PRIMARY KEY,
  message TEXT NOT NULL,
  created_by INT NOT NULL,
  created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (created_by) REFERENCES auditionees(id)
);

-- --------------------------------------------------------
-- TABLE: notifications
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS notifications (
  id SERIAL PRIMARY KEY,
  auditionee_id INT NOT NULL,
  message TEXT NOT NULL,
  type notification_type NOT NULL,
  is_read BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (auditionee_id) REFERENCES auditionees(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- Function to update `updated_at` timestamp
-- --------------------------------------------------------

CREATE OR REPLACE FUNCTION trigger_set_timestamp()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- --------------------------------------------------------
-- Trigger for `auditionees` table
-- --------------------------------------------------------

DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_trigger WHERE tgname = 'set_timestamp') THEN
        CREATE TRIGGER set_timestamp
        BEFORE UPDATE ON auditionees
        FOR EACH ROW
        EXECUTE PROCEDURE trigger_set_timestamp();
    END IF;
END$$;

-- --------------------------------------------------------
-- SAMPLE ADMIN / STUDENT DATA
-- --------------------------------------------------------

INSERT INTO auditionees
(
  school_id,
  first_name,
  middle_initial,
  last_name,
  year_level,
  department,
  talent,
  email,
  password,
  role,
  status
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
  '$2y$12$FuxGhwq4ecFFHEp4Aqwo..Eb/IgKQnfW/cH8eFzcDNHmBSPI8Yhv.',
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
  '$2y$12$FuxGhwq4ecFFHEp4Aqwo..Eb/IgKQnfW/cH8eFzcDNHmBSPI8Yhv.',
  'admin',
  'Approved'
)
ON CONFLICT (school_id) DO NOTHING;

-- --------------------------------------------------------
-- SAMPLE ANNOUNCEMENTS
-- --------------------------------------------------------

-- Note: Assumes the admin user (ADMIN-001) has id=2.
-- You may need to adjust this based on actual data.
INSERT INTO announcements (message, created_by)
SELECT 'Welcome to the Etheatro Audition System!', id FROM auditionees WHERE school_id = 'ADMIN-001'
ON CONFLICT DO NOTHING;

INSERT INTO announcements (message, created_by)
SELECT 'Auditions will start next week. Please prepare your talent presentation.', id FROM auditionees WHERE school_id = 'ADMIN-001'
ON CONFLICT DO NOTHING;

INSERT INTO announcements (message, created_by)
SELECT 'Reminder: Submit your requirements before the audition date.', id FROM auditionees WHERE school_id = 'ADMIN-001'
ON CONFLICT DO NOTHING;
