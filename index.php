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
    <title>e-Form BPRS HIK MCI Yogyakarta</title>
    <!-- Tambahkan link Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <!-- Tambahkan link AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="img/logo_white.png" type="image/png">
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
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if ($page === 'detail_eform_tabungan' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_tabungan.php') {
                        header("Location: detail_eform_tabungan.php");
                        exit;
                    }
                    // elseif ($page === 'detail_eform_pembiayaan' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_pembiayaan.php') {
                    //     header("Location: detail_eform_pembiayaan.php");
                    //     exit;
                    // }
                    // elseif ($page === 'detail_eform_deposito' && basename($_SERVER['PHP_SELF']) !== 'detail_eform_deposito.php') {
                    //     header("Location: detail_eform_deposito.php");
                    //     exit;
                    // }
                    // elseif ($page === 'neracakeuangan' && basename($_SERVER['PHP_SELF']) !== 'neracakeuangan.php') {
                    //     header("Location: neracakeuangan.php");
                    //     exit;
                    // }
                    elseif ($page === 'dashboard' && basename($_SERVER['PHP_SELF']) !== 'index.php') {
                        // echo "<h2>Dashboard Sistem Report</h2>";
                        header("Location: index.php");
                        exit;
                    }
                }
                ?>
                <section id="scrollspyHero" class="bsb-hero-2 bsb-tpl-bg-blue py-5 py-xl-8 py-xxl-10">
                    <div class="container overflow-hidden">
                    <div class="row gy-3 gy-lg-0 align-items-lg-center justify-content-lg-between">
                        <div class="col-12 col-lg-6 order-1 order-lg-0">
                        <h1 class="display-3 fw-bolder mb-3">E-FORM Laporan Perjalanan Dinas BPRS HIK MCI Yogyakarta.</h1>
                        <p class="fs-4 mb-5">"Sederhana, Cepat, Tepat - E-FORM Laporan Perjalanan Dinas!"</p>
                        </div>
                        <div class="col-12 col-lg-5 text-center">
                        <!-- <img class="img-fluid" loading="lazy" src="./assets/img/hero/hero-home.jpg" alt="" style="-webkit-mask-image: url(./assets/img/hero/hero-blob-1.svg); mask-image: url(./assets/img/hero/hero-blob-1.svg);"> -->
                        <img class="img-fluid" loading="lazy" src="img/logo_MCI-removebg-preview.png" alt="">
                        </div>
                    </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <!-- Tambahkan skrip Bootstrap dan AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script>
        $(document).ready(function() {
            // Tambahkan event click pada tombol pushmenu
            $('.nav-link[data-widget="pushmenu"]').on('click', function() {
                // Toggle class 'sidebar-collapse' pada elemen body
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
</body>
</html>
