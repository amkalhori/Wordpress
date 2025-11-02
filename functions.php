<?php
/**
 * CallAmir Theme Functions - integrated with cosmic style (Corrected Version)
 */
if (!defined('ABSPATH')) {
    exit;
}

// Load modular theme components.
require_once get_template_directory() . '/inc/language.php';

// Theme loaded successfully

/* --------------------------------------------------------------------------
 * Use WordPress Built-in Sanitize Functions
 * -------------------------------------------------------------------------- */
// Remove custom sanitize functions to prevent conflicts

/* --------------------------------------------------------------------------
 * Theme Setup and Support
 * -------------------------------------------------------------------------- */
function callamir_theme_setup() {
    // Load textdomain
    load_theme_textdomain('callamir', get_template_directory() . '/languages');
    
    // Add theme support for modern WordPress features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Add support for Elementor
    add_theme_support('elementor');
    add_theme_support('elementor-pro');
    
    // Register navigation menus
    register_nav_menus(array(
        'one_page_menu' => __('One Page Menu', 'callamir'),
        'footer_menu' => __('Footer Menu', 'callamir'),
    ));
    
    // Register Community Questions Custom Post Type
    callamir_register_community_questions();
}
add_action('after_setup_theme', 'callamir_theme_setup');

/* --------------------------------------------------------------------------
 * Community Questions Custom Post Type
 * -------------------------------------------------------------------------- */
  function callamir_register_community_questions() {
    $labels = array(
        'name' => __('Community Questions', 'callamir'),
        'singular_name' => __('Community Question', 'callamir'),
        'menu_name' => __('Community Questions', 'callamir'),
        'add_new' => __('Add New Question', 'callamir'),
        'add_new_item' => __('Add New Community Question', 'callamir'),
        'edit_item' => __('Edit Community Question', 'callamir'),
        'new_item' => __('New Community Question', 'callamir'),
        'view_item' => __('View Community Question', 'callamir'),
        'search_items' => __('Search Community Questions', 'callamir'),
        'not_found' => __('No community questions found', 'callamir'),
        'not_found_in_trash' => __('No community questions found in trash', 'callamir'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'community-question'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-format-chat',
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'show_in_rest' => true,
    );

    register_post_type('community_questions', $args);
}
add_action('init', 'callamir_register_community_questions');

/* --------------------------------------------------------------------------
 * Service Utilities
 * -------------------------------------------------------------------------- */
function callamir_max_services() {
    return 6;
}

function callamir_service_patterns() {
    return array(
        'callamir_service_icon_%d',
        'callamir_service_image_%d',
        'service_title_%d_en',
        'service_title_%d_fa',
        'service_desc_%d_en',
        'service_desc_%d_fa',
        'service_full_desc_%d_en',
        'service_full_desc_%d_fa',
        'service_price_%d_en',
        'service_price_%d_fa',
        'callamir_service_delete_trigger_%d',
    );
}

function callamir_sanitize_service_count($value) {
    $value = absint($value);
    $max = callamir_max_services();

    if ($value > $max) {
        $value = $max;
    }

    return $value;
}

function callamir_service_control_active($control) {
    $count_setting = $control->manager->get_setting('callamir_services_count');
    $count = $count_setting ? absint($count_setting->value()) : 0;

    $is_service_specific = false;

    if (property_exists($control, 'service_id') && $control->service_id) {
        $is_service_specific = true;
        return (int) $control->service_id <= $count && $count > 0;
    }

    if (preg_match('/_(\d+)(?:_|$)/', $control->id, $matches)) {
        $is_service_specific = true;
        return $count > 0 && (int) $matches[1] <= $count;
    }

    return !$is_service_specific ? true : $count > 0;
}

if (class_exists('WP_Customize_Control') && !class_exists('Callamir_Service_Delete_Control')) {
    class Callamir_Service_Delete_Control extends WP_Customize_Control {
        public $type = 'callamir_service_delete';

        public $service_id = 0;

        public function render_content() {
            if (!$this->service_id) {
                return;
            }

            echo '<div class="callamir-service-actions">';
            printf('<span class="customize-control-title">%s</span>', esc_html(sprintf(__('Service %d actions', 'callamir'), $this->service_id)));
            printf(
                '<button type="button" class="button button-secondary callamir-delete-service" data-service-id="%1$s">%2$s</button>',
                esc_attr($this->service_id),
                esc_html(sprintf(__('Delete Service %d', 'callamir'), $this->service_id))
            );
            echo '<p class="description">' . esc_html__('Remove this service and shift remaining services up.', 'callamir') . '</p>';
            echo '</div>';
        }
    }
}

function callamir_enqueue_customizer_service_assets() {
    wp_enqueue_script(
        'callamir-customizer-services',
        get_template_directory_uri() . '/js/customizer-services.js',
        array('customize-controls', 'jquery'),
        wp_get_theme()->get('Version'),
        true
    );

    wp_localize_script('callamir-customizer-services', 'callamirServiceManager', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('callamir_delete_service'),
        'confirmDelete' => __('Are you sure you want to delete Service %s? This action cannot be undone.', 'callamir'),
        'success' => __('Service deleted successfully.', 'callamir'),
        'error' => __('Unable to delete the service. Please try again.', 'callamir'),
    ));
}
add_action('customize_controls_enqueue_scripts', 'callamir_enqueue_customizer_service_assets');

