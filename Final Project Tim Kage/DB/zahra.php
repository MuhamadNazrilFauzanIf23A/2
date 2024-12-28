<?php
// Contoh kelas Database
class Database {
    private $conn;

    // Metode untuk menghubungkan ke database
    public function connect() {
        $this->conn = new mysqli("localhost", "root", "", "zahrarental");
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // Metode untuk mendapatkan koneksi database (ditambahkan)
    public function getConnection() {
        if (!$this->conn) {
            $this->connect(); // Buat koneksi jika belum ada
        }
        return $this->conn;
    }

    // Metode untuk menutup koneksi database
    public function closeConnection() {
        if ($this->conn) {
            $this->conn->close(); // Menutup koneksi
        }
    }
}
?>

