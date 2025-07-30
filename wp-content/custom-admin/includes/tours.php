<?php
$wp_load_path = dirname(dirname(dirname(__DIR__))) . '/wp-load.php';
if (!file_exists($wp_load_path)) {
    die('Could not load Page. Technical details: ' . $wp_load_path);
}

require_once($wp_load_path);
require_once(__DIR__ . '/auth-check.php');
require_once(__DIR__ . '/tour-functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_tour'])) {
        $result = add_new_tour($_POST);
        if ($result) {
            wp_redirect('tours.php?action=added');
            exit;
        }
    } elseif (isset($_POST['update_tour'])) {
        $result = update_existing_tour($_POST);
        if ($result) {
            wp_redirect('tours.php?action=updated');
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $field = sanitize_text_field($_POST['field']);
    $value = sanitize_text_field($_POST['value']);

    if ($field === 'post_title') {
        wp_update_post(['ID' => $id, 'post_title' => $value]);
    } elseif ($field === 'post_content') {
        wp_update_post(['ID' => $id, 'post_content' => $value]);
    }
    echo 'updated';
    exit;
}

$tours = get_all_tours();

if (isset($_GET['action'])) {
    $message = ($_GET['action'] === 'added') ? 'Tour added successfully!' : 'Tour updated successfully!';
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
        '.$message.'
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tour Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css"> -->
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/admin.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/sidebar.css">
    <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/header.css">
     <link rel="stylesheet" href="/tours/wp-content/custom-admin/assets/css/tours.css">
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
        
        .thumbnail-img {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .action-btns .btn {
            padding: 5px 10px;
            margin: 0 3px;
        }
          .editable {
            cursor: text;
            border-bottom: 1px dashed #ccc;
        }
        .editable:focus {
            outline: none;
            background-color: #fff3cd;
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
            <li class="active">
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
            <h1><i class="bi bi-map me-2"></i> Tour List</h1>
        </div>

        <div class="container-fluid mt-4">
            <?php if (empty($tours)): ?>
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-map fa-4x text-muted mb-4"></i>
                        <h3 class="text-muted">No Tours Found</h3>
                        <p class="text-muted mb-4">You haven't created any tours yet. Get started by adding your first tour!</p>
                        <button class="add-new" data-bs-toggle="modal" data-bs-target="#tourModal">
                            <i class="bi bi-plus me-2"></i> Create First Tour
                        </button>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-4">
                            <button class="add-new" data-bs-toggle="modal" data-bs-target="#tourModal">
                                <i class="bi bi-plus me-2"></i> Add New Tour
                            </button>
                        </div>
                        
                     <div class="card-body">
        <div class="table-responsive">
            <table id="toursTable" class="table table-hover table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Tour Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Time Duration</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours as $tour): 
                        $tour_id    = get_post_meta($tour->ID, 'original_tour_id', true);
                        $images     = $tour_id ? get_post_meta($tour_id, 'tour_images', true) : [];
                        $name       = $tour_id ? get_post_meta($tour_id, 'tour_name', true) : '';
                        $description= $tour_id ? get_post_meta($tour_id, 'tour_description', true) : '';
                        $price      = $tour_id ? get_post_meta($tour_id, 'tour_price', true) : '';
                        $duration   = $tour_id ? get_post_meta($tour_id, 'tour_duration', true) : '';
                    ?>
                    <tr>
                        <td><?= $tour->ID ?></td>
                        <td>
                            <?php if (!empty($images)): ?>
                                <img src="<?= esc_url($images[0]) ?>" class="img-thumbnail rounded" style="width: 80px; height: auto;" />
                            <?php else: ?>
                                <div class="img-thumbnail bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?= esc_html($tour->post_title) ?></td>
                        <td><?= wp_trim_words($tour->post_content, 8) ?></td>
                        <td><strong>$<?= number_format((float)$price, 2) ?></strong></td>
                        <td><?= esc_html($duration) ?></td>
                        <td class="action-btns">
                            <a href="#" class="btn btn-sm btn-warning text-dark edit-tour-btn" data-tour-id="<?= $tour->ID ?>" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="?delete=<?= $tour->ID ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Delete this tour?')">
                                <i class="bi bi-trash"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-secondary" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Add Tour Modal -->
    <div class="modal fade" id="tourModal" tabindex="-1" aria-labelledby="tourModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTourModalLabel">Add New Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="tourForm">
                     <input type="hidden" id="tourId" name="tour_id">
                     <input type="hidden" id="existingImages" name="existing_images">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tour Name</label>
                                <input type="text" class="form-control"  id="tour_name" name="tour_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price ($)</label>
                                <input type="number" step="0.01" class="form-control" id="tour_price" name="tour_price" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="tour_description" name="tour_description"  rows="3"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tour Duration</label>
                                <input type="text" class="form-control" id="tour_duration"  name="tour_duration" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tour Images</label>
                                <input type="file" class="form-control" name="tour_images[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveTourBtn" name="add_tour" class="btn btn-primary">Save Tour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script> 
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Buttons extension -->

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    
    <script>
        let tourModal; 
        $(document).ready(function() {
            $('#toursTable').DataTable({
                dom: 'Bfrtip',
                 buttons: [
        {
            extend: 'copy',
            text: 'Copy',
            className: 'custom-btn'
        },
        {
            extend: 'csv',
            text: 'CSV',
            className: 'custom-btn'
        },
        {
            extend: 'excel',
            text: 'Excel',
            className: 'custom-btn'
        },
        {
            extend: 'pdf',
            text: 'PDF',
            className: 'custom-btn'
        },
        {
            extend: 'print',
            text: 'Print',
            className: 'custom-btn'
        }],
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [0,1, 6] } 
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search tours...",
                    lengthMenu: "Show _MENU_ tours per page",
                    zeroRecords: "No matching tours found",
                    info: "Showing _START_ to _END_ of _TOTAL_ tours",
                    infoEmpty: "No tours available",
                    infoFiltered: "(filtered from _MAX_ total tours)"
                }
            });
               tourModal = new bootstrap.Modal(document.getElementById('tourModal'));

          $(document).on('click', '.edit-tour-btn', function(e) {
           e.preventDefault();
    const tourId = $(this).data('tour-id');

    if (!tourId) {
        alert("Tour ID is missing.");
        return;
    }
   
    $.ajax({
        url: 'get-tour-data.php',
        type: 'GET',
        data: { tour_id: tourId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const tour = response.data;
                $('#tourId').val(tour.ID);
                $('#tour_name').val(tour.post_title);
                $('#tour_description').val(tour.post_content);
                $('#tour_price').val(tour.meta.tour_price);
                $('#tour_duration').val(tour.meta.tour_duration);
                 $('#existingImages').val(JSON.stringify(tour.meta.tour_images || []));

                      $('#tourModalLabel').text('Edit Tour');
                    $('#saveTourBtn').text('Update Tour');
                    $('#saveTourBtn').attr('name', 'update_tour');

                $('#imagePreview').empty();
                    if(tour.meta.tour_images && tour.meta.tour_images.length > 0) {
                        tour.meta.tour_images.forEach(function(image) {
                            $('#imagePreview').append(`
                                <div class="position-relative d-inline-block m-2">
                                    <img src="${image}" class="img-thumbnail" style="width: 100px; height: 70px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 remove-image" 
                                        style="width: 20px; height: 20px; line-height: 20px;">
                                        &times;
                                    </button>
                                </div>
                            `);
                        });
                    }
  
                tourModal.show();
                }
                else {
                    alert("Tour not found.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching tour data:", error);
                alert("Error loading tour data. Please try again.");
            }
    });
});

document.getElementById('tourModal').addEventListener('hidden.bs.modal', function () {
        $('#tourForm')[0].reset();
        $('#tourId').val('');
        $('#existingImages').val('');
        $('#imagePreview').empty();
        $('#tourModalLabel').text('Add New Tour');
        $('#saveTourBtn').text('Save Tour').attr('name', 'add_tour');
    });

 $(document).on('click', '.remove-image', function() {
        $(this).parent().remove();
        const remainingImages = [];
        $('#imagePreview img').each(function() {
            remainingImages.push($(this).attr('src'));
        });
        $('#existingImages').val(JSON.stringify(remainingImages));
    });

    $('[data-bs-target="#tourModal"]').on('hidden.bs.modal', function() {
    $('#tourForm')[0].reset();
        $('#tourId').val('');
        $('#tourModalLabel').text('Add New Tour');
        $('#saveTourBtn').text('Save Tour').attr('name', 'add_tour');
          tourModal.show();
    });
  
            // Profile dropdown toggle
            $('#userAvatar').click(function(e) {
                e.stopPropagation();
                $('#profileDropdown').toggleClass('show');
            });
            
            // Close dropdown when clicking outside
            $(document).click(function() {
                $('#profileDropdown').removeClass('show');
            });
        });
    </script>
</body>
</html>