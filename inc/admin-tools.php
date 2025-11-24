<?php
/**
 * Administrative maintenance utilities for the theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Force reset all customizer settings to fix sanitize_checkbox issues
function callamir_force_reset_customizer() {
    if (current_user_can('manage_options')) {
        global $wpdb;
        
        // Get all theme mods
        $theme_mods = get_theme_mods();
        
        // Remove all theme mods
        remove_theme_mods();
        
        // Clear customizer changesets
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'customize_changeset_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'customize_%'");
        
        // Clear all caches
        wp_cache_flush();
        
        // Re-set default values for critical settings
        set_theme_mod('site_language', 'en');
        set_theme_mod('callamir_enable_header_stars', true);
        set_theme_mod('callamir_enable_footer_stars', true);
        set_theme_mod('callamir_enable_hero_effect', true);
        set_theme_mod('callamir_enable_services_effect', true);
        set_theme_mod('services_enable_cosmic_effect', true);
        set_theme_mod('services_enable_liquid_effect', true);
        
        error_log('Force reset customizer completed for callamir theme');
    }
}

// Nuclear option - completely remove all customizer data
function callamir_nuclear_reset() {
    if (current_user_can('manage_options')) {
        global $wpdb;
        
        // Remove ALL theme mods
        remove_theme_mods();
        
        // Delete ALL customizer-related options
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'customize_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'theme_mods_%'");
        
        // Clear all caches
        wp_cache_flush();
        
        // Clear object cache
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Clear any remaining cache
        if (function_exists('wp_cache_delete')) {
            wp_cache_delete('alloptions', 'options');
        }
        
        error_log('Nuclear reset completed - all customizer data removed');
    }
}

// Add a function to reset sanitization callbacks for theme settings
function callamir_reset_sanitization_callbacks() {
    if (current_user_can('manage_options')) {
        // Clear all theme mods that might be causing issues
        $problematic_keys = [
            'callamir_enable_header_stars',
            'callamir_enable_footer_stars',
            'callamir_enable_hero_effect',
            'callamir_enable_services_effect',
            'services_enable_cosmic_effect',
            'services_enable_liquid_effect'
        ];
        
        foreach ($problematic_keys as $key) {
            $current_value = get_theme_mod($key);
            if ($current_value !== false) {
                // Re-save with proper boolean validation
                set_theme_mod($key, wp_validate_boolean($current_value));
            }
        }
        
        // Clear WordPress object cache
        wp_cache_flush();
        
        // Log for debugging
        error_log('Theme mods sanitization callbacks reset for callamir theme');
    }
}
// Run this function once on theme activation or admin init
add_action('admin_init', 'callamir_reset_sanitization_callbacks');

// Additional function to clear customizer cache
function callamir_clear_customizer_cache() {
    if (current_user_can('manage_options')) {
        // Clear customizer changesets
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'customize_changeset_%'");
        
        // Clear theme mods cache
        delete_option('theme_mods_call-test');
        
        // Clear object cache
        wp_cache_flush();
        
        error_log('Customizer cache cleared for callamir theme');
    }
}
add_action('wp_ajax_clear_customizer_cache', 'callamir_clear_customizer_cache');

// Auto-fix on theme activation
function callamir_theme_activation_fix() {
    // Run the force reset on theme activation
    callamir_force_reset_customizer();
}
add_action('after_switch_theme', 'callamir_theme_activation_fix');

// Add admin menu for cache clearing
function callamir_add_admin_menu() {
    add_theme_page(
        'Clear Cache',
        'Clear Cache',
        'manage_options',
        'callamir-clear-cache',
        'callamir_clear_cache_page'
    );
}
add_action('admin_menu', 'callamir_add_admin_menu');

function callamir_clear_cache_page() {
    if (isset($_POST['clear_cache']) && wp_verify_nonce($_POST['_wpnonce'], 'clear_cache')) {
        callamir_clear_customizer_cache();
        echo '<div class="notice notice-success"><p>Cache cleared successfully!</p></div>';
    }
    
    if (isset($_POST['force_reset']) && wp_verify_nonce($_POST['_wpnonce'], 'force_reset')) {
        callamir_force_reset_customizer();
        echo '<div class="notice notice-success"><p>Customizer force reset completed! All settings have been reset to defaults.</p></div>';
    }
    
    if (isset($_POST['nuclear_reset']) && wp_verify_nonce($_POST['_wpnonce'], 'nuclear_reset')) {
        callamir_nuclear_reset();
        echo '<div class="notice notice-success"><p>Nuclear reset completed! ALL customizer data has been completely removed.</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Fix Sanitize Checkbox Error</h1>
        <p><strong>If you're still getting the sanitize_checkbox fatal error, try these options:</strong></p>
        
        <h2>Option 1: Clear Cache</h2>
        <p>This will clear all cached customizer data and theme mods.</p>
        <form method="post" style="margin-bottom: 20px;">
            <?php wp_nonce_field('clear_cache'); ?>
            <input type="submit" name="clear_cache" class="button button-primary" value="Clear Cache" onclick="return confirm('Are you sure you want to clear all cached data?');">
        </form>
        
        <h2>Option 2: Force Reset</h2>
        <p>This will reset ALL customizer settings to default values. You will need to reconfigure your theme settings.</p>
        <form method="post" style="margin-bottom: 20px;">
            <?php wp_nonce_field('force_reset'); ?>
            <input type="submit" name="force_reset" class="button button-secondary" value="Force Reset All Settings" onclick="return confirm('⚠️ WARNING: This will reset ALL your customizer settings to defaults. Are you absolutely sure?');">
        </form>
        
        <h2>Option 3: Nuclear Reset (Last Resort)</h2>
        <p><strong>🚨 EXTREME WARNING:</strong> This will completely remove ALL customizer data from the database. This is the most aggressive option and should only be used if nothing else works.</p>
        <form method="post">
            <?php wp_nonce_field('nuclear_reset'); ?>
            <input type="submit" name="nuclear_reset" class="button button-danger" value="🚨 NUCLEAR RESET 🚨" onclick="return confirm('🚨 EXTREME WARNING: This will completely remove ALL customizer data from the database. This cannot be undone. Are you absolutely certain?');">
        </form>
        
        <hr>
        <h3>What This Fixes:</h3>
        <ul>
            <li>Eliminates the sanitize_checkbox fatal error</li>
            <li>Clears all corrupted customizer data</li>
            <li>Resets theme settings to working defaults</li>
            <li>Allows you to reconfigure your theme properly</li>
        </ul>
    </div>
    <?php
}
