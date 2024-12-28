<?php
session_start();

// Periksa apakah pengguna sudah login
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Zahrarental</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <meta name="description" content="Zahrarental adalah penyedia layanan rental mobil terpercaya. Kami siap memenuhi kebutuhan perjalanan Anda dengan berbagai pilihan kendaraan dan layanan terbaik.">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/about.css?v=<?= time(); ?>" rel="stylesheet">
    <script>
        function handleProfileClick(event) {
            // Memeriksa apakah lebar layar kurang dari atau sama dengan 768px (contoh untuk perangkat mobile)
            if (window.innerWidth <= 768) {
                // Cegah dropdown muncul di perangkat mobile
                event.preventDefault();
                event.stopPropagation();
                
                // Redirect langsung ke halaman profil jika perangkat mobile
                window.location.href = "../profile/profile.php"; 
            }
        }
    </script>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container">
        <a class="navbar-brand fs-4 " href="Final.php">Zahrarental</a>
        <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Sidebar -->
        <div class="sidebar offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header" style="border-bottom: 1px solid black;">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Zahrarental</h5>
                <button type="button" class="btn-close btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <!-- sidebar body -->
            <div class="offcanvas-body d-flex flex-column flex-lg-row p-4 p-lg-0">
                <ul class="navbar-nav justify-content-center align-items-center fs-6 flex-grow-1 pe-3">
                    <li class="nav-item mx-2">
                        <a class="nav-link active" aria-current="page" href="Final.php">Home</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="About.php">About rental</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="../Pesanan/historypem.php">Pemesanan</a>
                    </li>
                </ul>
                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center gap-3 position-relative">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'user'): ?>
                <!-- Jika pengguna sudah login menampilkan profil -->
                <img src="../profile/imgprofil/<?php echo isset($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../profil/imgprofil/profiledefault.png'; ?>" 
                    alt="Profile Image" 
                    class="profile-img dropdown-toggle" 
                    id="dropdownMenuButton" 
                    data-bs-toggle="dropdown" 
                    aria-expanded="false" 
                    onclick="handleProfileClick(event)">
                <ul class="dropdown-menu dropdown-menu-end dropdown-profile" aria-labelledby="dropdownMenuButton">
                    <!-- Header Dropdown -->
                    <div class="dropdown-header">
                        <!-- Menampilkan gambar profil pengguna jika tersedia -->
                        <img src="../profile/imgprofil/<?php echo isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : '../profile/imgprofil/profiledefault.png'; ?>" alt="User Image" class="profile-img dropdown-toggle" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <!-- Menampilkan nama pengguna jika tersedia -->
                        <span><?php echo isset($_SESSION['user_name']) && !empty($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Nama Pengguna'; ?></span>
                    </div>
                    <li><a class="dropdown-item" href="../profile/profile.php"><i class="bi bi-person"></i>Edit Profil</a></li>
                    <li><a class="dropdown-item text-danger" href="../Login/logout.php"><i class="bi bi-box-arrow-right"></i>Logout</a></li>
                </ul>
            <?php else: ?>
                <!-- Jika pengguna belum login -->
                <a href="../Login/loginnew.php" class="text-black">Login</a>
                <a href="../Login/register.php" class="text-white text-decoration-none px-3 py-1 bg-primary rounded-4">Sign up</a>
            <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>


    <!-- About Section -->
    <div class="container">
        <div class="about-section">
            <div class="about-text">
                <h1>Tentang Rental</h1>
                <p>Selamat datang di ZahraRental,
                Kami adalah layanan rental mobil yang berkomitmen memberikan pengalaman penyewaan kendaraan yang mudah, nyaman, dan terpercaya. Dengan fokus pada kualitas layanan dan kepuasan pelanggan, kami menyediakan berbagai pilihan kendaraan yang selalu terjaga kebersihan dan performanya. Baik untuk perjalanan bisnis, liburan keluarga, maupun kebutuhan mendadak, ZahraRental hadir sebagai mitra transportasi andal yang siap menemani setiap langkah perjalanan Anda.</p>
            </div>
            <div class="about-image">
                <img src="../Foto/toyota.jpg" alt="About Us Image">
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
