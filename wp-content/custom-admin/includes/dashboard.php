<?php
require_once(__DIR__ . '/../../../wp-load.php');
require_once __DIR__ . '/booking-analytics.php';

// Check if logged in
if (!is_user_logged_in()) {
    wp_redirect('login.php');
    exit;
}

// Check if authorized
$current_user = wp_get_current_user();
$allowed = get_user_meta($current_user->ID, 'can_access_admin_portal', true) 
        || in_array('tour_manager', $current_user->roles);

if (!$allowed) {
    wp_logout();
    wp_redirect('login.php');
    exit;
}
$stats = get_booking_analytics();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tour Booking Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/admin.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/sidebar.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/header.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/charts.css">
</head>
<body>
    <header class="admin-header">
        <button class="menu-toggle" id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="header-logo">
            <img src="/tours/wp-content/custom-admin/assets/images/logo.png" alt="Tour Admin Logo">
            <span class="logo-text"> Tour Management System</span>
        </div>
        
        <div class="header-controls">
            <div class="user-profile">
                <div class="user-avatar" id="userAvatar">
                    <?= strtoupper(substr($current_user->display_name, 0, 1)) ?>
                </div>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-header">
                        <h5><?= $current_user->display_name ?></h5>
                        <p><?= $current_user->user_email ?></p>
                    </div>
                    <ul class="dropdown-menu">
                        <li><a href="#"><i class="bi bi-person"></i> My Profile</a></li>
                        <li><a href="#"><i class="bi bi-gear"></i> Settings</a></li>
                        <li><a href="#"><i class="bi bi-bell"></i> Notifications</a></li>
                   <li class="dropdown-divider"></li> 
                        <li>
                            <a href="<?= wp_logout_url('/tours/wp-content/custom-admin/login.php') ?>">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
  <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-map" style="font-size: 2rem; color: #3498db;"></i>
        </div>
        
        <ul class="sidebar-menu">
            <li class="active">
                <a href="dashboard.php">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="tours.php">
                    <i class="bi bi-map"></i>
                    <span class="menu-text">Tours</span>
                </a>
            </li>
            <li>
                <a href="bookings.php">
                    <i class="bi bi-calendar-check"></i>
                    <span class="menu-text">Bookings</span>
                </a>
            </li>
            <li>
                <a href="customers.php">
                    <i class="bi bi-people"></i>
                    <span class="menu-text">Customers</span>
                </a>
            </li>
            <li>
                <a href="reports.php">
                    <i class="bi bi-graph-up"></i>
                    <span class="menu-text">Reports</span>
                </a>
            </li>
            <li>
                <a href="settings.php">
                    <i class="bi bi-gear"></i>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
        </ul>
    </aside>
    
    <main class="admin-main">
         <div class="page-header">
            <h1><i class="bi bi-speedometer2 me-2"></i> Dashboard</h1>
        </div>
        <div class="container-fluid py-4">
        <h1 class="mb-4">Tour Booking Analytics</h1>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card bg-primary text-white">
                    <h5>Today</h5>
                    <h2><?= $stats['today'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-success text-white">
                    <h5>This Week</h5>
                    <h2><?= $stats['this_week'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-info text-white">
                    <h5>This Month</h5>
                    <h2><?= $stats['this_month'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-warning text-dark">
                    <h5>Total Bookings</h5>
                    <h2><?= $stats['total'] ?></h2>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Daily Bookings (Last 30 Days)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Monthly Bookings (This Year)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Weekly Comparison (Last 8 Weeks)</h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($stats['daily_labels']) ?>,
            datasets: [{
                label: 'Bookings',
                data: <?= json_encode($stats['daily_data']) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($stats['monthly_labels']) ?>,
            datasets: [{
                label: 'Bookings',
                data: <?= json_encode($stats['monthly_data']) ?>,
                fill: false,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.3,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($stats['weekly_labels']) ?>,
            datasets: [{
                label: 'Current Year',
                data: <?= json_encode($stats['weekly_current_data']) ?>,
                backgroundColor: 'rgba(153, 102, 255, 0.7)'
            }, {
                label: 'Previous Year',
                data: <?= json_encode($stats['weekly_previous_data']) ?>,
                backgroundColor: 'rgba(255, 159, 64, 0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: false,
                    beginAtZero: true
                }
            }
        }
    });
    </script>
    </main>

    <script src="/tours/wp-content/custom-admin/assets/js/admin.js"></script>
</body>
</html>