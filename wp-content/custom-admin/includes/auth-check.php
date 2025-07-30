<?php

$wp_load_path = 'C:/xampp/htdocs/tours/wp-load.php';

if (!file_exists($wp_load_path)) {
    die('WordPress not found at: ' . $wp_load_path);
}

require_once($wp_load_path);
if (!function_exists('wp_redirect')) {
    die('ERROR: WordPress not loaded properly');
}

// Rest of your authentication code...
if (!is_user_logged_in()) {
    wp_redirect('/tours/wp-content/custom-admin/login.php');
    exit;
}

if (!is_user_logged_in()) {
    wp_redirect('login.php');
    exit;
}

$allowed_roles = array('administrator', 'tour_manager', 'tour_editor');
$user = wp_get_current_user();
$has_role = false;

foreach ($allowed_roles as $role) {
    if (in_array($role, $user->roles)) {
        $has_role = true;
        break;
    }
}

if (!$has_role) {
    wp_redirect(home_url());
    exit;
}

// Role-specific capabilities
function current_user_can_edit_tours() {
    return current_user_can('edit_tours');
}

function current_user_can_publish_tours() {
    return current_user_can('publish_tours');
}

function current_user_can_delete_tours() {
    return current_user_can('delete_tours');
}