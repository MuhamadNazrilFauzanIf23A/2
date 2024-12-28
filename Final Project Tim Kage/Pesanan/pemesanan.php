<?php
class Pemesanan {
    public $id;
    public $user_id;
    public $mobil_id;
    public $tanggal_mulai;
    public $tanggal_selesai;
    public $masa_sewa;
    public $paket_sewa;
    public $harga;
    public $sopir;
    public $waktu_pengambilan; // Properti baru
    public $status;

    // Constructor
    public function __construct($id, $user_id, $mobil_id, $tanggal_mulai, $tanggal_selesai, $masa_sewa, $paket_sewa, $harga, $sopir, $waktu_pengambilan, $status) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->mobil_id = $mobil_id;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_selesai = $tanggal_selesai;
        $this->masa_sewa = $masa_sewa;
        $this->paket_sewa = $paket_sewa;
        $this->harga = $harga;
        $this->sopir = $sopir;
        $this->waktu_pengambilan = $waktu_pengambilan; // Inisialisasi properti baru
        $this->status = $status;
    }

    // Getter untuk waktu pengambilan
    public function getWaktuPengambilan() {
        return $this->waktu_pengambilan;
    }

    // Getter untuk status
    public function getStatus() {
        return $this->status;
    }

    // Getter untuk harga
    public function getHarga() {
        return $this->harga;
    }

    // Getter untuk mobil
    public function getMobil($conn) {
        return Mobil::getMobilById($this->mobil_id, $conn);
    }

    // Static function untuk mendapatkan pemesanan berdasarkan user_id
    public static function getPemesananByUser($user_id, $conn) {
        $sql = "SELECT * FROM pemesanan WHERE user_id = ? ORDER BY tanggal_pemesanan DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $pemesananList = [];
        while ($row = $result->fetch_assoc()) {
            $pemesananList[] = new Pemesanan(
                $row['id'],
                $row['user_id'],
                $row['id_mobil'],
                $row['tanggal_mulai'],
                $row['tanggal_selesai'],
                $row['masa_sewa'],
                $row['paket_sewa'],
                $row['harga'],
                $row['sopir'],
                $row['waktu_pengambilan'], // Tambahkan ke konstruktor
                $row['status']
            );
        }
        return $pemesananList;
    }
}
?>
