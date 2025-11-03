<?php
$current_lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';
$is_rtl_layout = function_exists('is_rtl') ? is_rtl() : ($current_lang === 'fa');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> <?php echo $is_rtl_layout ? 'dir="rtl"' : 'dir="ltr"'; ?>>
<header class="site-header modern-header">
    <canvas id="stars" class="stars-header" aria-hidden="true"></canvas>
    
    <!-- Modern Navigation Container -->
    <div class="nav-container">
        <div class="nav-wrapper">
            <!-- Logo Section -->
            <div class="nav-logo">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                        <span class="logo-text"><?php echo esc_html(function_exists('callamir_mod') ? callamir_mod('logo_text', $current_lang, __('CallAmir', 'callamir')) : __('CallAmir', 'callamir')); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <?php
            $desktop_menu_class = 'nav-menu-desktop' . ($is_rtl_layout ? ' nav-menu-desktop--rtl' : '');
            ?>
            <nav class="nav-desktop" role="navigation" aria-label="<?php _e('Main navigation', 'callamir'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'one_page_menu',
                    'menu_class' => $desktop_menu_class,
                    'container' => false,
                    'fallback_cb' => 'callamir_fallback_menu',
                    'walker' => class_exists('Callamir_Walker_Nav_Menu_With_FA') ? new Callamir_Walker_Nav_Menu_With_FA() : '',
                ));
                ?>
            </nav>

            <!-- Language Switcher -->
            <div class="nav-lang-switcher">
                <div class="lang-flags">
                    <?php
                    $flag_text = function_exists('callamir_mod') ? callamir_mod('flag_text', $current_lang, __('Languages:', 'callamir')) : __('Languages:', 'callamir');
                    $english_url = function_exists('callamir_localize_url') ? callamir_localize_url(home_url('/'), 'en') : add_query_arg('lang', 'en', home_url('/'));
                    $persian_url = function_exists('callamir_localize_url') ? callamir_localize_url(home_url('/'), 'fa') : add_query_arg('lang', 'fa', home_url('/'));
                    ?>
                    <span class="flag-text"><?php echo esc_html($flag_text); ?></span>
                    <?php if (get_theme_mod('enable_english', true)) : ?>
                        <a href="<?php echo esc_url($english_url); ?>" class="lang-flag-link<?php echo $current_lang === 'en' ? ' is-active' : ''; ?>" data-lang="en" aria-label="<?php _e('Switch to English', 'callamir'); ?>">
                            <img src="<?php echo esc_url(get_theme_mod('english_flag_image', get_template_directory_uri() . '/images/uk-flag.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('english_flag_alt', __('English', 'callamir'))); ?>" class="flag-img">
                        </a>
                    <?php endif; ?>
                    <?php if (get_theme_mod('enable_persian', true)) : ?>
                        <a href="<?php echo esc_url($persian_url); ?>" class="lang-flag-link<?php echo $current_lang === 'fa' ? ' is-active' : ''; ?>" data-lang="fa" aria-label="<?php _e('Switch to Persian', 'callamir'); ?>">
                            <img src="<?php echo esc_url(get_theme_mod('persian_flag_image', get_template_directory_uri() . '/images/iran-flag.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('persian_flag_alt', __('Persian', 'callamir'))); ?>" class="flag-img">
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="nav-cta">
                <?php $cta_url = function_exists('callamir_localize_url') ? callamir_localize_url(get_theme_mod('leave_message_url', '#contact'), $current_lang) : get_theme_mod('leave_message_url', '#contact'); ?>
                <a href="<?php echo esc_url($cta_url); ?>" class="cta-button">
                    <i class="<?php echo esc_attr(get_theme_mod('leave_message_icon', 'fa-solid fa-envelope')); ?>" aria-hidden="true"></i>
                    <span><?php echo esc_html(function_exists('callamir_mod') ? callamir_mod('leave_message_text', $current_lang, __('Leave a message', 'callamir')) : __('Leave a message', 'callamir')); ?></span>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-menu-toggle" aria-label="<?php _e('Toggle mobile menu', 'callamir'); ?>" aria-controls="mobile-menu" aria-expanded="false">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <?php
        $mobile_menu_class = 'nav-menu-mobile' . ($is_rtl_layout ? ' nav-menu-mobile--rtl' : '');
        ?>
        <nav class="nav-mobile" id="mobile-menu" role="navigation" aria-label="<?php _e('Mobile navigation', 'callamir'); ?>">
            <div class="mobile-menu-content">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'one_page_menu',
                    'menu_class' => $mobile_menu_class,
                    'container' => false,
                    'fallback_cb' => 'callamir_fallback_menu',
                    'walker' => class_exists('Callamir_Walker_Nav_Menu_With_FA') ? new Callamir_Walker_Nav_Menu_With_FA() : '',
                ));
                ?>
                
                <!-- Mobile CTA -->
                <div class="mobile-cta">
                    <?php $mobile_cta_url = function_exists('callamir_localize_url') ? callamir_localize_url(get_theme_mod('leave_message_url', '#contact'), $current_lang) : get_theme_mod('leave_message_url', '#contact'); ?>
                    <a href="<?php echo esc_url($mobile_cta_url); ?>" class="mobile-cta-button">
                        <i class="<?php echo esc_attr(get_theme_mod('leave_message_icon', 'fa-solid fa-envelope')); ?>" aria-hidden="true"></i>
                        <span><?php echo esc_html(function_exists('callamir_mod') ? callamir_mod('leave_message_text', $current_lang, __('Leave a message', 'callamir')) : __('Leave a message', 'callamir')); ?></span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    
    <a href="#content" class="skip-link screen-reader-text"><?php _e('Skip to content', 'callamir'); ?></a>
</header>