<?php
require_once 'db.php';
session_start();

$database = new Database();
$conn = $database->getConnection();

// --- BACKEND LOGIC ---

// 1. Contact Form (Email Sending)
// 1. Contact Form (Professional HTML Email)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_inquiry'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    if ($name && $email && $message) {
        $toAdmin = "kothenczmartin@gmail.com";
        $subjectAdmin = "🚀 Új Lead: " . $name;
        $subjectUser = "ProParking - Megkaptuk üzenetét";

        // --- EMAIL STYLE TEMPLATE FUNCTION ---
        $createEmailBody = function ($title, $content, $isAction = false) {
            return "
            <html>
            <head>
                <style>
                    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
                    .header { background-color: #2563eb; padding: 20px; text-align: center; }
                    .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 1px; }
                    .content { padding: 30px; color: #334155; line-height: 1.6; }
                    .highlight { background-color: #eff6ff; padding: 15px; border-left: 4px solid #2563eb; border-radius: 4px; margin: 20px 0; }
                    .footer { background-color: #0f172a; padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; }
                    .btn { display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Pro<span>Parking</span></h1>
                    </div>
                    <div class='content'>
                        <h2 style='color: #0f172a; margin-top: 0;'>$title</h2>
                        $content
                    </div>
                    <div class='footer'>
                        &copy; " . date('Y') . " ProParking Systems Kft.<br>
                        1051 Budapest, Tech Park 1. | +36 1 234 5678
                    </div>
                </div>
            </body>
            </html>";
        };

        // --- ADMIN EMAIL CONTENT ---
        $adminContent = "
            <p>Új érdeklődés érkezett a weboldalról. Az alábbiakban olvashatod a részleteket:</p>
            <div class='highlight'>
                <strong>Név:</strong> $name<br>
                <strong>Email:</strong> $email<br>
                <strong>Időpont:</strong> " . date("Y-m-d H:i:s") . "
            </div>
            <p><strong>Üzenet:</strong></p>
            <p style='background: #f1f5f9; padding: 15px; border-radius: 5px; font-style: italic;'>\"$message\"</p>
            " . ($isAction ? "<a href='mailto:$email' class='btn'>Válasz írása</a>" : "");

        $bodyAdmin = $createEmailBody("Új Üzenet Érkezett", $adminContent, true);

        // --- USER EMAIL CONTENT ---
        $userContent = "
            <p>Kedves <strong>$name</strong>!</p>
            <p>Köszönjük, hogy felvette velünk a kapcsolatot. Rendszerünk sikeresen fogadta üzenetét.</p>
            <p>Szakértő kollégáink hamarosan (általában 24 órán belül) feldolgozzák a megkeresést, és a megadott e-mail címen válaszolnak.</p>
            <div class='highlight'>
                <small>Az Ön által küldött üzenet:</small><br>
                <em>$message</em>
            </div>
            <p>Üdvözlettel,<br><strong>A ProParking Csapata</strong></p>";

        $bodyUser = $createEmailBody("Köszönjük megkeresését!", $userContent);

        // --- HEADERS ---
        $headersAdmin = "MIME-Version: 1.0" . "\r\n";
        $headersAdmin .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headersAdmin .= "From: noreply@proparking.hu" . "\r\n";
        $headersAdmin .= "Reply-To: $email" . "\r\n";

        $headersUser = "MIME-Version: 1.0" . "\r\n";
        $headersUser .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headersUser .= "From: noreply@proparking.hu" . "\r\n";

        // --- SEND ---
        @mail($toAdmin, $subjectAdmin, $bodyAdmin, $headersAdmin);
        @mail($email, $subjectUser, $bodyUser, $headersUser);

        $_SESSION['msg_success'] = "Köszönjük! Az üzenetet elküldtük, és emailben visszaigazoltuk.";
    }
    else {
        $_SESSION['msg_success'] = "Hiba: Kérjük töltsön ki minden mezőt!";
    }

    header("Location: " . $_SERVER['PHP_SELF'] . "#contact");
    exit();
}

// 2. Review Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $nev = htmlspecialchars(trim($_POST['reviewName']));
    $csillag = intval($_POST['rating']);
    $komment = htmlspecialchars(trim($_POST['reviewText']));

    if (!empty($nev) && $csillag >= 1 && $csillag <= 5 && !empty($komment)) {
        try {
            $stmt = $conn->prepare("INSERT INTO ertekeles (enev, ecsillag, ekomment) VALUES (:nev, :csillag, :komment)");
            $stmt->execute([':nev' => $nev, ':csillag' => $csillag, ':komment' => $komment]);
            $_SESSION['review_success'] = "Véleményét sikeresen rögzítettük.";
            header("Location: " . $_SERVER['PHP_SELF'] . "#reviews");
            exit();
        }
        catch (PDOException $e) { /* Silent fail */
        }
    }
}

// 3. Fetch Data & Stats
$reviews = [];
try {
    $stmt = $conn->query("SELECT * FROM ertekeles ORDER BY edatum DESC");
    if ($stmt)
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (Exception $e) {
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProParking | Intelligens Parkolásmenedzsment</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animation Libs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb; 
            --primary-dark: #1e40af;
            --secondary: #0f172a; 
            --accent: #3b82f6;
            --bg-light: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --radius: 16px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* --- RESET & BASE --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: white;
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.7;
        }

        h1, h2, h3, h4 {
            color: var(--secondary);
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        p { margin-bottom: 1rem; }
        a { text-decoration: none; color: inherit; transition: 0.3s; }
        section { padding: 100px 0; position: relative; }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* --- UTILITIES --- */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2rem;
            border-radius: var(--radius);
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            border: none;
            font-size: 1rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }
        .btn-primary:hover { background-color: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3); }

        .btn-secondary {
            background-color: white;
            color: var(--secondary);
            border: 2px solid #e2e8f0;
        }
        .btn-secondary:hover { border-color: var(--secondary); background-color: #f8fafc; }

        .badge {
            display: inline-block;
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            background-color: #eff6ff;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            border: 1px solid #dbeafe;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* --- NAVIGATION --- */
        nav {
            position: fixed;
            top: 0; left: 0; width: 100%;
            padding: 1.2rem 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            z-index: 1000;
            display: flex; justify-content: space-between; align-items: center;
            transition: all 0.3s ease;
        }
        nav.scrolled { padding: 0.8rem 2rem; background: rgba(255, 255, 255, 0.95); box-shadow: var(--shadow-sm); }

        .logo { font-size: 1.5rem; font-weight: 800; color: var(--secondary); letter-spacing: -1px; }
        .logo span { color: var(--primary); }
        
        .nav-menu { display: flex; gap: 2.5rem; margin: 0 2rem; }
        .nav-link { font-weight: 600; color: var(--text-muted); font-size: 0.95rem; }
        .nav-link:hover { color: var(--primary); }

        /* --- HERO SECTION --- */
        .hero {
            padding-top: 180px;
            padding-bottom: 120px;
            background: 
                radial-gradient(circle at 10% 20%, rgba(37, 99, 235, 0.05) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 20%);
        }

        .hero-grid {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 5rem;
            align-items: center;
        }

        .hero-content h1 { font-size: 4.5rem; margin-bottom: 1.5rem; letter-spacing: -2px; line-height: 1.1; }
        .hero-content p { font-size: 1.35rem; color: var(--text-muted); margin-bottom: 3rem; max-width: 550px; line-height: 1.6; }

        /* Dynamic App Mockup */
        .app-window {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transform: perspective(1200px) rotateY(-8deg) rotateX(4deg);
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
        }
        .hero-image:hover .app-window { transform: perspective(1200px) rotateY(0deg) rotateX(0deg) scale(1.02); }

        .app-sidebar { width: 60px; background: var(--secondary); height: 100%; position: absolute; left: 0; top: 0; display: flex; flex-direction: column; align-items: center; padding-top: 20px; gap: 20px; }
        .sidebar-icon { width: 24px; height: 24px; background: rgba(255,255,255,0.1); border-radius: 6px; }
        .sidebar-icon.active { background: var(--primary); }

        .app-main { margin-left: 60px; padding: 25px; background: #f8fafc; height: 400px; display: flex; flex-direction: column; gap: 20px; }
        .app-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .skeleton-title { width: 120px; height: 24px; background: #cbd5e1; border-radius: 6px; }
        
        .stat-card-row { display: flex; gap: 15px; }
        .stat-card-mock { flex: 1; background: white; padding: 15px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid #e2e8f0; }
        .mock-num { font-size: 1.5rem; font-weight: 800; color: var(--secondary); margin-top: 10px; }
        .mock-label { font-size: 0.8rem; color: var(--text-muted); }

        .traffic-graph { flex: 1; background: white; border-radius: 12px; border: 1px solid #e2e8f0; position: relative; overflow: hidden; }
        .graph-line { position: absolute; bottom: 0; left: 0; width: 100%; height: 60%; background: linear-gradient(to top, rgba(37,99,235,0.1), transparent); clip-path: polygon(0 100%, 0 40%, 20% 60%, 40% 30%, 60% 50%, 80% 20%, 100% 40%, 100% 100%); }

        /* --- FEATURES (DETAILED) --- */
        .features-section { background: white; }
        
        .feature-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6rem; align-items: center; margin-bottom: 8rem;
        }
        .feature-row.reverse { direction: rtl; } 
        .feature-row.reverse .feature-text { direction: ltr; } /* Reset text direction */

        .feature-icon-lg { 
            width: 64px; height: 64px; border-radius: 16px; background: #eff6ff; color: var(--primary); 
            display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 2rem;
        }

        .feature-text h3 { font-size: 2.5rem; margin-bottom: 1.5rem; }
        .feature-text p { font-size: 1.15rem; color: var(--text-muted); margin-bottom: 2rem; }
        
        .feature-list li {
            display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; font-weight: 500; font-size: 1.1rem;
        }
        .feature-list i { color: #22c55e; background: #dcfce7; padding: 5px; border-radius: 50%; font-size: 0.8rem; }

        .feature-img {
            background: #f1f5f9; border-radius: 24px; height: 400px; width: 100%; position: relative; overflow: hidden;
        }
        .feature-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .feature-img:hover img { transform: scale(1.05); }

        /* --- FAQ ACCORDION --- */
        .faq-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 0;
            cursor: pointer;
        }
        .faq-question {
            display: flex; justify-content: space-between; align-items: center; font-weight: 700; font-size: 1.2rem;
        }
        .faq-answer {
            max-height: 0; overflow: hidden; transition: max-height 0.3s ease; color: var(--text-muted); line-height: 1.6;
        }
        .faq-answer.open { max-height: 200px; margin-top: 1rem; }
        .faq-icon { transition: 0.3s; }
        .faq-item.active .faq-icon { transform: rotate(180deg); }

        /* --- STATS & NUMBERS --- */
        .stats-bar {
            background: var(--secondary); color: white; padding: 6rem 0;
        }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); text-align: center; gap: 2rem; }
        .stat-val { font-size: 3.5rem; font-weight: 800; color: var(--accent); margin-bottom: 0.5rem; }
        .stat-label { font-size: 1.1rem; color: #94a3b8; font-weight: 500; }

        /* --- CONTACT --- */
        .contact-wrapper {
            background: #fff; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; display: flex;
        }
        .contact-info { background: var(--primary); color: white; padding: 4rem; width: 40%; display: flex; flex-direction: column; justify-content: space-between; }
        .contact-form { padding: 4rem; width: 60%; }

        input, textarea, select {
            width: 100%; padding: 1rem; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; font-family: inherit; margin-bottom: 1.5rem; transition: 0.3s;
        }
        input:focus, textarea:focus { outline: none; border-color: var(--primary); background: white; }

        /* --- FOOTER --- */
        footer { margin-top: 5rem; background: #f8fafc; padding: 5rem 0; border-top: 1px solid #e2e8f0; }

        /* Responsive */
        @media (max-width: 900px) {
            .hero-grid, .feature-row, .contact-wrapper, .stats-grid { grid-template-columns: 1fr; }
            .hero-content h1 { font-size: 3rem; }
            .feature-row { gap: 3rem; margin-bottom: 4rem; }
            .contact-info { width: 100%; padding: 2rem; }
            .contact-form { width: 100%; padding: 2rem; }
            .stats-grid { gap: 3rem; }
            .feature-row.reverse { direction: ltr; }
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav id="navbar">
        <a href="#" class="logo">Pro<span>Parking</span>.</a>
        <div class="nav-menu">
            <a href="#features" class="nav-link">Funkciók</a>
            <a href="#howitworks" class="nav-link">Működés</a>
            <a href="#faq" class="nav-link">GYIK</a>
            <a href="#reviews" class="nav-link">Vélemények</a>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="#contact" class="btn btn-primary">Kapcsolat</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content" data-aos="fade-up">
                    <span class="badge">Vállalati Parkolásmenedzsment V4.0</span>
                    <h1>A Parkolója.<br><span class="gradient-text">Teljesen Automatizálva.</span></h1>
                    <p>
                        Felejtse el a távirányítókat és a papíralapú adminisztrációt. 
                        A ProParking komplett megoldást kínál rendszámfelismeréssel, vendégkezeléssel és valós idejű analitikával.
                    </p>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="#contact" class="btn btn-primary">Ingyenes Demó Kérése</a>
                        <a href="#features" class="btn btn-secondary">Tudjon meg többet</a>
                    </div>
                    <div style="margin-top: 2rem; display: flex; align-items: center; gap: 1rem; color: var(--text-muted); font-size: 0.9rem;">
                        <span><i class="fas fa-check-circle" style="color:#22c55e"></i> Nincs Setup Díj</span>
                        <span><i class="fas fa-check-circle" style="color:#22c55e"></i> 14 Napos Próba</span>
                    </div>
                </div>
                
                <div class="hero-image" data-aos="fade-left" data-aos-delay="200">
                    <div class="app-window">
                        <div class="app-sidebar">
                            <div class="sidebar-icon active"></div>
                            <div class="sidebar-icon"></div>
                            <div class="sidebar-icon"></div>
                        </div>
                        <div class="app-main">
                            <div class="app-header-row">
                                <div class="skeleton-title"></div>
                                <div class="badge" style="margin:0; font-size:0.7rem;">LIVE</div>
                            </div>
                            <div class="stat-card-row">
                                <div class="stat-card-mock">
                                    <div class="mock-label">Mai Belépések</div>
                                    <div class="mock-num counter" data-target="1245">0</div>
                                </div>
                                <div class="stat-card-mock">
                                    <div class="mock-label">Aktív Helyek</div>
                                    <div class="mock-num counter" data-target="42">0</div>
                                </div>
                            </div>
                            <div class="traffic-graph">
                                <div class="graph-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS BAR -->
    <section class="stats-bar">
        <div class="container">
            <div class="stats-grid">
                <div data-aos="zoom-in">
                    <div class="stat-val counter" data-target="99">0%</div>
                    <div class="stat-label">Pontosság LPR-ben</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="100">
                    <div class="stat-val counter" data-target="500">0+</div>
                    <div class="stat-label">Felszerelt Kamera</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="200">
                    <div class="stat-val counter" data-target="12000">0</div>
                    <div class="stat-label">Napi Tranzakció</div>
                </div>
                <div data-aos="zoom-in" data-aos-delay="300">
                    <div class="stat-val counter" data-target="24">0/7</div>
                    <div class="stat-label">Technikai Támogatás</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="features-section">
        <div class="container">
            <!-- Feature 1 -->
            <div class="feature-row">
                <div class="feature-text" data-aos="fade-right">
                    <div class="feature-icon-lg"><i class="fas fa-eye"></i></div>
                    <h3>Mesterséges Intelligencia a Kapuban</h3>
                    <p>
                        Saját fejlesztésű LPR (License Plate Recognition) algoritmusunk 0.2 másodperc alatt azonosítja a járműveket, 
                        még rossz látási viszonyok között vagy sáros rendszám esetén is.
                    </p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Azonnali sorompónyitás</li>
                        <li><i class="fas fa-check"></i> Integráció meglévő kamerákkal</li>
                        <li><i class="fas fa-check"></i> GDPR-kompatibilis adattárolás</li>
                    </ul>
                </div>
                <div class="feature-img" data-aos="fade-left">
                    <!-- Placeholder for Feature Image -->
                    <div style="width:100%; height:100%; background: linear-gradient(135deg, #e0f2fe, #bfdbfe); display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-car-side" style="font-size:8rem; color: #60a5fa; opacity:0.5;"></i>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="feature-row reverse">
                <div class="feature-text" data-aos="fade-left">
                    <div class="feature-icon-lg"><i class="fas fa-users-cog"></i></div>
                    <h3>Vendégkezelés Egyszerűen</h3>
                    <p>
                        Várjon vendégeket profin. Küldjön meghívót emailben, és a rendszer automatikusan beengedi őket a megadott időintervallumban.
                        Nincs több telefonálgatás a portára.
                    </p>
                    <ul class="feature-list">
                        <li><i class="fas fa-check"></i> Email és SMS értesítések</li>
                        <li><i class="fas fa-check"></i> Előre foglalt parkolóhelyek</li>
                        <li><i class="fas fa-check"></i> Valós idejű érkezési jelzés</li>
                    </ul>
                </div>
                <div class="feature-img" data-aos="fade-right">
                    <div style="width:100%; height:100%; background: linear-gradient(135deg, #f0fdf4, #bbf7d0); display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-mobile-alt" style="font-size:8rem; color: #4ade80; opacity:0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" style="background: #f8fafc;">
        <div class="container" style="max-width: 800px;">
            <div style="text-align:center; margin-bottom: 4rem;">
                <h2 style="font-size:2.5rem; margin-bottom:1rem;">Gyakori Kérdések</h2>
                <p style="color:var(--text-muted)">Minden, amit a telepítésről és üzemeltetésről tudni érdemes.</p>
            </div>

            <div class="faq-item active" onclick="toggleFaq(this)">
                <div class="faq-question">
                    Szükséges új kamerákat vásárolnom?
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer open">
                    Nem feltétlenül. A ProParking szoftvere kompatibilis a legtöbb modern IP kamerával (RTSP stream támogatás). 
                    Helyszíni felmérés során megvizsgáljuk meglévő eszközeit.
                </div>
            </div>
            
            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    Mennyi időbe telik a rendszer bevezetése?
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Egy átlagos irodaház esetén a telepítés és konfiguráció 1-2 munkanapot vesz igénybe. A felhasználók oktatása online történik.
                </div>
            </div>

            <div class="faq-item" onclick="toggleFaq(this)">
                <div class="faq-question">
                    Integrálható a bérszámfejtéssel?
                    <i class="fas fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Igen, API-n keresztül összeköthető HR rendszerekkel, így a parkolási díjak automatikusan levonhatók a bérből.
                </div>
            </div>
        </div>
    </section>

    <!-- REVIEWS -->
    <section id="reviews">
        <div class="container">
            <h2 style="text-align:center; font-size:2.5rem; margin-bottom:3rem;">Ügyfeleink Véleménye</h2>
            
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:2rem; margin-bottom:4rem;">
                <?php if (!empty($reviews)):
    foreach ($reviews as $rev): ?>
                    <div style="background:white; padding:2rem; border-radius:16px; border:1px solid #e2e8f0; box-shadow: var(--shadow-sm);" data-aos="fade-up">
                        <div style="color: #f59e0b; margin-bottom:1rem; font-size:0.9rem;">
                            <?php for ($i = 0; $i < $rev['ecsillag']; $i++)
            echo '<i class="fas fa-star"></i>'; ?>
                        </div>
                        <p style="font-style:italic; color:#475569;">"<?php echo htmlspecialchars($rev['ekomment']); ?>"</p>
                        <div style="margin-top:1.5rem; font-weight:700; font-size:0.9rem;">
                            <?php echo htmlspecialchars($rev['enev']); ?>
                        </div>
                    </div>
                <?php
    endforeach;
endif; ?>
            </div>

            <!-- Review Form -->
             <div style="background:var(--bg-light); padding:3rem; border-radius:20px; max-width:700px; margin:0 auto; text-align:center;">
                <h3 style="margin-bottom:1rem;">Írjon Véleményt</h3>
                <?php if (isset($_SESSION['review_success'])): ?>
                    <p style="color:green; margin-bottom:1rem;"><?php echo $_SESSION['review_success'];
    unset($_SESSION['review_success']); ?></p>
                <?php
endif; ?>
                <form method="POST" style="display:flex; flex-direction:column; gap:1rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <input type="text" name="reviewName" placeholder="Név / Cégnév" required>
                        <select name="rating">
                            <option value="5">5 Csillag - Kiváló</option>
                            <option value="4">4 Csillag - Jó</option>
                            <option value="3">3 Csillag</option>
                        </select>
                    </div>
                    <textarea name="reviewText" rows="3" placeholder="Ossza meg tapasztalatait..." required></textarea>
                    <button type="submit" name="submit_review" class="btn btn-secondary" style="width:100%;">Értékelés Küldése</button>
                </form>
             </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" style="background: #f1f5f9;">
        <div class="container">
            <div class="contact-wrapper" data-aos="zoom-in">
                <div class="contact-info">
                    <div>
                        <h3 style="color:white; font-size:2rem; margin-bottom:1rem;">Lépjen Szintet.</h3>
                        <p style="color:#bfdbfe;">Szakértőink segítenek a megfelelő csomag kiválasztásában.</p>
                    </div>
                    <div>
                        <div style="margin-bottom:1rem;"><i class="fas fa-envelope"></i> hello@proparking.hu</div>
                        <div style="margin-bottom:1rem;"><i class="fas fa-phone"></i> +36 1 234 5678</div>
                        <div><i class="fas fa-map-marker-alt"></i> 1051 Budapest, Tech Park 1.</div>
                    </div>
                </div>
                <div class="contact-form">
                    <?php if (isset($_SESSION['msg_success'])): ?>
                        <div style="background:#dcfce7; padding:1rem; border-radius:8px; margin-bottom:1rem; color:#166534; text-align:center;">
                            <?php echo $_SESSION['msg_success'];
    unset($_SESSION['msg_success']); ?>
                        </div>
                    <?php
endif; ?>

                    <form method="POST">
                        <label>Az Ön neve</label>
                        <input type="text" name="name" required placeholder="Teljes név">
                        
                        <label>Vállalati email cím</label>
                        <input type="email" name="email" required placeholder="nev@ceg.hu">
                        
                        <label>Üzenet</label>
                        <textarea name="message" rows="4" required placeholder="Miben segíthetünk?"></textarea>
                        
                        <button type="submit" name="send_inquiry" class="btn btn-primary" style="width:100%;">Küldés</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:2rem;">
                <div>
                    <div class="logo">Pro<span>Parking</span></div>
                    <p style="margin-top:0.5rem;">Hungary's #1 Access Control Solution</p>
                </div>
                <div style="font-size:0.9rem;">
                    &copy; <?php echo date('Y'); ?> Minden jog fenntartva. <a href="#">Adatkezelés</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // 1. AOS Init
        AOS.init({ duration: 800, offset: 50, once: true });

        // 2. Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if(window.scrollY > 50) nav.classList.add('scrolled');
            else nav.classList.remove('scrolled');
        });

        // 3. Counter Animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200; 
        
        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText.replace('+','').replace('%','');
                    const inc = target / speed;
                    
                    if(count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 20);
                    } else {
                        // Append suffixes back if needed
                        if(counter.getAttribute('data-target') === '99') counter.innerText = target + '%';
                        else if(counter.getAttribute('data-target') === '500') counter.innerText = target + '+';
                        else counter.innerText = target;
                    }
                }
                updateCount();
            });
        };

        // Trigger counters when Stats section is in view
        ScrollTrigger.create({
            trigger: ".stats-bar",
            start: "top 80%",
            onEnter: animateCounters
        });

        // 4. FAQ Accordion Logic
        function toggleFaq(element) {
            // Close all others
            document.querySelectorAll('.faq-item').forEach(item => {
                if(item !== element) {
                    item.classList.remove('active');
                    item.querySelector('.faq-answer').classList.remove('open');
                }
            });
            
            // Toggle current
            element.classList.toggle('active');
            const answer = element.querySelector('.faq-answer');
            answer.classList.toggle('open');
        }

        // 5. Dynamic Hero Graph (Simple visual update loop)
        const skelChart = document.querySelector('.skeleton-chart');
        if(skelChart) {
            // Just a visual placeholder, but we could animate bars here if needed
        }
    </script>
</body>
</html>
