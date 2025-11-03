<?php
/**
 * Blog helper queries.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_get_blog_items($posts_per_page = 3, $paged = 1) {
    return new WP_Query(array(
        'posts_per_page' => intval($posts_per_page),
        'paged' => intval($paged),
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
    ));
}