/* --------------------------------------------------------------------------
 * Community Questions Meta Boxes
 * -------------------------------------------------------------------------- */
function callamir_add_community_questions_meta_boxes() {
    add_meta_box(
        'community_question_details',
        __('Question Details', 'callamir'),
        'callamir_community_question_details_callback',
        'community_questions',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'callamir_add_community_questions_meta_boxes');

function callamir_community_question_details_callback($post) {
    wp_nonce_field('callamir_community_question_meta', 'callamir_community_question_meta_nonce');
    
    $question_status = get_post_meta($post->ID, '_question_status', true);
    $question_author_name = get_post_meta($post->ID, '_question_author_name', true);
    $question_author_email = get_post_meta($post->ID, '_question_author_email', true);
    $question_answer = get_post_meta($post->ID, '_question_answer', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="question_status"><?php _e('Question Status', 'callamir'); ?></label>
            </th>
            <td>
                <select name="question_status" id="question_status">
                    <option value="pending" <?php selected($question_status, 'pending'); ?>><?php _e('Pending', 'callamir'); ?></option>
                    <option value="answered" <?php selected($question_status, 'answered'); ?>><?php _e('Answered', 'callamir'); ?></option>
                    <option value="published" <?php selected($question_status, 'published'); ?>><?php _e('Published', 'callamir'); ?></option>
                </select>
                <p class="description"><?php _e('Set the status of this community question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_author_name"><?php _e('Author Name', 'callamir'); ?></label>
            </th>
            <td>
                <input type="text" name="question_author_name" id="question_author_name" value="<?php echo esc_attr($question_author_name); ?>" class="regular-text" />
                <p class="description"><?php _e('Name of the person who asked the question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_author_email"><?php _e('Author Email', 'callamir'); ?></label>
            </th>
            <td>
                <input type="email" name="question_author_email" id="question_author_email" value="<?php echo esc_attr($question_author_email); ?>" class="regular-text" />
                <p class="description"><?php _e('Email of the person who asked the question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_answer"><?php _e('Answer', 'callamir'); ?></label>
            </th>
            <td>
                <?php
                wp_editor($question_answer, 'question_answer', array(
                    'textarea_name' => 'question_answer',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                    'teeny' => false,
                ));
                ?>
                <p class="description"><?php _e('Provide the answer to this question.', 'callamir'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

function callamir_save_community_question_meta($post_id) {
    if (!isset($_POST['callamir_community_question_meta_nonce']) || 
        !wp_verify_nonce($_POST['callamir_community_question_meta_nonce'], 'callamir_community_question_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['question_status'])) {
        update_post_meta($post_id, '_question_status', sanitize_text_field($_POST['question_status']));
    }

    if (isset($_POST['question_author_name'])) {
        update_post_meta($post_id, '_question_author_name', sanitize_text_field($_POST['question_author_name']));
    }

    if (isset($_POST['question_author_email'])) {
        update_post_meta($post_id, '_question_author_email', sanitize_email($_POST['question_author_email']));
    }

    if (isset($_POST['question_answer'])) {
        update_post_meta($post_id, '_question_answer', wp_kses_post($_POST['question_answer']));
    }
}
add_action('save_post', 'callamir_save_community_question_meta');

/* --------------------------------------------------------------------------
 * Enqueue Scripts and Styles
 * -------------------------------------------------------------------------- */
function callamir_enqueue_scripts() {
    // Enqueue styles with performance optimizations
    $theme_version = '1.0.56';

    wp_enqueue_style('callamir-style', get_stylesheet_uri(), array(), $theme_version);
    wp_style_add_data('callamir-style', 'rtl', 'replace');

    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');

    // Enqueue scripts with modern optimizations
    wp_enqueue_script('callamir-theme-js', get_template_directory_uri() . '/js/theme.js', array('jquery'), $theme_version, true);
    
    // Localize script for AJAX and language metadata.
    wp_localize_script('callamir-theme-js', 'callamirText', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('callamir_nonce'),
    ));
    
    // Add preload hints for better performance
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
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
            'title' => callamir_mod("service_title_{$i}", $current_lang, $defaults['title'][$current_lang] ?? $defaults['title']['en']),
            'description' => callamir_mod("service_desc_{$i}", $current_lang, $defaults['description'][$current_lang] ?? $defaults['description']['en']),
            'fullDescription' => callamir_mod("service_full_desc_{$i}", $current_lang, $defaults['fullDescription'][$current_lang] ?? $defaults['fullDescription']['en']),
            'price' => callamir_mod("service_price_{$i}", $current_lang, ''),
            'image' => $image,
        );

        $service_translations['items'][$i] = array(
            'image' => $image,
            'translations' => array(),
        );

        foreach ($supported_languages as $code => $meta) {
            $service_translations['items'][$i]['translations'][$code] = array(
                'title' => callamir_mod("service_title_{$i}", $code, $defaults['title'][$code] ?? $defaults['title']['en']),
                'description' => callamir_mod("service_desc_{$i}", $code, $defaults['description'][$code] ?? $defaults['description']['en']),
                'fullDescription' => callamir_mod("service_full_desc_{$i}", $code, $defaults['fullDescription'][$code] ?? $defaults['fullDescription']['en']),
                'price' => callamir_mod("service_price_{$i}", $code, ''),
            );
        }
    }

    wp_localize_script('callamir-theme-js', 'serviceData', $service_data);
    wp_localize_script('callamir-theme-js', 'serviceTranslations', $service_translations);
}
add_action('wp_enqueue_scripts', 'callamir_enqueue_scripts');

/* --------------------------------------------------------------------------
 * Dynamic CSS Generator (Fixed Duplicates, Enhanced Visuals)
 * -------------------------------------------------------------------------- */
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
function callamir_enqueue_dynamic_css() {
    $css = callamir_generate_dynamic_css();
    wp_add_inline_style('callamir-style', $css);
}

/* --------------------------------------------------------------------------
 * Customizer Settings (Added Services, Header/Footer Controls)
 * -------------------------------------------------------------------------- */
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
    $wp_customize->add_setting('contact_phone', array(
        'default' => '416-123-4567',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_phone', array(
        'label' => __('Contact Phone Number', 'callamir'),
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

/* --------------------------------------------------------------------------
 * Service Management (AJAX)
 * -------------------------------------------------------------------------- */
function callamir_delete_service() {
    check_ajax_referer('callamir_delete_service', 'nonce');

    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(array('message' => __('You are not allowed to delete services.', 'callamir')));
    }

    $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
    $max_services = callamir_max_services();

    if ($service_id < 1 || $service_id > $max_services) {
        wp_send_json_error(array('message' => __('Invalid service identifier.', 'callamir')));
    }

    $patterns = callamir_service_patterns();
    $sentinel = '__callamir_missing__';

    for ($slot = $service_id; $slot < $max_services; $slot++) {
        $next_slot = $slot + 1;

        foreach ($patterns as $pattern) {
            $current_key = sprintf($pattern, $slot);

            if ($next_slot <= $max_services) {
                $next_key = sprintf($pattern, $next_slot);
                $value = get_theme_mod($next_key, $sentinel);

                if ($sentinel !== $value) {
                    set_theme_mod($current_key, $value);
                } else {
                    remove_theme_mod($current_key);
                }
            }
        }
    }

    foreach ($patterns as $pattern) {
        $last_key = sprintf($pattern, $max_services);
        remove_theme_mod($last_key);
    }

    $current_count = absint(get_theme_mod('callamir_services_count', 3));
    $new_count = $current_count;

    if ($service_id <= $current_count && $current_count > 0) {
        $new_count = max(0, $current_count - 1);
        set_theme_mod('callamir_services_count', $new_count);
    }

    wp_send_json_success(array(
        'message' => sprintf(__('Service %d deleted successfully.', 'callamir'), $service_id),
        'new_count' => $new_count,
        'service_id' => $service_id,
    ));
}
add_action('wp_ajax_callamir_delete_service', 'callamir_delete_service');

/* --------------------------------------------------------------------------
 * Blog Helper
 * -------------------------------------------------------------------------- */
function callamir_get_blog_items($posts_per_page = 3, $paged = 1) {
    return new WP_Query(array(
        'posts_per_page' => intval($posts_per_page),
        'paged' => intval($paged),
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
    ));
}



/* --------------------------------------------------------------------------
 * Theme Mod Helper
 * -------------------------------------------------------------------------- */
if (!function_exists('callamir_sanitize_select')) {
    function callamir_sanitize_select($input, $setting) {
        $input = sanitize_key($input);
        $control = $setting->manager->get_control($setting->id);
        if ($control && isset($control->choices[$input])) {
            return $input;
        }

        return $setting->default;
    }
}

if (!function_exists('callamir_sanitize_css_value')) {
    function callamir_sanitize_css_value($value) {
        if (is_string($value)) {
            $value = wp_strip_all_tags($value);
            $value = preg_replace('/\s+/', ' ', $value);
            return trim($value);
        }

        return '';
    }
}

if (!function_exists('callamir_sanitize_dimension')) {
    function callamir_sanitize_dimension($value) {
        $value = callamir_sanitize_css_value($value);

        if ($value === '') {
            return '';
        }

        if (preg_match('/^calc\(.+\)$/i', $value)) {
            return $value;
        }

        if (preg_match('/^-?\d+(?:\.\d+)?(px|em|rem|%|vh|vw|ch|ex)?$/i', $value)) {
            return $value;
        }

        return $value;
    }
}

if (!function_exists('callamir_sanitize_font_family')) {
    function callamir_sanitize_font_family($value) {
        if (!is_string($value)) {
            return '';
        }

        $value = wp_strip_all_tags($value);
        $value = preg_replace("/[^a-zA-Z0-9\s,\-\"']+/", '', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }
}

/* --------------------------------------------------------------------------
 * Fallback Menu Function
 * -------------------------------------------------------------------------- */
if (!function_exists('callamir_adjust_menu_for_persian')) {
    /**
     * Ensure the Persian navigation uses RTL ordering and translations.
     *
     * @param array   $items Menu items.
     * @param stdClass $args  Menu arguments.
     * @return array
     */
    function callamir_adjust_menu_for_persian($items, $args) {
        if (!function_exists('callamir_get_visitor_lang') || callamir_get_visitor_lang() !== 'fa') {
            return $items;
        }

        if (empty($items) || !is_array($items)) {
            return $items;
        }

        $desired_order = array(
            '#home' => __('خانه', 'callamir'),
            '#services' => __('خدمات', 'callamir'),
            '#contact' => __('تماس', 'callamir'),
            '#blog' => __('وبلاگ', 'callamir'),
        );

        $ordered = array();
        $others = array();

        foreach ($items as $item) {
            if (!is_object($item) || empty($item->url)) {
                $others[] = $item;
                continue;
            }

            $fragment = '';
            $hash_position = strpos($item->url, '#');
            if ($hash_position !== false) {
                $fragment = substr($item->url, $hash_position);
            }

            if ($fragment && isset($desired_order[$fragment])) {
                $item->title = $desired_order[$fragment];
                $ordered[$fragment] = $item;
            } else {
                $others[] = $item;
            }
        }

        $result = array();
        foreach ($desired_order as $fragment => $title) {
            if (isset($ordered[$fragment])) {
                $result[] = $ordered[$fragment];
            }
        }

        $result = array_merge($result, $others);

        foreach ($result as $index => $item) {
            if (is_object($item)) {
                $item->menu_order = $index + 1;
            }
        }

        return $result;
    }

    add_filter('wp_nav_menu_objects', 'callamir_adjust_menu_for_persian', 20, 2);
}

function callamir_fallback_menu($args = array()) {
    $lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : get_theme_mod('site_language', 'en');
    $menu_class = isset($args['menu_class']) ? (string) $args['menu_class'] : '';
    $is_mobile = $menu_class && strpos($menu_class, 'nav-menu-mobile') !== false;

    $classes = $is_mobile ? 'nav-menu-mobile' : 'nav-menu-desktop';

    if (!empty($menu_class)) {
        $additional_classes = array_filter(array_map('sanitize_html_class', preg_split('/\s+/', $menu_class)));
        if (!empty($additional_classes)) {
            $classes = implode(' ', array_unique(array_merge(array($classes), $additional_classes)));
        }
    }

    if ($lang === 'fa') {
        $rtl_class = $is_mobile ? 'nav-menu-mobile--rtl' : 'nav-menu-desktop--rtl';
        if (strpos($classes, $rtl_class) === false) {
            $classes .= ' ' . $rtl_class;
        }
    }

    $menu_items = array(
        array(
            'title' => ($lang === 'fa') ? __('خانه', 'callamir') : __('Home', 'callamir'),
            'url' => '#home',
        ),
        array(
            'title' => ($lang === 'fa') ? __('خدمات', 'callamir') : __('Services', 'callamir'),
            'url' => '#services',
        ),
        array(
            'title' => ($lang === 'fa') ? __('تماس', 'callamir') : __('Contact', 'callamir'),
            'url' => '#contact',
        ),
        array(
            'title' => ($lang === 'fa') ? __('وبلاگ', 'callamir') : __('Blog', 'callamir'),
            'url' => '#blog',
        ),
    );

    echo '<ul class="' . esc_attr(trim($classes)) . '">';
    foreach ($menu_items as $item) {
        echo '<li><a href="' . esc_url($item['url']) . '" class="nav-link">' . esc_html($item['title']) . '</a></li>';
    }
    echo '</ul>';
}

/* --------------------------------------------------------------------------
 * Elementor Compatibility and Optimizations
 * -------------------------------------------------------------------------- */
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

/* --------------------------------------------------------------------------
 * Performance Optimizations
 * -------------------------------------------------------------------------- */
function callamir_performance_optimizations() {
    // Remove unnecessary WordPress features for better performance
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    
    // Optimize WordPress queries
    add_action('pre_get_posts', function($query) {
        if (!is_admin() && $query->is_main_query()) {
            $query->set('no_found_rows', true);
            $query->set('update_post_meta_cache', false);
            $query->set('update_post_term_cache', false);
        }
    });
}
add_action('init', 'callamir_performance_optimizations');

/* --------------------------------------------------------------------------
 * Accessibility Improvements
 * -------------------------------------------------------------------------- */
function callamir_accessibility_improvements() {
    // Add skip links for better accessibility
    add_action('wp_head', function() {
        echo '<style>
            .skip-link {
                position: absolute;
                top: -40px;
                left: 6px;
                background: #FFD700;
                color: #0A0A0A;
                padding: 8px 16px;
                text-decoration: none;
                border-radius: 4px;
                z-index: 10000;
                transition: top 0.3s;
            }
            .skip-link:focus {
                top: 6px;
            }
        </style>';
    });
    
    // Add ARIA labels and roles
    add_filter('nav_menu_link_attributes', function($atts, $item, $args) {
        if ($args->theme_location === 'one_page_menu') {
            $atts['role'] = 'menuitem';
            $atts['aria-label'] = $item->title;
        }
        return $atts;
    }, 10, 3);
}
add_action('init', 'callamir_accessibility_improvements');

// Simple sanitize_checkbox function to prevent fatal errors
if (!function_exists('sanitize_checkbox')) {
    function sanitize_checkbox($input) {
        return (bool) $input;
    }
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
        $problematic_keys = array(
            'callamir_enable_header_stars',
            'callamir_enable_footer_stars', 
            'callamir_enable_hero_effect',
            'callamir_enable_services_effect',
            'services_enable_cosmic_effect',
            'services_enable_liquid_effect'
        );
        
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
/* --------------------------------------------------------------------------
 * Language-Aware Theme Mods (Automatic)
 * This filter makes get_theme_mod('key') return the Farsi value from 'key_fa'
 * automatically whenever the visitor language is 'fa'. This avoids touching
 * template files that already use get_theme_mod() in English.
 * -------------------------------------------------------------------------- */
if (!function_exists('callamir_filter_theme_mods_by_lang')) {
    function callamir_filter_theme_mods_by_lang($mods) {
        if (!is_array($mods)) {
            return $mods;
        }
        if (!function_exists('callamir_get_visitor_lang')) {
            return $mods;
        }
        $lang = callamir_get_visitor_lang(false);
        if ($lang === 'en' && isset($mods['site_language']) && $mods['site_language'] === 'fa' && !isset($_GET['lang'])) {
            $lang = 'fa';
        }
        if ($lang !== 'fa') {
            return $mods;
        }
        // For FA: if a *_fa variant exists and is non-empty, override the base key
        foreach ($mods as $key => $value) {
            if (substr($key, -3) === '_fa') {
                $base = substr($key, 0, -3);
                $normalized = callamir_normalize_theme_mod_value($mods[$key]);
                if ($normalized !== null) {
                    $mods[$base] = $normalized;
                }
            }
        }
        return $mods;
    }
    add_filter('option_theme_mods_' . get_stylesheet(), 'callamir_filter_theme_mods_by_lang', 20, 1);
}

/* --------------------------------------------------------------------------
 * Helper: Language-aware text fetcher for customizer keys.
 * Use in templates if you want explicit control:
 * echo callamir_get_text('service_title', 'Service Title', 'عنوان سرویس');
 * -------------------------------------------------------------------------- */
if (!function_exists('callamir_get_text')) {
    function callamir_get_text($key, $default_en = '', $default_fa = '') {
        $lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';

        $preferred_default = ($lang === 'fa' && $default_fa !== '') ? $default_fa : $default_en;
        $value = callamir_mod($key, $lang, $preferred_default);

        if ($lang === 'fa' && ($value === '' || $value === $preferred_default)) {
            $fallback = callamir_mod($key, 'en', $default_en);
            if ($fallback !== '') {
                $value = $fallback;
            }
        }

        if ($value === '') {
            return $preferred_default;
        }

        return $value;
    }
}

/* --------------------------------------------------------------------------
 * Helper: Inline literal translator (for hardcoded strings)
 * Example: echo callamir_t('Contact Us', 'تماس با ما');
 * -------------------------------------------------------------------------- */
if (!function_exists('callamir_t')) {
    function callamir_t($en, $fa) {
        $lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';
        return ($lang === 'fa') ? $fa : $en;
    }
}

?>