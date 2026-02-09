<?php
echo "<h3>Sedang mengecek lokasi file...</h3>";

if (file_exists('PHPMailer/PHPMailer.php')) {
    echo "<p style='color:green'>✅ File PHPMailer.php ditemukan!</p>";
} else {
    echo "<p style='color:red'>❌ File PHPMailer.php TIDAK DITEMUKAN. Cek nama folder Anda.</p>";
}

if (file_exists('PHPMailer/SMTP.php')) {
    echo "<p style='color:green'>✅ File SMTP.php ditemukan!</p>";
} else {
    echo "<p style='color:red'>❌ File SMTP.php TIDAK DITEMUKAN.</p>";
}
?>