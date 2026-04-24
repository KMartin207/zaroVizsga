<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Adatbázis kapcsolat
$servername = "localhost";
$username = "lbzhvkxw_martin";
$password = "ProParkingDatabase";
$dbname = "lbzhvkxw_parking_db";

// JSON adat fogadása
$input = file_get_contents('php://input');
$data = json_decode($input, true);

error_log("=== ADATKEZELÉS ===");
error_log("Kapott JSON: " . $input);

$response = ["success" => false, "message" => "Ismeretlen hiba"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($data && isset($data['type'])) {
        if ($data['type'] == 'card') {
            // KÁRTYA ELLENŐRZÉS
            $card_id = $data['card_id'] ?? '';
            
            error_log("Kártya ellenőrzés: " . $card_id);
            
            // 1. Kártya létezik-e és aktív-e
            $stmt = $conn->prepare("SELECT k.kid, k.kazonosito, k.kallapot, e.enev, e.cid, c.cnev,
                                   ke.keid
                                   FROM kartya k 
                                   LEFT JOIN kartyaember ke ON k.kid = ke.kid 
                                   LEFT JOIN ember e ON ke.eid = e.eid 
                                   LEFT JOIN ceg c ON e.cid = c.cid 
                                   WHERE k.kazonosito = :card_id");
            $stmt->bindParam(':card_id', $card_id);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $kartya = $stmt->fetch(PDO::FETCH_ASSOC);
                
                error_log("Kartya adatok: " . json_encode($kartya));
                
                // 2. Aktív-e a kártya
                if ($kartya['kallapot'] == 1) {
                    // 3. Weiss cég ellenőrzése
                    if ($kartya['cid'] == 2) {
                        $keid = $kartya['keid'] ?? null;
                        
                        if ($keid) {
                            // 4. Aktív parkolás ellenőrzése
                            $parking_stmt = $conn->prepare("SELECT nid, nidotol FROM naplo WHERE keid = :keid AND nidoig IS NULL");
                            $parking_stmt->bindParam(':keid', $keid);
                            $parking_stmt->execute();
                            
                            if ($parking_stmt->rowCount() > 0) {
                                // Aktív parkolás van - kilépés (VISZLÁT)
                                $parking = $parking_stmt->fetch(PDO::FETCH_ASSOC);
                                
                                // Parkolás lezárása
                                $update_stmt = $conn->prepare("UPDATE naplo SET nidoig = NOW() WHERE nid = :nid");
                                $update_stmt->bindParam(':nid', $parking['nid']);
                                
                                if ($update_stmt->execute()) {
                                    $response = [
                                        "success" => true,
                                        "message" => "Kilepes engedelyezve",
                                        "user_name" => $kartya['enev'] ?? "Ismeretlen",
                                        "action_type" => "exit"
                                    ];
                                    error_log("KILEPES: " . $card_id . " - " . $kartya['enev'] . " - Naplo ID: " . $parking['nid']);
                                } else {
                                    $response = ["success" => false, "message" => "Naplo frissitesi hiba"];
                                }
                                
                            } else {
                                // Nincs aktív parkolás - belépés (ÜDVÖZLÖM)
                                // BEÍRJUK A NAPLÓBA A BELÉPÉST
                                $insert_stmt = $conn->prepare("INSERT INTO naplo (keid, nidotol) VALUES (:keid, NOW())");
                                $insert_stmt->bindParam(':keid', $keid);
                                
                                if ($insert_stmt->execute()) {
                                    $naplo_id = $conn->lastInsertId();
                                    $response = [
                                        "success" => true,
                                        "message" => "Belepes engedelyezve", 
                                        "user_name" => $kartya['enev'] ?? "Ismeretlen",
                                        "action_type" => "enter"
                                    ];
                                    error_log("BELEPES: " . $card_id . " - " . $kartya['enev'] . " - Naplo ID: " . $naplo_id);
                                } else {
                                    $response = ["success" => false, "message" => "Naplo bejegyzesi hiba"];
                                }
                            }
                        } else {
                            $response = ["success" => false, "message" => "Kartya nincs hozzarendelve"];
                        }
                    } else {
                        $response = ["success" => false, "message" => "Nem Weiss ceg kartyaja"];
                    }
                } else {
                    $response = ["success" => false, "message" => "Kartya inaktiv"];
                }
            } else {
                $response = ["success" => false, "message" => "Kartya nem talalhato"];
            }
            
        } elseif ($data['type'] == 'code') {
            // KÓD ELLENŐRZÉS
            $code = $data['code'] ?? '';
            
            error_log("Kód ellenőrzés: " . $code);
            
            if (strlen($code) == 4 && is_numeric($code)) {
                // 1. Kód létezik-e és aktív-e
                $stmt = $conn->prepare("SELECT k.*, c.cnev 
                                       FROM kodok k 
                                       LEFT JOIN ceg c ON k.cid = c.cid 
                                       WHERE k.kod = :code AND k.hasznalt = 0");
                $stmt->bindParam(':code', $code);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $kod = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    error_log("Kod adatok: " . json_encode($kod));
                    
                    // 2. Lejárat ellenőrzése
                    $now = date('Y-m-d');
                    if (!$kod['lejarat'] || $kod['lejarat'] >= $now) {
                        // 3. Weiss cég ellenőrzése
                        if ($kod['cid'] == 2) {
                            // Kód felhasználásának jelölése
                            $update_stmt = $conn->prepare("UPDATE kodok SET hasznalt = 1, felhasznalva = NOW() WHERE id = :id");
                            $update_stmt->bindParam(':id', $kod['id']);
                            
                            if ($update_stmt->execute()) {
                                // KÓD ESETÉN IS BEÍRJUK A NAPLÓBA A BELÉPÉST
                                
                                
                                
                                    $naplo_id = $conn->lastInsertId();
                                    $response = [
                                        "success" => true,
                                        "message" => "Kod elfogadva",
                                        "user_name" => "Vendeg (" . $code . ")",
                                        "action_type" => "enter"  // Kód mindig belépés
                                    ];
                                    error_log("KOD ELFOGADVA: " . $code . " - Naplo ID: " . $naplo_id);
                                
                            } else {
                                $response = ["success" => false, "message" => "Kod frissitesi hiba"];
                            }
                        } else {
                            $response = ["success" => false, "message" => "Nem Weiss ceg kodja"];
                        }
                    } else {
                        $response = ["success" => false, "message" => "Kod lejart"];
                    }
                } else {
                    $response = ["success" => false, "message" => "Kod nem talalhato vagy mar felhasznalt"];
                }
            } else {
                $response = ["success" => false, "message" => "Ervenytelen kod formatum"];
            }
        }
    }
    
} catch(PDOException $e) {
    error_log("ADATBÁZIS HIBA: " . $e->getMessage());
    $response = ["success" => false, "message" => "Adatbazis hiba: " . $e->getMessage()];
}

error_log("Válasz: " . json_encode($response));
error_log("=== VÉGE ===");

echo json_encode($response);

if (isset($conn)) {
    $conn = null;
}
?>