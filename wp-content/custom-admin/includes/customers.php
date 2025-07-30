<?php
require_once(__DIR__ . '/../../../wp-load.php');

// Security check
if (!is_user_logged_in()) {
    wp_redirect('login.php');
    exit;
}

$current_user = wp_get_current_user();
if (!in_array('administrator', $current_user->roles) && !in_array('tour_manager', $current_user->roles)) {
    wp_redirect('login.php');
    exit;
}

// Get users
$users = get_users();
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
            <li>
                <a href="bookings.php">
                    <i class="bi bi-calendar-check"></i>
                    <span class="menu-text">Bookings</span>
                </a>
            </li>
            <li  class="active">
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
            <h1><i class="bi bi-people me-2"></i> Customer List</h1>
        </div>

        <div class="container-fluid mt-4">
            <?php if (empty($users)): ?>
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-people fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">No Customers Found</h3>
                  
                    </div>
                </div>
            <?php else: ?>
           
       <div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="customersTable" class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Display Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $count = 1; foreach ($users as $user): ?>
                        <tr>
                            <td><?= $count++ ?></td>
                            <td><?= esc_html($user->display_name); ?></td>
                            <td><?= esc_html($user->user_login); ?></td>
                            <td><?= esc_html($user->user_email); ?></td>
                            <td><?= esc_html(get_user_meta($user->ID, 'phone_number', true)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
                    </main>
                    </div>
                </div>
            <?php endif; ?>
        </div>  
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function () {
        $('#customersTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    className: 'custom-btn'
                },
                {
                    extend: 'csv',
                    className: 'custom-btn'
                },
                {
                    extend: 'excel',
                    className: 'custom-btn'
                },
                {
                    extend: 'pdf',
                    className: 'custom-btn'
                },
                {
                    extend: 'print',
                    className: 'custom-btn'
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search customers...",
                lengthMenu: "Show _MENU_ customers per page",
                zeroRecords: "No matching customers found",
                info: "Showing _START_ to _END_ of _TOTAL_ customers",
                infoEmpty: "No customers available",
                infoFiltered: "(filtered from _MAX_ total users)"
            }
        });
    });
</script>

<style>
.custom-btn {
    background-color: #fd7e14 !important;
    color: black !important;
    padding: 10px 16px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    margin-right: 8px;
}
 .table th {
            background-color: 0 2px 10px;
            font-weight: 600;
                color: black !important;
            white-space: nowrap;
        }
        
</style>
</body>
</html>
