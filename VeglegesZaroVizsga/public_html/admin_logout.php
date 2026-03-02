<?php
require_once 'db.php';
secureSessionStart();

// Naplózás
if (isset($_SESSION['admin_id'])) {
    $database = new Database();
    $conn = $database->getConnection();
    
    try {
        $log_stmt = $conn->prepare("INSERT INTO admin_naplo (admin_id, admin_nev, admin_email, muvelet, reszletek) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->execute([
            $_SESSION['admin_id'],
            $_SESSION['admin_name'],
            $_SESSION['admin_email'],
            'Kijelentkezés',
            'Sikeres kijelentkezés',
        ]);
    } catch(Exception $e) {
        // Naplózási hiba esetén is folytatjuk
    }
}

function secureLogout() {
    // 1) Ha van session id, regeneráljuk (biztonság)
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    // 2) Töröljük a $_SESSION tömböt
    $_SESSION = [];

    // Session cookie törlése
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // 4) Végleges session destroy
    session_destroy();

    // 5) Ajánlott: küldjünk cache-control header-eket (extra biztonság)
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.

    // 6) Vissza a login oldalra
    header("Location: login");
    exit;
}

secureLogout();

?>