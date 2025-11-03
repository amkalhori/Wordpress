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
    .callamir-hero h1 { font-size: 2.2rem; color: #ffffff; margin: 0 0 10px; }
    .callamir-hero p { font-size: 1.05rem; margin: 0 0 18px; color: #ffffff; }
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
    .service-title { 
        color: var(--services-title-color) !important; 
    }
    .service-description { 
        color: var(--services-desc-color) !important; 
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
    return $css;
}
add_action('wp_enqueue_scripts', 'callamir_enqueue_dynamic_css');
