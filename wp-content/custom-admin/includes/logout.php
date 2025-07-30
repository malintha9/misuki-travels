<?php
require_once('../../wp-load.php');
wp_logout();
wp_redirect('login.php');
exit;
?>

wp_nonce_field('add_new_tour_action', 'tour_nonce');

// When processing:
if (!isset($_POST['tour_nonce']) || !wp_verify_nonce($_POST['tour_nonce'], 'add_new_tour_action')) {
    die('Security check failed');
}