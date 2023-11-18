<?php
include 'koneksibaru.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data dari form
    $edit_id = $_POST["edit_id"];
    $edit_namapetugas = $_POST["namapetugas"];
    $edit_tanggal = $_POST["tanggal"];
    $edit_namapihaktujuan = implode(", ", $_POST["namapihaktujuan"]);
    $edit_alamat = $_POST["alamat"];
    $edit_nomorhptujuan = $_POST["nomorhptujuan"];
    // $edit_nomorhptujuan = "'" . $_POST["nomorhptujuan"] . "'";
    $edit_tujuankunjungan = $_POST["tujuankunjungan"];
    $edit_hasilkunjungan = $_POST["hasilkunjungan"];

    // Update data dalam database menggunakan prepared statement
    $stmt = $connectionServernew->prepare("UPDATE laporan_ga SET namapetugas=?, tanggal=?, namapihaktujuan=?, alamat=?, nomorhptujuan=?, tujuankunjungan=?, hasilkunjungan=? WHERE id=?");
    $stmt->bind_param("sssssssi", $edit_namapetugas, $edit_tanggal, $edit_namapihaktujuan, $edit_alamat, $edit_nomorhptujuan, $edit_tujuankunjungan, $edit_hasilkunjungan, $edit_id);

    // Eksekusi prepared statement
    if ($stmt->execute()) {
        $response["STATUS"] = "200";
        echo json_encode($response);

        // Redirect setelah proses selesai
        echo '<script>window.location.href = "detail_eform_perjadin.php?page=detail_eform_perjadin";</script>';
        exit();
    } else {
        $response["STATUS"] = "000198";
        echo json_encode($response);
        exit();
    }
}
?>
