<?php
$host = "localhost";
$user = "lbzhvkxw_martin";
$pass = "ProParkingDatabase";
$db = "lbzhvkxw_parking_db";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Parkolóhely mentése
if (isset($_POST['save'])) {
    $felhasz = $_POST['felhasz'];
    $parkhely = $_POST['place'];

    if ($felhasz && $parkhely) {
        // 1. Ellenőrizzük, hogy a felhasználó létezik-e
        $stmt = $conn->prepare("SELECT eid FROM ember WHERE efelhasz = ?");
        $stmt->bind_param("s", $felhasz);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $eid = $user['eid'];

            // 2. Ellenőrizzük, hogy van-e aktív kártyája a felhasználónak
            $stmt = $conn->prepare("SELECT ke.keid FROM kartyaember ke 
                                   JOIN kartya k ON ke.kid = k.kid 
                                   WHERE ke.eid = ? AND k.kallapot = 1");
            $stmt->bind_param("i", $eid);
            $stmt->execute();
            $card_result = $stmt->get_result();

            if ($card_result->num_rows > 0) {
                $card = $card_result->fetch_assoc();
                $keid = $card['keid'];

                // 3. Ellenőrizzük, hogy a felhasználó jelenleg bent parkol-e (nincs nidoig értéke)
                $stmt = $conn->prepare("SELECT nid FROM naplo WHERE keid = ? AND nidoig IS NULL");
                $stmt->bind_param("i", $keid);
                $stmt->execute();
                $parking_result = $stmt->get_result();

                if ($parking_result->num_rows > 0) {
                    $parking = $parking_result->fetch_assoc();
                    $nid = $parking['nid'];

                    // 4. Ellenőrizzük, hogy ennek a parkolásnak már van-e parkolóhelye
                    $stmt = $conn->prepare("SELECT nparkhely FROM naplo WHERE nid = ? AND nparkhely IS NOT NULL");
                    $stmt->bind_param("i", $nid);
                    $stmt->execute();
                    $existing_spot_result = $stmt->get_result();

                    if ($existing_spot_result->num_rows > 0) {
                        $error = "Ez a felhasználó már rendelkezik parkolóhellyel!";
                    }
                    else {
                        // 5. Ellenőrizzük, hogy a parkolóhely szabad-e
                        $stmt = $conn->prepare("SELECT nid FROM naplo WHERE nparkhely = ? AND nidoig IS NULL");
                        $stmt->bind_param("i", $parkhely);
                        $stmt->execute();
                        $spot_result = $stmt->get_result();

                        if ($spot_result->num_rows > 0) {
                            $error = "Ez a parkolóhely már foglalt!";
                        }
                        else {
                            // 6. Parkolóhely mentése
                            $stmt = $conn->prepare("UPDATE naplo SET nparkhely = ? WHERE nid = ?");
                            $stmt->bind_param("ii", $parkhely, $nid);

                            if ($stmt->execute()) {
                                $success = "Parkolóhely sikeresen elmentve!";
                            }
                            else {
                                $error = "Hiba történt a mentés során: " . $conn->error;
                            }
                        }
                    }
                }
                else {
                    $error = "Ez a felhasználó jelenleg nincs bent a parkolóban!";
                }
            }
            else {
                $error = "A felhasználónak nincs aktív kártyája!";
            }
        }
        else {
            $error = "Nem található ilyen felhasználó!";
        }
    }
    else {
        $error = "Kérjük, töltse ki mindkét mezőt!";
    }
}

