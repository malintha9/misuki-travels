<?php
require_once(__DIR__ . '/../../../wp-load.php');

// Security: Only admin or tour_manager
if (!is_user_logged_in()) {
    wp_redirect('login.php');
    exit;
}

$current_user = wp_get_current_user();
if (!in_array('administrator', $current_user->roles) && !in_array('tour_manager', $current_user->roles)) {
    wp_redirect('login.php');
    exit;
}

// Fetch bookings
global $wpdb;
$bookings = $wpdb->get_results("
    SELECT b.*, 
           t.post_title AS tour_title, 
           u.display_name AS user_name, 
           u.user_email 
    FROM wp_tour_bookings b
    JOIN wp_posts t ON b.tour_id = t.ID
    JOIN wp_users u ON b.user_id = u.ID
    ORDER BY b.booking_date DESC
");
?>
<!DOCTYPE html>
<html>
<head>
     <title>Tour Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/admin.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/sidebar.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/header.css">
    <style>
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            white-space: nowrap;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        
        .action-btns .btn {
            padding: 5px 10px;
            margin: 0 3px;
        }
        .confirmed { background-color: #28a745; color: white; }
        .cancelled { background-color: #dc3545; color: white; }
    </style>
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
            <li>
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
            <li  class="active">
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
            <h1><i class="bi bi-calendar-check me-2"></i> Booking List</h1>
        </div>

        <div class="container-fluid mt-4">
            <?php if (empty($bookings)): ?>
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-calendar-check fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">No Booking Found</h3>
                  
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Tour Title</th>
                <th>Booking Date</th>
                <th>Booked By</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking): ?>
            <tr id="row-<?php echo $booking->id; ?>">
                <td><?php echo esc_html($booking->tour_title); ?></td>
                <td><?php echo esc_html($booking->booking_date); ?></td>
                <td><?php echo esc_html($booking->user_name); ?></td>
                <td id="status-<?php echo $booking->id; ?>"><?php echo ucfirst($booking->status); ?></td>
                <td>
                    <?php if ($booking->status === 'pending'): ?>
                        <button class="confirmed" onclick="updateStatus(<?php echo $booking->id; ?>, 'confirmed')">Confirm</button>
                        <button class="cancelled" onclick="updateStatus(<?php echo $booking->id; ?>, 'cancelled')">Cancel</button>
                    <?php else: ?>
                        <em><?php echo ucfirst($booking->status); ?></em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
                    </main>
                    </div>
                </div>
            <?php endif; ?>
        </div>  
    <script>
        function updateStatus(id, status) {
            if (!confirm("Are you sure you want to " + status + " this booking?")) return;

            const formData = new FormData();
            formData.append('action', 'update_booking_status');
            formData.append('booking_id', id);
            formData.append('status', status);

            fetch('ajax-handler.php', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('status-' + id).innerText = status.charAt(0).toUpperCase() + status.slice(1);
                    document.getElementById('row-' + id).querySelector('td:last-child').innerHTML = `<em>${status}</em>`;
                } else {
                    alert("Failed to update.");
                }
            });
        }
    </script>
</body>
</html>
