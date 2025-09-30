<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <title>Smart Parking</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar">
    <h1>🚗 Weiss Parkoló</h1>
    <ul>
      <li><a href="#store">Parkolás mentése</a></li>
      <li><a href="#find">Autó keresése</a></li>
    </ul>
  </nav>

  <section id="store" class="card">
    <h3>Mentsd el a parkolóhelyed</h3>
    <form method="post">
      <label> 
        <span class="tooltip">i
          <span class="tooltiptext">Add meg a felhasználóneved a parkolóhely mentéséhez</span>
        </span>
      </label>
      <input type="text" name="card_id" placeholder="Felhasználónév" required> 
      <input type="number" name="place" placeholder="Parkolóhely száma" required>
      <button type="submit" name="save">Mentés</button>
    </form>
    <?php
      if(isset($_POST['save'])){
          insertParking($_POST['place'], $_POST['card_id']);
          echo "<p class='success'>✅ Parking spot saved!</p>";
      }
    ?>
  </section>

  <section id="find" class="card">
    <h3>Találd meg az autód</h3>
    <form method="post">
      <label> 
        <span class="tooltip">i
          <span class="tooltiptext">Add meg a felhasználóneved a parkolás kereséséhez</span>
        </span>
      </label>
      <input type="text" name="card_lookup" placeholder="Add meg a felhasználóneved" required>
      <button type="submit" name="find">Keresés</button>
    </form>

    <?php
      if(isset($_POST['find'])){
          $parking = getActiveParkingByCard($_POST['card_lookup']);
          if($parking){
              $start = new DateTime($parking['start_time']);
              $now = new DateTime();
              $diff = $start->diff($now);
              $hours = max(1, ($diff->days * 24) + $diff->h + ($diff->i > 0 ? 1 : 0));
              $price = $hours * 1000;

              echo "<p class='success'>🚘 Az autód a helyén van <b>{$parking['place']}</b></p>";
              echo "<p>⏰ Parked since: {$parking['start_time']}</p>";
              echo "<p>💰 Current Fee: <b>$price Ft</b></p>";

              echo "<form method='post'>
                        <input type='hidden' name='card_action' value='{$_POST['card_lookup']}'>
                        <button type='submit' name='stay'>Még mindig parkolok</button>
                        <button type='submit' name='leave'>Elhagytam a parkolót</button>
                    </form>";
          } else {
              echo "<p class='error'>❌ Nem találtunk ezzel a kártyával aktív parkolást.</p>";
          }
      }

      if(isset($_POST['leave'])){
          $price = archiveParking($_POST['card_action']);
          echo "<p class='success'>✅ Elhagytad a parkolót. Fizetendő összeg: <b>$price Ft</b></p>";
      }

      if(isset($_POST['stay'])){
          echo "<p class='info'>ℹ️ Még mindig parkolsz. Vigyázz a kártyára!</p>";
      }
    ?>
  </section>
</body>
</html>