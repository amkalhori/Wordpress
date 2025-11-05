<?php
/**
 * CallAmir Theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

$callamir_includes = array(
    'inc/language.php',
    'inc/navigation.php',
    'inc/sanitization.php',
    'inc/services.php',
    'inc/community-questions.php',
    'inc/setup.php',
    'inc/assets.php',
    'inc/dynamic-css.php',
    'inc/customizer.php',
    'inc/blog.php',
    'inc/integrations.php',
    'inc/performance.php',
    'inc/admin-tools.php',
    'inc/theme-colors.php',
);

foreach ($callamir_includes as $relative_path) {
    $path = get_template_directory() . '/' . $relative_path;

    if (file_exists($path)) {
        require_once $path;
    }
}

if (!function_exists('callamir_get_visitor_lang')) {
    /**
     * Detect the visitor language from the request context.
     *
     * @return string Two-letter language code.
     */
    function callamir_get_visitor_lang() {
        static $detected_lang = null;

        if (null !== $detected_lang) {
            return $detected_lang;
        }

        $supported = function_exists('callamir_get_supported_languages') ? callamir_get_supported_languages() : array('en' => array());

        if (isset($_GET['lang'])) {
            $lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));

            if (isset($supported[$lang])) {
                $detected_lang = $lang;
                return $detected_lang;
            }
        }

        if (function_exists('callamir_get_preview_language')) {
            $preview_lang = callamir_get_preview_language();
            if ($preview_lang && isset($supported[$preview_lang])) {
                $detected_lang = $preview_lang;
                return $detected_lang;
            }
        }

        $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
        if ($locale) {
            $locale_lang = function_exists('callamir_get_language_from_locale') ? callamir_get_language_from_locale($locale) : null;
            if ($locale_lang && isset($supported[$locale_lang])) {
                $detected_lang = $locale_lang;
                return $detected_lang;
            }
        }

        $default = function_exists('callamir_get_default_language') ? callamir_get_default_language() : 'en';
        if (isset($supported[$default])) {
            $detected_lang = $default;
            return $detected_lang;
        }

        $detected_lang = 'en';
        return $detected_lang;
    }
}

if (!function_exists('callamir_mod')) {
    /**
     * Retrieve a language-aware theme modification value with fallback support.
     *
     * @param string $field_base Base field identifier without language suffix.
     * @param string $fallback   Fallback value used when the theme mod is empty.
     * @return string
     */
    function callamir_mod($field_base, $fallback = '') {
        $lang = callamir_get_visitor_lang();
        $value = get_theme_mod($field_base . '_' . $lang);

        if ($value !== '' && $value !== false && $value !== null) {
            return $value;
        }

        return $fallback;
    }
}
