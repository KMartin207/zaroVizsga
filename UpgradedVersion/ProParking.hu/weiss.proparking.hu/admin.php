<?php

include "db.php";


$active_parkings = getAllActive();
$archived_parkings = getAllArchive();

// Calculate Stats
$total_active = count($active_parkings);
$total_archived = count($archived_parkings);
$total_revenue = 0;
foreach ($archived_parkings as $p) {
  $total_revenue += intval($p['total_price']);
}

// Prepare Visualizer Data
$occupied_spots = [];
foreach ($active_parkings as $p) {
  $occupied_spots[$p['place']] = $p['card_id'];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Smart Parking</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="dashboard-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-square-parking" style="margin-right: 10px; color: #38b2ac;"></i>
            ProParking
        </div>
        <nav class="sidebar-menu">
            <a href="#" class="active" onclick="showSection('dashboard', this)">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
            <a href="#" onclick="showSection('active', this)">
                <i class="fa-solid fa-car-side"></i> Active Parkings
            </a>
            <a href="#" onclick="showSection('archive', this)">
                <i class="fa-solid fa-clock-rotate-left"></i> Archive
            </a>
            <a href="#" onclick="showSection('map', this)">
                <i class="fa-solid fa-map-location-dot"></i> Live Map
            </a>
            <a href="index.php" style="margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);">
                <i class="fa-solid fa-arrow-left"></i> Back to Site
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <header class="top-header">
            <h2>Admin Dashboard</h2>
            <div class="user-info">
                <i class="fa-solid fa-user-circle"></i> Admin
            </div>
        </header>

        <div class="content-scroll">
            
            <!-- DASHBOARD SECTION -->
            <div id="section-dashboard" class="content-section">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Active Cars</h4>
                            <p><?php echo $total_active; ?></p>
                        </div>
                        <div class="stat-icon bg-blue">
                            <i class="fa-solid fa-car"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Revenue</h4>
                            <p><?php echo number_format($total_revenue, 0, '.', ' '); ?> Ft</p>
                        </div>
                        <div class="stat-icon bg-green">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Total Parking Events</h4>
                            <p><?php echo $total_archived + $total_active; ?></p>
                        </div>
                        <div class="stat-icon bg-purple">
                            <i class="fa-solid fa-list-check"></i>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-info">
                            <h4>Occupancy</h4>
                            <p><?php echo round(($total_active / 100) * 100, 1); ?>%</p> <!-- Assuming 100 spots based on grid -->
                        </div>
                        <div class="stat-icon bg-orange">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Preview -->
                <div class="table-container">
                    <div class="table-header">
                        <h3><i class="fa-solid fa-clock" style="color: #38b2ac;"></i> Recent Active Parkings</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Card ID</th>
                                <th>Start Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$count = 0;
foreach (array_reverse($active_parkings) as $row):
  if ($count++ >= 5)
    break;
?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><span class="badge badge-active"><?php echo $row['place']; ?></span></td>
                                <td><code><?php echo $row['card_id']; ?></code></td>
                                <td><?php echo $row['start_time']; ?></td>
                                <td><span class="badge badge-active">Active</span></td>
                            </tr>
                            <?php
endforeach; ?>
                            <?php if (empty($active_parkings))
  echo "<tr><td colspan='5' style='text-align:center'>No active parkings</td></tr>"; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ACTIVE LIST SECTION -->
            <div id="section-active" class="content-section" style="display: none;">
                <div class="table-container">
                    <div class="table-header">
                        <h3>All Active Parkings</h3>
                        <input type="text" placeholder="Search..." style="max-width: 200px; padding: 5px 10px; border-radius: 5px; border: 1px solid #ddd;">
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Card ID</th>
                                <th>Start Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($active_parkings as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['place']; ?></td>
                                <td><?php echo $row['card_id']; ?></td>
                                <td><?php echo $row['start_time']; ?></td>
                            </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ARCHIVE SECTION -->
            <div id="section-archive" class="content-section" style="display: none;">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Archived History</h3>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Place</th>
                                <th>Card ID</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Total Fee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($archived_parkings as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['place']; ?></td>
                                <td><?php echo $row['card_id']; ?></td>
                                <td><?php echo $row['start_time']; ?></td>
                                <td><?php echo $row['end_time']; ?></td>
                                <td><strong><?php echo $row['total_price']; ?> Ft</strong></td>
                            </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MAP SECTION -->
            <div id="section-map" class="content-section" style="display: none;">
                <div class="table-container">
                    <div class="table-header">
                        <h3>Live Parking Map (Top 100 Spots)</h3>
                        <div style="display:flex; gap: 15px; font-size: 0.9rem;">
                            <span style="display:flex; align-items:center; gap:5px;"><div style="width:15px; height:15px; background:#edf2f7; border-radius:3px;"></div> Free</span>
                            <span style="display:flex; align-items:center; gap:5px;"><div style="width:15px; height:15px; background:#fc8181; border-radius:3px;"></div> Occupied</span>
                        </div>
                    </div>
                    <div class="parking-grid">
                        <?php
// Generating 100 spots
for ($i = 1; $i <= 100; $i++) {
  $is_occupied = isset($occupied_spots[$i]);
  $class = $is_occupied ? 'occupied' : '';
  $title = $is_occupied ? 'Occupied by Card: ' . $occupied_spots[$i] : 'Free Spot';
  echo "<div class='parking-spot $class' title='$title'>$i</div>";
}
?>
                    </div>
                </div>
            </div>

        </div>
    </main>
  </div>

  <script>
    function showSection(sectionId, element) {
        // Hide all sections
        document.querySelectorAll('.content-section').forEach(el => el.style.display = 'none');
        
        // Show selected section
        document.getElementById('section-' + sectionId).style.display = 'block';
        
        // Update menu active state
        document.querySelectorAll('.sidebar-menu a').forEach(el => el.classList.remove('active'));
        if(element) element.classList.add('active');
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[placeholder="Search..."]');
        if(searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                const term = e.target.value.toLowerCase();
                const table = document.querySelector('#section-active table tbody');
                const rows = table.getElementsByTagName('tr');

                Array.from(rows).forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            });
        }
    });
  </script>
</body>
</html>
