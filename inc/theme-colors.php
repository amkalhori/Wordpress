<?php
/**
 * Theme Colors Management
 * 
 * This file handles the import and export of theme colors
 */

// Define default theme colors
function callamir_get_default_colors() {
    return array(
        'primary_color' => '#0066cc',      // Bright blue for primary elements
        'secondary_color' => '#002b44',    // Darker blue for secondary elements
        'accent_color' => '#ff0000',       // Red for accents
        'text_color' => '#333333',         // Dark gray for main text
        'background_color' => '#ffffff',   // White for background
        'nav_button_bg' => '#002b44',      // Dark blue for nav buttons
        'nav_button_hover' => '#001f33',   // Darker blue for nav hover
        'home_button_bg' => '#004080',     // Blue for home buttons
        'home_button_hover' => '#003366',  // Darker blue for home hover
        'hero_button_bg' => '#004080',     // Blue for hero buttons
        'hero_button_hover' => '#003366',  // Darker blue for hero hover
        'hero_bg_color' => '#004466',      // Dark blue for hero section
        'services_bg_color' => '#ffffff',  // White for services section
        'contact_bg_color' => '#ffffff',   // White for contact section
        'community_bg_color' => '#ffffff', // White for community section
        'blog_bg_color' => '#ffffff',      // White for blog section
        'subscribe_bg_color' => '#004466', // Dark blue for subscribe section
        'footer_bg_color' => '#004466',    // Dark blue for footer
        'body_bg_color' => '#ffffff',      // White for body background
        'secondary_text_color' => '#666666',// Medium gray for secondary text
        'border_color' => '#dddddd',       // Light gray for borders
        'section_bg_color' => '#f9f9f9',   // Very light gray for sections
        'form_bg_color' => '#ffffff',      // White for form backgrounds
        'success_bg_color' => '#e0ffd4',   // Light green for success messages
        'success_text_color' => '#2e7d32', // Dark green for success text
        'error_bg_color' => '#ffd4d4',     // Light red for error messages
        'error_text_color' => '#d32f2f',   // Dark red for error text
        'nav_menu_text_color' => '#ffffff', // White for navigation menu text
        'nav_container_bg' => '#002b44',    // Dark blue for navigation container
        'nav_menu_bg' => '#001f33'         // Darker blue for navigation menu background
    );
}

// Export colors to JSON file
function callamir_export_colors() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $colors = array();
    
    // Get all color settings from theme mods
    $default_colors = callamir_get_default_colors();
    foreach ($default_colors as $key => $default_value) {
        $colors[$key] = get_theme_mod($key, $default_value);
    }
    
    // Create JSON file
    $json = json_encode($colors, JSON_PRETTY_PRINT);
    $filename = 'callamir-colors-' . date('Y-m-d') . '.json';
    
    // Set headers for download
    if (!headers_sent()) {
        nocache_headers();
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($json));
        echo $json;
        exit;
    } else {
        wp_send_json_error('Headers already sent');
    }
}

// Add AJAX action for color export
function callamir_ajax_export_colors() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'callamir_export_colors')) {
        wp_send_json_error('Invalid nonce');
    }

    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Insufficient permissions');
    }

    $colors = array();
    
    // Get all color settings from theme mods
    $default_colors = callamir_get_default_colors();
    foreach ($default_colors as $key => $default_value) {
        $colors[$key] = get_theme_mod($key, $default_value);
    }
    
    wp_send_json_success($colors);
}
add_action('wp_ajax_callamir_export_colors', 'callamir_ajax_export_colors');

// Import colors from JSON file
function callamir_import_colors($json_file) {
    if (!file_exists($json_file)) {
        return new WP_Error('file_not_found', 'Color settings file not found.');
    }
    
    $json_content = file_get_contents($json_file);
    $colors = json_decode($json_content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error('invalid_json', 'Invalid JSON format in color settings file.');
    }
    
    // Update theme mods with imported colors
    foreach ($colors as $key => $value) {
        set_theme_mod($key, $value);
    }
    
    return true;
}

