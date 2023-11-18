<?php
// $server = "192.168.1.199";
// $username = "root";
// $password = "royalmaa2*123";
// $database = "MCI";

// // Buat koneksi ke server
// $connectionServer = new mysqli($server, $username, $password, $database);

// // Periksa koneksi ke server
// if ($connectionServer->connect_error) {
//     $hasil['STATUS'] = "000199";
//     die(json_encode($hasil));
// }
// $server = "192.168.1.184";
// $server = "192.168.1.199";
$server = "localhost";
$username = "root";
$password = "";
$database = "MCI";

// Buat koneksi ke server
$connectionServer = new mysqli($server, $username, $password, $database);

// Periksa koneksi ke server
if ($connectionServer->connect_error) {
    $hasil['STATUS'] = "000199";
    die(json_encode($hasil));
}
?>
