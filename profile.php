<?php
session_start();
require 'db.php';

// 1. Cek Login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

// 2. Ambil Data
$query = "SELECT u.full_name, u.email, m.package_name, m.start_date, m.end_date 
          FROM users u 
          LEFT JOIN memberships m ON u.id = m.user_id 
          WHERE u.id = ? 
          ORDER BY m.id DESC LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "Error: Data user tidak ditemukan. Silakan <a href='logout.php'>Logout</a>.";
    exit();
}

// 3. Hitung Logika Waktu
$sisa_hari = 0;
$total_durasi = 1; // Default biar gak division by zero
$persentase = 0;
$status_text = "TIDAK AKTIF";
$status_color = "#ff4444"; // Merah

if (!empty($data['end_date']) && !empty($data['start_date'])) {
    $tgl_mulai = new DateTime($data['start_date']);
    $tgl_hari_ini = new DateTime(); 
    $tgl_habis = new DateTime($data['end_date']); 
    
    // Reset jam agar hitungan akurat
    $tgl_hari_ini->setTime(0,0,0);
    $tgl_habis->setTime(0,0,0);
    $tgl_mulai->setTime(0,0,0);

    if ($tgl_habis >= $tgl_hari_ini) {
        $jarak = $tgl_hari_ini->diff($tgl_habis);
        $sisa_hari = $jarak->days;
        $status_text = "AKTIF";
        $status_color = "#00ff00"; // Hijau

        // Hitung Persentase Progress Bar
        $total_days = $tgl_mulai->diff($tgl_habis)->days;
        $total_durasi = ($total_days == 0) ? 1 : $total_days; // Hindari bagi nol
        $persentase = ($sisa_hari / $total_durasi) * 100;
    } else {
        $sisa_hari = 0;
        $persentase = 0;
        $status_text = "KEDALUWARSA";
    }
}

// Ambil Inisial Nama (Misal: Budi -> B)
$inisial = strtoupper(substr($data['full_name'], 0, 1));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Member - Bull's Fitness</title>
        <!-- PWA Settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#DAA520">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="img/icon-192.png">
        <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mobile.css">
</head>
<body>
    
    <header>
        <nav>
            <div class="logo"></div>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="member.php">Member</a></li>
                <li><a href="tentang.php">Tentang</a></li>
                <li><a href="tip.php">Tips & Blog</a></li>
                <li><a href="profile.php" class="active" style="color: #DAA520;">Profil Saya</a></li>
            </ul>
        </nav>
    </header>

    <main class="dashboard-wrapper">
        <div class="dashboard-container">
            
            <aside class="profile-sidebar" style="display: flex; flex-direction: column; min-height: 400px;">
                
                <div>
                    <div class="avatar-circle">
                        <?= $inisial; ?>
                    </div>
                    <h2 class="profile-name"><?= htmlspecialchars($data['full_name']); ?></h2>
                    <p class="profile-email"><?= htmlspecialchars($data['email']); ?></p>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <div style="margin-top: 30px;">
                            <a href="admin.php" class="cta-button" style="display: block; width: 100%; text-align: center; text-decoration: none; border: 1px solid #DAA520;">
                                ðŸ‘‘ Dashboard Admin
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="sidebar-menu" style="margin-top: auto; width: 100%;">
                    <a href="logout.php" class="btn" style="text-decoration: none; display: block; text-align: center;">Logout</a>
                </div>
            </aside>

        <section class="stats-container">
    
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                          <th colspan="2">BULL'S FITNESS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           <td>Member Name</td>
                            <td><?= strtoupper($data['full_name']); ?></td> 
                        </tr>
                        <tr>
                          <td>Package</td>
                         <td><?= $data['package_name']; ?></td> 
                        </tr>
                        <tr>
                          <td>Valid Thru</td>
                          <td><?= $data['end_date'] ? date("m/y", strtotime($data['end_date'])) : '--/--'; ?></td> </tr>
                        <tr>
                         <td>Status Member</td>
                          <td><span class="status-active">â— AKTIF</span></td>
                        </tr>
                        <tr>
                         <td>Sisa Waktu</td>
                            <td><?= $sisa_hari; ?> Hari</td>
                        </tr>
                
                        <tr class="progress-row">
                            <td colspan="2">
                                <div style="background:#333; height:4px; width:100%; margin-top:5px; border-radius:2px;">
                                    <div style="background:#DAA520; height:100%; width:<?= $persentase; ?>%;"></div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php if($sisa_hari < 7): ?>
        <div style="text-align: center; margin-top: 20px;">
            <a href="member.php" class="cta-button" style="width: 100%; display: block;">Perpanjang Membership</a>
        </div>
        <?php endif; ?>

        </section>

        </div>
    </main>

    <footer>
        <p>&copy; 2025 Bull's Fitness. Hak Cipta Dilindungi.</p>
    </footer>

    <script src="pwa.js"></script>
</body>
</html>

