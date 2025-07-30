<?php
class Tour_Notifications {
    public static function send_new_tour_email($tour_id) {
        $tour = get_post($tour_id);
        $admin_email = get_option('admin_email');
        $subject = 'New Tour Created: ' . $tour->post_title;
        
        $message = "A new tour has been created:\n\n";
        $message .= "Title: " . $tour->post_title . "\n";
        $message .= "Description: " . $tour->post_content . "\n";
        $message .= "Price: $" . get_post_meta($tour_id, 'tour_price', true) . "\n";
        $message .= "Date: " . get_post_meta($tour_id, 'tour_date', true) . "\n";
        
        wp_mail($admin_email, $subject, $message);
    }
    
    public static function send_tour_update_email($tour_id) {
        $tour = get_post($tour_id);
        $subject = 'Tour Updated: ' . $tour->post_title;
        
        $emails = self::get_subscribed_emails();
        if (empty($emails)) return;
        
        $message = "A tour has been updated:\n\n";
        $message .= "Title: " . $tour->post_title . "\n";
        $message .= "View Tour: " . get_permalink($tour_id) . "\n";
        
        foreach ($emails as $email) {
            wp_mail($email, $subject, $message);
        }
    }
    
    private static function get_subscribed_emails() {
        // Could be from a database table or options
        return array_filter(array_map('sanitize_email', (array)get_option('tour_notification_emails', array())));
    }
}