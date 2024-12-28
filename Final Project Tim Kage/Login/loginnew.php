<?php
session_start();
require_once "../DB/zahra.php";
require_once "../class/Auth.php";

// Variabel untuk menampilkan pesan error
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Inisialisasi koneksi database
        $database = new Database();
        $conn = $database->getConnection();

        // Inisialisasi kelas autentikasi
        $auth = new Auth($conn);

        // Data input dari form
        $email_or_phone = $_POST['email_or_phone'];
        $password = $_POST['password'];

        // Proses login
        $user_id = $auth->login($email_or_phone, $password); // Pastikan metode login mengembalikan user_id

        // Periksa apakah entri profil pengguna sudah ada
        $query_check = "SELECT COUNT(*) AS count FROM profil_pengguna WHERE user_id = ?";
        $stmt_check = $conn->prepare($query_check);
        $stmt_check->bind_param("i", $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] == 0) {
            // Jika tidak ada, buat entri baru di tabel profil_pengguna
            $query_insert = "INSERT INTO profil_pengguna (user_id, nama_lengkap, tempat_tinggal, no_hp, foto_profil) 
                             VALUES (?, '', '', '', '')";
            $stmt_insert = $conn->prepare($query_insert);
            $stmt_insert->bind_param("i", $user_id);
            $stmt_insert->execute();
        }

        // Setelah login berhasil, arahkan pengguna ke halaman dashboard
        header("Location: ../Halaman/Final.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Foto/Logo.jpg" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body class="class-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Login</h1>
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" name="email_or_phone" class="form-control" placeholder="Nomor HP atau email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Log In</button>
            </form>
            <p class="mt-4 text-center"><a href="lupa_password.php">Lupa Password?</a></p>
            <p class="mt-1 text-center">Belum memiliki akun? <a href="register.php">Daftar dulu</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
