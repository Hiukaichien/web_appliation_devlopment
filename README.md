# NEUC Web Application Development Project

This project is a group assignment for the Web Application Development course at New Era University College (NEUC). It implements a PHP & MySQL-based admin system focused on **staff-side functionalities** such as product management, user management, order management, and message management.

> **Note:** Customer-related pages (such as customer login and customer-facing features) are not the main focus of this project. The primary scope is on staff/admin functionalities.

## Project Structure

```
WAD_Project/
├── about-us.php                # About Us page
├── crud-product.php            # Product CRUD page (staff)
├── DBhelper.php                # Database helper class
├── index.php                   # Dashboard home (staff)
├── order-list.php              # Order list page (staff)
├── pages-crud-staff.php        # Staff management page
├── pages-crud-user.php         # User management page (staff)
├── pages-faq.php               # FAQ page
├── pages-received-message.php  # Message management page (staff)
├── staff-profile.php           # Staff profile page
├── StaffLoginPage.php          # Staff login page
├── update-status.php           # Status update endpoint
├── assets/
│   ├── css/
│   │   └── style.css           # Main stylesheet
│   ├── img/                    # Image assets
│   ├── js/                     # Custom JS
│   ├── scss/                   # SCSS source files
│   └── vendor/                 # Third-party libraries (Bootstrap, TinyMCE, Echarts, etc.)
├── forms/
│   ├── contact.php             # Contact form handler
│   └── Readme.txt
└── groupassignment.sql         # Database schema and sample data
```

## Main Features (Staff Side)

- **Staff Login**: Secure login for staff/admin users.
- **Product Management**: CRUD operations for products, including image upload.
- **User Management**: Staff can manage customer information.
- **Staff Management**: Admin can manage staff accounts.
- **Order Management**: View and update order status.
- **Message Management**: View messages sent via the contact form, with read/unread status.
- **FAQ/About Us**: Static information pages.

## Deployment Instructions

1. **Requirements**
   - PHP 7.4+
   - MySQL 5.7+/MariaDB
   - Web server (Apache/Nginx)

2. **Database Setup**
   - Import the [groupassignment.sql](groupassignment.sql) file to create the database and sample data.

3. **Database Configuration**
   - Edit the database connection settings in [`DBhelper.php`](WAD_Project/DBhelper.php) to match your environment.

4. **Access Point**
   - Staff backend: [`StaffLoginPage.php`](WAD_Project/StaffLoginPage.php)

5. **Third-party Libraries**
   - All required frontend libraries are included in [`assets/vendor`](WAD_Project/assets/vendor).

## Technology Stack

- Frontend: Bootstrap 5, Quill, TinyMCE, Echarts, Chart.js
- Backend: PHP
- Database: MySQL/MariaDB

## Credits

- [BootstrapMade NiceAdmin Template](https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/)
- [TinyMCE Rich Text Editor](https://www.tiny.cloud/)
- [Echarts Data Visualization](https://echarts.apache.org/)

---

For further development or deployment questions, please contact the project
