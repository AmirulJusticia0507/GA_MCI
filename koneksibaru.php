<?php
// $server = "192.168.1.184";
$server = "localhost";
$username = "root";
$password = "";
$database = "db_mobile_collection";

// Buat koneksi ke server
$connectionServernew = new mysqli($server, $username, $password, $database);

// Periksa koneksi ke server
if ($connectionServernew->connect_error) {
    $hasil['STATUS'] = "000199";
    die(json_encode($hasil));
}
?>