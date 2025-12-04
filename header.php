<?php
$current_lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';
$is_rtl_layout = function_exists('is_rtl') ? is_rtl() : ($current_lang === 'fa');

$html_dir = $is_rtl_layout ? 'rtl' : 'ltr';
$html_attributes = function_exists('get_language_attributes') ? get_language_attributes() : '';
$logo_text = function_exists('callamir_get_text') ? callamir_get_text(
    'logo_text',
    __('CallAmir', 'callamir'),
    'کال امیر'
) : __('CallAmir', 'callamir');
$leave_message_label = function_exists('callamir_get_text') ? callamir_get_text(
    'leave_message_text',
    __('Leave a message', 'callamir'),
    'پیام بگذارید'
) : __('Leave a message', 'callamir');

if ($html_attributes === '') {
    $language = function_exists('get_bloginfo') ? get_bloginfo('language') : 'en';
    $html_attributes = sprintf('lang="%s"', esc_attr(str_replace('_', '-', $language)));
}

$dir_fragment = sprintf('dir="%s"', esc_attr($html_dir));

if (strpos($html_attributes, 'dir=') === false) {
    $html_attributes = trim($html_attributes . ' ' . $dir_fragment);
} else {
    $replaced_attributes = preg_replace('/dir="[^"]*"/i', $dir_fragment, $html_attributes);
    $html_attributes = is_string($replaced_attributes) ? $replaced_attributes : trim($html_attributes . ' ' . $dir_fragment);
}

$kicker_text = function_exists('callamir_get_text') ? callamir_get_text(
    'header_kicker',
    __('Hand-crafted digital experiences', 'callamir'),
    'تجربه‌های دیجیتال دست‌ساز'
) : __('Hand-crafted digital experiences', 'callamir');

$header_title = function_exists('callamir_get_text') ? callamir_get_text(
    'header_title',
    __('A calmer header built for focus', 'callamir'),
    'سربرگی ساده برای تمرکز بیشتر'
) : __('A calmer header built for focus', 'callamir');

$header_subtitle = function_exists('callamir_get_text') ? callamir_get_text(
    'header_subtitle',
    __('Share your message without distracting menus or clutter.', 'callamir'),
    'پیام خود را بدون منوهای مزاحم و شلوغی بیان کنید.'
) : __('Share your message without distracting menus or clutter.', 'callamir');

$support_text = function_exists('callamir_get_text') ? callamir_get_text(
    'header_support_text',
    __('Support team responds in under an hour on business days.', 'callamir'),
    'تیم پشتیبانی در کمتر از یک ساعت پاسخ می‌دهد.'
) : __('Support team responds in under an hour on business days.', 'callamir');

$project_note = function_exists('callamir_get_text') ? callamir_get_text(
    'header_project_note',
    __('We deliver tailored WordPress builds with reliable care.', 'callamir'),
    'پروژه‌های وردپرسی سفارشی و قابل اعتماد ارائه می‌دهیم.'
) : __('We deliver tailored WordPress builds with reliable care.', 'callamir');

$contact_label = function_exists('callamir_get_text') ? callamir_get_text(
    'header_contact_label',
    __('Direct contact', 'callamir'),
    'ارتباط مستقیم'
) : __('Direct contact', 'callamir');

$secondary_cta_label = function_exists('callamir_get_text') ? callamir_get_text(
    'header_secondary_cta',
    __('See recent work', 'callamir'),
    'نمونه‌کارها را ببینید'
) : __('See recent work', 'callamir');

$cta_url = function_exists('callamir_localize_url') ? callamir_localize_url(get_theme_mod('leave_message_url', '#contact'), $current_lang) : get_theme_mod('leave_message_url', '#contact');
$secondary_cta_url = function_exists('callamir_localize_url') ? callamir_localize_url(get_theme_mod('secondary_cta_url', '#services'), $current_lang) : get_theme_mod('secondary_cta_url', '#services');
$contact_email = sanitize_email(get_theme_mod('header_contact_email', 'hello@callamir.com'));
?>
<!DOCTYPE html>
<html <?php echo $html_attributes; ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="site-header renewed-header">
        <canvas id="stars" class="stars-header" aria-hidden="true"></canvas>

        <div class="header-shell">
            <div class="header-grid">
                <div class="brand-panel">
                    <div class="logo-wrap">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                                <span class="logo-text"><?php echo esc_html($logo_text); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <p class="brand-kicker"><?php echo esc_html($kicker_text); ?></p>
                    <h1 class="brand-title"><?php echo esc_html($header_title); ?></h1>
                    <p class="brand-subtitle"><?php echo esc_html($header_subtitle); ?></p>

                    <div class="header-actions">
                        <a href="<?php echo esc_url($cta_url); ?>" class="header-button header-button--primary">
                            <i class="<?php echo esc_attr(get_theme_mod('leave_message_icon', 'fa-solid fa-envelope')); ?>" aria-hidden="true"></i>
                            <span><?php echo esc_html($leave_message_label); ?></span>
                        </a>
                        <a href="<?php echo esc_url($secondary_cta_url); ?>" class="header-button header-button--ghost">
                            <span><?php echo esc_html($secondary_cta_label); ?></span>
                        </a>
                    </div>
                </div>

                <div class="highlight-panel">
                    <div class="highlight-card">
                        <div class="highlight-icon" aria-hidden="true">★</div>
                        <div class="highlight-copy">
                            <p class="highlight-title"><?php echo esc_html($project_note); ?></p>
                            <p class="highlight-text"><?php echo esc_html($support_text); ?></p>
                        </div>
                    </div>

                    <div class="header-meta">
                        <div class="meta-item">
                            <span class="meta-label"><?php echo esc_html($contact_label); ?></span>
                            <a class="meta-value" href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><?php _e('Office hours', 'callamir'); ?></span>
                            <span class="meta-value"><?php _e('Sat – Thu, 9:00 to 18:00', 'callamir'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#content" class="skip-link screen-reader-text"><?php _e('Skip to content', 'callamir'); ?></a>
    </header>
