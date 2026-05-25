# ETHEATRO - Audition Management System

## Overview
ETHEATRO is a comprehensive audition management system for the University of Science and Technology of Southern Philippines. It allows students to register for auditions and provides administrators with tools to manage applications, schedule auditions, and post announcements.

## Features

### Student Features
- **User Registration & Login**: Easy sign-up with email verification
- **Profile Management**: Update personal information and talent details
- **Dashboard**: View audition status and statistics
- **Notifications**: Real-time updates about audition status and announcements
- **Profile Archive**: Keep track of audition history

### Admin Features
- **Manage Registrants**: Review and approve student applications
- **Manage Auditions**: Schedule auditions and record results
- **Announcements**: Post important updates for all students
- **Dashboard**: View statistics and manage the audition process

## System Colors (USTP Branding)
- Primary Blue: `#003DA5`
- Dark Blue: `#002066`
- Yellow: `#FFD700`
- White: `#FFFFFF`

## Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB
- Apache with mod_rewrite enabled
- XAMPP or similar local server environment

### Step 1: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `etheatro_audition`
3. Import the SQL file: `database/audition_system.sql`

### Step 2: Configure Database Connection
1. Edit `config/db.php`
2. Update the following values with your database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'etheatro_audition');
   ```

### Step 3: Access the System
- **Main Page**: http://localhost/Etheatro/
- **Login**: http://localhost/Etheatro/auth/login.php
- **Register**: http://localhost/Etheatro/auth/register.php

### Default Credentials (Demo)
- **Admin Email**: `admin@example.com`
- **Admin Password**: `password123`
- **Student Email**: `juan@example.com`
- **Student Password**: `password123`

> **Note**: Change these credentials in the database before deploying to production!

## File Structure

```
Etheatro/
├── index.php                 # Main landing page
├── auth/
│   ├── login.php            # Login page
│   ├── register.php         # Registration page
│   └── forgot_password.php  # Password recovery
├── students/
│   ├── dashboard.php        # Student dashboard
│   ├── profile.php          # View profile
│   ├── edit.php             # Edit profile
│   ├── notification.php     # View notifications
│   └── archive.php          # Archive/history
├── admin/
│   ├── manageregistrants.php    # Manage registrations
│   ├── manage_auditionees.php   # Manage auditions
│   └── announcements.php        # Post announcements
├── config/
│   └── db.php               # Database configuration
├── database/
│   └── audition_system.sql  # Database schema
├── assets/
│   ├── css/
│   │   └── style.css        # Custom styles (USTP branding)
│   └── js/
│       └── (JS files)
└── .htaccess                # URL rewriting rules
```

## Database Schema

### auditionees Table
- `id`: Primary key
- `school_id`: Unique school ID
- `first_name`: First name
- `middle_initial`: Middle initial (optional)
- `last_name`: Last name
- `year_level`: Year level (1st-4th Year)
- `department`: Department
- `talent`: Type of talent
- `email`: Unique email
- `password`: Hashed password
- `role`: 'student' or 'admin'
- `status`: Pending, Ready to Audition, Approved, Rejected
- `audition_date`: Scheduled audition date
- `feedback`: Admin feedback
- `created_at`: Registration timestamp
- `updated_at`: Last update timestamp

### announcements Table
- `id`: Primary key
- `message`: Announcement message
- `created_by`: Admin user ID
- `created_at`: Creation timestamp

### notifications Table
- `id`: Primary key
- `auditionee_id`: Student ID
- `message`: Notification message
- `type`: 'status_update', 'announcement', 'schedule'
- `is_read`: Boolean read status
- `created_at`: Creation timestamp

## User Workflows

### Student Registration Flow
1. User registers with school ID, name, and talent
2. Status is set to "Pending" by default
3. Admin reviews and approves for audition
4. Student receives notification
5. Admin schedules audition date
6. Student views audition date in profile
7. After audition, admin marks as Approved or Rejected

### Admin Workflow
1. Login to admin panel
2. View pending registrations
3. Approve students for audition or reject them
4. Schedule audition dates for approved students
5. Post announcements for all students
6. Record audition results (Approved/Rejected with feedback)

## Features

### Authentication
- Secure password hashing with bcrypt
- Session-based authentication
- Role-based access control (Student/Admin)

### UI/UX
- Bootstrap 5 responsive design
- USTP color scheme (Blue, Yellow, White)
- Smooth animations and transitions
- Mobile-friendly interface
- Font Awesome icons

### Notifications
- Automatic notifications for status updates
- Announcement broadcasting
- Schedule reminders
- Unread notification counter

### Data Management
- Secure database queries with escaping
- Timestamp tracking
- Status tracking workflow
- Feedback management

## Security Considerations

1. **Password Security**
   - Passwords are hashed using bcrypt
   - Never stored in plain text

2. **Input Validation**
   - All user inputs are sanitized
   - SQL injection prevention
   - XSS protection

3. **Access Control**
   - Role-based access (student vs admin)
   - Login required for protected pages
   - Session management

4. **Best Practices**
   - Use HTTPS in production
   - Regular security audits
   - Keep PHP and dependencies updated

## Troubleshooting

### Database Connection Error
- Check database credentials in `config/db.php`
- Ensure MySQL is running
- Verify database name is correct

### Session/Login Issues
- Clear browser cookies
- Check PHP session configuration
- Verify session directory permissions

### Email Issues
- Configure SMTP in `config/db.php` for password reset
- Test email functionality separately

## Development & Customization

### Adding New Features
1. Create new files in appropriate folders
2. Include database configuration: `require_once '../config/db.php'`
3. Use Bootstrap classes for styling
4. Follow existing code patterns

### Styling
- Custom styles in `assets/css/style.css`
- Uses Bootstrap 5 utilities
- USTP colors defined as CSS variables
- Responsive mobile-first approach

### Database Changes
- Modify `database/audition_system.sql`
- Use proper SQL syntax
- Test before deploying
- Backup existing data

## Support & Maintenance

For issues or feature requests, please contact:
- **Email**: etheatro@ustp.edu.ph
- **Department**: Student Affairs / Events

## License
Copyright © 2024 University of Science and Technology of Southern Philippines

## Version
Version 1.0 - Initial Release

---

**Last Updated**: May 25, 2024
