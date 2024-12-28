<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "zahrarental");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit;
}

// Fungsi Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM list_mobil WHERE id='$id'");

    header("Location: Update.php");
    exit; // Tambahkan exit untuk memastikan redirect berhenti di sini
}

// Ambil data mobil dengan JOIN detail_mobil
$query = "SELECT list_mobil.id, list_mobil.nama, list_mobil.harga, list_mobil.gambar, 
                 detail_mobil.deskripsi, detail_mobil.spesifikasi, detail_mobil.stok, list_mobil.is_premium
          FROM list_mobil 
          LEFT JOIN detail_mobil ON list_mobil.id = detail_mobil.id_mobil";
$dataMobil = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mobil</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link rel="stylesheet" href="../css/update.css?v=<?= time(); ?>">
</head>
<body>
<div class="header">
    <a href="admin.php" style="color: #fff; text-decoration: none;">
        <h1>Daftar Mobil</h1>
    </a>
</div>
    <div class="tambah">
    <a href="tambahmobil.php" class="btn btn-tambah">Tambah</a>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Mobil</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Spesifikasi</th>
                <th>Stok</th>
                <th>Tipe</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($dataMobil)) { ?>
                <tr>
                    <td data-label="No"><?= $no++; ?></td>
                    <td data-label="Gambar">
                        <img src="../Foto/<?= $row['gambar']; ?>" alt="Gambar Mobil">
                    </td>
                    <td data-label="Nama Mobil"><?= $row['nama']; ?></td>
                    <td data-label="Harga"><?= number_format($row['harga']); ?></td>
                    <td data-label="Deskripsi"><?= $row['deskripsi']; ?></td>
                    <td data-label="Spesifikasi"><?= $row['spesifikasi']; ?></td>
                    <td data-label="Stok"><?= $row['stok']; ?></td>
                    <td data-label="Status Premium">
                        <?php echo ($row['is_premium'] === 'ya' ? 'Premium' : 'Biasa'); ?>
                    </td>
                    <td data-label="Aksi">
                        <a href="editmobil.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                        <a href="?hapus=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin hapus?');" class="btn btn-hapus">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
