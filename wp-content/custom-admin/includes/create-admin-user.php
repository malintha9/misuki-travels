<?php
require_once('../wp-load.php');

// Only run this if accessed directly by admin
if (!defined('WP_CLI') && !current_user_can('create_users')) {
    die('Access denied');
}

// User data
$username = 'custom_admin';
$password = 'strong_password_123';
$email = 'admin@yoursite.com';

// Check if user exists
if (!username_exists($username)) {
    // Create the user
    $user_id = wp_create_user($username, $password, $email);
    
    if (!is_wp_error($user_id)) {
        // Set user role (create custom role if needed)
        $user = new WP_User($user_id);
        $user->set_role('tour_manager'); // Or your custom role
        
        echo "User created successfully!";
    } else {
        echo "Error creating user: " . $user_id->get_error_message();
    }
} else {
    echo "User already exists";
}