<?php
session_start();
require 'db.php';

// Jika tidak ada data user sementara, tendang ke login
if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['verifikasi'])) {
    $otp_input = $_POST['otp'];
    $user_id = $_SESSION['temp_user_id'];

    // Cek kode OTP di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND two_factor_code = ? AND two_factor_expires_at > NOW()");
    $stmt->bind_param("is", $user_id, $otp_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // --- LOGIN SUKSES ---
        $row = $result->fetch_assoc();
        
        // 1. Set Session Login Resmi
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $row['id']; 
        $_SESSION['role'] = $row['role']; // Role tetap disimpan sebagai admin/member
        $_SESSION['full_name'] = $row['full_name'];

        // 2. Hapus OTP dari database
        $conn->query("UPDATE users SET two_factor_code = NULL, two_factor_expires_at = NULL WHERE id = '$user_id'");

        // 3. Hapus session sementara
        unset($_SESSION['temp_user_id']);

        // 4. REDIRECT SEMUA USER KE INDEX.PHP (BERANDA)
        // Tidak peduli admin atau member, semua ke Beranda dulu.
        header("Location: index.php");
        exit();

    } else {
        $error = "Kode OTP salah atau sudah kedaluwarsa!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - Bull's Fitness</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: url('img/bgpembayaran.jpg'); background-size: cover; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .otp-card { background: rgba(0,0,0,0.85); padding: 40px; border-radius: 10px; border: 1px solid #DAA520; text-align: center; color: white; width: 100%; max-width: 400px; }
        input { width: 100%; padding: 15px; margin: 20px 0; font-size: 1.5rem; text-align: center; letter-spacing: 5px; background: #222; border: 1px solid #555; color: gold; border-radius: 5px; }
        button { width: 100%; padding: 15px; background: #DAA520; border: none; font-weight: bold; cursor: pointer; font-size: 1rem; border-radius: 5px; }
        button:hover { background: #b8860b; }
    </style>
</head>
<body>
    <div class="otp-card">
        <h2 style="color: #DAA520;">KEAMANAN TAMBAHAN</h2>
        <p>Masukkan 6 digit kode yang telah dikirim ke email Anda.</p>
        
        <?php if($error): ?>
            <p style="color: red; background: #ffdada; padding: 10px; border-radius: 5px; color: #721c24;"><?= $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="number" name="otp" placeholder="123456" required autofocus>
            <button type="submit" name="verifikasi">VERIFIKASI MASUK</button>
        </form>
        <br>
        <a href="login.php" style="color: #aaa; text-decoration: none; font-size: 0.9rem;">&larr; Kembali ke Login</a>
    </div>
</body>
</html>