# ETHEATRO System - Complete Setup Guide

## Quick Start Guide

### Prerequisites
- XAMPP or similar local development environment
- PHP 7.4+
- MySQL/MariaDB
- Modern web browser

---

## Step 1: Download & Place Files

1. Extract the Etheatro folder to your XAMPP htdocs directory:
   ```
   C:\xampp\htdocs\Etheatro\
   ```

2. Ensure all folders are created:
   - `auth/` - Authentication pages
   - `admin/` - Admin panel pages
   - `students/` - Student portal pages
   - `config/` - Configuration files
   - `database/` - SQL files
   - `assets/` - CSS and JavaScript files

---

## Step 2: Create Database

### Using phpMyAdmin:

1. Start XAMPP Control Panel
2. Start Apache and MySQL
3. Open browser: http://localhost/phpmyadmin
4. Click "New" to create a new database
5. Database name: `etheatro_audition`
6. Collation: `utf8mb4_general_ci`
7. Click "Create"

### Import SQL File:

1. Select the newly created `etheatro_audition` database
2. Click "Import" tab
3. Choose file: `database/audition_system.sql`
4. Click "Go/Import"

**Success!** You'll see the tables:
- `auditionees`
- `announcements`
- `notifications`

---

## Step 3: Configure Database Connection

1. Open `config/db.php` in text editor
2. Update database credentials if needed:

```php
define('DB_HOST', 'localhost');  // Usually localhost
define('DB_USER', 'root');       // XAMPP default is 'root'
define('DB_PASS', '');           // XAMPP default is empty
define('DB_NAME', 'etheatro_audition');
```

3. Save the file

**Note**: If you used a different database name or username, update accordingly.

---

## Step 4: Access the System

Open your web browser and navigate to:

```
http://localhost/Etheatro/
```

You should see the ETHEATRO landing page.

---

## Step 5: Default Login Credentials

The system comes with sample data:

### Admin Account:
- **Email**: admin@example.com
- **Password**: password123

### Student Account:
- **Email**: juan@example.com
- **Password**: password123

**⚠️ IMPORTANT**: Change these passwords immediately in production!

---

## User Guides

### For Students:

1. **Register**
   - Click "Register Now"
   - Fill in all required fields
   - Submit form
   - Status will be "Pending"

2. **Dashboard**
   - View your audition status
   - See latest announcements
   - Access quick links

3. **Profile**
   - View your complete information
   - See audition status details
   - View scheduled audition date (if assigned)
   - See feedback (after audition)

4. **Edit Profile**
   - Update name, year level, department, talent
   - Cannot change email or school ID
   - Changes saved immediately

5. **Notifications**
   - View all updates about your audition
   - See status changes
   - Get schedule information
   - Mark as read

---

### For Admins:

1. **Dashboard**
   - View statistics
   - Quick access to all admin tools

2. **Manage Registrants**
   - Review pending applications
   - Search and filter students
   - Approve for audition or reject
   - See registration details

3. **Manage Auditions**
   - Schedule audition dates
   - Record audition results
   - Add feedback
   - Mark as approved or rejected

4. **Announcements**
   - Post important updates
   - Messages auto-sent to all students
   - View announcement history
   - Delete old announcements

---

## Audition Status Workflow

```
Registration
    ↓
Pending (waiting for admin review)
    ↓
Ready to Audition (admin approved, date scheduled)
    ↓
Approved/Rejected (after audition)
```

### Status Descriptions:

| Status | Meaning | Next Action |
|--------|---------|------------|
| **Pending** | Application submitted, waiting for review | Admin reviews |
| **Ready to Audition** | Approved for audition, date scheduled | Student attends audition |
| **Approved** | Successfully passed audition | Prepare for production |
| **Rejected** | Not selected this round | Can apply next time |

---

## Features Overview

### Security Features
✅ Password hashing (bcrypt)
✅ Session-based authentication
✅ SQL injection prevention
✅ XSS protection
✅ Input validation

### User Interface
✅ Bootstrap 5 responsive design
✅ USTP color scheme (Blue/Yellow/White)
✅ Mobile-friendly
✅ Smooth animations
✅ Font Awesome icons

