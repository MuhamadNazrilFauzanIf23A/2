<?php 
// Memasukkan koneksi database dari file eksternal
require '../DB/Dbzahra.php';

$koneksi = new mysqli("localhost", "root", "", "zahrarental");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mendapatkan semua profil pengguna
$sql = "SELECT 
            profil_pengguna.foto_profil, 
            profil_pengguna.nama_lengkap, 
            profil_pengguna.tempat_tinggal, 
            profil_pengguna.no_hp, 
            users.email_or_phone 
        FROM 
            profil_pengguna 
        JOIN 
            users 
        ON 
            profil_pengguna.user_id = users.id";

$result = $koneksi->query($sql);

// Mengecek apakah ada data
if (!$result) {
    die("Query gagal: " . $koneksi->error);
}

// Ambil semua data profil
$profiles = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link href="../css/datauser.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body>
<div class="header">
    <a href="admin.php" style="color: #fff; text-decoration: none;">
        <h1>Data pengguna</h1>
    </a>
</div>
<table>
    <thead>
        <tr>
            <th>Foto Profil</th>
            <th>Nama Lengkap</th>
            <th>Tempat Tinggal</th>
            <th>No. HP</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($profiles)): ?>
            <tr>
                <td colspan="5">Tidak ada data yang ditemukan.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($profiles as $profile): ?>
                <tr>
                    <td data-label="Foto Profil">
                        <?php if (!empty($profile['foto_profil'])): ?>
                            <a href="../profile/imgprofil/<?php echo htmlspecialchars($profile['foto_profil']); ?>" download>
                                Unduh Foto
                            </a>
                        <?php else: ?>
                            Foto tidak tersedia
                        <?php endif; ?>
                    </td>
                    <td data-label="Nama Lengkap"><?php echo htmlspecialchars($profile['nama_lengkap']); ?></td>
                    <td data-label="Tempat Tinggal"><?php echo htmlspecialchars($profile['tempat_tinggal']); ?></td>
                    <td data-label="No. HP"><?php echo htmlspecialchars($profile['no_hp']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($profile['email_or_phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>

<?php
// Menutup koneksi
$koneksi->close();
?>
