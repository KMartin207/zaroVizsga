
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
        /* Contact Page Styles */
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
        }

        .page-content h2 {
            color: #34495e;
            font-size: 1.8rem;
            margin: 2rem 0 1rem 0;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }

        .page-content h3 {
            color: #2c3e50;
            font-size: 1.3rem;
            margin: 1.5rem 0 0.5rem 0;
        }

        .page-content p {
            line-height: 1.6;
            color: #555;
            margin-bottom: 1rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
            padding: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #bdc3c7;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .submit-btn:hover {
            background: linear-gradient(135deg, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Contact Info Styles */

        .contact-info h3 {
            color: #2c3e50;
            margin-top: 0;
        }

        .contact-info p {
            margin-bottom: 1rem;
        }

        .contact-info strong {
            color: #2c3e50;
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

        /* Service Card Styles */
        .service-card {
            background: white;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 4px solid #3498db;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .service-card h3 {
            color: #2c3e50;
            margin-top: 0;
            font-size: 1.2rem;
        }

        .service-card p {
            margin-bottom: 0;
            color: #555;
        }

        /* Emergency Section */
        .page-content > div:has(> h3:contains("Vészhelyzet")) {
            background: #fff5f5;
            padding: 1.5rem;
            border-radius: 10px;
            border-left: 4px solid #e74c3c;
        }

        /* Partners Grid */
        .page-content > div:has(> div:last-child) {
            margin: 2rem 0;
        }

        .page-content > div:has(> div:last-child) > div {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 20px;
        }

        .page-content > div:has(> div:last-child) > div:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            padding: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 1rem;
            }
            
            .page-content > div:first-of-type {
                grid-template-columns: 1fr !important;
                gap: 2rem;
            }
            
            .page-content h1 {
                font-size: 2rem;
            }
            
            .page-content h2 {
                font-size: 1.5rem;
            }
            
            .page-content > div:has(> div:last-child) {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)) !important;
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
            
            .contact-info,
            .service-card {
                padding: 1rem;
            }
            
            .page-content > div:has(> div:last-child) {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        /* Form Validation Styles */
        .form-group input:invalid:not(:focus):not(:placeholder-shown),
        .form-group select:invalid:not(:focus),
        .form-group textarea:invalid:not(:focus):not(:placeholder-shown) {
            border-color: #e74c3c;
        }

        .form-group input:valid:not(:focus):not(:placeholder-shown),
        .form-group select:valid:not(:focus),
        .form-group textarea:valid:not(:focus):not(:placeholder-shown) {
            border-color: #27ae60;
        }

        /* Loading State */
        .submit-btn.loading {
            position: relative;
            color: transparent;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-right-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Success Message */
        .form-success {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin-bottom: 1rem;
            text-align: center;
        }

        /* Map Placeholder Enhancement */
        .page-content > div:has(> h3:contains("Találjon")) > div:last-child {
            background: linear-gradient(45deg, #f8f9fa 25%, transparent 25%), 
                        linear-gradient(-45deg, #f8f9fa 25%, transparent 25%), 
                        linear-gradient(45deg, transparent 75%, #f8f9fa 75%), 
                        linear-gradient(-45deg, transparent 75%, #f8f9fa 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        .page-content > div:has(> h3:contains("Találjon")) > div:last-child p {
            background: white;
            padding: 1rem 2rem;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        <h1>Kapcsolat</h1>
        
        <p>Kérjük, használja az alábbi űrlapot, hogy felvéthesse velünk a kapcsolatot. Szakértő csapatunk 24 órán belül válaszol minden megkeresésre. Várjuk kérdéseit, észrevételeit és együttműködési ajánlatait!</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin: 2rem 0;">
            <div>
                <h2>Írjon nekünk</h2>
                
                <form id="contactForm" style="margin-top: 1.5rem;">
                    <div class="form-group">
                        <label for="name">Név *</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email cím *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Telefonszám</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="company">Cég neve</label>
                        <input type="text" id="company" name="company">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Tárgy *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Válasszon...</option>
                            <option value="general">Általános információ</option>
                            <option value="sales">Érdeklődés a termékekről</option>
                            <option value="support">Technikai támogatás</option>
                            <option value="partnership">Együttműködési lehetőség</option>
                            <option value="career">Álláslehetőség</option>
                            <option value="other">Egyéb</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Üzenet *</label>
                        <textarea id="message" name="message" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" class="submit-btn">Üzenet küldése</button>
                </form>
            </div>
            
            <div>
                <h2>Elérhetőségeink</h2>
                
                <div class="contact-info">
                    <h3>ProParking Kft.</h3>
                    <p><strong>Cím:</strong><br>1117 Budapest,<br>Infopark sétány 1.</p>
                    
                    <p><strong>Telefonszám:</strong><br>+36 1 234 5678</p>
                    
                    <p><strong>Email:</strong><br>info@proparking.hu</p>
                    
                    <p><strong>Ügyfélszolgálat:</strong><br>Hétfő - Péntek: 8:00 - 18:00<br>Szombat: 9:00 - 14:00</p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3>Vészhelyzet esetén</h3>
                    <p>Technikai problémák esetén, amelyek sürgős beavatkozást igényelnek, kérjük, hívja a vészhelyzeti számunkat:</p>
                    <p style="font-size: 1.2rem; font-weight: bold; color: #e74c3c;">+36 30 123 4567</p>
                    <p style="font-size: 0.9rem; color: #666;">Ez a szám 0-24 elérhető technikai problémák esetén.</p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h3>Találjon meg minket</h3>
                    <div style="height: 300px; background: #f8f9fa; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-top: 1rem;">
                        <p style="color: #666;"><iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d674.9011288858097!2d19.055831569658732!3d47.419654705621326!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNDfCsDI1JzEwLjgiTiAxOcKwMDMnMjMuMyJF!5e0!3m2!1shu!2shu!4v1762952165801!5m2!1shu!2shu" width="400" height="250" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></p>
                    </div>
                </div>
            </div>
        </div>
        
        <h2>Gyakran Ismételt Kérdések</h2>
        
        <div style="margin: 2rem 0;">
            <div class="service-card">
                <h3>Mennyi idő alatt válaszol az ügyfélszolgálat?</h3>
                <p>Általános kérdésekre 24 órán belül válaszolunk. Sürgős technikai problémák esetén a vészhelyzeti vonalunkon azonnal segítünk.</p>
            </div>
            
            <div class="service-card">
                <h3>Milyen fizetési módokat fogad el a ProParking?</h3>
                <p>Elfogadunk banki átutalást, bankkártyás fizetést, és egyes esetekben lízing konstrukciót is kínálunk nagyobb megrendelések esetén.</p>
            </div>
            
            <div class="service-card">
                <h3>Van lehetőség próbaidőszakra?</h3>
                <p>Igen, kínálunk 30 napos próbaidőszakot a ProParking rendszerünkhöz. Ez alatt az idő alatt teljes körű támogatást és képzést biztosítunk.</p>
            </div>
            
            <div class="service-card">
                <h3>Milyen garanciát adnak a termékekre?</h3>
                <p>Minden hardver termékünkre 2 év gyártói garanciát adunk, a szoftverre pedig folyamatos frissítéseket és támogatást biztosítunk.</p>
            </div>
        </div>
        
        <h2>Partnereink és Referenciáink</h2>
        <p>A ProParking büszkén dolgozik együtt számos vezető vállalattal és intézménnyel. Íme néhány partnereink közül:</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2rem; margin: 2rem 0; text-align: center;">
            <div style="padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <p><strong>WestEnd</strong><br>Budapest</p>
            </div>
            <div style="padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <p><strong>Corvin Plaza</strong><br>Budapest</p>
            </div>
            <div style="padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <p><strong>MOM Park</strong><br>Budapest</p>
            </div>
            <div style="padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <p><strong>Budapest Airport</strong><br>BUD</p>
            </div>
            <div style="padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <p><strong>Magyar Telekom</strong><br>Székház</p>
            </div>
        </div>
        
        <div class="contact-info">
            <h3>Következő lépések</h3>
            <p>Ha készen áll a ProParking rendszer bevezetésére, kérjük, vegye fel velünk a kapcsolatot, és egy szakértő munkatársunk személyre szabott ajánlattal és bemutatóval szolgál.</p>
            <p>📞 Hívjon minket bizalommal: +36 1 234 5678</p>
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
    document.getElementById('contactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Köszönjük megkeresését! Hamarosan felvesszük Önnel a kapcsolatot.');
        this.reset();
    });
    </script>
</body>
</html>