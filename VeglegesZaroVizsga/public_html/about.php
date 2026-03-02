<?php
$page = 'about';
$pageTitle = 'Rólunk - ProParking Intelligens Parkolórendszer';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="page-styles.css">
    
    <style>
        /* ===== OLDAL SPECIFIKUS STÍLUSOK ===== */
        
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
        
        /* Navigációs menü stílusok */
        .main-nav {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            border-bottom: 3px solid #3498db;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            height: 70px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            gap: 12px;
            transition: transform 0.3s ease;
        }

        .nav-logo:hover {
            transform: translateY(-2px);
        }

        .logo-icon {
            font-size: 2rem;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .nav-logo:hover .logo-icon {
            background: rgba(52, 152, 219, 0.3);
            transform: scale(1.1);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .logo-main {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
        }

        .logo-sub {
            font-size: 0.7rem;
            font-weight: 600;
            color: #3498db;
            letter-spacing: 1px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.8rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            font-size: 1rem;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .nav-links a.active {
            background: linear-gradient(135deg, #3498db, #2980b9);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: #ffd700;
            border-radius: 2px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
        }

        .nav-cta {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            background: linear-gradient(135deg, #c0392b, #a93226);
        }

        /* Mobil menü gomb */
        .mobile-menu-btn {
            display: none;
            flex-direction: column;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            gap: 4px;
        }

        .mobile-menu-btn span {
            width: 25px;
            height: 3px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s ease;
            transform-origin: center;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        /* Oldal tartalom stílusok */
        .page-content {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            margin: 2rem auto;
            line-height: 1.8;
            max-width: 1200px;
        }
        
        .page-content h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            font-weight: 800;
            text-align: center;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .page-content h2 {
            color: #2c3e50;
            margin: 2.5rem 0 1rem;
            font-size: 1.8rem;
            font-weight: 700;
            border-left: 4px solid #3498db;
            padding-left: 1rem;
        }
        
        .page-content h3 {
            color: #3498db;
            margin: 1.8rem 0 0.8rem;
            font-size: 1.4rem;
            font-weight: 600;
        }
        
        .page-content h4 {
            color: #34495e;
            margin: 1.5rem 0 0.6rem;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .page-content p {
            margin-bottom: 1.2rem;
            color: #555;
            font-size: 1.05rem;
        }
        
        .page-content ul, .page-content ol {
            margin: 1.2rem 0 1.2rem 2rem;
        }
        
        .page-content li {
            margin-bottom: 0.6rem;
            color: #555;
            line-height: 1.6;
        }
        
        .page-content strong {
            color: #2c3e50;
            font-weight: 700;
        }
        
        .page-content blockquote {
            border-left: 4px solid #3498db;
            padding-left: 1.5rem;
            margin: 1.8rem 0;
            font-style: italic;
            color: #666;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0 10px 10px 0;
        }
        
        /* Kontakt információk */
        .contact-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2.5rem;
            border-radius: 15px;
            margin: 2.5rem 0;
            border-left: 4px solid #3498db;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        
        .contact-info h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.5rem;
        }
        
        /* Blog bejegyzések */
        .blog-post {
            margin-bottom: 3rem;
            padding-bottom: 2.5rem;
            border-bottom: 2px solid #e9ecef;
            transition: transform 0.3s ease;
        }
        
        .blog-post:hover {
            transform: translateX(10px);
        }
        
        .blog-post h2 {
            border-left: none;
            padding-left: 0;
            margin-top: 0;
        }
        
        .blog-post h2 a {
            color: #2c3e50;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .blog-post h2 a:hover {
            color: #3498db;
        }
        
        .blog-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.2rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .blog-meta span {
            background: #f8f9fa;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        /* Szolgáltatás kártyák */
        .service-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, #3498db, #2980b9);
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .service-card h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.4rem;
        }
        
        .service-card ul {
            margin: 1rem 0 0 1rem;
        }
        
        .service-card li {
            position: relative;
            padding-left: 1.5rem;
        }
        
        .service-card li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
        }
        
        /* Csapat tagok */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            margin: 2.5rem 0;
        }
        
        .team-member {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        
        .team-member:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        
        .team-member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1.2rem;
            border: 4px solid #3498db;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .team-member h4 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }
        
        .team-member p {
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        /* Űrlap stílusok */
        .form-group {
            margin-bottom: 1.8rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.6rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 1rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-family: inherit;
            font-size: 1rem;
            background: white;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }
        
        .form-group textarea {
            min-height: 140px;
            resize: vertical;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border: none;
            padding: 1.2rem 2.5rem;
            border-radius: 12px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(-1px);
        }
        
        /* Rács elrendezések */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }
        
        /* Reszponzív design */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1rem;
                height: 60px;
            }
            
            .mobile-menu-btn {
                display: flex;
            }
            
            .nav-links {
                position: fixed;
                top: 60px;
                left: 0;
                width: 100%;
                background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
                flex-direction: column;
                padding: 2rem;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                transform: translateY(-100%);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                gap: 0;
            }
            
            .nav-links.active {
                transform: translateY(0);
                opacity: 1;
                visibility: visible;
            }
            
            .nav-links li {
                width: 100%;
                text-align: center;
            }
            
            .nav-links a {
                display: block;
                padding: 1rem;
                border-radius: 0;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .nav-links a:last-child {
                border-bottom: none;
            }
            
            .nav-actions {
                display: none;
            }
            
            .page-content {
                padding: 1.5rem;
                margin: 1rem;
                border-radius: 15px;
            }
            
            .page-content h1 {
                font-size: 2rem;
            }
            
            .page-content h2 {
                font-size: 1.6rem;
            }
            
            .grid-2 {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .team-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .service-card {
                padding: 1.5rem;
            }
            
            .contact-info {
                padding: 1.5rem;
            }
            
            body.nav-open {
                overflow: hidden;
            }
        }
        
        @media (max-width: 480px) {
            .nav-container {
                height: 55px;
            }
            
            .logo-main {
                font-size: 1.2rem;
            }
            
            .logo-sub {
                font-size: 0.6rem;
            }
            
            .logo-icon {
                font-size: 1.5rem;
                padding: 6px;
            }
            
            .page-content {
                padding: 1rem;
                margin: 0.5rem;
            }
            
            .page-content h1 {
                font-size: 1.8rem;
            }
            
            .blog-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
        }
        
        /* Animációk */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .page-content {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .service-card,
        .team-member,
        .blog-post {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Print stílusok */
        @media print {
            .main-nav {
                display: none;
            }
            
            .page-content {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
            
            .submit-btn {
                display: none;
            }
        }
    </style>
    
</head>
    
<body>

<!-- Navigációs menü -->
    <nav class="main-nav">
        <div class="nav-container">
            <a href="./?page=home" class="nav-logo">
                <div class="logo-icon">🚗</div>
                <div class="logo-text">
                    <span class="logo-main">ProParking</span>
                    <span class="logo-sub">Intelligens Parkolás</span>
                </div>
            </a>
            
            <button class="mobile-menu-btn" aria-label="Menü megnyitása">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <ul class="nav-links">
                <li><a href="index.php" class="<?php echo $page === 'home' ? 'active' : ''; ?>">Főoldal</a></li>
                <li><a href="about.php" class="<?php echo $page === 'about' ? 'active' : ''; ?>">Rólunk</a></li>
                <li><a href="services.php" class="<?php echo $page === 'services' ? 'active' : ''; ?>">Szolgáltatások</a></li>
                <li><a href="blog.php" class="<?php echo $page === 'blog' ? 'active' : ''; ?>">Blog</a></li>
                <li><a href="contact.php" class="<?php echo $page === 'contact' ? 'active' : ''; ?>">Kapcsolat</a></li>
            </ul>
            
            <div class="nav-actions">
                <a href="contact.php" class="nav-cta">Ingyenes konzultáció</a>
            </div>
        </div>
    </nav>
    
<div class="page-content">
    <h1>Rólunk - ProParking</h1>
    
    <p>A ProParking egy vezető intelligens parkolórendszer-szolgáltató, amely 2015 óta alakítja át a városi parkolás élményét. Célunk, hogy innovatív technológiákkal megoldjuk a modern városok parkolási kihívásait, miközben fenntartható és költséghatékony megoldásokat kínálunk ügyfeleinknek.</p>
    
    <h2>Cégtörténetünk</h2>
    <p>A ProParking alapítói - Kovács István és Nagy Eszter - 2015-ben vették észre, hogy a hagyományos parkolórendszerek már nem tudják kielégíteni a gyorsan növekvő városi forgalom igényeit. Egy budapesti irodaház parkolójában tapasztalt negatív élmények inspirálták őket arra, hogy létrehozzanak egy olyan rendszert, amely valóban zökkenőmentes élményt nyújt mind a parkoló üzemeltetői, mind a felhasználói számára.</p>
    
    <p>Az első prototípus 2016-ban készült el, amely már tartalmazta az automatikus rendszámfelismerés alapvető funkcióit. 2017-re sikerült befejezni a fejlesztést, és az első ügyfeleink - több budapesti bevásárlóközpont és irodaház - is csatlakoztak hozzánk. 2018-ban már több mint 50 parkolóban volt jelen a ProParking rendszer.</p>
    
    <p>2020-ra elértük az 500 telepített rendszer mérföldkövet, és nemzetközi terjeszkedésbe kezdtünk. Jelenleg Magyarországon, Szlovákiában, Romániában és Horvátországban vagyunk jelen, összesen több mint 1200 parkolóban.</p>
    
    <h2>Missziónk és értékeink</h2>
    
    <h3>Missziónk</h3>
    <p>Az innovatív technológia segítségével átalakítani a városi parkolást, hogy gyorsabb, kényelmesebb és környezetbarátabb legyen mindenki számára. Célunk, hogy a parkolás ne legyen stresszforrás, hanem a napi rutin zökkenőmentes része.</p>
    
    <h3>Értékeink</h3>
    <ul>
        <li><strong>Innováció:</strong> Folyamatosan keressük az új technológiai megoldásokat, hogy mindig a legjobb szolgáltatást nyújthassuk.</li>
        <li><strong>Megbízhatóság:</strong> Rendszereink 99,9%-os üzemidőt garantálnak, mert tudjuk, hogy a parkolás kritikus fontosságú.</li>
        <li><strong>Fenntarthatóság:</strong> Minden fejlesztésünknél figyelembe vesszük a környezeti hatásokat, és olyan megoldásokat alkalmazunk, amelyek csökkentik az energiafogyasztást.</li>
        <li><strong>Ügyfélközpontúság:</strong> Ügyfeleink és a végfelhasználók igényei vezérlik fejlesztéseinket.</li>
        <li><strong>Transzparencia:</strong> Minden ügyfelünk részletes betekintést kap a rendszer működésébe és a teljesítménybe.</li>
    </ul>
    
    <h2>Csapatunk</h2>
    
    <div class="team-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div class="team-member">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80" alt="Kovács István - CEO">
            <h4>Kovács István</h4>
            <p>CEO & Alapító</p>
            <p style="font-size: 0.9rem; color: #666;">A vállalat vezetője, aki 15+ év tapasztalattal rendelkezik a technológiai szektorban. Mérnöki háttérrel rendelkezik, és szenvedélye az innovatív megoldások keresése.</p>
        </div>
        
        <div class="team-member">
            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?auto=format&fit=crop&w=300&q=80" alt="Nagy Eszter - CTO">
            <h4>Nagy Eszter</h4>
            <p>CTO & Alapító</p>
            <p style="font-size: 0.9rem; color: #666;">Technikai vezető, aki a szoftverfejlesztés és az AI technológiák specialistája. Vezette a ProParking intelligens algoritmusainak fejlesztését.</p>
        </div>
        
        <div class="team-member">
            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80" alt="Tóth Gábor - Fejlesztési vezető">
            <h4>Tóth Gábor</h4>
            <p>Fejlesztési vezető</p>
            <p style="font-size: 0.9rem; color: #666;">10+ év tapasztalattal rendelkezik a szoftverfejlesztés területén. Felügyeli a technikai csapatot és a folyamatos fejlesztési folyamatokat.</p>
        </div>
        
        <div class="team-member">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=300&q=80" alt="Szabó Anna - Ügyfélszolgálati vezető">
            <h4>Szabó Anna</h4>
            <p>Ügyfélszolgálati vezető</p>
            <p style="font-size: 0.9rem; color: #666;">Ügyfélkapcsolati szakértő, aki biztosítja, hogy minden ügyfelünk a legjobb szolgáltatást kapja. 8+ év tapasztalattal rendelkezik a szolgáltatási szektorban.</p>
        </div>
    </div>
    
    <h2>Technológiánk</h2>
    <p>A ProParking rendszer három fő technológiai elemen alapul:</p>
    
    <div class="service-card">
        <h3>Mesterséges Intelligencia</h3>
        <p>Fejlett AI algoritmusaink 99,7%-os pontossággal képesek felismerni a rendszámokat, még nehéz időjárási viszonyok között is. A gépi tanulási modelleink folyamatosan tanulnak és javulnak, így a rendszerünk egyre pontosabbá válik.</p>
    </div>
    
    <div class="service-card">
        <h3>IoT és Szenzor Technológia</h3>
        <p>Parkolóhelyeinken telepített okos szenzorok valós időben jeleznek a helyfoglaltságról. Ezek az energiatakarékos eszközök akár 5 évig működnek egyetlen elemmel, minimalizálva a karbantartási igényt.</p>
    </div>
    
    <div class="service-card">
        <h3>Felhőalapú Architektúra</h3>
        <p>A rendszerünk teljes mértékben felhőalapú, ami rugalmasságot, skálázhatóságot és magas rendelkezésre állást biztosít. Adatai biztonságban vannak ISO 27001 tanúsítvánnyal rendelkező adatközpontjainkban.</p>
    </div>
    
    <h2>Kitüntetések és Elismerések</h2>
    <ul>
        <li><strong>2023</strong> - Leginnovatívabb Közlekedéstechnológiai Cég díj</li>
        <li><strong>2022</strong> - Kiemelkedő Startup díj a Közép-európai Technológiai Fórumon</li>
        <li><strong>2021</strong> - Zöld Technológia díj a Fenntartható Fejlődésért Alapítványtól</li>
        <li><strong>2020</strong> - Legjobb IoT Megoldás díj a Magyar Innovációs Szövetségtől</li>
        <li><strong>2019</strong> - Kiemelkedő Vállalkozás díj a Budapesti Kereskedelmi Kamarától</li>
    </ul>
    
    <h2>Jövőbeli terveink</h2>
    <p>A ProParking folyamatosan fejlődik és bővül. 2024-re tervezzük a nyugat-európai piacokra való belépést, valamint új funkciók bevezetését, mint például:</p>
    
    <ul>
        <li>Elektromos autók intelligens töltőállomás-integrációja</li>
        <li>Autonóm járművek számára tervezett parkolási megoldások</li>
        <li>Okos városi integráció a közlekedési rendszerekkel</li>
        <li>Blockchain-alapú biztonsági megoldások</li>
    </ul>
    
    <p>Hiszünk abban, hogy a technológia segítségével jobb városi élményt teremthetünk mindenki számára. A ProParking nem csupán egy parkolórendszer - ez egy teljes körű mobilitási megoldás, amely hozzájárul a fenntarthatóbb, élhetőbb városok kialakításához.</p>
    
</div>

<footer>
    <div id="footerbox">
        <p><a href="https://instagram.com" class="footerlink">📷 Instagram</a></p>
        <p><a href="https://facebook.com" class="footerlink">👥 Facebook</a></p>
        <p><a href="https://x.com" class="footerlink">🐦 X</a></p>
        <p>📞 Telefonszám: +36 31 568 7542</p>
    </div>
    &copy; 2025 Parkolórendszer bemutató | Minden jog fenntartva.
</footer>
</body>

</html>
