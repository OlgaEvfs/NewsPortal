# 📰 NewsPortal — Educational PHP Project (MVC)

A web portal for news publishing featuring a comment system, user registration, and a comprehensive administration panel.

---

## 🚀 Key Features
*   **News Management:** Category system, image uploads, previews, and full-article reading.
*   **Interactivity:** Commenting system for every news article.
*   **Security:** User registration and authentication using modern password hashing (`password_hash`).
*   **Admin Panel:** Full CRUD (Create, Read, Update, Delete) operations for news, categories, and users.
*   **Architecture:** Clean MVC (Model-View-Controller) pattern built on native PHP.

---

## 🛠 Tech Stack & Tools
*   **Backend:** PHP 8.2+
*   **Database:** MySQL (PDO)
*   **Frontend:** HTML5, CSS3, Bootstrap 3.3
*   **Testing:** [PHPUnit 11+](https://phpunit.de/)
*   **Dependency Management:** [Composer](https://getcomposer.org/)

---

## 🧪 Testing & Code Quality
The project includes a comprehensive Unit Testing suite (18 tests, 39 assertions) ensuring system stability and security.

### Test Coverage:
1.  **Registration (`RegisterTest`):** Email validation, password length checks, and matching confirmation.
2.  **News (`NewsTest`):** Database selection logic and SQL Injection prevention.
3.  **Admin Auth (`AdminAuthTest`):** Secure login logic using Database Mock objects.
4.  **Comments (`CommentsTest`):** XSS Protection (tag filtering) and accurate comment counting.
5.  **Admin Panel (`AdminNewsTest`):** CRUD logic validation and media file handling.

### How to Run Tests:
1. Install dependencies: `composer install`
2. Execute tests: `.\vendor\bin\phpunit tests`

---

## 🛡 Security (Security First)
The project has been refactored to protect against common web vulnerabilities:
*   **XSS Protection:** All user-generated content (comments, news titles) is filtered using `htmlspecialchars`.
*   **SQL Injection Prevention:** Implemented Integer casting for ID parameters and prepared for parameterized queries.
*   **Data Validation:** Strict checks for empty fields and format compliance.
*   **Dependency Injection:** Models refactored to support DI, making the codebase modular and highly testable.

---

## ⚙️ Installation & Setup
1.  Clone the repository to your XAMPP `htdocs` folder.
2.  Import the database from `news_portal.sql` via PHPMyAdmin.
3.  Configure DB connection in `inc/Database.php`.
4.  Open in browser: `http://localhost/NewsPortal/`

---

## 📁 Project Structure
*   `admin/` — Administration panel (Controller, Model, View).
*   `controller/` — Main application controllers.
*   `model/` — Application business logic.
*   `view/` — HTML templates and views.
*   `tests/` — Unit Testing suite.
*   `inc/` — Database configuration and helper classes.
*   `public/` — Static assets (CSS, JS).
