<?php
session_start();
require '../DB/Dbzahra.php';

$message = ''; // Inisialisasi variabel pesan

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST['email_or_phone'];

    // Cek apakah email/nomor HP terdaftar
    $query = "SELECT * FROM users WHERE email_or_phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $verification_code = rand(100000, 999999); // Generate kode OTP (6 digit angka)

        // Gunakan MySQL untuk mengatur waktu kedaluwarsa (15 menit dari sekarang)
        $query = "INSERT INTO password_resets (user_id, verification_code, expires_at) 
                  VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE)) 
                  ON DUPLICATE KEY UPDATE verification_code = ?, expires_at = DATE_ADD(NOW(), INTERVAL 10 MINUTE)";

        // Memperbaiki bagian bind_param, hanya tiga parameter yang perlu diikat
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iss", $user['id'], $verification_code, $verification_code);
        $stmt->execute();

        // Kirim kode verifikasi via email atau nomor HP
        $subject = "Kode Verifikasi Reset Password";
        $message_body = "Kode verifikasi Anda adalah: " . $verification_code . "\nKode ini berlaku selama 10 menit.";

        // Gunakan fungsi mail atau integrasi dengan layanan SMS/email lainnya
        if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {
            mail($email_or_phone, $subject, $message_body);
        } else {
            // Jika menggunakan nomor HP, kirim melalui layanan SMS
            // Contoh: kirimSMS($email_or_phone, $message_body);
        }

        // Simpan email_or_phone ke sesi untuk digunakan di halaman verifikasi
        $_SESSION['email_or_phone'] = $email_or_phone;

        // Arahkan ke halaman verifikasi
        header("Location: verify_code.php");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Email/nomor HP tidak terdaftar!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body class="class-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;">
            <h1 class="text-center mb-4">Lupa Password</h1>

            <!-- Pesan Error atau Sukses -->
            <?php if (!empty($message)) echo $message; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <input type="text" name="email_or_phone" class="form-control" placeholder="Nomor HP atau email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Kirim Kode Verifikasi</button>
            </form>
            <p class="mt-3 text-center"><a href="loginnew.php">Kembali ke Login</a></p>
        </div>
    </div>
</body>
</html>
