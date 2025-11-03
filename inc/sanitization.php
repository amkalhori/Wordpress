<?php
/**
 * Sanitization helpers for Customizer and theme mods.
 */

if (!defined('ABSPATH')) {
    exit;
}

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
