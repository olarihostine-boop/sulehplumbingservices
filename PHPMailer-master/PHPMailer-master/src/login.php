<?php
require 'config.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $message = 'Fill both fields.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, name, password, is_verified FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $hash, $is_verified);
        if (mysqli_stmt_fetch($stmt)) {
            if (!$is_verified) {
                $message = 'Please verify your email before logging in.';
            } elseif (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                header('Location: home.php');
                exit;
            } else {
                $message = 'Incorrect password.';
            }
        } else {
            $message = 'Email not found.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Login</h2>
  <?php if ($message): ?><p class="msg"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
  <form method="post" action="login.php">
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</div>
</body>
</html>
