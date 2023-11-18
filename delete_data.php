<?php
include 'koneksibaru.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil ID yang akan dihapus dari data POST
    $id = $_POST["id"];

    // Buat query DELETE
    $query = "DELETE FROM laporan_ga WHERE id = $id";

    // Eksekusi query
    if ($connectionServernew->query($query) === TRUE) {
        // Kirim respons ke JavaScript bahwa penghapusan berhasil
        echo "OK";
    } else {
        // Kirim respons ke JavaScript bahwa ada kesalahan
        echo "Error";
    }
    // Tutup koneksi setelah selesai
    $connectionServernew->close();
}
?>
