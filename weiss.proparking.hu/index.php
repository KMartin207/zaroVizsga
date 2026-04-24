<?php
// .htaccess-like behavior for routing /admin:
// If the user visits 'weiss.proparking.hu/index.php?p=admin' or similar, we can redirect.
// Best approach: Add a redirect at the top of index.php

$host = "localhost";
$user = "lbzhvkxw_martin";
$pass = "ProParkingDatabase";
$db = "lbzhvkxw_parking_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// --- LOGIC: SAVE PARKING ---
if (isset($_POST['save'])) {
    $felhasz = trim($_POST['felhasz']);
    $parkhely = intval($_POST['place']);

    if ($felhasz && $parkhely) {
        $stmt = $conn->prepare("SELECT eid FROM ember WHERE efelhasz = ?");
        $stmt->bind_param("s", $felhasz);
        $stmt->execute();
        $resUser = $stmt->get_result();

        if ($resUser->num_rows > 0) {
            $userData = $resUser->fetch_assoc();
            $eid = $userData['eid'];

            $stmt = $conn->prepare("SELECT ke.keid FROM kartyaember ke JOIN kartya k ON ke.kid = k.kid WHERE ke.eid = ? AND k.kallapot = 1");
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            $cardRes = $stmt->get_result();

            if ($cardRes->num_rows > 0) {
                $cardData = $cardRes->fetch_assoc();
                $keid = $cardData['keid'];

                $stmt = $conn->prepare("SELECT nid FROM naplo WHERE keid = ? AND nidoig IS NULL");
                $stmt->bind_param("i", $keid);
                $stmt->execute();
                $sessionRes = $stmt->get_result();

                if ($sessionRes->num_rows > 0) {
                    $sess = $sessionRes->fetch_assoc();
                    $nid = $sess['nid'];

                    // Check duplicate spot
                    $stmt = $conn->prepare("SELECT nparkhely FROM naplo WHERE nid = ? AND nparkhely IS NOT NULL");
                    $stmt->bind_param("i", $nid);
                    $stmt->execute();
                    if ($stmt->get_result()->num_rows > 0) {
                        $error = "Már van parkolóhelye!";
                    }
                    else {
                        // Check if spot taken
                        $stmt = $conn->prepare("SELECT nid FROM naplo WHERE nparkhely = ? AND nidoig IS NULL");
                        $stmt->bind_param("i", $parkhely);
                        $stmt->execute();
                        if ($stmt->get_result()->num_rows > 0) {
                            $error = "A $parkhely. hely már foglalt!";
                        }
                        else {
                            $stmt = $conn->prepare("UPDATE naplo SET nparkhely = ? WHERE nid = ?");
                            $stmt->bind_param("ii", $parkhely, $nid);
                            if ($stmt->execute()) {
                                $success = "Hely rögzítve: #$parkhely";
                            }
                            else {
                                $error = "Hiba: " . $conn->error;
                            }
                        }
                    }
                }
                else {
                    $error = "Nincs aktív behajtása!";
                }
            }
            else {
                $error = "Nincs aktív kártyája!";
            }
        }
        else {
            $error = "Ismeretlen felhasználó!";
        }
    }
    else {
        $error = "Hiányzó adatok!";
    }
}

// --- LOGIC: REGISTER USER ---
if (isset($_POST['usersave'])) {
    $kazonosito = trim($_POST['kazonosito']);
    $felhasz = trim($_POST['felhasz']);

    if ($kazonosito && $felhasz) {
        $stmt = $conn->prepare("SELECT k.kid, ke.eid FROM kartya k LEFT JOIN kartyaember ke ON k.kid = ke.kid WHERE k.kazonosito = ? AND k.kallapot = 1");
        $stmt->bind_param("s", $kazonosito);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $data = $res->fetch_assoc();
            $eid = $data['eid'];

            $stmt = $conn->prepare("SELECT eid FROM ember WHERE efelhasz = ?");
            $stmt->bind_param("s", $felhasz);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error_reg = "Név már foglalt!";
            }
            else {
                $stmt = $conn->prepare("UPDATE ember SET efelhasz = ? WHERE eid = ?");
                $stmt->bind_param("si", $felhasz, $eid);
                if ($stmt->execute())
                    $success_reg = "Felhasználó mentve!";
                else
                    $error_reg = "Hiba: " . $conn->error;
            }
        }
        else {
            $error_reg = "Érvénytelen kártya!";
        }
    }
    else {
        $error_reg = "Hiányzó adatok!";
    }
}

