<?php
/**
 * Language helpers and localisation utilities for the CallAmir theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('callamir_get_supported_languages')) {
    /**
     * Return the languages supported by the theme.
     *
     * @return array<string, array<string, string>>
     */
    function callamir_get_supported_languages() {
        return array(
            'en' => array(
                'label' => __('English', 'callamir'),
                'direction' => 'ltr',
            ),
            'fa' => array(
                'label' => __('Persian', 'callamir'),
                'direction' => 'rtl',
            ),
        );
    }
}

if (!function_exists('callamir_get_default_language')) {
    /**
     * Fetch the default theme language from the Customizer.
     *
     * @return string
     */
    function callamir_get_default_language() {
        $supported = callamir_get_supported_languages();
        $default = get_theme_mod('site_language', 'en');
        return isset($supported[$default]) ? $default : 'en';
    }
}

if (!function_exists('callamir_get_visitor_lang')) {
    /**
     * Determine the visitor language via query parameter, cookie or default.
     *
     * @param bool $use_theme_mod Whether to consider the saved default language.
     * @return string Two character locale key (en|fa).
     */
    function callamir_get_visitor_lang($use_theme_mod = true) {
        $supported = callamir_get_supported_languages();

        if (isset($_GET['lang'])) {
            $requested = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
            if (isset($supported[$requested])) {
                if (!headers_sent()) {
                    $cookie_path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
                    setcookie('language', $requested, time() + WEEK_IN_SECONDS, $cookie_path, defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '', is_ssl());
                }

                return $requested;
            }
        }

        if (isset($_COOKIE['language'])) {
            $cookie_lang = strtolower(sanitize_text_field(wp_unslash($_COOKIE['language'])));
            if (isset($supported[$cookie_lang])) {
                return $cookie_lang;
            }
        }

        if ($use_theme_mod) {
            return callamir_get_default_language();
        }

        return 'en';
    }
}

if (!function_exists('callamir_localize_url')) {
    /**
     * Append the active language query parameter to internal theme URLs.
     *
     * @param string      $url  URL to localise.
     * @param string|null $lang Optional language override.
     * @return string
     */
    function callamir_localize_url($url, $lang = null) {
        if (empty($url)) {
            return $url;
        }

        if (strpos($url, '#') === 0 || preg_match('#^(tel:|mailto:|javascript:)#i', $url)) {
            return $url;
        }

        $lang = $lang ? strtolower($lang) : callamir_get_visitor_lang();
        $supported = callamir_get_supported_languages();
        if (!isset($supported[$lang])) {
            return $url;
        }

        $fragment = '';
        if (false !== strpos($url, '#')) {
            list($url, $fragment) = explode('#', $url, 2);
            $fragment = '#' . $fragment;
        }

        $home_host = wp_parse_url(home_url(), PHP_URL_HOST);
        $url_host = wp_parse_url($url, PHP_URL_HOST);

        if ($url_host && $url_host !== $home_host) {
            return $url . $fragment;
        }

        if (!preg_match('#^https?://#i', $url)) {
            $url = home_url(ltrim($url, '/'));
        }

        $url = remove_query_arg('lang', $url);
        $url = add_query_arg('lang', $lang, $url);

        return $url . $fragment;
    }
}

if (!function_exists('callamir_preserve_language_in_nav_menu')) {
    /**
     * Ensure nav menu links keep the visitor's language selection.
     *
     * @param array  $atts Nav menu attributes.
     * @param object $item Nav menu item data object.
     * @return array
     */
    function callamir_preserve_language_in_nav_menu($atts, $item) {
        if (!empty($atts['href'])) {
            $atts['href'] = callamir_localize_url($atts['href']);
        }

        return $atts;
    }

    add_filter('nav_menu_link_attributes', 'callamir_preserve_language_in_nav_menu', 10, 2);
}

if (!function_exists('callamir_language_body_class')) {
    /**
     * Append the active language to the body classes for styling hooks.
     *
     * @param array $classes Existing body classes.
     * @return array
     */
    function callamir_language_body_class($classes) {
        $classes[] = 'lang-' . callamir_get_visitor_lang();
        return $classes;
    }

    add_filter('body_class', 'callamir_language_body_class');
}

if (!function_exists('callamir_normalize_theme_mod_value')) {
    /**
     * Normalize a Customizer setting so translation lookups can determine
     * whether the stored option is usable.
     *
     * @param mixed $value Raw value retrieved from get_theme_mod.
     * @return string|null Normalized string or null if unusable.
     */
    function callamir_normalize_theme_mod_value($value) {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_bool($value)) {
            return null;
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') {
                return null;
            }

            $lower = strtolower($trimmed);
            if (in_array($lower, array('1', '0', 'true', 'false', 'yes', 'no', 'on', 'off'), true)) {
                return null;
            }

            return $trimmed;
        }

        if (is_numeric($value)) {
            if ($value == 1 || $value == 0) {
                return null;
            }

            return (string) $value;
        }

        return null;
    }
}

if (!function_exists('callamir_mod')) {
    /**
     * Retrieve a translated theme mod value with graceful fallbacks.
     *
     * @param string $key_base Base key without language suffix.
     * @param string $lang     Target language code.
     * @param string $default  Default fallback string.
     * @return string
     */
    function callamir_mod($key_base, $lang, $default = '') {
        $candidates = array(
            $key_base . '_' . $lang,
            $lang === 'fa' ? $key_base . '_fa' : $key_base . '_en',
            $lang === 'fa' ? $key_base . '_en' : $key_base . '_fa',
            $key_base,
        );

        $candidates = array_values(array_unique(array_filter($candidates)));

        foreach ($candidates as $candidate_key) {
            $value = callamir_normalize_theme_mod_value(get_theme_mod($candidate_key, null));
            if ($value !== null) {
                return $value;
            }
        }

        return $default;
    }
}

if (!function_exists('callamir_debug_language_switching')) {
    /**
     * Output helpful debugging information for administrators.
     */
    function callamir_debug_language_switching() {
        if (current_user_can('manage_options')) {
            echo '<!-- Language Debug: Current=' . callamir_get_visitor_lang() . ', Cookie=' . (isset($_COOKIE['language']) ? sanitize_text_field(wp_unslash($_COOKIE['language'])) : 'Not set') . ' -->';
        }
    }

    add_action('wp_head', 'callamir_debug_language_switching');
}
