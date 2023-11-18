<?php
// Mulai sesi
session_start();

// Periksa apakah pengguna telah terautentikasi
if (!isset($_SESSION['userid'])) {
    // Jika tidak ada sesi pengguna, alihkan ke halaman login
    header('Location: login.php');
    exit;
}
?>

<?php
// Set waktu kedaluwarsa sesi ke 5 menit
$session_timeout = 900; // 5 menit dalam detik
if (!isset($_SESSION['userid'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $session_timeout) {
    // Jika sesi sudah kedaluwarsa, hancurkan sesi dan redirect ke halaman login
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
// Perbarui waktu aktivitas terakhir
$_SESSION['last_activity'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Form Perjalanan Dinas BPRS HIK MCI Yogyakarta</title>
    <!-- Tambahkan link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="../img/logo_white.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.15/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="icon" href="img/logo_white.png" type="image/png">
    <style>
        /* Mengubah warna latar belakang header tabel */
        #dataTable thead th {
            background-color: #007BFF;
            color: white;
        }

        /* Mengubah warna latar belakang baris ganjil */
        #dataTable tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }

        /* Mengubah warna latar belakang baris genap */
        #dataTable tbody tr:nth-child(even) {
            background-color: #dddddd;
        }

        /* Mengatur padding pada sel tabel */
        #dataTable tbody td {
            padding: 8px;
        }

        /* Mengubah warna latar belakang sel saat dihover */
        #dataTable tbody tr:hover {
            background-color: #ffdb58;
        }
    </style>
    <style>
        .myButton {
            box-shadow: 3px 4px 0px 0px #899599;
            background:linear-gradient(to bottom, #ededed 5%, #bab1ba 100%);
            background-color:#ededed;
            border-radius:15px;
            border:1px solid #d6bcd6;
            display:inline-block;
            cursor:pointer;
            color:#3a8a9e;
            font-family:Arial;
            font-size:17px;
            padding:7px 25px;
            text-decoration:none;
            text-shadow:0px 1px 0px #e1e2ed;
        }
        .myButton:hover {
            background:linear-gradient(to bottom, #bab1ba 5%, #ededed 100%);
            background-color:#bab1ba;
        }
        .myButton:active {
            position:relative;
            top:1px;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <?php include 'header.php'; ?>
        </nav>
        
        <?php include 'sidebar.php'; ?>

        <div class="content-wrapper">
            <!-- Konten Utama -->
            <main class="content">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Perjadin</li>
                    </ol>
                </nav>
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if ($page === 'detail_eform_perjadin' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_perjadin.php') {
                        header("Location: detail_eform_perjadin.php");
                        exit;
                    }
                    // elseif ($page === 'detail_eform_pembiayaan' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_pembiayaan.php') {
                    //     header("Location: ../detail_eform_pembiayaan.php");
                    //     exit;
                    // }
                    // elseif ($page === 'detail_eform_deposito' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_deposito.php') {
                    //     header("Location: ../detail_eform_deposito.php");
                    //     exit;
                    // }
                    // elseif ($page === 'neracakeuangan' && basename($_SERVER['PHP_SELF']) !== 'neracakeuangan.php') {
                    //     header("Location: neracakeuangan.php");
                    //     exit;
                    // }
                    elseif ($page === 'dashboard' && basename($_SERVER['PHP_SELF']) !== 'index.php') {
                        echo "<h2>Dashboard Perjalanan Dinas BPRS HIK MCI</h2>";
                        header("Location: index.php");
                        exit;
                    }
                }
                ?>
<?php
$server = "192.168.1.199";
$username = "root";
$password = "royalmaa2*123";
$database = "MCI";

// Buat koneksi ke server
$connectionServer = new mysqli($server, $username, $password, $database);

// Periksa koneksi ke server
if ($connectionServer->connect_error) {
    $hasil['STATUS'] = "000199";
    die(json_encode($hasil));
}

// Fungsi untuk mengambil data petugas
function getPetugasList() {
    global $connectionServer;
    $query = "SELECT userid, namalengkap FROM db_mobile_collection.jabatan WHERE keterangan = 'PETUGAS'";
    $result = $connectionServer->query($query);

    $petugasList = array();
    while ($row = $result->fetch_assoc()) {
        $petugasList[$row['userid']] = $row['namalengkap'];
    }

    return $petugasList;
}
?>

                    <div class="container">
                        <center><b><h2>E-Form Perjalanan Dinas</h2></b></center>
                        <div class="card">
                            <div class="card-body" id="resultsContent">
                                <!-- Button to trigger the modal -->
                                <button type="button" class="myButton" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa fa-plus"></i> Entry Data</button>
                                <!-- Modal for creating a new entry -->
                                <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createModalLabel">Create New Entry</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="process_form_perjadin.php" method="post" id="createForm" enctype="multipart/form-data">
                                                    <div id="statusMessage" class="alert" style="display: none;"></div>
                                                    <fieldset>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <b><label for="namapetugas" class="form-label">Nama Petugas :</label></b>
                                                                <input type="text" name="namapetugas" id="namapetugas" class="form-control" required>
                                                            </div>
                                                            <!-- <div class="col-md-6">
                                                                <b><label for="namapetugas" class="form-label">Nama Petugas :</label></b>
                                                                <select name="namapetugas" id="namapetugas" class="form-control" required>
                                                                    <?php
                                                                    $petugasList = getPetugasList();
                                                                    foreach ($petugasList as $userid => $namalengkap) {
                                                                        echo "<option value=\"$userid\">$namalengkap</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div> -->
                                                            <div class="col-md-6">
                                                                <b><label for="tanggal" class="form-label">Tanggal Perjalanan Dinas :</label></b>
                                                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="mb-3">
                                                            <b><label for="namapihaktujuan" class="form-label">Nama Pihak Tujuan :</label></b>
                                                            <input type="text" name="namapihaktujuan[]" id="namapihaktujuan" class="form-control" required>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <b><label for="alamat" class="form-label">Alamat (Kota/Kabupaten) :</label></b>
                                                                <textarea name="alamat" id="alamat" cols="3" rows="1" class="form-control" required></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <b><label for="nomorhptujuan" class="form-label">No. HP Tujuan :</label></b>
                                                                <input type="text" name="nomorhptujuan" id="nomorhptujuan" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="12" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <b><label for="tujuankunjungan" class="form-label">Tujuan Kunjungan :</label></b>
                                                            <textarea name="tujuankunjungan" id="tujuankunjungan" cols="5" rows="1" class="form-control" required></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <b><label for="hasilkunjungan" class="form-label">Hasil Kunjungan :</label></b>
                                                            <textarea name="hasilkunjungan" id="hasilkunjungan" cols="5" rows="1" class="form-control" required></textarea>
                                                        </div>
                                                    </fieldset>
                                                    <div class="form-group">
                                                        <button type="submit" class="myButton" id="submitBtn">Submit</button>
                                                        <button type="reset" class="btn btn-danger">Clear</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan ini setelah formulir penambahan data -->
                        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="process_edit_form_perjadin.php" method="post" id="editForm" enctype="multipart/form-data">
                                            <!-- Gunakan PHP untuk mengisi nilai yang sedang di-edit -->
                                            <input type="hidden" name="edit_id" id="edit_id">
                                            <fieldset>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <b><label for="namapetugas" class="form-label">Nama Petugas :</label></b>
                                                        <input type="text" name="namapetugas" id="edit_namapetugas" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <b><label for="tanggal" class="form-label">Tanggal Perjalanan Dinas :</label></b>
                                                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control" required>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="mb-3">
                                                    <b><label for="namapihaktujuan" class="form-label">Nama Pihak Tujuan :</label></b>
                                                    <input type="text" name="namapihaktujuan[]" id="edit_namapihaktujuan" class="form-control" required>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <b><label for="alamat" class="form-label">Alamat (Kota/Kabupaten) :</label></b>
                                                        <textarea name="alamat" id="edit_alamat" cols="3" rows="1" class="form-control" required></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <b><label for="nomorhptujuan" class="form-label">No. HP Tujuan :</label></b>
                                                        <input type="text" name="nomorhptujuan" id="edit_nomorhptujuan" oninput="this.value = this.value.replace(/[^0-9.]/g, '');" maxlength="12" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <b><label for="tujuankunjungan" class="form-label">Tujuan Kunjungan :</label></b>
                                                    <textarea name="tujuankunjungan" id="edit_tujuankunjungan" cols="5" rows="1" class="form-control" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <b><label for="hasilkunjungan" class="form-label">Hasil Kunjungan :</label></b>
                                                    <textarea name="hasilkunjungan" id="edit_hasilkunjungan" cols="5" rows="1" class="form-control" required></textarea>
                                                </div>
                                            </fieldset>
                                            <div class="form-group">
                                                <button type="submit" class="myButton" id="submitEditBtn">Update</button>
                                                <button type="reset" class="btn btn-danger">Clear</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tambahkan ini setelah formulir edit data -->
                        <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="detailsModalLabel">Details Data</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><b>Nama Petugas:</b> <span id="details_namapetugas"></span></p>
                                        <p><b>Tanggal:</b> <span id="details_tanggal"></span></p>

                                        <!-- Tabel Isian dari DataTables -->
                                        <div class="table-responsive">
                                            <table id="detailsDataTable" class="table table-bordered table-hover nowrap mx-auto" style="width: 100%;">
                                                <!-- Struktur kolom sesuaikan dengan DataTables -->
                                                <thead>
                                                    <th>No</th>
                                                    <th>GA Kode</th>
                                                    <th>Nama Pihak Tujuan</th>
                                                    <th>Alamat (Kabupaten/Kota)</th>
                                                    <th>No. HP Tujuan</th>
                                                    <th>Tujuan Kunjungan</th>
                                                    <th>Hasil Kunjungan</th>
                                                </thead>
                                                <tbody>
                                                    <!-- Data dari DataTables akan ditampilkan di sini -->
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tandatangan -->
                                        <div class="row mt-3">
                                            <div class="col">
                                                <p><b>Petugas:</b> _________________________</p>
                                            </div>
                                            <!-- Bagian tombol pada tandatangan TL/MANAGER/KADIV -->
                                            <div class="col">
                                                <p><b>TL/MANAGER/KADIV:</b> <span id="tl_manager_kadiv_signature"></span></p>
                                                <button type="button" class="btn btn-success" onclick="handleSignature('tl_manager_kadiv', 'tl_manager_kadiv_signature', 'tl_manager_kadiv_notification', 'Approve')">Approve</button>
                                                <button type="button" class="btn btn-danger" onclick="handleSignature('tl_manager_kadiv', 'tl_manager_kadiv_signature', 'tl_manager_kadiv_notification', 'Reject')">Reject</button>
                                                <button type="button" class="btn btn-warning" onclick="handleSignature('tl_manager_kadiv', 'tl_manager_kadiv_signature', 'tl_manager_kadiv_notification', 'Pending')">Pending</button>
                                                <p id="tl_manager_kadiv_notification"></p>
                                            </div>

                                            <!-- Bagian tombol pada tandatangan DIREKSI -->
                                            <div class="col">
                                                <p><b>DIREKSI:</b> <span id="direksi_signature"></span></p>
                                                <button type="button" class="btn btn-success" onclick="handleSignature('direksi', 'direksi_signature', 'direksi_notification', 'Approve')">Approve</button>
                                                <button type="button" class="btn btn-danger" onclick="handleSignature('direksi', 'direksi_signature', 'direksi_notification', 'Reject')">Reject</button>
                                                <button type="button" class="btn btn-warning" onclick="handleSignature('direksi', 'direksi_signature', 'direksi_notification', 'Pending')">Pending</button>
                                                <p id="direksi_notification"></p>
                                            </div>
                                        </div>

                                        <!-- Tombol Cetak di dalam modal details -->
                                        <button type="button" class="btn btn-primary" onclick="printDataDetails()">Cetak</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body" id="resultsContent">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover nowrap mx-auto" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>GA Kode</th>
                                                <th>Nama Petugas</th>
                                                <th>Tanggal</th>
                                                <th>Nama Pihak Tujuan</th>
                                                <th>Alamat (Kabupaten/Kota)</th>
                                                <th>No. HP Tujuan</th>
                                                <th>Tujuan Kunjungan</th>
                                                <th>Hasil Kunjungan</th>
                                                <!-- <th>Approval</th> -->
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            // Koneksi ke database
                                            // $server = "192.168.1.184";
                                            $server = "localhost";
                                            $username = "root";
                                            $password = "";
                                            $database = "db_mobile_collection";
                                            
                                            $connectionServernew = new mysqli($server, $username, $password, $database);
                                            
                                            if ($connectionServernew->connect_error) {
                                                $hasil['STATUS'] = "000199";
                                                die(json_encode($hasil));
                                            }

                                            // Ambil data dari database dan loop untuk menampilkan pada tabel
                                            $query = "SELECT id, GA_kode, namapetugas, tanggal, jam, namapihaktujuan, alamat, nomorhptujuan, tujuankunjungan, hasilkunjungan FROM db_mobile_collection.laporan_ga";
                                            $result = $connectionServernew->query($query);
                                            $no = 1; // Variable untuk menyimpan nomor urut

                                            while ($row = $result->fetch_assoc()) {
                                                $formattedDate = date('d-M-Y', strtotime($row['tanggal']));
                                                echo "<tr>
                                                        <td>{$no}</td>
                                                        <td>{$row['GA_kode']}</td>
                                                        <td>{$row['namapetugas']}</td>
                                                        <td>{$formattedDate}</td>
                                                        <td>".implode(", ", explode(",", $row['namapihaktujuan']))."</td>
                                                        <td>{$row['alamat']}</td>
                                                        <td>{$row['nomorhptujuan']}</td>
                                                        <td>{$row['tujuankunjungan']}</td>
                                                        <td>{$row['hasilkunjungan']}</td>
                                                        <td>
                                                            <!-- Tombol Aksi -->
                                                            <div class='btn-group'>
                                                                <button type='button' class='btn btn-info' onclick='showEditModal({$row['id']}, \"{$row['namapetugas']}\", \"{$row['tanggal']}\", \"{$row['namapihaktujuan']}\", \"{$row['alamat']}\", \"{$row['nomorhptujuan']}\", \"{$row['tujuankunjungan']}\", \"{$row['hasilkunjungan']}\")'>Edit</button>
                                                                <button type='button' class='btn btn-danger' onclick='deleteData({$row['id']})'>Delete</button>
                                                                <button type='button' class='btn btn-success' onclick='showDetailsModal({$row['id']}, \"{$row['namapetugas']}\", \"{$row['tanggal']}\", \"{$row['namapihaktujuan']}\", \"{$row['alamat']}\", \"{$row['nomorhptujuan']}\", \"{$row['tujuankunjungan']}\", \"{$row['hasilkunjungan']}\")'>Details</button>
                                                            </div>
                                                        </td>
                                                    </tr>";

                                                $no++; // Increment nomor urut
                                            }
                                            // Tutup koneksi setelah selesai
                                            $connectionServernew->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

            </main>
        </div>
        <?php include 'footer.php'; ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.15/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        
        <!-- Tambahkan link Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable({
                    responsive: true,
                    scrollX: true,
                    searching: true,
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10
                    // dom: 'Bfrtip',
                    // buttons: [
                    //     'copy', 'csv', 'excel', 'pdf', 'print'
                    // ]
                });
            });

             // Tambahkan script untuk aktivasi accordion pada dropdown aksi
            $('.dropdown').on('show.bs.dropdown', function () {
                $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
            });

            $('.dropdown').on('hide.bs.dropdown', function () {
                $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#namapihaktujuan').select2({
                    // Konfigurasi Select2
                    placeholder: 'Pilih Nama Pihak Tujuan',
                    tags: true, // Izinkan input tag baru
                    tokenSeparators: [',', ' '], // Pisahkan tag dengan koma atau spasi
                    multiple: true // Izinkan pemilihan lebih dari satu opsi
                });
                // Clear Autocomplete Sebelumnya saat formulir di-reset
                $('#perjadinForm').on('reset', function() {
                    $('#namapihaktujuan').val(null).trigger('change');
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                // Tambahkan event click pada tombol pushmenu
                $('.nav-link[data-widget="pushmenu"]').on('click', function() {
                    // Toggle class 'sidebar-collapse' pada elemen body
                    $('body').toggleClass('sidebar-collapse');
                });
            });
        </script>
        <script>
            // Fungsi untuk menampilkan modal edit dan mengatur nilai-nilai yang akan di-edit
            function showEditModal(id, namapetugas, tanggal, namapihaktujuan, alamat, nomorhptujuan, tujuankunjungan, hasilkunjungan) {
                // Mengisi nilai-nilai formulir edit dengan data yang diambil dari database
                document.getElementById('edit_id').value = id;
                document.getElementById('edit_namapetugas').value = namapetugas;
                document.getElementById('edit_tanggal').value = tanggal;
                document.getElementById('edit_namapihaktujuan').value = namapihaktujuan;
                document.getElementById('edit_alamat').value = alamat;
                document.getElementById('edit_nomorhptujuan').value = nomorhptujuan;
                document.getElementById('edit_tujuankunjungan').value = tujuankunjungan;
                document.getElementById('edit_hasilkunjungan').value = hasilkunjungan;

                // Menampilkan modal edit
                $('#editModal').modal('show');
            }

            // Fungsi untuk menampilkan modal details dengan tombol cetak di dalamnya
            function showDetailsModal(id, namapetugas, tanggal, namapihaktujuan, alamat, nomorhptujuan, tujuankunjungan, hasilkunjungan) {
                // Mengisi nilai-nilai modal details dengan data yang diambil dari database
                $("#details_namapetugas").text(namapetugas);
                $("#details_tanggal").text(tanggal);

                // Mengisi DataTables pada modal details
                var detailsDataTable = $('#detailsDataTable').DataTable();
                detailsDataTable.clear().draw();

                // Fetch details data for the DataTable using AJAX
                $.ajax({
                    url: "fetch_details.php", // Ganti dengan URL yang sesuai
                    method: "POST",
                    data: { id: id },
                    dataType: "json",
                    success: function (data) {
                        // Memasukkan data ke DataTables
                        $.each(data, function (index, row) {
                            detailsDataTable.row.add([
                                index + 1,
                                row.GA_kode,
                                row.namapihaktujuan,
                                row.alamat,
                                row.nomorhptujuan,
                                row.tujuankunjungan,
                                row.hasilkunjungan
                            ]).draw();
                        });

                        // Menampilkan modal details setelah memuat data
                        $('#detailsModal').modal('show');
                    },
                    error: function (error) {
                        console.error("Error fetching details:", error);
                    }
                });
            }


            // Fungsi untuk menangani tombol cetak di dalam modal details
            function printDataDetails() {
                // Implementasikan logika cetak data disini
                // Misalnya, membuka halaman cetak dengan menggunakan window.open
                window.open('print_form_perjadin.php?id=' + document.getElementById('details_id').innerText, '_blank');
            }

            function handleSignature(role, signatureId, notificationId, action) {
                // Implementasi logika approve, reject, atau pending disini
                // Misalnya, mengubah teks tandatangan dan notifikasi, serta menyembunyikan tombol
                if (action === 'Approve') {
                    document.getElementById(signatureId).innerText = "_________________________";
                    document.getElementById(notificationId).innerText = "Disetujui pada " + new Date().toLocaleString();
                } else if (action === 'Reject') {
                    document.getElementById(signatureId).innerText = "X";
                    document.getElementById(notificationId).innerText = "Ditolak pada " + new Date().toLocaleString();
                } else if (action === 'Pending') {
                    document.getElementById(notificationId).innerText = "Sedang menunggu verifikasi";
                }

                // Menyembunyikan tombol
                document.querySelector('#detailsModal').querySelectorAll(`button[onclick*="${role}"]`).forEach(button => {
                    button.style.display = 'none';
                });

                // Menampilkan notifikasi
                document.getElementById(notificationId).style.display = 'block';
            }
            function deleteData(id) {
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    // Kirim permintaan AJAX ke file PHP yang menangani penghapusan
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "delete_data.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Tampilkan pesan atau lakukan tindakan lain setelah penghapusan berhasil
                            alert("Data berhasil dihapus");
                            // Refresh halaman atau perbarui tabel
                            location.reload();
                        }
                    };
                    // Kirim data ID yang akan dihapus
                    xhr.send("id=" + id);
                }
            }

            // Add a click event handler to the "Details" button
            $('#dataTable tbody').on('click', 'button.details-button', function () {
                var data = $('#dataTable').DataTable().row($(this).parents('tr')).data();

                // Populate modal with data
                $('#details_namapetugas').text(data[2]);
                $('#details_tanggal').text(data[3]);

                // Assuming the detailsDataTable is initialized similarly to your main DataTable
                $('#detailsDataTable').DataTable().clear().draw();
                // Populate the detailsDataTable with data from the selected row
                // You may need to adjust the index based on the structure of your data
                $('#detailsDataTable').DataTable().row.add([
                    '1',  // No
                    data[1],  // GA Kode
                    data[4],  // Nama Pihak Tujuan
                    data[5],  // Alamat
                    data[6],  // No. HP Tujuan
                    data[7],  // Tujuan Kunjungan
                    data[8]   // Hasil Kunjungan
                ]).draw();
                
                // Show the detailsModal
                $('#detailsModal').modal('show');
            });
        </script>