
```markdown
# 🚀 Professional CV Management System

> A comprehensive Laravel-based platform for creating, managing, and showcasing professional CVs with multiple templates, user management, and advanced analytics.

[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-FF2D20.svg?style=flat&logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.1+-777BB4.svg?style=flat&logo=php)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1.svg?style=flat&logo=mysql)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3.svg?style=flat&logo=bootstrap)](https://getbootstrap.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## 📋 **Table of Contents**

- [Overview](#-overview)
- [Features](#-features)
- [Technology Stack](#-technology-stack)
- [Installation Guide](#-installation-guide)
- [Database Structure](#-database-structure)
- [Modules & Functionality](#-modules--functionality)
- [User Roles & Permissions](#-user-roles--permissions)
- [Screenshots](#-screenshots)
- [API Documentation](#-api-documentation)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-contact)

---

## 🎯 **Overview**

**CV Management System** is a full-featured web application built with Laravel that allows users to create, manage, and share professional CVs. The platform provides multiple design templates, real-time preview, PDF export, and comprehensive admin controls for user and content management.

**Target Audience:**
- Job seekers creating professional CVs
- HR professionals managing candidate profiles
- Admin users overseeing the platform
- Freelancers showcasing their portfolio

**Live Demo:** [View Live Demo](#)  
**Documentation:** [Wiki](#)

---

## ✨ **Features**

### 🔐 **Authentication & Authorization**
- ✅ Secure login/register system with Laravel Breeze
- ✅ Role-based access control (Admin / User)
- ✅ Email verification support
- ✅ Password reset functionality
- ✅ Remember me feature

### 🎨 **CV Templates**
- ✅ **5+ Professional Templates** (Modern, Minimal, Creative, Professional, Sidebar, Classic)
- ✅ **Real-time Preview** of CV changes
- ✅ **Color Customization** for each template
- ✅ **Dynamic Template Management** (Add, Edit, Delete templates)
- ✅ **Live Preview** during template editing
- ✅ **PDF Export** with dompdf integration

### 📊 **Dashboard & Analytics**
- ✅ Interactive dashboard with **real-time statistics**
- ✅ **Advanced Analytics** with Chart.js
- ✅ **User growth charts** (monthly/ yearly)
- ✅ **Project distribution** by category
- ✅ **Skills level distribution**
- ✅ **Activity tracking** and recent activity log
- ✅ **Export reports** (PDF, Excel, CSV, JSON, PNG)

### 👥 **User Management (Clients)**
- ✅ Full CRUD operations for users
- ✅ **Admin-only** user management panel
- ✅ User role assignment (Admin / User)
- ✅ Profile management with avatar upload
- ✅ CV template assignment per user

### 💬 **Messaging System (Chat)**
- ✅ **Real-time chat** between users (Admin ↔ User, User ↔ User)
- ✅ **WhatsApp-style interface** with conversation list
- ✅ **Unread message indicators**
- ✅ **Mark all as read** functionality
- ✅ **Search conversations** and filter by status
- ✅ **AJAX-based** dynamic message loading
- ✅ **Delete conversations** and individual messages

### 📝 **Project Management**
- ✅ Create, edit, delete projects
- ✅ Upload project images
- ✅ Assign projects to users (Admin)
- ✅ Category filtering (Laravel/PHP, Web, Java/Flutter, C++)
- ✅ Active/Inactive status toggle
- ✅ Sort order management

### 🛠️ **Skill Management**
- ✅ Add, edit, delete skills
- ✅ Skill level (0-100%)
- ✅ Category classification (Frontend, Backend, Database, DevOps, Mobile, Other)
- ✅ Active/Inactive status toggle

### 💼 **Experience Management**
- ✅ Add, edit, delete work experiences
- ✅ Company, job title, start/end dates
- ✅ Description and achievements
- ✅ Sort order management
- ✅ Active/Inactive status toggle

### 🎓 **Education Management**
- ✅ Add, edit, delete education records
- ✅ Degree, university, start/end dates
- ✅ Description fields
- ✅ Sort order management
- ✅ Active/Inactive status toggle

### 🔗 **Social Links Management**
- ✅ Add, edit, delete social links
- ✅ Multiple platforms (GitHub, LinkedIn, Twitter, Facebook, Instagram, YouTube, WhatsApp, Telegram)
- ✅ Active/Inactive status toggle

### ⚙️ **Settings Management**
- ✅ **General Settings** (Site name, title, description, keywords)
- ✅ **Appearance Settings** (Primary, secondary, accent colors)
- ✅ **CV Theme Customizer** (CV primary, secondary, accent colors)
- ✅ **Social Links Configuration**
- ✅ **Email Settings** (SMTP configuration)
- ✅ **Advanced Settings** (Custom CSS/JS, Google Analytics)
- ✅ **Maintenance Mode** toggle

### 🌙 **Dark/Light Theme**
- ✅ **Persistent dark/light mode** with localStorage
- ✅ System preference detection
- ✅ Smooth transitions between themes

### 🔍 **Global Search**
- ✅ Quick search across all modules
- ✅ Keyboard shortcuts (Ctrl+K)
- ✅ Search results in real-time

### 📱 **Responsive Design**
- ✅ Fully responsive for all devices
- ✅ Mobile-friendly sidebar navigation
- ✅ Touch-friendly UI elements

---

## 🛠️ **Technology Stack**

### **Backend**
| Technology | Version | Purpose |
|------------|---------|---------|
| Laravel | 10.x | PHP Framework |
| PHP | 8.1+ | Backend Language |
| MySQL | 8.0+ | Database |
| Laravel Breeze | Latest | Authentication |
| DomPDF | 2.x | PDF Generation |
| Laravel UI | Latest | Frontend Scaffolding |

### **Frontend**
| Technology | Version | Purpose |
|------------|---------|---------|
| Bootstrap | 5.3 | CSS Framework |
| Chart.js | 4.4 | Data Visualization |
| CodeMirror | 5.65 | Code Editor |
| jQuery | 3.7 | JavaScript Library |
| HTML/CSS/JS | - | Core Technologies |

### **Tools & Services**
| Tool | Purpose |
|------|---------|
| Composer | Dependency Management |
| NPM | Asset Management |
| Git | Version Control |
| VS Code | IDE |

---

## 📦 **Installation Guide**

### **Prerequisites**
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM

### **Step 1: Clone the Repository**
```bash
git clone https://github.com/your-username/cv-management-system.git
cd cv-management-system
```

### **Step 2: Install Dependencies**
```bash
composer install
npm install
```

### **Step 3: Environment Configuration**
```bash
cp .env.example .env
```

Update `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### **Step 4: Generate Application Key**
```bash
php artisan key:generate
```

