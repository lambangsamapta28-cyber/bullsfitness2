<?php
session_start();
require 'db.php';

// 1. Cek Login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// 2. Cek Data Paket
if (!isset($_POST['package_name'])) {
    echo "<script>alert('Silakan pilih paket terlebih dahulu!'); window.location.href='member.php';</script>";
    exit();
}

// 3. Ambil Data
$paket = $_POST['package_name'];
$durasi = $_POST['duration'];
$harga = $_POST['price'];

// Format Harga
$harga_format = "Rp " . number_format($harga, 0, ',', '.');
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checkout - <?= $paket; ?></title>
      <!-- PWA Settings -->
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#DAA520">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="img/icon-192.png">
        <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mobile.css">
  
  <style>
      /* Layout Tengah */
      .container {
          display: flex;
          justify-content: center;
          align-items: flex-start;
          padding-top: 50px;
          min-height: 80vh;
      }
      /* Style Kartu */
      .card {
          background: #111;
          width: 100%;
          max-width: 400px; /* Lebar kartu dibatasi agar rapi */
          border: 1px solid #333;
          border-top: 4px solid #DAA520; /* Garis Emas */
          border-radius: 12px;
          padding: 30px;
          box-shadow: 0 10px 30px rgba(0,0,0,0.5);
          color: white;
      }
      .meta {
          display: flex;
          justify-content: space-between;
          margin-bottom: 10px;
          border-bottom: 1px solid #222;
          padding-bottom: 10px;
      }
      .muted { color: #888; font-size: 0.9rem; }
      
      /* Tombol Konfirmasi */
      .btn-confirm {
          background-color: #DAA520;
          color: black;
          font-weight: bold;
          width: 100%;
          padding: 15px;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          font-size: 1rem;
          margin-top: 20px;
          transition: 0.3s;
      }
      .btn-confirm:hover { background-color: #b8860b; }

      /* --- PERBAIKAN: STYLE AGAR QR CODE DI TENGAH --- */
      #qris-area {
          background: white; /* Background putih biar QR terbaca jelas */
          padding: 15px;
          border-radius: 10px;
          width: fit-content; /* Lebar menyesuaikan isi */
          margin: 20px auto;  /* Auto margin kiri-kanan = Tengah */
          display: flex;
          justify-content: center;
          align-items: center;
      }
      #qris-area img {
          display: block; /* Mencegah celah di bawah gambar */
      }
  </style>
</head>
<body class="halaman-pembayaran">
  
  <header>
    <nav>
        <div class="logo"></div>
        <ul>
            <li><a href="index.php">Beranda</a></li>
            <li><a href="member.php">Member</a></li>
            <li><a href="tentang.php">Tentang</a></li>
            <li><a href="tip.php">Tips & Blog</a></li>
            <?php if(isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true): ?>
                    <li>
                        <a href="profile.php" class="user-menu">
                            <?= htmlspecialchars(explode(" ", $_SESSION['full_name'])[0]); ?>
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="login.php" style="font-weight: bold; color: #DAA520;">Login</a></li>
                <?php endif; ?>
        </ul>
    </nav>
  </header>

  <div class="container">
    <div class="card">
      <h2 style="text-align: center; color: #DAA520; margin-bottom: 20px;"><?= $paket; ?></h2>
      
      <div class="meta">
        <div class="muted">Durasi</div>
        <div><?= $durasi; ?> Bulan</div>
      </div>
      
      <div class="meta">
        <div class="muted">Harga Paket</div>
        <div><strong><?= $harga_format; ?></strong></div>
      </div>

      <div style="text-align: center; margin-top: 20px; color: #DAA520; font-weight: bold;">
          SCAN QRIS DI BAWAH INI
      </div>
      
      <div id="qris-area"></div>
      
      <div style="text-align:center; margin-bottom: 20px;">
        <small class="muted">Gunakan GoPay, OVO, Dana, atau Mobile Banking.</small>
      </div>
      
      <div class="meta" style="border-top: 1px dashed #555; border-bottom: none; padding-top: 15px;">
        <div class="muted">Total Bayar</div>
        <div style="font-size: 1.5rem; color: #DAA520;"><strong><?= $harga_format; ?></strong></div>
      </div>

      <form action="process_payment.php" method="POST">
          <input type="hidden" name="package_name" value="<?= $paket; ?>">
          <input type="hidden" name="duration" value="<?= $durasi; ?>">
          <input type="hidden" name="price" value="<?= $harga; ?>">
          
          <button type="submit" name="konfirmasi_bayar" class="btn-confirm">
              âœ… SAYA SUDAH MEMBAYAR
          </button>
      </form>
      
      <div style="text-align: center; margin-top: 15px;">
          <a href="member.php" style="color: #666; text-decoration: none; font-size: 0.85rem;">Batal / Pilih Paket Lain</a>
      </div>

    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  
  <script>
    window.onload = function() {
        var container = document.getElementById("qris-area");
        var text = "Bull's Fitness | <?= $paket; ?> | <?= $harga_format; ?>";
        
        // Generate QR
        new QRCode(container, {
            text: text,
            width: 180,  // Ukuran QR
            height: 180, // Ukuran QR
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    };
  </script>
    <script src="pwa.js"></script>
</body>
</html>

