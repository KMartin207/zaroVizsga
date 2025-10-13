<?php
require_once 'db.php';
secureSessionStart();



// Naplózási funkció
function logAdminAction($muvelet, $reszletek = '') {
    global $conn;
    
    try {
        $stmt = $conn->prepare("INSERT INTO admin_naplo (admin_id, admin_nev, admin_email, muvelet, reszletek) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_SESSION['admin_id'] ?? NULL,
            $_SESSION['admin_name'] ?? 'Ismeretlen',
            $_SESSION['admin_email'] ?? 'Ismeretlen',
            $muvelet,
            $reszletek,
        ]);
        return true;
    } catch(Exception $e) {
        error_log("Naplózási hiba: " . $e->getMessage());
        return false;
    }
}

// Biztonsági ellenőrzés
function checkAdminSession() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }
    return true;
}

// Átirányítás, ha nincs jogosultság
if (!checkAdminSession()) {
    header("Location: admin_login.php");
    exit();
}

// ADMIN: Csak a saját cégét láthatja
if ($_SESSION['admin_role'] === 'admin' && !isset($_SESSION['active_company'])) {
    $_SESSION['active_company'] = $_SESSION['admin_company_id'];
}

$active_company = $_SESSION['active_company'] ?? ($_SESSION['admin_role'] === 'superadmin' ? 'all' : $_SESSION['admin_company_id']);

// Cég nevének lekérése
function getCompanyName($cid) {
    global $conn;
    if ($cid === 'all') return 'Összes cég';
    
    try {
        $stmt = $conn->prepare("SELECT cnev FROM ceg WHERE cid = ?");
        $stmt->execute([$cid]);
        return $stmt->fetchColumn() ?: 'Ismeretlen cég';
    } catch(Exception $e) {
        return 'Ismeretlen cég';
    }
}

// Adatbázis kapcsolat
try {
    $database = new Database();
    $conn = $database->getConnection();
} catch(Exception $e) {
    die("Adatbázis kapcsolódási hiba.");
}



$message = "";
$message_type = "";


// VÁLTOZÓ DEFINICIÓ - MINDIG KELL
$show_password_modal = false;