### **Step 5: Run Migrations & Seeders**
```bash
php artisan migrate --seed
```

### **Step 6: Create Storage Link**
```bash
php artisan storage:link
```

### **Step 7: Build Assets**
```bash
npm run build
```

### **Step 8: Start Development Server**
```bash
php artisan serve
```

### **Default Admin Credentials**
```
Email: admin@example.com
Password: password
```

### **Default User Credentials**
```
Email: user@example.com
Password: password
```

---

## 🗄️ **Database Structure**

### **Core Tables**

| Table | Description |
|-------|-------------|
| `users` | User accounts and authentication |
| `profiles` | User profile information |
| `projects` | Portfolio projects |
| `skills` | Skills and proficiency levels |
| `experiences` | Work experience records |
| `education` | Education background |
| `social_links` | Social media profiles |
| `messages` | Chat messages between users |
| `conversations` | Chat conversations |
| `cv_templates` | CV design templates |
| `settings` | Application settings |

### **Relationships**
```
users
  ├── profile (one-to-one)
  ├── projects (one-to-many)
  ├── skills (one-to-many)
  ├── experiences (one-to-many)
  ├── education (one-to-many)
  ├── social_links (one-to-many)
  ├── conversations (one-to-many)
  ├── messages (one-to-many)
  └── cv_templates (belongs-to)
```

---

## 📂 **Modules & Functionality**

### **1. Authentication (Breeze)**
```
/auth
  ├── /login          # User login
  ├── /register       # User registration
  ├── /forgot-password # Password reset
  └── /verify-email   # Email verification
```

### **2. Dashboard**
```
/dashboard
  ├── /index          # Main dashboard with stats
  ├── /profile/edit   # Profile management
  ├── /settings       # Application settings
  └── /analytics      # Advanced analytics
```

