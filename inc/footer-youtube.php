<?php
/**
 * Footer YouTube subscribe section helpers.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Retrieve defaults for the YouTube subscribe section.
 *
 * @return array
 */
function callamir_get_footer_youtube_defaults() {
    return [
        'channel_url' => 'https://www.youtube.com/',
        'subscribe_url' => 'https://www.youtube.com/?sub_confirmation=1',
        'cta_en' => __('Subscribe to our YouTube channel', 'callamir'),
        'cta_fa' => __('در کانال یوتیوب ما مشترک شوید', 'callamir'),
        'button_color' => '#ff0000',
        'button_hover_color' => '#cc0000',
        'logo_theme' => 'light',
        'logo_custom_color' => '#ff0000',
    ];
}

/**
 * Register Customizer controls for the YouTube subscribe section.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function callamir_register_footer_youtube_controls($wp_customize) {
    $defaults = callamir_get_footer_youtube_defaults();

    $wp_customize->add_section('callamir_footer_youtube', [
        'title' => __('Footer YouTube Subscribe', 'callamir'),
        'priority' => 33,
        'description' => __('Configure the YouTube subscribe prompt displayed above the footer copyright.', 'callamir'),
    ]);

    $wp_customize->add_setting('footer_youtube_channel_url', [
        'default' => $defaults['channel_url'],
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_youtube_channel_url', [
        'label' => __('YouTube Channel URL', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('footer_youtube_subscribe_url', [
        'default' => $defaults['subscribe_url'],
        'sanitize_callback' => 'esc_url_raw',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_youtube_subscribe_url', [
        'label' => __('YouTube Subscribe URL', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'type' => 'url',
        'description' => __('Use a URL such as https://www.youtube.com/@channel?sub_confirmation=1', 'callamir'),
    ]);

    $wp_customize->add_setting('footer_youtube_cta_text_en', [
        'default' => $defaults['cta_en'],
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_youtube_cta_text_en', [
        'label' => __('CTA Text (English)', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('footer_youtube_cta_text_fa', [
        'default' => $defaults['cta_fa'],
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_youtube_cta_text_fa', [
        'label' => __('CTA Text (Persian)', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'type' => 'text',
    ]);

    $wp_customize->add_setting('footer_youtube_button_color', [
        'default' => $defaults['button_color'],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_youtube_button_color', [
        'label' => __('Subscribe Button Color', 'callamir'),
        'section' => 'callamir_footer_youtube',
    ]));

    $wp_customize->add_setting('footer_youtube_button_hover_color', [
        'default' => $defaults['button_hover_color'],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_youtube_button_hover_color', [
        'label' => __('Subscribe Button Hover Color', 'callamir'),
        'section' => 'callamir_footer_youtube',
    ]));

    $wp_customize->add_setting('footer_youtube_logo_theme', [
        'default' => $defaults['logo_theme'],
        'sanitize_callback' => 'callamir_sanitize_select',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control('footer_youtube_logo_theme', [
        'label' => __('Logo Color Theme', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'type' => 'select',
        'choices' => [
            'light' => __('Light', 'callamir'),
            'dark' => __('Dark', 'callamir'),
            'custom' => __('Custom', 'callamir'),
        ],
    ]);

    $wp_customize->add_setting('footer_youtube_logo_custom_color', [
        'default' => $defaults['logo_custom_color'],
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_youtube_logo_custom_color', [
        'label' => __('Logo Custom Color', 'callamir'),
        'section' => 'callamir_footer_youtube',
        'description' => __('Used when the logo color theme is set to Custom.', 'callamir'),
    ]));

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial('footer_youtube_subscribe', [
            'selector' => '.footer-youtube-subscribe',
            'settings' => [
                'footer_youtube_channel_url',
                'footer_youtube_subscribe_url',
                'footer_youtube_cta_text_en',
                'footer_youtube_cta_text_fa',
                'footer_youtube_button_color',
                'footer_youtube_button_hover_color',
                'footer_youtube_logo_theme',
                'footer_youtube_logo_custom_color',
            ],
            'render_callback' => 'callamir_render_footer_youtube_partial',
        ]);
    }
}
add_action('customize_register', 'callamir_register_footer_youtube_controls');

/**
 * Build an embeddable YouTube URL for channel-style links when oEmbed fails.
 *
 * @param string $channel_url Raw YouTube channel URL.
 * @return string
 */
