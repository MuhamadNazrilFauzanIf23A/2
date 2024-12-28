<?php
class Auth {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email_or_phone, $password) {
        // Cek login sebagai admin
        if ($this->checkAdmin($email_or_phone, $password)) {
            $_SESSION['role'] = 'admin';
            header("Location: ../Dashboard-admin/admin.php");
            exit;
        }

        // Cek login sebagai user
        if ($this->checkUser($email_or_phone, $password)) {
            $_SESSION['role'] = 'user';
            header("Location: ../Halaman/Final.php");
            exit;
        }

        throw new Exception("Email atau password salah!");
    }

    private function checkAdmin($email_or_phone, $password) {
        $query = "SELECT * FROM admin WHERE email_or_phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email_or_phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['user_id'] = $admin['id'];
                return true;
            }
        }
        return false;
    }

    private function checkUser($email_or_phone, $password) {
        $query = "SELECT * FROM users WHERE email_or_phone = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email_or_phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                return true;
            }
        }
        return false;
    }
}
?>
