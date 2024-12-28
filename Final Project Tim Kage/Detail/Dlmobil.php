<?php
session_start();

// Periksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';

// penghubungan
require_once "../DB/zahra.php";
require_once "../class/mobil.php";

// Inisialisasi koneksi database dan kelas mobil
$database = new Database();
$conn = $database->getConnection();
$mobilModel = new Mobil($conn);

// Ambil ID mobil dari URL
$id_mobil = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data mobil berdasarkan ID
$mobil = $mobilModel->getMobilById($id_mobil);

if (!$mobil) {
    echo "Mobil tidak ditemukan.";
    $database->closeConnection();
    exit;
}

// Inisialisasi variabel untuk pengecekan profil
$profileIncomplete = false;

// Periksa apakah pengguna sudah login dan jika profilnya lengkap
if ($isLoggedIn) {
    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi
    $query_check_profile = "SELECT nama_lengkap, tempat_tinggal, no_hp, foto_profil FROM profil_pengguna WHERE user_id = ?";
    $stmt_check_profile = $conn->prepare($query_check_profile);
    $stmt_check_profile->bind_param("i", $user_id);
    $stmt_check_profile->execute();
    $result_check_profile = $stmt_check_profile->get_result();
    $user_profile = $result_check_profile->fetch_assoc();

    // Cek jika profil belum lengkap
    $profileIncomplete = empty($user_profile['nama_lengkap']) || empty($user_profile['tempat_tinggal']) || empty($user_profile['no_hp']);
}

$database->closeConnection();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mobil - <?= htmlspecialchars($mobil['nama']); ?></title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/detail.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body>
<!-- Detail Mobil -->
<div class="container detail-container <?= $mobil['stok'] <= 0 ? 'unavailable' : ''; ?>">
    <div class="row">
        <!-- Gambar Mobil -->
        <div class="col-md-6 text-center">
            <img src="../Foto/<?= htmlspecialchars($mobil['gambar']); ?>" alt="<?= htmlspecialchars($mobil['nama']); ?>" class="img-fluid rounded">
        </div>
        
        <!-- Detail Mobil -->
        <div class="col-md-6">
            <h2><?= htmlspecialchars($mobil['nama']); ?></h2>
            <p><strong>Tipe:</strong> <?= $mobil['is_premium'] == 'ya' ? 'Premium' : 'Biasa'; ?></p>
            <p><strong>Harga:</strong> Rp <?= number_format($mobil['harga'], 0, ',', '.'); ?></p>
            <p><strong>Stok:</strong> <?= htmlspecialchars($mobil['stok']); ?> unit</p>

            <?php if ($mobil['stok'] > 0): ?>
                <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($mobil['deskripsi'])); ?></p>
                <p><strong>Spesifikasi:</strong> <?= nl2br(htmlspecialchars($mobil['spesifikasi'])); ?></p>

                <!-- Tombol Pesan Sekarang -->
                <button id="btnPesan" class="btn btn-primary" onclick="checkProfile()">
                    Pesan Sekarang
                </button>

            <?php else: ?>
                <div class="alert alert-danger">Mobil tidak tersedia</div>
            <?php endif; ?>

            <!-- Tombol Kembali -->
            <a href="../Halaman/Final.php" class="btn btn-secondary mt-2">Kembali</a>
        </div>
    </div>
</div>

    <!-- Modal jika profil belum lengkap -->
    <?php if ($isLoggedIn && $profileIncomplete): ?>
    <div class="modal fade" id="profilModal" tabindex="-1" aria-labelledby="profilModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profilModalLabel">Lengkapi Profil Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Untuk melanjutkan pemesanan, Anda harus melengkapi profil Anda terlebih dahulu. Silakan pergi ke halaman profil untuk memperbarui informasi Anda.</p>
                </div>
                <div class="modal-footer">
                    <a href="../profile/profile.php" class="btn btn-primary">Lengkapi Profil</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function checkProfile() {
            var profileIncomplete = <?php echo $profileIncomplete ? 'true' : 'false'; ?>;
            var idMobil = <?php echo json_encode($mobil['id']); ?>;

            if (profileIncomplete) {
                // Tampilkan modal jika profil belum lengkap
                var myModal = new bootstrap.Modal(document.getElementById('profilModal'));
                myModal.show();
            } else {
                // Arahkan ke halaman Booking.php dengan ID mobil
                window.location.href = "../Booking/Booking.php?id=" + idMobil;
            }
        }
    </script>
</body>
</html>