// Add color settings to customizer
function callamir_customize_colors($wp_customize) {
    $default_colors = callamir_get_default_colors();
    
    // Add main theme colors section
    $wp_customize->add_section('theme_colors', array(
        'title' => __('Theme Colors', 'callamir'),
        'priority' => 25,
        'description' => __('Customize the main colors of your theme', 'callamir')
    ));
    
    // Add main color settings
    $main_colors = array(
        'primary_color' => __('Primary Color', 'callamir'),
        'secondary_color' => __('Secondary Color', 'callamir'),
        'accent_color' => __('Accent Color', 'callamir'),
        'text_color' => __('Text Color', 'callamir'),
        'background_color' => __('Background Color', 'callamir'),
        'hero_bg_color' => __('Hero Background Color', 'callamir'),
        'services_bg_color' => __('Services Background Color', 'callamir'),
        'contact_bg_color' => __('Contact Background Color', 'callamir'),
        'community_bg_color' => __('Community Background Color', 'callamir'),
        'blog_bg_color' => __('Blog Background Color', 'callamir'),
        'subscribe_bg_color' => __('Subscribe Background Color', 'callamir'),
        'footer_bg_color' => __('Footer Background Color', 'callamir'),
        'body_bg_color' => __('Body Background Color', 'callamir'),
        'secondary_text_color' => __('Secondary Text Color', 'callamir'),
        'border_color' => __('Border Color', 'callamir'),
        'section_bg_color' => __('Section Background Color', 'callamir'),
        'form_bg_color' => __('Form Background Color', 'callamir'),
        'success_bg_color' => __('Success Background Color', 'callamir'),
        'success_text_color' => __('Success Text Color', 'callamir'),
        'error_bg_color' => __('Error Background Color', 'callamir'),
        'error_text_color' => __('Error Text Color', 'callamir'),
        'nav_menu_text_color' => __('Navigation Menu Text Color', 'callamir'),
        'nav_container_bg' => __('Navigation Container Background', 'callamir'),
        'nav_menu_bg' => __('Navigation Menu Background', 'callamir')
    );
    
    foreach ($main_colors as $key => $label) {
        $wp_customize->add_setting($key, array(
            'default' => $default_colors[$key],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, array(
            'label' => $label,
            'section' => 'theme_colors',
            'settings' => $key,
        )));
    }
    
    // Add button colors section
    $wp_customize->add_section('button_colors', array(
        'title' => __('Button Colors', 'callamir'),
        'priority' => 26,
    ));
    
    // Add button color settings
    $button_colors = array(
        'nav_button_bg' => __('Navigation Button Background', 'callamir'),
        'nav_button_hover' => __('Navigation Button Hover', 'callamir'),
        'home_button_bg' => __('Home Button Background', 'callamir'),
        'home_button_hover' => __('Home Button Hover', 'callamir'),
        'hero_button_bg' => __('Hero Button Background', 'callamir'),
        'hero_button_hover' => __('Hero Button Hover', 'callamir')
    );
    
    foreach ($button_colors as $key => $label) {
        $wp_customize->add_setting($key, array(
            'default' => $default_colors[$key],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, array(
            'label' => $label,
            'section' => 'button_colors',
            'settings' => $key,
        )));
    }
}
add_action('customize_register', 'callamir_customize_colors');

// Add admin menu for color import/export
function callamir_add_color_management_menu() {
    add_submenu_page(
        'themes.php',
        __('Theme Colors', 'callamir'),
        __('Theme Colors', 'callamir'),
        'manage_options',
        'callamir-colors',
        'callamir_color_management_page'
    );
}
add_action('admin_menu', 'callamir_add_color_management_menu');