// ELLENŐRZÉS: Ha a bejelentkezett adminnak nincs jelszava, akkor kötelező beállítania
try {
    // Ellenőrizzük, hogy van-e admin_id a sessionben
    if (isset($_SESSION['admin_id'])) {
        $stmt = $conn->prepare("SELECT ejelszo FROM ember WHERE eid = ?");
        $stmt->execute([$_SESSION['admin_id']]);
        $admin_data = $stmt->fetch();
        
        if ($admin_data && (empty($admin_data['ejelszo']) || $admin_data['ejelszo'] === '' || $admin_data['ejelszo'] === null)) {
            // Ha nincs jelszó, akkor megjelenítjük a kötelező jelszó beállítás modalt
            $show_password_modal = true;
        }
    }
} catch(Exception $e) {
    // Hibakezelés
    error_log("Jelszó ellenőrzési hiba: " . $e->getMessage());
}
        
        
        // Kötelező jelszó beállítás a bejelentkezett adminnak
        if (isset($_POST['set_required_password'])) {
            $jelszo = $_POST['jelszo'] ?? '';
            $jelszo_confirm = $_POST['jelszo_confirm'] ?? '';
            $current_admin_id = $_SESSION['admin_id'];
            
            if (strlen($jelszo) < 6) {
                $message = "A jelszónak minimum 6 karakter hosszúnak kell lennie!";
                $message_type = "error";
                $show_password_modal = true;
            } elseif ($jelszo !== $jelszo_confirm) {
                $message = "A jelszavak nem egyeznek!";
                $message_type = "error";
                $show_password_modal = true;
            } else {
                try {
                    // Jelszó hash-elése
                    $hashed_password = password_hash($jelszo, PASSWORD_DEFAULT);
                    
                    // Jelszó mentése az ember táblába
                    $stmt = $conn->prepare("UPDATE ember SET ejelszo = ? WHERE eid = ?");
                    $stmt->execute([$hashed_password, $current_admin_id]);
                    
                    $message = "Jelszó sikeresen beállítva! Most már teljes körűen használhatja az admin felületet.";
                    $message_type = "success";
                    logAdminAction('Kötelező jelszó beállítás', 'Saját jelszó beállítva');
                    
                    // Modal elrejtése
                    $show_password_modal = false;
                    
                } catch(PDOException $e) {
                    $message = "Hiba történt a jelszó mentése során: " . $e->getMessage();
                    $message_type = "error";
                    $show_password_modal = true;
                    logAdminAction('Jelszó mentési hiba', $e->getMessage());
                }
            }
        }
    
        //Értékelés törlés 
        if (isset($_POST['delete_review']) && $_SESSION['admin_role'] === 'superadmin') {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            
            if ($eid) {
                try {
                    $stmt = $conn->prepare("DELETE FROM ertekeles WHERE eid = ?");
                    $stmt->execute([$eid]);
                    $message = "Értékelés sikeresen törölve!";
                    $message_type = "success";
                    logAdminAction('Értékelés törlése', 'Értékelés ID: ' . $eid);
                
                } catch(PDOException $e) {
                    $message = "Hiba történt a cég törlése során.";
                    $message_type = "error";
                    logAdminAction('Értékelés törlési hiba', $e->getMessage());
                }
            }
        }

        // Értékelés szerkesztése (csak összes cég kiválasztásakor)
        if (isset($_POST['edit_review']) && $active_company === 'all') {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            $enev = filter_input(INPUT_POST, 'enev', FILTER_SANITIZE_SPECIAL_CHARS);
            $ecsillag = filter_input(INPUT_POST, 'ecsillag', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]]);
            $ekomment = filter_input(INPUT_POST, 'ekomment', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($eid && $enev && $ecsillag && $ekomment) {
                try {
                    $stmt = $conn->prepare("UPDATE ertekeles SET enev = ?, ecsillag = ?, ekomment = ? WHERE eid = ?");
                    $stmt->execute([$enev, $ecsillag, $ekomment, $eid]);
                    $message = "Értékelés sikeresen frissítve!";
                    $message_type = "success";
                    logAdminAction('Értékelés szerkesztése', 'Értékelés ID: ' . $eid);
                } catch(PDOException $e) {
                    $message = "Hiba történt a frissítés során.";
                    $message_type = "error";
                    logAdminAction('Értékelés szerkesztési hiba', $e->getMessage());
                }
            }
        }

        // Alkalmazott státusz váltás
        if (isset($_POST['toggle_employee_status'])) {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            if ($eid) {
                try {
                    $stmt = $conn->prepare("SELECT estatuszdolg, enev FROM ember WHERE eid = ?");
                    $stmt->execute([$eid]);
                    $alkalmazott = $stmt->fetch();
                    $current_status = $alkalmazott['estatuszdolg'];
                    $new_status = $current_status ? 0 : 1;
                    
                    $stmt = $conn->prepare("UPDATE ember SET estatuszdolg = ? WHERE eid = ?");
                    $stmt->execute([$new_status, $eid]);
                    $message = "Alkalmazott státusza sikeresen módosítva!";
                    $message_type = "success";
                    logAdminAction('Alkalmazott státusz váltás', $alkalmazott['enev'] . ' - ' . ($new_status ? 'Aktív' : 'Inaktív'));
                } catch(PDOException $e) {
                    $message = "Hiba történt a státusz módosítása során.";
                    $message_type = "error";
                    logAdminAction('Státusz váltási hiba', $e->getMessage());
                }
            }
        }

        // Alkalmazott szerkesztése
        if (isset($_POST['edit_employee'])) {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            $enev = filter_input(INPUT_POST, 'enev', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $etel = filter_input(INPUT_POST, 'etel', FILTER_SANITIZE_SPECIAL_CHARS);
            $jogosultsag = filter_input(INPUT_POST, 'jogosultsag', FILTER_SANITIZE_SPECIAL_CHARS);
            $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT);
            $ekomment = filter_input(INPUT_POST, 'ekomment', FILTER_SANITIZE_SPECIAL_CHARS);

            if ($eid && $enev && $email && $etel && $jogosultsag && $cid) {
                try {
                    
                    $stmt = $conn->prepare("UPDATE ember SET enev = ?, email = ?, etel = ?, jogosultsag = ?, cid = ?, ekomment = ? WHERE eid = ?");
                    $stmt->execute([$enev, $email, $etel, $jogosultsag, $cid, $ekomment, $eid]);
                    $message = "Alkalmazott sikeresen frissítve!";
                    $message_type = "success";
                    logAdminAction('Alkalmazott szerkesztése', $enev . ' (ID: ' . $eid . ')');
                } catch(PDOException $e) {
                    $message = "Hiba történt a frissítés során.";
                    $message_type = "error";
                    logAdminAction('Alkalmazott szerkesztési hiba', $e->getMessage());
                }
            }
        }

        // Cég hozzáadása (csak superadmin)
        if (isset($_POST['add_company']) && $_SESSION['admin_role'] === 'superadmin') {
            $cnev = filter_input(INPUT_POST, 'cnev', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($cnev) {
                try {
                    $stmt = $conn->prepare("INSERT INTO ceg (cnev) VALUES (?)");
                    $stmt->execute([$cnev]);
                    $message = "Cég sikeresen hozzáadva!";
                    $message_type = "success";
                    logAdminAction('Új cég hozzáadása', $cnev);
                } catch(PDOException $e) {
                    $message = "Hiba történt a cég hozzáadása során.";
                    $message_type = "error";
                    logAdminAction('Cég hozzáadási hiba', $e->getMessage());
                }
            }
        }

        // Alkalmazott hozzáadása
        if (isset($_POST['add_employee'])) {
            $enev = filter_input(INPUT_POST, 'enev', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $etel = filter_input(INPUT_POST, 'etel', FILTER_SANITIZE_SPECIAL_CHARS);
            $jogosultsag = 'user'; // Csak alkalmazottat lehet felvenni
            $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT);
            
            // Admin csak a saját cégéhez adhat hozzá alkalmazottat
            if ($_SESSION['admin_role'] === 'admin') {
                $cid = $_SESSION['admin_company_id'];
            }
            
            if ($enev && $email && $etel && $cid) {
                try {
                    $stmt = $conn->prepare("INSERT INTO ember (enev, email, etel, estatuszdolg, ekomment, jogosultsag, cid) VALUES (?, ?, ?, 1, 'Új alkalmazott', 'user', ?)");
                    $stmt->execute([$enev, $email, $etel, $cid]);
                    $message = "Alkalmazott sikeresen hozzáadva!";
                    $message_type = "success";
                    logAdminAction('Új alkalmazott hozzáadása', $enev);
                } catch(PDOException $e) {
                    $message = "Hiba történt az alkalmazott hozzáadása során.";
                    $message_type = "error";
                    logAdminAction('Alkalmazott hozzáadási hiba', $e->getMessage());
                }
            }
        }
        
        
        // Alkalmazott törlése - JOGOSULTSÁGOKKAL
        if (isset($_POST['delete_employee'])) {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            
            if ($eid) {
                try {
                    // Lekérjük a törlendő alkalmazott adatait
                    $stmt = $conn->prepare("SELECT enev, jogosultsag, cid FROM ember WHERE eid = ?");
                    $stmt->execute([$eid]);
                    $alkalmazott = $stmt->fetch();
                    
                    if (!$alkalmazott) {
                        $message = "Az alkalmazott nem található!";
                        $message_type = "error";
                    }
                    // Nem törölheti saját magát
                    elseif ($eid == $_SESSION['admin_id']) {
                        $message = "Nem törölheti saját magát!";
                        $message_type = "error";
                    }
                    // Csak superadmin törölhet admin/superadmin usereket
                    elseif (in_array($alkalmazott['jogosultsag'], ['admin', 'superadmin']) && $_SESSION['admin_role'] !== 'superadmin') {
                        $message = "Nincs jogosultsága admin felhasználók törlésére!";
                        $message_type = "error";
                    }
                    // Admin csak a saját cégéből törölhet (csak user jogosultságúakat)
                    elseif ($_SESSION['admin_role'] === 'admin' && $alkalmazott['cid'] != $_SESSION['admin_company_id']) {
                        $message = "Csak a saját cégéből törölhet alkalmazottat!";
                        $message_type = "error";
                    }
                    else {
                        $stmt = $conn->prepare("DELETE FROM ember WHERE eid = ?");
                        $stmt->execute([$eid]);
                        
                        $message = "Alkalmazott sikeresen törölve!";
                        $message_type = "success";
                        logAdminAction('Alkalmazott törlése', $alkalmazott['enev'] . ' (ID: ' . $eid . ')');
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a törlés során.";
                    $message_type = "error";
                    logAdminAction('Alkalmazott törlési hiba', $e->getMessage());
                }
            }
        }
        

        // Admin felhasználó hozzáadása (csak superadmin)
        if (isset($_POST['add_admin']) && $_SESSION['admin_role'] === 'superadmin') {
            $enev = filter_input(INPUT_POST, 'enev', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $etel = filter_input(INPUT_POST, 'etel', FILTER_SANITIZE_SPECIAL_CHARS);
            $jogosultsag = filter_input(INPUT_POST, 'jogosultsag', FILTER_SANITIZE_SPECIAL_CHARS);
            $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT);
            
            if ($enev && $email && $etel && $jogosultsag && $cid) {
                try {
                    $stmt = $conn->prepare("INSERT INTO ember (enev, email, etel, estatuszdolg, ekomment, jogosultsag, cid) VALUES (?, ?, ?, 1, 'Admin felhasználó', ?, ?)");
                    $stmt->execute([$enev, $email, $etel, $jogosultsag, $cid]);
                    $message = "Admin felhasználó sikeresen hozzáadva!";
                    $message_type = "success";
                    logAdminAction('Új admin hozzáadása', $enev . ' - ' . $jogosultsag);
                } catch(PDOException $e) {
                    $message = "Hiba történt az admin hozzáadása során.";
                    $message_type = "error";
                    logAdminAction('Admin hozzáadási hiba', $e->getMessage());
                }
            }
        }

        // Cég szerkesztése (csak superadmin)
        if (isset($_POST['edit_company']) && $_SESSION['admin_role'] === 'superadmin') {
            $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT);
            $cnev = filter_input(INPUT_POST, 'cnev', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($cid && $cnev) {
                try {
                    $stmt = $conn->prepare("UPDATE ceg SET cnev = ? WHERE cid = ?");
                    $stmt->execute([$cnev, $cid]);
                    $message = "Cég sikeresen frissítve!";
                    $message_type = "success";
                    logAdminAction('Cég szerkesztése', $cnev . ' (ID: ' . $cid . ')');
                } catch(PDOException $e) {
                    $message = "Hiba történt a cég frissítése során.";
                    $message_type = "error";
                    logAdminAction('Cég szerkesztési hiba', $e->getMessage());
                }
            }
        }

        // Cég törlése (csak superadmin)
        if (isset($_POST['delete_company']) && $_SESSION['admin_role'] === 'superadmin') {
            $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT);
            
            if ($cid) {
                try {
                    // Ellenőrizzük, hogy van-e alkalmazott a céghez
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM ember WHERE cid = ?");
                    $stmt->execute([$cid]);
                    $alkalmazott_count = $stmt->fetchColumn();
                    
                    if ($alkalmazott_count > 0) {
                        $message = "A cég nem törölhető, mert még vannak hozzá rendelt alkalmazottak!";
                        $message_type = "error";
                    } else {
                        $stmt = $conn->prepare("DELETE FROM ceg WHERE cid = ?");
                        $stmt->execute([$cid]);
                        $message = "Cég sikeresen törölve!";
                        $message_type = "success";
                        logAdminAction('Cég törlése', 'Cég ID: ' . $cid);
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a cég törlése során.";
                    $message_type = "error";
                    logAdminAction('Cég törlési hiba', $e->getMessage());
                }
            }
        }




// Szűrési paraméterek
$filter_jogosultsag = $_GET['filter_jogosultsag'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$naplo_filter_date = $_GET['naplo_filter_date'] ?? '';
$naplo_filter_type = $_GET['naplo_filter_type'] ?? 'current';

// Alkalmazottak lekérése szűréssel
$alkalmazottak_query = "SELECT e.*, c.cnev FROM ember e LEFT JOIN ceg c ON e.cid = c.cid WHERE 1=1";
$query_params = [];

// Cég szűrés
if ($_SESSION['admin_role'] === 'admin') {
    $alkalmazottak_query .= " AND e.cid = ?";
    $query_params[] = $_SESSION['admin_company_id'];
} elseif ($active_company !== 'all') {
    $alkalmazottak_query .= " AND e.cid = ?";
    $query_params[] = $active_company;
}

if ($filter_jogosultsag) {
    $alkalmazottak_query .= " AND e.jogosultsag = ?";
    $query_params[] = $filter_jogosultsag;
}

if ($filter_status !== '') {
    $alkalmazottak_query .= " AND e.estatuszdolg = ?";
    $query_params[] = $filter_status;
}

$alkalmazottak_query .= " ORDER BY e.enev ASC";

try {
    $stmt = $conn->prepare($alkalmazottak_query);
    $stmt->execute($query_params);
    $alkalmazottak = $stmt->fetchAll();
} catch(PDOException $e) {
    $alkalmazottak = [];
    $message = "Hiba történt az alkalmazottak betöltése során.";
    $message_type = "error";
}

// Adatok lekérése
try {
    // Értékelések (csak összes cég kiválasztásakor)
    if ($active_company === 'all') {
        $ertekelesek = $conn->query("SELECT * FROM ertekeles ORDER BY edatum DESC")->fetchAll();
    } else {
        $ertekelesek = [];
    }
    
    // Cégek
    $cegek = $conn->query("SELECT * FROM ceg")->fetchAll();
    
    // Aktív cég neve
    $active_company_name = getCompanyName($active_company);
    
    // Admin napló
    $admin_naplo_query = "SELECT * FROM admin_naplo WHERE 1=1";
    $admin_naplo_params = [];
    
    if ($naplo_filter_date) {
        $admin_naplo_query .= " AND DATE(created_at) = ?";
        $admin_naplo_params[] = $naplo_filter_date;
    }
    
    $admin_naplo_query .= " ORDER BY created_at DESC LIMIT 100";
    $stmt = $conn->prepare($admin_naplo_query);
    $stmt->execute($admin_naplo_params);
    $admin_naplo = $stmt->fetchAll();
    
    // Parkolási napló
    $parkolas_query = "SELECT n.*, e.enev, k.kazonosito 
                      FROM naplo n 
                      LEFT JOIN kartyaember ke ON n.keid = ke.keid 
                      LEFT JOIN ember e ON ke.eid = e.eid 
                      LEFT JOIN kartya k ON ke.kid = k.kid 
                      WHERE 1=1";
    $parkolas_params = [];
    
    // Cég szűrés parkolási naplóhoz
    if ($_SESSION['admin_role'] === 'admin') {
        $parkolas_query .= " AND e.cid = ?";
        $parkolas_params[] = $_SESSION['admin_company_id'];
    } elseif ($active_company !== 'all') {
        $parkolas_query .= " AND e.cid = ?";
        $parkolas_params[] = $active_company;
    }
    
    if ($naplo_filter_type === 'current') {
        $parkolas_query .= " AND n.ndatumig >= CURDATE()";
    } elseif ($naplo_filter_type === 'previous') {
        $parkolas_query .= " AND n.ndatumig < CURDATE()";
    }
    
    if ($naplo_filter_date) {
        $parkolas_query .= " AND n.ndatumtol = ?";
        $parkolas_params[] = $naplo_filter_date;
    }
    
    $parkolas_query .= " ORDER BY n.nidotol DESC";
    
    $stmt = $conn->prepare($parkolas_query);
    $stmt->execute($parkolas_params);
    $parkolas_naplo = $stmt->fetchAll();
    
    // Statisztikák
    $stats = [];
    try {
        // Értékelés statisztika (csak összes cég kiválasztásakor)
        if ($active_company === 'all') {
            $stats['ertekeles_atlag'] = $conn->query("SELECT AVG(ecsillag) FROM ertekeles")->fetchColumn();
            $stats['ertekeles_osszes'] = $conn->query("SELECT COUNT(*) FROM ertekeles")->fetchColumn();
            $stats['ertekeles_5_star'] = $conn->query("SELECT COUNT(*) FROM ertekeles WHERE ecsillag = 5")->fetchColumn();
        }
        
        // Alkalmazott statisztika
        $alkalmazott_query = "SELECT COUNT(*) FROM ember WHERE 1=1";
        $alkalmazott_params = [];
        
        if ($_SESSION['admin_role'] === 'admin') {
            $alkalmazott_query .= " AND cid = ?";
            $alkalmazott_params[] = $_SESSION['admin_company_id'];
        } elseif ($active_company !== 'all') {
            $alkalmazott_query .= " AND cid = ?";
            $alkalmazott_params[] = $active_company;
        }
        
        $stmt = $conn->prepare($alkalmazott_query);
        $stmt->execute($alkalmazott_params);
        $stats['alkalmazott_osszes'] = $stmt->fetchColumn();
        
        $stats['alkalmazott_aktiv'] = $conn->query("SELECT COUNT(*) FROM ember WHERE estatuszdolg = 1" . 
            ($_SESSION['admin_role'] === 'admin' ? " AND cid = " . $_SESSION['admin_company_id'] : 
            ($active_company !== 'all' ? " AND cid = $active_company" : "")))->fetchColumn();
            
        $stats['alkalmazott_inaktiv'] = $conn->query("SELECT COUNT(*) FROM ember WHERE estatuszdolg = 0" . 
            ($_SESSION['admin_role'] === 'admin' ? " AND cid = " . $_SESSION['admin_company_id'] : 
            ($active_company !== 'all' ? " AND cid = $active_company" : "")))->fetchColumn();
        
        // Parkolási statisztika
        $parkolas_stats_query = "SELECT COUNT(*) as osszes, 
                                COUNT(CASE WHEN ndatumig >= CURDATE() THEN 1 END) as aktiv,
                                COUNT(CASE WHEN ndatumig < CURDATE() THEN 1 END) as lejart
                                FROM naplo n
                                LEFT JOIN kartyaember ke ON n.keid = ke.keid 
                                LEFT JOIN ember e ON ke.eid = e.eid 
                                WHERE 1=1";
        $parkolas_stats_params = [];
        
        if ($_SESSION['admin_role'] === 'admin') {
            $parkolas_stats_query .= " AND e.cid = ?";
            $parkolas_stats_params[] = $_SESSION['admin_company_id'];
        } elseif ($active_company !== 'all') {
            $parkolas_stats_query .= " AND e.cid = ?";
            $parkolas_stats_params[] = $active_company;
        }
        
        $stmt = $conn->prepare($parkolas_stats_query);
        $stmt->execute($parkolas_stats_params);
        $parkolas_stats = $stmt->fetch();
        
        $stats['parkolas_osszes'] = $parkolas_stats['osszes'] ?? 0;
        $stats['parkolas_aktiv'] = $parkolas_stats['aktiv'] ?? 0;
        $stats['parkolas_lejart'] = $parkolas_stats['lejart'] ?? 0;
        
    } catch(PDOException $e) {
        $stats = [];
    }
    
} catch(PDOException $e) {
    $message = "Hiba történt az adatok betöltése során.";
    $message_type = "error";
}




?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Parkolórendszer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #6366f1;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark: #0f172a;
            --light: #1e293b;
            --gray: #64748b;
            --border: #334155;
            --text: #f8fafc;
            --text-muted: #94a3b8;
        }
        
        .light-mode {
            --dark: #f8fafc;
            --light: #ffffff;
            --gray: #64748b;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #475569;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--dark);
            color: var(--text);
            transition: all 0.3s ease;
            line-height: 1.6;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: var(--light);
            border-right: 1px solid var(--border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid var(--border);
            background: var(--light);
        }
        
        .sidebar-header h2 {
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-weight: 600;
            color: var(--text);
        }
        
        .user-info {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .user-info strong {
            display: block;
            margin-bottom: 5px;
            color: var(--text);
        }
        
        .user-info small {
            display: block;
            margin-bottom: 3px;
        }
        
        .superadmin-badge {
            background: var(--primary);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
            display: inline-block;
        }
        
        .company-badge {
            background: var(--success);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 5px;
            display: inline-block;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(59, 130, 246, 0.1);
            border-left-color: var(--primary);
            color: var(--text);
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 0;
            transition: all 0.3s ease;
        }
        
        .header {
            background: var(--light);
            border-bottom: 1px solid var(--border);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header-content h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            color: var(--text);
            font-weight: 700;
        }
        
        .header-content p {
            color: var(--text-muted);
            font-size: 1rem;
        }
        
        .highlight-info {
            color: var(--primary);
            font-weight: 600;
        }
        
        .highlight-success {
            color: var(--success);
            font-weight: 600;
        }
        
        .company-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .company-selector select {
            padding: 10px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--light);
            color: var(--text);
            font-size: 0.9rem;
            min-width: 200px;
        }
        
        /* Content Sections */
        .content-section {
            display: none;
            padding: 30px;
        }
        
        .content-section.active {
            display: block;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 25px;
            color: var(--text);
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 12px;
            color: var(--primary);
        }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: var(--light);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .stat-card.success i {
            color: var(--success);
        }
        
        .stat-card.warning i {
            color: var(--warning);
        }
        
        .stat-card.error i {
            color: var(--error);
        }
        
        .stat-card h3 {
            font-size: 2.2rem;
            margin-bottom: 8px;
            color: var(--text);
            font-weight: 700;
        }
        
        .stat-card p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        
        /* Tables */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: var(--light);
            border: 1px solid var(--border);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        
        th {
            background: rgba(59, 130, 246, 0.1);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        /* Status Badges */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .status-active {
            background: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background: #fecaca;
            color: #991b1b;
        }
        
        /* Role Badges */
        .role-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .role-superadmin {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .role-admin {
            background: #fef3c7;
            color: #92400e;
        }
        
        .role-user {
            background: #dcfce7;
            color: #166534;
        }
        
        /* Buttons */
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 8px 14px;
            font-size: 0.85rem;
        }
        
        .btn-edit {
            background: var(--primary);
            color: white;
        }
        
        .btn-edit:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background: var(--error);
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }
        
        .btn-add {
            background: var(--success);
            color: white;
        }
        
        .btn-add:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        .btn-warning {
            background: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        
        /* Table Actions */
        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light);
            color: var(--text);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Tabs */
        .tab-container {
            background: var(--light);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
        }
        
        .tabs {
            display: flex;
            background: rgba(59, 130, 246, 0.1);
            border-bottom: 1px solid var(--border);
        }
        
        .tab {
            padding: 15px 25px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background: var(--light);
        }
        
        .tab-content {
            padding: 25px;
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Filter Container */
        .filter-container {
            background: rgba(59, 130, 246, 0.05);
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }
        
        .filter-container h4 {
            margin-bottom: 15px;
            color: var(--text);
            font-size: 1.1rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--text-muted);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--border);
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: var(--text);
        }
        
        /* Messages */
        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        
        .message.success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid var(--success);
        }
        
        .message.error {
            background: #fecaca;
            color: #991b1b;
            border-left: 4px solid var(--error);
        }
        
        /* Stars */
        .stars {
            color: #fbbf24;
        }
        
        /* Count Badge */
        .count-badge {
            background: var(--border);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }
        
        .modal-content {
            background-color: var(--light);
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {transform: translateY(-50px); opacity: 0;}
            to {transform: translateY(0); opacity: 1;}
        }
        
        .modal-content h3 {
            padding: 20px 25px;
            background: rgba(59, 130, 246, 0.1);
            border-bottom: 1px solid var(--border);
            font-size: 1.3rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .modal-content form {
            padding: 25px;
        }
        
        .close {
            color: var(--text-muted);
            float: right;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            padding: 5px;
            margin-top: -5px;
        }
        
        .close:hover {
            color: var(--text);
        }
        
        /* Theme Toggle */
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 1001;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content {
                margin-left: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .company-selector {
                width: 100%;
            }
            
            .company-selector select {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .table-actions {
                flex-direction: column;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--primary);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1002;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
        }
        
        /* Kötelező jelszó modal stílusok */
        #requiredPasswordModal {
            pointer-events: all !important;
        }
        
        #requiredPasswordModal .close {
            display: none !important;
        }
        
        /* Input mezők eseménykezelése */
        #required_jelszo, #required_jelszo_confirm {
            border: 2px solid var(--border);
            transition: all 0.3s ease;
        }
        
        #required_jelszo:focus, #required_jelszo_confirm:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        /* Gomb stílusok */
        #requiredPasswordSubmit:disabled {
            background: #6b7280 !important;
            cursor: not-allowed !important;
            transform: none !important;
        }
        
        #requiredPasswordSubmit:not(:disabled):hover {
            background: #059669 !important;
            transform: translateY(-2px) !important;
        }

    </style>