function callamir_normalize_youtube_embed_url($channel_url) {
    $parsed = wp_parse_url($channel_url);
    if (empty($parsed['host'])) {
        return '';
    }

    $host = strtolower($parsed['host']);
    $path = isset($parsed['path']) ? trim($parsed['path'], '/') : '';
    $segments = $path !== '' ? explode('/', $path) : [];
    $identifier = '';

    if ($host === 'youtu.be' && $segments) {
        return sprintf(
            'https://www.youtube.com/embed/%s',
            rawurlencode($segments[0])
        );
    }

    if ($segments) {
        $first_segment = $segments[0];
        if (strpos($first_segment, '@') === 0) {
            $identifier = substr($first_segment, 1);
        } elseif ($first_segment === 'channel' && isset($segments[1])) {
            $identifier = $segments[1];
        } elseif (in_array($first_segment, ['c', 'user'], true) && isset($segments[1])) {
            $identifier = $segments[1];
        } elseif ($first_segment === 'shorts' && isset($segments[1])) {
            return sprintf(
                'https://www.youtube.com/embed/%s',
                rawurlencode($segments[1])
            );
        }
    }

    if ($identifier === '' && !empty($parsed['query'])) {
        parse_str($parsed['query'], $query_args);
        if (!empty($query_args['list'])) {
            return sprintf(
                'https://www.youtube.com/embed/videoseries?list=%s',
                rawurlencode($query_args['list'])
            );
        }

        if (!empty($query_args['v'])) {
            return sprintf(
                'https://www.youtube.com/embed/%s',
                rawurlencode($query_args['v'])
            );
        }
    }

    if ($identifier === '') {
        return '';
    }

    return sprintf(
        'https://www.youtube.com/embed?listType=user_uploads&list=%s',
        rawurlencode($identifier)
    );
}

/**
 * Retrieve the embed HTML for the footer YouTube section.
 *
 * @param string $channel_url Channel URL configured in the Customizer.
 * @return string
 */
function callamir_get_footer_youtube_embed($channel_url) {
    if (!$channel_url) {
        return '';
    }

    $embed_html = '';
    if (function_exists('wp_oembed_get')) {
        $embed_html = wp_oembed_get($channel_url) ?: '';
    }

    if ($embed_html !== '') {
        return $embed_html;
    }

    $embed_url = callamir_normalize_youtube_embed_url($channel_url);
    if ($embed_url === '' || !wp_http_validate_url($embed_url)) {
        return '';
    }

    return sprintf(
        '<iframe src="%1$s" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="%2$s"></iframe>',
        esc_url($embed_url),
        esc_attr__('YouTube player', 'callamir')
    );
}

/**
 * Gather the data needed to render the subscribe section.
 *
 * @return array
 */
function callamir_get_footer_youtube_data() {
    $defaults = callamir_get_footer_youtube_defaults();

    $channel_url = get_theme_mod('footer_youtube_channel_url', $defaults['channel_url']);
    if ($channel_url === '') {
        $channel_url = $defaults['channel_url'];
    }
    $subscribe_url = get_theme_mod('footer_youtube_subscribe_url', $defaults['subscribe_url']);
    if ($subscribe_url === '' && $channel_url) {
        $subscribe_url = trailingslashit($channel_url) . '?sub_confirmation=1';
    }
    if ($subscribe_url === '') {
        $subscribe_url = $defaults['subscribe_url'];
    }
    $button_color = get_theme_mod('footer_youtube_button_color', $defaults['button_color']);
    $button_hover_color = get_theme_mod('footer_youtube_button_hover_color', $defaults['button_hover_color']);
    $logo_theme = get_theme_mod('footer_youtube_logo_theme', $defaults['logo_theme']);
    $logo_custom_color = get_theme_mod('footer_youtube_logo_custom_color', $defaults['logo_custom_color']);

    $logo_color = 'var(--color-light)';
    if ($logo_theme === 'dark') {
        $logo_color = 'var(--color-dark)';
    } elseif ($logo_theme === 'custom' && $logo_custom_color) {
        $logo_color = $logo_custom_color;
    }

    $embed_html = callamir_get_footer_youtube_embed($channel_url);

    return [
        'channel_url' => $channel_url,
        'cta_text' => callamir_get_text('footer_youtube_cta_text', $defaults['cta_en'], $defaults['cta_fa']),
        'subscribe_url' => $subscribe_url,
        'button_color' => $button_color ?: $defaults['button_color'],
        'button_hover_color' => $button_hover_color ?: $defaults['button_hover_color'],
        'logo_color' => $logo_color,
        'embed_html' => $embed_html,
    ];
}

/**
 * Render the subscribe section markup.
 *
 * @param bool $echo Whether to echo the markup.
 * @return string
 */
