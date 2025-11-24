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
 * Gather the data needed to render the subscribe section.
 *
 * @return array
 */
function callamir_get_footer_youtube_data() {
    $defaults = callamir_get_footer_youtube_defaults();

    $channel_url = get_theme_mod('footer_youtube_channel_url', $defaults['channel_url']);
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

    return [
        'channel_url' => $channel_url,
        'cta_text' => callamir_get_text('footer_youtube_cta_text', $defaults['cta_en'], $defaults['cta_fa']),
        'button_color' => $button_color ?: $defaults['button_color'],
        'button_hover_color' => $button_hover_color ?: $defaults['button_hover_color'],
        'logo_color' => $logo_color,
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

    $style_rules = sprintf(
        '--footer-yt-button-bg:%1$s;--footer-yt-button-hover:%2$s;--footer-yt-logo-color:%3$s;',
        esc_attr($data['button_color']),
        esc_attr($data['button_hover_color']),
        esc_attr($data['logo_color'])
    );

    $button_label = callamir_t(__('Subscribe', 'callamir'), __('اشتراک', 'callamir'));

    $markup = sprintf(
        '<div class="footer-youtube-subscribe" style="%1$s" role="complementary" aria-label="%2$s">',
        esc_attr($style_rules),
        esc_attr__('YouTube subscribe prompt', 'callamir')
    );
    $markup .= '<div class="footer-youtube-logo" aria-hidden="true">';
    $markup .= '<svg viewBox="0 0 24 24" focusable="false" aria-hidden="true">';
    $markup .= '<path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.4 3.5 12 3.5 12 3.5s-7.4 0-9.4.6A3 3 0 0 0 .5 6.2 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c2 .6 9.4.6 9.4.6s7.4 0 9.4-.6a3 3 0 0 0 2.1-2.1 31.6 31.6 0 0 0 .5-5.8 31.6 31.6 0 0 0-.5-5.8Z" />';
    $markup .= '<path d="m9.75 15.02 6.25-3.02-6.25-3.02Z" />';
    $markup .= '</svg>';
    $markup .= '</div>';
    $markup .= sprintf(
        '<p class="footer-youtube-text">%s</p>',
        esc_html($data['cta_text'])
    );
    $markup .= sprintf(
        '<a class="footer-youtube-button" href="%1$s" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%2$s</a>',
        $channel_url,
        esc_html($button_label),
        esc_attr(callamir_t(__('Subscribe to our YouTube channel (opens in a new tab)', 'callamir'), __('اشتراک در کانال یوتیوب ما (در زبانه جدید باز می‌شود)', 'callamir')))
    );
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
