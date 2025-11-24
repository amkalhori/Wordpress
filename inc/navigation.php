<?php
/**
 * Navigation helpers for CallAmir theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('callamir_adjust_menu_for_persian')) {
    /**
     * Reorder and translate key menu anchors for the Persian experience.
     *
     * @param array $items Menu items as prepared by WordPress.
     * @param array $args  Menu arguments.
     * @return array
     */
    function callamir_adjust_menu_for_persian($items, $args) {
        if (!function_exists('callamir_get_visitor_lang') || callamir_get_visitor_lang() !== 'fa') {
            return $items;
        }

        if (!isset($args->theme_location) || $args->theme_location !== 'one_page_menu') {
            return $items;
        }

        if (empty($items) || !is_array($items)) {
            return $items;
        }

        $desired_order = [
            '#home' => __('خانه', 'callamir'),
            '#services' => __('خدمات', 'callamir'),
            '#contact' => __('تماس', 'callamir'),
            '#blog' => __('وبلاگ', 'callamir'),
        ];

        $ordered = [];
        $others = [];

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

        $result = [];
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

if (!function_exists('callamir_fallback_menu')) {
    /**
     * Provide a fallback menu when no navigation is assigned.
     *
     * @param array $args Menu arguments from wp_nav_menu.
     * @return void
     */
    function callamir_fallback_menu($args = []) {
        $lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : get_theme_mod('site_language', 'en');
        $menu_class = isset($args['menu_class']) ? (string) $args['menu_class'] : '';
        $is_mobile = $menu_class && strpos($menu_class, 'nav-menu-mobile') !== false;

        $classes = $is_mobile ? 'nav-menu-mobile' : 'nav-menu-desktop';

        if (!empty($menu_class)) {
            $additional_classes = array_filter(array_map('sanitize_html_class', preg_split('/\s+/', $menu_class)));
            if (!empty($additional_classes)) {
                $classes = implode(' ', array_unique(array_merge([$classes], $additional_classes)));
            }
        }

        if ($lang === 'fa') {
            $rtl_class = $is_mobile ? 'nav-menu-mobile--rtl' : 'nav-menu-desktop--rtl';
            if (strpos($classes, $rtl_class) === false) {
                $classes .= ' ' . $rtl_class;
            }
        }

        $menu_items = [
            [
                'title' => ($lang === 'fa') ? __('خانه', 'callamir') : __('Home', 'callamir'),
                'url' => '#home',
            ],
            [
                'title' => ($lang === 'fa') ? __('خدمات', 'callamir') : __('Services', 'callamir'),
                'url' => '#services',
            ],
            [
                'title' => ($lang === 'fa') ? __('تماس', 'callamir') : __('Contact', 'callamir'),
                'url' => '#contact',
            ],
            [
                'title' => ($lang === 'fa') ? __('وبلاگ', 'callamir') : __('Blog', 'callamir'),
                'url' => '#blog',
            ],
        ];

        echo '<ul class="' . esc_attr(trim($classes)) . '">';
        foreach ($menu_items as $item) {
            echo '<li><a href="' . esc_url($item['url']) . '" class="nav-link">' . esc_html($item['title']) . '</a></li>';
        }
        echo '</ul>';
    }
}

