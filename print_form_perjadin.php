<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Form Perjalanan Dinas</title>
    <!-- Include CSS library for styling, adjust the path accordingly -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons, adjust the path accordingly -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Adjust styling as needed */
        body {
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .signature {
            width: 40%;
            display: inline-block;
            text-align: center;
            margin-top: 20px;
        }
        .signature p {
            margin-top: 60px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
            // Ambil ID dari parameter URL
            $id = $_GET['id'];

            // Koneksi ke database
            $server = "localhost";
            $username = "root";
            $password = "";
            $database = "db_mobile_collection";
            
            $connectionServernew = new mysqli($server, $username, $password, $database);
            
            if ($connectionServernew->connect_error) {
                $hasil['STATUS'] = "000199";
                die(json_encode($hasil));
            }

            // Query untuk mengambil data berdasarkan ID
            $query = "SELECT * FROM laporan_ga WHERE id = $id";
            $result = $connectionServernew->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<h2>Form Perjalanan Dinas</h2>";
                    echo "<table>";
                    echo "<tr><th>ID</th><td>{$row['id']}</td></tr>";
                    echo "<tr><th>Nama Petugas</th><td>{$row['namapetugas']}</td></tr>";
                    echo "<tr><th>Tanggal</th><td>{$row['tanggal']}</td></tr>";

                    // Tambahkan kolom-kolom lain sesuai kebutuhan
                    // ...

                    echo "</table>";

                    // Tampilkan isian tabel
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-bordered table-hover nowrap mx-auto'>";
                    echo "<thead>";
                    echo "<th>No</th>";
                    echo "<th>GA Kode</th>";
                    echo "<th>Nama Pihak Tujuan</th>";
                    echo "<th>Alamat (Kabupaten/Kota)</th>";
                    echo "<th>No. HP Tujuan</th>";
                    echo "<th>Tujuan Kunjungan</th>";
                    echo "<th>Hasil Kunjungan</th>";
                    echo "</thead>";
                    echo "<tbody>";
                    
                    // Ambil data dari tabel dan tampilkan dalam bentuk baris
                    // Sesuaikan dengan struktur tabel dan nama kolom di database Anda
                    $queryDetails = "SELECT * FROM laporan_ga_details WHERE id_laporan_ga = $id";
                    $resultDetails = $connectionServernew->query($queryDetails);

                    $noDetails = 1;
                    while ($rowDetails = $resultDetails->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$noDetails}</td>";
                        echo "<td>{$rowDetails['ga_kode']}</td>";
                        echo "<td>{$rowDetails['namapihaktujuan']}</td>";
                        echo "<td>{$rowDetails['alamat']}</td>";
                        echo "<td>{$rowDetails['nomorhptujuan']}</td>";
                        echo "<td>{$rowDetails['tujuankunjungan']}</td>";
                        echo "<td>{$rowDetails['hasilkunjungan']}</td>";
                        echo "</tr>";

                        $noDetails++;
                    }

                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";

                    // Tandatangan
                    echo "<div class='signature'>";
                    echo "<p><b>Petugas:</b></p>";
                    echo "<p>_________________________</p>";
                    echo "<p><b>TL/MANAGER/KADIV:</b></p>";
                    echo "<p>{$row['tl_manager_kadiv_signature']}</p>";
                    echo "<p>{$row['tl_manager_kadiv_notification']}</p>";
                    echo "<p><b>DIREKSI:</b></p>";
                    echo "<p>_________________________</p>";
                    echo "</div>";
                }
            } else {
                echo "No data found.";
            }

            // Tutup koneksi
            $connectionServernew->close();
        ?>
    </div>
</body>
</html>
