<?php
require_once 'db.php';
secureSessionStart();

// Ha már be van jelentkezve, irányítsuk át
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin.php");
    exit();
}

$error = '';
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Hibakeresés
    error_log("Login attempt - Email: $email, Password: $password");
    
    if ($email && $password) {
        try {
            // Admin felhasználó keresése
            $stmt = $conn->prepare("SELECT e.*, c.cnev FROM ember e LEFT JOIN ceg c ON e.cid = c.cid WHERE e.email = ? AND e.jogosultsag IN ('admin', 'superadmin')");
            $stmt->execute([$email]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($admin) {
                error_log("User found: " . $admin['enev'] . ", Role: " . $admin['jogosultsag']);
                
                // ÚJ: Jelszó ellenőrzés - ha van beállítva jelszó, akkor azt használjuk
                if (!empty($admin['ejelszo'])) {
                    // Ha van jelszó beállítva, akkor ellenőrizzük
                    if (password_verify($password, $admin['ejelszo'])) {
                        error_log("Password correct (hashed) for user: " . $admin['enev']);
                        loginSuccess($admin, $conn);
                    } else {
                        $error = "Hibás jelszó!";
                        error_log("Password incorrect (hashed) for user: " . $admin['enev']);
                    }
                    
                } else {
                    // Ha nincs jelszó beállítva, akkor a régi rendszer szerint
                    if (($admin['jogosultsag'] === 'superadmin' && $password === 'superadmin') || 
                        ($admin['jogosultsag'] === 'admin' && $password === 'admin')) {
                        
                        error_log("Password correct (default) for user: " . $admin['enev']);
                        loginSuccess($admin, $conn);
                    } else {
                        $error = "Hibás jelszó!";
                        error_log("Password incorrect (default) for user: " . $admin['enev']);
                    }
                }
            } else {
                $error = "Nem található admin felhasználó ezzel az email címmel!";
                error_log("No admin user found with email: $email");
            }
        } catch(PDOException $e) {
            $error = "Adatbázis hiba történt: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    } else {
        $error = "Kérjük, töltse ki mindkét mezőt!";
    }
}

// Bejelentkezés sikerének kezelése
function loginSuccess($admin, $conn) {
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $admin['eid'];
    $_SESSION['admin_name'] = $admin['enev'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['admin_role'] = $admin['jogosultsag'];
    $_SESSION['admin_company_id'] = $admin['cid'];
    $_SESSION['admin_company_name'] = $admin['cnev'] ?? 'Ismeretlen';
    $_SESSION['login_time'] = time();
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
    
    // Naplózás
    $log_stmt = $conn->prepare("INSERT INTO admin_naplo (admin_id, admin_nev, admin_email, muvelet, reszletek, ip_cim, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $log_stmt->execute([
        $admin['eid'],
        $admin['enev'],
        $admin['email'],
        'Bejelentkezés',
        'Sikeres bejelentkezés',
        $_SERVER['REMOTE_ADDR'],
        $_SERVER['HTTP_USER_AGENT']
    ]);
    
    // Átirányítás
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bejelentkezés - Parkolórendszer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: #1e293b;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            border: 1px solid #334155;
        }
        
        .login-header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #e2e8f0;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #475569;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #334155;
            color: #f8fafc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(59, 130, 246, 0.3);
        }
        
        .error-message {
            background: #7f1d1d;
            color: #fecaca;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
            font-size: 0.9rem;
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #475569;
            color: #94a3b8;
            font-size: 0.85rem;
        }
        
        .demo-info {
            background: #1e3a8a;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #dbeafe;
        }
        
        .user-list {
            background: #1e3a8a;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #dbeafe;
        }
        
        .user-list h4 {
            margin-bottom: 10px;
            color: #dbeafe;
        }
        
        .user-item {
            padding: 8px;
            border-bottom: 1px solid #3b82f6;
        }
        
        .user-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fas fa-lock"></i> Admin Bejelentkezés</h1>
            <p>Parkolórendszer Admin Felület</p>
        </div>
        
        <div class="login-form">
            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            
            <form method="POST">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email cím:</label>
                    <input type="email" id="email" name="email" class="form-control" required placeholder="admin@proparking.hu">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-key"></i> Jelszó:</label>
                    <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Bejelentkezés
                </button>
            </form>
            
            <div class="login-footer">
                <i class="fas fa-shield-alt"></i> Biztonságos kapcsolat
            </div>
        </div>
    </div>
</body>
</html>