// Color management page
function callamir_color_management_page() {
    if (isset($_FILES['import_colors']) && $_FILES['import_colors']['error'] === UPLOAD_ERR_OK) {
        $result = callamir_import_colors($_FILES['import_colors']['tmp_name']);
        if (is_wp_error($result)) {
            echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>' . __('Colors imported successfully!', 'callamir') . '</p></div>';
        }
    }
    
    ?>
    <div class="wrap">
        <h1><?php _e('Theme Colors Management', 'callamir'); ?></h1>
        
        <div class="card">
            <h2><?php _e('Export Colors', 'callamir'); ?></h2>
            <p><?php _e('Export your current theme colors to a JSON file.', 'callamir'); ?></p>
            <button type="button" class="button button-primary" id="export-colors"><?php _e('Export Colors', 'callamir'); ?></button>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2><?php _e('Import Colors', 'callamir'); ?></h2>
            <p><?php _e('Import theme colors from a JSON file.', 'callamir'); ?></p>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('callamir_import_colors', 'callamir_import_nonce'); ?>
                <input type="file" name="import_colors" accept=".json">
                <input type="submit" class="button button-primary" value="<?php _e('Import Colors', 'callamir'); ?>">
            </form>
        </div>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#export-colors').on('click', function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'callamir_export_colors',
                    nonce: '<?php echo wp_create_nonce('callamir_export_colors'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        var colors = response.data;
                        var json = JSON.stringify(colors, null, 2);
                        var blob = new Blob([json], { type: 'application/json' });
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = 'callamir-colors-' + new Date().toISOString().split('T')[0] + '.json';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        a.remove();
                    } else {
                        alert('Error exporting colors: ' + (response.data || 'Unknown error'));
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error exporting colors: ' + error);
                }
            });
        });
    });
    </script>
    <?php
}

// Add customizer import/export controls
function callamir_customize_import_export_controls() {
    ?>
    <script type="text/javascript">
    (function($) {
        'use strict';
        
        // Add import/export buttons to customizer
        var $section = $('#accordion-section-button_colors');
        var $controls = $section.find('.customize-section-content');
        
        if ($controls.length) {
            var $exportButton = $('<button type="button" class="button button-primary"><?php _e('Export Colors', 'callamir'); ?></button>');
            var $importButton = $('<button type="button" class="button"><?php _e('Import Colors', 'callamir'); ?></button>');
            var $fileInput = $('<input type="file" accept=".json" style="display: none;">');
            
            $controls.append($exportButton);
            $controls.append($importButton);
            $controls.append($fileInput);
            
            // Handle export
            $exportButton.on('click', function() {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'callamir_export_colors',
                        nonce: '<?php echo wp_create_nonce('callamir_export_colors'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var colors = response.data;
                            var json = JSON.stringify(colors, null, 2);
                            var blob = new Blob([json], { type: 'application/json' });
                            var url = window.URL.createObjectURL(blob);
                            var a = document.createElement('a');
                            a.href = url;
                            a.download = 'callamir-colors-' + new Date().toISOString().split('T')[0] + '.json';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            a.remove();
                        } else {
                            alert('Error exporting colors: ' + (response.data || 'Unknown error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error exporting colors: ' + error);
                    }
                });
            });
            
            // Handle import
            $importButton.on('click', function() {
                $fileInput.click();
            });
            
            $fileInput.on('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            var colors = JSON.parse(e.target.result);
                            // Update each color setting
                            Object.keys(colors).forEach(function(key) {
                                var setting = wp.customize(key);
                                if (setting) {
                                    setting.set(colors[key]);
                                }
                            });
                            wp.customize.previewer.refresh();
                        } catch (error) {
                            alert('Error importing colors: Invalid file format');
                        }
                    };
                    reader.readAsText(file);
                }
            });
        }
    })(jQuery);
    </script>
    <?php
}
add_action('customize_controls_print_footer_scripts', 'callamir_customize_import_export_controls'); 