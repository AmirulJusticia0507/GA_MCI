<?php
session_start();
if (isset($_SESSION['userid'])) {
    // Redirect ke halaman lain jika pengguna sudah login
    header("Location: index.php");
    exit;
}
include 'koneksi_server.php';
// require_once 'koneksi.php';

$loginError = ""; // Inisialisasi pesan kesalahan
function dekripsiKarakterasli($karakterasli) {
    $passwordawal = '';
    for ($i = 0; $i < strlen($karakterasli); $i++) {
        $passwordawal .= chr(ord(substr($karakterasli, $i, 1)) - 5);
    }
    return $passwordawal;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST["username"]);
    $passwordawal = htmlspecialchars($_POST["passwordawal"]);
    $karakterasii = htmlspecialchars($_POST["karakterasii"]); // Ambil karakterasii dari input

    // Hindari SQL Injection dengan menggunakan prepared statement
    $stmt = $connectionServer->prepare("SELECT userid, namalengkap, PASSWORD, user_blokir FROM passwd WHERE USERNAME = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Data pengguna ditemukan
        $stmt->bind_result($userid, $namalengkap, $storedPassword, $user_blokir);
        $stmt->fetch();

        $passwordawal = dekripsiKarakterasli($karakterasii);
        // $karakterasii = reverseKarakterasli($passwordawal);

        if ($passwordawal === $storedPassword || $karakterasii === $storedPassword) { // Ganti kondisi ini
            // Password cocok
            if ($user_blokir == 0) {
                // Akun tidak diblokir, izinkan login
                $_SESSION["userid"] = $userid;
                $_SESSION["username"] = $username;
                $_SESSION["passwordawal"] = $passwordawal;
                $_SESSION["namalengkap"] = $namalengkap;
                // Redirect ke halaman lain setelah login berhasil
                header("Location: index.php");
                exit;
            } else {
                // Akun diblokir
                $loginError = "Akun Anda telah diblokir. Hubungi administrator untuk informasi lebih lanjut.";
            }
        } else {
            // Password tidak cocok
            $loginError = "Password salah. Silakan coba lagi";
            // echo $karakterasii." - ".$storedPassword;
            // echo $passwordawal." - ".$storedPassword;
        }
    } else {
        // Pengguna dengan username tersebut tidak ditemukan
        $loginError = "Username tidak ditemukan. Silakan coba lagi.";
    }

    $stmt->close();
}

$connectionServer->close();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" href="img/logo_white.png" type="image/png">
    <title>E-FORM PERJALANAN DINAS BPRS HIK MCI</title>
    <style>
        .progress {
            display: none;
            margin-top: 10px;
        }
        .progress-bar {
            width: 0;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="img/logo_MCI-removebg-preview.png" alt="Image" class="img-fluid">
            </div>

            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="mb-4">
                            <?php if (!empty($loginError)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $loginError; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <h3>Sign In <br>
                            <b>E-FORM PERJALANAN DINAS</b></h3>
                        </div>
                        <form action="" method="POST" onsubmit="return validateForm()">
                            <div class="form-group first">
                                <label for="username">Username :</label>
                                <input type="text" class="form-control" placeholder="Username" name="username" id="username" required>
                            </div>
                            <div class="form-group last mb-4">
                                <label for="passwordawal">Password :</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Password" name="passwordawal" id="passwordawal" required oninput="generateKarakterasii(this.value)">
                                    <input type="password" class="form-control" placeholder="Masukkan Password" name="karakterasii" id="karakterasii" hidden>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="showPassword">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Log In" class="btn btn-block btn-primary" id="loginButton">
                            <!-- <a href="cekpassword.php" class="btn btn-success btn-block" value="Cek Password">Cek Password</a> -->
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressBar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
    // Temukan elemen-elemen yang diperlukan
    var passwordInput = document.getElementById("karakterasii");
    var showPasswordIcon = document.getElementById("showPassword");

    // Tambahkan event listener untuk menampilkan / menyembunyikan kata sandi
    showPasswordIcon.addEventListener("click", function () {
        if (passwordInput.type === "karakterasii") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "karakterasii";
        }
    });
    var passwordInput = document.getElementById("passwordawal");
    var showPasswordIcon = document.getElementById("showPassword");

    // Tambahkan event listener untuk menampilkan / menyembunyikan kata sandi
    showPasswordIcon.addEventListener("click", function () {
        if (passwordInput.type === "passwordawal") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "passwordawal";
        }
    });
</script>
<script>
    function validateForm() {
        var username = document.getElementById("username").value;
        var passwordawal = document.getElementById("passwordawal").value;
        var karakterasii = document.getElementById("karakterasii").value;

        // Add your validation logic here
        if (username === "" || passwordawal === "" || karakterasii === "") {
            alert("All fields are required.");
            return false;
        }

        // Show the progress bar
        var progressBar = document.getElementById("progressBar");
        progressBar.style.width = "0%";
        var loginButton = document.getElementById("loginButton");
        loginButton.disabled = true;
        var progress = 0;

        var interval = setInterval(function () {
            progress += 5;
            progressBar.style.width = progress + "%";
            if (progress >= 100) {
                clearInterval(interval);
                loginButton.disabled = false;
            }
        }, 1000);

        return true;
    }

    // Opsi 1
    function generateKarakterasii(passwordawal) {
        // Implementasi Anda untuk menghasilkan karakterasii dari passwordawal
        // Contoh sederhana: menggeser kode ASCII setiap karakter
        var karakterasii = "";
        for (var i = 0; i < passwordawal.length; i++) {
            var charCode = passwordawal.charCodeAt(i);
            karakterasii += String.fromCharCode(charCode + 5); // Geser kode ASCII
        }

        // Isi bidang karakterasii dengan hasilnya
        document.getElementById("karakterasii").value = karakterasii;
    }
    // Opsi 2
    // function generateKarakterasii(passwordawal) {
    //     // Implementasi Anda untuk menghasilkan karakterasii dari passwordawal
    //     var karakterasii = "";
    //     for (var i = 0; i < passwordawal.length; i++) {
    //         var charCode = passwordawal.charCodeAt(i);

    //         // Menambahkan offset hanya untuk karakter khusus
    //         if (isKarakterKhusus(passwordawal[i])) {
    //             karakterasii += String.fromCharCode(charCode + 5);
    //         } else {
    //             karakterasii += passwordawal[i];
    //         }
    //     }

    //     // Isi bidang karakterasii dengan hasilnya
    //     document.getElementById("karakterasii").value = karakterasii;
    // }

    // function isKarakterKhusus(char) {
    //     // Ganti dengan logika yang sesuai untuk menentukan apakah karakter adalah karakter khusus
    //     var karakterKhusus = "!@#$%^&*()-_=+[]{}|;:'\",.<>?";
    //     return karakterKhusus.includes(char);
    // }

</script>
</body>
</html>