### Functionality
✅ User registration and login
✅ Profile management
✅ Real-time notifications
✅ Audition scheduling
✅ Result management
✅ Announcement system

---

## File Structure Quick Reference

```
Etheatro/
├── index.php                      ← Landing page
├── api.php                        ← API endpoints
├── auth/
│   ├── login.php                 ← Student/Admin login
│   ├── register.php              ← Student registration
│   └── forgot_password.php       ← Password recovery
├── students/
│   ├── dashboard.php             ← Student main page
│   ├── profile.php               ← View profile
│   ├── edit.php                  ← Edit profile
│   ├── notification.php          ← Notifications
│   └── archive.php               ← History/Archive
├── admin/
│   ├── manageregistrants.php    ← Review applications
│   ├── manage_auditionees.php   ← Schedule auditions
│   └── announcements.php         ← Post announcements
├── config/
│   └── db.php                    ← Database config
├── database/
│   └── audition_system.sql      ← Database schema
├── assets/
│   ├── css/
│   │   └── style.css            ← Main styles
│   └── js/
│       └── script.js            ← JavaScript utilities
└── README.md                      ← Full documentation
```

---

## Common Issues & Solutions

### Issue: Database Connection Error

**Error**: "Connection failed: Access denied for user 'root'@'localhost'"

**Solution**:
1. Check MySQL is running in XAMPP
2. Verify username and password in `config/db.php`
3. Default XAMPP: user=`root`, password=`` (empty)

### Issue: Cannot Login

**Error**: "Invalid email or password"

**Solution**:
1. Check email address is correct (case-sensitive)
2. Verify password is correct
3. Check user role (admin vs student)
4. Try default accounts first

### Issue: 404 Page Not Found

**Error**: "The requested URL was not found"

**Solution**:
1. Ensure file is in correct directory
2. Check URL spelling
3. Enable .htaccess if using Apache
4. Check Apache mod_rewrite is enabled

### Issue: Notifications Not Working

**Error**: Notifications don't appear or disappear

**Solution**:
1. Check notifications table in database
2. Verify session is active
3. Clear browser cookies
4. Check JavaScript console for errors

---

## Customization Tips

### Change School Name/Branding
1. Edit `index.php` - Change "ETHEATRO" text
2. Edit `assets/css/style.css` - Modify color variables
3. Update page titles in all PHP files

### Add Custom Fields
1. Alter `auditionees` table in database
2. Update registration and profile forms
3. Update profile display pages

### Modify Color Scheme
Edit `assets/css/style.css`:
```css
:root {
    --ustp-white: #FFFFFF;
    --ustp-yellow: #FFD700;
    --ustp-blue: #003DA5;
    --ustp-dark-blue: #002066;
}
```

---

## Production Deployment

Before deploying to production:

1. **Change Credentials**
   - Update admin email and password
   - Remove sample student accounts
   - Update database credentials

2. **Enable HTTPS**
   - Get SSL certificate
   - Configure Apache for HTTPS
   - Update all URLs to use https://

3. **Security Hardening**
   - Set strong passwords
   - Disable debug mode
   - Restrict admin access by IP
   - Regular backups

4. **Optimization**
   - Compress images
   - Minify CSS/JavaScript
   - Enable caching
   - Optimize database queries

---

## Testing Checklist

- [ ] Registration works
- [ ] Login works for admin and student
- [ ] Admin can approve registrants
- [ ] Admin can schedule auditions
- [ ] Notifications appear for students
- [ ] Students can edit profiles
- [ ] Admin can post announcements
- [ ] All links work correctly
- [ ] Mobile view is responsive
- [ ] Logout works properly

---

## Support & Help

For additional help:
1. Check README.md for detailed documentation
2. Review inline code comments
3. Check browser console for JavaScript errors
4. Check XAMPP error logs
5. Verify database tables and data

---

## Next Steps

1. Complete the setup checklist above
2. Test with default credentials
3. Create your own admin account
4. Test student registration
5. Invite users to register
6. Start managing auditions!

---

**Version**: 1.0
**Last Updated**: May 25, 2024

Good luck with ETHEATRO! 🎭
