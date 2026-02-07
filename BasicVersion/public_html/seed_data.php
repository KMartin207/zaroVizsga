<?php
require_once 'db.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Random parkolási adatok generálása
    $stmt = $conn->prepare("INSERT INTO naplo (keid, nstatusz, ndatumtol, nidotol, ndatumig, nidoig, nparkhely) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // 50 random parkolási rekord
    for ($i = 0; $i < 50; $i++) {
        $keid = rand(1, 3); // 1-3 kártya
        $nstatusz = rand(0, 1);
        $days_ago = rand(0, 30);
        $hours_parked = rand(1, 10);
        
        $ndatumtol = date('Y-m-d', strtotime("-$days_ago days"));
        $nidotol = date('Y-m-d H:i:s', strtotime("-$days_ago days " . rand(6, 10) . " hours"));
        $ndatumig = $nstatusz ? $ndatumtol : '0000-00-00';
        $nidoig = $nstatusz ? date('Y-m-d H:i:s', strtotime($nidotol . " + $hours_parked hours")) : '0000-00-00 00:00:00';
        $nparkhely = rand(100, 150);
        
        $stmt->execute([$keid, $nstatusz, $ndatumtol, $nidotol, $ndatumig, $nidoig, $nparkhely]);
    }
    
    echo "Tesztadatok sikeresen feltöltve!";
    
} catch(Exception $e) {
    echo "Hiba: " . $e->getMessage();
}
?>