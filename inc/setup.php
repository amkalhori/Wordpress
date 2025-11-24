<?php
/**
 * Theme setup and feature support declarations.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_theme_setup() {
    // Load textdomain
    load_theme_textdomain('callamir', get_template_directory() . '/languages');
    
    // Add theme support for modern WordPress features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Add support for Elementor
    add_theme_support('elementor');
    add_theme_support('elementor-pro');
    
    // Register navigation menus
    register_nav_menus([
        'one_page_menu' => __('One Page Menu', 'callamir'),
        'footer_menu' => __('Footer Menu', 'callamir'),
    ]);
    
    // Register Community Questions Custom Post Type
    callamir_register_community_questions();
}
add_action('after_setup_theme', 'callamir_theme_setup');