// Felhasználónév elmentése kártyaazonosító alapján
if (isset($_POST['usersave'])) {
    $kazonosito = $_POST['kazonosito'];
    $felhasz = $_POST['felhasz'];

    if ($kazonosito && $felhasz) {
        // 1. Ellenőrizzük, hogy a kártyaazonosító létezik-e
        $stmt = $conn->prepare("SELECT k.kid, ke.eid, e.enev 
                               FROM kartya k 
                               LEFT JOIN kartyaember ke ON k.kid = ke.kid 
                               LEFT JOIN ember e ON ke.eid = e.eid 
                               WHERE k.kazonosito = ? AND k.kallapot = 1");
        $stmt->bind_param("s", $kazonosito);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $card_data = $result->fetch_assoc();
            $kid = $card_data['kid'];
            $eid = $card_data['eid'];
            $current_name = $card_data['enev'];

            // 2. Ellenőrizzük, hogy a felhasználónév már létezik-e
            $stmt = $conn->prepare("SELECT eid FROM ember WHERE efelhasz = ?");
            $stmt->bind_param("s", $felhasz);
            $stmt->execute();
            $username_result = $stmt->get_result();

            if ($username_result->num_rows > 0) {
                $error = "Ez a felhasználónév már foglalt!";
            }
            else {
                // 3. Ellenőrizzük, hogy a kártyához már tartozik-e felhasználónév
                if (!empty($card_data['efelhasz'])) {
                    $error = "Ehhez a kártyához már tartozik felhasználónév!";
                }
                else {
                    // 4. Frissítjük a felhasználónevet
                    $stmt = $conn->prepare("UPDATE ember SET efelhasz = ? WHERE eid = ?");
                    $stmt->bind_param("si", $felhasz, $eid);

                    if ($stmt->execute()) {
                        $success = "Felhasználónév sikeresen elmentve!<br>Kártya: $kazonosito<br>Felhasználó: $current_name<br>Felhasználónév: $felhasz";
                    }
                    else {
                        $error = "Hiba történt a mentés során: " . $conn->error;
                    }
                }
            }
        }
        else {
            $error = "Nem található ilyen kártyaazonosító, vagy a kártya nem aktív!";
        }
    }
    else {
        $error = "Kérjük, töltse ki mindkét mezőt!";
    }
}

