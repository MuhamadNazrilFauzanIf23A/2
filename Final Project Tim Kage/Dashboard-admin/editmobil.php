<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "zahrarental");

if (mysqli_connect_errno()) {
    echo "Koneksi database gagal: " . mysqli_connect_error();
    exit;
}

// Ambil ID mobil dari parameter URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data mobil berdasarkan ID, termasuk harga per 6, 12, dan 24 jam
    $query = "SELECT list_mobil.*, detail_mobil.deskripsi, detail_mobil.spesifikasi, detail_mobil.stok 
              FROM list_mobil 
              LEFT JOIN detail_mobil ON list_mobil.id = detail_mobil.id_mobil
              WHERE list_mobil.id = '$id'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    // Jika data tidak ditemukan
    if (!$data) {
        echo "Data tidak ditemukan!";
        exit;
    }
} else {
    echo "ID tidak valid!";
    exit;
}

// Proses update data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $tipe = $_POST['tipe']; // "tipe" akan menjadi "is_premium"
    $harga = $_POST['harga'];
    $harga_per6jam = $_POST['harga_per6jam'];
    $harga_per12jam = $_POST['harga_per12jam'];
    $harga_per24jam = $_POST['harga_per24jam'];
    $deskripsi = $_POST['deskripsi'];
    $spesifikasi = $_POST['spesifikasi'];
    $stok = $_POST['stok'];

    // Query update list_mobil
    $updateMobil = "UPDATE list_mobil SET 
                    nama = '$nama',
                    is_premium = '$tipe',
                    harga = '$harga',
                    harga_per6jam = '$harga_per6jam',
                    harga_per12jam = '$harga_per12jam',
                    harga_per24jam = '$harga_per24jam'
                    WHERE id = '$id'";


    // Eksekusi query update list_mobil
    if (!mysqli_query($koneksi, $updateMobil)) {
        echo "Error Update Mobil: " . mysqli_error($koneksi);
        exit;
    }

    // Cek apakah data detail_mobil ada
    $cekDetail = mysqli_query($koneksi, "SELECT * FROM detail_mobil WHERE id_mobil = '$id'");
    if (mysqli_num_rows($cekDetail) == 0) {
        // Insert jika belum ada
        $insertDetail = "INSERT INTO detail_mobil (id_mobil, deskripsi, spesifikasi, stok) 
                         VALUES ('$id', '$deskripsi', '$spesifikasi', '$stok')";
        if (!mysqli_query($koneksi, $insertDetail)) {
            echo "Error Insert Detail: " . mysqli_error($koneksi);
            exit;
        }
    } else {
        // Update jika data ada
        $updateDetail = "UPDATE detail_mobil SET 
                         deskripsi = '$deskripsi',
                         spesifikasi = '$spesifikasi',
                         stok = '$stok'
                         WHERE id_mobil = '$id'";
        if (!mysqli_query($koneksi, $updateDetail)) {
            echo "Error Update Detail: " . mysqli_error($koneksi);
            exit;
        }
    }

    // Redirect setelah update
    header("Location: Update.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil</title>
    <link rel="icon" href="../Foto/Logo.jpg" />
    <link href="../css/editmobil.css?v=<?= time(); ?>" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Edit Data Mobil</h2>
        <form method="POST">
            <label for="nama">Nama Mobil</label>
            <input type="text" name="nama" id="nama" value="<?= $data['nama']; ?>" required>
            <!-- Ganti input tipe menjadi select untuk memilih Premium / Biasa -->
            <label for="tipe">Tipe</label>
            <select name="tipe" id="tipe" required>
                <option value="ya" <?= ($data['is_premium'] == 'ya') ? 'selected' : ''; ?>>Premium</option>
                <option value="tidak" <?= ($data['is_premium'] == 'tidak') ? 'selected' : ''; ?>>Biasa</option>
            </select>



            <label for="harga">Harga</label>
            <input type="number" name="harga" id="harga" value="<?= $data['harga']; ?>" required>

            <label for="harga_per6jam">Harga Per 6 Jam</label>
            <input type="number" name="harga_per6jam" id="harga_per6jam" value="<?= $data['harga_per6jam']; ?>" required>

            <label for="harga_per12jam">Harga Per 12 Jam</label>
            <input type="number" name="harga_per12jam" id="harga_per12jam" value="<?= $data['harga_per12jam']; ?>" required>

            <label for="harga_per24jam">Harga Per 24 Jam</label>
            <input type="number" name="harga_per24jam" id="harga_per24jam" value="<?= $data['harga_per24jam']; ?>" required>

            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" rows="3" required><?= $data['deskripsi']; ?></textarea>

            <label for="spesifikasi">Spesifikasi</label>
            <textarea name="spesifikasi" id="spesifikasi" rows="3" required><?= $data['spesifikasi']; ?></textarea>

            <label for="stok">Stok</label>
            <input type="number" name="stok" id="stok" value="<?= $data['stok']; ?>" required>

            <button type="submit" name="update">Update</button>
            <a href="Update.php">Batal</a>
        </form>
    </div>
</body>
</html>