</head>
<body class="dark-mode <?php echo $show_password_modal ? 'modal-open' : ''; ?>">
    <!-- Mobile Menu Toggle -->
    <div class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </div>
    
    <!-- Theme Toggle -->
    <div class="theme-toggle" id="themeToggle">
        <i class="fas fa-sun"></i>
    </div>
    
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cogs"></i> Admin Panel</h2>
                <div class="user-info">
                    <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong>
                    <small><?php echo htmlspecialchars($_SESSION['admin_email']); ?></small>
                    <small style="display: block; margin-top: 5px;">
                        <i class="fas fa-clock"></i> <?php echo date('H:i', $_SESSION['login_time']); ?>
                        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                            <span class="superadmin-badge">SUPERADMIN</span>
                        <?php else: ?>
                            <span class="company-badge"><?php echo htmlspecialchars($_SESSION['admin_company_name']); ?></span>
                        <?php endif; ?>
                    </small>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="#statisztikak" class="nav-link active" data-target="statisztikak"><i class="fas fa-chart-bar"></i> Statisztikák</a></li>
                <li><a href="#parkolas-naplo" class="nav-link" data-target="parkolas-naplo"><i class="fas fa-car"></i> Parkolási Napló</a></li>
                <?php if ($active_company === 'all'): ?>
                <li><a href="#ertekelesek" class="nav-link" data-target="ertekelesek"><i class="fas fa-star"></i> Értékelések</a></li>
                <?php endif; ?>
                <li><a href="#alkalmazottak" class="nav-link" data-target="alkalmazottak"><i class="fas fa-users"></i> Alkalmazottak</a></li>
                <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                <li><a href="#cegek" class="nav-link" data-target="cegek"><i class="fas fa-building"></i> Cégek</a></li>
                <li><a href="#admin-kezeles" class="nav-link" data-target="admin-kezeles"><i class="fas fa-user-shield"></i> Admin Kezelés</a></li>
                <?php endif; ?>
                <li><a href="#admin-naplo" class="nav-link" data-target="admin-naplo"><i class="fas fa-history"></i> Tevékenység Napló</a></li>
                <li><a href="admin_logout.php" style="color: #ef4444;" onclick="return confirm('Biztosan ki szeretne jelentkezni?')">
                    <i class="fas fa-sign-out-alt"></i> Kijelentkezés
                </a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <div class="header-content">
                    <h1>Adminisztrációs Felület</h1>
                    <p>
                        Parkolórendszer • 
                        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                            <strong class="highlight-info">Aktív cég: <?php echo htmlspecialchars($active_company_name); ?></strong>
                        <?php else: ?>
                            <strong class="highlight-success">Cég: <?php echo htmlspecialchars($_SESSION['admin_company_name']); ?></strong>
                        <?php endif; ?>
                    </p>
                </div>
                
                <!-- SUPERADMIN: Cég váltó -->
                <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                <div class="company-selector">
                    
                    <form method="POST" id="companyForm">
                        <label for="change_company" style="display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-muted);">
                            <i class="fas fa-building"></i> Cég váltása:
                        </label>
                        
                        
                        <select name="change_company" id="change_company" onchange="document.getElementById('companyForm').submit()">
                            <option value="all" <?php echo $active_company === 'all' ? 'selected' : ''; ?>>Összes cég</option>
                            <?php foreach ($cegek as $ceg): ?>
                                <option value="<?php echo htmlspecialchars($ceg['cid']); ?>" <?php echo $active_company == $ceg['cid'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ceg['cnev']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                
                <?php
                // Cég váltás kezelése
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_company'])) {
                    $new_company = $_POST['change_company'];
                    
                    // Validálás, hogy a kiválasztott cég létezik-e
                    $valid_companies = ['all'];
                    foreach ($cegek as $ceg) {
                        $valid_companies[] = $ceg['cid'];
                    }
                    
                    if (in_array($new_company, $valid_companies)) {
                        $_SESSION['active_company'] = $new_company;
                        $active_company = $new_company;
                        
                        // Oldal újratöltése
                        echo '<meta http-equiv="refresh" content="0" />';
                    }
                }
                ?>
                <?php endif; ?>
            </div>

            <!-- Üzenetek -->
            <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Statisztikák -->
            <div class="content-section active" id="statisztikak">
                <h2 class="section-title"><i class="fas fa-chart-bar"></i> Áttekintő Statisztikák</h2>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <h3><?php echo $stats['alkalmazott_osszes'] ?? 0; ?></h3>
                        <p>Összes alkalmazott</p>
                    </div>
                    
                    <div class="stat-card success">
                        <i class="fas fa-user-check"></i>
                        <h3><?php echo $stats['alkalmazott_aktiv'] ?? 0; ?></h3>
                        <p>Aktív alkalmazottak</p>
                    </div>
                    
                    <div class="stat-card warning">
                        <i class="fas fa-user-times"></i>
                        <h3><?php echo $stats['alkalmazott_inaktiv'] ?? 0; ?></h3>
                        <p>Inaktív alkalmazottak</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-car"></i>
                        <h3><?php echo $stats['parkolas_osszes'] ?? 0; ?></h3>
                        <p>Összes parkolás</p>
                    </div>
                    
                    <div class="stat-card success">
                        <i class="fas fa-car-side"></i>
                        <h3><?php echo $stats['parkolas_aktiv'] ?? 0; ?></h3>
                        <p>Aktív parkolások</p>
                    </div>
                    
                    <div class="stat-card error">
                        <i class="fas fa-car-crash"></i>
                        <h3><?php echo $stats['parkolas_lejart'] ?? 0; ?></h3>
                        <p>Lejárt parkolások</p>
                    </div>
                    
                    <?php if ($active_company === 'all'): ?>
                    <div class="stat-card">
                        <i class="fas fa-star"></i>
                        <h3><?php echo number_format($stats['ertekeles_atlag'] ?? 0, 1); ?></h3>
                        <p>Átlagos értékelés</p>
                    </div>
                    
                    <div class="stat-card">
                        <i class="fas fa-chart-line"></i>
                        <h3><?php echo $stats['ertekeles_osszes'] ?? 0; ?></h3>
                        <p>Összes értékelés</p>
                    </div>
                    
                    <div class="stat-card success">
                        <i class="fas fa-star-half-alt"></i>
                        <h3><?php echo $stats['ertekeles_5_star'] ?? 0; ?></h3>
                        <p>5 csillagos értékelés</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Parkolási Napló -->
            <div class="content-section" id="parkolas-naplo">
                <h2 class="section-title"><i class="fas fa-car"></i> Parkolási Napló</h2>
                
                <!-- Szűrő űrlap -->
                <div class="filter-container">
                    <h4><i class="fas fa-filter"></i> Szűrés</h4>
                    <form method="GET" class="filter-grid">
                        <input type="hidden" name="filter_jogosultsag" value="<?php echo htmlspecialchars($filter_jogosultsag); ?>">
                        <input type="hidden" name="filter_status" value="<?php echo htmlspecialchars($filter_status); ?>">
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="naplo_filter_type" style="font-size: 0.9rem;">Parkolás típusa:</label>
                            <select id="naplo_filter_type" name="naplo_filter_type" class="form-control">
                                <option value="current" <?php echo $naplo_filter_type === 'current' ? 'selected' : ''; ?>>Aktív parkolások</option>
                                <option value="previous" <?php echo $naplo_filter_type === 'previous' ? 'selected' : ''; ?>>Korábbi parkolások</option>
                                <option value="all" <?php echo $naplo_filter_type === 'all' ? 'selected' : ''; ?>>Összes parkolás</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="naplo_filter_date" style="font-size: 0.9rem;">Dátum:</label>
                            <input type="date" id="naplo_filter_date" name="naplo_filter_date" class="form-control" value="<?php echo htmlspecialchars($naplo_filter_date); ?>">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <button type="submit" class="btn btn-edit" style="width: 100%;">
                                <i class="fas fa-search"></i> Szűrés
                            </button>
                        </div>
                        
                        <?php if ($naplo_filter_date || $naplo_filter_type !== 'current'): ?>
                        <div class="form-group" style="margin-bottom: 0;">
                            <a href="?" class="btn btn-delete" style="width: 100%; text-decoration: none; text-align: center;">
                                <i class="fas fa-times"></i> Szűrők törlése
                            </a>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <?php if (count($parkolas_naplo) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Alkalmazott</th>
                                <th>Kártya</th>
                                <th>Parkolóhely</th>
                                <th>Érkezés</th>
                                <th>Távozás</th>
                                <th>Státusz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($parkolas_naplo as $parkolas): 
                                $status_class = strtotime($parkolas['ndatumig']) >= strtotime('today') ? 'status-active' : 'status-inactive';
                                $status_text = strtotime($parkolas['ndatumig']) >= strtotime('today') ? 'Aktív' : 'Lejárt';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($parkolas['nid']); ?></td>
                                <td><strong><?php echo htmlspecialchars($parkolas['enev'] ?? 'Ismeretlen'); ?></strong></td>
                                <td><?php echo htmlspecialchars($parkolas['kazonosito'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($parkolas['nparkhely']); ?></td>
                                <td><?php echo date('Y.m.d H:i', strtotime($parkolas['nidotol'])); ?></td>
                                <td><?php echo date('Y.m.d H:i', strtotime($parkolas['nidoig'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-car"></i>
                    <h3>Nincsenek parkolási adatok</h3>
                    <p>
                        <?php if ($naplo_filter_date || $naplo_filter_type !== 'current'): ?>
                        Nincs találat a megadott szűrési feltételekkel.
                        <?php else: ?>
                        Még nincsenek parkolási adatok a rendszerben.
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Értékelések (csak összes cég kiválasztásakor) -->
            <?php if ($active_company == 'all'): ?>
            <div class="content-section" id="ertekelesek">
                <h2 class="section-title"><i class="fas fa-star"></i> Értékelések Kezelése</h2>
                
                <?php if (count($ertekelesek) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Név</th>
                                <th>Értékelés</th>
                                <th>Szöveg</th>
                                <th>Dátum</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ertekelesek as $ertekeles): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ertekeles['eid']); ?></td>
                                <td><strong><?php echo htmlspecialchars($ertekeles['enev']); ?></strong></td>
                                <td>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star<?php echo $i > $ertekeles['ecsillag'] ? '-o' : ''; ?>"></i>
                                        <?php endfor; ?>
                                        <span style="margin-left: 8px; color: var(--text-muted);">(<?php echo htmlspecialchars($ertekeles['ecsillag']); ?>/5)</span>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($ertekeles['ekomment']); ?></td>
                                <td><?php echo date('Y.m.d H:i', strtotime($ertekeles['edatum'])); ?></td>
                                <td>
                                    <div class="table-actions">
                                        <button class="btn btn-edit btn-sm" onclick="openEditModal(<?php echo $ertekeles['eid']; ?>, '<?php echo addslashes($ertekeles['enev']); ?>', <?php echo $ertekeles['ecsillag']; ?>, `<?php echo addslashes($ertekeles['ekomment']); ?>`)">
                                            <i class="fas fa-edit"></i> Szerkesztés
                                        </button>
                                        <form method="POST" class="ajax-form">
                                        
                                            <input type="hidden" name="eid" value="<?php echo htmlspecialchars($ertekeles['eid']); ?>">
                                            <button type="submit" name="delete_review" class="btn btn-delete btn-sm" onclick="return confirm('Biztosan törölni szeretné ezt az értékelést?')">
                                                <i class="fas fa-trash"></i> Törlés
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h3>Nincsenek értékelések</h3>
                    <p>Még nem érkeztek értékelések a rendszerbe.</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Alkalmazottak -->
            <div class="content-section" id="alkalmazottak">
                <h2 class="section-title"><i class="fas fa-users"></i> Alkalmazottak</h2>
                
                <div class="tab-container">
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('employee-list')">Alkalmazott Lista</div>
                        <div class="tab" onclick="switchTab('add-employee')">Új Alkalmazott</div>
                    </div>
                    
                    <div id="employee-list" class="tab-content active">
                        <!-- Szűrő űrlap -->
                        <div class="filter-container">
                            <h4><i class="fas fa-filter"></i> Szűrés</h4>
                            <form method="GET" class="filter-grid">
                                <input type="hidden" name="naplo_filter_date" value="<?php echo htmlspecialchars($naplo_filter_date); ?>">
                                <input type="hidden" name="naplo_filter_type" value="<?php echo htmlspecialchars($naplo_filter_type); ?>">
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="filter_jogosultsag" style="font-size: 0.9rem;">Jogosultság:</label>
                                    <select id="filter_jogosultsag" name="filter_jogosultsag" class="form-control">
                                        <option value="">- Minden jogosultság -</option>
                                        <option value="user" <?php echo $filter_jogosultsag == 'user' ? 'selected' : ''; ?>>Alkalmazott</option>
                                        <option value="admin" <?php echo $filter_jogosultsag == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                                        <option value="superadmin" <?php echo $filter_jogosultsag == 'superadmin' ? 'selected' : ''; ?>>Super Admin</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="filter_status" style="font-size: 0.9rem;">Státusz:</label>
                                    <select id="filter_status" name="filter_status" class="form-control">
                                        <option value="">- Minden státusz -</option>
                                        <option value="1" <?php echo $filter_status === '1' ? 'selected' : ''; ?>>Aktív</option>
                                        <option value="0" <?php echo $filter_status === '0' ? 'selected' : ''; ?>>Inaktív</option>
                                    </select>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <button type="submit" class="btn btn-edit" style="width: 100%;">
                                        <i class="fas fa-search"></i> Szűrés
                                    </button>
                                </div>
                                
                                <?php if ($filter_jogosultsag || $filter_status !== ''): ?>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <a href="?" class="btn btn-delete" style="width: 100%; text-decoration: none; text-align: center;">
                                        <i class="fas fa-times"></i> Szűrők törlése
                                    </a>
                                </div>
                                <?php endif; ?>
                            </form>
                        </div>

                        <?php if (count($alkalmazottak) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Név</th>
                                        <th>Email</th>
                                        <th>Telefon</th>
                                        <th>Státusz</th>
                                        <th>Jogosultság</th>
                                        <th>Cég</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alkalmazottak as $alkalmazott): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($alkalmazott['eid']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($alkalmazott['enev']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($alkalmazott['email']); ?></td>
                                        <td><?php echo htmlspecialchars($alkalmazott['etel']); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $alkalmazott['estatuszdolg'] ? 'status-active' : 'status-inactive'; ?>">
                                                <?php echo $alkalmazott['estatuszdolg'] ? 'Aktív' : 'Inaktív'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="role-badge role-<?php echo htmlspecialchars($alkalmazott['jogosultsag']); ?>">
                                                <?php echo htmlspecialchars($alkalmazott['jogosultsag']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($alkalmazott['cnev'] ?? 'N/A'); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                
                                                
                                                
                                                <!-- SZERKESZTÉS GOMB - CSAK SUPERADMIN LÁTHATJA ADMINOKNÁL -->
                                                <?php if (
                                                    ($_SESSION['admin_role'] === 'superadmin' && $alkalmazott['jogosultsag'] !== 'superadmin') ||
                                                    ($_SESSION['admin_role'] === 'admin' && $alkalmazott['jogosultsag'] === 'user' && $alkalmazott['cid'] == $_SESSION['admin_company_id'])
                                                ): ?>
                                                <button class="btn btn-edit btn-sm" 
                                                        onclick="openEmployeeModal(
                                                            <?php echo (int)$alkalmazott['eid']; ?>,
                                                            '<?php echo htmlspecialchars($alkalmazott['enev'] ?? '', ENT_QUOTES); ?>',
                                                            '<?php echo htmlspecialchars($alkalmazott['email'] ?? '', ENT_QUOTES); ?>',
                                                            '<?php echo htmlspecialchars($alkalmazott['etel'] ?? '', ENT_QUOTES); ?>',
                                                            '<?php echo htmlspecialchars($alkalmazott['jogosultsag'] ?? 'user', ENT_QUOTES); ?>',
                                                            <?php echo (int)($alkalmazott['cid'] ?? 0); ?>,
                                                            '<?php echo htmlspecialchars($alkalmazott['ekomment'] ?? '', ENT_QUOTES); ?>'
                                                        )">
                                                    <i class="fas fa-edit"></i> Szerkesztés
                                                </button>
                                                <?php endif; ?>
                                                
                                                
                                                
                                                <!-- TÖRLÉS GOMB - CSAK SUPERADMIN LÁTHATJA ADMINOKNÁL -->
                                                <?php if (
                                                    // Superadmin csak admin és user jogosultságúakat törölhet (NEM superadmin-t)
                                                    ($_SESSION['admin_role'] === 'superadmin' && $alkalmazott['jogosultsag'] !== 'superadmin') ||
                                                    // Admin csak sima usereket törölhet (nem admin/superadmin)
                                                    ($_SESSION['admin_role'] === 'admin' && $alkalmazott['jogosultsag'] === 'user' && $alkalmazott['cid'] == $_SESSION['admin_company_id'])
                                                ): ?>
                                                <form method="POST" class="ajax-form">
                                                    <input type="hidden" name="eid" value="<?php echo htmlspecialchars($alkalmazott['eid']); ?>">
                                                    <button type="submit" name="delete_employee" class="btn btn-delete btn-sm" onclick="return confirm('Biztosan törölni szeretné ezt az alkalmazottat?')">
                                                        <i class="fas fa-trash"></i> Törlés
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h3>Nincsenek alkalmazottak</h3>
                            <p>
                                <?php if ($filter_jogosultsag || $filter_status !== ''): ?>
                                Nincs találat a megadott szűrési feltételekkel.
                                <?php else: ?>
                                Még nincsenek alkalmazottak hozzáadva a rendszerhez.
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="add-employee" class="tab-content">

                        <form method="POST">
                            <div class="form-group">
                                <label for="enev">Név:</label>
                                <input type="text" id="enev" name="enev" class="form-control" required maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required maxlength="50">
                            </div>
                            
                            <div class="form-group">
                                <label for="etel">Telefon:</label>
                                <input type="text" id="etel" name="etel" class="form-control" required maxlength="12">
                            </div>
                            
                            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                            <div class="form-group">
                                <label for="cid">Cég:</label>
                                <select id="cid" name="cid" class="form-control" required>
                                    <option value="">- Válasszon céget -</option>
                                    <?php foreach ($cegek as $ceg): ?>
                                        <option value="<?php echo htmlspecialchars($ceg['cid']); ?>"><?php echo htmlspecialchars($ceg['cnev']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php else: ?>
                            <input type="hidden" name="cid" value="<?php echo $_SESSION['admin_company_id']; ?>">
                            <?php endif; ?>
                            
                            <button type="submit" name="add_employee" class="btn btn-add">
                                <i class="fas fa-plus"></i> Alkalmazott Hozzáadása
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SUPERADMIN: Cégek kezelése -->
            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
            <div class="content-section" id="cegek">
                <h2 class="section-title"><i class="fas fa-building"></i> Cégek Kezelése</h2>
                
                <div class="tab-container">
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('company-list')">Cég Lista</div>
                        <div class="tab" onclick="switchTab('add-company')">Új Cég</div>
                    </div>
                    
                    <div id="company-list" class="tab-content active">
                        <?php if (count($cegek) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cég Név</th>
                                        <th>Alkalmazottak</th>
                                        <th>Adminok</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cegek as $ceg): 
                                        $alkalmazott_count = 0;
                                        $admin_count = 0;
                                        
                                        try {
                                            $stmt = $conn->prepare("SELECT COUNT(*) FROM ember WHERE cid = ?");
                                            $stmt->execute([$ceg['cid']]);
                                            $alkalmazott_count = $stmt->fetchColumn();
                                            
                                            $stmt = $conn->prepare("SELECT COUNT(*) FROM ember WHERE cid = ? AND jogosultsag IN ('admin', 'superadmin')");
                                            $stmt->execute([$ceg['cid']]);
                                            $admin_count = $stmt->fetchColumn();
                                        } catch(PDOException $e) {}
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ceg['cid']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($ceg['cnev']); ?></strong></td>
                                        <td>
                                            <span class="count-badge"><?php echo $alkalmazott_count; ?> alkalmazott</span>
                                        </td>
                                        <td>
                                            <span class="count-badge"><?php echo $admin_count; ?> admin</span>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="?change_company=<?php echo htmlspecialchars($ceg['cid']); ?>" class="btn btn-edit btn-sm">
                                                    <i class="fas fa-check"></i> Kiválasztás
                                                </a>
                                                <button class="btn btn-edit btn-sm" onclick="openCompanyModal(<?php echo $ceg['cid']; ?>, '<?php echo addslashes($ceg['cnev']); ?>')">
                                                    <i class="fas fa-edit"></i> Szerkesztés
                                                </button>

                                                <form method="POST" class="ajax-form">
                                                    <input type="hidden" name="cid" value="<?php echo htmlspecialchars($ceg['cid']); ?>">
                                                    <button type="submit" name="delete_company" class="btn btn-delete btn-sm" onclick="return confirm('Biztosan törölni szeretné ezt a céget?')">
                                                        <i class="fas fa-trash"></i> Törlés
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <h3>Nincsenek cégek</h3>
                            <p>Még nincsenek cégek hozzáadva a rendszerhez.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="add-company" class="tab-content">

                        <form method="POST">
                            <div class="form-group">
                                <label for="cnev">Cég Név:</label>
                                <input type="text" id="cnev" name="cnev" class="form-control" required maxlength="100">
                            </div>
                            
                            <button type="submit" name="add_company" class="btn btn-add">
                                <i class="fas fa-plus"></i> Cég Hozzáadása
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- SUPERADMIN: Admin felhasználó hozzáadása -->
            <div class="content-section" id="admin-kezeles">
                <h2 class="section-title"><i class="fas fa-user-shield"></i> Admin Felhasználó Hozzáadása</h2>

                <form method="POST">
                    <div class="form-group">
                        <label for="enev">Admin Név:</label>
                        <input type="text" id="enev" name="enev" class="form-control" required maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="etel">Telefon:</label>
                        <input type="text" id="etel" name="etel" class="form-control" required maxlength="12">
                    </div>
                    
                    <div class="form-group">
                        <label for="jogosultsag">Jogosultság:</label>
                        <select id="jogosultsag" name="jogosultsag" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="cid">Cég:</label>
                        <select id="cid" name="cid" class="form-control" required>
                            <option value="">- Válasszon céget -</option>
                            <?php foreach ($cegek as $ceg): ?>
                                <option value="<?php echo htmlspecialchars($ceg['cid']); ?>"><?php echo htmlspecialchars($ceg['cnev']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" name="add_admin" class="btn btn-add">
                        <i class="fas fa-plus"></i> Admin Hozzáadása
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Admin tevékenység napló -->
            <a name="admin-naplo"></a>
            <div class="content-section" id="admin-naplo">
                <h2 class="section-title"><i class="fas fa-history"></i> Admin Tevékenység Napló</h2>
                
                <!-- Szűrő űrlap -->
                <div class="filter-container">
                    <h4><i class="fas fa-filter"></i> Szűrés</h4>

                    <form method="GET" action="?filter_jogosultsag=&filter_status=&naplo_filter_type=&naplo_filter_date=#admin-naplo" class="filter-grid">
                        <input type="hidden" name="filter_jogosultsag" value="<?php echo htmlspecialchars($filter_jogosultsag); ?>">
                        <input type="hidden" name="filter_status" value="<?php echo htmlspecialchars($filter_status); ?>">
                        <input type="hidden" name="naplo_filter_type" value="<?php echo htmlspecialchars($naplo_filter_type); ?>">
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="naplo_filter_date" style="font-size: 0.9rem;">Dátum:</label>
                            <input type="date" id="naplo_filter_date" name="naplo_filter_date" class="form-control" value="<?php echo htmlspecialchars($naplo_filter_date); ?>">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <button type="submit" class="btn btn-edit" style="width: 100%;">
                                <i class="fas fa-search"></i> Szűrés
                            </button>
                        </div>
                        
                        <?php if ($naplo_filter_date): ?>
                        <div class="form-group" style="margin-bottom: 0;">
                            <a href="?" class="btn btn-delete" style="width: 100%; text-decoration: none; text-align: center;">
                                <i class="fas fa-times"></i> Szűrők törlése
                            </a>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <?php if (count($admin_naplo) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Admin</th>
                                <th>Művelet</th>
                                <th>Részletek</th>
                                <th>Időpont</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($admin_naplo as $naplo): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($naplo['aid']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($naplo['admin_nev']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($naplo['admin_email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($naplo['muvelet']); ?></td>
                                <td><?php echo htmlspecialchars($naplo['reszletek']); ?></td>
                                <td><?php echo date('Y.m.d H:i', strtotime($naplo['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-history"></i>
                    <h3>Nincs tevékenység</h3>
                    <p>Még nem történt tevékenység a rendszerben.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    
    
    
    
    
    
    <!-- Jelszó beállítás Modal -->
    <!-- Kötelező jelszó beállítás Modal - NEM BEZÁRHATÓ -->
    <div id="requiredPasswordModal" class="modal" style="display: <?php echo $show_password_modal ? 'block' : 'none'; ?>; background-color: rgba(0,0,0,0.95); z-index: 9999; position: fixed; top: 0; left: 0; right: 0; bottom: 0;">
        <div class="modal-content" style="max-width: 500px; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
            <!-- NINCS BEZÁRÁS GOMB - nem lehet kilépni -->
            <h3 style="background: #ef4444; color: white; padding: 20px 25px; margin: 0; display: flex; align-items: center; gap: 10px; border-radius: 12px 12px 0 0;">
                <i class="fas fa-shield-alt"></i> Kötelező jelszó beállítás
            </h3>
            
            <form method="POST" id="requiredPasswordForm" style="padding: 25px;">
                <input type="hidden" name="set_required_password" value="true">
                
                <div style="text-align: center; margin-bottom: 25px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ef4444; margin-bottom: 15px;"></i>
                    <h4 style="color: var(--text); margin-bottom: 10px;">Biztonsági beállítás</h4>
                    <p style="color: var(--text-muted); line-height: 1.5;">
                        Üdvözöljük <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong>!<br>
                        A folytatáshoz kötelező jelszót beállítania a fiókjához.
                    </p>
                </div>
                
                <div class="form-group">
                    <label for="required_jelszo"><i class="fas fa-lock"></i> Új jelszó:</label>
                    <input type="password" id="required_jelszo" name="jelszo" class="form-control" required 
                           minlength="6" placeholder="Minimum 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="required_jelszo_confirm"><i class="fas fa-lock"></i> Jelszó megerősítése:</label>
                    <input type="password" id="required_jelszo_confirm" name="jelszo_confirm" class="form-control" required 
                           minlength="6" placeholder="Írja be újra a jelszót">
                </div>
                
                <div id="requiredPasswordMatchMessage" style="display: none; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;"></div>
                
                <div style="display: flex; gap: 10px; margin-top: 25px;">
                    <button type="submit" id="requiredPasswordSubmit" class="btn btn-add" style="flex: 1; padding: 12px;" disabled>
                        <i class="fas fa-save"></i> Jelszó mentése és folytatás
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    

    <!-- Értékelés Szerkesztési Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3><i class="fas fa-edit"></i> Értékelés Szerkesztése</h3>

            <form method="POST" id="editForm">
                <input type="hidden" name="eid" id="edit_eid">
                
                <div class="form-group">
                    <label for="edit_enev">Név:</label>
                    <input type="text" id="edit_enev" name="enev" class="form-control" required maxlength="100">
                </div>
                
                <div class="form-group">
                    <label for="edit_ecsillag">Csillagok:</label>
                    <select id="edit_ecsillag" name="ecsillag" class="form-control" required>
                        <option value="1">⭐ (1 csillag)</option>
                        <option value="2">⭐⭐ (2 csillag)</option>
                        <option value="3">⭐⭐⭐ (3 csillag)</option>
                        <option value="4">⭐⭐⭐⭐ (4 csillag)</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5 csillag)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_ekomment">Értékelés:</label>
                    <textarea id="edit_ekomment" name="ekomment" class="form-control" rows="4" required maxlength="1000" placeholder="Írja ide az értékelés szövegét..."></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="edit_review" class="btn btn-edit" style="flex: 1;">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <button type="button" onclick="closeEditModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alkalmazott Szerkesztési Modal -->
    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEmployeeModal()">&times;</span>
            <h3><i class="fas fa-user-edit"></i> Alkalmazott Szerkesztése</h3>
            
            <form method="POST" id="employeeForm">
                <input type="hidden" name="eid" id="employee_eid">
                
                <div class="form-group">
                    <label for="employee_enev">Név:</label>
                    <input type="text" id="employee_enev" name="enev" class="form-control" required maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="employee_email">Email:</label>
                    <input type="email" id="employee_email" name="email" class="form-control" required maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="employee_etel">Telefon:</label>
                    <input type="text" id="employee_etel" name="etel" class="form-control" required maxlength="12">
                </div>
                
                <div class="form-group">
                    <label for="employee_email">Email:</label>
                    <input type="email" id="employee_email" name="email" class="form-control" required maxlength="50">
                </div>
                
                <div class="form-group">
                    <label for="employee_jogosultsag">Jogosultság:</label>
                    <select id="employee_jogosultsag" name="jogosultsag" class="form-control" required>
                        <option value="user">Alkalmazott</option>
                        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                <div class="form-group">
                    <label for="employee_cid">Cég:</label>
                    <select id="employee_cid" name="cid" class="form-control" required>
                        <option value="">- Válasszon céget -</option>
                        <?php foreach ($cegek as $ceg): ?>
                            <option value="<?php echo htmlspecialchars($ceg['cid']); ?>"><?php echo htmlspecialchars($ceg['cnev']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php else: ?>
                <input type="hidden" name="cid" value="<?php echo $_SESSION['admin_company_id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="employee_ekomment">Megjegyzés:</label>
                    <textarea id="employee_ekomment" name="ekomment" class="form-control" rows="3" maxlength="100" placeholder="Opicionális megjegyzés..."></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="edit_employee" class="btn btn-edit" style="flex: 1;">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <button type="button" onclick="closeEmployeeModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>
    

    <!-- Cég Szerkesztési Modal -->
    <div id="companyModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCompanyModal()">&times;</span>
            <h3><i class="fas fa-building"></i> Cég Szerkesztése</h3>
            
            <form method="POST" id="companyForm">
                <input type="hidden" name="cid" id="company_cid">
                
                <div class="form-group">
                    <label for="company_cnev">Cég Név:</label>
                    <input type="text" id="company_cnev" name="cnev" class="form-control" required maxlength="100">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="edit_company" class="btn btn-edit" style="flex: 1;">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <button type="button" onclick="closeCompanyModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
    
    
    
    

    <script>
        
        // Aktív section mentése URL hash-be
        document.addEventListener('DOMContentLoaded', function() {
            // URL hash alapján section aktiválása
            if (window.location.hash) {
                const targetSection = document.querySelector(window.location.hash);
                if (targetSection) {
                    // Elrejtjük az összes section-t
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    // Megjelenítjük a cél section-t
                    targetSection.classList.add('active');
                    
                    // Frissítjük a navigációt
                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('data-target') === window.location.hash.substring(1)) {
                            link.classList.add('active');
                        }
                    });
                }
            }
        
            // Navigáció kattintáskor URL hash frissítése
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    const target = this.getAttribute('data-target');
                    // Frissítjük az URL-t anélkül, hogy újratöltenénk az oldalt
                    history.pushState(null, null, '#' + target);
                });
            });
        });
        
        // Form submit esetén is megtartjuk a hash-t
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const currentHash = window.location.hash;
                // Hozzáadjuk a hash-t a form action-hez
                if (currentHash && !this.action.includes('#')) {
                    this.action += currentHash;
                }
            });
        });
    
    
        // Navigation
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    navLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Hide all content sections
                    const sections = document.querySelectorAll('.content-section');
                    sections.forEach(section => section.classList.remove('active'));
                    
                    // Show target section
                    const target = this.getAttribute('data-target');
                    document.getElementById(target).classList.add('active');
                });
            });
            
            // Mobile menu toggle
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
            
            // Theme toggle
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');
            
            themeToggle.addEventListener('click', function() {
                document.body.classList.toggle('light-mode');
                
                if (document.body.classList.contains('light-mode')) {
                    themeIcon.className = 'fas fa-moon';
                    localStorage.setItem('theme', 'light');
                } else {
                    themeIcon.className = 'fas fa-sun';
                    localStorage.setItem('theme', 'dark');
                }
            });
            
            // Load saved theme
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.body.classList.add('light-mode');
                themeIcon.className = 'fas fa-moon';
            }
        });
        
        // Tab switching
        function switchTab(tabId) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(tab => tab.classList.remove('active'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');
            
            // Add active class to clicked tab
            event.target.classList.add('active');
        }
        
        
        
        // Modal functions
        
        // Kötelező jelszó modal ellenőrzése
        function checkRequiredPasswordMatch() {
            console.log('checkRequiredPasswordMatch called');
            
            const password = document.getElementById('required_jelszo');
            const confirmPassword = document.getElementById('required_jelszo_confirm');
            const messageDiv = document.getElementById('requiredPasswordMatchMessage');
            const submitBtn = document.getElementById('requiredPasswordSubmit');
            
            if (!password || !confirmPassword) {
                console.error('Password elements not found!');
                return;
            }
            
            const passwordValue = password.value;
            const confirmPasswordValue = confirmPassword.value;
            
            console.log('Password check:', { password: passwordValue, confirm: confirmPasswordValue });
            
            if (passwordValue.length < 6) {
                messageDiv.style.display = 'block';
                messageDiv.style.background = '#fef3c7';
                messageDiv.style.color = '#92400e';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> A jelszónak minimum 6 karakter hosszúnak kell lennie';
                if (submitBtn) submitBtn.disabled = true;
                return;
            }
            
            if (confirmPasswordValue === '') {
                messageDiv.style.display = 'none';
                if (submitBtn) submitBtn.disabled = true;
                return;
            }
            
            if (passwordValue === confirmPasswordValue) {
                messageDiv.style.display = 'block';
                messageDiv.style.background = '#dcfce7';
                messageDiv.style.color = '#166534';
                messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> A jelszavak egyeznek - most már mentheti';
                if (submitBtn) submitBtn.disabled = false;
            } else {
                messageDiv.style.display = 'block';
                messageDiv.style.background = '#fecaca';
                messageDiv.style.color = '#991b1b';
                messageDiv.innerHTML = '<i class="fas fa-times-circle"></i> A jelszavak nem egyeznek';
                if (submitBtn) submitBtn.disabled = true;
            }
        }
        
        // Modal blokkolás
        function setupPasswordModal() {
            console.log('setupPasswordModal called');
            
            const requiredModal = document.getElementById('requiredPasswordModal');
            const requiredForm = document.getElementById('requiredPasswordForm');
            
            if (!requiredModal) {
                console.error('Required password modal not found!');
                return;
            }
            
            console.log('Modal found, display:', requiredModal.style.display);
            
            // Csak akkor blokkoljuk, ha látható
            if (requiredModal.style.display === 'block' || requiredModal.style.display === '') {
                console.log('Blocking modal interactions');
            }
            
            
            // Form eseménykezelők
            if (requiredForm) {
                console.log('Setting up form event handlers');
                
                const passwordInput = document.getElementById('required_jelszo');
                const confirmInput = document.getElementById('required_jelszo_confirm');
                
                if (passwordInput && confirmInput) {
                    passwordInput.addEventListener('input', checkRequiredPasswordMatch);
                    confirmInput.addEventListener('input', checkRequiredPasswordMatch);
                }
                
                // Form submit előtti ellenőrzés
                requiredForm.addEventListener('submit', function(e) {
                    console.log('Form submit attempted');
                    
                    const password = document.getElementById('required_jelszo').value;
                    const confirmPassword = document.getElementById('required_jelszo_confirm').value;
                    
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('A jelszavak nem egyeznek! Kérjük, javítsa ki a hibát.');
                        console.log('Submit prevented: passwords do not match');
                        return false;
                    }
                    
                    if (password.length < 6) {
                        e.preventDefault();
                        alert('A jelszónak minimum 6 karakter hosszúnak kell lennie!');
                        console.log('Submit prevented: password too short');
                        return false;
                    }
                    
                    console.log('Form submit allowed');
                    return true;
                });
            }
        }
        
        // Oldal betöltésekor indítjuk
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - setting up password modal');
            setupPasswordModal();
            
            // Alternatív indítás, ha már betöltött az oldal
            setTimeout(setupPasswordModal, 100);
        });
        
        // Manuális indítás is, ha szükséges
        window.addEventListener('load', setupPasswordModal);
                
        function openEditModal(eid, enev, ecsillag, ekomment) {
            document.getElementById('edit_eid').value = eid;
            document.getElementById('edit_enev').value = enev;
            document.getElementById('edit_ecsillag').value = ecsillag;
            document.getElementById('edit_ekomment').value = ekomment;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        function openEmployeeModal(eid, enev, email, etel, jogosultsag, cid, ekomment) {
            console.log('Modal megnyitás paraméterek:', {eid, enev, email, etel, jogosultsag, cid, ekomment});
            
            try {
                // Alapértékek beállítása
                document.getElementById('employee_eid').value = eid || '';
                document.getElementById('employee_enev').value = enev || '';
                document.getElementById('employee_email').value = email || '';
                document.getElementById('employee_etel').value = etel || '';
                document.getElementById('employee_jogosultsag').value = jogosultsag || 'user';
                document.getElementById('employee_ekomment').value = ekomment || '';
                
                // Cég beállítása
                const cidSelect = document.getElementById('employee_cid');
                if (cidSelect) {
                    cidSelect.value = cid || '';
                }
                
                // Hidden cég mező beállítása adminoknak
                const cidHidden = document.querySelector('input[name="cid"][type="hidden"]');
                if (cidHidden) {
                    cidHidden.value = cid || '';
                }
                
                document.getElementById('employeeModal').style.display = 'block';
            } catch (error) {
                console.error('Hiba a modal megnyitásakor:', error);
            }
        }
        
        function closeEmployeeModal() {
            try {
                document.getElementById('employeeModal').style.display = 'none';
            } catch (error) {
                console.error('Hiba a modal bezárásakor:', error);
            }
        }
        
        function openCompanyModal(cid, cnev) {
            document.getElementById('company_cid').value = cid;
            document.getElementById('company_cnev').value = cnev;
            document.getElementById('companyModal').style.display = 'block';
        }
        
        function closeCompanyModal() {
            document.getElementById('companyModal').style.display = 'none';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }
    </script>
</body>
</html>