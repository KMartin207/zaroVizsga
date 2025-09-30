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

<!-- Értékelések szekció -->
<section class="reviews-section">
    <h2>Ügyfeleink értékelései</h2>
    <div class="reviews-container">
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-name">Kovács István</div>
                <div class="review-date">2024.08.15</div>
            </div>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p class="review-text">"Végre egy olyan parkolórendszer, ami tényleg működik! A mobilapp zseniális, soha többé nem kell körözni a parkolóban."</p>
        </div>
        
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-name">Nagy Eszter</div>
                <div class="review-date">2024.07.22</div>
            </div>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
            </div>
            <p class="review-text">"Az automatikus beléptetés hihetetlenül kényelmes. Csak beérkezem és a rendszer felismeri a rendszámomat. Csak ajánlani tudom!"</p>
        </div>
        
        <div class="review-card">
            <div class="review-header">
                <div class="reviewer-name">Tóth Gábor</div>
                <div class="review-date">2024.09.03</div>
            </div>
            <div class="stars">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            <p class="review-text">"A valós idejű helyfoglaltság információ nélkülözhetetlen a városi parkoláshoz. Megkönnyítette a mindennapjaimat!"</p>
        </div>
    </div>
</section>

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