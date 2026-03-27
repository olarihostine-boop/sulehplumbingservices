<?php
// config.php - DB connection and email helper
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\smtp;

session_start();

// --- EDIT THESE ---
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','email_auth');
define('BASE_URL','http://localhost/email_auth'); // adjust if needed

// If using Gmail SMTP, set these (replace placeholders)
define('SMTP_HOST','smtp.gmail.com');
define('SMTP_USER','YOUR_EMAIL@gmail.com');
define('SMTP_PASS','YOUR_APP_PASSWORD'); // use App Password if Gmail
define('SMTP_PORT',587);
// -------------------

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Try to use Composer autoload if present
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/mailer/src/PHPMailer.php')) {
    // If PHPMailer is available via manual copy, require it
    require __DIR__ . '/mailer/src/Exception.php';
    require __DIR__ . '/mailer/src/PHPMailer.php';
    require __DIR__ . '/mailer/src/SMTP.php';
} else {
    die('PHPMailer library not found. Please install it via Composer or manually.');
}

function sendVerificationEmail($toEmail, $code) {
    $verifyLink = BASE_URL . '/verify.php?email=' . urlencode($toEmail) . '&code=' . urlencode($code);
    $subject = 'Verify your email';
    $html = "<p>Thanks for registering. Click the link to verify your email:</p>"
            . "<a href='" . $verifyLink . "'>Verify Email</a>";
    
    // If PHPMailer class exists, use SMTP (recommended)
    if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = 'tls';
            $mail->Port = SMTP_PORT;

            $mail->setFrom(SMTP_USER, 'Auth System');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Mail error: ' . $mail->ErrorInfo);
            return false;
        }
    } else {
        // Fallback to PHP mail() - may not work on XAMPP without setup
        $headers = "MIME-Version: 1.0\r\n" .
                   "Content-type: text/html; charset=iso-8859-1\r\n" .
                   "From: " . SMTP_USER . "\r\n";
        return mail($toEmail, $subject, $html, $headers);
    }
}
?>
