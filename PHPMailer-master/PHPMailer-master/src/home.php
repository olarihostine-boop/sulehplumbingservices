<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Home</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<div class="container">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
  <p>This is a protected page.</p>
  <p><a href="logout.php">Logout</a></p>
</div>
</body>
</html>
