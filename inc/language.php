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
        static $cached = null;

        if (null !== $cached) {
            return $cached;
        }

        $languages = array(
            'en' => array(
                'label' => 'English',
                'direction' => 'ltr',
            ),
            'fa' => array(
                'label' => 'Persian',
                'direction' => 'rtl',
            ),
        );

        if (doing_filter('locale')) {
            return $languages;
        }

        $languages['en']['label'] = __('English', 'callamir');
        $languages['fa']['label'] = __('Persian', 'callamir');

        $cached = $languages;

        return $languages;
    }
}

if (!function_exists('callamir_get_locale_for_language')) {
    /**
     * Map a short language code to a full WordPress locale string.
     *
     * @param string $lang Two character language code.
     * @return string Locale identifier understood by WordPress.
     */
    function callamir_get_locale_for_language($lang) {
        switch ($lang) {
            case 'fa':
                return 'fa_IR';
            case 'en':
            default:
                return 'en_US';
        }
    }
}

if (!function_exists('callamir_get_preview_language')) {
    /**
     * Inspect the Customizer preview and return the language being previewed.
     *
     * @return string|null Two character language code or null when not previewing.
     */
    function callamir_get_preview_language() {
        static $preview_lang = null;
        static $did_lookup = false;

        if ($did_lookup) {
            return $preview_lang;
        }

        $did_lookup = true;

        if (!function_exists('is_customize_preview') || !is_customize_preview()) {
            return null;
        }

        if (!class_exists('WP_Customize_Manager')) {
            return null;
        }

        global $wp_customize;

        if (!$wp_customize instanceof WP_Customize_Manager) {
            return null;
        }

        $setting = $wp_customize->get_setting('site_language');
        if (!$setting) {
            return null;
        }

        $value = $setting->post_value();
        if (!is_string($value) || $value === '') {
            $value = $setting->value();
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        $candidate = sanitize_key($value);
        $supported = callamir_get_supported_languages();

        if (isset($supported[$candidate])) {
            $preview_lang = $candidate;
        }

        return $preview_lang;
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

        $preview_lang = callamir_get_preview_language();
        if ($preview_lang && isset($supported[$preview_lang])) {
            return $preview_lang;
        }

        $default = get_theme_mod('site_language', 'en');
        return isset($supported[$default]) ? $default : 'en';
    }
}

if (!function_exists('callamir_get_visitor_lang')) {
    /**
     * Determine the visitor language via query parameter or the saved default.
     *
     * @param bool $use_theme_mod Whether to consider the saved default language.
     * @return string Two character locale key (en|fa).
     */
    function callamir_get_visitor_lang($use_theme_mod = true) {
        $supported = callamir_get_supported_languages();

        if (isset($_GET['lang'])) {
            $requested = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
            if (isset($supported[$requested])) {
                return $requested;
            }
        }

        $preview_lang = callamir_get_preview_language();
        if ($preview_lang) {
            return $preview_lang;
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

if (!function_exists('callamir_filter_theme_mods_by_lang')) {
    /**
     * Make theme mods aware of the active visitor language.
     *
     * @param array<string, mixed> $mods Theme modifications loaded by WordPress.
     * @return array<string, mixed>
     */
    function callamir_filter_theme_mods_by_lang($mods) {
        if (!is_array($mods) || empty($mods)) {
            return $mods;
        }

        if (!function_exists('callamir_get_supported_languages') || !function_exists('callamir_get_visitor_lang')) {
            return $mods;
        }

        $supported = callamir_get_supported_languages();
        $lang = callamir_get_visitor_lang(false);

        if ((!is_string($lang) || $lang === '' || !isset($supported[$lang]) || 'en' === $lang) && !isset($_GET['lang'])) {
            $default_lang = null;

            if (isset($mods['site_language'])) {
                $default_lang = sanitize_key($mods['site_language']);
            }

            if ((!$default_lang || !isset($supported[$default_lang]))) {
                $preview_lang = callamir_get_preview_language();
                if ($preview_lang && isset($supported[$preview_lang])) {
                    $default_lang = $preview_lang;
                }
            }

            if ($default_lang && isset($supported[$default_lang])) {
                $lang = $default_lang;
            }
        }

        if (!is_string($lang) || !isset($supported[$lang]) || 'en' === $lang) {
            return $mods;
        }

        $suffix = '_' . $lang;
        $suffix_length = strlen($suffix);

        foreach ($mods as $key => $value) {
            if (!is_string($key) || substr($key, -$suffix_length) !== $suffix) {
                continue;
            }

            $base = substr($key, 0, -$suffix_length);
            if ($base === '') {
                continue;
            }

            $normalized = callamir_normalize_theme_mod_value($value);
            if ($normalized !== null) {
                $mods[$base] = $normalized;
            }
        }

        return $mods;
    }

    add_filter('option_theme_mods_' . get_stylesheet(), 'callamir_filter_theme_mods_by_lang', 20, 1);
}

if (!function_exists('callamir_get_text')) {
    /**
     * Helper for retrieving a translated theme mod value.
     *
     * @param string $key        Theme mod key base.
     * @param string $default_en Default English text.
     * @param string $default_fa Default Persian text.
     * @return string
     */
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

if (!function_exists('callamir_t')) {
    /**
     * Inline literal translator for template strings.
     *
     * @param string $en English string.
     * @param string $fa Persian string.
     * @return string
     */
    function callamir_t($en, $fa) {
        $lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';
        return ($lang === 'fa') ? $fa : $en;
    }
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

if (!function_exists('callamir_filter_locale_for_frontend')) {
    /**
     * Ensure WordPress boots with the correct locale for the active visitor language.
     *
     * Without this the front-end can render with LTR defaults even when the
     * visitor has switched to a RTL language because WordPress would continue
     * using the site default locale.
     *
     * @param string $locale Current locale detected by WordPress.
     * @return string
     */
    function callamir_filter_locale_for_frontend($locale) {
        $doing_rest = function_exists('wp_doing_rest') ? wp_doing_rest() : (defined('REST_REQUEST') && REST_REQUEST);

        if (is_admin() && !wp_doing_ajax() && !$doing_rest) {
            return $locale;
        }

        $active = callamir_get_visitor_lang();
        return callamir_get_locale_for_language($active);
    }

    add_filter('locale', 'callamir_filter_locale_for_frontend');
}

if (!function_exists('callamir_language_attributes')) {
    /**
     * Override the <html> tag attributes so they reflect the visitor's chosen language.
     *
     * @param string $output Existing attribute string.
     * @param string $doctype Doctype slug provided by WordPress.
     * @return string
     */
    function callamir_language_attributes($output, $doctype) {
        if ('html' !== $doctype) {
            return $output;
        }

        $lang = callamir_get_visitor_lang();
        $supported = callamir_get_supported_languages();

        if (!isset($supported[$lang])) {
            return $output;
        }

        $attributes = array(
            'lang' => str_replace('_', '-', callamir_get_locale_for_language($lang)),
            'dir' => $supported[$lang]['direction'],
        );

        $fragments = array();
        foreach ($attributes as $key => $value) {
            if ($value === '') {
                continue;
            }

            $fragments[] = sprintf('%s="%s"', $key, esc_attr($value));
        }

        return implode(' ', $fragments);
    }

    add_filter('language_attributes', 'callamir_language_attributes', 10, 2);
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
        $candidates = array();

        if (!empty($lang)) {
            $candidates[] = $key_base . '_' . $lang;
        }

        $candidates[] = $key_base;

        if (function_exists('callamir_get_default_language')) {
            $default_lang = callamir_get_default_language();
            if ($default_lang && $default_lang !== $lang) {
                $candidates[] = $key_base . '_' . $default_lang;
            }
        }

        if (function_exists('callamir_get_supported_languages')) {
            foreach (array_keys(callamir_get_supported_languages()) as $supported_lang) {
                if ($supported_lang === $lang) {
                    continue;
                }

                $candidates[] = $key_base . '_' . $supported_lang;
            }
        }

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
            echo '<!-- Language Debug: Current=' . callamir_get_visitor_lang() . ', Default=' . callamir_get_default_language() . ' -->';
        }
    }

    add_action('wp_head', 'callamir_debug_language_switching');
}
