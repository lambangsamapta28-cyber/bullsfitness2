<?php
session_start();
require 'db.php';

// 1. SECURITY CHECK: Hanya Role 'admin' yang boleh masuk
if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// =======================================================
// FITUR HAPUS AKUN (LOGIKA BARU DITAMBAHKAN DISINI)
// =======================================================
if (isset($_GET['hapus_id'])) {
    $id_user = intval($_GET['hapus_id']); // Pastikan angka aman
    
    // Jangan biarkan admin menghapus dirinya sendiri
    if ($id_user == $_SESSION['user_id']) {
        echo "<script>alert('Anda tidak bisa menghapus akun sendiri saat sedang login!');</script>";
    } else {
        // Query Hapus
        // Karena ada ON DELETE CASCADE di database, menghapus user otomatis menghapus membershipnya juga
        $stmt_hapus = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt_hapus->bind_param("i", $id_user);
        
        if ($stmt_hapus->execute()) {
            echo "<script>alert('Data user berhasil dihapus!'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data.');</script>";
        }
        $stmt_hapus->close();
    }
}

// 2. Query Data User (Tampilkan User + Paketnya)
$query = "SELECT u.id, u.full_name, u.email, u.created_at, 
          m.package_name, m.end_date 
          FROM users u 
          LEFT JOIN memberships m ON u.id = m.user_id 
          ORDER BY u.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bull's Fitness</title>
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #000;">

    <header style="border-bottom: 1px solid #333;">
        <nav>
            <div class="logo"></div>
            <div style="color: white; font-weight: bold; margin-left: auto;">
                ADMIN PANEL
            </div>
        </nav>
    </header>

    <main class="admin-container">
        
        <a href="profile.php" class="btn-back">
            <span>&#8592;</span> Kembali ke Profile
        </a>

        <div class="admin-header">
            <h1 class="admin-title">Data Member</h1>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">Nama Lengkap</th>
                        <th width="25%">Email</th>
                        <th width="20%">Paket</th>
                        <th width="10%">Status</th>
                        <th width="10%">Berakhir</th>
                        <th width="10%" style="text-align: center;">Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            
                            <?php 
                                // Logika Status Warna
                                $status_badge = '<span class="badge badge-none">Non-Member</span>';
                                $tgl_berakhir = '-';

                                if (!empty($row['end_date'])) {
                                    $today = new DateTime();
                                    $end = new DateTime($row['end_date']);
                                    $tgl_berakhir = date("d M Y", strtotime($row['end_date']));

                                    if ($end >= $today) {
                                        $status_badge = '<span class="badge badge-active">Active</span>';
                                    } else {
                                        $status_badge = '<span class="badge badge-expired">Expired</span>';
                                    }
                                }
                            ?>

                            <tr>
                                <td style="color: #666;">#<?= $row['id']; ?></td>
                                <td style="font-weight: bold;"><?= htmlspecialchars($row['full_name']); ?></td>
                                <td style="color: #aaa;"><?= htmlspecialchars($row['email']); ?></td>
                                <td style="color: #DAA520;"><?= $row['package_name'] ? $row['package_name'] : '-'; ?></td>
                                <td><?= $status_badge; ?></td>
                                <td><?= $tgl_berakhir; ?></td>
                                
                                <td style="text-align: center;">
                                    <a href="admin.php?hapus_id=<?= $row['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('YAKIN HAPUS?\n\nData user ini beserta riwayat membershipnya akan hilang permanen!');">
                                       🗑 Hapus
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 50px; color: #666;">
                                Belum ada data member.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <footer>
        <p>&copy; 2025 Bull's Fitness Admin System.</p>
    </footer>

</body>
</html>