### **3. Projects**
```
/dashboard/projects
  ├── /index          # List all projects
  ├── /create         # Create new project
  ├── /{id}/edit      # Edit project
  ├── /{id}/show      # View project
  └── /{id}/toggle    # Toggle active status
```

### **4. Skills**
```
/dashboard/skills
  ├── /index          # List all skills
  ├── /create         # Create new skill
  ├── /{id}/edit      # Edit skill
  ├── /{id}/show      # View skill
  └── /{id}/toggle    # Toggle active status
```

### **5. Experiences**
```
/dashboard/experiences
  ├── /index          # List all experiences
  ├── /create         # Create new experience
  ├── /{id}/edit      # Edit experience
  ├── /{id}/show      # View experience
  └── /{id}/toggle    # Toggle active status
```

### **6. Education**
```
/dashboard/education
  ├── /index          # List all education
  ├── /create         # Create new education
  ├── /{id}/edit      # Edit education
  ├── /{id}/show      # View education
  └── /{id}/toggle    # Toggle active status
```

### **7. Social Links**
```
/dashboard/social-links
  ├── /index          # List all social links
  ├── /create         # Create new social link
  ├── /{id}/edit      # Edit social link
  ├── /{id}/show      # View social link
  └── /{id}/toggle    # Toggle active status
```

### **8. Messages (Chat System)**
```
/dashboard/messages
  ├── /index          # Chat interface
  ├── /conversations  # Get conversations (AJAX)
  ├── /{id}/get       # Get messages (AJAX)
  ├── /send           # Send message (AJAX)
  ├── /start          # Start conversation (AJAX)
  ├── /unread-count   # Get unread count (AJAX)
  └── /{id}/delete    # Delete conversation
```

### **9. CV Templates**
```
/dashboard/cv-templates
  ├── /index          # List all templates
  ├── /create         # Create new template
  ├── /{id}/edit      # Edit template
  ├── /{id}/show      # View template
  ├── /{id}/toggle    # Toggle active status
  ├── /{id}/set-default # Set as default
  └── /{id}/preview   # Preview template
```

### **10. Clients (User Management - Admin Only)**
```
/dashboard/clients
  ├── /index          # List all users
  ├── /create         # Create new user
  ├── /{id}/edit      # Edit user
  ├── /{id}/show      # View user profile
  ├── /{id}/delete    # Delete user
  ├── /{id}/download-cv # Download user CV
  └── /{id}/preview-cv # Preview user CV
```

### **11. Resume**
```
/dashboard/resume
  ├── /index          # Template selection
  ├── /save-template  # Save template preference
  ├── /preview/{slug} # Preview CV
  └── /download/{slug}# Download PDF
```

### **12. Settings**
```
/dashboard/settings
  ├── /index          # Settings dashboard
  ├── /update         # Update settings
  ├── /reset          # Reset to default
  └── /test-email     # Test email configuration
```

---

## 👤 **User Roles & Permissions**

| Feature | Admin | User |
|---------|-------|------|
| View Dashboard | ✅ | ✅ |
| Edit Profile | ✅ | ✅ |
| Manage Projects | ✅ (All users) | ✅ (Own only) |
| Manage Skills | ✅ (All users) | ✅ (Own only) |
| Manage Experiences | ✅ (All users) | ✅ (Own only) |
| Manage Education | ✅ (All users) | ✅ (Own only) |
| Manage Social Links | ✅ (All users) | ✅ (Own only) |
| Manage Users | ✅ | ❌ |
| Manage CV Templates | ✅ | ❌ |
| View Messages | ✅ (All) | ✅ (Own only) |
| Send Messages | ✅ | ✅ |
| View Analytics | ✅ (All data) | ✅ (Own data) |
| Manage Settings | ✅ | ❌ |

---

## 📸 **Screenshots**

### 🏠 **Landing Page**
![Landing Page](public/images/screenshots/landing.png)

### 📊 **Dashboard**
![Dashboard](public/images/screenshots/dashboard.png)

### 🎨 **CV Templates**
![CV Templates](public/images/screenshots/templates.png)

### 💬 **Chat System**
![Chat System](public/images/screenshots/chat.png)

