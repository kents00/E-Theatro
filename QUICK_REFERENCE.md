# ETHEATRO System - Quick Reference

## 🌐 Main Pages

### Public Pages (No Login Required)
| URL | Purpose | Description |
|-----|---------|-------------|
| `/Etheatro/` | Landing Page | Welcome, features, how it works |
| `/Etheatro/auth/login.php` | Login | Student/Admin login |
| `/Etheatro/auth/register.php` | Registration | New student registration |
| `/Etheatro/auth/forgot_password.php` | Password Recovery | Reset forgot password |

---

### Student Pages (Login Required)
| URL | Page Name | Purpose | Access |
|-----|-----------|---------|--------|
| `/Etheatro/students/dashboard.php` | Student Dashboard | Main student hub, status overview | Students |
| `/Etheatro/students/profile.php` | View Profile | See complete profile info | Students |
| `/Etheatro/students/edit.php` | Edit Profile | Update personal information | Students |
| `/Etheatro/students/notification.php` | Notifications | View all notifications | Students |
| `/Etheatro/students/archive.php` | Archive | Historical records | Students |

---

### Admin Pages (Admin Login Required)
| URL | Page Name | Purpose | Access |
|-----|-----------|---------|--------|
| `/Etheatro/admin/manageregistrants.php` | Manage Registrants | Review applications | Admins |
| `/Etheatro/admin/manage_auditionees.php` | Manage Auditions | Schedule & record results | Admins |
| `/Etheatro/admin/announcements.php` | Announcements | Post system announcements | Admins |

---

## 🔐 Default Test Accounts

### Admin Account
```
Email: admin@example.com
Password: password123
Role: Administrator
```

### Student Account
```
Email: juan@example.com
Password: password123
Role: Student
Status: Pending
```

**⚠️ Change these immediately after first login!**

---

## 📋 User Roles & Permissions

### Student Role
- ✅ View own profile
- ✅ Edit own profile
- ✅ View notifications
- ✅ View announcements
- ✅ View audition status
- ❌ Cannot manage other students
- ❌ Cannot post announcements
- ❌ Cannot approve/reject

### Admin Role
- ✅ View all registrations
- ✅ Approve/reject students
- ✅ Schedule auditions
- ✅ Record audition results
- ✅ Post announcements
- ✅ View all notifications
- ✅ Access admin dashboard
- ❌ Cannot delete user data
- ❌ Cannot modify past auditions

---

## 🎯 Status Meanings

| Status | Meaning | Student Can See | Admin Can Do |
|--------|---------|-----------------|--------------|
| **Pending** | Awaiting review | Yes | Approve or Reject |
| **Ready to Audition** | Date scheduled | Yes with date | Record results |
| **Approved** | Passed audition | Yes with feedback | Change to Rejected |
| **Rejected** | Not accepted | Yes with feedback | Change to Approved |

---

## 📧 Notification Types

| Type | Triggered By | Sent To |
|------|--------------|---------|
| Status Update | Admin changes status | That student |
| Announcement | Admin posts message | All students |
| Schedule | Admin sets audition date | That student |

---

## 🛠️ Configuration Files

| File | Purpose | Edit? |
|------|---------|-------|
| `config/db.php` | Database connection | ✅ On first setup |
| `assets/css/style.css` | Styling & colors | ✅ For customization |
| `assets/js/script.js` | Client-side logic | ⚠️ If needed |
| `database/audition_system.sql` | Database schema | ❌ After import |

---

## 🔧 Common Tasks

### For Students

**Task**: View audition status
1. Login at `/auth/login.php`
2. Go to `/students/dashboard.php`
3. Check "Current Status" section

**Task**: Update phone number or address
1. Go to `/students/edit.php`
2. Scroll to talent field
3. Update information
4. Click "Save Changes"

**Task**: Check if audition is scheduled
1. Go to `/students/profile.php`
2. Look for "Audition Date" field
3. If empty, still waiting for schedule

