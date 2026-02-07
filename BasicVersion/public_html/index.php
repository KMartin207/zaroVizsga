<?php
require_once 'db.php';
session_start();

$database = new Database();
$conn = $database->getConnection();

// Értékelés beküldése
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    // Adatok feldolgozása
    $nev = htmlspecialchars(trim($_POST['reviewName']));
    $email = htmlspecialchars(trim($_POST['reviewEmail']));
    $csillag = intval($_POST['rating']);
    $komment = htmlspecialchars(trim($_POST['reviewText']));
    
    // Validáció
    $errors = [];
    
    if (empty($nev) || strlen($nev) < 2) {
        $errors[] = "A név minimum 2 karakter hosszú legyen.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Kérjük, érvényes email címet adj meg.";
    }
    
    if ($csillag < 1 || $csillag > 5) {
        $errors[] = "Az értékelés 1-5 csillag között lehet.";
    }
    
    if (empty($komment) || strlen($komment) < 10) {
        $errors[] = "A komment minimum 10 karakter hosszú legyen.";
    }
    
    if (strlen($nev) > 100) {
        $errors[] = "A név túl hosszú (max 100 karakter).";
    }
    
    if (strlen($komment) > 1000) {
        $errors[] = "A komment túl hosszú (max 1000 karakter).";
    }
    
    // Ha nincs hiba, mentés
    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("INSERT INTO ertekeles (enev, ecsillag, ekomment) VALUES (:nev, :csillag, :komment)");
            $stmt->bindParam(':nev', $nev);
            $stmt->bindParam(':csillag', $csillag);
            $stmt->bindParam(':komment', $komment);
            
            if ($stmt->execute()) {
                $_SESSION['review_success'] = "✅ Köszönjük az értékelésed!";
                header("Location: " . $_SERVER['PHP_SELF'] . "#reviews");
                exit();
            }
        } catch(PDOException $e) {
            $errors[] = "Hiba történt az értékelés mentése során: " . $e->getMessage();
        }
    }
    
    // Hibaüzenetek mentése
    if (!empty($errors)) {
        $_SESSION['review_errors'] = $errors;
        $_SESSION['review_data'] = [
            'nev' => $nev, 
            'email' => $email,
            'csillag' => $csillag, 
            'komment' => $komment
        ];
        header("Location: " . $_SERVER['PHP_SELF'] . "#reviews");
        exit();
    }
}

