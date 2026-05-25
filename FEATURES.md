# ETHEATRO System - Features & User Guide

## System Overview

ETHEATRO is a complete audition management system for USTP that streamlines the registration, scheduling, and management of student auditions.

---

## 🎓 Student Features

### Registration & Authentication

#### Register for Auditions
- **Location**: `/auth/register.php`
- **Required Fields**:
  - School ID (unique)
  - First Name
  - Middle Initial (optional)
  - Last Name
  - Year Level
  - Department
  - Talent/Skill
  - Email (unique)
  - Password (min 6 characters)

- **Process**:
  1. Fill out registration form
  2. Verify all information
  3. Create account
  4. Status automatically set to "Pending"
  5. Wait for admin approval

#### Login
- **Location**: `/auth/login.php`
- **Required**: Email and Password
- **Features**:
  - Remember login details
  - Forgot password option
  - Secure password hashing

---

### Student Dashboard

#### Overview
- **Location**: `/students/dashboard.php`
- **Features**:
  - Welcome message with name
  - Current audition status
  - Pending registrations count
  - Latest announcements
  - Quick status overview

#### Status Indicators
- **Pending**: Application under review
- **Ready to Audition**: Date scheduled, prepare for audition
- **Approved**: Congratulations! You've been accepted
- **Rejected**: Application unsuccessful, feedback available

---

### Profile Management

#### View Profile
- **Location**: `/students/profile.php`
- **Displays**:
  - Personal information
  - Department and year level
  - School ID and email
  - Current audition status
  - Scheduled audition date (if available)
  - Feedback from judges (if available)
  - Registration date

#### Edit Profile
- **Location**: `/students/edit.php`
- **Editable Fields**:
  - First Name
  - Middle Initial
  - Last Name
  - Year Level
  - Department
  - Talent/Skill

- **Not Editable**:
  - School ID (contact admin)
  - Email address (contact admin)

---

### Notifications System

#### View Notifications
- **Location**: `/students/notification.php`
- **Features**:
  - Status update notifications
  - Announcement notifications
  - Schedule reminders
  - Audition date confirmations
  - Feedback notifications

- **Notification Types**:
  - 🔄 **Status Update**: Changes to audition status
  - 📢 **Announcement**: Important system announcements
  - 📅 **Schedule**: Audition date and time updates

#### Notification Badges
- Red badge shows unread notification count
- Click "Mark as read" to mark individual notifications
- "Mark all as read" option available

#### Archive
- **Location**: `/students/archive.php`
- **Features**:
  - Keep track of audition history
  - View past applications
  - Archive management

---

## 👨‍💼 Admin Features

### Admin Dashboard

#### Overview
- **Location**: `/admin/` (redirects from student dashboard if admin)
- **Statistics**:
  - Total pending applications
  - Students ready to audition
  - Approved applicants
  - Rejected applicants

#### Quick Access
- Links to all admin functions
- Statistics at a glance
- System status overview

---

### Manage Registrants

#### Review Applications
- **Location**: `/admin/manageregistrants.php`
- **Features**:
  - View all student registrations
  - Search by name, email, or school ID
  - Filter by status
  - Approve or reject students
  - View complete application details

#### Actions for Pending Applications
- ✅ **Approve for Audition**: Move to "Ready to Audition" status
- ❌ **Reject**: Move to "Rejected" status

#### Search & Filter
- **Search**: By name, email, school ID
- **Filter**: By current status
- **Combine**: Use together for precise results

---

### Manage Auditions

#### Schedule Auditions
- **Location**: `/admin/manage_auditionees.php`
- **For "Ready to Audition" Students**:
  1. Click "Manage" button
  2. Select audition date and time
  3. Confirm scheduling
  4. Student automatically notified

#### Record Results
- **For Students Who Auditioned**:
  1. Click "Manage" button
  2. Select "Approve" or "Reject"
  3. Add feedback (optional but recommended)
  4. Submit

#### Feedback
- Provide constructive feedback for:
  - Approved students
  - Rejected students
- Helps students understand decision

#### Statistics
- Real-time count of:
  - Students ready to audition
  - Approved students
  - Rejected students

---

### Announcements System

#### Post Announcements
- **Location**: `/admin/announcements.php`
- **Features**:
  - Write important messages
  - Auto-broadcast to all students
  - Track announcement history
  - Delete old announcements

#### Announcement Types
Typical announcements:
- Audition schedule changes
- Important deadlines
- System maintenance
- Congratulations messages
- Event updates

