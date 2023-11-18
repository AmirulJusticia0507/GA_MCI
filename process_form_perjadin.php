<?php
include 'koneksibaru.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fungsi untuk membuat kode unik
    $ga_kode = generateUniqueCode();
    
    // Data dari form
    $namapetugas = $_POST["namapetugas"];
    $tanggal = $_POST["tanggal"];
    $namapihaktujuan = implode(", ", $_POST["namapihaktujuan"]);
    $alamat = $_POST["alamat"];
    // $nomorhptujuan = "'" . $_POST["nomorhptujuan"] . "'";
    $nomorhptujuan = $_POST["nomorhptujuan"];
    $tujuankunjungan = $_POST["tujuankunjungan"];
    $hasilkunjungan = $_POST["hasilkunjungan"];
    
    // Waktu saat ini
    date_default_timezone_set('Asia/Jakarta');
    $jam = date("H:i:s");

    // Insert data into database using prepared statement
    $stmt = $connectionServernew->prepare("INSERT INTO laporan_ga (GA_kode, namapetugas, tanggal, jam, namapihaktujuan, alamat, nomorhptujuan, tujuankunjungan, hasilkunjungan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $ga_kode, $namapetugas, $tanggal, $jam, $namapihaktujuan, $alamat, $nomorhptujuan, $tujuankunjungan, $hasilkunjungan);

    // Execute the prepared statement
    if ($stmt->execute()) {
        $response["STATUS"] = "200";
        echo json_encode($response);

        // Redirect after the process is complete
        echo '<script>window.location.href = "detail_eform_perjadin.php?page=detail_eform_perjadin";</script>';
        exit();
    } else {
        $response["STATUS"] = "000198";
        echo json_encode($response);
        exit();
    }
}

function generateUniqueCode() {
    // Function to generate a unique code, you can adjust it as needed
    return "GA" . date("YmdHis") . rand(100, 999);
}
?>
