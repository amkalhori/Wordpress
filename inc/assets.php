<?php
/**
 * Asset loading and front-end data localization.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_enqueue_scripts() {
    // Enqueue styles with performance optimizations
    $theme_version = '1.0.56';

    wp_enqueue_style('callamir-style', get_stylesheet_uri(), array(), $theme_version);
    wp_style_add_data('callamir-style', 'rtl', 'replace');

    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');

    // Enqueue scripts with modern optimizations
    wp_enqueue_script('callamir-theme-js', get_template_directory_uri() . '/js/theme.js', array('jquery'), $theme_version, true);
    
    // Localize script for AJAX and language metadata.
    wp_localize_script('callamir-theme-js', 'callamirText', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('callamir_nonce'),
        'navPrevLabel' => __('Previous menu item', 'callamir'),
        'navNextLabel' => __('Next menu item', 'callamir'),
    ));
    
    // Add preload hints for better performance
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
    });


    // Provide the front end with canonical language metadata and URLs so
    // JavaScript can keep navigation in sync with the visitor selection.
    $current_lang = callamir_get_visitor_lang();
    $language_payload = array(
        'current' => $current_lang,
        'default' => callamir_get_default_language(),
        'supported' => array(),
    );

    foreach (callamir_get_supported_languages() as $code => $meta) {
        $language_payload['supported'][] = array(
            'code' => $code,
            'label' => $meta['label'],
            'direction' => $meta['direction'],
            'url' => callamir_localize_url(home_url('/'), $code),
        );
    }

    wp_localize_script('callamir-theme-js', 'callamirLang', $language_payload);

    // Localize theme mods for cosmic effects and services
    $services_count = callamir_sanitize_service_count(get_theme_mod('callamir_services_count', 3));

    $theme_mods = array(
        // Header Stars
        'enable_header_stars' => get_theme_mod('callamir_enable_header_stars', true),
        'star_count_header' => get_theme_mod('callamir_star_count_header', 100),
        // Footer Stars
        'enable_footer_stars' => get_theme_mod('callamir_enable_footer_stars', true),
        'star_count_footer' => get_theme_mod('callamir_star_count_footer', 100),
        // Hero Cosmic
        'enable_hero_effect' => get_theme_mod('callamir_enable_hero_effect', true),
        'hero_blackhole_pattern' => get_theme_mod('callamir_hero_blackhole_pattern', 'circular'),
        'hero_circle_count' => get_theme_mod('callamir_hero_circle_count', 50),
        'hero_star_count' => get_theme_mod('callamir_hero_star_count', 150),
        // Services Cosmic
        'enable_services_effect' => get_theme_mod('callamir_enable_services_effect', true),
        'services_pattern' => get_theme_mod('callamir_services_pattern', 'circular'),
        'services_circle_count' => get_theme_mod('callamir_services_circle_count', 50),
        'services_star_count' => get_theme_mod('callamir_services_star_count', 150),
        // Services Count
        'services_count' => $services_count,
    );
    wp_localize_script('callamir-theme-js', 'themeMods', $theme_mods);

    // Localize service data for JavaScript
    $service_data = array();
    $service_translations = array(
        'current' => $current_lang,
        'default' => callamir_get_default_language(),
        'items' => array(),
    );

    $supported_languages = callamir_get_supported_languages();
    $count = $services_count;

    for ($i = 1; $i <= $count; $i++) {
        $defaults = array(
            'title' => array(
                'en' => sprintf(__('Service %d', 'callamir'), $i),
                'fa' => sprintf(__('خدمت %d', 'callamir'), $i),
            ),
            'description' => array(
                'en' => sprintf(__('Description for service %d', 'callamir'), $i),
                'fa' => sprintf(__('توضیح خدمت %d', 'callamir'), $i),
            ),
            'fullDescription' => array(
                'en' => sprintf(__('Detailed description for service %d', 'callamir'), $i),
                'fa' => sprintf(__('توضیح کامل برای خدمت %d', 'callamir'), $i),
            ),
        );

        $image = get_theme_mod("callamir_service_image_{$i}", '');

        $service_data[$i] = array(
            'title' => callamir_get_text("service_title_{$i}", $defaults['title']['en'], $defaults['title']['fa']),
            'description' => callamir_get_text("service_desc_{$i}", $defaults['description']['en'], $defaults['description']['fa']),
            'fullDescription' => callamir_get_text("service_full_desc_{$i}", $defaults['fullDescription']['en'], $defaults['fullDescription']['fa']),
            'price' => callamir_mod("service_price_{$i}", ''),
            'image' => $image,
        );

        $service_translations['items'][$i] = array(
            'image' => $image,
            'translations' => array(),
        );

        foreach ($supported_languages as $code => $meta) {
            $service_translations['items'][$i]['translations'][$code] = array(
                'title' => get_theme_mod("service_title_{$i}_{$code}", $defaults['title'][$code] ?? $defaults['title']['en']),
                'description' => get_theme_mod("service_desc_{$i}_{$code}", $defaults['description'][$code] ?? $defaults['description']['en']),
                'fullDescription' => get_theme_mod("service_full_desc_{$i}_{$code}", $defaults['fullDescription'][$code] ?? $defaults['fullDescription']['en']),
                'price' => get_theme_mod("service_price_{$i}_{$code}", ''),
            );
        }
    }

    wp_localize_script('callamir-theme-js', 'serviceData', $service_data);
    wp_localize_script('callamir-theme-js', 'serviceTranslations', $service_translations);
}
add_action('wp_enqueue_scripts', 'callamir_enqueue_scripts');