// --- LOGIC: SEARCH ---
if (isset($_POST['search'])) {
    $sName = trim($_POST['search_name']);
    $stmt = $conn->prepare("SELECT n.nparkhely, n.nidotol FROM naplo n JOIN kartyaember ke ON n.keid = ke.keid JOIN ember e ON ke.eid = e.eid WHERE e.efelhasz = ? AND n.nidoig IS NULL AND n.nparkhely IS NOT NULL");
    $stmt->bind_param("s", $sName);
    $stmt->execute();
    $sRes = $stmt->get_result();
    if ($sRes->num_rows > 0) {
        $found = $sRes->fetch_assoc();
    }
    else {
        $sError = "Nem találtam parkoló autót ezen a néven.";
    }
}

// --- STATS ---
$active = $conn->query("SELECT nid FROM naplo WHERE nidoig IS NULL AND nparkhely IS NOT NULL")->num_rows;
$free = max(0, 100 - $active);
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Weiss Parking</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb; 
            --secondary: #0f172a;
            --weiss-green: #15803d;
            --bg-light: #f1f5f9;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-light); 
            margin: 0; 
            padding: 0; 
            color: #334155; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* HEADER - Fully Responsive */
        header { 
            background: white; 
            border-bottom: 4px solid var(--weiss-green); 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        }
        
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0.75rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        @media (min-width: 768px) {
            .nav-container {
                padding: 0.75rem 2rem;
                flex-direction: row;
                align-items: center;
                gap: 3rem;
            }
        }
        
        .brand { 
            font-size: 1.75rem;
            font-weight: 800; 
            color: #000000;
            margin: 0;
            text-align: center;
        }
        
        @media (min-width: 768px) {
            .brand {
                font-size: 2rem;
                text-align: left;
            }
        }
        
        .brand span { 
            color: #2563eb;
        }
        
        .nav-menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
        }
        
        @media (min-width: 768px) {
            .nav-menu {
                flex-grow: 1;
                justify-content: flex-start;
                gap: 1rem;
            }
        }
        
        .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            white-space: nowrap;
        }
        
        @media (min-width: 480px) {
            .nav-link {
                padding: 0.5rem 1.25rem;
                font-size: 1rem;
            }
        }
        
        .nav-link i {
            font-size: 0.9rem;
        }
        
        @media (min-width: 768px) {
            .nav-link i {
                font-size: 1rem;
            }
        }
        
        .nav-link:hover { 
            color: var(--primary); 
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        .nav-link.active { 
            background: var(--primary); 
            color: white; 
        }

        /* Container */
        .container { 
            max-width: 800px; 
            margin: 1rem auto; 
            padding: 0 1rem; 
            width: 100%;
            flex: 1;
        }
        
        @media (min-width: 640px) {
            .container {
                margin: 2rem auto;
                padding: 0 1.5rem;
            }
        }

        /* CARD - Responsive */
        .card { 
            background: white; 
            padding: 1.5rem;
            border-radius: 16px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
            text-align: center; 
            width: 100%;
        }
        
        @media (min-width: 640px) {
            .card {
                padding: 2.5rem;
            }
        }
        
        @media (min-width: 768px) {
            .card {
                padding: 3rem;
            }
        }
        
        h2 { 
            margin-bottom: 1.5rem; 
            color: var(--secondary); 
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        @media (min-width: 640px) {
            h2 {
                font-size: 1.8rem;
            }
        }
        
        h2 i {
            font-size: 1.3rem;
        }
        
        @media (min-width: 640px) {
            h2 i {
                font-size: 1.6rem;
            }
        }
        
        /* Forms - Responsive */
        input { 
            width: 100%; 
            padding: 0.875rem;
            margin-bottom: 1rem; 
            border: 2px solid #e2e8f0; 
            border-radius: 12px; 
            font-size: 1rem;
            text-align: center; 
            transition: all 0.3s ease;
            -webkit-appearance: none;
        }
        
        @media (min-width: 640px) {
            input {
                padding: 1rem;
                margin-bottom: 1.5rem;
                font-size: 1.1rem;
            }
        }
        
        input:focus { 
            border-color: var(--primary); 
            outline: none; 
            background: #eff6ff; 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        button {
            width: 100%; 
            padding: 0.875rem;
            background: var(--secondary); 
            color: white; 
            border: none; 
            border-radius: 12px;
            font-weight: 700; 
            font-size: 1rem;
            cursor: pointer; 
            transition: all 0.3s ease;
            -webkit-appearance: none;
        }
        
        @media (min-width: 640px) {
            button {
                padding: 1rem;
                font-size: 1.1rem;
            }
        }
        
        button:hover { 
            background: var(--primary); 
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }
        
        button:active {
            transform: translateY(0);
        }

        /* Alerts - Responsive */
        .alert { 
            padding: 0.875rem;
            border-radius: 8px; 
            margin-bottom: 1.5rem; 
            font-weight: 600; 
            font-size: 0.95rem;
            word-break: break-word;
        }
        
        @media (min-width: 640px) {
            .alert {
                padding: 1rem;
                font-size: 1rem;
            }
        }
        
        .success { 
            background: #dcfce7; 
            color: #15803d; 
        }
        .error { 
            background: #fee2e2; 
            color: #991b1b; 
        }

        /* SEARCH RESULT - Responsive */
        .result-box { 
            margin-top: 1.5rem; 
            padding: 1.5rem;
            background: #eff6ff; 
            border-radius: 12px; 
            border: 2px solid var(--primary); 
        }
        
        @media (min-width: 640px) {
            .result-box {
                margin-top: 2rem;
                padding: 2rem;
            }
        }
        
        .big-number { 
            font-size: 2.5rem;
            font-weight: 800; 
            color: var(--primary); 
            margin: 0.5rem 0; 
        }
        
        @media (min-width: 640px) {
            .big-number {
                font-size: 3.5rem;
            }
        }

        /* STATS - Responsive */
        .stats-bar { 
            display: flex; 
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 700; 
            color: #64748b;
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        @media (min-width: 480px) {
            .stats-bar {
                flex-direction: row;
                justify-content: center;
                gap: 2rem;
                margin-bottom: 2rem;
                padding: 1rem 2rem;
            }
        }
        
        @media (min-width: 768px) {
            .stats-bar {
                gap: 3rem;
                margin-bottom: 3rem;
            }
        }
        
        .stat-val { 
            font-size: 1.25rem;
            color: var(--weiss-green); 
            margin-left: 0.5rem;
            font-weight: 800;
        }
        
        @media (min-width: 640px) {
            .stat-val {
                font-size: 1.5rem;
            }
        }

        /* Touch-friendly improvements */
        @media (max-width: 768px) {
            .nav-link, button {
                min-height: 44px; /* Minimum touch target size */
            }
            
            input, button {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }

        /* Landscape mode optimization */
        @media (max-height: 600px) and (orientation: landscape) {
            .container {
                margin: 0.5rem auto;
            }
            
            .card {
                padding: 1rem;
            }
            
            h2 {
                margin-bottom: 0.75rem;
                font-size: 1.3rem;
            }
            
            input, button {
                padding: 0.5rem;
                margin-bottom: 0.5rem;
            }
            
            .stats-bar {
                margin-bottom: 0.75rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="nav-container">
            <div class="brand">Pro<span>parking.</span></div>
            <div class="nav-menu">
                <a href="?p=park" class="nav-link <?php echo(!isset($_GET['p']) || $_GET['p'] == 'park') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-square-parking"></i> 
                    <span class="nav-text">Helyfoglalás</span>
                </a>
                <a href="?p=search" class="nav-link <?php echo(isset($_GET['p']) && $_GET['p'] == 'search') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-magnifying-glass"></i> 
                    <span class="nav-text">Keresés</span>
                </a>
                <a href="?p=register" class="nav-link <?php echo(isset($_GET['p']) && $_GET['p'] == 'register') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-user-plus"></i> 
                    <span class="nav-text">Regisztráció</span>
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Stats -->
        <div class="stats-bar">
            <div>Szabad Hely: <span class="stat-val"><?php echo $free; ?></span></div>
            <div>Idő: <span class="stat-val" id="clock">00:00</span></div>
        </div>

        <!-- 1. PARKOLÁS (Default) -->
        <?php if (!isset($_GET['p']) || $_GET['p'] == 'park'): ?>
            <div class="card">
                <h2><i class="fa-solid fa-square-parking"></i> Parkolóhely Mentése</h2>
                <?php if (isset($success))
        echo "<div class='alert success'>$success</div>"; ?>
                <?php if (isset($error))
        echo "<div class='alert error'>$error</div>"; ?>
                
                <form method="POST">
                    <input type="text" name="felhasz" required placeholder="Felhasználónév (pl. kovacs.bela)">
                    <input type="number" name="place" required placeholder="Parkolóhely Száma" min="1" max="100">
                    <button type="submit" name="save">Adatok Mentése</button>
                </form>
            </div>
        
        <!-- 2. KERESÉS -->
        <?php
elseif ($_GET['p'] == 'search'): ?>
            <div class="card">
                <h2><i class="fa-solid fa-magnifying-glass"></i> Hol Parkol?</h2>
                <?php if (isset($sError))
        echo "<div class='alert error'>$sError</div>"; ?>
                
                <form method="POST">
                    <input type="text" name="search_name" required placeholder="Keresett Felhasználónév">
                    <button type="submit" name="search" style="background:var(--primary);">Keresés Indítása</button>
                </form>

                <?php if (isset($found)): ?>
                    <div class="result-box">
                        <div>A parkolóhely száma:</div>
                        <div class="big-number">#<?php echo $found['nparkhely']; ?></div>
                        <div>Beérkezés: <?php echo date('H:i', strtotime($found['nidotol'])); ?></div>
                    </div>
                <?php
    endif; ?>
            </div>

        <!-- 3. REGISZTRÁCIÓ -->
        <?php
elseif ($_GET['p'] == 'register'): ?>
            <div class="card">
                <h2><i class="fa-solid fa-user-plus"></i> Új Felhasználó</h2>
                <?php if (isset($success_reg))
        echo "<div class='alert success'>$success_reg</div>"; ?>
                <?php if (isset($error_reg))
        echo "<div class='alert error'>$error_reg</div>"; ?>
                
                <form method="POST">
                    <input type="text" name="kazonosito" required placeholder="Kártya Azonosító (RFID)">
                    <input type="text" name="felhasz" required placeholder="Kívánt Felhasználónév">
                    <button type="submit" name="usersave" style="background:var(--weiss-green);">Felhasználó Rögzítése</button>
                </form>
            </div>
        <?php
endif; ?>

    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('hu-HU', {hour:'2-digit', minute:'2-digit'});
            document.getElementById('clock').innerText = timeString;
        }
        
        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
        
        // Add active class to current nav item based on URL
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link');
            const currentPage = window.location.search.match(/p=([a-z]+)/)?.[1] || 'park';
            
            navLinks.forEach(link => {
                if (link.getAttribute('href').includes(`p=${currentPage}`)) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>