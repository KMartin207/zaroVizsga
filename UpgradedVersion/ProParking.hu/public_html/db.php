<?php
class Database
{
    private $host = "localhost";
    private $db_name = "lbzhvkxw_parking_db";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $exception) {
            die("Adatbázis kapcsolódási hiba: " . $exception->getMessage());
        }
        return $this->conn;
    }
}

// Biztonságos munkamenet indítása
function secureSessionStart()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}


// Jelszó ellenőrzés
function verifyPassword($input_password, $user_role)
{
    if ($user_role === 'superadmin') {
        return $input_password === 'superadmin';
    }
    elseif ($user_role === 'admin') {
        return $input_password === 'admin';
    }
    return false;
}




// Session hash frissítése bejelentkezéskor
function updateAdminSessionHash($admin_id)
{
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT ejelszo, email, last_updated FROM ember WHERE eid = ?");
        $stmt->execute([$admin_id]);
        $admin_data = $stmt->fetch();

        if ($admin_data) {
            $_SESSION['admin_data_hash'] = md5($admin_data['ejelszo'] . $admin_data['email'] . $admin_data['last_updated']);
        }
    }
    catch (Exception $e) {
    // Hiba kezelése
    }
}



?>