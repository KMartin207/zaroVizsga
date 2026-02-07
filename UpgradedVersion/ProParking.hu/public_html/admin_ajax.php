<?php
require_once 'db.php';
secureSessionStart();

if (!checkAdminSession()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_GET['action'] === 'change_company' && $_SESSION['admin_role'] === 'superadmin') {
    $company_id = $_GET['company_id'];

    // Validálás
    if ($company_id === 'all' || is_numeric($company_id)) {
        $_SESSION['active_company'] = $company_id;
        echo json_encode(['success' => true]);
    }
    else {
        echo json_encode(['success' => false, 'error' => 'Invalid company']);
    }
    exit();
}
?>