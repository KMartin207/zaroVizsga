<?php
$page = 'services';
$pageTitle = 'Szolgáltatások - ProParking Intelligens Parkolórendszer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
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

        /* Services Page Styles */
        .page-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-content h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-align: center;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-content h2 {
            color: #34495e;
            font-size: 1.8rem;
            margin: 3rem 0 1.5rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #3498db;
            position: relative;
        }

        .page-content h2::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100px;
            height: 3px;
            background: #e74c3c;
        }

        .page-content h3 {
            color: #2c3e50;
            font-size: 1.4rem;
            margin: 1.5rem 0 1rem 0;
        }

        .page-content h4 {
            color: #34495e;
            font-size: 1.1rem;
            margin: 1rem 0 0.5rem 0;
        }

        .page-content p {
            line-height: 1.7;
            color: #555;
            margin-bottom: 1rem;
        }

        /* Service Card Styles */
        .service-card {
            background: white;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 5px solid #3498db;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.2);
        }

        .service-card h3 {
            color: #2c3e50;
            margin-top: 0;
            display: flex;
            align-items: center;
        }

        .service-card h3::before {
            content: '🚀';
            margin-right: 0.5rem;
            font-size: 1.2em;
        }

        .service-card ul {
            margin: 1rem 0;
            padding-left: 1.5rem;
        }

        .service-card li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
            color: #555;
            position: relative;
        }

        .service-card li::marker {
            color: #3498db;
            font-weight: bold;
        }

        /* Pricing Grid Styles */
        .page-content > div:has(> div:last-child) {
            margin: 3rem 0;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }

        .pricing-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            border: 2px solid transparent;
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .pricing-card.popular {
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
            border: 2px solid #3498db;
            transform: scale(1.05);
        }

        .pricing-card.popular:hover {
            transform: scale(1.05) translateY(-5px);
        }

        .popular-badge {
            background: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: inline-block;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .pricing-card h3 {
            color: #2c3e50;
            margin: 1rem 0;
            font-size: 1.5rem;
        }

        .pricing-price {
            font-size: 2rem;
            font-weight: bold;
            color: #3498db;
            margin: 1rem 0;
        }

        .pricing-card ul {
            text-align: left;
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }

        .pricing-card li {
            margin-bottom: 0.8rem;
            padding-left: 1.5rem;
            position: relative;
            color: #555;
        }

        .pricing-card li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #27ae60;
            font-weight: bold;
        }

        .pricing-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .pricing-btn:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .pricing-card.popular .pricing-btn {
            background: linear-gradient(135deg, #2c3e50, #34495e);
        }

        .pricing-card.popular .pricing-btn:hover {
            background: linear-gradient(135deg, #34495e, #2c3e50);
        }

        /* Technical Specifications */
        .tech-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .tech-specs h4 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.3rem;
            margin-bottom: 0.8rem;
        }

        .tech-specs ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tech-specs li {
            margin-bottom: 0.5rem;
            padding-left: 1.2rem;
            position: relative;
            color: #555;
        }

        .tech-specs li::before {
            content: '▸';
            position: absolute;
            left: 0;
            color: #3498db;
            font-weight: bold;
        }

        /* Contact Info */
        .contact-info {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 2rem;
            border-radius: 12px;
            margin-top: 3rem;
            text-align: center;
            border-left: 5px solid #e74c3c;
        }

        .contact-info h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.5rem;
        }

        .contact-info p {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 1rem;
            }
            
            .page-content h1 {
                font-size: 2rem;
            }
            
            .page-content h2 {
                font-size: 1.5rem;
                margin: 2rem 0 1rem 0;
            }
            
            .service-card {
                padding: 1.5rem;
            }
            
            .pricing-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .pricing-card.popular {
                transform: none;
            }
            
            .pricing-card.popular:hover {
                transform: translateY(-5px);
            }
            
            .tech-specs {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .page-content {
                padding: 0.5rem;
            }
            
            .page-content h1 {
                font-size: 1.8rem;
            }
            
            .service-card {
                padding: 1rem;
            }
            
            .pricing-card {
                padding: 1.5rem;
            }
            
            .contact-info {
                padding: 1.5rem;
            }
        }

        /* Animation Classes */
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

        .service-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .service-card:nth-child(odd) {
            animation-delay: 0.1s;
        }

        .service-card:nth-child(even) {
            animation-delay: 0.2s;
        }

        /* Icon Styles for Different Services */
        .service-card:nth-child(1) h3::before { content: '📷'; } /* ANPR */
        .service-card:nth-child(2) h3::before { content: '💳'; } /* Payment */
        .service-card:nth-child(3) h3::before { content: '📊'; } /* Monitoring */
        .service-card:nth-child(4) h3::before { content: '📅'; } /* Reservation */
        .service-card:nth-child(5) h3::before { content: '⚡'; } /* EV Charging */
        .service-card:nth-child(6) h3::before { content: '🛡️'; } /* Security */
        .service-card:nth-child(7) h3::before { content: '📈'; } /* Analytics */
        .service-card:nth-child(8) h3::before { content: '🔗'; } /* Integration */

        /* Print Styles */
        @media print {
            .service-card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
            
            .pricing-grid {
                grid-template-columns: 1fr;
            }
            
            .pricing-card.popular {
                transform: none;
                border: 1px solid #3498db;
            }
        }
    </style>

