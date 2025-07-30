<?php
require_once('../../wp-load.php');
require_once('includes/auth-check.php');

header('Content-Type: application/json');

$response = array('success' => false);

if (!is_user_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$current_user = wp_get_current_user();
if (!in_array('administrator', $current_user->roles) && !in_array('tour_manager', $current_user->roles)) {
    echo json_encode(['success' => false, 'message' => 'Permission denied']);
    exit;
}

global $wpdb;

$booking_id = intval($_POST['booking_id']);
$status = sanitize_text_field($_POST['status']);

$booking = $wpdb->get_row("
    SELECT b.*, t.post_title, u.user_email, u.display_name
    FROM wp_tour_bookings b
    JOIN wp_posts t ON b.tour_id = t.ID
    JOIN wp_users u ON b.user_id = u.ID
    WHERE b.id = $booking_id
");

if (!$booking) {
    echo json_encode(['success' => false, 'message' => 'Booking not found']);
    exit;
}

// Update status
$wpdb->update('wp_tour_bookings', ['status' => $status], ['id' => $booking_id]);

// Email content
$subject = "Your tour booking has been " . ucfirst($status);
$message = "Hi {$booking->display_name},\n\nYour booking for the tour \"{$booking->post_title}\" on {$booking->booking_date} has been **{$status}**.\n\nThank you,\nTour Admin";

// Send email
wp_mail($booking->user_email, $subject, $message);

echo json_encode(['success' => true]);