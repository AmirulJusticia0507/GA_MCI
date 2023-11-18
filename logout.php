<?php
session_start();
include 'koneksi.php';
// // Tambahkan aktivitas logout ke dalam tabel log_activity
// $activityType = "logout"; // Ini adalah aktivitas logout
// $logoutDate = date("Y-m-d H:i:s"); // Waktu logout

// // Sisipkan data aktivitas logout ke dalam tabel
// $stmt = $connectionLocal->prepare("INSERT INTO log_activity (userid, TYPE, logoutDate, ipaddress, macaddress, device) VALUES (?, ?, ?, ?, ?, ?)");
// $stmt->bind_param("isssss", $_SESSION["userid"], $activityType, $logoutDate, $ipAddress, $macAddress, $device); // Pastikan Anda memiliki alamat IP, MAC, dan informasi perangkat saat logout
// $stmt->execute();

session_unset(); // Hapus semua variabel sesi
session_destroy(); // Hapus sesi
header("Location: login.php"); // Redirect ke halaman login
?>
