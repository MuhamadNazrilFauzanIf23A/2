<?php
class RentalMobil {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    // Mengambil data mobil berdasarkan ID
    public function getMobilById($idMobil) {
        $stmt = $this->conn->prepare("SELECT id, nama, harga_per6jam, harga_per12jam, harga_per24jam, is_premium FROM list_mobil WHERE id = ?");
        $stmt->bind_param("i", $idMobil);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Mengambil stok mobil berdasarkan ID mobil
    public function getStokByMobilId($idMobil) {
        $stmt = $this->conn->prepare("SELECT stok FROM detail_mobil WHERE id_mobil = ?");
        $stmt->bind_param("i", $idMobil);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            return $data['stok'];
        }
        return null; 
    }

    // Mengurangi stok mobil setelah pemesanan
    public function updateStokMobil($idMobil, $stokBaru) {
        $stmt = $this->conn->prepare("UPDATE detail_mobil SET stok = ? WHERE id_mobil = ?");
        $stmt->bind_param("ii", $stokBaru, $idMobil);
        return $stmt->execute();
    }

    // Menyimpan data pemesanan
    public function simpanPemesanan($userId, $idMobil, $tanggalMulai, $tanggalSelesai, $masaSewa, $paketSewa, $harga, $sopir, $waktuPengambilan, $fileUnggahan, $status = 'pending') {
        $stmt = $this->conn->prepare("
            INSERT INTO pemesanan (user_id, id_mobil, tanggal_mulai, tanggal_selesai, masa_sewa, paket_sewa, harga, sopir, waktu_pengambilan, file_unggahan, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iississssss", 
            $userId, 
            $idMobil, 
            $tanggalMulai, 
            $tanggalSelesai, 
            $masaSewa, 
            $paketSewa, 
            $harga, 
            $sopir, 
            $waktuPengambilan, 
            $fileUnggahan, 
            $status
        );
        return $stmt->execute();
    }
}
?>