function callamir_render_footer_youtube_section($echo = true) {
    $data = callamir_get_footer_youtube_data();
    $defaults = callamir_get_footer_youtube_defaults();
    $channel_url = esc_url($data['channel_url']);
    if ($channel_url === '') {
        $channel_url = esc_url($defaults['channel_url']);
    }
    $subscribe_url = esc_url($data['subscribe_url']);
    if ($subscribe_url === '') {
        $subscribe_url = esc_url($defaults['subscribe_url']);
    }

    $button_label = callamir_t(__('Subscribe', 'callamir'), __('اشتراک', 'callamir'));

    static $footer_youtube_inline_styles_printed = false;
    $markup = '';

    if (!$footer_youtube_inline_styles_printed) {
        $markup .= '<style>';
        $markup .= '.footer-youtube-subscribe{display:flex;flex-direction:column;align-items:stretch;gap:clamp(14px,2vw,18px);';
        $markup .= '--footer-yt-button-bg:var(--footer-yt-btn-bg);--footer-yt-button-hover:var(--footer-yt-btn-hover);';
        $markup .= '--footer-yt-logo-color:var(--footer-yt-logo,var(--color-light));--footer-yt-icon-color:var(--footer-yt-icon,var(--footer-yt-logo));}';
        $markup .= '.footer-youtube-inner{display:flex;flex-direction:column;gap:inherit;width:100%;}';
        $markup .= '.footer-youtube-details{text-align:center;}';
        $markup .= '.footer-youtube-logo i,.footer-youtube-embed__icon i{font-size:clamp(36px,5vw,44px);color:var(--footer-yt-icon-color);}';
        $markup .= '.footer-youtube-embed__message{margin-top:10px;font-size:0.95rem;color:rgba(var(--color-light-rgb),0.92);}';
        $markup .= '@media(max-width:640px){.footer-youtube-logo i,.footer-youtube-embed__icon i{font-size:32px;}}';
        $markup .= '@media(min-width:900px){.footer-youtube-subscribe{flex-direction:row;align-items:center;}';
        $markup .= '.footer-youtube-inner{flex-direction:row;align-items:center;gap:clamp(16px,2vw,22px);}';
        $markup .= '.footer-youtube-embed,.footer-youtube-details{flex:1;}';
        $markup .= '.footer-youtube-details{align-items:flex-start;text-align:start;}}';
        $markup .= '</style>';
        $footer_youtube_inline_styles_printed = true;
    }

    $markup .= sprintf(
        '<div class="footer-youtube-subscribe" role="complementary" aria-label="%s">',
        esc_attr__('YouTube subscribe prompt', 'callamir')
    );
    $markup .= '<div class="footer-youtube-inner">';
    $markup .= '<div class="footer-youtube-embed" aria-hidden="true">';
    if (!empty($data['embed_html'])) {
        $markup .= $data['embed_html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    } else {
        $markup .= '<div class="footer-youtube-embed__placeholder">';
        $markup .= '<span class="footer-youtube-embed__pulse"></span>';
        $markup .= '<span class="footer-youtube-embed__icon" aria-hidden="true">';
        $markup .= '<i class="fa-brands fa-youtube"></i>';
        $markup .= '</span>';
        $markup .= sprintf(
            '<p class="footer-youtube-embed__message">%s</p>',
            esc_html__('Subscribe on YouTube to watch our latest videos.', 'callamir')
        );
        $markup .= '</div>';
    }
    $markup .= '</div>';
    $markup .= '<div class="footer-youtube-details">';
    $markup .= '<div class="footer-youtube-logo" aria-hidden="true">';
    $markup .= '<i class="fa-brands fa-youtube"></i>';
    $markup .= '</div>';
    $markup .= sprintf(
        '<p class="footer-youtube-text">%s</p>',
        esc_html($data['cta_text'])
    );
    $markup .= sprintf(
        '<a class="footer-youtube-button" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%2$s</a>',
        $subscribe_url,
        esc_html($button_label),
        esc_attr(callamir_t(__('Subscribe to our YouTube channel (opens in a new tab)', 'callamir'), __('اشتراک در کانال یوتیوب ما (در زبانه جدید باز می‌شود)', 'callamir')))
    );
    $markup .= '</div>';
    $markup .= '</div>';
    $markup .= '</div>';

    if ($echo) {
        echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    return $markup;
}

/**
 * Render callback for the Customizer partial.
 *
 * @return string
 */
function callamir_render_footer_youtube_partial() {
    return callamir_render_footer_youtube_section(false);
}
