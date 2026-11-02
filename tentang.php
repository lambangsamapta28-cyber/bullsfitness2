<?php
session_start(); // Wajib ada di paling atas untuk baca session login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Bull's Fitness</title>     <!-- PWA Settings -->
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
                <li><a href="tentang.php" class="active">Tentang</a></li>
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

    <section class="tentang-hero">
        <div class="hero-overlay">
            <h1>Dibangun di Atas Kekuatan</h1>
            <p>Pelajari filosofi dan komitmen kami pada kebugaran Anda.</p>
        </div>
    </section>

    <section class="tentang-story">
        <div class="story-container">
            <div class="story-image">
                <div class="story-image-placeholder">
                    <img src="img/logo.png" alt="Foto Tentang Kami Bull's Fitness" class="story-image-real">
                </div>
            </div>
            <div class="story-content">
                <h2>Misi Kami: Membangun Generasi Terkuat</h2>
                <p>
                    Bull's Fitness lahir dari satu keyakinan: bahwa setiap individu memiliki potensi kekuatan yang belum terjamah. Kami bukan sekadar gym; kami adalah sebuah arena. Sebuah tempat di mana tekad ditempa menjadi hasil, dan keringat diubah menjadi pencapaian.
                </p>
                <p>
                    Didirikan pada tahun 2020, misi kami adalah menyediakan lingkungan, peralatan, dan bimbingan ahli terbaik di kelasnya. Kami menolak kompromi dalam kualitas dan berfokus tanpa henti untuk membantu setiap member melampaui batas mereka, baik mereka seorang atlet profesional maupun pemula yang baru memulai perjalanan.
                </p>
            </div>
        </div>
    </section>

    <section class="filosofi-section">
        <h2>Filosofi Kami</h2>
        <div class="filosofi-container">
            
            <div class="filosofi-item">
                <span class="filosofi-icon">ðŸ†</span> 
                <h3>Fasilitas Elit</h3>
                <p>Kekuatan menuntut peralatan terbaik. Kami berinvestasi pada mesin (plate-loaded) premium, (free weights) terlengkap, dan zona fungsional yang dirancang untuk performa optimal.</p>
            </div>

            <div class="filosofi-item">
                <span class="filosofi-icon">ðŸ§ </span>
                <h3>Bimbingan Pakar</h3>
                <p>Berlatih keras saja tidak cukup; Anda harus berlatih cerdas. Tim pelatih elit kami adalah para spesialis bersertifikat yang berdedikasi pada kemajuan Anda yang aman dan efisien.</p>
            </div>

            <div class="filosofi-item">
                <span class="filosofi-icon">ðŸ¤</span>
                <h3>Komunitas Juara</h3>
                <p>Anda adalah cerminan dari lingkungan Anda. Di Bull's Fitness, Anda dikelilingi oleh individu-individu yang fokus, termotivasi, dan saling mendukung untuk tumbuh lebih kuat.</p>
            </div>

        </div>
    </section>

    <section class="cta-tentang">
        <h2>Siap Memulai Transformasi Anda?</h2>
        <p>Kekuatan sejati menanti Anda. Bergabunglah dengan komunitas kami hari ini.</p>
        <a href="member.html" class="cta-button">Lihat Paket Member</a>
    </section>


    <footer>
        <p>&copy; 2025 Bull's Fitness. Hak Cipta Dilindungi.</p>
    </footer>

    <script src="pwa.js"></script>
</body>
</html>

