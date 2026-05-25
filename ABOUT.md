# 🎭 ETHEATRO - Audition System

## What is ETHEATRO?

**ETHEATRO** is a comprehensive **Digital Audition Management System** designed for educational institutions to streamline and manage student auditions for theatrical productions. This system digitizes the entire audition process - from registration through final approval - making it easier for administrators and students to participate in auditions.

Built for the **University of Science and Technology of Southern Philippines (USTP)**, ETHEATRO is a web-based application that eliminates paper-based processes and provides real-time status updates.

---

## Purpose & Objectives

### Primary Goals:
- 🎯 **Simplify Registration** - Students can easily register for auditions online
- 📋 **Streamline Management** - Administrators can efficiently review and manage audition applications
- 📊 **Track Status** - Real-time status updates for students throughout the audition process
- 🔔 **Automated Notifications** - Instant notifications about application status changes and announcements
- 📱 **User-Friendly Interface** - Intuitive design for both students and administrators

---

## Key Features

### For Students:
✅ **User Registration** - Create account with school ID and personal details  
✅ **Audition Application** - Submit audition registrations with talent information  
✅ **Status Tracking** - Monitor application status in real-time  
✅ **Notifications** - Receive updates about application decisions and announcements  
✅ **Profile Management** - View and update personal information  

### For Administrators:
✅ **Dashboard Overview** - Quick statistics on registrations and auditions  
✅ **Application Review** - Review student registrations and make decisions  
✅ **Audition Management** - Schedule auditions and record results  
✅ **Status Updates** - Approve, reject, or request more information  
✅ **Announcements** - Broadcast important messages to all students  
✅ **Notifications** - Automatic notifications sent to students on status changes  

---

## How It Works

### Student Journey:
```
1. REGISTER
   └─ Create account with school ID, name, department, talent info

2. SUBMIT APPLICATION
   └─ Complete audition registration form

3. AWAIT REVIEW
   └─ Admin reviews application (status: Pending)

4. GET APPROVED
   └─ If approved → Status: Ready to Audition

5. ATTEND AUDITION
   └─ Admin schedules and records audition

6. RECEIVE DECISION
   └─ Final approval or rejection notification
```

### Admin Workflow:
```
1. REVIEW APPLICATIONS
   └─ View all pending registrations

2. APPROVE/REJECT
   └─ Accept students for auditions or reject applications

3. SCHEDULE AUDITIONS
   └─ Assign audition dates and times

4. CONDUCT AUDITIONS
   └─ Record audition feedback and performance

5. MAKE FINAL DECISION
   └─ Approve or reject based on audition results

6. NOTIFY STUDENTS
   └─ Send automatic notifications about decisions
```

---

## User Roles

### 👤 Student
- Register for auditions
- View application status
- Access notifications
- Update profile information

### 👨‍💼 Administrator
- Review and approve/reject student applications
- Schedule auditions
- Record audition results and feedback
- Post announcements to students
- View system statistics and reports

---

## System Architecture

### Technology Stack:
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8.2
- **Database**: MySQL
- **Server**: Apache/XAMPP
- **Security**: Password hashing (bcrypt), SQL injection prevention

### Database Structure:
- **auditionees** - Student registration data
- **notifications** - Student notifications and announcements
- **announcements** - System-wide announcements

---

## File Structure

```
Etheatro/
├── config/
│   └── db.php                    # Database connection
├── auth/
│   ├── login.php                 # Student/Admin login
│   ├── register.php              # Student registration
│   └── forgot_password.php       # Password recovery
├── students/
│   ├── dashboard.php             # Student dashboard
│   ├── profile.php               # Profile management
│   ├── edit.php                  # Edit profile
│   ├── notification.php          # View notifications
│   └── archive.php               # Archived applications
├── admin/
│   ├── dashboard.php             # Admin dashboard
│   ├── manageregistrants.php     # Review applications
│   ├── manage_auditionees.php    # Manage auditions
│   └── announcements.php         # Post announcements
├── assets/
│   ├── css/
│   │   └── style.css             # Main stylesheet
│   └── js/
│       └── script.js             # JavaScript functions
├── database/
│   └── audition_system.sql       # Database schema
├── api.php                        # API endpoints
├── index.php                      # Home page
└── README.md                      # Setup instructions
```

---

## Getting Started

### Installation Steps:
1. **Setup Database** - Import `database/audition_system.sql` into MySQL
2. **Configure Database** - Update credentials in `config/db.php`
3. **Start Server** - Run XAMPP and access via `http://localhost/Etheatro`
4. **Default Login**:
   - Email: `admin@example.com`
   - Password: `password123`

### Access Points:
- **Home**: `http://localhost/Etheatro/`
- **Student Login**: `http://localhost/Etheatro/auth/login.php`
- **Student Dashboard**: `http://localhost/Etheatro/students/dashboard.php`
- **Admin Dashboard**: `http://localhost/Etheatro/admin/dashboard.php`

---

## Security Features

🔒 **Password Protection** - Bcrypt hashing for secure passwords  
🔐 **SQL Injection Prevention** - Parameterized queries and input escaping  
🚪 **Session Management** - Secure user authentication and logout  
✅ **Input Validation** - All user inputs validated before processing  
📧 **Email Validation** - Valid email format checking  

---

## Benefits

### For Students:
- 📱 Easy access anytime, anywhere
- 🔔 Real-time status updates
- 📊 Track audition progress
- 🎯 Clear guidance on next steps

### For Administrators:
- ⚡ Faster processing of applications
- 📈 Better organization and tracking
- 🤖 Automated notifications reduce manual work
- 📊 Data insights and statistics
- 💾 Organized record-keeping

### For Institution:
- 🎓 Professional audition management
- 📋 Transparent process
- 📊 Data-driven decision making
- 🌐 Modern digital infrastructure

---

## Support & Contact

For technical issues or questions regarding ETHEATRO:
- Contact: Admin Dashboard
- Email: postmaster@localhost
- Institution: University of Science and Technology of Southern Philippines

---

## Version Information

- **System**: ETHEATRO Audition System v1.0
- **Created**: 2024
- **Last Updated**: May 2026
- **PHP Version**: 8.2+
- **MySQL Version**: 5.7+

---

## License & Usage

ETHEATRO is developed for educational purposes at USTP. All rights reserved.

---

**Welcome to ETHEATRO! 🎭**  
*Transforming Auditions into Digital Excellence*
