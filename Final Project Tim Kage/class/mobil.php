<?php
class Mobil {
    private $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    // Fungsi untuk mendapatkan detail mobil berdasarkan ID
    public function getMobilById($id) {
        $sql = "
            SELECT lm.id, lm.nama, lm.harga, lm.gambar, 
                   lm.is_premium,  
                   dm.deskripsi, dm.spesifikasi, dm.stok
            FROM list_mobil lm
            LEFT JOIN detail_mobil dm ON lm.id = dm.id_mobil
            WHERE lm.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getListMobil($filter = 'Semua') {
        $query = "SELECT * FROM list_mobil";

        if ($filter === 'Premium') {
            $query .= " WHERE is_premium = 'ya'";
        } elseif ($filter === 'Biasa') {
            $query .= " WHERE is_premium = 'tidak'";
        }

        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}
?>