### For Admins

**Task**: Approve student for audition
1. Go to `/admin/manageregistrants.php`
2. Find student in "Pending" list
3. Click green checkmark (✓)
4. Student automatically notified

**Task**: Schedule audition date
1. Go to `/admin/manage_auditionees.php`
2. Find student in list
3. Click "Manage" button
4. Select date and time
5. Click "Schedule"
6. Student notified automatically

**Task**: Post announcement
1. Go to `/admin/announcements.php`
2. Write message in text area
3. Click "Post Announcement"
4. All students instantly notified

---

## 🚨 Troubleshooting Quick Links

| Problem | Solution |
|---------|----------|
| Can't login | Check email/password, create account first |
| Can't register | Email already exists, use different email |
| Database error | Check `config/db.php` settings |
| Page not found | Check URL spelling, ensure logged in |
| Password forgotten | Click "Forgot password" on login page |
| Notifications not showing | Refresh page, check browser cache |

---

## 📱 Mobile Access

All pages work on mobile devices:
- Responsive design
- Touch-friendly buttons
- Mobile menu (hamburger icon)
- Auto-scaling text

Access from: `http://localhost/Etheatro/`

---

## 🎨 USTP Colors Used

```css
Primary Blue: #003DA5
Dark Blue: #002066
Gold/Yellow: #FFD700
White: #FFFFFF
Light Gray: #f8f9fa
```

These colors appear in:
- Navigation bars
- Buttons
- Headers
- Cards
- Status badges

---

## 📊 Database Tables

### auditionees
Stores all user accounts (students & admins)
- 11 columns: id, school_id, name fields, credentials, role, status, etc.
- Unique: school_id, email
- Indexed: school_id, email

### announcements
Stores system announcements
- 4 columns: id, message, created_by (admin), created_at

### notifications
Stores individual notifications per student
- 5 columns: id, auditionee_id, message, type, is_read, created_at

---

## 🔐 Password Security

### Password Requirements
- Minimum 6 characters
- Stored as bcrypt hash (never plain text)
- Reset available if forgotten

### Changing Password
- Not currently available in student panel
- Admin can reset manually in database
- Use password reset form on login page

---

## 📞 Support Contacts

**System Administrator**
- Email: admin@example.com (update this)

**Help Desk**
- Available during school hours
- Email or visit office

**Technical Support**
- For developers/IT staff
- Check error logs in XAMPP

---

## 📈 Statistics Available

### For Admins
- Total registrations
- Pending applications
- Ready to audition count
- Approved candidates
- Rejected candidates

### For Students
- Current status
- Scheduled audition date
- Recent announcements

---

## 🎓 Getting Help

1. **Read Documentation**
   - README.md - Full documentation
   - SETUP_GUIDE.md - Installation help
   - FEATURES.md - Feature descriptions

2. **Check Common Issues**
   - SETUP_GUIDE.md - Troubleshooting section
   - This Quick Reference

3. **Ask Administrator**
   - Report bugs with details
   - Ask about features
   - Request customizations

---

## 📅 Important Dates

- **Audition Registration**: Open
- **Application Review**: Ongoing
- **Audition Schedule**: Posted individually
- **Results**: After audition

---

## ✅ Checklist for New Users

### First Time Students
- [ ] Create account at `/auth/register.php`
- [ ] Verify all information is correct
- [ ] Login at `/auth/login.php`
- [ ] View dashboard at `/students/dashboard.php`
- [ ] Wait for admin approval
- [ ] Check notifications for updates

### First Time Admins
- [ ] Change admin password
- [ ] Review `/admin/manageregistrants.php`
- [ ] Approve deserving applications
- [ ] Schedule auditions at `/admin/manage_auditionees.php`
- [ ] Post announcements at `/admin/announcements.php`

---

**Last Updated**: May 25, 2024
**Version**: 1.0

For more information, see the full README.md documentation.
