<?php
/**
 * Customizer registration hooks.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_customize_register($wp_customize) {
    // Theme Colors Section
    $wp_customize->add_section('theme_colors', [
        'title' => __('Theme Colors', 'callamir'),
        'priority' => 25,
        'description' => __('Customize the main colors of your theme', 'callamir')
    ]);
    $default_colors = [
        'primary_color' => '#041635',
        'accent_color' => '#ebb900',
        'green_color' => '#318e00',
        'danger_color' => '#e52021',
        'text_color' => '#041635',
        'section_bg_color' => '#f0f4f8',
        'border_color' => '#444444',
        'cosmic_primary' => '#0A0A0A',
        'cosmic_accent' => '#FFD700',
    ];
    foreach ($default_colors as $key => $default_value) {
        $wp_customize->add_setting($key, [
            'default' => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, [
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'theme_colors',
        ]));
    }

    $section_color_settings = [
        'hero_title_color' => '#ffffff',
        'hero_text_color' => '#dbeafe',
        'section_title_color' => '#ffffff',
        'section_title_accent_color' => '#ffd700',
        'section_subtitle_color' => '#e5e7eb',
        'contact_form_background_color' => '#111827',
        'contact_form_border_color' => '#1f2937',
        'contact_form_field_background' => '#0b1220',
        'contact_form_field_color' => '#f9fafb',
        'contact_form_button_background' => '#facc15',
        'contact_form_button_color' => '#111827',
    ];

    foreach ($section_color_settings as $key => $default_value) {
        $wp_customize->add_setting($key, [
            'default' => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, [
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'theme_colors',
        ]));
    }

    // Header & Footer Settings Section
    $wp_customize->add_section('callamir_header_footer', [
        'title' => __('Header & Footer Settings', 'callamir'),
        'priority' => 28,
        'description' => __('Customize header and footer layout', 'callamir')
    ]);
    $wp_customize->add_setting('header_justify_content', [
        'default' => 'space-between',
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('header_justify_content', [
        'label' => __('Header Justify Content', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => [
            'space-between' => __('Space Between', 'callamir'),
            'space-around' => __('Space Around', 'callamir'),
            'center' => __('Center', 'callamir'),
            'flex-start' => __('Start', 'callamir'),
            'flex-end' => __('End', 'callamir'),
        ],
    ]);
    $wp_customize->add_setting('header_justify_content_mobile', [
        'default' => 'space-between',
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('header_justify_content_mobile', [
        'label' => __('Header Justify Content (Mobile)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => [
            'space-between' => __('Space Between', 'callamir'),
            'space-around' => __('Space Around', 'callamir'),
            'center' => __('Center', 'callamir'),
            'flex-start' => __('Start', 'callamir'),
            'flex-end' => __('End', 'callamir'),
        ],
    ]);
    $wp_customize->add_setting('header_logo_alignment', [
        'default' => 'left',
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('header_logo_alignment', [
        'label' => __('Logo Alignment', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => [
            'left' => __('Left', 'callamir'),
            'center' => __('Center', 'callamir'),
            'right' => __('Right', 'callamir'),
        ],
    ]);
    $wp_customize->add_setting('logo_max_height', [
        'default' => '80px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('logo_max_height', [
        'label' => __('Logo Max Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 80px', 'callamir'),
    ]);

    $wp_customize->add_setting('logo_text_en', [
        'default' => __('CallAmir', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('logo_text_en', [
        'label' => __('Logo Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('logo_text_fa', [
        'default' => __('کال امیر', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('logo_text_fa', [
        'label' => __('Logo Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('leave_message_width', [
        'default' => '200px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_width', [
        'label' => __('Leave Message Button Width', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 200px', 'callamir'),
    ]);
    $wp_customize->add_setting('leave_message_height', [
        'default' => '50px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_height', [
        'label' => __('Leave Message Button Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 50px', 'callamir'),
    ]);

    $wp_customize->add_setting('leave_message_text_en', [
        'default' => __('Leave a message', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_text_en', [
        'label' => __('Leave Message Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('leave_message_text_fa', [
        'default' => __('پیام بگذارید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_text_fa', [
        'label' => __('Leave Message Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('leave_message_url', [
        'default' => '#contact',
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_url', [
        'label' => __('Leave Message Link', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'url',
        'description' => __('Target URL for the Leave a Message button', 'callamir'),
    ]);
    $wp_customize->add_setting('leave_message_icon', [
        'default' => 'fa-solid fa-envelope',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('leave_message_icon', [
        'label' => __('Leave Message Icon Class', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Font Awesome class used beside the Leave a Message button', 'callamir'),
    ]);
    $wp_customize->add_setting('footer_min_height', [
        'default' => '100px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_min_height', [
        'label' => __('Footer Minimum Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 100px', 'callamir'),
    ]);

    $wp_customize->add_setting('copyright_text_en', [
        'default' => __('CallAmir. All rights reserved © {year}', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('copyright_text_en', [
        'label' => __('Footer Copyright Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Use {year} to automatically insert the current year.', 'callamir'),
    ]);

    $wp_customize->add_setting('copyright_text_fa', [
        'default' => __('کال امیر. کلیه حقوق محفوظ است © {year}', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('copyright_text_fa', [
        'label' => __('Footer Copyright Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Use {year} to automatically insert the current year.', 'callamir'),
    ]);

    // Typography & Layout Section
    $wp_customize->add_section('callamir_typography_layout', [
        'title' => __('Typography & Layout', 'callamir'),
        'priority' => 29,
        'description' => __('Control fonts and layout spacing used throughout the theme.', 'callamir'),
    ]);

    $wp_customize->add_setting('body_font_family', [
        'default' => "'Roboto', Arial, sans-serif",
        'sanitize_callback' => 'callamir_sanitize_font_family',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('body_font_family', [
        'label' => __('Body Font Family', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Example: "Roboto, Arial, sans-serif"', 'callamir'),
    ]);

    $wp_customize->add_setting('heading_font_family', [
        'default' => "'Montserrat', 'Roboto', Arial, sans-serif",
        'sanitize_callback' => 'callamir_sanitize_font_family',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('heading_font_family', [
        'label' => __('Heading Font Family', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('base_font_size', [
        'default' => '16px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('base_font_size', [
        'label' => __('Base Font Size', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Set the default font size (e.g., 16px).', 'callamir'),
    ]);

    $wp_customize->add_setting('container_max_width', [
        'default' => '1200px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('container_max_width', [
        'label' => __('Content Max Width', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Maximum width for layout containers (e.g., 1200px).', 'callamir'),
    ]);

    $wp_customize->add_setting('section_vertical_padding', [
        'default' => '4rem',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('section_vertical_padding', [
        'label' => __('Section Vertical Padding', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Controls top and bottom padding for major sections.', 'callamir'),
    ]);

    $wp_customize->add_setting('section_min_height', [
        'default' => '320px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('section_min_height', [
        'label' => __('Section Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('hero_min_height', [
        'default' => '420px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('hero_min_height', [
        'label' => __('Hero Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('header_min_height', [
        'default' => '80px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('header_min_height', [
        'label' => __('Header Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ]);

    // Cosmic Effects Section
    $wp_customize->add_section('callamir_cosmic_effects', [
        'title' => __('Cosmic Effects', 'callamir'),
        'priority' => 30,
        'description' => __('Customize cosmic visual effects for the theme', 'callamir')
    ]);
    // Header Stars
    $wp_customize->add_setting('callamir_enable_header_stars', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_enable_header_stars', [
        'label' => __('Enable Header Stars', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ]);
    $wp_customize->add_setting('callamir_star_count_header', [
        'default' => 100,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_star_count_header', [
        'label' => __('Header Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 500, 'step' => 10],
    ]);
    // Footer Stars
    $wp_customize->add_setting('callamir_enable_footer_stars', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_enable_footer_stars', [
        'label' => __('Enable Footer Stars', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ]);
    $wp_customize->add_setting('callamir_star_count_footer', [
        'default' => 100,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_star_count_footer', [
        'label' => __('Footer Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 500, 'step' => 10],
    ]);
    // Hero Effect
    $wp_customize->add_setting('callamir_enable_hero_effect', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_enable_hero_effect', [
        'label' => __('Enable Hero Cosmic Effect', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ]);
    $wp_customize->add_setting('callamir_hero_blackhole_pattern', [
        'default' => 'circular',
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_hero_blackhole_pattern', [
        'label' => __('Hero Blackhole Pattern', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'select',
        'choices' => [
            'circular' => __('Circular', 'callamir'),
            'hexagon' => __('Hexagon Network', 'callamir'),
        ],
    ]);
    $wp_customize->add_setting('callamir_hero_circle_count', [
        'default' => 50,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_hero_circle_count', [
        'label' => __('Hero Circle Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 200, 'step' => 10],
    ]);
    $wp_customize->add_setting('callamir_hero_star_count', [
        'default' => 150,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_hero_star_count', [
        'label' => __('Hero Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 500, 'step' => 10],
    ]);
    // Services Effect
    $wp_customize->add_setting('callamir_enable_services_effect', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_enable_services_effect', [
        'label' => __('Enable Services Cosmic Effect', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ]);
    $wp_customize->add_setting('callamir_services_pattern', [
        'default' => 'circular',
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_services_pattern', [
        'label' => __('Services Pattern', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'select',
        'choices' => [
            'circular' => __('Circular', 'callamir'),
            'hexagon' => __('Hexagon Network', 'callamir'),
        ],
    ]);
    $wp_customize->add_setting('callamir_services_circle_count', [
        'default' => 50,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_services_circle_count', [
        'label' => __('Services Circle Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 200, 'step' => 10],
    ]);
    $wp_customize->add_setting('callamir_services_star_count', [
        'default' => 150,
        'sanitize_callback' => 'absint',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_services_star_count', [
        'label' => __('Services Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => ['min' => 10, 'max' => 500, 'step' => 10],
    ]);

    $max_services = function_exists('callamir_max_services') ? callamir_max_services() : 6;

    // Modern Services Section
    $wp_customize->add_section('callamir_modern_services', [
        'title' => __('Modern Services Section', 'callamir'),
        'priority' => 35,
        'description' => __('Customize the modern services section with advanced features including modals, contact forms, and detailed descriptions.', 'callamir')
    ]);

    // Services Section Settings
    $wp_customize->add_setting('callamir_services_count', [
        'default' => 3,
        'sanitize_callback' => 'callamir_sanitize_service_count',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('callamir_services_count', [
        'label' => __('Number of Services', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'number',
        'input_attrs' => ['min' => 0, 'max' => $max_services, 'step' => 1],
        'description' => __('Set to 0 to hide all services.', 'callamir'),
    ]);

    // Services Section Title and Subtitle
    $wp_customize->add_setting('services_title_en', [
        'default' => __('Our Services', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_title_en', [
        'label' => __('Services Section Title (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('services_title_fa', [
        'default' => __('خدمات ما', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_title_fa', [
        'label' => __('Services Section Title (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('services_subtitle_en', [
        'default' => __('Professional IT solutions tailored to your needs', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_subtitle_en', [
        'label' => __('Services Section Subtitle (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'textarea',
    ]);

    $wp_customize->add_setting('services_subtitle_fa', [
        'default' => __('راه‌حل‌های حرفه‌ای آی‌تی متناسب با نیازهای شما', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_subtitle_fa', [
        'label' => __('Services Section Subtitle (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'textarea',
    ]);

    // Read More Button Text
    $wp_customize->add_setting('read_more_text_en', [
        'default' => __('Read More', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('read_more_text_en', [
        'label' => __('Read More Button Text (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('read_more_text_fa', [
        'default' => __('بیشتر بخوانید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('read_more_text_fa', [
        'label' => __('Read More Button Text (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ]);

    // Individual Service Settings
    for ($i = 1; $i <= $max_services; $i++) {
        // Service Icon
        $wp_customize->add_setting("callamir_service_icon_{$i}", [
            'default' => 'fa-solid fa-computer',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("callamir_service_icon_{$i}", [
            'label' => __("Service {$i} Icon (FontAwesome class)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., fa-solid fa-laptop', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]);

        // Service Image
        $wp_customize->add_setting("callamir_service_image_{$i}", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "callamir_service_image_{$i}", [
            'label' => __("Service {$i} Image", 'callamir'),
            'section' => 'callamir_modern_services',
            'description' => __('Upload an image for the service modal (recommended: 800x600px)', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]));

        // Service Title (EN/FA)
        $wp_customize->add_setting("service_title_{$i}_en", [
            'default' => __("Service {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_title_{$i}_en", [
            'label' => __("Service {$i} Title (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'active_callback' => 'callamir_service_control_active',
        ]);

        $wp_customize->add_setting("service_title_{$i}_fa", [
            'default' => __("خدمت {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_title_{$i}_fa", [
            'label' => __("Service {$i} Title (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'active_callback' => 'callamir_service_control_active',
        ]);

        // Service Short Description (EN/FA)
        $wp_customize->add_setting("service_desc_{$i}_en", [
            'default' => __("Description for service {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_desc_{$i}_en", [
            'label' => __("Service {$i} Short Description (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'active_callback' => 'callamir_service_control_active',
        ]);

        $wp_customize->add_setting("service_desc_{$i}_fa", [
            'default' => __("توضیح خدمت {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_desc_{$i}_fa", [
            'label' => __("Service {$i} Short Description (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'active_callback' => 'callamir_service_control_active',
        ]);

        // Service Full Description (EN/FA)
        $wp_customize->add_setting("service_full_desc_{$i}_en", [
            'default' => __("Detailed description for service {$i}. This will be shown in the modal popup.", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_full_desc_{$i}_en", [
            'label' => __("Service {$i} Full Description (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'description' => __('This detailed description will be shown in the modal popup when users click "Read More".', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]);

        $wp_customize->add_setting("service_full_desc_{$i}_fa", [
            'default' => __("توضیح کامل خدمت {$i}. این متن در پنجره بازشو نمایش داده می‌شود.", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_full_desc_{$i}_fa", [
            'label' => __("Service {$i} Full Description (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'description' => __('This detailed description will be shown in the modal popup when users click "Read More".', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]);

        // Service Price (EN/FA)
        $wp_customize->add_setting("service_price_{$i}_en", [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_price_{$i}_en", [
            'label' => __("Service {$i} Price (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., "Starting from $99/month" or "Contact for pricing"', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]);

        $wp_customize->add_setting("service_price_{$i}_fa", [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control("service_price_{$i}_fa", [
            'label' => __("Service {$i} Price (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., "شروع از ۹۹ دلار در ماه" یا "برای قیمت تماس بگیرید"', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ]);

        $delete_setting = "callamir_service_delete_trigger_{$i}";
        $wp_customize->add_setting($delete_setting, [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        $wp_customize->add_control(new Callamir_Service_Delete_Control($wp_customize, $delete_setting, [
            'section' => 'callamir_modern_services',
            'service_id' => $i,
            'priority' => 200 + $i,
            'active_callback' => 'callamir_service_control_active',
        ]));

    }

    // Services Section Styling
    $wp_customize->add_setting('services_card_background', [
        'default' => 'rgba(255, 255, 255, 0.05)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_card_background', [
        'label' => __('Service Card Background', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS background value for service cards (e.g., rgba(255, 255, 255, 0.05))', 'callamir'),
    ]);

    $wp_customize->add_setting('services_card_border_color', [
        'default' => 'rgba(255, 255, 255, 0.1)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_card_border_color', [
        'label' => __('Service Card Border Color', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS border color for service cards', 'callamir'),
    ]);

    $wp_customize->add_setting('services_icon_color', [
        'default' => '#FFD700',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_icon_color', [
        'label' => __('Service Icon Color', 'callamir'),
        'section' => 'callamir_modern_services',
    ]));

    $wp_customize->add_setting('services_title_color', [
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_title_color', [
        'label' => __('Service Title Color', 'callamir'),
        'section' => 'callamir_modern_services',
    ]));

    $wp_customize->add_setting('services_description_color', [
        'default' => 'rgba(255, 255, 255, 0.8)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_description_color', [
        'label' => __('Service Description Color', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS color value for service descriptions', 'callamir'),
    ]);

    $wp_customize->add_setting('services_button_background', [
        'default' => 'linear-gradient(135deg, #FFD700, #FFA500)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_button_background', [
        'label' => __('Read More Button Background', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS background for the Read More button', 'callamir'),
    ]);

    $wp_customize->add_setting('services_button_text_color', [
        'default' => '#0A0A0A',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_button_text_color', [
        'label' => __('Read More Button Text Color', 'callamir'),
        'section' => 'callamir_modern_services',
    ]));

    // Additional Service Section Controls
    $wp_customize->add_setting('services_enable_cosmic_effect', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_enable_cosmic_effect', [
        'label' => __('Enable Cosmic Background Effect', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'checkbox',
        'description' => __('Enable subtle animated cosmic background effect', 'callamir'),
    ]);

    $wp_customize->add_setting('services_icon_size', [
        'default' => '32px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_icon_size', [
        'label' => __('Service Icon Size', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Icon size in pixels (e.g., 32px)', 'callamir'),
    ]);

    $wp_customize->add_setting('services_button_font_size', [
        'default' => '16px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_button_font_size', [
        'label' => __('Read More Button Font Size', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Button font size (e.g., 16px)', 'callamir'),
    ]);

    $wp_customize->add_setting('services_button_border_radius', [
        'default' => '8px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_button_border_radius', [
        'label' => __('Read More Button Border Radius', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Button border radius (e.g., 8px)', 'callamir'),
    ]);

    $wp_customize->add_setting('services_enable_liquid_effect', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('services_enable_liquid_effect', [
        'label' => __('Enable Liquid Hover Effect', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'checkbox',
        'description' => __('Enable liquid-style hover animation for buttons', 'callamir'),
    ]);

    // Contact Section
    $wp_customize->add_panel('callamir_contact_panel', [
        'title' => __('Contact Section', 'callamir'),
        'priority' => 40,
        'description' => __('Manage the contact area and form settings.', 'callamir'),
    ]);

    $wp_customize->add_section('callamir_contact', [
        'title' => __('Contact', 'callamir'),
        'priority' => 40,
        'panel' => 'callamir_contact_panel',
    ]);
    $wp_customize->add_setting('contact_title_en', [
        'default' => __('Contact Us', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_title_en', [
        'label' => __('Contact Section Title (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_title_fa', [
        'default' => __('تماس با ما', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_title_fa', [
        'label' => __('Contact Section Title (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_phone_en', [
        'default' => '416-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_phone_en', [
        'label' => __('Contact Phone Number (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('contact_phone_fa', [
        'default' => '416-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('contact_phone_fa', [
        'label' => __('Contact Phone Number (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('hero_btn_call_en', [
        'default' => __('Call Now', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_btn_call_en', [
        'label' => __('Call Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('hero_btn_call_fa', [
        'default' => __('اکنون تماس بگیرید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_btn_call_fa', [
        'label' => __('Call Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('hero_btn_support_en', [
        'default' => __('Get IT Support', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_btn_support_en', [
        'label' => __('Support Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('hero_btn_support_fa', [
        'default' => __('دریافت پشتیبانی آی تی', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_btn_support_fa', [
        'label' => __('Support Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('whatsapp_text_en', [
        'default' => __('WhatsApp', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('whatsapp_text_en', [
        'label' => __('WhatsApp Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('whatsapp_text_fa', [
        'default' => __('واتساپ', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('whatsapp_text_fa', [
        'label' => __('WhatsApp Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('telegram_text_en', [
        'default' => __('Telegram', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('telegram_text_en', [
        'label' => __('Telegram Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('telegram_text_fa', [
        'default' => __('تلگرام', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('telegram_text_fa', [
        'label' => __('Telegram Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('whatsapp_url', [
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('whatsapp_url', [
        'label' => __('WhatsApp URL', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('callamir_contact_form_shortcode', [
        'default' => '[contact-form-7 id="123" title="Contact form 1"]',
        'sanitize_callback' => 'callamir_sanitize_shortcode',
        'transport' => 'postMessage',
    ]);

    $wp_customize->add_control('callamir_contact_form_shortcode', [
        'label' => __('Contact Form 7 Shortcode', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
        'description' => __('Paste the Contact Form 7 shortcode, e.g. [contact-form-7 id="123" title="Contact form"]', 'callamir'),
    ]);

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('callamir_contact_form_shortcode', [
            'selector' => '#contact .callamir-contact-form',
            'render_callback' => 'callamir_render_contact_form_partial',
            'fallback_refresh' => true,
        ]);
    }
    $wp_customize->add_setting('telegram_url', [
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control('telegram_url', [
        'label' => __('Telegram URL', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'url',
    ]);

    // Blog Section
    $wp_customize->add_section('callamir_blog', [
        'title' => __('Blog', 'callamir'),
        'priority' => 50,
    ]);
    $wp_customize->add_setting('blog_title_en', [
        'default' => __('Tips & Daily Quotes', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('blog_title_en', [
        'label' => __('Blog Section Title (EN)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('blog_title_fa', [
        'default' => __('نکات و نقل قول‌های روزانه', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('blog_title_fa', [
        'label' => __('Blog Section Title (FA)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('blog_desc_en', [
        'default' => __('Latest insights, IT tips, and motivational posts.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('blog_desc_en', [
        'label' => __('Blog Section Description (EN)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'textarea',
    ]);
    $wp_customize->add_setting('blog_desc_fa', [
        'default' => __('آخرین نکات، ترفندهای آی تی و پست‌های انگیزشی.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('blog_desc_fa', [
        'label' => __('Blog Section Description (FA)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'textarea',
    ]);

    // Language Settings Section
    $wp_customize->add_section('callamir_language_settings', [
        'title' => __('Language Settings', 'callamir'),
        'priority' => 45,
        'description' => __('Configure language settings and flags', 'callamir')
    ]);

    // Default Site Language
    $wp_customize->add_setting('site_language', [
        'default' => 'en',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('site_language', [
        'label' => __('Default Site Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'select',
        'choices' => [
            'en' => __('English', 'callamir'),
            'fa' => __('Persian/Farsi', 'callamir'),
        ],
    ]);

    // Enable English
    $wp_customize->add_setting('enable_english', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('enable_english', [
        'label' => __('Enable English Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'checkbox',
    ]);

    // Enable Persian
    $wp_customize->add_setting('enable_persian', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    $wp_customize->add_control('enable_persian', [
        'label' => __('Enable Persian Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'checkbox',
    ]);

    // English Flag Image
    $wp_customize->add_setting('english_flag_image', [
        'default' => get_template_directory_uri() . '/images/uk-flag.png',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'english_flag_image', [
        'label' => __('English Flag Image', 'callamir'),
        'section' => 'callamir_language_settings',
    ]));

    // Persian Flag Image
    $wp_customize->add_setting('persian_flag_image', [
        'default' => get_template_directory_uri() . '/images/iran-flag.png',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'persian_flag_image', [
        'label' => __('Persian Flag Image', 'callamir'),
        'section' => 'callamir_language_settings',
    ]));

    // Flag Text
    $wp_customize->add_setting('flag_text_en', [
        'default' => __('Languages:', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('flag_text_en', [
        'label' => __('Flag Text (EN)', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('flag_text_fa', [
        'default' => __('زبان‌ها:', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('flag_text_fa', [
        'label' => __('Flag Text (FA)', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ]);

    // Flag Alt Text
    $wp_customize->add_setting('english_flag_alt', [
        'default' => __('English', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('english_flag_alt', [
        'label' => __('English Flag Alt Text', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('persian_flag_alt', [
        'default' => __('Persian', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('persian_flag_alt', [
        'label' => __('Persian Flag Alt Text', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ]);

    // Community Section
    $wp_customize->add_section('callamir_community', [
        'title' => __('Community', 'callamir'),
        'priority' => 60,
    ]);
    $wp_customize->add_setting('community_title_en', [
        'default' => __('Community Questions', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('community_title_en', [
        'label' => __('Community Section Title (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ]);
    $wp_customize->add_setting('community_title_fa', [
        'default' => __('سوالات جامعه', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('community_title_fa', [
        'label' => __('Community Section Title (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('community_subtitle_en', [
        'default' => __('Common questions and helpful answers from our community', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('community_subtitle_en', [
        'label' => __('Community Section Subtitle (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ]);

    $wp_customize->add_setting('community_subtitle_fa', [
        'default' => __('سوالات متداول و پاسخ‌های مفید از جامعه ما', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('community_subtitle_fa', [
        'label' => __('Community Section Subtitle (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ]);
    
    
    // Community Question Form Title
    $wp_customize->add_setting('community_question_form_title_en', [
        'default' => __('Ask a Question', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('community_question_form_title_en', [
        'label' => __('Question Form Title (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ]);
    
    $wp_customize->add_setting('community_question_form_title_fa', [
        'default' => __('سوال بپرسید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('community_question_form_title_fa', [
        'label' => __('Question Form Title (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ]);
    
    // Community Question Form Description
    $wp_customize->add_setting('community_question_form_desc_en', [
        'default' => __('Have a question? Ask our community and get helpful answers.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('community_question_form_desc_en', [
        'label' => __('Question Form Description (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ]);
    
    $wp_customize->add_setting('community_question_form_desc_fa', [
        'default' => __('سوالی دارید؟ از جامعه ما بپرسید و پاسخ‌های مفید دریافت کنید.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp_customize->add_control('community_question_form_desc_fa', [
        'label' => __('Question Form Description (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ]);

    for ($i = 1; $i <= 5; $i++) {
        $default_question_en = $i === 1 ? __('How do I request support?', 'callamir') : '';
        $default_question_fa = $i === 1 ? __('چگونه درخواست پشتیبانی بدهم؟', 'callamir') : '';
        $default_answer_en = $i === 1 ? __('Use the Get IT Support button above or WhatsApp/Telegram in Contact.', 'callamir') : '';
        $default_answer_fa = $i === 1 ? __('از دکمه دریافت پشتیبانی آی تی در بالا یا واتساپ/تلگرام در بخش تماس استفاده کنید.', 'callamir') : '';

        $wp_customize->add_setting("faq_q_{$i}_en", [
            'default' => $default_question_en,
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("faq_q_{$i}_en", [
            'label' => sprintf(__('FAQ %d Question (EN)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("faq_q_{$i}_fa", [
            'default' => $default_question_fa,
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("faq_q_{$i}_fa", [
            'label' => sprintf(__('FAQ %d Question (FA)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("faq_a_{$i}_en", [
            'default' => $default_answer_en,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("faq_a_{$i}_en", [
            'label' => sprintf(__('FAQ %d Answer (EN)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'textarea',
        ]);

        $wp_customize->add_setting("faq_a_{$i}_fa", [
            'default' => $default_answer_fa,
            'sanitize_callback' => 'sanitize_textarea_field',
        ]);
        $wp_customize->add_control("faq_a_{$i}_fa", [
            'label' => sprintf(__('FAQ %d Answer (FA)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'textarea',
        ]);
    }

    // Hero Section Language Settings
    $wp_customize->add_setting('hero_title_en', [
        'default' => __('Simplifying Tech for Seniors & Small Businesses', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_title_en', [
        'label' => __('Hero Title (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('hero_text_en', [
        'default' => __('Friendly, professional, and reasonably priced IT support in Toronto.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_text_en', [
        'label' => __('Hero Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('hero_short_desc_en', [
        'default' => __('We help non-technical users solve tech problems quickly and kindly.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_short_desc_en', [
        'label' => __('Hero Short Description (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    // Add missing language settings for better Persian support
    $wp_customize->add_setting('hero_title_fa', [
        'default' => __('ساده‌سازی فناوری برای سالمندان و کسب‌وکارهای کوچک', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_title_fa', [
        'label' => __('Hero Title (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('hero_text_fa', [
        'default' => __('پشتیبانی آی‌تی دوستانه، حرفه‌ای و با قیمت مناسب در تورنتو.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_text_fa', [
        'label' => __('Hero Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('hero_short_desc_fa', [
        'default' => __('ما به کاربران غیرفنی کمک می‌کنیم تا مشکلات فناوری را سریع و مهربان حل کنند.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('hero_short_desc_fa', [
        'label' => __('Hero Short Description (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ]);
}
add_action('customize_register', 'callamir_customize_register');

/**
 * Render the contact form output for selective refresh.
 */
function callamir_render_contact_form_partial() {
    $contact_form_shortcode = get_theme_mod('callamir_contact_form_shortcode', '[contact-form-7 id="123" title="Contact form 1"]');
    $contact_form_output = do_shortcode($contact_form_shortcode);

    if (empty($contact_form_shortcode) || stripos($contact_form_output, 'contact form not found') !== false) {
        return '<div class="callamir-contact-form-warning">' . esc_html__('Please update the Contact form shortcode in the Customizer.', 'callamir') . '</div>';
    }

    return $contact_form_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
