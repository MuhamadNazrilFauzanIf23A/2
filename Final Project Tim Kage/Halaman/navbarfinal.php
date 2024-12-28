<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary fixed-top">
    <div class="container">
        <a class="navbar-brand fs-4 " href="#">Zahrarental</a>
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
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
