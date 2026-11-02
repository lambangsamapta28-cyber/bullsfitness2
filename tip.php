<?php
session_start(); // Wajib ada di paling atas untuk baca session login
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tips & Blog - Bull's Fitness</title>     <!-- PWA Settings -->
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
                <li><a href="tip.php" class="active">Tips & Blog</a></li>
    
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

    <main class="tips-page-content">

        <section class="tips-hero">
            <div class="hero-overlay">
                <h1>Tips Kebugaran & Nutrisi</h1>
                <p>Panduan Anda untuk membangun kekuatan dan kesehatan optimal.</p>
            </div>
        </section>

        <section class="tips-main-content">
            
            <div class="tips-container">
                
                <div class="tips-column">
                    <h2>Tips Kebugaran Fisik</h2>
                    <p>Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas sehari-hari dengan efisien tanpa kelelahan berlebihan, serta masih memiliki energi cadangan untuk kegiatan lain.</p>
                    <p>Tujuan utamanya adalah menjaga kesehatan jantung, kekuatan otot, daya tahan tubuh, kelenturan sendi, dan keseimbangan tubuh.</p>
                    
                    <h3>1. Konsisten Olahraga</h3>
                    <ul>
                        <li>Lakukan olahraga minimal 30 menit per hari, 3â€“5 kali seminggu.</li>
                        <li>Pilih aktivitas yang kamu sukai: jogging, bersepeda, berenang, senam, atau sekadar jalan cepat.</li>
                        <li>Kombinasikan:
                            <ul>
                                <li><strong>Cardio:</strong> untuk daya tahan jantung dan paru (lari, lompat tali, bersepeda).</li>
                                <li><strong>Strength training:</strong> untuk kekuatan otot (push-up, plank, squat, angkat beban).</li>
                                <li><strong>Flexibility:</strong> untuk kelenturan tubuh (stretching, yoga).</li>
                            </ul>
                        </li>
                    </ul>

                    <h3>2. Pemanasan & Pendinginan</h3>
                    <ul>
                        <li>Selalu lakukan pemanasan 5â€“10 menit before olahraga untuk mencegah cedera.</li>
                        <li>Akhiri dengan pendinginan agar detak jantung dan otot kembali rileks.</li>
                    </ul>

                    <h3>3. Tidur Cukup</h3>
                    <ul>
                        <li>Tidur 7â€“9 jam per malam penting untuk pemulihan otot dan daya tahan tubuh.</li>
                        <li>Kurang tidur bisa menurunkan performa olahraga dan metabolisme.</li>
                    </ul>

                    <h3>4. Jaga Postur Tubuh</h3>
                    <ul>
                        <li>Latih postur duduk dan berdiri yang benar, terutama jika sering di depan komputer.</li>
                        <li>Coba peregangan ringan setiap 1 jam untuk menghindari nyeri punggung/leher.</li>
                    </ul>
                </div>

                <div class="tips-column">
                    <h2>Tips Nutrisi Sehat</h2>
                    <p>Nutrisi adalah zat gizi yang dibutuhkan tubuh untuk tumbuh, memperbaiki jaringan, menghasilkan energi, dan menjaga fungsi organ.</p>
                    <p>Keseimbangan nutrisi adalah kunci utama kebugaran.</p>

                    <h3>1. Makan Seimbang</h3>
                    <p>Gunakan prinsip â€œIsi Piringkuâ€ dari Kemenkes:</p>
                    <ul>
                        <li><strong>Â½ piring:</strong> sayur dan buah</li>
                        <li><strong>Â¼ piring:</strong> sumber karbohidrat (nasi, roti gandum, kentang, jagung)</li>
                        <li><strong>Â¼ piring:</strong> sumber protein (ikan, ayam, telur, tahu, tempe)</li>
                    </ul>

                    <h3>2. Cukupi Cairan Tubuh</h3>
                    <ul>
                        <li>Minum 8â€“10 gelas air putih per hari.</li>
                        <li>Kurangi minuman manis dan soda.</li>
                    </ul>

                    <h3>3. Batasi Gula, Garam, dan Lemak</h3>
                    <ul>
                        <li><strong>Gula:</strong> maksimal 4 sdm per hari</li>
                        <li><strong>Garam:</strong> maksimal 1 sdt per hari</li>
                        <li><strong>Lemak:</strong> maksimal 5 sdm minyak per hari</li>
                    </ul>

                    <h3>4. Konsumsi Makanan Alami</h3>
                    <ul>
                        <li>Pilih makanan segar dan minim olahan.</li>
                        <li>Hindari junk food dan makanan cepat saji berlebihan.</li>
                    </ul>

                    <h3>5. Atur Pola Makan</h3>
                    <ul>
                        <li>Sarapan setiap pagi untuk energi awal.</li>
                        <li>Jangan makan terlalu larut malam.</li>
                        <li>Makan dalam porsi kecil tapi sering bisa membantu metabolisme.</li>
                    </ul>
                </div>
            </div> <section class="jadwal-section">
                <h2>Jadwal Fitness Yang Cocok Buat Kamu</h2>

                <h3>Seminggu 3 kali</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Fokus Latihan</th>
                                <th>Otot yang Dilatih</th>
                                <th>Contoh Latihan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Senin</td>
                                <td>Full Bodyâ€“Kekuatan (Strength Focus)</td>
                                <td>Dada, Punggung, Kaki, Core</td>
                                <td>- Bench Press (3x8â€“10)<br>- Barbell Row (3x10)<br>- Squat (4x8)<br>- Plank (3x45 detik)</td>
                                <td>Gunakan beban sedang-berat</td>
                            </tr>
                            <tr>
                                <td>Rabu</td>
                                <td>Full Body â€“ Volume (Hypertrophy Focus)</td>
                                <td>Bahu, Tangan, Paha, Core</td>
                                <td>- Shoulder Press (3x10)<br>- Dumbbell Curl (3x12)<br>- Lunges (3x12 per kaki)<br>- Leg Press (3x10)<br>- Russian Twist (3x20)</td>
                                <td>Fokus pada jumlah repetisi dan kontrol</td>
                            </tr>
                            <tr>
                                <td>Jumat</td>
                                <td>Full Body â€“ Cardio & Stabilitas (Conditioning Focus)</td>
                                <td>Seluruh tubuh + jantung</td>
                                <td>- Deadlift ringan (3x10)<br>- Pull-up / Lat Pulldown (3x10)<br>- Push-up (3x15)<br>- Jump Squat (3x15)<br>- HIIT / Treadmill 20â€“30 menit</td>
                                <td>Latihan lebih cepat, intensitas tinggi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Seminggu 4 kali</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Fokus Latihan</th>
                                <th>Otot yang Dilatih</th>
                                <th>Contoh Latihan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Senin</td>
                                <td>Upper Body (Push Day)</td>
                                <td>Dada, Bahu, Trisep</td>
                                <td>- Bench Press (3x10)<br>- Shoulder Press (3x10)<br>- Dumbbell Fly (3x12)<br>- Triceps Dips / Pushdown (3x12)</td>
                                <td>Gunakan beban sedang-berat</td>
                            </tr>
                            <tr>
                                <td>Selasa</td>
                                <td>Lower Body (Leg Day)</td>
                                <td>Paha, Betis, Bokong</td>
                                <td>- Squat (4x10)<br>- Lunges (3x12)<br>- Leg Press (3x10)<br>- Calf Raise (3x15)</td>
                                <td>Fokus pada teknik & keseimbangan</td>
                            </tr>
                            <tr>
                                <td>Kamis</td>
                                <td>Upper Body (Pull Day)</td>
                                <td>Punggung, Biceps</td>
                                <td>- Pull-up / Lat Pulldown (3x10)<br>- Barbell Row (3x10)<br>- Dumbbell Curl (3x12)<br>- Face Pull (3x12)</td>
                                <td>Gerakan terkontrol & jangan terburu-buru</td>
                            </tr>
                            <tr>
                                <td>Sabtu</td>
                                <td>Core + Cardio Day</td>
                                <td>Perut, pinggang, jantung</td>
                                <td>- Plank (3x60 detik)<br>- Hanging Leg Raise (3x12)<br>- Russian Twist (3x20)<br>- Treadmill / HIIT 20â€“30 menit</td>
                                <td>Tambahkan stretching ringan setelah latihan</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3>Seminggu 5 kali</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Fokus Latihan</th>
                                <th>Otot yang Dilatih</th>
                                <th>Contoh Latihan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Senin</td>
                                <td>Push Day (Dada, Bahu, Trisep)</td>
                                <td>Dada, Bahu depan, Trisep</td>
                                <td>- Bench Press (4x8â€“10)<br>- Dumbbell Shoulder Press (3x10)<br>- Incline Dumbbell Press (3x10)<br>- Lateral Raise (3x12)<br>- Triceps Pushdown (3x12)</td>
                                <td>Gunakan beban sedangâ€“berat</td>
                            </tr>
                            <tr>
                                <td>Selasa</td>
                                <td>Pull Day (Punggung, Bisep)</td>
                                <td>Punggung atas/bawah, Bisep</td>
                                <td>- Pull-up / Lat Pulldown (3x10)<br>- Barbell Row (4x8)<br>- Seated Cable Row (3x10)<br>- Dumbbell Curl (3x12)<br>- Face Pull (3x15)</td>
                                <td>Fokus pada kontraksi otot punggung</td>
                            </tr>
                            <tr>
                                <td>Rabu</td>
                                <td>Leg Day (Kaki dan Glutes)</td>
                                <td>Paha depan, Paha belakang, Betis</td>
                                <td>- Squat (4x10)<br>- Leg Press (3x10)<br>- Romanian Deadlift (3x10)<br>- Lunges (3x12 per kaki)<br>- Calf Raise (3x15)</td>
                                <td>Jaga teknik & rentang gerak</td>
                            </tr>
                            <tr>
                                <td>Kamis</td>
                                <td>Core + Cardio (Stabilitas dan Daya Tahan)</td>
                                <td>Perut, Pinggang, Daya tahan</td>
                                <td>- Plank (3x60 detik)<br>- Hanging Leg Raise (3x15)<br>- Russian Twist (3x20)<br>- Mountain Climber (3x20)<br>- HIIT/Treadmill 20â€“30 menit</td>
                                <td>Bisa juga diganti dengan berenang / skipping</td>
                            </tr>
                            <tr>
                                <td>Jumat</td>
                                <td>Full Body (Power & Endurance)</td>
                                <td>Seluruh tubuh</td>
                                <td>- Deadlift (4x8)<br>- Dumbbell Bench Press (3x10)<br>- Pull-up (3x10)<br>- Squat Jump (3x12)<br>- Farmer Walk (3x40 detik)</td>
                                <td>Latihan gabungan untuk kekuatan penuh</td>
                            </tr>
                            <tr>
                                <td>Sabtu & Minggu</td>
                                <td>Istirahat / Aktif ringan</td>
                                <td>â€”</td>
                                <td>Jalan kaki, peregangan, yoga</td>
                                <td>Pemulihan otot dan sendi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section> </section> </main>

    <footer>
        <p>&copy; 2025 Bull's Fitness. Hak Cipta Dilindungi.</p>
    </footer>

    <script src="pwa.js"></script>
</body>
</html>

