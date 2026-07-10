# Contributing to Cash Flow System

Thank you for considering contributing!

## 🧠 Philosophy
This project represents a modernized legacy application. The primary goal is maintaining **Strict Security** and **Database Agnosticism** (supporting both MySQL and PostgreSQL via PDO).

## 💻 Coding Standards
*   **Security First:** You MUST use PDO Prepared Statements for any database query.
*   **Sanitization:** Always use `htmlspecialchars()` when outputting data to the view.
*   **Passwords:** Never implement custom hashing. Always use PHP's native `password_hash()`.

## 🔄 Pull Request Process
1. Test your queries against BOTH MySQL and PostgreSQL if you modified database logic.
2. Submit the PR against the `main` branch.
