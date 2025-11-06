<?php
/**
 * Customizer registration hooks.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_customize_register($wp_customize) {
    // Theme Colors Section
    $wp_customize->add_section('theme_colors', array(
        'title' => __('Theme Colors', 'callamir'),
        'priority' => 25,
        'description' => __('Customize the main colors of your theme', 'callamir')
    ));
    $default_colors = array(
        'primary_color' => '#041635',
        'accent_color' => '#ebb900',
        'green_color' => '#318e00',
        'danger_color' => '#e52021',
        'text_color' => '#041635',
        'section_bg_color' => '#f0f4f8',
        'border_color' => '#444444',
        'cosmic_primary' => '#0A0A0A',
        'cosmic_accent' => '#FFD700',
    );
    foreach ($default_colors as $key => $default_value) {
        $wp_customize->add_setting($key, array(
            'default' => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'theme_colors',
        )));
    }

    $section_color_settings = array(
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
    );

    foreach ($section_color_settings as $key => $default_value) {
        $wp_customize->add_setting($key, array(
            'default' => $default_value,
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, array(
            'label' => ucwords(str_replace('_', ' ', $key)),
            'section' => 'theme_colors',
        )));
    }

    // Header & Footer Settings Section
    $wp_customize->add_section('callamir_header_footer', array(
        'title' => __('Header & Footer Settings', 'callamir'),
        'priority' => 28,
        'description' => __('Customize header and footer layout', 'callamir')
    ));
    $wp_customize->add_setting('header_justify_content', array(
        'default' => 'space-between',
        'sanitize_callback' => 'callamir_sanitize_select',
    ));
    $wp_customize->add_control('header_justify_content', array(
        'label' => __('Header Justify Content', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => array(
            'space-between' => __('Space Between', 'callamir'),
            'space-around' => __('Space Around', 'callamir'),
            'center' => __('Center', 'callamir'),
            'flex-start' => __('Start', 'callamir'),
            'flex-end' => __('End', 'callamir'),
        ),
    ));
    $wp_customize->add_setting('header_justify_content_mobile', array(
        'default' => 'space-between',
        'sanitize_callback' => 'callamir_sanitize_select',
    ));
    $wp_customize->add_control('header_justify_content_mobile', array(
        'label' => __('Header Justify Content (Mobile)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => array(
            'space-between' => __('Space Between', 'callamir'),
            'space-around' => __('Space Around', 'callamir'),
            'center' => __('Center', 'callamir'),
            'flex-start' => __('Start', 'callamir'),
            'flex-end' => __('End', 'callamir'),
        ),
    ));
    $wp_customize->add_setting('header_logo_alignment', array(
        'default' => 'left',
        'sanitize_callback' => 'callamir_sanitize_select',
    ));
    $wp_customize->add_control('header_logo_alignment', array(
        'label' => __('Logo Alignment', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'select',
        'choices' => array(
            'left' => __('Left', 'callamir'),
            'center' => __('Center', 'callamir'),
            'right' => __('Right', 'callamir'),
        ),
    ));
    $wp_customize->add_setting('logo_max_height', array(
        'default' => '80px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('logo_max_height', array(
        'label' => __('Logo Max Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 80px', 'callamir'),
    ));

    $wp_customize->add_setting('logo_text_en', array(
        'default' => __('CallAmir', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('logo_text_en', array(
        'label' => __('Logo Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ));

    $wp_customize->add_setting('logo_text_fa', array(
        'default' => __('کال امیر', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('logo_text_fa', array(
        'label' => __('Logo Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ));
    $wp_customize->add_setting('leave_message_width', array(
        'default' => '200px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('leave_message_width', array(
        'label' => __('Leave Message Button Width', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 200px', 'callamir'),
    ));
    $wp_customize->add_setting('leave_message_height', array(
        'default' => '50px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('leave_message_height', array(
        'label' => __('Leave Message Button Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 50px', 'callamir'),
    ));

    $wp_customize->add_setting('leave_message_text_en', array(
        'default' => __('Leave a message', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('leave_message_text_en', array(
        'label' => __('Leave Message Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ));

    $wp_customize->add_setting('leave_message_text_fa', array(
        'default' => __('پیام بگذارید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('leave_message_text_fa', array(
        'label' => __('Leave Message Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
    ));
    $wp_customize->add_setting('footer_min_height', array(
        'default' => '100px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('footer_min_height', array(
        'label' => __('Footer Minimum Height', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Enter value with unit, e.g., 100px', 'callamir'),
    ));

    $wp_customize->add_setting('copyright_text_en', array(
        'default' => __('CallAmir. All rights reserved © {year}', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('copyright_text_en', array(
        'label' => __('Footer Copyright Text (EN)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Use {year} to automatically insert the current year.', 'callamir'),
    ));

    $wp_customize->add_setting('copyright_text_fa', array(
        'default' => __('کال امیر. کلیه حقوق محفوظ است © {year}', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('copyright_text_fa', array(
        'label' => __('Footer Copyright Text (FA)', 'callamir'),
        'section' => 'callamir_header_footer',
        'type' => 'text',
        'description' => __('Use {year} to automatically insert the current year.', 'callamir'),
    ));

    // Typography & Layout Section
    $wp_customize->add_section('callamir_typography_layout', array(
        'title' => __('Typography & Layout', 'callamir'),
        'priority' => 29,
        'description' => __('Control fonts and layout spacing used throughout the theme.', 'callamir'),
    ));

    $wp_customize->add_setting('body_font_family', array(
        'default' => "'Roboto', Arial, sans-serif",
        'sanitize_callback' => 'callamir_sanitize_font_family',
    ));
    $wp_customize->add_control('body_font_family', array(
        'label' => __('Body Font Family', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Example: "Roboto, Arial, sans-serif"', 'callamir'),
    ));

    $wp_customize->add_setting('heading_font_family', array(
        'default' => "'Montserrat', 'Roboto', Arial, sans-serif",
        'sanitize_callback' => 'callamir_sanitize_font_family',
    ));
    $wp_customize->add_control('heading_font_family', array(
        'label' => __('Heading Font Family', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ));

    $wp_customize->add_setting('base_font_size', array(
        'default' => '16px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('base_font_size', array(
        'label' => __('Base Font Size', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Set the default font size (e.g., 16px).', 'callamir'),
    ));

    $wp_customize->add_setting('container_max_width', array(
        'default' => '1200px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('container_max_width', array(
        'label' => __('Content Max Width', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Maximum width for layout containers (e.g., 1200px).', 'callamir'),
    ));

    $wp_customize->add_setting('section_vertical_padding', array(
        'default' => '4rem',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('section_vertical_padding', array(
        'label' => __('Section Vertical Padding', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
        'description' => __('Controls top and bottom padding for major sections.', 'callamir'),
    ));

    $wp_customize->add_setting('section_min_height', array(
        'default' => '320px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('section_min_height', array(
        'label' => __('Section Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_min_height', array(
        'default' => '420px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('hero_min_height', array(
        'label' => __('Hero Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ));

    $wp_customize->add_setting('header_min_height', array(
        'default' => '80px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('header_min_height', array(
        'label' => __('Header Minimum Height', 'callamir'),
        'section' => 'callamir_typography_layout',
        'type' => 'text',
    ));

    // Cosmic Effects Section
    $wp_customize->add_section('callamir_cosmic_effects', array(
        'title' => __('Cosmic Effects', 'callamir'),
        'priority' => 30,
        'description' => __('Customize cosmic visual effects for the theme', 'callamir')
    ));
    // Header Stars
    $wp_customize->add_setting('callamir_enable_header_stars', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('callamir_enable_header_stars', array(
        'label' => __('Enable Header Stars', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ));
    $wp_customize->add_setting('callamir_star_count_header', array(
        'default' => 100,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_star_count_header', array(
        'label' => __('Header Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 500, 'step' => 10),
    ));
    // Footer Stars
    $wp_customize->add_setting('callamir_enable_footer_stars', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('callamir_enable_footer_stars', array(
        'label' => __('Enable Footer Stars', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ));
    $wp_customize->add_setting('callamir_star_count_footer', array(
        'default' => 100,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_star_count_footer', array(
        'label' => __('Footer Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 500, 'step' => 10),
    ));
    // Hero Effect
    $wp_customize->add_setting('callamir_enable_hero_effect', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('callamir_enable_hero_effect', array(
        'label' => __('Enable Hero Cosmic Effect', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ));
    $wp_customize->add_setting('callamir_hero_blackhole_pattern', array(
        'default' => 'circular',
        'sanitize_callback' => 'callamir_sanitize_select',
    ));
    $wp_customize->add_control('callamir_hero_blackhole_pattern', array(
        'label' => __('Hero Blackhole Pattern', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'select',
        'choices' => array(
            'circular' => __('Circular', 'callamir'),
            'hexagon' => __('Hexagon Network', 'callamir'),
        ),
    ));
    $wp_customize->add_setting('callamir_hero_circle_count', array(
        'default' => 50,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_hero_circle_count', array(
        'label' => __('Hero Circle Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 200, 'step' => 10),
    ));
    $wp_customize->add_setting('callamir_hero_star_count', array(
        'default' => 150,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_hero_star_count', array(
        'label' => __('Hero Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 500, 'step' => 10),
    ));
    // Services Effect
    $wp_customize->add_setting('callamir_enable_services_effect', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('callamir_enable_services_effect', array(
        'label' => __('Enable Services Cosmic Effect', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'checkbox',
    ));
    $wp_customize->add_setting('callamir_services_pattern', array(
        'default' => 'circular',
        'sanitize_callback' => 'callamir_sanitize_select',
    ));
    $wp_customize->add_control('callamir_services_pattern', array(
        'label' => __('Services Pattern', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'select',
        'choices' => array(
            'circular' => __('Circular', 'callamir'),
            'hexagon' => __('Hexagon Network', 'callamir'),
        ),
    ));
    $wp_customize->add_setting('callamir_services_circle_count', array(
        'default' => 50,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_services_circle_count', array(
        'label' => __('Services Circle Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 200, 'step' => 10),
    ));
    $wp_customize->add_setting('callamir_services_star_count', array(
        'default' => 150,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('callamir_services_star_count', array(
        'label' => __('Services Star Count', 'callamir'),
        'section' => 'callamir_cosmic_effects',
        'type' => 'number',
        'input_attrs' => array('min' => 10, 'max' => 500, 'step' => 10),
    ));

    // Modern Services Section
    $wp_customize->add_section('callamir_modern_services', array(
        'title' => __('Modern Services Section', 'callamir'),
        'priority' => 35,
        'description' => __('Customize the modern services section with advanced features including modals, contact forms, and detailed descriptions.', 'callamir')
    ));

    // Services Section Settings
    $wp_customize->add_setting('callamir_services_count', array(
        'default' => 3,
        'sanitize_callback' => 'callamir_sanitize_service_count',
        'transport' => 'postMessage',
    ));
    $wp_customize->add_control('callamir_services_count', array(
        'label' => __('Number of Services', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'number',
        'input_attrs' => array('min' => 0, 'max' => callamir_max_services(), 'step' => 1),
        'description' => __('Set to 0 to hide all services.', 'callamir'),
    ));

    // Services Section Title and Subtitle
    $wp_customize->add_setting('services_title_en', array(
        'default' => __('Our Services', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('services_title_en', array(
        'label' => __('Services Section Title (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ));

    $wp_customize->add_setting('services_title_fa', array(
        'default' => __('خدمات ما', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('services_title_fa', array(
        'label' => __('Services Section Title (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ));

    $wp_customize->add_setting('services_subtitle_en', array(
        'default' => __('Professional IT solutions tailored to your needs', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('services_subtitle_en', array(
        'label' => __('Services Section Subtitle (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('services_subtitle_fa', array(
        'default' => __('راه‌حل‌های حرفه‌ای آی‌تی متناسب با نیازهای شما', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('services_subtitle_fa', array(
        'label' => __('Services Section Subtitle (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'textarea',
    ));

    // Read More Button Text
    $wp_customize->add_setting('read_more_text_en', array(
        'default' => __('Read More', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('read_more_text_en', array(
        'label' => __('Read More Button Text (EN)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ));

    $wp_customize->add_setting('read_more_text_fa', array(
        'default' => __('بیشتر بخوانید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('read_more_text_fa', array(
        'label' => __('Read More Button Text (FA)', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
    ));

    // Individual Service Settings
    for ($i = 1; $i <= callamir_max_services(); $i++) {
        // Service Icon
        $wp_customize->add_setting("callamir_service_icon_{$i}", array(
            'default' => 'fa-solid fa-computer',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("callamir_service_icon_{$i}", array(
            'label' => __("Service {$i} Icon (FontAwesome class)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., fa-solid fa-laptop', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ));

        // Service Image
        $wp_customize->add_setting("callamir_service_image_{$i}", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "callamir_service_image_{$i}", array(
            'label' => __("Service {$i} Image", 'callamir'),
            'section' => 'callamir_modern_services',
            'description' => __('Upload an image for the service modal (recommended: 800x600px)', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        )));

        // Service Title (EN/FA)
        $wp_customize->add_setting("service_title_{$i}_en", array(
            'default' => __("Service {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("service_title_{$i}_en", array(
            'label' => __("Service {$i} Title (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'active_callback' => 'callamir_service_control_active',
        ));

        $wp_customize->add_setting("service_title_{$i}_fa", array(
            'default' => __("خدمت {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("service_title_{$i}_fa", array(
            'label' => __("Service {$i} Title (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'active_callback' => 'callamir_service_control_active',
        ));

        // Service Short Description (EN/FA)
        $wp_customize->add_setting("service_desc_{$i}_en", array(
            'default' => __("Description for service {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("service_desc_{$i}_en", array(
            'label' => __("Service {$i} Short Description (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'active_callback' => 'callamir_service_control_active',
        ));

        $wp_customize->add_setting("service_desc_{$i}_fa", array(
            'default' => __("توضیح خدمت {$i}", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("service_desc_{$i}_fa", array(
            'label' => __("Service {$i} Short Description (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'active_callback' => 'callamir_service_control_active',
        ));

        // Service Full Description (EN/FA)
        $wp_customize->add_setting("service_full_desc_{$i}_en", array(
            'default' => __("Detailed description for service {$i}. This will be shown in the modal popup.", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("service_full_desc_{$i}_en", array(
            'label' => __("Service {$i} Full Description (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'description' => __('This detailed description will be shown in the modal popup when users click "Read More".', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ));

        $wp_customize->add_setting("service_full_desc_{$i}_fa", array(
            'default' => __("توضیح کامل خدمت {$i}. این متن در پنجره بازشو نمایش داده می‌شود.", 'callamir'),
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("service_full_desc_{$i}_fa", array(
            'label' => __("Service {$i} Full Description (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'textarea',
            'description' => __('This detailed description will be shown in the modal popup when users click "Read More".', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ));

        // Service Price (EN/FA)
        $wp_customize->add_setting("service_price_{$i}_en", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("service_price_{$i}_en", array(
            'label' => __("Service {$i} Price (EN)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., "Starting from $99/month" or "Contact for pricing"', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ));

        $wp_customize->add_setting("service_price_{$i}_fa", array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("service_price_{$i}_fa", array(
            'label' => __("Service {$i} Price (FA)", 'callamir'),
            'section' => 'callamir_modern_services',
            'type' => 'text',
            'description' => __('e.g., "شروع از ۹۹ دلار در ماه" یا "برای قیمت تماس بگیرید"', 'callamir'),
            'active_callback' => 'callamir_service_control_active',
        ));

        $delete_setting = "callamir_service_delete_trigger_{$i}";
        $wp_customize->add_setting($delete_setting, array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ));
        $wp_customize->add_control(new Callamir_Service_Delete_Control($wp_customize, $delete_setting, array(
            'section' => 'callamir_modern_services',
            'service_id' => $i,
            'priority' => 200 + $i,
            'active_callback' => 'callamir_service_control_active',
        )));

    }

    // Services Section Styling
    $wp_customize->add_setting('services_card_background', array(
        'default' => 'rgba(255, 255, 255, 0.05)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
    ));
    $wp_customize->add_control('services_card_background', array(
        'label' => __('Service Card Background', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS background value for service cards (e.g., rgba(255, 255, 255, 0.05))', 'callamir'),
    ));

    $wp_customize->add_setting('services_card_border_color', array(
        'default' => 'rgba(255, 255, 255, 0.1)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
    ));
    $wp_customize->add_control('services_card_border_color', array(
        'label' => __('Service Card Border Color', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS border color for service cards', 'callamir'),
    ));

    $wp_customize->add_setting('services_icon_color', array(
        'default' => '#FFD700',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_icon_color', array(
        'label' => __('Service Icon Color', 'callamir'),
        'section' => 'callamir_modern_services',
    )));

    $wp_customize->add_setting('services_title_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_title_color', array(
        'label' => __('Service Title Color', 'callamir'),
        'section' => 'callamir_modern_services',
    )));

    $wp_customize->add_setting('services_description_color', array(
        'default' => 'rgba(255, 255, 255, 0.8)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
    ));
    $wp_customize->add_control('services_description_color', array(
        'label' => __('Service Description Color', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS color value for service descriptions', 'callamir'),
    ));

    $wp_customize->add_setting('services_button_background', array(
        'default' => 'linear-gradient(135deg, #FFD700, #FFA500)',
        'sanitize_callback' => 'callamir_sanitize_css_value',
    ));
    $wp_customize->add_control('services_button_background', array(
        'label' => __('Read More Button Background', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('CSS background for the Read More button', 'callamir'),
    ));

    $wp_customize->add_setting('services_button_text_color', array(
        'default' => '#0A0A0A',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'services_button_text_color', array(
        'label' => __('Read More Button Text Color', 'callamir'),
        'section' => 'callamir_modern_services',
    )));

    // Additional Service Section Controls
    $wp_customize->add_setting('services_enable_cosmic_effect', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('services_enable_cosmic_effect', array(
        'label' => __('Enable Cosmic Background Effect', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'checkbox',
        'description' => __('Enable subtle animated cosmic background effect', 'callamir'),
    ));

    $wp_customize->add_setting('services_icon_size', array(
        'default' => '32px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('services_icon_size', array(
        'label' => __('Service Icon Size', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Icon size in pixels (e.g., 32px)', 'callamir'),
    ));

    $wp_customize->add_setting('services_button_font_size', array(
        'default' => '16px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('services_button_font_size', array(
        'label' => __('Read More Button Font Size', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Button font size (e.g., 16px)', 'callamir'),
    ));

    $wp_customize->add_setting('services_button_border_radius', array(
        'default' => '8px',
        'sanitize_callback' => 'callamir_sanitize_dimension',
    ));
    $wp_customize->add_control('services_button_border_radius', array(
        'label' => __('Read More Button Border Radius', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'text',
        'description' => __('Button border radius (e.g., 8px)', 'callamir'),
    ));

    $wp_customize->add_setting('services_enable_liquid_effect', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('services_enable_liquid_effect', array(
        'label' => __('Enable Liquid Hover Effect', 'callamir'),
        'section' => 'callamir_modern_services',
        'type' => 'checkbox',
        'description' => __('Enable liquid-style hover animation for buttons', 'callamir'),
    ));

    // Contact Section
    $wp_customize->add_section('callamir_contact', array(
        'title' => __('Contact', 'callamir'),
        'priority' => 40,
    ));
    $wp_customize->add_setting('contact_title_en', array(
        'default' => __('Contact Us', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_title_en', array(
        'label' => __('Contact Section Title (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('contact_title_fa', array(
        'default' => __('تماس با ما', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_title_fa', array(
        'label' => __('Contact Section Title (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('contact_phone_en', array(
        'default' => '416-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_phone_en', array(
        'label' => __('Contact Phone Number (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('contact_phone_fa', array(
        'default' => '416-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_phone_fa', array(
        'label' => __('Contact Phone Number (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('hero_btn_call_en', array(
        'default' => __('Call Now', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_btn_call_en', array(
        'label' => __('Call Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('hero_btn_call_fa', array(
        'default' => __('اکنون تماس بگیرید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_btn_call_fa', array(
        'label' => __('Call Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('hero_btn_support_en', array(
        'default' => __('Get IT Support', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_btn_support_en', array(
        'label' => __('Support Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('hero_btn_support_fa', array(
        'default' => __('دریافت پشتیبانی آی تی', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_btn_support_fa', array(
        'label' => __('Support Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('whatsapp_text_en', array(
        'default' => __('WhatsApp', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('whatsapp_text_en', array(
        'label' => __('WhatsApp Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('whatsapp_text_fa', array(
        'default' => __('واتساپ', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('whatsapp_text_fa', array(
        'label' => __('WhatsApp Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('telegram_text_en', array(
        'default' => __('Telegram', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('telegram_text_en', array(
        'label' => __('Telegram Button Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('telegram_text_fa', array(
        'default' => __('تلگرام', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('telegram_text_fa', array(
        'label' => __('Telegram Button Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
    $wp_customize->add_setting('whatsapp_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('whatsapp_url', array(
        'label' => __('WhatsApp URL', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'url',
    ));
    $wp_customize->add_setting('telegram_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('telegram_url', array(
        'label' => __('Telegram URL', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'url',
    ));

    // Blog Section
    $wp_customize->add_section('callamir_blog', array(
        'title' => __('Blog', 'callamir'),
        'priority' => 50,
    ));
    $wp_customize->add_setting('blog_title_en', array(
        'default' => __('Tips & Daily Quotes', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('blog_title_en', array(
        'label' => __('Blog Section Title (EN)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'text',
    ));
    $wp_customize->add_setting('blog_title_fa', array(
        'default' => __('نکات و نقل قول‌های روزانه', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('blog_title_fa', array(
        'label' => __('Blog Section Title (FA)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'text',
    ));
    $wp_customize->add_setting('blog_desc_en', array(
        'default' => __('Latest insights, IT tips, and motivational posts.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('blog_desc_en', array(
        'label' => __('Blog Section Description (EN)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'textarea',
    ));
    $wp_customize->add_setting('blog_desc_fa', array(
        'default' => __('آخرین نکات، ترفندهای آی تی و پست‌های انگیزشی.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('blog_desc_fa', array(
        'label' => __('Blog Section Description (FA)', 'callamir'),
        'section' => 'callamir_blog',
        'type' => 'textarea',
    ));

    // Language Settings Section
    $wp_customize->add_section('callamir_language_settings', array(
        'title' => __('Language Settings', 'callamir'),
        'priority' => 45,
        'description' => __('Configure language settings and flags', 'callamir')
    ));

    // Default Site Language
    $wp_customize->add_setting('site_language', array(
        'default' => 'en',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('site_language', array(
        'label' => __('Default Site Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'select',
        'choices' => array(
            'en' => __('English', 'callamir'),
            'fa' => __('Persian/Farsi', 'callamir'),
        ),
    ));

    // Enable English
    $wp_customize->add_setting('enable_english', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('enable_english', array(
        'label' => __('Enable English Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'checkbox',
    ));

    // Enable Persian
    $wp_customize->add_setting('enable_persian', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('enable_persian', array(
        'label' => __('Enable Persian Language', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'checkbox',
    ));

    // English Flag Image
    $wp_customize->add_setting('english_flag_image', array(
        'default' => get_template_directory_uri() . '/images/uk-flag.png',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'english_flag_image', array(
        'label' => __('English Flag Image', 'callamir'),
        'section' => 'callamir_language_settings',
    )));

    // Persian Flag Image
    $wp_customize->add_setting('persian_flag_image', array(
        'default' => get_template_directory_uri() . '/images/iran-flag.png',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'persian_flag_image', array(
        'label' => __('Persian Flag Image', 'callamir'),
        'section' => 'callamir_language_settings',
    )));

    // Flag Text
    $wp_customize->add_setting('flag_text_en', array(
        'default' => __('Languages:', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('flag_text_en', array(
        'label' => __('Flag Text (EN)', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('flag_text_fa', array(
        'default' => __('زبان‌ها:', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('flag_text_fa', array(
        'label' => __('Flag Text (FA)', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ));

    // Flag Alt Text
    $wp_customize->add_setting('english_flag_alt', array(
        'default' => __('English', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('english_flag_alt', array(
        'label' => __('English Flag Alt Text', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ));

    $wp_customize->add_setting('persian_flag_alt', array(
        'default' => __('Persian', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('persian_flag_alt', array(
        'label' => __('Persian Flag Alt Text', 'callamir'),
        'section' => 'callamir_language_settings',
        'type' => 'text',
    ));

    // Community Section
    $wp_customize->add_section('callamir_community', array(
        'title' => __('Community', 'callamir'),
        'priority' => 60,
    ));
    $wp_customize->add_setting('community_title_en', array(
        'default' => __('Community Questions', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('community_title_en', array(
        'label' => __('Community Section Title (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ));
    $wp_customize->add_setting('community_title_fa', array(
        'default' => __('سوالات جامعه', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('community_title_fa', array(
        'label' => __('Community Section Title (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ));

    $wp_customize->add_setting('community_subtitle_en', array(
        'default' => __('Common questions and helpful answers from our community', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('community_subtitle_en', array(
        'label' => __('Community Section Subtitle (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ));

    $wp_customize->add_setting('community_subtitle_fa', array(
        'default' => __('سوالات متداول و پاسخ‌های مفید از جامعه ما', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('community_subtitle_fa', array(
        'label' => __('Community Section Subtitle (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ));
    
    
    // Community Question Form Title
    $wp_customize->add_setting('community_question_form_title_en', array(
        'default' => __('Ask a Question', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('community_question_form_title_en', array(
        'label' => __('Question Form Title (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('community_question_form_title_fa', array(
        'default' => __('سوال بپرسید', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('community_question_form_title_fa', array(
        'label' => __('Question Form Title (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'text',
    ));
    
    // Community Question Form Description
    $wp_customize->add_setting('community_question_form_desc_en', array(
        'default' => __('Have a question? Ask our community and get helpful answers.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('community_question_form_desc_en', array(
        'label' => __('Question Form Description (EN)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('community_question_form_desc_fa', array(
        'default' => __('سوالی دارید؟ از جامعه ما بپرسید و پاسخ‌های مفید دریافت کنید.', 'callamir'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('community_question_form_desc_fa', array(
        'label' => __('Question Form Description (FA)', 'callamir'),
        'section' => 'callamir_community',
        'type' => 'textarea',
    ));

    for ($i = 1; $i <= 5; $i++) {
        $default_question_en = $i === 1 ? __('How do I request support?', 'callamir') : '';
        $default_question_fa = $i === 1 ? __('چگونه درخواست پشتیبانی بدهم؟', 'callamir') : '';
        $default_answer_en = $i === 1 ? __('Use the Get IT Support button above or WhatsApp/Telegram in Contact.', 'callamir') : '';
        $default_answer_fa = $i === 1 ? __('از دکمه دریافت پشتیبانی آی تی در بالا یا واتساپ/تلگرام در بخش تماس استفاده کنید.', 'callamir') : '';

        $wp_customize->add_setting("faq_q_{$i}_en", array(
            'default' => $default_question_en,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("faq_q_{$i}_en", array(
            'label' => sprintf(__('FAQ %d Question (EN)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'text',
        ));

        $wp_customize->add_setting("faq_q_{$i}_fa", array(
            'default' => $default_question_fa,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control("faq_q_{$i}_fa", array(
            'label' => sprintf(__('FAQ %d Question (FA)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'text',
        ));

        $wp_customize->add_setting("faq_a_{$i}_en", array(
            'default' => $default_answer_en,
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("faq_a_{$i}_en", array(
            'label' => sprintf(__('FAQ %d Answer (EN)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'textarea',
        ));

        $wp_customize->add_setting("faq_a_{$i}_fa", array(
            'default' => $default_answer_fa,
            'sanitize_callback' => 'sanitize_textarea_field',
        ));
        $wp_customize->add_control("faq_a_{$i}_fa", array(
            'label' => sprintf(__('FAQ %d Answer (FA)', 'callamir'), $i),
            'section' => 'callamir_community',
            'type' => 'textarea',
        ));
    }

    // Hero Section Language Settings
    $wp_customize->add_setting('hero_title_en', array(
        'default' => __('Simplifying Tech for Seniors & Small Businesses', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_title_en', array(
        'label' => __('Hero Title (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_text_en', array(
        'default' => __('Friendly, professional, and reasonably priced IT support in Toronto.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_text_en', array(
        'label' => __('Hero Text (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_short_desc_en', array(
        'default' => __('We help non-technical users solve tech problems quickly and kindly.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_short_desc_en', array(
        'label' => __('Hero Short Description (EN)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    // Add missing language settings for better Persian support
    $wp_customize->add_setting('hero_title_fa', array(
        'default' => __('ساده‌سازی فناوری برای سالمندان و کسب‌وکارهای کوچک', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_title_fa', array(
        'label' => __('Hero Title (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_text_fa', array(
        'default' => __('پشتیبانی آی‌تی دوستانه، حرفه‌ای و با قیمت مناسب در تورنتو.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_text_fa', array(
        'label' => __('Hero Text (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));

    $wp_customize->add_setting('hero_short_desc_fa', array(
        'default' => __('ما به کاربران غیرفنی کمک می‌کنیم تا مشکلات فناوری را سریع و مهربان حل کنند.', 'callamir'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_short_desc_fa', array(
        'label' => __('Hero Short Description (FA)', 'callamir'),
        'section' => 'callamir_contact',
        'type' => 'text',
    ));
}
add_action('customize_register', 'callamir_customize_register');
