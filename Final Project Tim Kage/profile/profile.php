<?php
// Mulai sesi
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login/loginnew.php");
    exit;
}

// Sambungkan ke database dan require class User
require '../DB/zahra.php';
require '../class/Users.php';

// Buat instance database dan user
$database = new Database();
$conn = $database->connect();
$user = new User($conn);    

// Ambil user_id dari session
$user_id = $_SESSION['user_id'];

// Periksa apakah profil pengguna sudah ada, jika tidak buat entri default
if (!$user->checkProfileExists($user_id)) {
    $user->createDefaultProfile($user_id);
}

// Ambil data profil pengguna
$profile = $user->getProfile($user_id);

// Proses saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input dari form atau gunakan data lama
    $nama_lengkap = !empty($_POST['fullName']) ? htmlspecialchars($_POST['fullName'], ENT_QUOTES) : $profile['nama_lengkap'];
    $tempat_tinggal = !empty($_POST['residence']) ? htmlspecialchars($_POST['residence'], ENT_QUOTES) : $profile['tempat_tinggal'];
    $no_hp = !empty($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES) : $profile['no_hp'];
    $foto_profil = $profile['foto_profil'];

    // Proses upload file
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'imgprofil/';
        $file_name = time() . '_' . basename($_FILES['profileImage']['name']);
        $target_file = $upload_dir . $file_name;

        // Validasi tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['profileImage']['tmp_name']);
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $target_file)) {
                $foto_profil = $file_name;
            } else {
                echo "<script>alert('Gagal mengunggah gambar.');</script>";
            }
        } else {
            echo "<script>alert('Tipe file tidak valid. Hanya diperbolehkan JPG, PNG, atau GIF.');</script>";
        }
    }

    // Update profil di database
    if ($user->updateProfile($user_id, $nama_lengkap, $tempat_tinggal, $no_hp, $foto_profil)) {
        echo "<script>alert('Data berhasil diperbarui!');</script>";
        header("Location: ../Halaman/Final.php");
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom CSS -->
    <link href="../css/profile.css?v=<?= time(); ?>" rel="stylesheet">
    <script>
        function handleProfileClick(event) {
            if (window.innerWidth <= 768) {
                event.preventDefault();
                event.stopPropagation();
                window.location.href = "../profile/profile.php";
            }
        }
    </script>
</head>
<body>
<div class="profile-container">
    <!-- Gambar profil -->
    <img src="imgprofil/<?php echo htmlspecialchars(!empty($profile['foto_profil']) ? $profile['foto_profil'] : 'profiledefault.png'); ?>"
         alt="Profile Picture" class="img-thumbnail">
    <p>Change Image ↓</p>   

<!-- Formulir edit profil -->
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="profileImage" class="form-label">Gambar Profil</label>
        <input type="file" class="form-control" name="profileImage" id="profileImage">
    </div>
    
    <div class="mb-3">
        <label for="fullName" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($profile['nama_lengkap'] ?? ''); ?>">
    </div>
    
    <div class="mb-3">
        <label for="residence" class="form-label">Tempat Tinggal Lengkap</label>
        <input type="text" class="form-control" id="residence" name="residence" placeholder="Masukkan tempat tinggal" value="<?php echo htmlspecialchars($profile['tempat_tinggal'] ?? ''); ?>">
    </div>
    
    <div class="mb-3">
        <label for="phone" class="form-label">No HP</label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor HP" value="<?php echo htmlspecialchars($profile['no_hp'] ?? ''); ?>">
    </div>
    
    <div class="button-group">
        <button type="submit" class="btn btn-primary btn-save">Save Profile</button>
        <a href="../Halaman/Final.php" class="btn btn-secondary btn-back">Kembali</a>
    </div>
</form>
</div>
</body>
</html>
