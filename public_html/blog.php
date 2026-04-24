<?php
$page = 'blog';
$pageTitle = 'Blog - ProParking Intelligens Parkolórendszer';
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        /* ===== ALAP STÍLUSOK ===== */
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

        /* ===== NAVIGÁCIÓS SÁV ===== */
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

        /* ===== BLOG OLDAL SPECIFIKUS STÍLUSOK ===== */
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

        .page-content p {
            margin-bottom: 1.2rem;
            color: #555;
            font-size: 1.05rem;
        }

        .page-content strong {
            color: #2c3e50;
            font-weight: 700;
        }

        /* Blog bejegyzések */
        .blog-post {
            margin-bottom: 3rem;
            padding-bottom: 2.5rem;
            border-bottom: 2px solid #e9ecef;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .blog-post::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(52, 152, 219, 0.05), transparent);
            transition: left 0.6s;
        }

        .blog-post:hover::before {
            left: 100%;
        }

        .blog-post:hover {
            transform: translateX(10px);
            border-bottom-color: #3498db;
        }

        .blog-post h2 {
            color: #2c3e50;
            font-size: 1.6rem;
            margin-bottom: 1rem;
            border-left: none;
            padding-left: 0;
            transition: color 0.3s ease;
        }

        .blog-post:hover h2 {
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
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .blog-post:hover .blog-meta span {
            background: #e3f2fd;
            transform: translateY(-2px);
        }

        .blog-post p:last-of-type {
            position: relative;
            padding-top: 1rem;
        }

        .blog-post p:last-of-type::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 2px;
        }

        /* Blog kategóriák */
        .category-tags {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }

        .category-tag {
            background: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .category-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .category-tag.technology { background: #3498db; }
        .category-tag.business { background: #2ecc71; }
        .category-tag.sustainability { background: #e74c3c; }
        .category-tag.guide { background: #f39c12; }
        .category-tag.news { background: #9b59b6; }
        .category-tag.security { background: #1abc9c; }
        .category-tag.trends { background: #34495e; }

        /* Hírlevél feliratkozás */
        .contact-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 2.5rem;
            border-radius: 15px;
            margin: 2.5rem 0;
            border-left: 4px solid #3498db;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
        }

        .contact-info h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .newsletter-form {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .newsletter-form input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .newsletter-form input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .newsletter-form button {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-form button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .privacy-note {
            font-size: 0.8rem;
            margin-top: 0.5rem;
            color: #666;
        }

        /* Következő cikkek szekció */
        .upcoming-posts {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }

        .upcoming-posts h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-size: 1.4rem;
        }

        .upcoming-posts p {
            color: #666;
            margin-bottom: 0.5rem;
        }

        .social-follow {
            margin-top: 1rem;
        }

        .social-follow p {
            font-size: 0.9rem;
            color: #888;
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

        .blog-post {
            animation: fadeInUp 0.6s ease-out;
        }

        .blog-post:nth-child(1) { animation-delay: 0.1s; }
        .blog-post:nth-child(2) { animation-delay: 0.2s; }
        .blog-post:nth-child(3) { animation-delay: 0.3s; }
        .blog-post:nth-child(4) { animation-delay: 0.4s; }
        .blog-post:nth-child(5) { animation-delay: 0.5s; }
        .blog-post:nth-child(6) { animation-delay: 0.6s; }

        /* ===== RESZPONZÍV DESIGN ===== */
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
            
            .blog-post {
                padding: 1.5rem;
                margin-bottom: 2rem;
            }
            
            .blog-post:hover {
                transform: translateX(5px);
            }
            
            .blog-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
            
            .category-tags {
                justify-content: center;
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
            
            .blog-post h2 {
                font-size: 1.4rem;
            }
            
            .contact-info {
                padding: 1.5rem;
            }
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
            
            .newsletter-form {
                display: none;
            }
            
            .blog-post:hover {
                transform: none;
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
        <h1>ProParking Blog</h1>
        
        <p>Üdvözöljük a ProParking hivatalos blogján! Itt megismerheti a legújabb trendeket a parkolás technológiában, praktikus tippeket kap, és betekintést nyerhet cégünk legújabb fejlesztéseibe. Kövessen minket, hogy mindig naprakész legyen!</p>
        
        <div class="blog-post">
            <h2>Az Okos Parkolás Jövője: 5 Trend, Amit 2024-ben Figyelni Kell</h2>
            <div class="blog-meta">
                <span>📅 2024. január 15.</span> | 
                <span>👨‍💼 Írta: Kovács István</span> | 
                <span>🏷️ Technológia, Trendek</span>
            </div>
            <p>A parkolás technológiája rohamosan fejlődik, és 2024 számos izgalmas újdonsággal kecsegtet. Ebben a cikkben bemutatjuk az öt legfontosabb trendet, amely alakítani fogja az okos parkolás jövőjét...</p>
            <p>Az első és legjelentősebb trend az elektromos járművek integrációja. A világ egyre inkább az elektromos mobilitás felé halad, és a parkolóknak ennek megfelelően kell alkalmazkodniuk. A ProParking rendszerünk már most képes kezelni a töltési folyamatokat, de 2024-ben tovább fejlesztjük ezt a funkciót...</p>
            <p>A második nagy trend a mesterséges intelligencia alkalmazása a parkolás optimalizálásában. MI-alapú algoritmusaink nemcsak a rendszámfelismerésben nyújtanak kiváló teljesítményt, hanem képesek előre jelezni a forgalmi mintákat is...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <div class="blog-post">
            <h2>Hogyan Növelheti Meg a Parkoló Bevételeit Intelligens Technológiával?</h2>
            <div class="blog-meta">
                <span>📅 2023. december 3.</span> | 
                <span>👩‍💼 Írta: Nagy Eszter</span> | 
                <span>🏷️ Üzlet, Stratégia</span>
            </div>
            <p>A hagyományos parkolórendszerek sok esetben nem tudják maximális szinten kihasználni a bevételi lehetőségeket. Ebben a részletes útmutatóban bemutatjuk, hogyan segíthet a ProParking rendszer a bevétel növelésében...</p>
            <p>Az első és legfontosabb lépés a dinamikus árazás bevezetése. A ProParking rendszer lehetővé teszi, hogy a parkolási díjak automatikusan változzanak a kereslet függvényében. Például csúcsidőben, vagy speciális események alkalmával magasabb díjat lehet alkalmazni...</p>
            <p>A második nagy lehetőség a parkolás előre történő foglalása. Statisztikáink szerint azok a parkolók, amelyek lehetővé teszik az előre foglalást, akár 30%-kal növelhetik bevételüket. A felhasználók hajlandóak többet fizetni a biztonságért, hogy garantáltan kapjanak parkolóhelyet...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <div class="blog-post">
            <h2>Fenntartható Parkolás: Hogyan Csökkenthetjük a Környezeti Lábnyomunkat</h2>
            <div class="blog-meta">
                <span>📅 2023. november 18.</span> | 
                <span>👨‍💼 Írta: Tóth Gábor</span> | 
                <span>🏷️ Fenntarthatóság, Innováció</span>
            </div>
            <p>A fenntarthatóság egyre fontosabb tényezővé válik minden iparágban, és a parkolás sem kivétel. Ebben a cikkben bemutatjuk, hogyan segít a ProParking technológia csökkenteni a környezeti hatásokat...</p>
            <p>Az egyik legnagyobb energiafogyasztó a parkolók világítása. Hagyományos rendszerekben a világítás folyamatosan működik, függetlenül attól, hogy valaki használja-e a parkolót vagy sem. ProParking rendszerünk okos világítást alkalmaz, amely csak akkor kapcsol be teljes fényerőre, amikor valóban szükséges...</p>
            <p>Az elektromos járművek térnyerése új kihívásokat és lehetőségeket egyaránt teremt. ProParking töltőállomás integrációnk nemcsak lehetővé teszi az elektromos járművek töltését, hanem optimalizálja is az energiafelhasználást...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <div class="blog-post">
            <h2>Útmutató: Hogyan Válassza ki a Megfelelő Parkolórendszert Vállalata Számára</h2>
            <div class="blog-meta">
                <span>📅 2023. október 29.</span> | 
                <span>👩‍💼 Írta: Szabó Anna</span> | 
                <span>🏷️ Útmutató, Vásárlás</span>
            </div>
            <p>A megfelelő parkolórendszer kiválasztása nem mindig egyszerű feladat. Számos tényezőt kell figyelembe venni, hogy a befektetés hosszú távon is megtérüljön. Ebben az átfogó útmutatóban végigvezetjük Önt a döntési folyamaton...</p>
            <p>Első lépésként értékelje ki jelenlegi igényeit és a jövőbeli növekedési terveit. Fontos, hogy a választott rendszer ne csak a jelenlegi kapacitásnak megfeleljen, hanem képes legyen skálázódni a jövőbeli igények szerint is...</p>
            <p>Tekintse meg a rendszer integrációs képességeit. Egy modern parkolórendszernek képesnek kell lennie kommunikálni más vállalati rendszerekkel, mint például a számlázási rendszerrel, a hozzáférés-vezérléssel vagy az irodaház irányítási rendszerével...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <div class="blog-post">
            <h2>ProParking Új Funkció: Automatikus Parkolóhely Keresés</h2>
            <div class="blog-meta">
                <span>📅 2023. október 12.</span> | 
                <span>👨‍💼 Írta: Kovács István</span> | 
                <span>🏷️ Újdonság, Technológia</span>
            </div>
            <p>Büszkén jelentjük be legújabb funkciónkat: az Automatikus Parkolóhely Keresést. Ez az innovatív megoldás forradalmasítja a parkolás élményét, és jelentősen csökkenti a parkolásra fordított időt...</p>
            <p>Az új funkció lehetővé teszi, hogy a felhasználók egyszerűen beállítsák preferenciáikat (például "közel a kijárathoz" vagy "tágas parkolóhely"), és a rendszer automatikusan megtalálja számukra a legmegfelelőbb helyet. Az algoritmus figyelembe veszi a jármű méretét, a jelenlegi forgalmi helyzetet és a felhasználó korábbi preferenciáit is...</p>
            <p>A funkció tesztelése során azt tapasztaltuk, hogy a felhasználók átlagosan 65%-kal kevesebb időt töltöttek parkolóhely kereséssel. Ez nemcsak időt takarít meg, hanem csökkenti a üzemanyag-fogyasztást és a környezetszennyezést is...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <div class="blog-post">
            <h2>Biztonság Előtérben: ProParking Új Biztonsági Funkciói</h2>
            <div class="blog-meta">
                <span>📅 2023. szeptember 25.</span> | 
                <span>👩‍💼 Írta: Nagy Eszter</span> | 
                <span>🏷️ Biztonság, Innováció</span>
            </div>
            <p>A biztonság mindig is elsődleges szempont volt számunkra, és most tovább fejlesztettük rendszerünket, hogy még magasabb szintű védelmet nyújthassunk. Ismerje meg új biztonsági funkcióinkat...</p>
            <p>Az egyik legfontosabb újdonság a Valós Idejű Anomália Detektálás. Ez a funkció képes automatikusan észlelni gyanús tevékenységeket a parkoló területén, mint például hosszabb ideig egy helyben álló járműveket, vagy éjszakai behatolásokat...</p>
            <p>Emellett bevezettük a Biztonsági Övezetek funkciót is, amely lehetővé teszi, hogy a parkoló üzemeltető kijelöljön speciális biztonsági zónákat. Ezekben a zónákban a rendszer automatikusan riaszt, ha ismeretlen jármű vagy személy tartózkodik...</p>
            <p><strong>Olvass tovább...</strong></p>
        </div>
        
        <h2>Blog Kategóriák</h2>
        
        <div class="category-tags">
            <span class="category-tag technology">Technológia</span>
            <span class="category-tag business">Üzlet</span>
            <span class="category-tag sustainability">Fenntarthatóság</span>
            <span class="category-tag guide">Útmutató</span>
            <span class="category-tag news">Újdonság</span>
            <span class="category-tag security">Biztonság</span>
            <span class="category-tag trends">Trendek</span>
        </div>
        
        <h2>Iratkozz fel hírlevelünkre</h2>
        
        <div class="contact-info">
            <p>Szeretne elsőként értesülni legújabb cikkeinkről, újdonságainkról és speciális ajánlatainkról? Iratkozzon fel hírlevelünkre!</p>
            
            <form class="newsletter-form">
                <input type="email" placeholder="Email címed" required>
                <button type="submit">Feliratkozás</button>
            </form>
            
            <p class="privacy-note">A feliratkozással elfogadja adatvédelmi szabályzatunkat. Leiratkozás bármikor lehetséges.</p>
        </div>
        
        <div class="upcoming-posts">
            <h3>Következő cikkeink</h3>
            <p>Hamarosan érkezik: "Mesterséges Intelligencia a Parkolásban: Mit Várhatunk 2025-től?" és "Hogyan Védjük Meg Parkolóinkat a Kibertámadásoktól?"</p>
            <div class="social-follow">
                <p style="color: #666; font-size: 0.9rem;">Ne maradjon le! Kövessen minket a közösségi médiában is.</p>
            </div>
        </div>
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

    <script>
    // Mobil menü kezelése
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
        const navLinks = document.querySelector('.nav-links');
        
        if (mobileMenuBtn && navLinks) {
            mobileMenuBtn.addEventListener('click', function() {
                navLinks.classList.toggle('active');
                mobileMenuBtn.classList.toggle('active');
                document.body.classList.toggle('nav-open');
            });
            
            // Kattintás menün kívül bezárja a menüt
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.nav-container')) {
                    navLinks.classList.remove('active');
                    mobileMenuBtn.classList.remove('active');
                    document.body.classList.remove('nav-open');
                }
            });
            
            // ESC billentyű bezárja a menüt
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    navLinks.classList.remove('active');
                    mobileMenuBtn.classList.remove('active');
                    document.body.classList.remove('nav-open');
                }
            });
        }

        // Hírlevél feliratkozás
        const newsletterForm = document.querySelector('.newsletter-form');
        if (newsletterForm) {
            newsletterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = this.querySelector('input[type="email"]').value;
                alert('Köszönjük a feliratkozást! Hamarosan kapni fogja első hírlevelünket a(z) ' + email + ' címre.');
                this.reset();
            });
        }
    });
    </script>
</body>
</html>