</head>
<body>
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
    <h1>Szolgáltatásaink</h1>
    
    <p>A ProParking átfogó parkolásmenedzsment megoldásokat kínál, amelyek mind a parkoló üzemeltetők, mind a felhasználók számára optimalizálják a parkolási élményt. Technológiai innovációink segítségével biztosítjuk a hatékonyságot, biztonságot és kényelmet.</p>
    
    <h2>Alapszolgáltatásaink</h2>
    
    <div class="service-card">
        <h3>Automatikus Rendszámfelismerés (ANPR)</h3>
        <p>Fejlett kamera rendszerünk 99,7%-os pontossággal képes felismerni a járművek rendszámait, még nehéz fényviszonyok között is. A rendszer automatikusan rögzíti a belépési és kilépési időpontokat, minimalizálva a humán hibák lehetőségét.</p>
        <ul>
            <li>Magas felbontású IP kamera rendszer</li>
            <li>Valós idejű adatfeldolgozás</li>
            <li>Integrált világítás rossz fényviszonyokhoz</li>
            <li>Automatikus adatbázis frissítés</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Online Fizetési Rendszer</h3>
        <p>Biztonságos online fizetési platformunk lehetővé teszi a felhasználók számára, hogy mobilalkalmazásunkon vagy webes felületünkön keresztül fizessenek parkolási díjakat. Több fizetési mód közül választhatnak, beleértve a bankkártyát, mobil fizetést és előre feltöltött egyenleget.</p>
        <ul>
            <li>Többfajta fizetési mód támogatása</li>
            <li>PCI DSS szintű adatbiztonság</li>
            <li>Automatikus számlázás</li>
            <li>Előfizetési modellek</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Valós Idejű Helyfoglaltság Monitorozás</h3>
        <p>Ulrahang szenzoraink és okos kameráink valós időben követik a parkolóhelyek foglaltságát. Ezek az adatok mobilalkalmazásunkon és digitális kijelzőinken jelennek meg, így a felhasználók azonnal megtalálják a szabad helyeket.</p>
        <ul>
            <li>IoT szenzor technológia</li>
            <li>LED jelzőrendszer</li>
            <li>Mobilalkalmazás integráció</li>
            <li>Statisztikai jelentések</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Parkolás Előre Foglalása</h3>
        <p>Felhasználóink mobilalkalmazásunk segítségével akár 30 nappal korábban is lefoglalhatják a kívánt parkolóhelyet. Ez különösen hasznos repülőtéri parkolásoknál, eseményeknél, vagy nagy forgalommal rendelkező területeken.</p>
        <ul>
            <li>Időalapú foglalási rendszer</li>
            <li>Dinamikus árazás</li>
            <li>Automatikus emlékeztetők</li>
            <li>Ingyenes lemondás opció</li>
        </ul>
    </div>
    
    <h2>Haladó Szolgáltatásaink</h2>
    
    <div class="service-card">
        <h3>Elektromos Jármű Töltőállomás Integráció</h3>
        <p>Parkolóink egyre nagyobb számban kapcsolódnak elektromos jármű töltőállomásokhoz. Rendszerünk képes kezelni a töltési folyamatot, a fizetést, és akár előre foglalni a töltőállomásokat is.</p>
        <ul>
            <li>Okos töltés menedzsment</li>
            <li>Energiafogyasztás monitorozás</li>
            <li>Különböző töltőállomány típusok támogatása</li>
            <li>Integrált fizetési rendszer</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Biztonsági és Megfigyelési Rendszer</h3>
        <p>Magas felbontású biztonsági kameráink 24/7 figyelik a parkolók területét. A rendszer képes észlelni gyanús tevékenységeket, és automatikusan értesíti a biztonsági szolgálatot.</p>
        <ul>
            <li>360 fokos kamera lefedettség</li>
            <li>Mozgásérzékelés és riasztás</li>
            <li>Felhőalapú videó tárolás</li>
            <li>Távoli hozzáférés és vezérlés</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Adatelemzés és Jelentéskészítés</h3>
        <p>Rendszerünk részletes adatelemzést készít a parkolási mintákról, forgalomról és bevételi forrásokról. Ezek az adatok segítenek optimalizálni a parkoló működését és a jövőbeli fejlesztéseket tervezni.</p>
        <ul>
            <li>Egyéni jelentések</li>
            <li>Valós idejű dashboard</li>
            <li>Előrejelző elemzés</li>
            <li>Automatikus jelentések emailben</li>
        </ul>
    </div>
    
    <div class="service-card">
        <h3>Integráció Harmadik Fél Rendszerekkel</h3>
        <p>A ProParking rendszer könnyen integrálható más vállalati rendszerekkel, mint például számlázó rendszerekkel, irodaépület hozzáférés-vezérlő rendszerekkel, vagy városi közlekedési applikációkkal.</p>
        <ul>
            <li>API alapú integráció</li>
            <li>Egyéni fejlesztési lehetőség</li>
            <li>Rugalmas adatcsere formátumok</li>
            <li>Technikai támogatás</li>
        </ul>
    </div>
    
    <h2>Csomagjaink</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin: 2rem 0;">
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
            <h3>Alap Csomag</h3>
            <div style="font-size: 2rem; font-weight: bold; color: #3498db; margin: 1rem 0;">49.990 Ft/hó</div>
            <ul style="text-align: left; list-style: none; padding: 0;">
                <li style="margin-bottom: 0.8rem;">✓ Akár 50 parkolóhely</li>
                <li style="margin-bottom: 0.8rem;">✓ Alapfunkciók</li>
                <li style="margin-bottom: 0.8rem;">✓ Mobil alkalmazás</li>
                <li style="margin-bottom: 0.8rem;">✓ 8/5 támogatás</li>
                <li style="margin-bottom: 0.8rem;">✓ Havi riportok</li>
            </ul>
            <button style="background: #3498db; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 1rem;">Választom</button>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3); text-align: center; border: 2px solid #3498db; transform: scale(1.05);">
            <div style="background: #3498db; color: white; padding: 0.5rem 1rem; border-radius: 8px; margin-bottom: 1rem; display: inline-block;">Legnépszerűbb</div>
            <h3>Prémium Csomag</h3>
            <div style="font-size: 2rem; font-weight: bold; color: #3498db; margin: 1rem 0;">89.990 Ft/hó</div>
            <ul style="text-align: left; list-style: none; padding: 0;">
                <li style="margin-bottom: 0.8rem;">✓ Akár 200 parkolóhely</li>
                <li style="margin-bottom: 0.8rem;">✓ Összes funkció</li>
                <li style="margin-bottom: 0.8rem;">✓ Haladó analitika</li>
                <li style="margin-bottom: 0.8rem;">✓ 24/7 támogatás</li>
                <li style="margin-bottom: 0.8rem;">✓ Egyéni integráció</li>
            </ul>
            <button style="background: #2c3e50; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 1rem;">Választom</button>
        </div>
        
        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
            <h3>Vállalati Csomag</h3>
            <div style="font-size: 2rem; font-weight: bold; color: #3498db; margin: 1rem 0;">Egyedi árajánlat</div>
            <ul style="text-align: left; list-style: none; padding: 0;">
                <li style="margin-bottom: 0.8rem;">✓ 200+ parkolóhely</li>
                <li style="margin-bottom: 0.8rem;">✓ Teljes testreszabás</li>
                <li style="margin-bottom: 0.8rem;">✓ Saját szerver opció</li>
                <li style="margin-bottom: 0.8rem;">✓ Dedikált account manager</li>
                <li style="margin-bottom: 0.8rem;">✓ API hozzáférés</li>
            </ul>
            <button style="background: #3498db; color: white; border: none; padding: 0.8rem 2rem; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; margin-top: 1rem;">Ajánlatot kérek</button>
        </div>
    </div>
    
    <h2>Technikai Specifikációk</h2>
    
    <div class="service-card">
        <h3>Hardver Követelmények</h3>
        <p>A ProParking rendszer minimális hardver követelményekkel rendelkezik, így könnyen integrálható meglévő infrastruktúrákba is.</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
            <div>
                <h4>Alaprendszer</h4>
                <ul>
                    <li>Intel i5 processzor vagy újabb</li>
                    <li>8GB RAM (ajánlott 16GB)</li>
                    <li>500GB SSD tárhely</li>
                    <li>Gigabit hálózati kapcsolat</li>
                </ul>
            </div>
            
            <div>
                <h4>Perifériák</h4>
                <ul>
                    <li>HD IP kamera (min. 2MP)</li>
                    <li>LED kijelzők</li>
                    <li>IoT szenzorok</li>
                    <li>UPS tápegység</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="service-card">
        <h3>Szoftver Követelmények</h3>
        <p>A ProParking platform modern technológiákon alapul, biztosítva a stabil működést és a biztonságot.</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
            <div>
                <h4>Szerver Oldal</h4>
                <ul>
                    <li>Linux (Ubuntu 20.04+)</li>
                    <li>Docker konténerizáció</li>
                    <li>MySQL 8.0 adatbázis</li>
                    <li>Redis gyorsítótár</li>
                </ul>
            </div>
            
            <div>
                <h4>Kliens Oldal</h4>
                <ul>
                    <li>Modern webböngésző</li>
                    <li>iOS 13+ / Android 8+</li>
                    <li>HTTPS kapcsolat</li>
                    <li>JavaScript engedélyezve</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="contact-info">
        <h3>Készen áll a változásra?</h3>
        <p>Vegye fel velünk a kapcsolatot, és egy szakértő munkatársunk segít kiválasztani az Ön számára legmegfelelőbb csomagot és konfigurációt.</p>
        <p>📞 Hívjon minket bizalommal: +36 1 234 5678 | 📧 info@proparking.hu</p>
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
</div>
</body>
</html>