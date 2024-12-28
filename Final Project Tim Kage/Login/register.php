<?php
session_start();

// Memuat class Database
require '../DB/zahra.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email_or_phone = $_POST['email_or_phone'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi email atau nomor HP
        if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {
            if (!str_ends_with($email_or_phone, '@gmail.com')) {
                $error = "Email tidak valid. Harus berupa alamat @gmail.com.";
            }
        } elseif (preg_match('/^\d{10,12}$/', $email_or_phone)) {
            // Nomor HP valid
        } else {
            $error = "Nomor HP harus terdiri dari 10-12 angka!";
        }

        // Validasi password
        if (strlen($password) < 8) {
            $error = "Password harus memiliki minimal 8 karakter!";
        }

        if (empty($error) && $password !== $confirm_password) {
            $error = "Password dan konfirmasi password tidak cocok!";
        }

        if (empty($error)) {
            // Membuat instance class Database
            $db = new Database();
            $conn = $db->getConnection();

            // Periksa apakah email/nomor HP sudah ada di database
            $stmt = $conn->prepare("SELECT * FROM users WHERE email_or_phone = ?");
            $stmt->bind_param("s", $email_or_phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email atau nomor HP sudah terdaftar!";
            } else {
                // Enkripsi password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Simpan data ke database
                $stmt = $conn->prepare("INSERT INTO users (email_or_phone, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email_or_phone, $hashed_password);
                $stmt->execute();

                $success = "Akun berhasil dibuat! Silakan login.";
            }

            // Menutup koneksi database
            $db->closeConnection();
        }
    }
} catch (Exception $e) {
    $error = "Terjadi kesalahan: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css?v=<?= time(); ?>" rel="stylesheet">
</head>
    <body class="class-page">
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Daftar Akun</h1>
            <!-- Pesan Error atau Sukses -->
            <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
            <!-- Form -->
            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" name="email_or_phone" class="form-control" placeholder="Nomor HP atau email" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Daftar</button>
            </form>
            <p class="mt-3 text-center">Sudah memiliki akun? <a href="loginnew.php">Login, Sekarang!</a></p>
        </div>
    </div>
</body>
     <!-- JavaScript -->
     <script src="../Js/regist.js"></script>
    <!-- Bootsrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
