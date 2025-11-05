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

if (!function_exists('callamir_override_load_textdomain')) {
    /**
     * Load the theme translations from the human-readable PO files so we do not
     * need to ship compiled MO binaries. Some hosting platforms disallow
     * uploading binary files which would otherwise prevent the theme from being
     * installed or updated.
     *
     * @param bool   $override Whether another hook has already handled loading.
     * @param string $domain   Text domain requested by WordPress.
     * @param string $mofile   Expected MO file path.
     * @return bool True when the translations were loaded from the PO file.
     */
    function callamir_override_load_textdomain($override, $domain, $mofile) {
        if ('callamir' !== $domain) {
            return $override;
        }

        $po_file = preg_replace('/\.mo$/', '.po', $mofile);
        if (!$po_file || !file_exists($po_file)) {
            return false;
        }

        if (!class_exists('PO')) {
            require_once ABSPATH . WPINC . '/pomo/po.php';
        }

        $translations = new PO();
        if (!$translations->import_from_file($po_file)) {
            return false;
        }

        $GLOBALS['l10n'][$domain] = $translations;

        return true;
    }

    add_filter('override_load_textdomain', 'callamir_override_load_textdomain', 10, 3);
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

if (!function_exists('callamir_get_language_from_locale')) {
    /**
     * Resolve a WordPress locale back to the theme language code.
     *
     * @param string $locale Locale identifier provided by WordPress.
     * @return string|null Two character language code or null when unknown.
     */
    function callamir_get_language_from_locale($locale) {
        if (!is_string($locale) || $locale === '') {
            return null;
        }

        $normalized = strtolower(str_replace('-', '_', $locale));
        $supported = callamir_get_supported_languages();

        foreach ($supported as $code => $meta) {
            $locale_code = strtolower(str_replace('-', '_', callamir_get_locale_for_language($code)));

            if ($normalized === $locale_code || $normalized === strtolower($code)) {
                return $code;
            }
        }

        return null;
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
        static $cached_default = null;
        static $resolving = false;

        if (null !== $cached_default) {
            return $cached_default;
        }

        if ($resolving) {
            return 'en';
        }

        $resolving = true;
        $supported = callamir_get_supported_languages();

        $preview_lang = callamir_get_preview_language();
        if ($preview_lang && isset($supported[$preview_lang])) {
            $cached_default = $preview_lang;
        }

        if (null === $cached_default) {
            $previous_flag = isset($GLOBALS['callamir_resolving_language']) ? (bool) $GLOBALS['callamir_resolving_language'] : false;
            $GLOBALS['callamir_resolving_language'] = true;

            $default = get_theme_mod('site_language', 'en');

            $GLOBALS['callamir_resolving_language'] = $previous_flag;

            if (isset($supported[$default])) {
                $cached_default = $default;
            }
        }

        if (null === $cached_default || !isset($supported[$cached_default])) {
            $cached_default = 'en';
        }

        $resolving = false;

        return $cached_default;
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
        static $processing = false;

        if ($processing) {
            return $mods;
        }

        if (!empty($GLOBALS['callamir_resolving_language'])) {
            return $mods;
        }

        if (!is_array($mods) || empty($mods)) {
            return $mods;
        }

        if (!function_exists('callamir_get_supported_languages') || !function_exists('callamir_get_visitor_lang')) {
            return $mods;
        }

        $processing = true;
        $result = $mods;

        $supported = callamir_get_supported_languages();
        $lang = callamir_get_visitor_lang();

        if (is_string($lang) && isset($supported[$lang]) && 'en' !== $lang) {
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
                if (null !== $normalized) {
                    $result[$base] = $normalized;
                }
            }
        }

        $processing = false;

        return $result;
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
        $value = callamir_mod($key, $preferred_default);

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

if (!function_exists('callamir_resolve_language_for_locale_filter')) {
    /**
     * Determine the language a locale filter should enforce without triggering
     * additional locale lookups that could recurse back into the filter.
     *
     * @param string $locale Locale currently being filtered by WordPress.
     * @return string Two-letter language code.
     */
    function callamir_resolve_language_for_locale_filter($locale) {
        static $cache = array();

        $cache_key = is_string($locale) ? strtolower($locale) : '';

        if (isset($cache[$cache_key])) {
            return $cache[$cache_key];
        }

        $supported = function_exists('callamir_get_supported_languages') ? callamir_get_supported_languages() : array();
        $language = null;

        if (isset($_GET['lang'])) {
            $candidate = strtolower(sanitize_text_field(wp_unslash($_GET['lang'])));
            if (isset($supported[$candidate])) {
                $language = $candidate;
            }
        }

        if (null === $language && function_exists('callamir_get_preview_language')) {
            $preview = callamir_get_preview_language();
            if ($preview && isset($supported[$preview])) {
                $language = $preview;
            }
        }

        if (null === $language && function_exists('callamir_get_default_language')) {
            $default = callamir_get_default_language();
            if ($default && isset($supported[$default])) {
                $language = $default;
            }
        }

        if (null === $language && function_exists('callamir_get_language_from_locale')) {
            $derived = callamir_get_language_from_locale($locale);
            if ($derived && isset($supported[$derived])) {
                $language = $derived;
            }
        }

        if (null === $language) {
            $language = 'en';
        }

        $cache[$cache_key] = $language;

        return $language;
    }
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
        static $processing = false;

        if ($processing) {
            return $locale;
        }

        $doing_rest = function_exists('wp_doing_rest') ? wp_doing_rest() : (defined('REST_REQUEST') && REST_REQUEST);

        $doing_ajax = function_exists('wp_doing_ajax') ? wp_doing_ajax() : (defined('DOING_AJAX') && DOING_AJAX);

        if (is_admin() && !$doing_ajax && !$doing_rest) {
            return $locale;
        }

        $processing = true;

        try {
            $active = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang(false) : 'en';

            if (!function_exists('callamir_get_supported_languages')) {
                return callamir_get_locale_for_language($active);
            }

            $supported = callamir_get_supported_languages();

            if (!isset($supported[$active])) {
                $fallback = function_exists('callamir_get_language_from_locale') ? callamir_get_language_from_locale($locale) : null;
                if ($fallback && isset($supported[$fallback])) {
                    $active = $fallback;
                }
            }

            $target_locale = callamir_get_locale_for_language($active);

            if ('' === $target_locale) {
                return $locale;
            }

            return $target_locale;
        } finally {
            $processing = false;
        }
    }

    add_filter('locale', 'callamir_filter_locale_for_frontend');
}

if (!function_exists('callamir_prime_frontend_language')) {
    /**
     * Switch the runtime locale early so front-end requests honour the visitor selection.
     *
     * WordPress determines the locale before the active theme files are loaded which means
     * filters added here are too late to influence the initial locale calculation. By
     * explicitly switching locales during `after_setup_theme` we ensure translations and
     * RTL styles load for the requested language on non-admin requests.
     */
    function callamir_prime_frontend_language() {
        $doing_rest = function_exists('wp_doing_rest') ? wp_doing_rest() : (defined('REST_REQUEST') && REST_REQUEST);

        $doing_ajax = function_exists('wp_doing_ajax') ? wp_doing_ajax() : (defined('DOING_AJAX') && DOING_AJAX);

        if (is_admin() && !$doing_ajax && !$doing_rest) {
            return;
        }

        $target_lang = callamir_get_visitor_lang();
        $target_locale = callamir_get_locale_for_language($target_lang);
        $current_locale = get_locale();

        if ($target_locale === $current_locale || '' === $target_locale) {
            return;
        }

        if (function_exists('switch_to_locale') && switch_to_locale($target_locale)) {
            return;
        }

        global $locale;
        $locale = $target_locale;
    }

    add_action('after_setup_theme', 'callamir_prime_frontend_language', 0);
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
