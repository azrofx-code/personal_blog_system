# Custom PHP & MySQL Blog CMS

A fully functional, custom-built Content Management System (CMS) designed from scratch using PHP, MySQL, and Bootstrap 5. This project serves as a dynamic personal blog and portfolio with a secure administrative backend.

## 🚀 Live Demo
**[https://azblog1.42web.io/]**

## ✨ Core Features
* **Role-Based Access Control (RBAC):** Secure routing differentiating between `admin` and `subscriber` privileges.
* **Secure Authentication:** Passwords are cryptographically hashed using PHP's native `password_hash()` (bcrypt).
* **Advanced Database Architecture:** Utilizes `ENUM` data types, cascading foreign key deletions, and prepared statements to prevent SQL Injection.
* **Dynamic Frontend:** Paginated blog feed limiting the display to 5 posts per page for optimal load speeds.
* **Rich Text Editing:** Integrated WYSIWYG editor (CKEditor) for formatting post content.
* **Interactive Commenting System:** Logged-in users can comment seamlessly, with an admin moderation queue (Approve/Unapprove/Delete).
* **Automated File Management:** Uses `finfo` to verify MIME types for secure image uploads, and automatically deletes physical server files when a post is removed from the database.

## 🛠️ Built With
* **Backend:** PHP 8
* **Database:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3, Bootstrap 5
* **Security:** PHP Prepared Statements, `htmlspecialchars` sanitation, Session-based auth.

## 📂 Installation (Local Setup)
1. Clone this repository to your local `htdocs` or `www` directory.
2. Create a new MySQL database named `personal_blog`.
3. Import the included `database.sql` file to generate the tables and constraints.
4. Update the database credentials in `includes/db.php`.
5. Access the site via your local server environment (e.g., XAMPP, MAMP).

**Default Admin Credentials:**
* **Username:** `admin`
* **Password:** `admin123`
