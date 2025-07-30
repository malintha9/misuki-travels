<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/wp-load.php');

if (!isset($_GET['tour_id'])) {
    wp_send_json_error('Missing tour ID');
}

$tour_id = intval($_GET['tour_id']);
$post = get_post($tour_id);

if (!$post || $post->post_type !== 'post') { 
    wp_send_json_error('Tour not found');
}

$original_tour_id = get_post_meta($post->ID, 'original_tour_id', true);
$meta_source_id = $original_tour_id ? intval($original_tour_id) : $post->ID;

$response = [
    'success' => true,
    'data' => [
        'ID'           => $post->ID,
        'post_title'   => $post->post_title,
        'post_content' => $post->post_content,
        'meta'         => [
            'tour_price'    => get_post_meta($post->ID, 'tour_price', true),
            'tour_duration' => get_post_meta($post->ID, 'tour_duration', true),
            'tour_images'   => get_post_meta($post->ID, 'tour_images', true) ?: []
        ]
    ]
];

wp_send_json($response);