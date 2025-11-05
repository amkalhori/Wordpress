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
     * @param bool $allow_locale_lookup Whether to inspect the WordPress locale for fallback detection.
     * @return string Two-letter language code.
     */
    function callamir_get_visitor_lang($allow_locale_lookup = true) {
        static $detected_lang = array();
        static $resolving = false;

        $cache_key = $allow_locale_lookup ? 'full' : 'safe';

        if (isset($detected_lang[$cache_key])) {
            return $detected_lang[$cache_key];
        }

        if ($resolving) {
            if (isset($detected_lang['full'])) {
                return $detected_lang['full'];
            }

            return 'en';
        }

        $resolving = true;
        $previous_flag = isset($GLOBALS['callamir_resolving_language']) ? (bool) $GLOBALS['callamir_resolving_language'] : false;
        $GLOBALS['callamir_resolving_language'] = true;

        $supported = function_exists('callamir_get_supported_languages') ? callamir_get_supported_languages() : array('en' => array());
        $language = null;

        if (isset($_GET['lang'])) {
            $lang = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));

            if (isset($supported[$lang])) {
                $language = $lang;
            }
        }

        if (null === $language && function_exists('callamir_get_preview_language')) {
            $preview_lang = callamir_get_preview_language();
            if ($preview_lang && isset($supported[$preview_lang])) {
                $language = $preview_lang;
            }
        }

        if (null === $language) {
            $locale_lang = null;

            if ($allow_locale_lookup && !doing_filter('locale')) {
                $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
                if ($locale && function_exists('callamir_get_language_from_locale')) {
                    $locale_lang = callamir_get_language_from_locale($locale);
                }
            } elseif (!$allow_locale_lookup) {
                if (isset($detected_lang['full'])) {
                    $locale_lang = $detected_lang['full'];
                } else {
                    $raw_locale = isset($GLOBALS['locale']) ? $GLOBALS['locale'] : '';
                    if (!$raw_locale) {
                        $raw_locale = get_option('WPLANG');
                    }

                    if ($raw_locale && function_exists('callamir_get_language_from_locale')) {
                        $locale_lang = callamir_get_language_from_locale($raw_locale);
                    }
                }
            }

            if ($locale_lang && isset($supported[$locale_lang])) {
                $language = $locale_lang;
            }
        }

        if (null === $language && function_exists('callamir_get_default_language')) {
            $default = callamir_get_default_language();
            if (isset($supported[$default])) {
                $language = $default;
            }
        }

        if (null === $language || !isset($supported[$language])) {
            $language = 'en';
        }

        $detected_lang[$cache_key] = $language;
        if ($allow_locale_lookup) {
            $detected_lang['safe'] = $language;
        }

        $GLOBALS['callamir_resolving_language'] = $previous_flag;
        $resolving = false;

        return $language;
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
