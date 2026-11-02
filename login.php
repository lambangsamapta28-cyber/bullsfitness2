<?php
// --- SETUP ERROR REPORTING ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load Library PHPMailer (PASTIKAN FOLDERNYA ADA)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

session_start();
require 'db.php'; 

// ================= KONFIGURASI GMAIL =================
define('EMAIL_PENGIRIM', 'vryn.corps@gmail.com'); 
define('PASSWORD_APP', 'jsns ukrs lspw orka'); 
// =====================================================

// 1. CEK JIKA SUDAH LOGIN (Redirect ke Beranda)
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error_msg = "";

if (isset($_POST['tombol_login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, role, full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            
            // --- PROSES OTP DIMULAI ---
            $otp = rand(100000, 999999);
            $user_id = $row['id'];
            $nama_user = $row['full_name'];
            
            // Simpan OTP ke Database
            $conn->query("UPDATE users SET two_factor_code = '$otp', two_factor_expires_at = DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id = '$user_id'");

            // Set Session Sementara (Belum Login Full)
            $_SESSION['temp_user_id'] = $user_id;
            $_SESSION['role'] = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            // --- KIRIM EMAIL ---
            $mail = new PHPMailer(true);

            try {
                // Konfigurasi Server
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = EMAIL_PENGIRIM;
                $mail->Password   = PASSWORD_APP;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Penerima
                $mail->setFrom(EMAIL_PENGIRIM, "Bulls Fitness System");
                $mail->addAddress($email, $nama_user); 

                // Konten Email
                $mail->isHTML(true);
                $mail->Subject = 'Kode OTP Login';
                $mail->Body    = "<h2>Kode OTP: $otp</h2><p>Kode ini berlaku selama 5 menit.</p>";

                $mail->send();
                
                // === PERUBAHAN DI SINI ===
                // Tidak pakai echo script alert lagi.
                // Langsung redirect PHP ke halaman verifikasi.
                header("Location: verify.php");
                exit();
                // =========================

            } catch (Exception $e) {
                $error_msg = "Gagal Kirim OTP: " . $mail->ErrorInfo;
            }

        } else {
            $error_msg = "Password Salah!";
        }
    } else {
        $error_msg = "Email Tidak Ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bull's Fitness</title>
        <!-- PWA Settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#DAA520">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="img/icon-192.png">
        <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mobile.css">
</head>
<body class="auth-body"> <header>
        <nav>
            <div class="logo"></div>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="member.php">Member</a></li>
                <li><a href="tentang.php">Tentang</a></li>
                <li><a href="tip.php">Tips & Blog</a></li>
            </ul>
        </nav>
    </header>

    <main class="auth-page">
        <div class="auth-overlay">
            <h2>Masuk Member</h2>
            
            <?php if($error_msg): ?>
                <div style="background: #ffcccc; color: red; padding: 10px; margin-bottom:10px; border-radius:5px;">
                    <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="tombol_login" class="auth-btn">LOGIN</button>
            </form>

            <a href="register.php" class="auth-link">Belum punya akun? Daftar</a>
        </div>
    </main>

    <footer><p>&copy; 2025 Bull's Fitness. Hak Cipta Dilindungi.</p></footer>
    <script src="pwa.js"></script>
</body>
</html>

