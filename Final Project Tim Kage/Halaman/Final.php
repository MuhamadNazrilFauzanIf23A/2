<?php
session_start();

// validasi apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';

// Memuat kelas-kelas
require '../DB/zahra.php';
require '../class/users.php';
require '../class/mobil.php';

// Membuat instance koneksi database
$database = new Database();
$conn = $database->connect();

// Membuat instance kelas User dan Mobil
$user = new User($conn);
$car = new Mobil($conn);

// Ambil profil pengguna jika login
if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $profile = $user->getProfile($userId);

    if ($profile) {
        $_SESSION['user_name'] = $profile['nama_lengkap'];
        $_SESSION['profile_image'] = $profile['foto_profil'];
    } else {
        $_SESSION['user_name'] = 'Nama Pengguna';
        $_SESSION['profile_image'] = 'default.jpg';
    }
}

// Proses update profil jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST" && $isLoggedIn) {
    $nama_lengkap = $_POST['fullName'] ?? '';
    $tempat_tinggal = $_POST['residence'] ?? '';
    $no_hp = $_POST['phone'] ?? '';
    $foto_profil = $_SESSION['profile_image'];

    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../profile/imgprofil/';
        $file_name = time() . '_' . basename($_FILES['profileImage']['name']);
        $target_file = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target_file)) {
            $foto_profil = $file_name;
        }
    }

    if ($user->updateProfile($userId, $nama_lengkap, $tempat_tinggal, $no_hp, $foto_profil)) {
        $_SESSION['user_name'] = $nama_lengkap;
        $_SESSION['profile_image'] = $foto_profil;
        echo "<script>alert('Profil berhasil diperbarui!');</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil.');</script>";
    }
}

// Menetapkan filter default
$allowedFilters = ['Semua', 'Premium', 'Biasa'];
$filter = isset($_GET['filter']) && in_array($_GET['filter'], $allowedFilters) ? $_GET['filter'] : 'Semua';

// Mengambil daftar mobil berdasarkan filter
$list_mobil = $car->getListMobil($filter);

// Tutup koneksi
$database->closeConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Foto/Logo.jpg" />
    <title>Zahrarental</title>
    <!-- Bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Fontawesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom css -->
    <link href="../css/final.css?v=<?= time(); ?>" rel="stylesheet">
    <script>
        function handleProfileClick(event) {
            // untuk mengubah foto profil menjadi klik
            if (window.innerWidth <= 768) {
                // dropdown tidak muncul saat layar hp
                event.preventDefault();
                event.stopPropagation();
                
                // pindahkan langsung ke halaman profil jika perangkat mobile
                window.location.href = "../profile/profile.php"; 
            }
        }
    </script>
</head>
<body>
    <?php
    // navbar
    include 'navbarfinal.php';
    ?>

    <!-- background -->
    <div class="background">
        <div class="text-container">
            <h1>Zahra Rental</h1>
            <p>Selamat datang di rental mobil zahra</p>
        </div>
    </div>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Daftar Mobil</h2>

    <!-- Tombol filter tipe -->
    <div class="d-flex justify-content-center mb-4">
        <div class="btn-group" role="group">
            <a href="?filter=Semua" class="btn btn-outline-primary <?= $filter === 'Semua' ? 'active' : '' ?>">Semua</a>
            <a href="?filter=Biasa" class="btn btn-outline-primary <?= $filter === 'Biasa' ? 'active' : '' ?>">Biasa</a>
            <a href="?filter=Premium" class="btn btn-outline-primary <?= $filter === 'Premium' ? 'active' : '' ?>">Premium</a>
        </div>
    </div>

    <!-- Card daftar mobil -->
    <div class="row">
        <?php if (!empty($list_mobil)): ?>
            <?php foreach ($list_mobil as $mobil): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" id="card-<?= $mobil['id']; ?>" onclick="window.location.href='../Detail/Dlmobil.php?id=<?= $mobil['id']; ?>'">
                        <img src="../Foto/<?= htmlspecialchars($mobil['gambar'], ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" alt="<?= htmlspecialchars($mobil['nama'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($mobil['nama'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="card-text"><?= $mobil['is_premium'] == 'ya' ? 'Premium' : 'Biasa'; ?></p>
                            <p class="card-text">Rp <?= number_format($mobil['harga'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Tidak ada mobil dengan kategori <?= htmlspecialchars($filter, ENT_QUOTES, 'UTF-8'); ?>.</p>
        <?php endif; ?>
    </div>

        <!-- modal syarat -->
    <div class="container mt-5">
        <?php include 'modal.html'; ?>
    </div>

    <footer class="mt-5">
        <div class="footerContainer">
            <div class="socialIcons">
                <ul>
                    <a href="https://www.instagram.com/zahrarentalkarawang?igsh=eWZkM3A2czV2M2V1"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://wa.me/c/6287730041815"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://tiktok.com/@zahrarent"><i class="fa-brands fa-tiktok"></i></a>
                </ul>
            </div>
            <div class="footerNav">
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="About.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="footerBottom">
                <p>Copyright &copy;2025; Designed by <span class="">Rental</span>
            </div>
        </div>
    </footer>
            
    <!-- javascript -->
    <script src="../Js/final.js"></script>
    </body>
    </html>
