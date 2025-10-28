<?php
/**
 * Dynamic CSS Generation
 *
 * This file handles the generation of dynamic CSS with theme colors and cosmic effects
 */
function callamir_generate_dynamic_css() {
    $css = '';
    // Cosmic Colors from Customizer
    $cosmic_primary = get_theme_mod('cosmic_primary', '#0A0A0A');
    $cosmic_accent = get_theme_mod('cosmic_accent', '#FFD700');
    $enable_cosmic_effects = get_theme_mod('enable_cosmic_effects', true);

    // Dynamic CSS Variables
    $css .= "--cosmic-primary: {$cosmic_primary};\n";
    $css .= "--cosmic-accent: {$cosmic_accent};\n";

    // Base styles that depend on theme mods
    $css .= "
.site-header.scrolled { background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
.callamir-section { position: relative; }
.stars-header, .stars-footer { pointer-events: none; }
";

    // Optional cosmic effects styling
    if ($enable_cosmic_effects) {
        $css .= "
#blackhole, #services-canvas { display:block; }
";
    }

    return $css;
}

// Output CSS via admin-ajax if needed
function callamir_output_dynamic_css() {
    header('Content-type: text/css; charset: UTF-8');
    echo ":root {\n";
    echo callamir_generate_dynamic_css();
    echo "}\n";
    exit;
}

// Enqueue Dynamic CSS with Better Approach
function callamir_enqueue_dynamic_css() {
    $css = callamir_generate_dynamic_css();

    // RTL for Persian
    if (function_exists('callamir_get_visitor_lang') && callamir_get_visitor_lang() === 'fa') {
        $css .= "body{direction:rtl;text-align:right;} .alignleft{float:right;margin-left:1em;margin-right:0;} .alignright{float:left;margin-right:1em;margin-left:0;}\n";
    }

    wp_add_inline_style('callamir-style', $css); // Attach to main stylesheet
}
add_action('wp_enqueue_scripts', 'callamir_enqueue_dynamic_css');

// Optional: Remove AJAX method if inline works
// add_action('wp_ajax_callamir_dynamic_css', 'callamir_output_dynamic_css');
// add_action('wp_ajax_nopriv_callamir_dynamic_css', 'callamir_output_dynamic_css');
