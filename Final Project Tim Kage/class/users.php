<?php
class User {
    private $conn;

    public function __construct($dbConn) {
        $this->conn = $dbConn;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';
    }

    public function checkProfileExists($user_id) {
        $query = "SELECT COUNT(*) AS count FROM profil_pengguna WHERE user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function createDefaultProfile($user_id) {
        $query = "INSERT INTO profil_pengguna (user_id, nama_lengkap, tempat_tinggal, no_hp, foto_profil) 
                  VALUES (?, '', '', '', 'profiledefault.png')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    
    public function getProfile($userId) {
        $stmt = $this->conn->prepare("SELECT nama_lengkap, tempat_tinggal, no_hp, foto_profil FROM profil_pengguna WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            }
        }
        return null;
    }

    public function updateProfile($userId, $nama_lengkap, $tempat_tinggal, $no_hp, $foto_profil) {
        $stmt = $this->conn->prepare("UPDATE profil_pengguna SET nama_lengkap = ?, tempat_tinggal = ?, no_hp = ?, foto_profil = ? WHERE user_id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssi", $nama_lengkap, $tempat_tinggal, $no_hp, $foto_profil, $userId);
            return $stmt->execute();
        }
        return false;
    }
}
?>