// Értékelések lekérdezése az adatbázisból
try {
    $stmt = $conn->query("SELECT * FROM ertekeles ORDER BY edatum DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $reviews = [];
}

// Átlagos értékelés számítása
try {
    $stmt = $conn->query("SELECT AVG(ecsillag) as avg_rating, COUNT(*) as total_reviews FROM ertekeles");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    $avg_rating = round($stats['avg_rating'] ?? 0, 1);
    $total_reviews = $stats['total_reviews'] ?? 0;
} catch(PDOException $e) {
    $avg_rating = 0;
    $total_reviews = 0;
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProParking NextGen | A Jövő Parkolása</title>
    
    <!-- Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/ScrollTrigger.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #050a14;
            --primary: #00f2fe;
            --secondary: #4facfe;
            --accent: #ff0055;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-main: #ffffff;
            --text-muted: #a0a0a0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--secondary);
            border-radius: 4px;
        }

        /* Utility Classes */
        .glass-panel {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            outline: none;
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            color: var(--bg-color);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.3);
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 0 40px rgba(0, 242, 254, 0.6);
        }

        /* Hero Section */
        #hero {
            height: 100vh;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        #hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            background: radial-gradient(circle at center, #1a2a3a 0%, #050a14 70%);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            opacity: 0; /* Animated in */
            transform: translateY(30px);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            opacity: 0;
            transform: translateY(30px);
        }

        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.4;
            animation: float 20s infinite ease-in-out;
        }
        
        /* 3D Demo Section */
        #demo-section {
            min-height: 100vh;
            position: relative;
            padding: 4rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #canvas-container {
            width: 100%;
            max-width: 1200px;
            height: 600px;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            position: relative;
            border: 1px solid var(--glass-border);
        }

        .demo-overlay {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 1rem;
            background: rgba(0,0,0,0.6);
            padding: 1rem 2rem;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            z-index: 10;
        }

        .color-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 2px solid white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .color-btn:hover { transform: scale(1.2); }

        /* Features */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 4rem auto;
            padding: 0 2rem;
        }

        .feature-card {
            padding: 2.5rem;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }

        /* Interactive Tools */
        .tools-section {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .tool-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
        }

        .calculator {
            padding: 2rem;
        }

        .calc-input {
            width: 100%;
            margin: 1.5rem 0;
        }
        
        .price-display {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
        }

        .live-map {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            padding: 1rem;
        }

        .parking-spot {
            aspect-ratio: 1;
            border-radius: 8px;
            background: #1a1a1a;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: background 0.3s;
        }

        .spot-free { background: rgba(46, 204, 113, 0.2); border-color: #2ecc71; color: #2ecc71; }
        .spot-occupied { background: rgba(231, 76, 60, 0.2); border-color: #e74c3c; color: #e74c3c; }

        /* Reviews */
        .reviews-section {
            padding: 5rem 2rem;
            background: linear-gradient(to top, #050a14, #0a1525);
        }

        .review-card {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .form-control {
            width: 100%;
            padding: 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 10px;
            margin-bottom: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, -30px); }
        }

        /* Message Styles */
        .message {
            max-width: 600px;
            margin: 1rem auto;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }
        .message.success { background: rgba(46, 204, 113, 0.2); border: 1px solid #2ecc71; color: #2ecc71; }
        .message.error { background: rgba(231, 76, 60, 0.2); border: 1px solid #e74c3c; color: #e74c3c; }

    </style>
</head>
<body>

    <!-- PHP Messages -->
    <?php if (isset($_SESSION['review_success'])): ?>
        <div style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;" class="message success">
            <?php echo $_SESSION['review_success']; unset($_SESSION['review_success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['review_errors'])): ?>
        <div style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 1000;" class="message error">
            <?php foreach ($_SESSION['review_errors'] as $error) { echo "<div>$error</div>"; } unset($_SESSION['review_errors']); ?>
        </div>
    <?php endif; ?>

    <!-- Bg Orbs -->
    <div class="floating-orb" style="width:500px; height:500px; background:var(--primary); top:-100px; left:-100px;"></div>
    <div class="floating-orb" style="width:400px; height:400px; background:var(--accent); bottom:10%; right:-100px; animation-delay: -5s;"></div>

    <!-- Hero -->
    <section id="hero">
        <div id="hero-bg"></div>
        <div class="hero-content">
            <h1 class="hero-title">ProParking <span class="gradient-text">NextGen</span></h1>
            <p class="hero-subtitle">Experience the future of intelligent parking management with 3D visualization and real-time AI analytics.</p>
            <button class="btn btn-primary" onclick="document.getElementById('demo-section').scrollIntoView({behavior: 'smooth'})">
                Kipróbálom a 3D Demót
            </button>
        </div>
    </section>

    <!-- 3D Demo -->
    <section id="demo-section">
        <h2 style="font-size: 2.5rem; margin-bottom: 2rem;">Interaktív <span class="gradient-text">3D Vizualizáció</span></h2>
        <div id="canvas-container">
            <!-- Three.js Canvas will be here -->
            <div class="demo-overlay">
                <div class="color-btn" style="background:#ff0055" onclick="changeCarColor(0xff0055)"></div>
                <div class="color-btn" style="background:#00f2fe" onclick="changeCarColor(0x00f2fe)"></div>
                <div class="color-btn" style="background:#f1c40f" onclick="changeCarColor(0xf1c40f)"></div>
                <div class="color-btn" style="background:#ffffff" onclick="changeCarColor(0xffffff)"></div>
            </div>
        </div>
        <p style="margin-top: 1rem; color: var(--text-muted);">Húzd a egeret a forgatáshoz • Görgess a nagyításhoz</p>
    </section>

    <!-- Stats & Tools -->
    <section class="tools-section">
        <div class="tool-container">
            <!-- Calculator -->
            <div class="glass-panel calculator">
                <h3><i class="fas fa-calculator"></i> Parkolási Díj Kalkulátor</h3>
                <p style="color: var(--text-muted); margin-bottom: 2rem;">Becsült költség számítása valós időben</p>
                
                <label>Időtartam: <span id="hours-display" style="color:var(--primary)">2</span> óra</label>
                <input type="range" min="1" max="24" value="2" class="calc-input" id="time-slider">
                
                <div style="display:flex; justify-content: space-between; align-items: flex-end;">
                    <div>
                        <small style="color:var(--text-muted)">Zóna: Belváros (450 Ft/ó)</small>
                    </div>
                    <div class="price-display"><span id="total-price">900</span> <span style="font-size:1.5rem">Ft</span></div>
                </div>
            </div>

            <!-- Live Map -->
            <div class="glass-panel calculator">
                <div style="display:flex; justify-content:space-between; margin-bottom: 1rem;">
                    <h3><i class="fas fa-wifi"></i> Élő Foglaltság (Szimuláció)</h3>
                    <div style="display:flex; gap:10px; font-size:0.8rem;">
                        <span style="color:#2ecc71">● Szabad</span>
                        <span style="color:#e74c3c">● Foglalt</span>
                    </div>
                </div>
                <div class="live-map" id="map-grid">
                    <!-- JS generated spots -->
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <div class="features-grid">
        <div class="glass-panel feature-card">
            <i class="fas fa-brain feature-icon"></i>
            <h3>AI Vezérlés</h3>
            <p style="color: var(--text-muted); margin-top: 1rem;">Mesterséges intelligencia optimalizálja a helykihasználást.</p>
        </div>
        <div class="glass-panel feature-card">
            <i class="fas fa-bolt feature-icon" style="color: var(--secondary)"></i>
            <h3>Villámgyors</h3>
            <p style="color: var(--text-muted); margin-top: 1rem;">Azonnali sorompónyitás rendszámfelismeréssel.</p>
        </div>
        <div class="glass-panel feature-card">
            <i class="fas fa-cloud feature-icon" style="color: var(--accent)"></i>
            <h3>Felhő Alapú</h3>
            <p style="color: var(--text-muted); margin-top: 1rem;">Minden adatod biztonságban, bárhonnan elérhetően.</p>
        </div>
    </div>

    <!-- Reviews (PHP) -->
    <section class="reviews-section" id="reviews">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2 style="text-align: center; margin-bottom: 3rem;">Ügyfél <span class="gradient-text">Visszajelzések</span></h2>

            <!-- Review Stats -->
            <div class="glass-panel" style="padding: 2rem; display: flex; align-items: center; justify-content: center; margin-bottom: 3rem; gap: 2rem;">
                <div style="font-size: 3rem; font-weight: 900; color: #f1c40f;"><?php echo $avg_rating; ?></div>
                <div>
                    <div style="color: #f1c40f; letter-spacing: 5px; font-size: 1.5rem;">
                        <?php for($i=1;$i<=5;$i++) echo $i<=round($avg_rating)?'★':'☆'; ?>
                    </div>
                    <div style="color: var(--text-muted);"><?php echo $total_reviews; ?> értékelés alapján</div>
                </div>
            </div>

            <!-- Existing Reviews -->
            <div style="max-height: 500px; overflow-y: auto; padding-right: 10px; margin-bottom: 3rem;">
                <?php if (!empty($reviews)): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="glass-panel review-card">
                            <div style="display:flex; justify-content:space-between; margin-bottom: 0.5rem;">
                                <strong style="color: var(--secondary); font-size: 1.1rem;"><?php echo htmlspecialchars($review['enev']); ?></strong>
                                <span style="color: #f1c40f;"><?php for($i=0;$i<$review['ecsillag'];$i++) echo '★'; ?></span>
                            </div>
                            <p style="color: #ddd; font-style: italic;">"<?php echo htmlspecialchars($review['ekomment']); ?>"</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-muted);">Még nincsenek értékelések.</p>
                <?php endif; ?>
            </div>

            <!-- Review Form -->
            <div class="glass-panel" style="padding: 3rem;">
                <h3 style="margin-bottom: 2rem;">Írj véleményt</h3>
                <form method="POST" action="">
                    <input type="text" name="reviewName" class="form-control" placeholder="Neved" required 
                           value="<?php echo isset($_SESSION['review_data']['nev']) ? htmlspecialchars($_SESSION['review_data']['nev']) : ''; ?>">
                    
                    <input type="email" name="reviewEmail" class="form-control" placeholder="Email címed" required
                           value="<?php echo isset($_SESSION['review_data']['email']) ? htmlspecialchars($_SESSION['review_data']['email']) : ''; ?>">
                    
                    <textarea name="reviewText" class="form-control" rows="4" placeholder="Véleményed..." required><?php echo isset($_SESSION['review_data']['komment']) ? htmlspecialchars($_SESSION['review_data']['komment']) : ''; ?></textarea>
                    
                    <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                        <span>Értékelés:</span>
                        <select name="rating" class="form-control" style="width: auto; margin:0;">
                            <option value="5">5 Csillag</option>
                            <option value="4">4 Csillag</option>
                            <option value="3">3 Csillag</option>
                            <option value="2">2 Csillag</option>
                            <option value="1">1 Csillag</option>
                        </select>
                    </div>

                    <button type="submit" name="submit_review" class="btn btn-primary" style="width: 100%;">Beküldés</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="text-align: center; padding: 2rem; color: var(--text-muted); border-top: 1px solid var(--glass-border);">
        <p>&copy; <?php echo date('Y'); ?> ProParking Systems. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script>
        // Init GSAP
        gsap.registerPlugin(ScrollTrigger);

        // Hero Animation
        gsap.to(".hero-title", { opacity: 1, y: 0, duration: 1, delay: 0.2 });
        gsap.to(".hero-subtitle", { opacity: 1, y: 0, duration: 1, delay: 0.5 });

        // Scroll Animations
        gsap.utils.toArray('.feature-card').forEach((card, i) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%"
                },
                y: 50,
                opacity: 0,
                duration: 0.8,
                delay: i * 0.2
            });
        });

        // --- THREE.JS 3D DEMO ---
        const init3D = () => {
            const container = document.getElementById('canvas-container');
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0x0a1525);
            
            // Fog for depth
            scene.fog = new THREE.FogExp2(0x0a1525, 0.02);

            const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
            camera.position.set(5, 5, 7);
            camera.lookAt(0, 0, 0);

            const renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(container.clientWidth, container.clientHeight);
            renderer.shadowMap.enabled = true;
            container.appendChild(renderer.domElement);

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
            scene.add(ambientLight);

            const spotLight = new THREE.SpotLight(0x00f2fe, 1);
            spotLight.position.set(10, 20, 10);
            spotLight.castShadow = true;
            scene.add(spotLight);

            const accentList = new THREE.PointLight(0xff0055, 0.8);
            accentList.position.set(-10, 5, -5);
            scene.add(accentList);

            // Floor
            const gridHelper = new THREE.GridHelper(50, 50, 0x4facfe, 0x2c3e50);
            scene.add(gridHelper);

            // Car Group
            const car = new THREE.Group();
            
            // Car Body (Stylized Cybertruck-ish)
            const bodyGeo = new THREE.BoxGeometry(4, 1.2, 2);
            const bodyMat = new THREE.MeshPhongMaterial({ color: 0x00f2fe, shininess: 100 });
            const body = new THREE.Mesh(bodyGeo, bodyMat);
            body.position.y = 1;
            body.castShadow = true;
            car.add(body);

            // Cabin
            const cabinGeo = new THREE.BoxGeometry(2.5, 0.8, 1.8);
            const cabinMat = new THREE.MeshPhongMaterial({ color: 0x111111 });
            const cabin = new THREE.Mesh(cabinGeo, cabinMat);
            cabin.position.set(-0.2, 2, 0);
            car.add(cabin);

            // Wheels
            const wheelGeo = new THREE.CylinderGeometry(0.5, 0.5, 0.4, 32);
            const wheelMat = new THREE.MeshStandardMaterial({ color: 0x333333 });
            
            const createWheel = (x, z) => {
                const wheel = new THREE.Mesh(wheelGeo, wheelMat);
                wheel.rotation.x = Math.PI / 2;
                wheel.position.set(x, 0.5, z);
                car.add(wheel);
            };

            createWheel(1.2, 1);
            createWheel(1.2, -1);
            createWheel(-1.2, 1);
            createWheel(-1.2, -1);

            scene.add(car);

            // Store car mesh for color changing
            window.carBodyMesh = body;

            // Loop
            let time = 0;
            const animate = () => {
                requestAnimationFrame(animate);

                // Idle Animation
                time += 0.005;
                car.position.y = Math.sin(time * 2) * 0.1; 
                car.rotation.y = Math.sin(time) * 0.2;

                renderer.render(scene, camera);
            };
            animate();

            // Resize handle
            window.addEventListener('resize', () => {
                const width = container.clientWidth;
                const height = container.clientHeight;
                renderer.setSize(width, height);
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
            });

            // Interactive: Mouse Orbit (Simple version)
            let isDragging = false;
            let previousMousePosition = { x: 0, y: 0 };

            container.addEventListener('mousedown', () => isDragging = true);
            container.addEventListener('mouseup', () => isDragging = false);
            container.addEventListener('mousemove', (e) => {
                if(isDragging) {
                    const deltaX = e.offsetX - previousMousePosition.x;
                    car.rotation.y += deltaX * 0.01;
                }
                previousMousePosition = { x: e.offsetX, y: e.offsetY };
            });
        };

        // Change Car Color
        window.changeCarColor = (hex) => {
            if(window.carBodyMesh) {
                gsap.to(window.carBodyMesh.material.color, {
                    r: new THREE.Color(hex).r,
                    g: new THREE.Color(hex).g,
                    b: new THREE.Color(hex).b,
                    duration: 0.5
                });
            }
        };

        init3D();

        // --- Interactive Tools JS ---
        
        // Calculator
        const slider = document.getElementById('time-slider');
        const hoursDisplay = document.getElementById('hours-display');
        const totalPrice = document.getElementById('total-price');
        const hourlyRate = 450;

        slider.addEventListener('input', (e) => {
            const val = e.target.value;
            hoursDisplay.innerText = val;
            
            // Animate number
            const targetPrice = val * hourlyRate;
            gsap.to(totalPrice, {
                innerText: targetPrice,
                duration: 0.5,
                snap: { innerText: 1 }
            });
        });

        // Live Map Simulation
        const mapGrid = document.getElementById('map-grid');
        for(let i=0; i<20; i++) {
            const spot = document.createElement('div');
            spot.className = 'parking-spot spot-free';
            spot.innerText = 'P' + (i+1);
            mapGrid.appendChild(spot);
        }

        setInterval(() => {
            const spots = document.querySelectorAll('.parking-spot');
            const randomSpot = spots[Math.floor(Math.random() * spots.length)];
            
            if(randomSpot.classList.contains('spot-free')) {
                randomSpot.classList.replace('spot-free', 'spot-occupied');
                // Pulse effect
                gsap.fromTo(randomSpot, {scale: 0.8}, {scale: 1, duration: 0.4});
            } else {
                randomSpot.classList.replace('spot-occupied', 'spot-free');
            }
        }, 2000);

    </script>
</body>
</html>