// Aktív parkolások lekérése (információ céljából)
function getActiveParkings()
{
    global $conn;
    $query = "SELECT e.efelhasz, n.nparkhely, n.nidotol 
              FROM naplo n 
              JOIN kartyaember ke ON n.keid = ke.keid 
              JOIN ember e ON ke.eid = e.eid 
              WHERE n.nidoig IS NULL AND n.nparkhely IS NOT NULL 
              ORDER BY n.nidotol DESC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Felhasználók listája a kártyáikkal
function getUsersWithCards()
{
    global $conn;
    $query = "SELECT e.eid, e.enev, e.efelhasz, k.kazonosito 
              FROM ember e 
              JOIN kartyaember ke ON e.eid = ke.eid 
              JOIN kartya k ON ke.kid = k.kid 
              WHERE k.kallapot = 1 
              ORDER BY e.enev";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

$active_parkings = getActiveParkings();
$users_with_cards = getUsersWithCards();
?>
<?php
// Calculate stats
$total_spots = 100; // As seen in admin dashboard
$count_active = count($active_parkings);
$free_spots = max(0, $total_spots - $count_active);
$occupancy = ($count_active / $total_spots) * 100;
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Smart Parking - Weiss System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <style>
    :root {
        --primary: #00f3ff;
        --secondary: #0066cc;
        --bg-dark: #0a0a12;
        --bg-panel: rgba(16, 20, 30, 0.85);
        --text-main: #e0e6ed;
        --text-muted: #94a3b8;
        --accent: #ff0055;
        --success: #00ff9d;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Roboto', sans-serif;
        background-color: var(--bg-dark);
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(0, 243, 255, 0.1) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(255, 0, 85, 0.08) 0%, transparent 20%),
            url('https://images.unsplash.com/photo-1573348722427-f1d68195d55d?q=80&w=2940&auto=format&fit=crop'); 
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: var(--text-main);
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Overlay for readability */
    body::before {
        content: '';
        position: fixed;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 100%;
        background: rgba(10, 10, 18, 0.85);
        z-index: -1;
        backdrop-filter: blur(3px);
    }

    /* Navbar */
    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 5%;
        background: rgba(10, 10, 18, 0.6);
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(0, 243, 255, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .brand i {
        font-size: 2rem;
        color: var(--primary);
        text-shadow: 0 0 15px var(--primary);
    }

    .brand h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.8rem;
        letter-spacing: 2px;
        background: linear-gradient(90deg, #fff, var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-transform: uppercase;
    }

    .nav-links {
        display: flex;
        gap: 1rem;
    }

    .nav-btn {
        text-decoration: none;
        color: var(--text-main);
        padding: 0.8rem 1.5rem;
        border-radius: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
        border: 1px solid transparent;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-btn::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(0, 243, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .nav-btn:hover::before {
        left: 100%;
    }

    .nav-btn.active, .nav-btn:hover {
        background: rgba(0, 243, 255, 0.1);
        border-color: var(--primary);
        box-shadow: 0 0 15px rgba(0, 243, 255, 0.2);
        color: var(--primary);
    }

    /* Stats Bar */
    .stats-bar {
        display: flex;
        justify-content: space-around;
        padding: 1rem 5%;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stat-item {
        background: var(--bg-panel);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 1rem 2rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 200px;
        backdrop-filter: blur(10px);
    }

    .stat-icon {
        font-size: 1.5rem;
        color: var(--primary);
    }

    .stat-info span {
        display: block;
        font-size: 0.8rem;
        color: var(--text-muted);
        text-transform: uppercase;
    }

    .stat-info strong {
        font-size: 1.2rem;
        font-family: 'Orbitron', sans-serif;
    }

    /* Main Container */
    .container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .main-card {
        background: var(--bg-panel);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    .main-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary), transparent);
    }

    .card-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .card-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .card-header p {
        color: var(--text-muted);
    }

    /* Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
        font-size: 0.9rem;
        letter-spacing: 1px;
    }

    input[type="text"],
    input[type="number"] {
        width: 100%;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
        color: #fff;
        border-radius: 6px;
        font-family: 'Orbitron', sans-serif;
        font-size: 1rem;
        transition: 0.3s;
    }

    input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 15px rgba(0, 243, 255, 0.1);
        background: rgba(0, 243, 255, 0.05);
    }

    button {
        width: 100%;
        padding: 15px;
        background: linear-gradient(90deg, rgba(0,243,255,0.8), rgba(0,243,255,0.4));
        border: 1px solid var(--primary);
        color: #000;
        font-weight: 700;
        text-transform: uppercase;
        font-family: 'Orbitron', sans-serif;
        letter-spacing: 2px;
        cursor: pointer;
        transition: 0.3s;
        border-radius: 6px;
        position: relative;
        overflow: hidden;
    }

    button:hover {
        background: var(--primary);
        box-shadow: 0 0 30px var(--primary);
        transform: translateY(-2px);
    }

    /* Recent Activity / Grid */
    .data-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .data-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1.5rem;
        border-radius: 8px;
        transition: 0.3s;
    }

    .data-card:hover {
        border-color: var(--primary);
        transform: translateY(-5px);
        background: rgba(0, 243, 255, 0.02);
    }

    .data-label {
        color: var(--text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
    }

    .data-value {
        font-size: 1.2rem;
        color: #fff;
        margin-bottom: 0.5rem;
        font-family: 'Orbitron', sans-serif;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        background: rgba(0, 255, 157, 0.1);
        color: var(--success);
        border: 1px solid var(--success);
    }

    /* Alerts */
    .alert {
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        text-align: center;
        border: 1px solid;
    }

    .alert-success {
        background: rgba(0, 255, 157, 0.1);
        border-color: var(--success);
        color: var(--success);
    }

    .alert-error {
        background: rgba(255, 0, 85, 0.1);
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar { flex-direction: column; gap: 1rem; }
        .stats-bar { flex-direction: column; }
        .stat-item { width: 100%; }
        .container { padding: 10px; }
        .main-card { padding: 1.5rem; }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="brand">
        <i class="fa-solid fa-square-parking"></i>
        <h1>Weiss<span style="color:white; font-weight:300;">Parking</span></h1>
    </div>
    <div class="nav-links">
        <a href="./?p=" class="nav-btn <?php echo(!isset($_GET['p']) || $_GET['p'] == '') ? 'active' : ''; ?>">
            <i class="fa-solid fa-square-plus"></i> Parkolás
        </a>
        <a href="./?p=getparknum" class="nav-btn <?php echo(isset($_GET['p']) && $_GET['p'] == 'getparknum') ? 'active' : ''; ?>">
            <i class="fa-solid fa-magnifying-glass"></i> Keresés
        </a>
        <a href="./?p=felhasz" class="nav-btn <?php echo(isset($_GET['p']) && $_GET['p'] == 'felhasz') ? 'active' : ''; ?>">
            <i class="fa-solid fa-user-plus"></i> Új Felhasználó
        </a>
        <a href="admin.php" class="nav-btn">
            <i class="fa-solid fa-shield-halved"></i> Admin
        </a>
    </div>
  </nav>

  <!-- Live Stats Bar -->
  <div class="stats-bar">
    <div class="stat-item">
        <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <span>Pontos Idő</span>
            <strong id="clock">00:00:00</strong>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-icon" style="color: var(--success);"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info">
            <span>Szabad Helyek</span>
            <strong><?php echo $free_spots; ?> / <?php echo $total_spots; ?></strong>
        </div>
    </div>
    <div class="stat-item">
        <div class="stat-icon" style="color: var(--accent);"><i class="fa-solid fa-car-side"></i></div>
        <div class="stat-info">
            <span>Aktív Parkolások</span>
            <strong><?php echo $count_active; ?></strong>
        </div>
    </div>
  </div>

  <div class="container">
    <?php
$p = $_GET['p'] ?? "";

// ================= MAIN PAGE: SAVE PARKING =================
if ($p === "") {
?>
      <section class="main-card">
        <div class="card-header">
            <h2>Parkolóhely Rögzítése</h2>
            <p>Jármű beérkeztetése a rendszerbe</p>
        </div>

        <?php
    if (isset($success))
        echo '<div class="alert alert-success"><i class="fa-solid fa-check"></i> ' . $success . '</div>';
    if (isset($error))
        echo '<div class="alert alert-error"><i class="fa-solid fa-triangle-exclamation"></i> ' . $error . '</div>';
?>

        <form method="post" autocomplete="off" style="max-width: 600px; margin: 0 auto;">
          <div class="form-group">
            <label><i class="fa-solid fa-user"></i> Felhasználónév</label>
            <input type="text" name="felhasz" placeholder="Írd be a felhasználónevet..." required />
          </div>
          
          <div class="form-group">
            <label><i class="fa-solid fa-hashtag"></i> Parkolóhely Száma</label>
            <input type="number" name="place" placeholder="Pl. 42" min="1" max="500" required />
          </div>
          
          <button type="submit" name="save">ADATOK MENTÉSE <i class="fa-solid fa-arrow-right"></i></button>
        </form>

        <?php if (!empty($active_parkings)): ?>
        <div style="margin-top: 3rem; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem;">
            <h3 style="text-align:center; font-family:'Orbitron'; margin-bottom:1rem; color:var(--primary);">Jelenleg Parkoló Járművek</h3>
            <div class="data-grid">
                <?php foreach ($active_parkings as $parking): ?>
                <div class="data-card">
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                        <span class="status-badge">AKTÍV</span>
                        <i class="fa-solid fa-car" style="color:var(--text-muted)"></i>
                    </div>
                    <div class="data-label">Felhasználó</div>
                    <div class="data-value"><?php echo htmlspecialchars($parking['efelhasz'] ?? 'N/A'); ?></div>
                    <div class="data-label">Parkolóhely</div>
                    <div class="data-value" style="color:var(--primary);">#<?php echo htmlspecialchars($parking['nparkhely']); ?></div>
                    <div class="data-label">Érkezés</div>
                    <div style="color:var(--text-muted); font-family:'Orbitron'; font-size:0.9rem;">
                        <?php echo date('H:i', strtotime($parking['nidotol'])); ?> <span style="font-size:0.7rem; opacity:0.6;">(<?php echo date('Y.m.d', strtotime($parking['nidotol'])); ?>)</span>
                    </div>
                </div>
                <?php
        endforeach; ?>
            </div>
        </div>
        <?php
    endif; ?>
      </section>

    <?php
}
// ================= FIND CAR =================
elseif ($p === "getparknum") {
?>
      <section class="main-card">
        <div class="card-header">
            <h2>Jármű Keresése</h2>
            <p>Parkolóhely lekérdezése név alapján</p>
        </div>

        <form method="post" autocomplete="off" style="max-width: 600px; margin: 0 auto;">
          <div class="form-group">
            <label><i class="fa-solid fa-magnifying-glass"></i> Felhasználónév</label>
            <input type="text" name="card_lookup" placeholder="Keresett név..." required />
          </div>
          <button type="submit" name="find">KERESÉS INDÍTÁSA</button>
        </form>

        <?php
    if (isset($_POST['find'])) {
        $search_user = $_POST['card_lookup'];
        // Using existing prepared statement logic from original file
        $stmt = $conn->prepare("SELECT e.efelhasz, n.nparkhely, n.nidotol, n.nidoig 
                                   FROM naplo n 
                                   JOIN kartyaember ke ON n.keid = ke.keid 
                                   JOIN ember e ON ke.eid = e.eid 
                                   WHERE e.efelhasz = ? AND n.nparkhely IS NOT NULL 
                                   ORDER BY n.nidotol DESC 
                                   LIMIT 1");
        $stmt->bind_param("s", $search_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $parking = $result->fetch_assoc();
?>
            <div class="alert alert-success" style="margin-top: 2rem; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
                <h3 style="color: var(--success); margin-bottom: 1rem;"><i class="fa-solid fa-location-dot"></i> Megtalálva!</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>Felhasználó:</div>
                    <div style="font-weight: bold;"><?php echo htmlspecialchars($parking['efelhasz']); ?></div>
                    <div>Parkolóhely:</div>
                    <div style="font-weight: bold; color: var(--success); font-size: 1.2rem;">#<?php echo htmlspecialchars($parking['nparkhely']); ?></div>
                    <div>Érkezés:</div>
                    <div><?php echo date('Y.m.d H:i', strtotime($parking['nidotol'])); ?></div>
                </div>
            </div>
        <?php
        }
        else {
            echo '<div class="alert alert-error" style="margin-top: 2rem;">Nem található aktív parkolás ehhez a felhasználóhoz!</div>';
        }
    }
?>
      </section>

    <?php
}
// ================= CREATE USER =================
elseif ($p === "felhasz") {
?>
      <section class="main-card">
        <div class="card-header">
            <h2>Felhasználó Létrehozása</h2>
            <p>Hozzárendelés kártyaazonosítóhoz</p>
        </div>

        <?php
    if (isset($success))
        echo '<div class="alert alert-success">' . $success . '</div>';
    if (isset($error))
        echo '<div class="alert alert-error">' . $error . '</div>';
?>

        <div style="background: rgba(0,243,255,0.05); padding: 1rem; border-radius: 6px; margin-bottom: 2rem; border-left: 3px solid var(--primary);">
            <i class="fa-solid fa-circle-info"></i> <strong>Infó:</strong> Minden kártyához csak egy felhasználónév tartozhat.
        </div>
        
        <form method="post" autocomplete="off" style="max-width: 600px; margin: 0 auto;">
          <div class="form-group">
            <label><i class="fa-solid fa-id-card"></i> Kártya Azonosító (RFID)</label>
            <input type="text" name="kazonosito" placeholder="Pl. 123456789" required />
          </div>
          
          <div class="form-group">
            <label><i class="fa-solid fa-user-tag"></i> Új Felhasználónév</label>
            <input type="text" name="felhasz" placeholder="Pl. kovacs.janos" required />
          </div>
          
          <button type="submit" name="usersave">FELHASZNÁLÓ RÖGZÍTÉSE</button>
        </form>

        <?php if (!empty($users_with_cards)): ?>
            <div style="margin-top: 3rem;">
                <h3 style="text-align:center; font-family:'Orbitron'; margin-bottom:1rem;">Regisztrált Tagok</h3>
                <div class="data-grid">
                    <?php foreach ($users_with_cards as $user): ?>
                    <div class="data-card">
                        <div class="data-label">Név</div>
                        <div class="data-value"><?php echo htmlspecialchars($user['enev']); ?></div>
                        <div class="data-label">Felhasználónév</div>
                        <div style="color:var(--primary); margin-bottom:0.5rem;"><?php echo htmlspecialchars($user['efelhasz'] ?? '---'); ?></div>
                        <div class="data-label">Kártya ID</div>
                        <div style="font-family:monospace; color:var(--text-muted);"><?php echo htmlspecialchars($user['kazonosito']); ?></div>
                    </div>
                    <?php
        endforeach; ?>
                </div>
            </div>
        <?php
    endif; ?>
      </section>
    <?php
}?>
  </div>

  <script>
    // Live Clock
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('hu-HU');
        document.getElementById('clock').textContent = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>
</body>
</html>