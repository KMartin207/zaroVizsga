<?php
require_once 'db.php';
secureSessionStart();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Session időbélyeg frissítése
    $_SESSION['last_activity'] = time();
    echo json_encode(['success' => true]);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
}
?>