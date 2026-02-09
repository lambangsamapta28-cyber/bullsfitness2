<?php
// --- SETUP ERROR REPORTING ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php'; 

$error_msg = "";
$success_msg = "";

if (isset($_POST['tombol_daftar'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validasi Password
    if ($password !== $confirm_password) {
        $error_msg = "Konfirmasi password tidak cocok!";
    } else {
        // 2. Cek apakah email sudah terdaftar?
        $cek_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $cek_email->bind_param("s", $email);
        $cek_email->execute();
        $result = $cek_email->get_result();

        if ($result->num_rows > 0) {
            $error_msg = "Email sudah terdaftar! Silakan login.";
        } else {
            // 3. Masukkan Data ke Database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'member';

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Pendaftaran Berhasil! Silakan Login untuk memilih paket membership.');
                        window.location.href = 'login.php';
                      </script>";
                exit();
            } else {
                $error_msg = "Terjadi kesalahan sistem: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Bull's Fitness</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
    
    <header>
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
            <h2>Buat Akun Baru</h2>
            <p style="text-align: center; color: #ccc; margin-bottom: 20px;">Bergabunglah dengan Bull's Fitness Family</p>

            <?php if($error_msg): ?>
                <div style="background: #ffcccc; color: red; padding: 10px; margin-bottom:10px; border-radius:5px;">
                    <?= $error_msg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="full_name" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@contoh.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" name="tombol_daftar" class="auth-btn">DAFTAR SEKARANG</button>
            </form>

            <a href="login.php" class="auth-link">Sudah punya akun? Login disini</a>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Bull's Fitness. Hak Cipta Dilindungi.</p>
    </footer>

</body>
</html>