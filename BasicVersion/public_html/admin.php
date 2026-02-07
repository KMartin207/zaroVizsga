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

// Biztonsági ellenőrzés - PHP oldali idő ellenőrzés
function checkAdminSession() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        return false;
    }
    
    // 15 perces időzítő ellenőrzése
    $max_idle_time = 15 * 60;
    
    // Session kezdő idő beállítása, ha még nincs VAGY ha új belépés történt
    if (!isset($_SESSION['session_start_time']) || isset($_GET['new_login'])) {
        $_SESSION['session_start_time'] = time();
    }
    
    // Szigorú idő ellenőrzés - session kezdete óta eltelt idő
    $session_duration = time() - $_SESSION['session_start_time'];
    
    if ($session_duration > $max_idle_time) {
        // Session lejárt - azonnali kiléptetés
        session_destroy();
        header("Location: admin_logout.php?reason=timeout");
        exit();
    }
    
    return true;
}

// Átirányítás, ha nincs jogosultság
if (!checkAdminSession()) {
    header("Location: login");
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
        
        
        // Jelszó szerkesztése (csak superadmin)
        if (isset($_POST['edit_password'])) {
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            $ujjelszo = $_POST['ujjelszo'] ?? ''; 
            
            if ($eid && $ujjelszo) {

                $hashed_password = password_hash($ujjelszo, PASSWORD_DEFAULT);
                try {
                    $stmt = $conn->prepare("UPDATE ember SET ejelszo = ? WHERE eid = ?");
                    $stmt->execute([$hashed_password, $eid]);
                    $message = "Jelszó sikeresen frissítve!";
                    $message_type = "success";
                    logAdminAction('Jelszó szerkesztése', $eid . ' (ID: ' . $eid . ')');
                } catch(PDOException $e) {
                    $message = "Hiba történt a jelszó frissítése során.";
                    $message_type = "error";
                    logAdminAction('Jelszó szerkesztési hiba', $e->getMessage());
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
        
        // Kártya hozzáadása
        if (isset($_POST['add_card'])) {
            $kazonosito = filter_input(INPUT_POST, 'kazonosito', FILTER_VALIDATE_INT);
            $kallapot = filter_input(INPUT_POST, 'kallapot', FILTER_VALIDATE_INT);
            $hozzarendelt_ember = filter_input(INPUT_POST, 'hozzarendelt_ember', FILTER_VALIDATE_INT);
            
            if ($kazonosito && $kallapot !== null) {
                try {
                    // Ellenőrizzük, hogy létezik-e már ilyen azonosítójú kártya
                    $stmt = $conn->prepare("SELECT kid FROM kartya WHERE kazonosito = ?");
                    $stmt->execute([$kazonosito]);
                    
                    if ($stmt->rowCount() > 0) {
                        $message = "Már létezik ilyen azonosítójú kártya!";
                        $message_type = "error";
                    } else {
                        // Kártya beszúrása
                        $stmt = $conn->prepare("INSERT INTO kartya (kazonosito, kallapot) VALUES (?, ?)");
                        $stmt->execute([$kazonosito, $kallapot]);
                        $kid = $conn->lastInsertId();
                        
                        // Ha van hozzárendelt ember, akkor kapcsolat létrehozása
                        if ($hozzarendelt_ember) {
                            $stmt = $conn->prepare("INSERT INTO kartyaember (kid, eid) VALUES (?, ?)");
                            $stmt->execute([$kid, $hozzarendelt_ember]);
                        }
                        
                        $message = "Kártya sikeresen hozzáadva!";
                        $message_type = "success";
                        logAdminAction('Új kártya hozzáadása', 'Kártya ID: ' . $kazonosito);
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a kártya hozzáadása során: " . $e->getMessage();
                    $message_type = "error";
                    logAdminAction('Kártya hozzáadási hiba', $e->getMessage());
                }
            }
        }
        
        // Kártya státusz váltás
        if (isset($_POST['toggle_card_status'])) {
            $kid = filter_input(INPUT_POST, 'kid', FILTER_VALIDATE_INT);
            
            if ($kid) {
                try {
                    $stmt = $conn->prepare("SELECT kallapot, kazonosito FROM kartya WHERE kid = ?");
                    $stmt->execute([$kid]);
                    $kartya = $stmt->fetch();
                    
                    if ($kartya) {
                        $new_status = $kartya['kallapot'] ? 0 : 1;
                        
                        $stmt = $conn->prepare("UPDATE kartya SET kallapot = ? WHERE kid = ?");
                        $stmt->execute([$new_status, $kid]);
                        
                        $message = "Kártya státusza sikeresen módosítva!";
                        $message_type = "success";
                        logAdminAction('Kártya státusz váltás', 'Kártya ID: ' . $kartya['kazonosito'] . ' - ' . ($new_status ? 'Aktív' : 'Inaktív'));
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a státusz módosítása során.";
                    $message_type = "error";
                }
            }
        }
        
        // Kártya törlése
        if (isset($_POST['delete_card'])) {
            $kid = filter_input(INPUT_POST, 'kid', FILTER_VALIDATE_INT);
            
            if ($kid) {
                try {
                    // Ellenőrizzük, hogy van-e kapcsolódó rekord
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM kartyaember WHERE kid = ?");
                    $stmt->execute([$kid]);
                    $kapcsolat_count = $stmt->fetchColumn();
                    
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM naplo WHERE keid IN (SELECT keid FROM kartyaember WHERE kid = ?)");
                    $stmt->execute([$kid]);
                    $naplo_count = $stmt->fetchColumn();
                    
                    if ($kapcsolat_count > 0 || $naplo_count > 0) {
                        $message = "A kártya nem törölhető, mert még vannak hozzá kapcsolódó adatok!";
                        $message_type = "error";
                    } else {
                        $stmt = $conn->prepare("SELECT kazonosito FROM kartya WHERE kid = ?");
                        $stmt->execute([$kid]);
                        $kartya = $stmt->fetch();
                        
                        $stmt = $conn->prepare("DELETE FROM kartya WHERE kid = ?");
                        $stmt->execute([$kid]);
                        
                        $message = "Kártya sikeresen törölve!";
                        $message_type = "success";
                        logAdminAction('Kártya törlése', 'Kártya ID: ' . $kartya['kazonosito']);
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a kártya törlése során.";
                    $message_type = "error";
                }
            }
        }
        
        // Kártya hozzárendelése alkalmazotthoz (inline form)
        if (isset($_POST['assign_card'])) {
            $kid = filter_input(INPUT_POST, 'kid', FILTER_VALIDATE_INT);
            $eid = filter_input(INPUT_POST, 'eid', FILTER_VALIDATE_INT);
            
            if ($kid && $eid) {
                try {
                    // Ellenőrizzük, hogy már van-e kapcsolat
                    $stmt = $conn->prepare("SELECT keid FROM kartyaember WHERE kid = ? AND eid = ?");
                    $stmt->execute([$kid, $eid]);
                    
                    if ($stmt->rowCount() > 0) {
                        $message = "Ez a kártya már hozzá van rendelve ehhez az alkalmazotthoz!";
                        $message_type = "error";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO kartyaember (kid, eid) VALUES (?, ?)");
                        $stmt->execute([$kid, $eid]);
                        
                        // Lekérjük az alkalmazott nevét
                        $stmt = $conn->prepare("SELECT enev FROM ember WHERE eid = ?");
                        $stmt->execute([$eid]);
                        $alkalmazott = $stmt->fetch();
                        
                        $message = "Kártya sikeresen hozzárendelve!";
                        $message_type = "success";
                        logAdminAction('Kártya hozzárendelés', 'Kártya ID: ' . $kid . ' → Alkalmazott: ' . $alkalmazott['enev']);
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a kártya hozzárendelése során.";
                    $message_type = "error";
                }
            }
        }
        
        // Jármű hozzáadása (inline form)
        if (isset($_POST['add_vehicle'])) {
            $kid = filter_input(INPUT_POST, 'kid', FILTER_VALIDATE_INT);
            $jrendszam = filter_input(INPUT_POST, 'jrendszam', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($kid && $jrendszam) {
                try {
                    // Lekérjük a kártya tulajdonosát
                    $stmt = $conn->prepare("SELECT eid FROM kartyaember WHERE kid = ?");
                    $stmt->execute([$kid]);
                    $kartya_ember = $stmt->fetch();
                    
                    if (!$kartya_ember) {
                        $message = "A kártyának nincs tulajdonosa! Először rendeljen hozzá tulajdonost.";
                        $message_type = "error";
                    } else {
                        // Ellenőrizzük, hogy létezik-e már ilyen rendszám
                        $stmt = $conn->prepare("SELECT jid FROM jarmu WHERE jrendszam = ?");
                        $stmt->execute([$jrendszam]);
                        
                        if ($stmt->rowCount() > 0) {
                            $message = "Már létezik ilyen rendszámú jármű!";
                            $message_type = "error";
                        } else {
                            // Jármű beszúrása
                            $stmt = $conn->prepare("INSERT INTO jarmu (jrendszam, jtipus, jszin, jkomment) VALUES (?, '', '', '')");
                            $stmt->execute([$jrendszam]);
                            $jid = $conn->lastInsertId();
                            
                            // Kapcsolat létrehozása
                            $stmt = $conn->prepare("INSERT INTO emberjarmu (eid, jid) VALUES (?, ?)");
                            $stmt->execute([$kartya_ember['eid'], $jid]);
                            
                            $message = "Jármű sikeresen hozzáadva!";
                            $message_type = "success";
                            logAdminAction('Jármű hozzáadása', 'Rendszám: ' . $jrendszam . ' → Kártya ID: ' . $kid);
                        }
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a jármű hozzáadása során: " . $e->getMessage();
                    $message_type = "error";
                }
            }
        }
        
        // Jármű törlése
        if (isset($_POST['delete_vehicle'])) {
            $jid = filter_input(INPUT_POST, 'jid', FILTER_VALIDATE_INT);
            $jrendszam = filter_input(INPUT_POST, 'jrendszam', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($jid) {
                try {
                    // Először töröljük az emberjarmu kapcsolatot
                    $stmt = $conn->prepare("DELETE FROM emberjarmu WHERE jid = ?");
                    $stmt->execute([$jid]);
                    
                    // Majd töröljük a járművet
                    $stmt = $conn->prepare("DELETE FROM jarmu WHERE jid = ?");
                    $stmt->execute([$jid]);
                    
                    $message = "Jármű sikeresen törölve!";
                    $message_type = "success";
                    logAdminAction('Jármű törlése', 'Rendszám: ' . $jrendszam);
                } catch(PDOException $e) {
                    $message = "Hiba történt a jármű törlése során.";
                    $message_type = "error";
                }
            }
        }
        
        // Tulajdonos eltávolítása kártyáról (minden hozzá tartozó adattal együtt)
        if (isset($_POST['remove_card_owner'])) {
            $keid = filter_input(INPUT_POST, 'keid', FILTER_VALIDATE_INT);
            $tulajdonos_nev = filter_input(INPUT_POST, 'tulajdonos_nev', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if ($keid) {
                try {
                    // Lekérjük a kapcsolat adatait
                    $stmt = $conn->prepare("SELECT ke.kid, ke.eid FROM kartyaember ke WHERE ke.keid = ?");
                    $stmt->execute([$keid]);
                    $kapcsolat = $stmt->fetch();
                    
                    if ($kapcsolat) {
                        $eid = $kapcsolat['eid'];
                        $kid = $kapcsolat['kid'];
                        
                        // 1. Töröljük az összes járművet, ami ehhez az emberhez tartozik
                        $stmt = $conn->prepare("SELECT j.jid FROM jarmu j 
                                              INNER JOIN emberjarmu ej ON j.jid = ej.jid 
                                              WHERE ej.eid = ?");
                        $stmt->execute([$eid]);
                        $jarmuvek = $stmt->fetchAll();
                        
                        foreach ($jarmuvek as $jarmu) {
                            // Először töröljük az emberjarmu kapcsolatot
                            $stmt = $conn->prepare("DELETE FROM emberjarmu WHERE jid = ? AND eid = ?");
                            $stmt->execute([$jarmu['jid'], $eid]);
                            
                            // Majd töröljük a járművet
                            $stmt = $conn->prepare("DELETE FROM jarmu WHERE jid = ?");
                            $stmt->execute([$jarmu['jid']]);
                        }
                        
                        // 2. Töröljük a kartyaember kapcsolatot
                        $stmt = $conn->prepare("DELETE FROM kartyaember WHERE keid = ?");
                        $stmt->execute([$keid]);
                        
                        $message = "Tulajdonos és minden hozzá tartozó adat sikeresen törölve!";
                        $message_type = "success";
                        logAdminAction('Tulajdonos törlése kártyáról', 'Tulajdonos: ' . $tulajdonos_nev . ' (Kapcsolat ID: ' . $keid . ')');
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a tulajdonos törlése során: " . $e->getMessage();
                    $message_type = "error";
                    logAdminAction('Tulajdonos törlési hiba', $e->getMessage());
                }
            }
        }
        
        // Kártya szerkesztése
        if (isset($_POST['edit_card'])) {
            $kid = filter_input(INPUT_POST, 'kid', FILTER_VALIDATE_INT);
            $kazonosito = filter_input(INPUT_POST, 'kazonosito', FILTER_VALIDATE_INT);
            $kallapot = filter_input(INPUT_POST, 'kallapot', FILTER_VALIDATE_INT);
            
            if ($kid && $kazonosito && $kallapot !== null) {
                try {
                    $stmt = $conn->prepare("UPDATE kartya SET kazonosito = ?, kallapot = ? WHERE kid = ?");
                    $stmt->execute([$kazonosito, $kallapot, $kid]);
                    
                    $message = "Kártya sikeresen frissítve!";
                    $message_type = "success";
                    logAdminAction('Kártya szerkesztése', 'Kártya ID: ' . $kazonosito);
                } catch(PDOException $e) {
                    $message = "Hiba történt a kártya frissítése során.";
                    $message_type = "error";
                }
            }
        }
        
        // Kártyák lekérése az adatbázisból
        $kartyak_query = "
            SELECT 
                k.kid,
                k.kazonosito,
                k.kallapot,
                ke.keid,
                e.eid as tulajdonos_id,
                e.enev as tulajdonos_nev,
                e.email as tulajdonos_email,
                c.cnev,
                c.cid,
                j.jid,
                j.jrendszam
            FROM kartya k
            LEFT JOIN kartyaember ke ON k.kid = ke.kid
            LEFT JOIN ember e ON ke.eid = e.eid
            LEFT JOIN ceg c ON e.cid = c.cid
            LEFT JOIN emberjarmu ej ON e.eid = ej.eid
            LEFT JOIN jarmu j ON ej.jid = j.jid
            WHERE 1=1
        ";
        
        $kartyak_params = [];
        
        // Szűrés cég szerint
        if (isset($_GET['card_filter_company']) && $_GET['card_filter_company'] !== '') {
            $kartyak_query .= " AND (c.cid = ? OR c.cid IS NULL)";
            $kartyak_params[] = $_GET['card_filter_company'];
        }
        
        // Szűrés státusz szerint
        if (isset($_GET['card_filter_status']) && $_GET['card_filter_status'] !== '') {
            $kartyak_query .= " AND k.kallapot = ?";
            $kartyak_params[] = $_GET['card_filter_status'];
        }
        
        $kartyak_query .= " ORDER BY k.kazonosito ASC";
        
        try {
            $stmt = $conn->prepare($kartyak_query);
            $stmt->execute($kartyak_params);
            $kartyak_raw = $stmt->fetchAll();
            
            // Átszervezzük az adatokat kártyánként
            $kartyak = [];
            foreach ($kartyak_raw as $row) {
                $kid = $row['kid'];
                
                if (!isset($kartyak[$kid])) {
                    $kartyak[$kid] = [
                        'kid' => $row['kid'],
                        'kazonosito' => $row['kazonosito'],
                        'kallapot' => $row['kallapot'],
                        'keid' => $row['keid'],
                        'tulajdonos_id' => $row['tulajdonos_id'],
                        'tulajdonos_nev' => $row['tulajdonos_nev'],
                        'tulajdonos_email' => $row['tulajdonos_email'],
                        'cnev' => $row['cnev'],
                        'cid' => $row['cid'],
                        'jarmuvek' => []
                    ];
                }
                
                // Jármű hozzáadása, ha van
                if (!empty($row['jid']) && !empty($row['jrendszam'])) {
                    $jarmu_letezik = false;
                    foreach ($kartyak[$kid]['jarmuvek'] as $jarmu) {
                        if ($jarmu['jid'] == $row['jid']) {
                            $jarmu_letezik = true;
                            break;
                        }
                    }
                    
                    if (!$jarmu_letezik) {
                        $kartyak[$kid]['jarmuvek'][] = [
                            'jid' => $row['jid'],
                            'jrendszam' => $row['jrendszam']
                        ];
                    }
                }
            }
            
            $kartyak = array_values($kartyak);
            
        } catch(PDOException $e) {
            $kartyak = [];
            error_log("Kártyák betöltési hiba: " . $e->getMessage());
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
        
        // Kódkezelés funkciók - ÚJ
        if (isset($_POST['add_code'])) {
            $kod = $_POST['kod'] ?? '';
            $lejarat = $_POST['lejarat'] ?? '';
            $cid = $_POST['cid'] ?? '';
            
            // Cég beállítása jogosultság alapján
            if ($_SESSION['admin_role'] === 'admin') {
                $cid = $_SESSION['admin_company_id'];
            } elseif ($_SESSION['admin_role'] === 'superadmin' && empty($cid)) {
                $message = "Superadmin esetén kötelező céget választani!";
                $message_type = "error";
            }
            
            if (strlen($kod) === 4 && is_numeric($kod) && !empty($cid)) {
                try {
                    // Ellenőrizzük, hogy létezik-e már ilyen kód
                    $stmt = $conn->prepare("SELECT id FROM kodok WHERE kod = ?");
                    $stmt->execute([$kod]);
                    
                    if ($stmt->rowCount() > 0) {
                        $message = "Ez a kód már létezik!";
                        $message_type = "error";
                    } else {
                        // Új kód beszúrása
                        $stmt = $conn->prepare("INSERT INTO kodok (kod, lejarat, keszitette, cid) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$kod, $lejarat, $_SESSION['admin_id'], $cid]);
                        
                        $message = "Kód sikeresen hozzáadva!";
                        $message_type = "success";
                        logAdminAction('Új kód hozzáadása', 'Kód: ' . $kod . ' - Cég: ' . $cid);
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a kód hozzáadása során: " . $e->getMessage();
                    $message_type = "error";
                }
            } elseif (empty($cid)) {
                $message = "Cég kiválasztása kötelező!";
                $message_type = "error";
            } else {
                $message = "A kódnak pontosan 4 számjegyből kell állnia!";
                $message_type = "error";
            }
        }
        
        // Kód törlése - JAVÍTVA JOGOSULTSÁGGAL
        if (isset($_POST['delete_code'])) {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            
            if ($id) {
                try {
                    // Ellenőrizzük a jogosultságot
                    $stmt = $conn->prepare("SELECT k.*, e.cid as keszito_cid FROM kodok k 
                                          LEFT JOIN ember e ON k.keszitette = e.eid 
                                          WHERE k.id = ?");
                    $stmt->execute([$id]);
                    $kod = $stmt->fetch();
                    
                    if ($kod) {
                        $has_permission = false;
                        
                        if ($_SESSION['admin_role'] === 'superadmin') {
                            $has_permission = true;
                        } elseif ($_SESSION['admin_role'] === 'admin' && $kod['cid'] == $_SESSION['admin_company_id']) {
                            $has_permission = true;
                        }
                        
                        if ($has_permission) {
                            $stmt = $conn->prepare("DELETE FROM kodok WHERE id = ?");
                            $stmt->execute([$id]);
                            
                            $message = "Kód sikeresen törölve!";
                            $message_type = "success";
                            logAdminAction('Kód törlése', 'Kód ID: ' . $id);
                        } else {
                            $message = "Nincs jogosultsága ezt a kódot törölni!";
                            $message_type = "error";
                        }
                    }
                } catch(PDOException $e) {
                    $message = "Hiba történt a kód törlése során.";
                    $message_type = "error";
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
    
    // Parkolási napló lekérése - MÓDOSÍTOTT
    $parkolas_query = "SELECT n.*, e.enev, k.kazonosito, e.eid as alkalmazott_id,
                              CASE 
                                  WHEN n.nidoig IS NULL OR n.nidoig = '' THEN 'Jelenleg parkol'
                                  ELSE DATE_FORMAT(n.nidoig, '%Y.%m.%d %H:%i')
                              END as format_nidoig
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
        $parkolas_query .= " AND (n.nidoig IS NULL OR n.nidoig >= CURDATE())";
    } elseif ($naplo_filter_type === 'previous') {
        $parkolas_query .= " AND n.nidoig < CURDATE()";
    }
    
    if ($naplo_filter_date) {
        $parkolas_query .= " AND DATE(n.nidotol) = ?";
        $parkolas_params[] = $naplo_filter_date;
    }
    
    $parkolas_query .= " ORDER BY n.nidotol DESC";
    
    $stmt = $conn->prepare($parkolas_query);
    $stmt->execute($parkolas_params);
    $parkolas_naplo = $stmt->fetchAll();
    
    // Kódok lekérése
    try {
        $kodok_query = "SELECT k.*, e.enev as keszito_nev, c.cnev 
                       FROM kodok k 
                       LEFT JOIN ember e ON k.keszitette = e.eid 
                       LEFT JOIN ceg c ON k.cid = c.cid 
                       WHERE 1=1";
        $kodok_params = [];
        
        // Cég szűrés jogosultság alapján
        if ($_SESSION['admin_role'] === 'admin') {
            $kodok_query .= " AND k.cid = ?";
            $kodok_params[] = $_SESSION['admin_company_id'];
        } elseif ($active_company !== 'all') {
            $kodok_query .= " AND k.cid = ?";
            $kodok_params[] = $active_company;
        }
        
        $kodok_query .= " ORDER BY k.keszitve DESC";
        
        $stmt = $conn->prepare($kodok_query);
        $stmt->execute($kodok_params);
        $kodok = $stmt->fetchAll();
    } catch(PDOException $e) {
        $kodok = [];
        error_log("Kódok betöltési hiba: " . $e->getMessage());
    }
    
    // Manuális kijelentkeztetés funkció - ÚJ
    if (isset($_POST['manual_checkout'])) {
        $nid = filter_input(INPUT_POST, 'nid', FILTER_VALIDATE_INT);
        $manual_checkout_time = $_POST['manual_checkout_time'] ?? '';
        
        if ($nid && $manual_checkout_time) {
            try {
                // Ellenőrizzük, hogy a parkolás létezik és még aktív-e
                $stmt = $conn->prepare("SELECT n.*, e.enev, e.cid FROM naplo n 
                                      LEFT JOIN kartyaember ke ON n.keid = ke.keid 
                                      LEFT JOIN ember e ON ke.eid = e.eid 
                                      WHERE n.nid = ?");
                $stmt->execute([$nid]);
                $parkolas = $stmt->fetch();
                
                if ($parkolas) {
                    // Jogosultság ellenőrzése
                    $has_permission = false;
                    if ($_SESSION['admin_role'] === 'superadmin') {
                        $has_permission = true;
                    } elseif ($_SESSION['admin_role'] === 'admin' && $parkolas['cid'] == $_SESSION['admin_company_id']) {
                        $has_permission = true;
                    }
                    
                    if ($has_permission) {
                        // Frissítjük a távozási időt
                        $stmt = $conn->prepare("UPDATE naplo SET nidoig = ? WHERE nid = ?");
                        $stmt->execute([$manual_checkout_time, $nid]);
                        
                        $message = "Sikeresen kijelentkeztetve!";
                        $message_type = "success";
                        logAdminAction('Manuális kijelentkeztetés', 
                            $parkolas['enev'] . ' - ' . $manual_checkout_time . ' (Parkolás ID: ' . $nid . ')');
                        
                        // Oldal újratöltése az adatok frissítéséhez
                        header("Location: " . $_SERVER['PHP_SELF'] . "?naplo_filter_type=" . urlencode($naplo_filter_type) . "&naplo_filter_date=" . urlencode($naplo_filter_date) . "#parkolas-naplo");
                        exit();
                    } else {
                        $message = "Nincs jogosultsága ezt a parkolást módosítani!";
                        $message_type = "error";
                    }
                } else {
                    $message = "A parkolás nem található!";
                    $message_type = "error";
                }
            } catch(PDOException $e) {
                $message = "Hiba történt a kijelentkeztetés során: " . $e->getMessage();
                $message_type = "error";
            }
        }
    }
    
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
                                COUNT(CASE WHEN nidoig >= CURDATE() THEN 1 END) as aktiv,
                                COUNT(CASE WHEN nidoig < CURDATE() THEN 1 END) as lejart
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
            transition: all 0.3s ease-in-out;
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
        
        /* Desktop Tables - ASZTALI NÉZET */
        .table-responsive {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background: var(--light);
            border: 1px solid var(--border);
        }
        
        .table-responsive table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table-responsive th, .table-responsive td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }
        
        .table-responsive th {
            background: rgba(59, 130, 246, 0.1);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table-responsive tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        /* Mobile Cards - MOBIL NÉZET */
        .mobile-cards {
            display: none;
            gap: 16px;
            flex-direction: column;
        }
        
        .mobile-card {
            background: var(--light);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            margin-bottom: 16px;
        }
        
        .mobile-card:last-child {
            margin-bottom: 0;
        }
        
        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .mobile-card-title {
            font-weight: 700;
            color: var(--text);
            font-size: 1.1rem;
            flex: 1;
        }
        
        .mobile-card-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .mobile-card-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .mobile-card-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .mobile-card-row:last-child {
            border-bottom: none;
        }
        
        .mobile-card-label {
            font-weight: 600;
            color: var(--text-muted);
            font-size: 0.9rem;
            min-width: 100px;
            flex-shrink: 0;
        }
        
        .mobile-card-value {
            color: var(--text);
            text-align: right;
            flex: 1;
            margin-left: 15px;
            word-break: break-word;
        }
        
        .mobile-card-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
            flex-wrap: wrap;
        }
        
        .mobile-card-actions .btn {
            flex: 1;
            min-width: 140px;
            justify-content: center;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
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
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
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
            text-decoration: none;
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
            overflow-x: auto;
        }
        
        .tab {
            padding: 15px 25px;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-muted);
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            white-space: nowrap;
            flex-shrink: 0;
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
        
        .empty-state p {
            color: var(--text-muted);
            font-size: 1rem;
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
            display: inline-block;
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
        
        /* Countdown sidebar */
        .countdown-sidebar {
            background: var(--primary);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }
        
        .countdown-sidebar.warning {
            background: var(--warning);
        }
        
        .countdown-sidebar.error {
            background: var(--error);
        }
        
        .countdown-sidebar i {
            font-size: 0.9rem;
        }
        
        #countdownSidebarText {
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }
        
        /* Jelszó modal speciális stílusok */
        #requiredPasswordModal {
            pointer-events: all !important;
        }
        
        #requiredPasswordModal .close {
            display: none !important;
        }
        
        /* ===== RESPONSIVE DESIGN ===== */
        
        /* Tablet */
        @media (max-width: 1024px) {
            .sidebar {
                width: 250px;
            }
            
            
            
            .main-content {
                margin-left: 250px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .content-section {
                padding: 25px;
            }
        }
        
        /* Mobile - EZ A LÉNYEGI RÉSZ */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
            }
            
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .sidebar.active + .sidebar-overlay {
                display: block;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px 20px;
                margin-top: 45px;
                gap: 12px;
            }
            
            .company-selector {
                width: 100%;
            }
            
            .company-selector select {
                width: 100%;
                min-width: auto;
            }
            
            .content-section {
                padding: 20px 15px;
            }
            
            .section-title {
                font-size: 1.3rem;
                margin-bottom: 20px;
            }
            
            /* MOBILE CARDS ACTIVATION - ASZTALI TÁBLÁZAT ELREJTÉSE */
            .table-responsive table {
                display: none !important;
            }
            
            .mobile-cards {
                display: flex !important;
            }
            
            .table-responsive {
                background: transparent;
                box-shadow: none;
                border: none;
                overflow: visible;
            }
            
            /* Stats grid mobil nézet */
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-card h3 {
                font-size: 1.8rem;
            }
            
            .stat-card i {
                font-size: 2rem;
            }
            
            /* Tabok mobil nézet */
            .tab-content {
                padding: 20px;
            }
            
            .tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding: 5px;
            }
            
            .tab {
                padding: 12px 20px;
                flex-shrink: 0;
                font-size: 0.9rem;
            }
            
            /* Filter grid mobil nézet */
            .filter-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .filter-container {
                padding: 15px;
            }
            
            /* Mobile card improvements */
            .mobile-card {
                padding: 18px;
                margin-bottom: 16px;
            }
            
            .mobile-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            
            .mobile-card-title {
                min-width: auto;
                width: 100%;
            }
            
            .mobile-card-badges {
                width: 100%;
                justify-content: flex-start;
            }
            
            .mobile-card-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
                padding: 10px 0;
            }
            
            .mobile-card-label {
                min-width: auto;
                font-size: 0.85rem;
            }
            
            .mobile-card-value {
                margin-left: 0;
                text-align: left;
                width: 100%;
                font-size: 0.9rem;
            }
            
            .mobile-card-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .mobile-card-actions .btn {
                min-width: 100%;
                width: 100%;
            }
            
            /* Gombok mobil nézet */
            .btn {
                width: 100%;
                justify-content: center;
                padding: 14px 16px;
            }
            
            .table-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            /* Modal mobil nézet */
            .modal-content {
                width: 95%;
                margin: 10% auto;
                max-width: none;
            }
            
            .modal-content form {
                padding: 20px;
            }
            
            .theme-toggle {
                width: 45px;
                height: 45px;
                bottom: 15px;
                right: 15px;
            }
            
            .empty-state {
                padding: 40px 15px;
            }
            
            .empty-state i {
                font-size: 3rem;
            }
            
            .empty-state h3 {
                font-size: 1.3rem;
            }
        }
        
        /* Kis mobilok */
        @media (max-width: 480px) {
            .header-content h1 {
                font-size: 1.4rem;
            }
            
            .header-content p {
                font-size: 0.9rem;
            }
            
            .content-section {
                padding: 15px 12px;
            }
            
            .section-title {
                font-size: 1.2rem;
                margin-bottom: 18px;
            }
            
            .mobile-card {
                padding: 16px;
            }
            
            .stat-card {
                padding: 18px;
            }
            
            .stat-card i {
                font-size: 1.8rem;
            }
            
            .stat-card h3 {
                font-size: 1.6rem;
            }
            
            .tab {
                padding: 10px 15px;
                font-size: 0.85rem;
            }
            
            .tab-content {
                padding: 18px;
            }
            
            .mobile-card-actions .btn {
                font-size: 0.85rem;
            }
        }
        
        /* Nagyon kis mobilok */
        @media (max-width: 360px) {
            .sidebar {
                width: 100%;
            }
            
            .header {
                padding: 12px 15px;
            }
            
            .content-section {
                padding: 12px 10px;
            }
            
            .mobile-card {
                padding: 14px;
            }
            
            .btn {
                padding: 12px 14px;
                font-size: 0.85rem;
            }
            
            .theme-toggle {
                width: 40px;
                height: 40px;
                bottom: 10px;
                right: 10px;
            }
            
            .mobile-menu-toggle {
                width: 40px;
                height: 40px;
            }
        }
        
        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .btn, .tab, .sidebar-menu a {
                min-height: 44px;
            }
            
            .form-control {
                min-height: 44px;
            }
            
            .stat-card:hover, .btn:hover {
                transform: none;
            }
            
            .mobile-card {
                min-height: auto;
            }
        }
        
        /* Print styles */
        @media print {
            .sidebar,
            .header,
            .theme-toggle,
            .mobile-menu-toggle,
            .table-actions,
            .btn {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .content-section {
                display: block !important;
                page-break-inside: avoid;
            }
            
            .mobile-cards {
                display: none !important;
            }
            
            .table-responsive table {
                display: table !important;
            }
        }
    </style>
    
    <meta name="google-adsense-account" content="ca-pub-3303266389157706">
    
    <!-- Cache megakadályozása -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚗</text></svg>">
    
    
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
                    <small><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($_SESSION['admin_email']); ?></small>
                    <small style="display: block; margin-top: 5px;">
                        <i class="fas fa-user-shield"></i> 
                        <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                            <span class="superadmin-badge">SUPERADMIN</span>
                        <?php elseif ($_SESSION['admin_role'] === 'admin'): ?>
                            <span class="company-badge">ADMIN</span>
                        <?php else: ?>
                            <span class="role-badge role-user" style="display: inline-block;">USER</span>
                        <?php endif; ?>
                        <span class="company-badge"><?php echo htmlspecialchars($_SESSION['admin_company_name']); ?></span>
                    </small>
                    <small style="display: block; margin-top: 5px;">
                        <i class="fas fa-clock"></i> Bejelentkezve: <?php echo date('H:i', $_SESSION['login_time']); ?>
                    </small>
                    <!-- Visszaszámláló a sidebar-ban -->
                    <div class="countdown-sidebar" id="countdownSidebar">
                        <i class="fas fa-hourglass-half"></i>
                        <span id="countdownSidebarText">15:00</span>
                    </div>
                </div>
            </div>
            <ul class="sidebar-menu">
            <li><a href="#statisztikak" class="nav-link active" data-target="statisztikak"><i class="fas fa-chart-bar"></i> Statisztikák</a></li>
            <li><a href="#parkolas-naplo" class="nav-link" data-target="parkolas-naplo"><i class="fas fa-car"></i> Parkolási Napló</a></li>
            <?php if ($active_company === 'all'): ?>
            <li><a href="#ertekelesek" class="nav-link" data-target="ertekelesek"><i class="fas fa-star"></i> Értékelések</a></li>
            <?php endif; ?>
            <li><a href="#alkalmazottak" class="nav-link" data-target="alkalmazottak"><i class="fas fa-users"></i> Alkalmazottak</a></li>
            <li><a href="#kartyak" class="nav-link" data-target="kartyak"><i class="fas fa-id-card"></i> Kártyák</a></li>
            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
            <li><a href="#cegek" class="nav-link" data-target="cegek"><i class="fas fa-building"></i> Cégek</a></li>
            <li><a href="#admin-kezeles" class="nav-link" data-target="admin-kezeles"><i class="fas fa-user-shield"></i> Admin Kezelés</a></li>
            <?php endif; ?>
            <li><a href="#admin-naplo" class="nav-link" data-target="admin-naplo"><i class="fas fa-history"></i> Tevékenység Napló</a></li>
            <li><a href="#" onclick="manualLogout(); return false;" style="color: #ef4444;">
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
                
                
                <!-- Új gomb a kódkezeléshez -->
                <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                    <a href="#kod-kezeles" class="btn btn-success nav-link" data-target="kod-kezeles" style="text-decoration: none;">
                        <i class="fas fa-key"></i> Kódok Kezelése
                    </a>
                </div>
                
                
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
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($parkolas_naplo as $parkolas): 
                                $is_active = empty($parkolas['nidoig']);
                                $status_class = $is_active ? 'status-active' : 'status-inactive';
                                $status_text = $is_active ? 'Aktív' : 'Lejárt';
                                
                                // Távozási idő megjelenítése
                                $tavozas_display = empty($parkolas['nidoig']) ? 
                                    '<span style="color: var(--text-muted);">-</span>' : 
                                    date('Y.m.d H:i', strtotime($parkolas['nidoig']));
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($parkolas['nid']); ?></td>
                                <td><strong><?php echo htmlspecialchars($parkolas['enev'] ?? 'Ismeretlen'); ?></strong></td>
                                <td><?php echo htmlspecialchars($parkolas['kazonosito'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($parkolas['nparkhely']); ?></td>
                                <td><?php echo date('Y.m.d H:i', strtotime($parkolas['nidotol'])); ?></td>
                                <td><?php echo $tavozas_display; ?></td>
                                <td>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                    <?php if (empty($parkolas['nidoig'])): ?>
                                        <br><small style="color: var(--success); font-size: 0.8rem;">
                                            <i class="fas fa-car"></i> Jelenleg bent parkol
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (empty($parkolas['nidoig'])): ?>
                                        <!-- Manuális kijelentkeztetés gomb -->
                                        <button class="btn btn-warning btn-sm" 
                                                onclick="openManualCheckoutModal(<?php echo $parkolas['nid']; ?>, '<?php echo addslashes($parkolas['enev'] ?? 'Ismeretlen'); ?>')">
                                            <i class="fas fa-sign-out-alt"></i> Kijelentkeztetés
                                        </button>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.9rem;">Lezárva</span>
                                    <?php endif; ?>
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
            
            <!-- ÚJ: Kódkezelés oldal -->
            <div class="content-section" id="kod-kezeles">
                <h2 class="section-title"><i class="fas fa-key"></i> Kódok Kezelése</h2>
                
                <!-- Információs sáv -->
                <div style="background: rgba(59, 130, 246, 0.1); padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid var(--primary);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                        <div>
                            <strong>Jelenlegi szűrés:</strong>
                            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                                <span class="company-badge"><?php echo htmlspecialchars($_SESSION['admin_company_name']); ?></span>
                            <?php elseif ($active_company !== 'all'): ?>
                                <span class="company-badge"><?php echo htmlspecialchars($active_company_name); ?></span>
                            <?php else: ?>
                                <span class="superadmin-badge">Összes cég</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="tab-container">
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('code-list')">Kód Lista</div>
                        <div class="tab" onclick="switchTab('add-code')">Új Kód</div>
                    </div>
                    
                    <div id="code-list" class="tab-content active">
                        <?php if (count($kodok) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Kód</th>
                                        <th>Cég</th>
                                        <th>Státusz</th>
                                        <th>Készítve</th>
                                        <th>Felhasználva</th>
                                        <th>Lejárat</th>
                                        <th>Készítette</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kodok as $kod): 
                                        $can_delete = (!$kod['hasznalt'] && 
                                            ($_SESSION['admin_role'] === 'superadmin' || 
                                            ($_SESSION['admin_role'] === 'admin' && $kod['cid'] == $_SESSION['admin_company_id'])));
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($kod['id']); ?></td>
                                        <td>
                                            <strong style="font-family: 'Courier New', monospace; font-size: 1.2rem;">
                                                <?php echo htmlspecialchars($kod['kod']); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="company-badge" style="font-size: 0.8rem;">
                                                <?php echo htmlspecialchars($kod['cnev'] ?? 'Ismeretlen'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $kod['hasznalt'] ? 'status-inactive' : 'status-active'; ?>">
                                                <?php echo $kod['hasznalt'] ? 'Felhasznált' : 'Aktív'; ?>
                                            </span>
                                            <?php if ($kod['lejarat'] && strtotime($kod['lejarat']) < time() && !$kod['hasznalt']): ?>
                                                <br><small style="color: var(--error); font-size: 0.7rem;">Lejárt</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('Y.m.d H:i', strtotime($kod['keszitve'])); ?></td>
                                        <td>
                                            <?php echo $kod['felhasznalva'] ? 
                                                date('Y.m.d H:i', strtotime($kod['felhasznalva'])) : 
                                                '<span style="color: var(--text-muted);">-</span>'; ?>
                                        </td>
                                        <td>
                                            <?php echo $kod['lejarat'] ? 
                                                date('Y.m.d', strtotime($kod['lejarat'])) : 
                                                '<span style="color: var(--text-muted);">-</span>'; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($kod['keszito_nev'] ?? 'Ismeretlen'); ?></td>
                                        <td>
                                            <div class="table-actions">
                                                <?php if ($can_delete): ?>
                                                <form method="POST" class="ajax-form">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($kod['id']); ?>">
                                                    <button type="submit" name="delete_code" class="btn btn-delete btn-sm" 
                                                            onclick="return confirm('Biztosan törölni szeretné ezt a kódot?')">
                                                        <i class="fas fa-trash"></i> Törlés
                                                    </button>
                                                </form>
                                                <?php else: ?>
                                                <span style="color: var(--text-muted); font-size: 0.9rem;">
                                                    <?php if ($kod['hasznalt']): ?>
                                                        <i class="fas fa-lock"></i> Felhasznált
                                                    <?php else: ?>
                                                        <i class="fas fa-ban"></i> Nem törölhető
                                                    <?php endif; ?>
                                                </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Statisztika -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
                            <div class="stat-card">
                                <i class="fas fa-key"></i>
                                <h3><?php echo count($kodok); ?></h3>
                                <p>Összes kód</p>
                            </div>
                            
                            <div class="stat-card success">
                                <i class="fas fa-check-circle"></i>
                                <h3><?php echo count(array_filter($kodok, function($k) { return !$k['hasznalt']; })); ?></h3>
                                <p>Aktív kódok</p>
                            </div>
                            
                            <div class="stat-card error">
                                <i class="fas fa-times-circle"></i>
                                <h3><?php echo count(array_filter($kodok, function($k) { return $k['hasznalt']; })); ?></h3>
                                <p>Felhasznált kódok</p>
                            </div>
                            
                            <div class="stat-card warning">
                                <i class="fas fa-clock"></i>
                                <h3><?php echo count(array_filter($kodok, function($k) { 
                                    return $k['lejarat'] && strtotime($k['lejarat']) < time() && !$k['hasznalt']; 
                                })); ?></h3>
                                <p>Lejárt kódok</p>
                            </div>
                        </div>
                        
                        <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-key"></i>
                            <h3>Nincsenek kódok</h3>
                            <p>
                                <?php if ($_SESSION['admin_role'] === 'admin' || $active_company !== 'all'): ?>
                                Nincsenek kódok a kiválasztott céghez.
                                <?php else: ?>
                                Még nincsenek kódok létrehozva a rendszerben.
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="add-code" class="tab-content">
                        <form method="POST" id="addCodeForm">
                            <div class="form-group">
                                <label for="kod">4 számjegyű kód:</label>
                                <input type="text" id="kod" name="kod" class="form-control" 
                                       required maxlength="4" minlength="4" 
                                       pattern="[0-9]{4}" placeholder="1234"
                                       style="font-family: 'Courier New', monospace; font-size: 1.2rem; text-align: center;">
                                <small style="color: var(--text-muted); margin-top: 5px; display: block;">
                                    <i class="fas fa-info-circle"></i> A kódnak pontosan 4 számjegyből kell állnia (0-9)
                                </small>
                            </div>
                            
                            <!-- Cég választó - CSAK SUPERADMINNAK -->
                            <?php if ($_SESSION['admin_role'] === 'superadmin'): ?>
                            <div class="form-group">
                                <label for="cid">Cég:</label>
                                <select id="cid" name="cid" class="form-control" required>
                                    <option value="">- Válasszon céget -</option>
                                    <?php foreach ($cegek as $ceg): ?>
                                        <option value="<?php echo htmlspecialchars($ceg['cid']); ?>" 
                                            <?php echo $active_company == $ceg['cid'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($ceg['cnev']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php else: ?>
                            <input type="hidden" name="cid" value="<?php echo $_SESSION['admin_company_id']; ?>">
                            <div class="form-group">
                                <label>Cég:</label>
                                <input type="text" class="form-control" 
                                       value="<?php echo htmlspecialchars($_SESSION['admin_company_name']); ?>" 
                                       readonly style="background: var(--border);">
                                <small style="color: var(--text-muted); margin-top: 5px; display: block;">
                                    <i class="fas fa-info-circle"></i> Csak a saját cégéhez adhat hozzá kódot
                                </small>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label for="lejarat">Lejárati dátum (opcionális):</label>
                                <input type="date" id="lejarat" name="lejarat" class="form-control">
                                <small style="color: var(--text-muted); margin-top: 5px; display: block;">
                                    <i class="fas fa-info-circle"></i> Ha nincs megadva, a kód nem jár le
                                </small>
                            </div>
                            
                            <div style="background: rgba(59, 130, 246, 0.1); border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <h4 style="color: var(--primary); margin-bottom: 10px; font-size: 1rem;">
                                    <i class="fas fa-lightbulb"></i> Információ
                                </h4>
                                <ul style="color: var(--text-muted); margin: 0; padding-left: 20px;">
                                    <li>A kód automatikusan aktív státusszal jön létre</li>
                                    <li>Csak nem felhasznált kódok törölhetők</li>
                                    <li>A készítő automatikusan beállításra kerül</li>
                                    <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                                    <li>Csak a saját cégéhez adhat hozzá kódot</li>
                                    <?php else: ?>
                                    <li>Válassza ki, hogy melyik céghez tartozzon a kód</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            
                            <button type="submit" name="add_code" class="btn btn-add">
                                <i class="fas fa-plus"></i> Kód Hozzáadása
                            </button>
                        </form>
                    </div>
                </div>
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
                                                    ($_SESSION['admin_role'] == "superadmin" && $_SESSION['admin_email'] == $alkalmazott['email']) ||
                                                     ($_SESSION['admin_role'] == "superadmin" && $alkalmazott['jogosultsag'] == "admin" ) ||
                                                     ($_SESSION['admin_role'] == "superadmin" && $alkalmazott['jogosultsag'] == "user" ) ||
                                                     ($_SESSION['admin_role'] == "admin" && $alkalmazott['jogosultsag'] == 'user')
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
                                                    <button type="submit" class="btn btn-delete btn-sm" onclick="return confirm('Biztosan törölni szeretné ezt az alkalmazottat?')">
                                                        <i class="fas fa-trash"></i> Törlés
                                                    </button>
                                                </form>
                                                <?php endif; ?>
                                                
                                                
                                                
                                                
                                                
                                                <!-- JELSZÓ MEGVÁLTOZTATÁSA -->
                                                <?php if (
                                                    ($alkalmazott['jogosultsag'] == 'admin' && $_SESSION['admin_role'] == 'superadmin') ||
                                                    ($alkalmazott['email'] == $_SESSION['admin_email'] && $_SESSION['admin_role'] == 'superadmin') 
                                                    
                                                ): ?>
                                                <form method="POST" class="ajax-form">
                                                    <input type="hidden" name="eid" value="<?php echo htmlspecialchars($alkalmazott['eid']); ?>">
                                                    <button type="button" class="btn btn-edit btn-sm"
                                                        onclick="openPassModal(<?php echo (int)$alkalmazott['eid']; ?>)">
                                                        <i class="fas fa-edit"></i> Jelszó
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
            
    
            
            
            <!-- Kártyák -->
            <div class="content-section" id="kartyak">
                <h2 class="section-title"><i class="fas fa-id-card"></i> Kártyák Kezelése</h2>
                
                <div class="tab-container">
                    <div class="tabs">
                        <div class="tab active" onclick="switchTab('card-list')">Kártya Lista</div>
                        <div class="tab" onclick="switchTab('add-card')">Új Kártya</div>
                    </div>
                    
                    <div id="card-list" class="tab-content active">
                        <!-- Szűrő űrlap -->
                        <div class="filter-container">
                            <h4><i class="fas fa-filter"></i> Szűrés</h4>
                            <form method="GET" class="filter-grid">
                                <input type="hidden" name="filter_jogosultsag" value="<?php echo htmlspecialchars($filter_jogosultsag); ?>">
                                <input type="hidden" name="filter_status" value="<?php echo htmlspecialchars($filter_status); ?>">
                                <input type="hidden" name="naplo_filter_date" value="<?php echo htmlspecialchars($naplo_filter_date); ?>">
                                <input type="hidden" name="naplo_filter_type" value="<?php echo htmlspecialchars($naplo_filter_type); ?>">
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="card_filter_status" style="font-size: 0.9rem;">Státusz:</label>
                                    <select id="card_filter_status" name="card_filter_status" class="form-control">
                                        <option value="">- Minden státusz -</option>
                                        <option value="1" <?php echo ($_GET['card_filter_status'] ?? '') === '1' ? 'selected' : ''; ?>>Aktív</option>
                                        <option value="0" <?php echo ($_GET['card_filter_status'] ?? '') === '0' ? 'selected' : ''; ?>>Inaktív</option>
                                    </select>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="card_filter_company" style="font-size: 0.9rem;">Cég:</label>
                                    <select id="card_filter_company" name="card_filter_company" class="form-control">
                                        <option value="">- Minden cég -</option>
                                        <?php foreach ($cegek as $ceg): ?>
                                            <option value="<?php echo htmlspecialchars($ceg['cid']); ?>" <?php echo ($_GET['card_filter_company'] ?? '') == $ceg['cid'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($ceg['cnev']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group" style="margin-bottom: 0;">
                                    <button type="submit" class="btn btn-edit" style="width: 100%;">
                                        <i class="fas fa-search"></i> Szűrés
                                    </button>
                                </div>
                                
                                <?php if (isset($_GET['card_filter_status']) || isset($_GET['card_filter_company'])): ?>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <a href="?" class="btn btn-delete" style="width: 100%; text-decoration: none; text-align: center;">
                                        <i class="fas fa-times"></i> Szűrők törlése
                                    </a>
                                </div>
                                <?php endif; ?>
                            </form>
                        </div>
            
                        <?php if (count($kartyak) > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Kártya ID</th>
                                        <th>Azonosító</th>
                                        <th>Tulajdonos</th>
                                        <th>Cég</th>
                                        <th>Státusz</th>
                                        <th>Járművek</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($kartyak as $kartya): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($kartya['kid']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($kartya['kazonosito']); ?></strong></td>
                                        <td>
                                            <?php if (!empty($kartya['tulajdonos_nev'])): ?>
                                                <strong><?php echo htmlspecialchars($kartya['tulajdonos_nev']); ?></strong>
                                                <?php if (!empty($kartya['tulajdonos_email'])): ?>
                                                    <br><small><?php echo htmlspecialchars($kartya['tulajdonos_email']); ?></small>
                                                <?php endif; ?>
                                                <!-- Tulajdonos törlése gomb -->
                                                <form method="POST" class="ajax-form" style="margin-top: 5px;">
                                                    <input type="hidden" name="keid" value="<?php echo htmlspecialchars($kartya['keid']); ?>">
                                                    <input type="hidden" name="tulajdonos_nev" value="<?php echo htmlspecialchars($kartya['tulajdonos_nev']); ?>">
                                                    <button type="submit" name="remove_card_owner" class="btn btn-delete btn-sm" 
                                                            onclick="return confirm('Biztosan törölni szeretné a tulajdonost? Ez törölni fogja az összes hozzá tartozó járművet is!')"
                                                            style="padding: 3px 8px; font-size: 0.7rem;">
                                                        <i class="fas fa-user-times"></i> Tulajdonos törlése
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <!-- Tulajdonos hozzárendelése -->
                                                <?php if (empty($kartya['tulajdonos_id'])): ?>
                                                    <form method="POST" class="ajax-form" style="display: inline;">
                                                        <input type="hidden" name="kid" value="<?php echo htmlspecialchars($kartya['kid']); ?>">
                                                        <div style="display: flex; gap: 5px; align-items: center;">
                                                            <select name="eid" style="padding: 4px 8px; border: 1px solid var(--border); border-radius: 4px; font-size: 0.8rem;" required>
                                                                <option value="">- Válasszon -</option>
                                                                <?php foreach ($alkalmazottak as $alkalmazott): ?>
                                                                    <?php if ($alkalmazott['estatuszdolg'] == 1): ?>
                                                                        <option value="<?php echo htmlspecialchars($alkalmazott['eid']); ?>">
                                                                            <?php echo htmlspecialchars($alkalmazott['email']); ?>
                                                                        </option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                            <button type="submit" name="assign_card" class="btn btn-success btn-sm" 
                                                                    style="padding: 4px 8px; font-size: 0.7rem;">
                                                                <i class="fas fa-link"></i> Rendel
                                                            </button>
                                                        </div>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($kartya['cnev'] ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $kartya['kallapot'] ? 'status-active' : 'status-inactive'; ?>">
                                                <?php echo $kartya['kallapot'] ? 'Aktív' : 'Inaktív'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($kartya['jarmuvek'])): ?>
                                                <div style="max-width: 150px;">
                                                    <?php foreach ($kartya['jarmuvek'] as $jarmu): ?>
                                                        <span class="count-badge" style="display: block; margin-bottom: 2px; font-size: 0.75rem;">
                                                            <?php echo htmlspecialchars($jarmu['jrendszam']); ?>
                                                            <!-- Jármű törlése gomb -->
                                                            <form method="POST" class="ajax-form" style="display: inline; margin-left: 5px;">
                                                                <input type="hidden" name="jid" value="<?php echo htmlspecialchars($jarmu['jid']); ?>">
                                                                <input type="hidden" name="jrendszam" value="<?php echo htmlspecialchars($jarmu['jrendszam']); ?>">
                                                                <button type="submit" name="delete_vehicle" class="btn btn-delete btn-sm" 
                                                                        onclick="return confirm('Biztosan törölni szeretné ezt a járművet?')"
                                                                        style="padding: 1px 4px; font-size: 0.6rem; margin-left: 5px;">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <span style="color: var(--text-muted); font-size: 0.9rem;">Nincs</span>
                                            <?php endif; ?>
                                            
                                            <!-- Jármű hozzáadása gomb -->
                                            <?php if (!empty($kartya['tulajdonos_id'])): ?>
                                                <form method="POST" style="margin-top: 5px;">
                                                    <input type="hidden" name="kid" value="<?php echo htmlspecialchars($kartya['kid']); ?>">
                                                    <div style="display: flex; gap: 5px; align-items: center;">
                                                        <input type="text" name="jrendszam" placeholder="Rendszám" 
                                                               style="padding: 4px 8px; border: 1px solid var(--border); border-radius: 4px; font-size: 0.8rem; width: 80px;" 
                                                               maxlength="8" required>
                                                        <button type="submit" name="add_vehicle" class="btn btn-success btn-sm" 
                                                                style="padding: 4px 8px; font-size: 0.7rem;">
                                                            <i class="fas fa-plus"></i> Hozzáad
                                                        </button>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <!-- Szerkesztés -->
                                                <button class="btn btn-edit btn-sm" 
                                                        onclick="openCardModal(
                                                            <?php echo (int)$kartya['kid']; ?>,
                                                            '<?php echo htmlspecialchars($kartya['kazonosito'] ?? '', ENT_QUOTES); ?>',
                                                            <?php echo (int)$kartya['kallapot']; ?>,
                                                            <?php echo (int)($kartya['tulajdonos_id'] ?? 0); ?>
                                                        )">
                                                    <i class="fas fa-edit"></i> Szerkesztés
                                                </button>
                                                
                                                
                                                
                                                <!-- Státusz váltás -->
                                                <form method="POST" class="ajax-form" style="display: inline;">
                                                    <input type="hidden" name="kid" value="<?php echo htmlspecialchars($kartya['kid']); ?>">
                                                    <button type="submit" name="toggle_card_status" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-power-off"></i> 
                                                        <?php echo $kartya['kallapot'] ? 'Deaktiválás' : 'Aktiválás'; ?>
                                                    </button>
                                                </form>
                                                
                                                <!-- Kártya törlése -->
                                                <form method="POST" class="ajax-form" style="display: inline;">
                                                    <input type="hidden" name="kid" value="<?php echo htmlspecialchars($kartya['kid']); ?>">
                                                    <button type="submit" name="delete_card" class="btn btn-delete btn-sm" 
                                                            onclick="return confirm('Biztosan törölni szeretné ezt a kártyát?')">
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
                            <i class="fas fa-id-card"></i>
                            <h3>Nincsenek kártyák</h3>
                            <p>
                                <?php if (isset($_GET['card_filter_status']) || isset($_GET['card_filter_company'])): ?>
                                Nincs találat a megadott szűrési feltételekkel.
                                <?php else: ?>
                                Még nincsenek kártyák hozzáadva a rendszerhez.
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="add-card" class="tab-content">
                        <form method="POST">
                            <div class="form-group">
                                <label for="kazonosito">Kártya Azonosító:</label>
                                <input type="number" id="kazonosito" name="kazonosito" class="form-control" required 
                                       placeholder="Kártya egyedi azonosítója (pl. 105263846)">
                            </div>
                            
                            <div class="form-group">
                                <label for="kallapot">Kezdeti állapot:</label>
                                <select id="kallapot" name="kallapot" class="form-control" required>
                                    <option value="1">Aktív</option>
                                    <option value="0">Inaktív</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="hozzarendelt_ember">Tulajdonos (opcionális):</label>
                                <select id="hozzarendelt_ember" name="hozzarendelt_ember" class="form-control">
                                    <option value="">- Válasszon tulajdonost -</option>
                                    <?php foreach ($alkalmazottak as $alkalmazott): ?>
                                        <?php if ($alkalmazott['estatuszdolg'] == 1): ?>
                                            <option value="<?php echo htmlspecialchars($alkalmazott['eid']); ?>">
                                                <?php echo htmlspecialchars($alkalmazott['enev']); ?> 
                                                (<?php echo htmlspecialchars($alkalmazott['cnev'] ?? 'N/A'); ?>)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <button type="submit" name="add_card" class="btn btn-add">
                                <i class="fas fa-plus"></i> Kártya Hozzáadása
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
    
    
    <!-- Jelszó Szerkesztési Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePassModal()">&times;</span>
            <h3><i class="fas fa-key"></i> Jelszó Megváltoztatása</h3>
            
            <form method="POST" id="passwordForm">
                <input type="hidden" name="eid" id="password_eid">
                
                <div class="form-group">
                    <label for="employee_ujjelszo">Új jelszó:</label>
                    <input type="password" id="employee_ujjelszo" name="ujjelszo" class="form-control" required 
                           minlength="6" placeholder="Minimum 6 karakter">
                </div>
                
                <div class="form-group">
                    <label for="employee_ujjelszo_confirm">Jelszó megerősítése:</label>
                    <input type="password" id="employee_ujjelszo_confirm" name="ujjelszo_confirm" class="form-control" required 
                           minlength="6" placeholder="Írja be újra a jelszót">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="edit_password" class="btn btn-edit" style="flex: 1;">
                        <i class="fas fa-save"></i> Jelszó mentése
                    </button>
                    <button type="button" onclick="closePassModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Kártya Szerkesztési Modal -->
    <div id="cardModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCardModal()">&times;</span>
            <h3><i class="fas fa-edit"></i> Kártya Szerkesztése</h3>
            
            <form method="POST" id="cardForm">
                <input type="hidden" name="kid" id="card_kid">
                
                <div class="form-group">
                    <label for="card_kazonosito">Kártya Azonosító:</label>
                    <input type="number" id="card_kazonosito" name="kazonosito" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="card_kallapot">Státusz:</label>
                    <select id="card_kallapot" name="kallapot" class="form-control" required>
                        <option value="1">Aktív</option>
                        <option value="0">Inaktív</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="card_tulajdonos">Tulajdonos:</label>
                    <select id="card_tulajdonos" name="tulajdonos_id" class="form-control">
                        <option value="">- Nincs tulajdonos -</option>
                        <?php foreach ($alkalmazottak as $alkalmazott): ?>
                            <?php if ($alkalmazott['estatuszdolg'] == 1): ?>
                                <option value="<?php echo htmlspecialchars($alkalmazott['eid']); ?>">
                                    <?php echo htmlspecialchars($alkalmazott['enev']); ?> 
                                    (<?php echo htmlspecialchars($alkalmazott['cnev'] ?? 'N/A'); ?>)
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="edit_card" class="btn btn-edit" style="flex: 1;">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <button type="button" onclick="closeCardModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Jármű Hozzáadása Modal -->
    <div id="vehicleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeVehicleModal()">&times;</span>
            <h3><i class="fas fa-car"></i> Jármű Hozzáadása</h3>
            
            <form method="POST" id="vehicleForm">
                <input type="hidden" name="kid" id="vehicle_kid">
                
                <div class="form-group">
                    <label for="vehicle_rendszam">Rendszám:</label>
                    <input type="text" id="vehicle_rendszam" name="jrendszam" class="form-control" required 
                           placeholder="ABC-123" maxlength="8">
                </div>
                
                <div class="form-group">
                    <label for="vehicle_tipus">Típus:</label>
                    <input type="text" id="vehicle_tipus" name="jtipus" class="form-control" 
                           placeholder="Pl. Toyota Corolla" maxlength="20">
                </div>
                
                <div class="form-group">
                    <label for="vehicle_szin">Szín:</label>
                    <input type="text" id="vehicle_szin" name="jszin" class="form-control" 
                           placeholder="Pl. fehér" maxlength="20">
                </div>
                
                <div class="form-group">
                    <label for="vehicle_komment">Megjegyzés:</label>
                    <textarea id="vehicle_komment" name="jkomment" class="form-control" rows="3" 
                              placeholder="Opcionális megjegyzés..." maxlength="100"></textarea>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" name="add_vehicle" class="btn btn-add" style="flex: 1;">
                        <i class="fas fa-plus"></i> Jármű Hozzáadása
                    </button>
                    <button type="button" onclick="closeVehicleModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    
    <!-- Manuális kijelentkeztetés Modal - ÚJ -->
    <div id="manualCheckoutModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeManualCheckoutModal()">&times;</span>
            <h3><i class="fas fa-sign-out-alt"></i> Manuális Kijelentkeztetés</h3>
            
            <form method="POST" id="manualCheckoutForm">
                <input type="hidden" name="nid" id="checkout_nid">
                
                <div class="form-group">
                    <label for="checkout_alkalmazott">Alkalmazott:</label>
                    <input type="text" id="checkout_alkalmazott" class="form-control" readonly style="background: var(--border);">
                </div>
                
                <div class="form-group">
                    <label for="manual_checkout_time">Távozás időpontja:</label>
                    <input type="datetime-local" id="manual_checkout_time" name="manual_checkout_time" 
                           class="form-control" required 
                           value="<?php echo date('Y-m-d\TH:i'); ?>">
                    <small style="color: var(--text-muted); margin-top: 5px; display: block;">
                        <i class="fas fa-info-circle"></i> Adja meg, mikor hagyta el a parkolót
                    </small>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button type="submit" name="manual_checkout" class="btn btn-warning" style="flex: 1;">
                        <i class="fas fa-check"></i> Kijelentkeztetés
                    </button>
                    <button type="button" onclick="closeManualCheckoutModal()" class="btn btn-delete" style="flex: 1;">
                        <i class="fas fa-times"></i> Mégse
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    
    

    <script>
    
        
        // Kód form validáció
        document.addEventListener('DOMContentLoaded', function() {
            const codeForm = document.getElementById('addCodeForm');
            const codeInput = document.getElementById('kod');
            
            if (codeForm && codeInput) {
                // Csak számok engedélyezése
                codeInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
                
                // Form submit ellenőrzés
                codeForm.addEventListener('submit', function(e) {
                    const codeValue = codeInput.value;
                    
                    if (codeValue.length !== 4) {
                        e.preventDefault();
                        alert('A kódnak pontosan 4 számjegyből kell állnia!');
                        codeInput.focus();
                        return false;
                    }
                    
                    if (!/^\d{4}$/.test(codeValue)) {
                        e.preventDefault();
                        alert('A kód csak számjegyeket tartalmazhat!');
                        codeInput.focus();
                        return false;
                    }
                    
                    return true;
                });
            }
        });
        
                
        // Automatikus fókusz a kód mezőre
        function switchTab(tabId) {
            // ... (a meglévő tab váltás logika)
            
            if (tabId === 'add-code') {
                setTimeout(() => {
                    const codeInput = document.getElementById('kod');
                    if (codeInput) {
                        codeInput.focus();
                    }
                }, 100);
            }
        }
    
    
        // Manuális kijelentkeztetés modal funkciók - ÚJ
        function openManualCheckoutModal(nid, alkalmazottNev) {
            document.getElementById('checkout_nid').value = nid;
            document.getElementById('checkout_alkalmazott').value = alkalmazottNev;
            document.getElementById('manualCheckoutModal').style.display = 'block';
        }
        
        function closeManualCheckoutModal() {
            document.getElementById('manualCheckoutModal').style.display = 'none';
        }
        
    
        // Mobile cards initialization - JAVÍTOTT VÁLTOZAT
        function initializeMobileCards() {
            console.log('Initializing mobile cards...');
            
            // Csak mobil nézetben inicializáljuk
            if (window.innerWidth > 768) {
                console.log('Desktop view - skipping mobile cards');
                // Asztali nézetben biztosítsuk, hogy csak táblázatok látszódjanak
                document.querySelectorAll('.mobile-cards').forEach(cards => {
                    cards.style.display = 'none';
                });
                document.querySelectorAll('.table-responsive table').forEach(table => {
                    table.style.display = '';
                });
                return;
            }
            
            // Táblázatok keresése - MINDEN table-responsive-ban lévő táblázat
            const tableContainers = document.querySelectorAll('.table-responsive');
            console.log('Found table containers:', tableContainers.length);
            
            tableContainers.forEach((tableContainer, containerIndex) => {
                const table = tableContainer.querySelector('table');
                if (!table) {
                    console.log('No table found in container', containerIndex);
                    return;
                }
                
                // Ellenőrizzük, hogy már létezik-e a mobile cards container
                const existingMobileCards = tableContainer.nextElementSibling;
                if (existingMobileCards && existingMobileCards.classList.contains('mobile-cards')) {
                    console.log('Mobile cards already exist for table', containerIndex);
                    return;
                }
                
                // Create mobile cards container
                const mobileCards = document.createElement('div');
                mobileCards.className = 'mobile-cards';
                mobileCards.id = `mobile-cards-${containerIndex}`;
                
                // Get table headers for labels
                const headers = Array.from(table.querySelectorAll('thead th')).map(th => {
                    return th.textContent.trim();
                });
                
                console.log('Headers:', headers);
                
                // Convert each row to a card
                const rows = table.querySelectorAll('tbody tr');
                console.log('Rows found:', rows.length);
                
                if (rows.length === 0) {
                    console.log('No rows in table');
                    return;
                }
                
                rows.forEach((row, rowIndex) => {
                    const card = document.createElement('div');
                    card.className = 'mobile-card';
                    
                    const cells = Array.from(row.querySelectorAll('td'));
                    console.log(`Row ${rowIndex} cells:`, cells.length);
                    
                    if (cells.length === 0) return;
                    
                    let cardContent = '';
                    const firstCell = cells[0];
                    
                    // Card header
                    const title = firstCell.textContent.trim() || `#${rowIndex + 1}`;
                    const badges = firstCell.querySelectorAll('.status-badge, .role-badge, .count-badge');
                    
                    let badgesHtml = '';
                    badges.forEach(badge => {
                        badgesHtml += badge.outerHTML;
                    });
                    
                    cardContent += `
                        <div class="mobile-card-header">
                            <div class="mobile-card-title">${title}</div>
                            <div class="mobile-card-badges">${badgesHtml}</div>
                        </div>
                        <div class="mobile-card-content">
                    `;
                    
                    // Card content - minden cella (az elsőt kihagyjuk, mert az a cím)
                    for (let cellIndex = 1; cellIndex < cells.length; cellIndex++) {
                        const cell = cells[cellIndex];
                        const label = headers[cellIndex] || `Mező ${cellIndex}`;
                        let value = cell.innerHTML.trim();
                        
                        // Ha nincs érték, ugrunk
                        if (!value || value === '') continue;
                        
                        // Action gombok eltávolítása a megjelenítésből
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = value;
                        const actionButtons = tempDiv.querySelector('.table-actions');
                        if (actionButtons) {
                            actionButtons.remove();
                        }
                        value = tempDiv.innerHTML.trim();
                        
                        if (value && value !== '') {
                            cardContent += `
                                <div class="mobile-card-row">
                                    <span class="mobile-card-label">${label}:</span>
                                    <span class="mobile-card-value">${value}</span>
                                </div>
                            `;
                        }
                    }
                    
                    cardContent += `</div>`;
                    
                    // Action buttons hozzáadása
                    const actionButtons = row.querySelector('.table-actions');
                    if (actionButtons && actionButtons.innerHTML.trim() !== '') {
                        cardContent += `
                            <div class="mobile-card-actions">
                                ${actionButtons.innerHTML}
                            </div>
                        `;
                    }
                    
                    card.innerHTML = cardContent;
                    mobileCards.appendChild(card);
                });
                
                // Mobile cards beszúrása
                tableContainer.parentNode.insertBefore(mobileCards, tableContainer.nextSibling);
                console.log('Mobile cards created for table container', containerIndex);
            });
        }
        
        // Resize observer a mobil nézet váltáshoz
        function setupResponsiveObserver() {
            let resizeTimer;
            
            // Resize esemény figyelése
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    console.log('Window resized to:', window.innerWidth);
                    initializeMobileCards();
                }, 250);
            });
        }
        
        // Navigation váltáskor is inicializáljuk
        function setupNavigationListener() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('nav-link')) {
                    setTimeout(function() {
                        console.log('Navigation changed - reinitializing mobile cards');
                        // Eltávolítjuk a régi mobile cards-okat
                        document.querySelectorAll('.mobile-cards').forEach(cards => {
                            cards.remove();
                        });
                        initializeMobileCards();
                    }, 500);
                }
            });
        }
        
        // Tab váltáskor is inicializáljuk
        function setupTabListener() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('tab')) {
                    setTimeout(function() {
                        console.log('Tab changed - reinitializing mobile cards');
                        // Eltávolítjuk a régi mobile cards-okat
                        document.querySelectorAll('.mobile-cards').forEach(cards => {
                            cards.remove();
                        });
                        initializeMobileCards();
                    }, 500);
                }
            });
        }
        
        // Oldal betöltésekor inicializálás
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing mobile cards');
            
            // Rövid késleltetés, hogy biztosan renderelődjön minden
            setTimeout(function() {
                initializeMobileCards();
                setupResponsiveObserver();
                setupNavigationListener();
                setupTabListener();
            }, 100);
            
            // Biztonsági inicializálás késleltetéssel is
            setTimeout(initializeMobileCards, 500);
            setTimeout(initializeMobileCards, 1000);
        });

    
    
    
    
    
    
    
    
    
        // Visszaszámláló és kiléptetési funkciók
        let countdownTimer;
        let inactivityTimer;
        let isLoggingOut = false;
        
        // Kezdő idő beállítása vagy betöltése
        function getRemainingTime() {
            const savedTime = sessionStorage.getItem('countdownRemaining');
            const savedTimestamp = sessionStorage.getItem('countdownStartTime');
            const now = Math.floor(Date.now() / 1000);
            
            if (savedTime && savedTimestamp) {
                const elapsed = now - parseInt(savedTimestamp);
                const remaining = parseInt(savedTime) - elapsed;
                return Math.max(0, remaining);
            }
            
            // Új session - 15 perc (vagy ha kiléptünk és újra beléptünk)
            const initialTime = 15 * 60;
            saveRemainingTime(initialTime);
            return initialTime;
        }
        
        // Idő mentése sessionStorage-ba
        function saveRemainingTime(remaining) {
            sessionStorage.setItem('countdownRemaining', remaining.toString());
            sessionStorage.setItem('countdownStartTime', Math.floor(Date.now() / 1000).toString());
        }
        
        // Idő törlése sessionStorage-ból (kilépéskor)
        function clearSavedTime() {
            sessionStorage.removeItem('countdownRemaining');
            sessionStorage.removeItem('countdownStartTime');
            sessionStorage.removeItem('userSessionActive');
        }
        
        // Session megjelölése aktívnak
        function markSessionActive() {
            sessionStorage.setItem('userSessionActive', 'true');
        }
        
        // Session ellenőrzése - új session kezdése
        function checkAndStartSession() {
            const sessionActive = sessionStorage.getItem('userSessionActive');
            
            if (!sessionActive) {
                // Új session - töröljük a régi időket és kezdjük újra
                clearSavedTime();
                markSessionActive();
                console.log('🆕 Új session indítása - 15 perc');
                return true;
            }
            
            return false;
        }
        
        // Fő visszaszámláló frissítése
        function updateCountdown() {
            if (isLoggingOut) return;
            
            let remainingTime = getRemainingTime();
            
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            
            const countdownText = document.getElementById('countdownText');
            const countdownSidebarText = document.getElementById('countdownSidebarText');
            const countdownContainer = document.getElementById('countdownTimer');
            const countdownSidebar = document.getElementById('countdownSidebar');
            
            // Frissítjük mindkét visszaszámlálót
            if (countdownText) {
                countdownText.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            if (countdownSidebarText) {
                countdownSidebarText.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            // Stílus változtatás figyelmeztetésekhez - HEADER
            if (countdownContainer) {
                countdownContainer.className = 'countdown-container';
                
                if (remainingTime <= 60) {
                    countdownContainer.classList.add('error');
                } else if (remainingTime <= 300) {
                    countdownContainer.classList.add('warning');
                } else {
                    countdownContainer.classList.add('normal');
                }
            }
            
            // Stílus változtatás figyelmeztetésekhez - SIDEBAR
            if (countdownSidebar) {
                countdownSidebar.className = 'countdown-sidebar';
                
                if (remainingTime <= 60) {
                    countdownSidebar.classList.add('error');
                } else if (remainingTime <= 300) {
                    countdownSidebar.classList.add('warning');
                }
            }
            
            // 0 másodpercnél kiléptetés
            if (remainingTime <= 0) {
                clearInterval(countdownTimer);
                clearTimeout(inactivityTimer);
                forceLogout();
            } else {
                // Idő mentése minden frissítéskor
                saveRemainingTime(remainingTime);
            }
        }
        
        // Erős kiléptetés - mindenképpen kiléptet
        function forceLogout() {
            if (isLoggingOut) return;
            isLoggingOut = true;
            
            console.log('⏰ Idő lejárt - automatikus kiléptetés');
            clearSavedTime(); // Töröljük a mentett időt
            window.location.href = 'admin_logout.php?reason=timeout';
        }
        
        // Inaktivitás kiléptetés
        function logoutDueToInactivity() {
            if (isLoggingOut) return;
            isLoggingOut = true;
            
            console.log('💤 Inaktivitás - automatikus kiléptetés');
            clearSavedTime(); // Töröljük a mentett időt
            window.location.href = 'admin_logout.php?reason=inactivity';
        }
        
        // Manuális kilépés
        function manualLogout() {
            if (isLoggingOut) return;
            isLoggingOut = true;
            
            console.log('🚪 Manuális kilépés');
            clearSavedTime(); // Töröljük a mentett időt
            window.location.href = 'admin_logout.php?reason=manual';
        }
        
        // Visszaszámláló alaphelyzetbe állítása (CSAK interakcióra)
        function resetTimers() {
            // NEM állítjuk vissza a számlálót, csak az inaktivitás detektálást
            clearTimeout(inactivityTimer);
            inactivityTimer = setTimeout(logoutDueToInactivity, 15 * 60 * 1000);
            
            // Frissítjük a megjelenítést
            updateCountdown();
        }
        
        // Eseményfigyelők az aktivitás detektálásához
        const activityEvents = ['mousemove', 'keypress', 'keydown', 'click', 'scroll', 'touchstart', 'mousedown', 'input', 'change'];
        activityEvents.forEach(event => {
            document.addEventListener(event, resetTimers, { passive: true });
        });
        
        // Oldal betöltésekor indítjuk az időzítőket
        document.addEventListener('DOMContentLoaded', function() {
            // Session ellenőrzése és indítása
            checkAndStartSession();
            
            console.log('🚀 Időzítők indítása - session idő: ' + getRemainingTime() + ' másodperc');
            
            // Visszaszámláló indítása (másodpercenként)
            countdownTimer = setInterval(updateCountdown, 1000);
            updateCountdown();
            
            // Inaktivitás timer indítása
            resetTimers();
        });
        
        // Biztonsági időzítő - mindenképpen kiléptet 16 perc után
        setTimeout(() => {
            if (!isLoggingOut) {
                console.log('🛡️ Biztonsági időzítő - kiléptetés');
                forceLogout();
            }
        }, 16 * 60 * 1000); // 16 perc (1 perc extra biztonság)
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
        
        
        
        function openPassModal(eid) {
            console.log('Jelszó modal megnyitása ID:', eid);
            
            document.getElementById('password_eid').value = eid;
            document.getElementById('employee_ujjelszo').value = '';
            document.getElementById('employee_ujjelszo_confirm').value = '';
        
            try {
                document.getElementById('passwordModal').style.display = 'block';
            } catch (error) {
                console.error('Hiba a jelszó modal megnyitásakor:', error);
            }
        }
        
        function closePassModal() {
            try {
                document.getElementById('passwordModal').style.display = 'none';
                // Form reset
                document.getElementById('passwordForm').reset();
            } catch (error) {
                console.error('Hiba a modal bezárásakor:', error);
            }
        }
        
        // Kártya modal funkciók
        function openCardModal(kid, kazonosito, kallapot, tulajdonos_id) {
            document.getElementById('card_kid').value = kid;
            document.getElementById('card_kazonosito').value = kazonosito;
            document.getElementById('card_kallapot').value = kallapot;
            document.getElementById('card_tulajdonos').value = tulajdonos_id || '';
            
            document.getElementById('cardModal').style.display = 'block';
        }
        
        function closeCardModal() {
            document.getElementById('cardModal').style.display = 'none';
        }
        
        function openVehicleModal(kid) {
            document.getElementById('vehicle_kid').value = kid;
            document.getElementById('vehicleModal').style.display = 'block';
        }
        
        function closeVehicleModal() {
            document.getElementById('vehicleModal').style.display = 'none';
        }
        
        
        // Cég modal
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