### 📈 **Analytics**
![Analytics](public/images/screenshots/analytics.png)

### ⚙️ **Settings**
![Settings](public/images/screenshots/settings.png)

---

## 🔌 **API Documentation**

### **Authentication Endpoints**
```
POST   /login              # User login
POST   /register           # User registration
POST   /logout             # User logout
POST   /forgot-password    # Password reset request
POST   /reset-password     # Password reset
```

### **Dashboard Endpoints**
```
GET    /dashboard          # Main dashboard
GET    /dashboard/analytics # Analytics data
```

### **CV Endpoints**
```
GET    /cv/{username}      # Public CV view
GET    /cv/{username}/download # Download CV as PDF
```

### **Message Endpoints (AJAX)**
```
GET    /dashboard/messages/conversations    # Get conversations
GET    /dashboard/messages/{id}/get         # Get messages
POST   /dashboard/messages/send             # Send message
POST   /dashboard/messages/start            # Start conversation
GET    /dashboard/messages/unread-count     # Get unread count
DELETE /dashboard/messages/{id}/delete      # Delete conversation
```

---

## 🤝 **Contributing**

We welcome contributions! Please follow these steps:

1. **Fork** the repository
2. **Clone** your fork
```bash
git clone https://github.com/your-username/cv-management-system.git
```

3. **Create** a feature branch
```bash
git checkout -b feature/amazing-feature
```

4. **Commit** your changes
```bash
git commit -m 'Add some amazing feature'
```

5. **Push** to the branch
```bash
git push origin feature/amazing-feature
```

6. **Create** a Pull Request

### **Coding Standards**
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document your code
- Update README if needed

---

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 📞 **Contact**

**Mo'men Sarsour**
- 📧 Email: [momensarsour5@gmail.com](mailto:momensarsour5@gmail.com)
- 🔗 GitHub: [Momen9Sarsour](https://github.com/Momen9Sarsour)
- 💼 LinkedIn: [Mo'men Sarsour](https://linkedin.com/in/momen-sarsour)

**Project Links:**
- 🌐 Live Demo: [View Demo](https://your-demo-url.com)
- 📚 Documentation: [Wiki](https://github.com/your-username/cv-management-system/wiki)
- 🐛 Issue Tracker: [Issues](https://github.com/your-username/cv-management-system/issues)

---

## 🔑 **Keywords (SEO)**

```
Laravel CV Builder, CV Management System, Professional CV Maker, Resume Builder PHP, 
Laravel Portfolio System, CV Templates Laravel, PDF CV Generator, Job Seeker Platform,
Laravel Admin Panel, CV Creator, Resume Management, Online CV Maker, Laravel Chat System,
Portfolio Management, CV Customizer, Multiple CV Templates, PDF Export Laravel,
Laravel Dashboard, Analytics Dashboard, Laravel CRUD, User Management System
```

---

## 🏆 **Acknowledgments**

- [Laravel](https://laravel.com) - The PHP Framework
- [Bootstrap](https://getbootstrap.com) - CSS Framework
- [Chart.js](https://www.chartjs.org) - Charts
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF Generation
- [CodeMirror](https://codemirror.net) - Code Editor
- [Font Awesome](https://fontawesome.com) - Icons

---

## 📊 **Project Status**

| Component | Status | Progress |
|-----------|--------|----------|
| Authentication | ✅ Complete | 100% |
| Dashboard | ✅ Complete | 100% |
| User Management | ✅ Complete | 100% |
| Projects Management | ✅ Complete | 100% |
| Skills Management | ✅ Complete | 100% |
| Experiences Management | ✅ Complete | 100% |
| Education Management | ✅ Complete | 100% |
| Social Links Management | ✅ Complete | 100% |
| Messaging System | ✅ Complete | 100% |
| CV Templates | ✅ Complete | 100% |
| Resume Builder | ✅ Complete | 100% |
| Analytics | ✅ Complete | 100% |
| Settings | ✅ Complete | 100% |
| Dark/Light Theme | ✅ Complete | 100% |
| Responsive Design | ✅ Complete | 100% |

---

**Built with ❤️ by [Mo'men Sarsour](https://github.com/Momen9Sarsour)**
```

---
