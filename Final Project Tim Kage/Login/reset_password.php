<?php
session_start();
require '../DB/Dbzahra.php';

// Pastikan sesi valid
if (!isset($_SESSION['email_or_phone'])) {
    header("Location: lupa_password.php");
    exit();
}

$message = ''; // Inisialisasi variabel pesan

// Periksa jika form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $email_or_phone = $_SESSION['email_or_phone'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Periksa apakah password baru dan konfirmasi password sama
    if ($new_password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Password baru dan konfirmasi password tidak cocok!</div>";
    } elseif (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $new_password)) {
        // Validasi password minimal 8 karakter, kombinasi huruf dan angka
        $message = "<div class='alert alert-danger'>Password harus minimal 8 karakter dan mengandung kombinasi huruf dan angka!</div>";
    } else {
        // Hash password baru
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Perbarui password di database
        $query = "UPDATE users SET password = ? WHERE email_or_phone = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $new_password_hashed, $email_or_phone);
        $stmt->execute();

        // Hapus kode verifikasi dari tabel password_resets
        $query = "DELETE FROM password_resets WHERE user_id = (SELECT id FROM users WHERE email_or_phone = ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email_or_phone);
        $stmt->execute();

        // Hapus sesi dan arahkan ke halaman login
        unset($_SESSION['email_or_phone']);
        $message = "<div class='alert alert-success'>Password berhasil diubah. <a href='loginnew.php'>Login sekarang</a></div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body class="class-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Ganti Password</h1>

            <!-- Pesan Error atau Sukses -->
            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="" onsubmit="return validatePassword()">
                <div class="mb-3">
                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Password Baru" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password Baru" required>
                </div>
                <div id="error_message" class="text-danger mb-3"></div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Ganti Password</button>
            </form>
        </div>
    </div>
</body>
</html> 