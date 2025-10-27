<?php
    require_once 'db.php';
    session_start();

    // Adatbázis inicializálása
    $database = new Database();
    $conn = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta name="google-adsense-account" content="ca-pub-3303266389157706">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Parkolórendszer bemutató</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Alap stílusok */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #ffffff;
        }

        /* Header stílusok */
        header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            text-align: center;
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 3.2rem;
            margin-bottom: 1rem;
            font-weight: 800;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        header p {
            font-size: 1.3rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Counter stílus */
        #counter-container {
            text-align: center;
            margin: 40px auto;
            padding: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px;
            max-width: 800px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        #counter-container h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        #counter {
            font-size: 3rem;
            font-weight: 800;
            color: #ffd700;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Gördülő értékelések szekció */
        .reviews-slider-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 4rem 0;
            margin: 3rem 0;
            overflow: hidden;
            position: relative;
        }

        .reviews-slider-container {
            display: flex;
            animation: slideReviews 40s linear infinite;
            gap: 2rem;
            padding: 0 2rem;
        }

        .review-slider-card {
            flex: 0 0 auto;
            width: 350px;
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            border: 1px solid #e9ecef;
        }

        .review-slider-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .review-slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .review-slider-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.2rem;
        }

        .review-slider-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .review-slider-stars {
            color: #ffd700;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .review-slider-stars i {
            margin-right: 3px;
        }

        .review-slider-text {
            color: #555;
            line-height: 1.7;
            font-style: italic;
            font-size: 1.1rem;
        }

        @keyframes slideReviews {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(calc(-350px * 8 - 2rem * 8));
            }
        }

        /* Main tartalom */
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        /* Kártya stílusok */
        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid #e9ecef;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.1), transparent);
            transition: left 0.6s;
        }

        .card:hover::before {
            left: 100%;
        }

        .card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.1);
        }

        .card-content {
            padding: 2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .card p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        /* Statikus értékelések szekció */
        .reviews-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 2rem;
            text-align: center;
        }

        .reviews-section h2 {
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #2c3e50;
            font-weight: 800;
        }

        .reviews-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
            margin-top: 2rem;
        }

        .review-card {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            text-align: left;
            border: 1px solid #e9ecef;
        }

        .review-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .reviewer-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.2rem;
        }

        .review-date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .stars {
            color: #ffd700;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .stars i {
            margin-right: 3px;
        }

        .review-text {
            color: #555;
            line-height: 1.7;
            font-style: italic;
            font-size: 1.1rem;
        }

        /* Értékelés űrlap */
        .review-form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 3rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .review-form h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #2c3e50;
            font-size: 2.2rem;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-family: inherit;
            font-size: 1rem;
            background: white;
            color: #333;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .form-group textarea {
            min-height: 140px;
            resize: vertical;
        }

        .star-rating {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
            justify-content: center;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .star-rating label:hover,
        .star-rating input:checked ~ label {
            color: #ffd700;
            transform: scale(1.2);
        }

        .submit-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border: none;
            padding: 1.2rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Gyors választó gombok */
        .quick-option-btn {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .quick-option-btn:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        /* Navigációs gombok */
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin: 3rem 0;
        }

        .nav-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }

        /* Footer stílusok */
        footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 3rem 2rem;
            margin-top: 4rem;
        }

        #footerbox {
            display: flex;
            justify-content: center;
            gap: 2.5rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        #footerbox p {
            margin: 0;
        }

        a.footerlink {
            color: #ffd700;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        a.footerlink:hover {
            color: #ffed4e;
            transform: translateY(-2px);
        }

        /* Üzenet stílusok */
        .message {
            text-align: center;
            padding: 1.5rem;
            margin: 1rem auto;
            border-radius: 12px;
            max-width: 600px;
            border: 1px solid;
            font-weight: 600;
            animation: slideIn 0.5s ease-out;
        }

        .message.success {
            background: rgba(46, 204, 113, 0.1);
            border-color: #2ecc71;
            color: #2ecc71;
        }

        .message.error {
            background: rgba(231, 76, 60, 0.1);
            border-color: #e74c3c;
            color: #e74c3c;
        }

        .message.warning {
            background: rgba(241, 196, 15, 0.1);
            border-color: #f1c40f;
            color: #f1c40f;
        }

        /* Animációk */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .card {
            animation: fadeIn 0.8s ease forwards;
        }

        .review-card {
            animation: fadeIn 0.8s ease forwards;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        
        .review-card:nth-child(1) { animation-delay: 0.2s; }
        .review-card:nth-child(2) { animation-delay: 0.3s; }
        .review-card:nth-child(3) { animation-delay: 0.4s; }

        /* Karakter számláló */
        .char-counter {
            text-align: right;
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .char-counter.warning {
            color: #f39c12;
        }

        .char-counter.error {
            color: #e74c3c;
        }

        /* Új elemek - Értékelés statisztika */
        .rating-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 150px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #3498db;
            display: block;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* Progress bar értékelésekhez */
        .rating-bars {
            max-width: 400px;
            margin: 0 auto;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 0.8rem;
        }

        .rating-star {
            width: 80px;
            color: #ffd700;
            font-weight: 600;
        }

        .rating-progress {
            flex: 1;
            height: 12px;
            background: #ecf0f1;
            border-radius: 6px;
            overflow: hidden;
            margin: 0 1rem;
        }

        .rating-fill {
            height: 100%;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            border-radius: 6px;
            transition: width 1s ease-in-out;
        }

        .rating-count {
            width: 40px;
            text-align: right;
            color: #666;
            font-weight: 600;
        }

        /* Reszponzív design */
        @media (max-width: 768px) {
            header {
                padding: 3rem 1.5rem;
            }
            
            header h1 {
                font-size: 2.5rem;
            }
            
            header p {
                font-size: 1.1rem;
            }
            
            main {
                grid-template-columns: 1fr;
                padding: 0 1.5rem 2rem;
                gap: 2rem;
            }
            
            .card-content {
                padding: 1.5rem;
            }
            
            .reviews-container {
                grid-template-columns: 1fr;
            }
            
            .review-form-container {
                padding: 2rem;
            }
            
            #footerbox {
                flex-direction: column;
                gap: 1rem;
            }
            
            #counter-container {
                margin: 20px 15px;
                padding: 2rem;
            }
            
            #counter-container h2 {
                font-size: 1.6rem;
            }
            
            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }

            .star-rating {
                gap: 0.5rem;
            }

            .star-rating label {
                font-size: 1.8rem;
            }

            .review-slider-card {
                width: 300px;
                padding: 1.5rem;
            }

            .rating-stats {
                gap: 1rem;
            }

            .stat-item {
                min-width: 120px;
                padding: 1rem;
            }

            .stat-number {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 2rem 1rem;
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            header p {
                font-size: 1rem;
            }
            
            main {
                padding: 0 1rem 1.5rem;
            }
            
            .review-card {
                padding: 1.5rem;
            }
            
            .review-form-container {
                padding: 1.5rem;
            }

            #counter {
                font-size: 2.5rem;
            }

            .review-slider-card {
                width: 280px;
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Okos Parkolórendszer Bemutató</h1>
    <p>Hatékony, modern megoldások a városi parkolás kezelésére</p>    
</header>

<div id="counter-container">
    <h2>Elégedett felhasználók: <span id="counter">0</span></h2>
</div>

<main>
    <?php
    // Parkolórendszer elemei tömbben (képek, cím, leírás)
    $parkingSystems = [
        [
            "title" => "Automatizált beléptetés",
            "description" => "Az automatikus rendszámfelismerő kamerák segítségével gyors és zökkenőmentes a belépés.",
            "image" => "https://images.unsplash.com/photo-1543465077-db45d34b88a5?auto=format&fit=crop&w=600&q=80&ixlib=rb-4.0.3"
        ],
        [
            "title" => "Mobil alkalmazás alapú parkolás",
            "description" => "Parkolóhely foglalás, fizetés és időhosszabbítás egyetlen applikációban.",
            "image" => "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80"
        ],
        [
            "title" => "Valós idejű helyfoglaltság",
            "description" => "Érzékelők és szenzorok segítségével mindig tudhatod, hol van szabad parkolóhely.",
            "image" => "https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=80"
        ],
        [
            "title" => "Energiahatékony LED világítás",
            "description" => "Csökkenti az energiafogyasztást és növeli a biztonságot a parkoló területén.",
            "image" => "https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?auto=format&fit=crop&w=600&q=80"
        ],
    ];

    foreach ($parkingSystems as $system) {
        echo '<article class="card">';
        echo '<img src="'.htmlspecialchars($system["image"]).'" alt="'.htmlspecialchars($system["title"]).' kép">';
        echo '<div class="card-content">';
        echo '<h2>'.htmlspecialchars($system["title"]).'</h2>';
        echo '<p>'.htmlspecialchars($system["description"]).'</p>';
        echo '</div>';
        echo '</article>';
    }
    ?>
</main>

<?php
// URL paraméter
$p = isset($_GET['p']) ? $_GET['p'] : "ertekelesek";

// Biztonsági funkciók
function containsCode($text) {
    $dangerousPatterns = [
        '/<script/i',
        '/<\/script>/i',
        '/<php/i',
        '/\<\?php/i',
        '/\?\>/i',
        '/onload=/i',
        '/onerror=/i',
        '/onclick=/i',
        '/javascript:/i',
        '/eval\(/i',
        '/document\./i',
        '/window\./i',
        '/alert\(/i',
        '/<iframe/i',
        '/<\/iframe>/i',
        '/<object/i',
        '/<\/object>/i',
        '/<embed/i',
        '/<\/embed>/i'
    ];
    
    foreach ($dangerousPatterns as $pattern) {
        if (preg_match($pattern, $text)) {
            return true;
        }
    }
    return false;
}

function sanitizeInput($input) {
    // HTML speciális karakterek konvertálása
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    // Túl hosszú szöveg vágása
    if (strlen($input) > 100) {
        $input = substr($input, 0, 100);
    }
    
    return trim($input);
}

function canSubmitReview() {
    if (!isset($_SESSION['last_review_time'])) {
        return true;
    }
    
    $timeDiff = time() - $_SESSION['last_review_time'];
    return $timeDiff > 600; // 10 perc = 600 másodperc
}

// Értékelés statisztikák számítása
function getRatingStats($conn) {
    $stats = [
        'total' => 0,
        'average' => 0,
        'distribution' => [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]
    ];
    
    try {
        // Összes értékelés száma
        $stmt = $conn->query("SELECT COUNT(*) as total, AVG(ecsillag) as average FROM ertekeles");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total'] = $result['total'] ?? 0;
        $stats['average'] = round($result['average'] ?? 0, 1);
        
        // Értékelés eloszlás
        $stmt = $conn->query("SELECT ecsillag, COUNT(*) as count FROM ertekeles GROUP BY ecsillag ORDER BY ecsillag DESC");
        $distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($distribution as $row) {
            $stats['distribution'][$row['ecsillag']] = $row['count'];
        }
        
    } catch(PDOException $e) {
        // Hiba esetén üres statisztikák
    }
    
    return $stats;
}

$ratingStats = getRatingStats($conn);
?>

<a name="jel"></a>
<div class="nav-buttons">
<?php
    if ($p == 'ertekeles') {
        echo '<a href="./?p=ertekelesek#jel" class="nav-btn">Értékelések</a>';
    } else {
        echo '<a href="./?p=ertekeles#jel" class="nav-btn">Értékelés írása</a>';
    }
?>
</div>

<?php
// Értékelés beküldése - JAVÍTOTT RÉSZ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    
    // 10 perces korlátozás ellenőrzése - JAVÍTOTT
    if (!canSubmitReview()) {
        $remainingTime = 600 - (time() - $_SESSION['last_review_time']);
        $remainingMinutes = ceil($remainingTime / 60);
        echo "<div class='message warning'>";
        echo "⏰ Csak 10 percenként küldhetsz értékelést. Várj még <strong>" . $remainingMinutes . " percet</strong>.";
        echo "</div>";
    } else {
        // Adatok feldolgozása
        $nev = sanitizeInput($_POST['nev']);
        $csillag = intval($_POST['csillag']);
        $komment = sanitizeInput($_POST['komment']);
        
        // Validáció
        $errors = [];
        
        // Név validáció
        if (empty($nev)) {
            $errors[] = "A név megadása kötelező.";
        } elseif (strlen($nev) < 2) {
            $errors[] = "A név túl rövid (minimum 2 karakter).";
        } elseif (strlen($nev) > 50) {
            $errors[] = "A név túl hosszú (maximum 50 karakter).";
        }
        
        // Csillag validáció
        if ($csillag < 1 || $csillag > 5) {
            $errors[] = "Az értékelés 1-5 csillag között lehet.";
        }
        
        // Komment validáció
        if (empty($komment)) {
            $errors[] = "A komment megadása kötelező.";
        } elseif (strlen($komment) < 10) {
            $errors[] = "A komment túl rövid (minimum 10 karakter).";
        } elseif (strlen($komment) > 100) {
            $errors[] = "A komment túl hosszú (maximum 100 karakter).";
        }
        
        // Kód ellenőrzése
        if (containsCode($nev) || containsCode($komment)) {
            $errors[] = "Az értékelés tartalmazhat tiltott kódokat vagy szkripteket.";
        }
        
        // Ha nincs hiba, mentés
        if (empty($errors)) {
            try {
                $stmt = $conn->prepare("INSERT INTO ertekeles (enev, ecsillag, ekomment) VALUES (:nev, :csillag, :komment)");
                $stmt->bindParam(':nev', $nev);
                $stmt->bindParam(':csillag', $csillag);
                $stmt->bindParam(':komment', $komment);
                
                if ($stmt->execute()) {
                    // Sikeres küldés időpontjának mentése - JAVÍTOTT
                    $_SESSION['last_review_time'] = time();
                    
                    echo "<div class='message success'>";
                    echo "✅ Köszönjük az értékelésed! Az üzenet sikeresen elküldve.";
                    echo "</div>";
                    
                    // Statisztikák frissítése
                    $ratingStats = getRatingStats($conn);
                } else {
                    throw new PDOException("Execute failed");
                }
                
            } catch(PDOException $e) {
                echo "<div class='message error'>";
                echo "❌ Hiba történt az értékelés mentése során. Kérjük, próbáld újra később.";
                echo "</div>";
            }
        } else {
            // Hibaüzenetek megjelenítése
            echo "<div class='message error'>";
            echo "<strong>❌ Hibák:</strong><br>";
            foreach ($errors as $error) {
                echo "• " . $error . "<br>";
            }
            echo "</div>";
        }
    }
}

