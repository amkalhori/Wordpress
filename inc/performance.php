<?php
/**
 * Performance tuning and accessibility helpers.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_performance_optimizations() {
    // Remove unnecessary WordPress features for better performance
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Optimize WordPress queries
    add_action('pre_get_posts', function($query) {
        if (!is_admin() && $query->is_main_query()) {
            $query->set('no_found_rows', true);
            $query->set('update_post_meta_cache', false);
            $query->set('update_post_term_cache', false);
        }
    });
}
add_action('init', 'callamir_performance_optimizations');

/* --------------------------------------------------------------------------
 * Accessibility Improvements
 * -------------------------------------------------------------------------- */
function callamir_accessibility_improvements() {
    // Add skip links for better accessibility
    add_action('wp_head', function() {
        echo '<style>
            .skip-link {
                position: absolute;
                top: -40px;
                left: 6px;
                background: #FFD700;
                color: #0A0A0A;
                padding: 8px 16px;
                text-decoration: none;
                border-radius: 4px;
                z-index: 10000;
                transition: top 0.3s;
            }
            .skip-link:focus {
                top: 6px;
            }
        </style>';
    });
    
    // Add ARIA labels and roles
    add_filter('nav_menu_link_attributes', function($atts, $item, $args) {
        if ($args->theme_location === 'one_page_menu') {
            $atts['role'] = 'menuitem';
            $atts['aria-label'] = $item->title;
        }
        return $atts;
    }, 10, 3);
}
add_action('init', 'callamir_accessibility_improvements');
