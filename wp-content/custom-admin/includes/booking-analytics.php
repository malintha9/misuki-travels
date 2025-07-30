<?php
function get_booking_analytics() {
    global $wpdb;
    
    $stats = [];
    
    // Today's bookings
    $stats['today'] = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking' 
         AND post_date >= CURDATE()"
    );
    
    // This week's bookings
    $stats['this_week'] = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking' 
         AND post_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)"
    );
    
    // This month's bookings
    $stats['this_month'] = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking' 
         AND post_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)"
    );
    
    // Total bookings
    $stats['total'] = $wpdb->get_var(
        "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking'"
    );
    
    // Last 30 days daily data
    $daily_results = $wpdb->get_results(
        "SELECT DATE(post_date) as day, COUNT(*) as count 
         FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking' 
         AND post_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
         GROUP BY day ORDER BY day"
    );
    
    $stats['daily_labels'] = [];
    $stats['daily_data'] = [];
    
    foreach ($daily_results as $result) {
        $stats['daily_labels'][] = date('M j', strtotime($result->day));
        $stats['daily_data'][] = $result->count;
    }
    
    // Monthly data for current year
    $monthly_results = $wpdb->get_results(
        "SELECT MONTH(post_date) as month, COUNT(*) as count 
         FROM {$wpdb->prefix}posts 
         WHERE post_type = 'booking' 
         AND YEAR(post_date) = YEAR(CURDATE())
         GROUP BY month ORDER BY month"
    );
    
    $stats['monthly_labels'] = [];
    $stats['monthly_data'] = array_fill(0, 12, 0);
    
    foreach ($monthly_results as $result) {
        $stats['monthly_labels'][] = date('F', mktime(0, 0, 0, $result->month, 1));
        $stats['monthly_data'][$result->month - 1] = $result->count;
    }
    
    // Weekly comparison data
    $stats['weekly_labels'] = [];
    $stats['weekly_current_data'] = [];
    $stats['weekly_previous_data'] = [];
    
    for ($i = 8; $i >= 1; $i--) {
        $week_start = date('Y-m-d', strtotime("-$i week"));
        $week_end = date('Y-m-d', strtotime("-$i week +6 days"));
        
        $stats['weekly_labels'][] = date('M j', strtotime($week_start)) . ' - ' . date('M j', strtotime($week_end));
        
        // Current year data
        $current_year = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
                 WHERE post_type = 'booking' 
                 AND post_date >= %s 
                 AND post_date <= %s 
                 AND YEAR(post_date) = YEAR(CURDATE())",
                $week_start, $week_end
            )
        );
        $stats['weekly_current_data'][] = $current_year ?: 0;
        
        // Previous year data
        $previous_year = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$wpdb->prefix}posts 
                 WHERE post_type = 'booking' 
                 AND post_date >= DATE_SUB(%s, INTERVAL 1 YEAR) 
                 AND post_date <= DATE_SUB(%s, INTERVAL 1 YEAR)",
                $week_start, $week_end
            )
        );
        $stats['weekly_previous_data'][] = $previous_year ?: 0;
    }
    
    return $stats;
}