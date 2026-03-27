<?php
require 'config.php';

$message = '';
if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];

    $stmt = mysqli_prepare($conn, 'SELECT id, is_verified FROM users WHERE email = ? AND verification_code = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 'ss', $email, $code);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $is_verified);
    if (mysqli_stmt_fetch($stmt)) {
        if ($is_verified) {
            $message = 'Email already verified. You can login.';
        } else {
            mysqli_stmt_close($stmt);
            $up = mysqli_prepare($conn, 'UPDATE users SET is_verified = 1, verification_code = NULL WHERE id = ?');
            mysqli_stmt_bind_param($up, 'i', $id);
            if (mysqli_stmt_execute($up)) {
                $message = 'Email verified! You can now <a href="login.php">login</a>.';
            } else {
                $message = 'Verification failed (DB).';
            }
        }
    } else {
        $message = 'Invalid verification link.';
    }
    mysqli_stmt_close($stmt);
} else {
    $message = 'Invalid request.';
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Verify</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Email Verification</h2>
  <p><?php echo $message; ?></p>
</div>
</body>
</html>
