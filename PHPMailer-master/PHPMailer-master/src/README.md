# Simple PHP Signup/Login with Email Verification (XAMPP)

## What this ZIP contains
- `config.php`         : Database connection + email sending helper
- `signup.php`         : Registration form & handler
- `verify.php`         : Email verification handler
- `login.php`          : Login form & handler (only verified users)
- `home.php`           : Simple protected page
- `logout.php`         : Logs out user
- `sql.sql`            : SQL to create the `users` table
- `assets/style.css`   : Basic styling
- `README.md`          : This file

## Before you run
1. Install XAMPP and start **Apache** and **MySQL**.
2. Place this folder inside your XAMPP `htdocs` directory (e.g. `C:\xampp\htdocs\email_auth` on Windows)
   or use the bundled path if running locally in a server environment.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`) and create a database named `email_auth`.
   Then import `sql.sql` from this project to create the `users` table.

## PHPMailer (recommended)
This project expects you to install PHPMailer into `mailer/` folder or use Composer.

Option A (Composer, recommended):
- `cd` into the project folder and run:
  ```
  composer require phpmailer/phpmailer
  ```
  Then `config.php` will automatically use Composer's autoloader.

Option B (manual):
- Download PHPMailer from https://github.com/PHPMailer/PHPMailer
- Copy the `src/` folder into `mailer/src/` inside this project, so you have `mailer/src/PHPMailer.php`, `mailer/src/Exception.php`, `mailer/src/SMTP.php`.

## Gmail SMTP (example)
- If using Gmail SMTP you must enable 2-Step Verification and create an App Password:
  https://myaccount.google.com/security -> App passwords
- Set the credentials in `config.php` (placeholders provided).

## If you don't configure PHPMailer
- `config.php` will fall back to PHP `mail()` (may not work on default XAMPP).
- For reliable delivery use PHPMailer + SMTP.

## How to test
1. Visit `http://localhost/email_auth/signup.php` to register.
2. Check the verification email and open the verification link.
3. Log in at `http://localhost/email_auth/login.php`.

## Security Notes
- This project is intentionally simple for learning. Do **not** use it as-is in production.
- Improvements: prepared statements (PDO), CSRF protection, input validation/sanitization, stronger rate-limiting, password reset flow.

Enjoy — ask me to customize the UI or convert to PDO if you want!
