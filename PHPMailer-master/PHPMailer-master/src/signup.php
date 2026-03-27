<?php
require 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $message = 'Please fill all fields.';
    } else {
        // basic unique email check
        $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE email = ?');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $code = md5(uniqid(rand(), true));
            $insert = mysqli_prepare($conn, 'INSERT INTO users (name, email, password, verification_code) VALUES (?, ?, ?, ?)');
            mysqli_stmt_bind_param($insert, 'ssss', $name, $email, $hash, $code);
            if (mysqli_stmt_execute($insert)) {
                // send verification email
                $sent = sendVerificationEmail($email, $code);
                if ($sent) {
                    $message = 'Registration successful! Check your email to verify.';
                } else {
                    $message = 'Registered but failed to send verification email. Check config.php settings.';
                }
            } else {
                $message = 'Database error: ' . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
  <h2>Sign Up</h2>
  <?php if ($message): ?><p class="msg"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
  <form method="post" action="signup.php">
    <input name="name" placeholder="Full name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Sign Up</button>
  </form>
  <p>Already registered? <a href="login.php">Login</a></p>
</div>
</body>
</html>
