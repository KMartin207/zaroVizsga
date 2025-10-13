<?php
    require_once 'db.php';
?>

<!DOCTYPE html>
<html lang="hu">
<head>
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
            background-color: #f8f9fa;
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
            font-size: 2.8rem;
            margin-bottom: 1rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        header p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Counter stílus */
        #counter-container {
            text-align: center;
            margin: 40px 0;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        #counter-container h2 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        #counter {
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffd700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Main tartalom */
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem 3rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        /* Kártya stílusok */
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 0.8rem;
            color: #2c3e50;
            font-weight: 600;
        }

        .card p {
            color: #555;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        /* Értékelések szekció */
        .reviews-section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            text-align: center;
        }

        .reviews-section h2 {
            font-size: 2.2rem;
            margin-bottom: 2rem;
            color: #2c3e50;
            font-weight: 700;
        }

        .reviews-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .review-card {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            text-align: left;
        }

        .review-card:hover {
            transform: translateY(-5px);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .reviewer-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .review-date {
            color: #777;
            font-size: 0.9rem;
        }

        .stars {
            color: #ffd700;
            margin-bottom: 1rem;
        }

        .stars i {
            margin-right: 2px;
        }

        .review-text {
            color: #555;
            line-height: 1.6;
            font-style: italic;
        }

        /* Értékelés űrlap */
        .review-form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .review-form h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .star-rating {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 1.5rem;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating label:hover,
        .star-rating input:checked ~ label {
            color: #ffd700;
        }

        .submit-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Navigációs gombok */
        .nav-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .nav-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Footer stílusok */
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }

        #footerbox {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        #footerbox p {
            margin: 0;
        }

        a.footerlink {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a.footerlink:hover {
            color: #3498db;
        }

        /* Reszponzív design */
        @media (max-width: 768px) {
            header {
                padding: 3rem 1.5rem;
            }
            
            header h1 {
                font-size: 2.2rem;
            }
            
            header p {
                font-size: 1.1rem;
            }
            
            main {
                grid-template-columns: 1fr;
                padding: 0 1.5rem 2rem;
                gap: 1.5rem;
            }
            
            .card-content {
                padding: 1.2rem;
            }
            
            .reviews-container {
                grid-template-columns: 1fr;
            }
            
            .review-form-container {
                padding: 1.5rem;
            }
            
            #footerbox {
                flex-direction: column;
                gap: 1rem;
            }
            
            #counter-container {
                margin: 20px 15px;
                padding: 1.5rem;
            }
            
            #counter-container h2 {
                font-size: 1.5rem;
            }
            
            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 2rem 1rem;
            }
            
            header h1 {
                font-size: 1.8rem;
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
                padding: 1rem;
            }
        }

        /* Animációk */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeIn 0.6s ease forwards;
        }

        .review-card {
            animation: fadeIn 0.6s ease forwards;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        
        .review-card:nth-child(1) { animation-delay: 0.2s; }
        .review-card:nth-child(2) { animation-delay: 0.3s; }
        .review-card:nth-child(3) { animation-delay: 0.4s; }
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
            "image" => "kepek/parkoloteton.jpg"
        ],
        [
            "title" => "Valós idejű helyfoglaltság",
            "description" => "Érzékelők és szenzorok segítségével mindig tudhatod, hol van szabad parkolóhely.",
            "image" => "kepek/parkoloautokkal.jpg"
        ],
        [
            "title" => "Energiahatékony LED világítás",
            "description" => "Csökkenti az energiafogyasztást és növeli a biztonságot a parkoló területén.",
            "image" => "kepek/parkolokep.png"
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
?>
<a name="jel"></a>
<div class="nav-buttons">
<?php
    if ($p == 'ertekeles') {
        echo '
            <a href="./?p=ertekelesek#jel" class="nav-btn">Értékelések</a>
        ';
    }
    else {
        echo '
            <a href="./?p=ertekeles#jel" class="nav-btn">Értékelés írása</a>
        ';
    }
?>
    
    
</div>

<?php


// Adatbázis inicializálása
$database = new Database();
$conn = $database->getConnection();



// Értékelés beküldése
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $nev = trim($_POST['nev']);
    $csillag = intval($_POST['csillag']);
    $komment = trim($_POST['komment']);

    if (!empty($nev) && !empty($csillag) && !empty($komment)) {
        try {
            $stmt = $conn->prepare("INSERT INTO ertekeles (enev, ecsillag, ekomment) VALUES (:nev, :csillag, :komment)");
            $stmt->bindParam(':nev', $nev);
            $stmt->bindParam(':csillag', $csillag);
            $stmt->bindParam(':komment', $komment);
            $stmt->execute();
            echo "<div style='text-align:center; color:green; padding:1rem;'>Köszönjük az értékelésed!</div>";
        } catch(PDOException $e) {
            echo "<div style='text-align:center; color:red; padding:1rem;'>Hiba: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div style='text-align:center; color:red; padding:1rem;'>Kérlek, töltsd ki az összes mezőt!</div>";
    }
}

// --- Oldalbetöltés logika ---
if ($p == "ertekelesek") {
    echo '<section class="reviews-section">';
    echo '<h2>Ügyfeleink értékelései</h2>';
    echo '<div class="reviews-container">';
    try {
        $stmt = $conn->query("SELECT * FROM ertekeles ORDER BY RAND() LIMIT 3");
        $ertekelesek = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($ertekelesek) {
            foreach ($ertekelesek as $ertekeles) {
                echo '<div class="review-card">';
                echo '<div class="review-header">';
                echo '<div class="reviewer-name">' . htmlspecialchars($ertekeles['enev']) . '</div>';
                echo '<div class="review-date">' . date('Y.m.d', strtotime($ertekeles['edatum'])) . '</div>';
                echo '</div><div class="stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $ertekeles['ecsillag'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }
                echo '</div><p class="review-text">"' . htmlspecialchars($ertekeles['ekomment']) . '"</p>';
                echo '</div>';
            }
        } else {
            echo '<p>Még nincsenek értékelések. Legyél te az első!</p>';
        }
    } catch(PDOException $e) {
        echo '<p>Nem sikerült betölteni az értékeléseket.</p>';
    }
    echo '</div></section>';
} elseif ($p == "ertekeles") {
    echo '<section class="reviews-section"><div class="review-form-container">';
    echo '<form class="review-form" method="POST" action="">';
    echo '<h2>Értékelés írása</h2>';
    echo '<div class="form-group"><label for="nev">Név:</label><input type="text" id="nev" name="nev" required></div>';
    echo '<div class="form-group"><label>Értékelés (csillagok):</label><div class="star-rating">';
    for ($i = 5; $i >= 1; $i--) {
        echo '<input type="radio" id="star' . $i . '" name="csillag" value="' . $i . '" required>';
        echo '<label for="star' . $i . '"><i class="fas fa-star"></i></label>';
    }
    echo '</div></div>';
    echo '<div class="form-group"><label for="komment">Értékelés szövege:</label><textarea id="komment" name="komment" required></textarea></div>';
    echo '<button type="submit" name="submit_review" class="submit-btn">Értékelés elküldése</button>';
    echo '</form></div></section>';
} else {
    // alapértelmezett rész: véletlen értékelések
    echo '<section class="reviews-section">';
    echo '<h2>Ügyfeleink értékelései</h2>';
    echo '<div class="reviews-container">';
    try {
        $stmt = $conn->query("SELECT * FROM ertekeles ORDER BY RAND() LIMIT 3");
        $ertekelesek = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($ertekelesek) {
            foreach ($ertekelesek as $ertekeles) {
                echo '<div class="review-card">';
                echo '<div class="review-header">';
                echo '<div class="reviewer-name">' . htmlspecialchars($ertekeles['enev']) . '</div>';
                echo '<div class="review-date">' . date('Y.m.d', strtotime($ertekeles['edatum'])) . '</div>';
                echo '</div><div class="stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo $i <= $ertekeles['ecsillag'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                }
                echo '</div><p class="review-text">"' . htmlspecialchars($ertekeles['ekomment']) . '"</p>';
                echo '</div>';
            }
        } else {
            echo '<p>Nincsenek értékelések az adatbázisban.</p>';
        }
    } catch(PDOException $e) {
        echo '<p>Adatbázis hiba az értékelések betöltésekor.</p>';
    }
    echo '</div></section>';
}
?>


<footer>
    <div id="footerbox">
        <p><a href="https://instagram.com" class="footerlink">Instagram</a></p>
        <p><a href="https://facebook.com" class="footerlink">Facebook</a></p>
        <p><a href="https://x.com" class="footerlink">X</a></p>
        <p>Telefonszám: +36 31 568 7542</p>
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

    // Indítás oldalbetöltéskor
    window.addEventListener('DOMContentLoaded', updateCounter);
</script>

</body>
</html>