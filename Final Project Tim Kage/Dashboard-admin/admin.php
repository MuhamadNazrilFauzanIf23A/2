<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../css/admin.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body>
<!-- Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Zahrarental</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="datauser.php"><i class="fas fa-user me-2"></i>Data user</a></li>
                <li class="nav-item"><a class="nav-link" href="Databoking.php"><i class="fas fa-cart-arrow-down me-2"></i></i>Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="../Halaman/Final.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar untuk Laptop -->
        <div class="col-lg-2 sidebar d-none d-lg-block">
            <h4 class="text-center py-3">Zahrarental</h4>
            <ul class="nav flex-column">
                <li><a href="#"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                <li><a href="datauser.php"><i class="fas fa-user me-2"></i>data user</a></li>
                <li><a href="Databoking.php"><i class="fas fa-cart-arrow-down me-2"></i></i>Pesanan</a></li>
                <li><a href="../Halaman/Final.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="col-lg-10 col-12 p-4">
            <h3>Dashboard</h3>
            <p>Hai <strong>Admin</strong>, selamat datang kembali</p>

            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-12 col-md-4">
                    <div class="card-custom card-blue">
                        <i class="fas fa-user icon-large"></i>
                        <h5>data user</h5>
                        <a href="datauser.php" class="stretched-link"></a>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-12 col-md-4">
                    <div class="card-custom card-green">
                        <i class="fas fa-car icon-large"></i>
                        <h5>Mobil yang Dibooking</h5>
                        <a href="Databoking.php" class="stretched-link"></a>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-12 col-md-4">
                    <div class="card-custom card-red">
                        <i class="fas fa-user-edit icon-large"></i>
                        <h5>Update Mobil</h5>
                        <a href="update.php" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