// --- Oldalbetöltés logika ---
if ($p == "ertekelesek") {
    // Gördülő értékelések szekció
    echo '<section class="reviews-slider-section">';
    echo '<div class="reviews-slider-container" id="reviewsSlider">';
    try {
        $stmt = $conn->query("SELECT * FROM ertekeles ORDER BY RAND() LIMIT 10");
        $ertekelesek = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($ertekelesek) {
            // Első kör
            foreach ($ertekelesek as $ertekeles) {
                echo '<div class="review-slider-card">';
                echo '<div class="review-slider-header">';
                echo '<div class="review-slider-name">' . htmlspecialchars($ertekeles['enev']) . '</div>';
                echo '<div class="review-slider-date">' . date('Y.m.d', strtotime($ertekeles['edatum'])) . '</div>';
                echo '</div><div class="review-slider-stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $ertekeles['ecsillag'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }
                echo '</div><p class="review-slider-text">"' . htmlspecialchars($ertekeles['ekomment']) . '"</p>';
                echo '</div>';
            }
            // Második kör (duplikálva a folyamatos loop-hoz)
            foreach ($ertekelesek as $ertekeles) {
                echo '<div class="review-slider-card">';
                echo '<div class="review-slider-header">';
                echo '<div class="review-slider-name">' . htmlspecialchars($ertekeles['enev']) . '</div>';
                echo '<div class="review-slider-date">' . date('Y.m.d', strtotime($ertekeles['edatum'])) . '</div>';
                echo '</div><div class="review-slider-stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $ertekeles['ecsillag'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }
                echo '</div><p class="review-slider-text">"' . htmlspecialchars($ertekeles['ekomment']) . '"</p>';
                echo '</div>';
            }
        } else {
            echo '<div class="review-slider-card" style="text-align: center;">';
            echo '<p>Még nincsenek értékelések. Legyél te az első! 🌟</p>';
            echo '</div>';
        }
    } catch(PDOException $e) {
        echo '<div class="review-slider-card" style="text-align: center;">';
        echo '<p>Nem sikerült betölteni az értékeléseket.</p>';
        echo '</div>';
    }
    echo '</div></section>';

    // Statisztikák szekció
    echo '<section class="reviews-section">';
    echo '<h2>📊 Értékelés Statisztikák</h2>';
    
    echo '<div class="rating-stats">';
    echo '<div class="stat-item">';
    echo '<span class="stat-number">' . $ratingStats['total'] . '</span>';
    echo '<span class="stat-label">Összes értékelés</span>';
    echo '</div>';
    
    echo '<div class="stat-item">';
    echo '<span class="stat-number">' . $ratingStats['average'] . '</span>';
    echo '<span class="stat-label">Átlagos értékelés</span>';
    echo '</div>';
    
    echo '<div class="stat-item">';
    echo '<span class="stat-number">' . ($ratingStats['total'] > 0 ? round(($ratingStats['distribution'][5] / $ratingStats['total']) * 100) : 0) . '%</span>';
    echo '<span class="stat-label">5 csillagos</span>';
    echo '</div>';
    echo '</div>';

    // Értékelés eloszlás
    if ($ratingStats['total'] > 0) {
        echo '<div class="rating-bars">';
        for ($i = 5; $i >= 1; $i--) {
            $percentage = $ratingStats['total'] > 0 ? ($ratingStats['distribution'][$i] / $ratingStats['total']) * 100 : 0;
            echo '<div class="rating-bar">';
            echo '<div class="rating-star">' . $i . ' ⭐</div>';
            echo '<div class="rating-progress">';
            echo '<div class="rating-fill" style="width: ' . $percentage . '%"></div>';
            echo '</div>';
            echo '<div class="rating-count">' . $ratingStats['distribution'][$i] . '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    
    
} elseif ($p == "ertekeles") {
    echo '<section class="reviews-section"><div class="review-form-container">';
    echo '<form class="review-form" method="POST" action="" id="reviewForm">';
    echo '<h2>✍️ Értékelés írása</h2>';
    
    // Gyors választó gombok - JAVÍTOTT (csillagok sorrendje)
    echo '<div class="form-group">';
    echo '<label>Gyors választás:</label>';
    echo '<div style="display: flex; gap: 0.5rem; margin-bottom: 1rem; flex-wrap: wrap;">';
    $quickOptions = [
        'Nem ajánlom' => 1,
        'Van hová fejlődni' => 2,
        'Megfelelő' => 3,
        'Nagyon elégedett vagyok' => 4,
        'Kiváló szolgáltatás!' => 5
        
    ];
    foreach ($quickOptions as $text => $stars) {
        echo '<button type="button" class="quick-option-btn" data-stars="' . $stars . '" data-text="' . $text . '">' . $stars . ' ⭐</button>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="nev">Név:</label>';
    echo '<input type="text" id="nev" name="nev" required maxlength="50" placeholder="Add meg a neved">';
    echo '<div class="char-counter"><span id="nameCounter">0</span>/50</div>';
    echo '</div>';
    echo '<div class="form-group"><label>Értékelés (csillagok):</label><div class="star-rating">';
    for ($i = 1; $i <= 5; $i++) {
        echo '<input type="radio" id="star' . $i . '" name="csillag" value="' . $i . '" required>';
        echo '<label for="star' . $i . '"><i class="fas fa-star"></i></label>';
    }
    echo '</div></div>';
    echo '<div class="form-group">';
    echo '<label for="komment">Értékelés szövege:</label>';
    echo '<textarea id="komment" name="komment" required maxlength="100" placeholder="Írd le véleményed (minimum 10 karakter)"></textarea>';
    echo '<div class="char-counter"><span id="commentCounter">0</span>/100</div>';
    echo '</div>';
    echo '<button type="submit" name="submit_review" class="submit-btn">🚀 Értékelés elküldése</button>';
    echo '</form></div></section>';
}
?>

<footer>
    <div id="footerbox">
        <p><a href="https://instagram.com" class="footerlink">📷 Instagram</a></p>
        <p><a href="https://facebook.com" class="footerlink">👥 Facebook</a></p>
        <p><a href="https://x.com" class="footerlink">🐦 X</a></p>
        <p>📞 Telefonszám: +36 31 568 7542</p>
    </div>
    &copy; 2025 Parkolórendszer bemutató | Minden jog fenntartva.
</footer>

<script>
    let counter = 0;
    const target = 1056143;
    const speed = 0.0000001;

    function updateCounter() {
        const counterElement = document.getElementById('counter');
        if (counter < target) {
            counter = counter + 5023;
            counterElement.textContent = counter.toLocaleString('hu-HU');
            setTimeout(updateCounter, speed);
        }
    }

    // Karakter számláló
    function setupCharCounters() {
        const nameInput = document.getElementById('nev');
        const commentInput = document.getElementById('komment');
        const nameCounter = document.getElementById('nameCounter');
        const commentCounter = document.getElementById('commentCounter');

        if (nameInput && nameCounter) {
            nameInput.addEventListener('input', function() {
                const count = this.value.length;
                nameCounter.textContent = count;
                nameCounter.className = 'char-counter' + (count > 45 ? ' warning' : '');
            });
        }

        if (commentInput && commentCounter) {
            commentInput.addEventListener('input', function() {
                const count = this.value.length;
                commentCounter.textContent = count;
                commentCounter.className = 'char-counter' + 
                    (count > 450 ? ' error' : count > 400 ? ' warning' : '');
            });
        }
    }

    // Csillag animáció
    function setupStarAnimation() {
        const stars = document.querySelectorAll('.star-rating label');
        stars.forEach((star, index) => {
            star.style.animationDelay = (index * 0.1) + 's';
        });
    }

    // Gördülő értékelések pause on hover
    function setupReviewsSlider() {
        const slider = document.getElementById('reviewsSlider');
        if (slider) {
            slider.addEventListener('mouseenter', () => {
                slider.style.animationPlayState = 'paused';
            });
            slider.addEventListener('mouseleave', () => {
                slider.style.animationPlayState = 'running';
            });
        }
    }

    // Gyors választó gombok
    function setupQuickOptions() {
        const quickButtons = document.querySelectorAll('.quick-option-btn');
        quickButtons.forEach(button => {
            button.addEventListener('click', function() {
                const stars = this.getAttribute('data-stars');
                const text = this.getAttribute('data-text');
                
                // Csillagok beállítása
                const starInput = document.querySelector(`#star${stars}`);
                if (starInput) {
                    starInput.checked = true;
                }
                
                // Szöveg beállítása
                const commentTextarea = document.getElementById('komment');
                if (commentTextarea) {
                    commentTextarea.value = text;
                    
                    // Karakter számláló frissítése
                    const commentCounter = document.getElementById('commentCounter');
                    if (commentCounter) {
                        commentCounter.textContent = text.length;
                        commentCounter.className = 'char-counter' + 
                            (text.length > 450 ? ' error' : text.length > 400 ? ' warning' : '');
                    }
                }
                
                // Gomb kiemelése
                quickButtons.forEach(btn => {
                    btn.style.background = '#f8f9fa';
                    btn.style.color = '#333';
                });
                this.style.background = 'linear-gradient(135deg, #2c3e50 0%, #3498db 100%)';
                this.style.color = 'white';
            });
        });
    }

    // Progress bar animáció
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.rating-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }

    // Indítás oldalbetöltéskor
    window.addEventListener('DOMContentLoaded', function() {
        updateCounter();
        setupCharCounters();
        setupStarAnimation();
        setupReviewsSlider();
        setupQuickOptions();
        animateProgressBars();
    });
</script>

</body>
</html>