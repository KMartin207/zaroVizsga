<?php
require_once 'db.php';
session_start();

$database = new Database();
$conn = $database->getConnection();

// --- HELPER FUNCTIONS (PDO) ---

function getParkingStats($conn)
{
    // Statisztikák a 'naplo' és 'active_parking' alapján
    // Ha a 'naplo' a fő forrás:
    try {
        $stmt = $conn->query("SELECT COUNT(*) as active_count FROM naplo WHERE nidoig IS NULL AND nparkhely IS NOT NULL");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $active = $result['active_count'];

        // Feltételezve 100 helyet
        $total = 100;
        $free = max(0, $total - $active);

        return ['active' => $active, 'free' => $free, 'total' => $total];
    }
    catch (PDOException $e) {
        return ['active' => 0, 'free' => 0, 'total' => 100];
    }
}

$stats = getParkingStats($conn);
$search_result = null;
$history_result = null;

// --- HANDLE FORMS ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. KERESÉS (FIND CAR)
    if (isset($_POST['find_car'])) {
        $search_term = trim($_POST['search_term']);
        if (!empty($search_term)) {
            try {
                $sql = "SELECT e.efelhasz, n.nparkhely, n.nidotol 
                        FROM naplo n 
                        JOIN kartyaember ke ON n.keid = ke.keid 
                        JOIN ember e ON ke.eid = e.eid 
                        WHERE e.efelhasz LIKE :term 
                        AND n.nidoig IS NULL 
                        AND n.nparkhely IS NOT NULL 
                        LIMIT 1";
                $stmt = $conn->prepare($sql);
                $term = "%" . $search_term . "%";
                $stmt->bindParam(':term', $term);
                $stmt->execute();
                $search_result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$search_result) {
                    $_SESSION['flash_error'] = "Nem található aktív parkolás ezen a néven.";
                }
                else {
                    $_SESSION['flash_success'] = "Jármű megtalálva!";
                }
            }
            catch (PDOException $e) {
                $_SESSION['flash_error'] = "Hiba történt a keresés során.";
            }
        }
    }

    // 2. ELŐZMÉNYEK (HISTORY) - NEW FEATURE
    if (isset($_POST['show_history'])) {
        $history_term = trim($_POST['history_term']);
        if (!empty($history_term)) {
            try {
                $sql = "SELECT e.efelhasz, n.nparkhely, n.nidotol, n.nidoig,
                        TIMESTAMPDIFF(MINUTE, n.nidotol, n.nidoig) as duration_mins
                        FROM naplo n 
                        JOIN kartyaember ke ON n.keid = ke.keid 
                        JOIN ember e ON ke.eid = e.eid 
                        WHERE e.efelhasz LIKE :term 
                        AND n.nidoig IS NOT NULL 
                        ORDER BY n.nidotol DESC 
                        LIMIT 5";
                $stmt = $conn->prepare($sql);
                $term = "%" . $history_term . "%";
                $stmt->bindParam(':term', $term);
                $stmt->execute();
                $history_result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($history_result)) {
                    $_SESSION['flash_error'] = "Nincsenek korábbi parkolások ezen a néven.";
                }
            }
            catch (PDOException $e) {
                $_SESSION['flash_error'] = "Adatbázis hiba: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProParking | Okos Parkolás</title>
    
    <!-- Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

    <style>
        :root {
            --bg-dark: #0a0a12;
            --primary: #00f3ff;
            --secondary: #0066cc;
            --accent: #ff0055;
            --success: #00ff9d;
            --glass: rgba(16, 25, 40, 0.85);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f0f0f0;
            --text-muted: #94a3b8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--bg-dark);
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 243, 255, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(255, 0, 85, 0.05) 0%, transparent 20%);
            color: var(--text-main);
            min-height: 100vh;
        }
        
        /* Typography */
        h1, h2, h3 { font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 2px; }
        .gradient-text {
            background: linear-gradient(90deg, #fff, var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Layout */
        .container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        
        /* Components */
        .card {
            background: var(--glass);
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s;
        }
        .card:hover { transform: translateY(-5px); border-color: var(--primary); }

        .btn {
            background: linear-gradient(90deg, rgba(0,243,255,0.1), rgba(0,243,255,0.3));
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 1rem 2rem;
            font-family: 'Orbitron';
            font-weight: 700;
            letter-spacing: 1px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
            text-transform: uppercase;
        }
        .btn:hover { background: var(--primary); color: #000; box-shadow: 0 0 20px var(--primary); }

        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(0,0,0,0.3);
            border: 1px solid var(--border);
            color: white;
            border-radius: 5px;
            font-family: 'Orbitron';
        }
        input:focus { outline: none; border-color: var(--primary); }

        /* Stats Bar */
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .stat-box {
            flex: 1;
            background: var(--glass);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border-bottom: 3px solid var(--primary);
            min-width: 200px;
        }
        .stat-value { font-size: 2.5rem; font-family: 'Orbitron'; margin: 0.5rem 0; display: block; }
        .stat-label { color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase; }

        /* Alerts */
        .alert { padding: 1rem; border-radius: 5px; margin-bottom: 1rem; text-align: center; }
        .alert-success { background: rgba(0, 255, 157, 0.1); border: 1px solid var(--success); color: var(--success); }
        .alert-error { background: rgba(255, 0, 85, 0.1); border: 1px solid var(--accent); color: var(--accent); }

        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav style="padding: 1.5rem 0; border-bottom: 1px solid var(--border); background: rgba(0,0,0,0.5);">
        <div class="container" style="display:flex; justify-content:space-between; align-items:center; padding-top:0; padding-bottom:0;">
            <div style="font-size:1.5rem; font-family:'Orbitron'; font-weight:900;">
                <i class="fa-solid fa-square-parking" style="color:var(--primary)"></i> WEISS<span style="font-weight:300">PARKING</span>
            </div>
            <div style="font-family:'Orbitron'; font-size:0.9rem; color:var(--text-muted);">
                <i class="fa-regular fa-clock"></i> <span id="clock">--:--:--</span>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- HERO STATS -->
        <div class="stats-row">
            <div class="stat-box" style="border-color: var(--success);">
                <i class="fa-solid fa-circle-check" style="color: var(--success); font-size: 1.5rem;"></i>
                <span class="stat-value" style="color: var(--success);"><?php echo $stats['free']; ?></span>
                <span class="stat-label">Szabad Hely</span>
            </div>
            <div class="stat-box" style="border-color: var(--primary);">
                <i class="fa-solid fa-car" style="color: var(--primary); font-size: 1.5rem;"></i>
                <span class="stat-value" style="color: var(--primary);"><?php echo $stats['active']; ?></span>
                <span class="stat-label">Jármű Bent</span>
            </div>
            <div class="stat-box" style="border-color: var(--accent);">
                <i class="fa-solid fa-users" style="color: var(--accent); font-size: 1.5rem;"></i>
                <span class="stat-value" style="color: var(--accent);">24/7</span>
                <span class="stat-label">Üzemidő</span>
            </div>
        </div>

        <!-- MESSAGES -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['flash_success'];
    unset($_SESSION['flash_success']); ?></div>
        <?php
endif; ?>
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-error"><?php echo $_SESSION['flash_error'];
    unset($_SESSION['flash_error']); ?></div>
        <?php
endif; ?>

        <div class="grid-2">
            <!-- FIND CAR SECTION -->
            <div class="card">
                <h2><i class="fa-solid fa-magnifying-glass"></i> Hol a kocsim?</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Írd be a neved vagy rendszámodat a kereséshez.</p>
                
                <form method="POST">
                    <div class="input-group">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" name="search_term" placeholder="Név / Rendszám" required>
                    </div>
                    <button type="submit" name="find_car" class="btn">Keresés</button>
                </form>

                <?php if ($search_result): ?>
                    <div style="margin-top: 2rem; padding: 1rem; background: rgba(0,255,157,0.05); border: 1px solid var(--success); border-radius: 8px;">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <h3 style="color: var(--success); font-size: 1.2rem;">MEGTALÁLVA</h3>
                                <div style="margin-top: 0.5rem;">
                                    <strong><?php echo htmlspecialchars($search_result['efelhasz']); ?></strong>
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size: 2rem; font-weight:bold; color:white;">P-<?php echo htmlspecialchars($search_result['nparkhely']); ?></div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">Parkolóhely</div>
                            </div>
                        </div>
                        <div style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-muted);">
                            <i class="fa-regular fa-clock"></i> Beérkezés: <?php echo $search_result['nidotol']; ?>
                        </div>
                    </div>
                <?php
endif; ?>
            </div>

            <!-- HISTORY SECTION -->
            <div class="card">
                <h2><i class="fa-solid fa-clock-rotate-left"></i> Előzmények</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Korábbi parkolásaid listája és költségek.</p>
                
                <form method="POST">
                    <div class="input-group">
                        <i class="fa-solid fa-id-card"></i>
                        <input type="text" name="history_term" placeholder="Felhasználónév" required>
                    </div>
                    <button type="submit" name="show_history" class="btn" style="border-color: var(--secondary); color: var(--secondary);">Listázás</button>
                </form>

                <?php if ($history_result): ?>
                    <div style="margin-top: 2rem;">
                        <h4 style="margin-bottom: 1rem; color: var(--secondary);">Utolsó 5 Parkolás</h4>
                        <?php foreach ($history_result as $row): ?>
                            <div style="padding: 10px; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-weight:bold;"><?php echo date('Y.m.d', strtotime($row['nidotol'])); ?></div>
                                    <div style="font-size:0.8rem; color:var(--text-muted);"><?php echo $row['duration_mins']; ?> perc</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="color: var(--secondary);">P-<?php echo $row['nparkhely']; ?></div>
                                    <div style="font-size:0.8rem; opacity:0.7;">Befejezve</div>
                                </div>
                            </div>
                        <?php
    endforeach; ?>
                    </div>
                <?php
endif; ?>
            </div>
        </div>
        
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('hu-HU');
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
