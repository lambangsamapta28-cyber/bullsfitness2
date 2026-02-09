<?php
session_start();
require 'db.php';
date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sesuai

// 1. Cek Login & Tombol
if (!isset($_SESSION['is_logged_in']) || !isset($_POST['konfirmasi_bayar'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$package_name = $_POST['package_name'];
$duration = (int)$_POST['duration']; // Durasi tambahan (misal: 12)
$price = $_POST['price'];

// 2. CEK MEMBERSHIP TERAKHIR USER
$check_query = $conn->prepare("SELECT id, start_date, end_date FROM memberships WHERE user_id = ? ORDER BY end_date DESC LIMIT 1");
$check_query->bind_param("i", $user_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    // --- SKENARIO A: SUDAH PERNAH BELI (CEK PERPANJANGAN) ---
    $row = $result->fetch_assoc();
    $membership_id = $row['id'];
    
    $db_end_date = new DateTime($row['end_date']); // Tanggal mati paket lama
    $today = new DateTime(); // Hari ini

    // Reset jam agar perbandingan akurat
    $db_end_date->setTime(0,0,0);
    $today->setTime(0,0,0);

    if ($db_end_date >= $today) {
        // KONDISI 1: PAKET MASIH AKTIF (AKUMULASI)
        // Logika: Tanggal Mati Lama + Durasi Baru
        // Contoh: Juni + 12 Bulan = Juni Tahun Depan
        
        $start_date_final = $row['start_date']; // Tanggal mulai TIDAK berubah
        
        $db_end_date->modify("+$duration month"); // Tambahkan durasi ke tanggal mati lama
        $end_date_final = $db_end_date->format('Y-m-d');

        $status_pesan = "Membership Diperpanjang! Total durasi bertambah $duration bulan.";

    } else {
        // KONDISI 2: SUDAH EXPIRED (RESET)
        // Logika: Mulai Baru dari Hari Ini
        
        $start_date_final = date('Y-m-d');
        $end_date_final = date('Y-m-d', strtotime("+$duration month"));
        
        $status_pesan = "Membership Diaktifkan Kembali! Paket aktif selama $duration bulan.";
    }

    // UPDATE DATA YANG ADA (Jangan buat baris baru biar tidak dobel)
    $stmt = $conn->prepare("UPDATE memberships SET package_name=?, price=?, start_date=?, end_date=? WHERE id=?");
    $stmt->bind_param("sdssi", $package_name, $price, $start_date_final, $end_date_final, $membership_id);

} else {
    // --- SKENARIO B: PENGGUNA BARU (INSERT) ---
    $start_date_final = date("Y-m-d");
    $end_date_final = date("Y-m-d", strtotime("+$duration month"));
    
    $stmt = $conn->prepare("INSERT INTO memberships (user_id, package_name, price, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $package_name, $price, $start_date_final, $end_date_final);
    
    $status_pesan = "Pembayaran Berhasil! Paket $package_name telah AKTIF.";
}

// EKSEKUSI QUERY
if ($stmt->execute()) {
    echo "<script>
            alert('$status_pesan');
            window.location.href = 'profile.php';
          </script>";
} else {
    echo "Terjadi Kesalahan: " . $stmt->error;
}

$stmt->close();
$check_query->close();
$conn->close();
?>