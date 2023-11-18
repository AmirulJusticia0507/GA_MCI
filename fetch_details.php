<?php
include 'koneksibaru.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Query untuk mengambil data detail berdasarkan ID
    $query = "SELECT GA_kode, namapihaktujuan, alamat, nomorhptujuan, tujuankunjungan, hasilkunjungan FROM laporan_ga WHERE id = ?";
    $stmt = $connectionServernew->prepare($query);
    $stmt->bind_param("i", $id);
    
    // Eksekusi query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $details = array();

        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }

        // Mengembalikan data dalam format JSON
        echo json_encode($details);
    } else {
        // Handle error
        echo json_encode(array("error" => "Failed to fetch details"));
    }

    // Tutup statement
    $stmt->close();
} else {
    // Handle invalid request
    echo json_encode(array("error" => "Invalid request"));
}

// Tutup koneksi
$connectionServernew->close();
?>
