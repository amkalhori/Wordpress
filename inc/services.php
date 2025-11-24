<?php
/**
 * Service helpers, controls, and AJAX management.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_max_services() {
    return 6;
}

function callamir_service_patterns() {
    return [
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
    ];
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
        ['customize-controls', 'jquery'],
        wp_get_theme()->get('Version'),
        true
    );

    wp_localize_script('callamir-customizer-services', 'callamirServiceManager', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('callamir_delete_service'),
        'confirmDelete' => __('Are you sure you want to delete Service %s? This action cannot be undone.', 'callamir'),
        'success' => __('Service deleted successfully.', 'callamir'),
        'error' => __('Unable to delete the service. Please try again.', 'callamir'),
    ]);
}
add_action('customize_controls_enqueue_scripts', 'callamir_enqueue_customizer_service_assets');

function callamir_delete_service() {
    check_ajax_referer('callamir_delete_service', 'nonce');

    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(['message' => __('You are not allowed to delete services.', 'callamir')]);
    }

    $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
    $max_services = callamir_max_services();

    if ($service_id < 1 || $service_id > $max_services) {
        wp_send_json_error(['message' => __('Invalid service identifier.', 'callamir')]);
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

    wp_send_json_success([
        'message' => sprintf(__('Service %d deleted successfully.', 'callamir'), $service_id),
        'new_count' => $new_count,
        'service_id' => $service_id,
    ]);
}
add_action('wp_ajax_callamir_delete_service', 'callamir_delete_service');