#### Student Notifications
- All students receive notifications
- Messages appear in their notification panel
- Can mark as read

---

## 🎨 User Interface Features

### Design & Branding
- **Colors**: USTP Official Colors
  - Primary Blue: `#003DA5`
  - Gold/Yellow: `#FFD700`
  - White: `#FFFFFF`
  
- **Framework**: Bootstrap 5
- **Responsive**: Works on all devices
- **Icons**: Font Awesome icons throughout
- **Animations**: Smooth transitions and effects

### Navigation
- **Navbar**: All pages have consistent navigation
- **Quick Links**: Easy access to main sections
- **Breadcrumbs**: Clear navigation path
- **Mobile Menu**: Responsive hamburger menu

### Accessibility
- Clean, readable interface
- High contrast colors
- Keyboard navigation support
- Form labels and descriptions
- Error messages clearly displayed

---

## 🔐 Security Features

### Authentication
- Secure password hashing (bcrypt)
- Session-based login
- Automatic logout
- Remember login option

### Data Protection
- SQL injection prevention
- XSS protection
- Input validation
- Secure password recovery

### Access Control
- Role-based access (Student/Admin)
- Protected pages require login
- Students can only view own data
- Admins have full system access

---

## 📊 Database Tables

### Auditionees Table
```
Stores all student and admin accounts
- ID, School ID, Name (First/Middle/Last)
- Year Level, Department, Talent
- Email, Password (hashed)
- Role (student/admin)
- Status (Pending/Ready/Approved/Rejected)
- Audition Date, Feedback
- Created/Updated timestamps
```

### Announcements Table
```
Stores all system announcements
- ID, Message
- Created by (admin ID)
- Created timestamp
```

### Notifications Table
```
Stores individual student notifications
- ID, Auditionee ID
- Message, Type (status_update/announcement/schedule)
- Is Read flag
- Created timestamp
```

---

## 📱 Mobile Features

- Full responsive design
- Touch-friendly buttons
- Mobile-optimized navigation
- Fast loading on mobile networks
- Readable fonts on small screens

---

## 🚀 Performance Features

- Optimized database queries
- Cached assets
- Minified CSS/JavaScript
- Lazy loading images
- Session optimization

---

## 🔄 Workflow Examples

### Complete Student Audition Workflow

```
1. Student registers at /auth/register.php
   ↓
2. Status set to "Pending"
   ↓
3. Student sees "Pending" in dashboard
   ↓
4. Admin reviews at /admin/manageregistrants.php
   ↓
5. Admin clicks "Approve for Audition"
   ↓
6. Student notified automatically
   ↓
7. Status changes to "Ready to Audition"
   ↓
8. Admin schedules at /admin/manage_auditionees.php
   ↓
9. Student sees audition date in profile
   ↓
10. Student attends audition
    ↓
11. Admin records result (Approve/Reject) with feedback
    ↓
12. Student notified of final result
    ↓
13. Status becomes "Approved" or "Rejected"
```

### Admin Broadcasting Workflow

```
1. Admin goes to /admin/announcements.php
   ↓
2. Admin writes announcement message
   ↓
3. Admin clicks "Post Announcement"
   ↓
4. Message auto-sent to all students
   ↓
5. All students see notification badge
   ↓
6. Students view in notification panel
   ↓
7. Admin can see history and delete old ones
```

---

## 🛠️ Customization Options

### For Administrators
- Change announcement messages
- Schedule audition dates
- Approve/reject registrations
- Add feedback to students

### For Developers
- Modify color scheme (CSS variables)
- Add new fields to forms
- Extend database schema
- Add new admin features
- Integrate with email system

---

## ⚠️ Important Notes

### For New Admins
- Change default admin password immediately
- Review all pending applications regularly
- Post announcements about audition schedules
- Keep feedback constructive and helpful

### For New Students
- Complete all required fields in registration
- Check notifications regularly
- Update profile if information changes
- Prepare well before scheduled audition

### For System Maintenance
- Regular database backups
- Monitor error logs
- Update PHP and dependencies
- Test new features before deploying

---

## 📧 Contact & Support

For technical issues:
1. Check SETUP_GUIDE.md for troubleshooting
2. Review error messages carefully
3. Check browser console for errors
4. Contact your system administrator

---

## Version History

### Version 1.0 (May 2024)
- Initial release
- Complete student registration system
- Admin audition management
- Real-time notifications
- Announcement system
- USTP branding

---

## License

Copyright © 2024 University of Science and Technology of Southern Philippines

---

**Last Updated**: May 25, 2024
**Next Review**: August 2024
