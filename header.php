<style>
    /* CSS Kustom untuk Header */
    .navbar-custom-menu {
        margin-right: 10px; /* Atur margin kanan sesuai kebutuhan */
    }

    .navbar-custom-menu .dropdown-menu {
        right: 0; /* Menggeser menu dropdown ke kanan */
        left: auto; /* Menonaktifkan penyesuaian ke kiri */
    }

    .user-details {
        padding: 10px;
    }
</style>

<div class="navbar-custom-menu ml-auto">
    <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
            <?php
            if (session_status() == PHP_SESSION_NONE) {
                session_start(); // Mulai sesi hanya jika belum ada
            }
            if (isset($_SESSION['userid'])) {
                // Sesi sudah ada, gunakan $_SESSION['userid']
                $userID = $_SESSION['userid'];

                // Sisipkan koneksi.php
                require 'koneksi_server.php';

                $query = "SELECT username, namalengkap FROM passwd WHERE userid = $userID";
                $result = $connectionServer->query($query);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $username = $row["username"];
                    $namalengkap = $row["namalengkap"];
                } else {
                    $username = "Nama Username";
                }

                echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
                echo '<span class="hidden-xs">' . $username . '</span>';
                echo '</a>';
            }
            ?>
            <ul class="dropdown-menu">
                <li class="user-details">
                    <p>Nama Pengguna: <strong><?php echo $namalengkap; ?></strong></p>
                </li>
            </ul>
        </li>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Saat tombol dropdown di-klik
        $(".dropdown-toggle").click(function (e) {
            e.preventDefault(); // Mencegah tindakan default dari link
            $(this).next(".dropdown-menu").slideToggle(); // Menampilkan atau menyembunyikan dropdown menu
        });
    });
</script>
