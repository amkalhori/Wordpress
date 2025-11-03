<?php
/**
 * Third-party plugin integrations and compatibility tweaks.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_elementor_support() {
    // Add Elementor theme location support
    if (class_exists('Elementor\Plugin')) {
        add_action('elementor/theme/before_do_header', function() {
            get_header();
        });
        
        add_action('elementor/theme/before_do_footer', function() {
            get_footer();
        });
    }
}
add_action('after_setup_theme', 'callamir_elementor_support');

// Optimize Elementor performance
function callamir_elementor_optimizations() {
    if (class_exists('Elementor\Plugin')) {
        // Disable Elementor's default fonts if not needed
        add_filter('elementor/frontend/print_google_fonts', '__return_false');
        
        // Optimize Elementor CSS loading
        add_action('wp_enqueue_scripts', function() {
            wp_dequeue_style('elementor-frontend');
            wp_enqueue_style('elementor-frontend', ELEMENTOR_URL . 'assets/css/frontend.min.css', array(), ELEMENTOR_VERSION);
        }, 20);
    }
}
add_action('init', 'callamir_elementor_optimizations');
