<?php
/**
 * Dynamic CSS generation for theme modifiers and cosmic effects.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_generate_dynamic_css() {
    $primary = esc_attr(get_theme_mod('primary_color', '#041635'));
    $accent = esc_attr(get_theme_mod('accent_color', '#ebb900'));
    $green = esc_attr(get_theme_mod('green_color', '#318e00'));
    $danger = esc_attr(get_theme_mod('danger_color', '#e52021'));
    $text = esc_attr(get_theme_mod('text_color', '#041635'));
    $section_bg = esc_attr(get_theme_mod('section_bg_color', '#f0f4f8'));
    $border = esc_attr(get_theme_mod('border_color', '#444444'));
    $cosmic_primary = esc_attr(get_theme_mod('cosmic_primary', '#0A0A0A'));
    $cosmic_accent = esc_attr(get_theme_mod('cosmic_accent', '#FFD700'));
    $enable_header_stars = get_theme_mod('callamir_enable_header_stars', true);
    $enable_footer_stars = get_theme_mod('callamir_enable_footer_stars', true);
    $enable_hero_effect = get_theme_mod('callamir_enable_hero_effect', true);
    $enable_services_effect = get_theme_mod('callamir_enable_services_effect', true);
    $header_justify = esc_attr(get_theme_mod('header_justify_content', 'space-between'));
    $header_justify_mobile = esc_attr(get_theme_mod('header_justify_content_mobile', 'space-between'));
    $logo_align = esc_attr(get_theme_mod('header_logo_alignment', 'left'));
    $logo_max_height = esc_attr(get_theme_mod('logo_max_height', '80px'));
    $leave_message_width = esc_attr(get_theme_mod('leave_message_width', '200px'));
    $leave_message_height = esc_attr(get_theme_mod('leave_message_height', '50px'));
    $footer_min_height = esc_attr(get_theme_mod('footer_min_height', '100px'));
    $body_font_family = callamir_sanitize_font_family(get_theme_mod('body_font_family', "'Roboto', Arial, sans-serif"));
    $heading_font_family = callamir_sanitize_font_family(get_theme_mod('heading_font_family', "'Montserrat', 'Roboto', Arial, sans-serif"));
    $base_font_size = callamir_sanitize_dimension(get_theme_mod('base_font_size', '16px'));
    $container_max_width = callamir_sanitize_dimension(get_theme_mod('container_max_width', '1200px'));
    $section_vertical_padding = callamir_sanitize_dimension(get_theme_mod('section_vertical_padding', '4rem'));
    $section_min_height = callamir_sanitize_dimension(get_theme_mod('section_min_height', '320px'));
    $hero_min_height = callamir_sanitize_dimension(get_theme_mod('hero_min_height', '420px'));
    $header_min_height = callamir_sanitize_dimension(get_theme_mod('header_min_height', '80px'));
    
    // Section and typography colors
    $hero_title_color = esc_attr(get_theme_mod('hero_title_color', '#ffffff'));
    $hero_text_color = esc_attr(get_theme_mod('hero_text_color', '#dbeafe'));
    $section_title_color = esc_attr(get_theme_mod('section_title_color', '#ffffff'));
    $section_title_accent = esc_attr(get_theme_mod('section_title_accent_color', '#ffd700'));
    $section_subtitle_color = esc_attr(get_theme_mod('section_subtitle_color', '#e5e7eb'));
    $contact_form_background = esc_attr(get_theme_mod('contact_form_background_color', '#111827'));
    $contact_form_border = esc_attr(get_theme_mod('contact_form_border_color', '#1f2937'));
    $contact_form_field_background = esc_attr(get_theme_mod('contact_form_field_background', '#0b1220'));
    $contact_form_field_color = esc_attr(get_theme_mod('contact_form_field_color', '#f9fafb'));
    $contact_form_button_background = esc_attr(get_theme_mod('contact_form_button_background', '#facc15'));
    $contact_form_button_color = esc_attr(get_theme_mod('contact_form_button_color', '#111827'));

    // Services section styling
    $services_card_bg = esc_attr(get_theme_mod('services_card_background', 'rgba(255, 255, 255, 0.05)'));
    $services_card_border = esc_attr(get_theme_mod('services_card_border_color', 'rgba(255, 255, 255, 0.1)'));
    $services_icon_color = esc_attr(get_theme_mod('services_icon_color', '#FFD700'));
    $services_title_color = esc_attr(get_theme_mod('services_title_color', '#ffffff'));
    $services_desc_color = esc_attr(get_theme_mod('services_description_color', 'rgba(255, 255, 255, 0.8)'));
    $services_button_bg = esc_attr(get_theme_mod('services_button_background', 'linear-gradient(135deg, #FFD700, #FFA500)'));
    $services_button_text_color = esc_attr(get_theme_mod('services_button_text_color', '#0A0A0A'));
    $services_icon_size = esc_attr(get_theme_mod('services_icon_size', '32px'));
    $services_button_font_size = esc_attr(get_theme_mod('services_button_font_size', '16px'));
    $services_button_border_radius = esc_attr(get_theme_mod('services_button_border_radius', '8px'));
    $services_enable_cosmic_effect = get_theme_mod('callamir_enable_services_effect', true);
    $services_enable_liquid_effect = get_theme_mod('services_enable_liquid_effect', true);

    // Navigation styling
    $nav_gap = callamir_sanitize_css_value(get_theme_mod('nav_menu_gap', 'clamp(18px, 3vw, 34px)'));
    $nav_item_padding = callamir_sanitize_css_value(get_theme_mod('nav_menu_padding', '12px 0'));
    $nav_item_radius = callamir_sanitize_dimension(get_theme_mod('nav_menu_border_radius', '0px'));
    $nav_item_bg = callamir_sanitize_css_value(get_theme_mod('nav_menu_background', 'transparent'));
    $nav_item_hover_bg = callamir_sanitize_css_value(get_theme_mod('nav_menu_hover_background', 'transparent'));
    $nav_text_color = esc_attr(get_theme_mod('nav_menu_text_color', '#f9fafb'));
    $nav_hover_color = esc_attr(get_theme_mod('nav_menu_hover_color', '#ffd700'));

    $nav_mobile_panel_bg = callamir_sanitize_css_value(get_theme_mod('nav_mobile_panel_background', 'rgba(var(--color-dark-rgb), 0.96)'));
    $nav_mobile_item_bg = callamir_sanitize_css_value(get_theme_mod('nav_mobile_item_background', 'rgba(var(--color-light-rgb), 0.04)'));
    $nav_mobile_item_hover_bg = callamir_sanitize_css_value(get_theme_mod('nav_mobile_item_hover_background', 'rgba(var(--color-light-rgb), 0.08)'));
    $nav_mobile_item_radius = callamir_sanitize_dimension(get_theme_mod('nav_mobile_item_radius', '12px'));
    $nav_mobile_gap = callamir_sanitize_css_value(get_theme_mod('nav_mobile_gap', '6px'));
    $nav_mobile_text_color = esc_attr(get_theme_mod('nav_mobile_text_color', '#f9fafb'));
    $nav_mobile_hover_color = esc_attr(get_theme_mod('nav_mobile_hover_color', '#ffd700'));

    $footer_yt_defaults = function_exists('callamir_get_footer_youtube_defaults') ? callamir_get_footer_youtube_defaults() : [
        'button_color' => '#ff0000',
        'button_hover_color' => '#cc0000',
        'logo_theme' => 'light',
        'logo_custom_color' => '#ff0000',
    ];

    $footer_yt_button_color = esc_attr(get_theme_mod('footer_youtube_button_color', $footer_yt_defaults['button_color']));
    $footer_yt_button_hover_color = esc_attr(get_theme_mod('footer_youtube_button_hover_color', $footer_yt_defaults['button_hover_color']));
    $footer_yt_logo_theme = get_theme_mod('footer_youtube_logo_theme', $footer_yt_defaults['logo_theme']);
    $footer_yt_logo_custom = get_theme_mod('footer_youtube_logo_custom_color', $footer_yt_defaults['logo_custom_color']);

    $footer_yt_logo_color = 'var(--color-light)';
    if ($footer_yt_logo_theme === 'dark') {
        $footer_yt_logo_color = 'var(--color-dark)';
    } elseif ($footer_yt_logo_theme === 'custom' && $footer_yt_logo_custom) {
        $footer_yt_logo_color = esc_attr($footer_yt_logo_custom);
    }

    $css = "
    :root {
        --callamir-primary: {$primary};
        --callamir-accent: {$accent};
        --callamir-green: {$green};
        --callamir-danger: {$danger};
        --callamir-text: {$text};
        --callamir-section-bg: {$section_bg};
        --callamir-border: {$border};
        --cosmic-primary: {$cosmic_primary};
        --cosmic-accent: {$cosmic_accent};
        --header-justify: {$header_justify};
        --header-justify-mobile: {$header_justify_mobile};
        --logo-align: {$logo_align};
        --footer-min-height: {$footer_min_height};
        --services-card-bg: {$services_card_bg};
        --services-card-border: {$services_card_border};
        --services-icon-color: {$services_icon_color};
        --services-title-color: {$services_title_color};
        --services-desc-color: {$services_desc_color};
        --services-button-bg: {$services_button_bg};
        --services-button-text: {$services_button_text_color};
        --services-icon-size: {$services_icon_size};
        --services-button-font-size: {$services_button_font_size};
        --services-button-border-radius: {$services_button_border_radius};
        --callamir-body-font: {$body_font_family};
        --callamir-heading-font: {$heading_font_family};
        --callamir-base-font-size: {$base_font_size};
        --callamir-container-width: {$container_max_width};
        --callamir-section-padding: {$section_vertical_padding};
        --callamir-section-min-height: {$section_min_height};
        --callamir-hero-min-height: {$hero_min_height};
        --callamir-header-min-height: {$header_min_height};
        --callamir-hero-title-color: {$hero_title_color};
        --callamir-hero-text-color: {$hero_text_color};
        --callamir-section-title-color: {$section_title_color};
        --callamir-section-title-accent: {$section_title_accent};
        --callamir-section-subtitle-color: {$section_subtitle_color};
        --callamir-contact-form-bg: {$contact_form_background};
        --callamir-contact-form-border: {$contact_form_border};
        --callamir-contact-form-field-bg: {$contact_form_field_background};
        --callamir-contact-form-field-color: {$contact_form_field_color};
        --callamir-contact-form-button-bg: {$contact_form_button_background};
        --callamir-contact-form-button-color: {$contact_form_button_color};
        --footer-yt-btn-bg: {$footer_yt_button_color};
        --footer-yt-btn-hover: {$footer_yt_button_hover_color};
        --footer-yt-logo: {$footer_yt_logo_color};
        --footer-yt-button-bg: {$footer_yt_button_color};
        --footer-yt-button-hover: {$footer_yt_button_hover_color};
        --footer-yt-logo-color: {$footer_yt_logo_color};
        --footer-yt-icon: {$footer_yt_button_color};
        --nav-gap: {$nav_gap};
        --nav-desktop-item-padding: {$nav_item_padding};
        --nav-desktop-item-radius: {$nav_item_radius};
        --nav-desktop-bg: {$nav_item_bg};
        --nav-desktop-hover-bg: {$nav_item_hover_bg};
        --nav-desktop-text: {$nav_text_color};
        --nav-desktop-hover: {$nav_hover_color};
        --nav-mobile-panel-bg: {$nav_mobile_panel_bg};
        --nav-mobile-item-bg: {$nav_mobile_item_bg};
        --nav-mobile-item-hover-bg: {$nav_mobile_item_hover_bg};
        --nav-mobile-text: {$nav_mobile_text_color};
        --nav-mobile-hover: {$nav_mobile_hover_color};
        --nav-mobile-radius: {$nav_mobile_item_radius};
        --nav-mobile-gap: {$nav_mobile_gap};
    }
    body { color: var(--callamir-text); font-family: var(--callamir-body-font); font-size: var(--callamir-base-font-size); }
    h1, h2, h3, h4, h5, h6 { font-family: var(--callamir-heading-font); }
    .wrap { max-width: var(--callamir-container-width); }
    /* Header - Enhanced Cosmic Gradient */
    .site-header {
        background: " . ($enable_header_stars ? "linear-gradient(90deg, var(--cosmic-primary) 0%, #1B263B 70%, #415A77 100%)" : "var(--callamir-primary)") . ";
        position: sticky; top: 0; z-index: 1000;
        border-bottom: 1px solid rgba(255, 215, 0, 0.2);
        min-height: var(--callamir-header-min-height);
    }
    .stars-header { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; }
    .site-header .wrap { display: flex; justify-content: var(--header-justify); align-items: center; padding: 10px 20px; }
    @media (max-width: 768px) {
        .site-header .wrap { justify-content: var(--header-justify-mobile); }
    }
    .site-header .logo { text-align: var(--logo-align); }
    .logo img { max-height: {$logo_max_height}; width: auto; }
    .nav-link:hover { color: var(--cosmic-accent); text-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
    .leave-message {
        width: {$leave_message_width}; height: {$leave_message_height};
        background: linear-gradient(to right, #FFD700, #4169E1);
        color: var(--cosmic-primary); text-align: center; border-radius: 8px;
        transition: box-shadow 0.3s ease;
    }
    .leave-message:hover { box-shadow: 0 0 20px rgba(255, 215, 0, 0.8); }
    /* Footer - Enhanced Starfield */
    .site-footer {
        background: " . ($enable_footer_stars ? "linear-gradient(90deg, var(--cosmic-primary) 0%, #1B263B 70%, #415A77 100%)" : "var(--callamir-primary)") . ";
        min-height: var(--footer-min-height); position: relative; overflow: hidden;
        color: #fff; text-align: center; padding: 20px;
    }
    .stars-footer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; }
    /* Hero & Services - Enhanced Effects */
    .callamir-hero {
        padding: var(--callamir-section-padding) 20px; text-align: center; border-radius: 40px 40px 60px 60px;
        border-top: 2px solid var(--callamir-border); border-bottom: 2px solid var(--callamir-border);
        margin-bottom: 20px;
        background: " . ($enable_hero_effect ? "radial-gradient(circle at center, var(--cosmic-primary) 0%, #1B263B 50%, #415A77 100%)" : "var(--callamir-section-bg)") . ";
        background-size: 200%; animation: " . ($enable_hero_effect ? "blackholeSwirl 20s ease infinite" : "none") . ";
        position: relative; z-index: 1; min-height: var(--callamir-hero-min-height);
    }
    .callamir-hero-title { color: var(--callamir-hero-title-color) !important; }
    .callamir-hero p,
    .callamir-hero-text { color: var(--callamir-hero-text-color) !important; }
    .callamir-hero h1 { font-size: 2.2rem; margin: 0 0 10px; }
    .callamir-hero p { font-size: 1.05rem; margin: 0 0 18px; }
    .callamir-btn { display: inline-block; padding: 12px 20px; border-radius: 8px; text-decoration: none; cursor: pointer; margin: 6px; font-weight: 600; }
    .callamir-btn-call { background: var(--callamir-danger); color: #fff; }
    .callamir-btn-support { background: var(--callamir-accent); color: var(--callamir-primary); }
    .callamir-section {
        padding: var(--callamir-section-padding) 18px; border-radius: 20px; margin-bottom: 20px;
        background: " . ($enable_services_effect ? "radial-gradient(circle at center, var(--cosmic-primary) 0%, #1B263B 50%, #415A77 100%)" : "var(--callamir-section-bg)") . ";
        background-size: 200%; animation: " . ($enable_services_effect ? "blackholeSwirl 20s ease infinite" : "none") . ";
        position: relative; z-index: 1; min-height: var(--callamir-section-min-height);
    }
    .callamir-service-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; }
    .callamir-card { background: #fff; padding: 18px; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.03); text-align: center; }
    .callamir-contact-number { font-size: 1.2rem; font-weight: 700; color: var(--callamir-primary); }
    #blackhole, #services-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; }
    
    /* Modern Services Section Dynamic Styling */
    .service-card { 
        background: var(--services-card-bg) !important; 
        border-color: var(--services-card-border) !important; 
    }
    .service-icon-wrapper { 
        background: linear-gradient(135deg, var(--services-icon-color), #FFA500) !important; 
    }
    .service-icon-wrapper i {
        font-size: var(--services-icon-size) !important;
    }
    .services-title,
    .callamir-section-title {
        color: var(--callamir-section-title-color) !important;
        background: linear-gradient(45deg, var(--callamir-section-title-color), var(--callamir-section-title-accent), var(--callamir-section-title-color));
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .service-title {
        color: var(--services-title-color) !important;
    }
    .service-description {
        color: var(--services-desc-color) !important;
    }
    .callamir-section-subtitle,
    .services-subtitle {
        color: var(--callamir-section-subtitle-color) !important;
    }
    .read-more-btn {
        background: var(--services-button-bg) !important;
        color: var(--services-button-text) !important;
        font-size: var(--services-button-font-size) !important;
        border-radius: var(--services-button-border-radius) !important;
    }

    /* Conditional Cosmic Effect */
    " . ($services_enable_cosmic_effect ? ".modern-services-section::before { display: block !important; opacity: 1 !important; }" : ".modern-services-section::before { display: none !important; }") . "
    
    /* Conditional Liquid Effect */
    " . ($services_enable_liquid_effect ? ".read-more-btn::after { display: block; }" : ".read-more-btn::after { display: none; }") . "
    
    @keyframes blackholeSwirl {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    ";

    $css .= <<<'CSS'

    .callamir-contact-form {
        background: var(--callamir-contact-form-bg);
        border: 1px solid var(--callamir-contact-form-border);
        border-radius: 24px;
        padding: clamp(1.5rem, 2.5vw, 3rem);
        box-shadow: 0 30px 60px rgba(6, 10, 22, 0.35);
    }

    .callamir-contact-form .wpcf7-form,
    .callamir-contact-form form {
        display: grid;
        gap: 1.25rem;
    }

    .callamir-contact-form label {
        color: var(--callamir-section-subtitle-color);
        font-weight: 600;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .callamir-contact-form input[type="text"],
    .callamir-contact-form input[type="email"],
    .callamir-contact-form input[type="tel"],
    .callamir-contact-form select,
    .callamir-contact-form textarea {
        background: var(--callamir-contact-form-field-bg);
        color: var(--callamir-contact-form-field-color);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 0.85rem 1.1rem;
        font-size: 1rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        width: 100%;
    }

    .callamir-contact-form input[type="text"]:focus,
    .callamir-contact-form input[type="email"]:focus,
    .callamir-contact-form input[type="tel"]:focus,
    .callamir-contact-form select:focus,
    .callamir-contact-form textarea:focus {
        outline: none;
        border-color: var(--callamir-section-title-accent);
        box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.15);
    }

    .callamir-contact-form textarea {
        min-height: 160px;
        resize: vertical;
    }

    .callamir-contact-form .wpcf7-submit,
    .callamir-contact-form button[type="submit"],
    .callamir-contact-form input[type="submit"] {
        background: var(--callamir-contact-form-button-bg);
        color: var(--callamir-contact-form-button-color);
        border: none;
        border-radius: 999px;
        padding: 0.9rem 1.75rem;
        font-weight: 700;
        font-size: 1.05rem;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .callamir-contact-form .wpcf7-submit:hover,
    .callamir-contact-form button[type="submit"]:hover,
    .callamir-contact-form input[type="submit"]:hover {
        transform: translateY(-1px);
        box-shadow: 0 20px 30px rgba(250, 204, 21, 0.3);
    }

    .callamir-contact-form .wpcf7-not-valid-tip,
    .callamir-contact-form .wpcf7-response-output {
        color: var(--callamir-contact-form-button-bg);
    }

CSS;
    return $css;
}
if (!function_exists('callamir_enqueue_dynamic_css')) {
    /**
     * Attach generated CSS to the front-end stylesheet.
     */
    function callamir_enqueue_dynamic_css() {
        $css = callamir_generate_dynamic_css();

        if (empty(trim($css))) {
            return;
        }

        wp_add_inline_style('callamir-style', $css);
    }
}

add_action('wp_enqueue_scripts', 'callamir_enqueue_dynamic_css', 20);
