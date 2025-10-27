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

// Naplózás - kiírjuk a kapott adatot
error_log("=== KÁRTYA ELLENŐRZÉS ===");
error_log("Kapott JSON: " . $input);
if ($data && isset($data['card_id'])) {
    error_log("Kapott kártya ID: " . $data['card_id']);
} else {
    error_log("HIBA: Nem érkezett card_id");
}

$response = false;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Összes kártya listázása (debug célra)
    $all_cards_stmt = $conn->prepare("SELECT kazonosito FROM kartya WHERE kallapot = 1");
    $all_cards_stmt->execute();
    $all_cards = $all_cards_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    error_log("Adatbázisban lévő AKTÍV kártyák: " . implode(", ", $all_cards));

    if ($data && isset($data['card_id'])) {
        $card_id = $data['card_id'];
        
        // 1. ELLENŐRZÉS: Létezik-e a kártya és aktív-e
        $stmt = $conn->prepare("SELECT kid, kallapot FROM kartya WHERE kazonosito = :card_id");
        $stmt->bindParam(':card_id', $card_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $kartya = $stmt->fetch(PDO::FETCH_ASSOC);
            $kid = $kartya['kid'];
            $kallapot = $kartya['kallapot'];
            
            // 2. ELLENŐRZÉS: Aktív-e a kártya
            if ($kallapot == 1) {
                error_log("KÁRTYA AKTÍV: " . $card_id);
                
                // 3. ELLENŐRZÉS: Van-e kartyaember kapcsolat
                $ke_stmt = $conn->prepare("SELECT keid FROM kartyaember WHERE kid = :kid");
                $ke_stmt->bindParam(':kid', $kid);
                $ke_stmt->execute();
                
                if ($ke_stmt->rowCount() > 0) {
                    $ke_row = $ke_stmt->fetch(PDO::FETCH_ASSOC);
                    $keid = $ke_row['keid'];
                    
                    // 4. ELLENŐRZÉS: Aktív parkolás van-e már ezzel a kártyával
                    $active_parking_stmt = $conn->prepare("
                        SELECT nid 
                        FROM naplo 
                        WHERE keid = :keid 
                        AND nidoig IS NULL
                    ");
                    $active_parking_stmt->bindParam(':keid', $keid);
                    $active_parking_stmt->execute();
                    
                    if ($active_parking_stmt->rowCount() > 0) {
                        // Aktív parkolás van, nem engedjük be újra
                        error_log("AKTÍV PARKOLÁS MEGTALÁLVA - Belépés megtagadva: " . $card_id);
                        $response = false;
                        
                    } else {
                        // Nincs aktív parkolás, beírjuk a naplóba a belépést
                        $naplo_stmt = $conn->prepare("
                            INSERT INTO naplo (keid, nidotol, nidoig, nparkhely) 
                            VALUES (:keid, NOW(), NULL, NULL)
                        ");
                        $naplo_stmt->bindParam(':keid', $keid);
                        
                        if ($naplo_stmt->execute()) {
                            error_log("Naplóbejegyzés létrehozva: keid=" . $keid);
                            $response = true;
                        } else {
                            error_log("HIBA: Naplóbejegyzés nem sikerült");
                            $response = false;
                        }
                    }
                    
                } else {
                    error_log("KÁRTYA NINCS HOZZÁRENDELVE: " . $card_id);
                    $response = false;
                }
            } else {
                error_log("KÁRTYA INAKTÍV: " . $card_id);
                $response = false;
            }
        } else {
            error_log("KÁRTYA NEM TALÁLHATÓ: " . $card_id);
            $response = false;
        }
    } else {
        error_log("HIÁNYZÓ KÁRTYA AZONOSÍTÓ");
        $response = false;
    }
    
} catch(PDOException $e) {
    error_log("ADATBÁZIS HIBA: " . $e->getMessage());
    $response = false;
}

error_log("Válasz a Pico-nak: " . ($response ? "TRUE" : "FALSE"));
error_log("=== VÉGE ===");

// Csak true vagy false értéket küldünk vissza
echo json_encode($response);

if (isset($conn)) {
    $conn = null;
}
?>