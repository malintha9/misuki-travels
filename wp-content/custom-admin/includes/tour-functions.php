<?php
function get_all_tours() {
    return get_posts(array(
      'post_type'      => 'post',
        'category_name'  => 'tours',
        'numberposts'    => -1,
        'orderby'        => 'title',   // Sort by title (post_title)
        'order'          => 'ASC'
    ));
}

function add_new_tour($data) {
    // Process image uploads first
    $image_urls = array();
    if (!empty($_FILES['tour_images'])) {
        $image_urls = handle_tour_image_upload();
    }
    
    $new_tour = array(
        'post_title'   => sanitize_text_field($data['tour_name']),
        'post_content' => wp_kses_post($data['tour_description']),
        'post_status'  => 'publish',
        'post_type'    => 'tour',
        'meta_input'   => array(
            'tour_price' => floatval(sanitize_text_field($data['tour_price'])),
            'tour_duration'  => sanitize_text_field($data['tour_duration']),
            'tour_images' => $image_urls
        )
    );
    
    $post_id = wp_insert_post($new_tour);
    
     // Create a regular POST with the same data
    $regular_post = array(
        'post_title'   => sanitize_text_field($data['tour_name']),
        'post_content' => wp_kses_post($data['tour_description']),
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'meta_input'   => array(
            'original_tour_id' => $post_id ,
             'tour_price'       => floatval(sanitize_text_field($data['tour_price'])),
        'tour_duration'    => sanitize_text_field($data['tour_duration']),
        'tour_images'      => $image_urls
        )
    );
    
    $regular_post_id = wp_insert_post($regular_post);

     $tours_cat_id = get_cat_ID('tours');
if ($regular_post_id && $tours_cat_id) {
    wp_set_post_categories($regular_post_id, array($tours_cat_id));
}

        if ($post_id  && $regular_post_id) {
        // Copy featured image if needed
        if (!empty($image_urls)) {
            set_post_thumbnail($regular_post_id, attachment_url_to_postid($image_urls[0]));
        }
        // Send notification if needed
        if (file_exists(__DIR__ . '/notifications.php')) {
            require_once(__DIR__ . '/notifications.php');
            Tour_Notifications::send_new_tour_email($post_id);
        }
  return array('tour_id' => $post_id , 'post_id' => $regular_post_id);
    }
    
    return false;
}

function handle_tour_image_upload() {
    if (!empty($_FILES['tour_images'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $image_urls = array();
        foreach ($_FILES['tour_images']['name'] as $key => $value) {
            if ($_FILES['tour_images']['name'][$key]) {
                $file = array(
                    'name'     => sanitize_file_name($_FILES['tour_images']['name'][$key]),
                    'type'     => $_FILES['tour_images']['type'][$key],
                    'tmp_name' => $_FILES['tour_images']['tmp_name'][$key],
                    'error'    => $_FILES['tour_images']['error'][$key],
                    'size'     => $_FILES['tour_images']['size'][$key]
                );
                
                $uploaded = media_handle_sideload($file, 0);
                if (!is_wp_error($uploaded)) {
                    $image_urls[] = esc_url_raw(wp_get_attachment_url($uploaded));
                }
            }
        }
        return $image_urls;
    }
    return array();
}

function sanitize_image_array($images) {
    if (!is_array($images)) return array();
    
    return array_map('esc_url_raw', $images);
}

function update_existing_tour($data) {
    // Sanitize existing images or process new uploads
    $images = isset($data['existing_images']) ? sanitize_image_array($data['existing_images']) : array();
    
    if (!empty($_FILES['tour_images'])) {
        $new_images = handle_tour_image_upload();
        $images = array_merge($images, $new_images);
    }
    
    $updated_tour = array(
        'ID'           => intval($data['tour_id']),
        'post_title'   => sanitize_text_field($data['tour_name']),
        'post_content' => wp_kses_post($data['tour_description']),
        'meta_input'   => array(
            'tour_price' => floatval(sanitize_text_field($data['tour_price'])),
            'tour_duration'  => sanitize_text_field($data['tour_duration']),
            'tour_images' => $images
        )
    );
    
    $updated = wp_update_post($updated_tour);
    
    if (!is_wp_error($updated) && $updated) {
        if (file_exists(__DIR__ . '/notifications.php')) {
            require_once(__DIR__ . '/notifications.php');
            Tour_Notifications::send_tour_update_email($data['tour_id']);
        }
        return $updated;
    }
    
    return false;
}

function delete_tour($tour_id) {
    $deleted = wp_delete_post(intval($tour_id), true);
    
    if ($deleted) {
        // Optional: Add any cleanup actions here
        return true;
    }
    
    return false;
}