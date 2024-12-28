<?php
session_start();
require '../DB/Dbzahra.php';

$message = ''; // Inisialisasi variabel pesan

// Pastikan email_or_phone ada di sesi
if (!isset($_SESSION['email_or_phone'])) {
    header("Location: lupa_password.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_SESSION['email_or_phone']; // Ambil dari sesi
    $verification_code = $_POST['verification_code']; // Ambil kode OTP dari input

    // Cek apakah kode OTP valid
    $query = "SELECT * FROM users u JOIN password_resets pr ON u.id = pr.user_id 
              WHERE u.email_or_phone = ? AND pr.verification_code = ? AND pr.expires_at > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email_or_phone, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['verified'] = true; // Tandai bahwa pengguna berhasil diverifikasi
        header("Location: reset_password.php"); // Arahkan ke halaman ganti password
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Kode verifikasi tidak valid atau sudah kedaluwarsa!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body class="class-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Verifikasi Kode</h1>

            <!-- Pesan Error atau Sukses -->
            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" name="verification_code" class="form-control" placeholder="Kode Verifikasi" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Verifikasi</button>
            </form>
        </div>
    </div>
</body>
</html>
