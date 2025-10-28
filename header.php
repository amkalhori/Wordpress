<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <style>
        .site-header {
            position: relative;
            min-height: 80px;
        }
        .stars-header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body <?php body_class(); ?> <?php echo (get_theme_mod('site_language', 'en') === 'fa') ? 'dir="rtl"' : 'dir="ltr"'; ?>>
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
                        <span class="logo-text"><?php echo esc_html(get_theme_mod('logo_text_' . get_theme_mod('site_language', 'en'), __('CallAmir', 'callamir'))); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <nav class="nav-desktop" role="navigation" aria-label="<?php _e('Main navigation', 'callamir'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'one_page_menu',
                    'menu_class' => 'nav-menu-desktop',
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
                    $current_lang = callamir_get_visitor_lang();
                    $flag_text = ($current_lang === 'fa') ? get_theme_mod('flag_text_fa', __('زبان‌ها:', 'callamir')) : get_theme_mod('flag_text_en', __('Languages:', 'callamir'));
                    ?>
                    <span class="flag-text"><?php echo esc_html($flag_text); ?></span>
                    <?php if (get_theme_mod('enable_english', true)) : ?>
                        <a href="#" class="lang-flag-link" data-lang="en" aria-label="<?php _e('Switch to English', 'callamir'); ?>">
                            <img src="<?php echo esc_url(get_theme_mod('english_flag_image', get_template_directory_uri() . '/images/uk-flag.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('english_flag_alt', __('English', 'callamir'))); ?>" class="flag-img">
                        </a>
                    <?php endif; ?>
                    <?php if (get_theme_mod('enable_persian', true)) : ?>
                        <a href="#" class="lang-flag-link" data-lang="fa" aria-label="<?php _e('Switch to Persian', 'callamir'); ?>">
                            <img src="<?php echo esc_url(get_theme_mod('persian_flag_image', get_template_directory_uri() . '/images/iran-flag.png')); ?>" alt="<?php echo esc_attr(get_theme_mod('persian_flag_alt', __('Persian', 'callamir'))); ?>" class="flag-img">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Language Switcher Fallback Script -->
            <script>
                console.log('=== HEADER DEBUG ===');
                console.log('Current language:', '<?php echo $current_lang; ?>');
                console.log('Enable English:', <?php echo get_theme_mod('enable_english', true) ? 'true' : 'false'; ?>);
                console.log('Enable Persian:', <?php echo get_theme_mod('enable_persian', true) ? 'true' : 'false'; ?>);
                console.log('Language switcher HTML generated');
                
                // Simple language switching fallback
                document.addEventListener('DOMContentLoaded', function() {
                    const langLinks = document.querySelectorAll('.lang-flag-link, a[data-lang]');
                    console.log('Fallback script found language links:', langLinks.length);
                    
                    // Add visual indicator for testing
                    if (langLinks.length > 0) {
                        console.log('✅ Language links found and ready');
                        langLinks.forEach((link, index) => {
                            console.log(`Link ${index + 1}:`, link, 'data-lang:', link.getAttribute('data-lang'));
                        });
                    } else {
                        console.log('❌ No language links found');
                    }
                    
                    langLinks.forEach(link => {
                        // Remove any existing listeners to prevent duplicates
                        link.removeEventListener('click', handleLanguageClick);
                        
                        // Add new listener
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            const lang = this.getAttribute('data-lang');
                            console.log('🔄 Language switch clicked:', lang);
                            
                            if (!lang) {
                                console.error('❌ No data-lang attribute found');
                                return;
                            }
                            
                            // Show loading state
                            this.style.opacity = '0.5';
                            this.style.pointerEvents = 'none';
                            
                            // Set cookie
                            document.cookie = 'language=' + lang + '; path=/; max-age=604800';
                            console.log('🍪 Cookie set for language:', lang);
                            
                            // Reload page
                            console.log('🔄 Reloading page...');
                            window.location.reload();
                        });
                    });
                });
                
                // Function to handle language clicks
                function handleLanguageClick(e) {
                    e.preventDefault();
                    const lang = this.getAttribute('data-lang');
                    console.log('🔄 Language switch clicked:', lang);
                    
                    if (!lang) {
                        console.error('❌ No data-lang attribute found');
                        return;
                    }
                    
                    // Set cookie and reload
                    document.cookie = 'language=' + lang + '; path=/; max-age=604800';
                    window.location.reload();
                }
            </script>

            <!-- CTA Button -->
            <div class="nav-cta">
                <a href="<?php echo esc_url(get_theme_mod('leave_message_url', '#contact')); ?>" class="cta-button">
                    <i class="<?php echo esc_attr(get_theme_mod('leave_message_icon', 'fa-solid fa-envelope')); ?>" aria-hidden="true"></i>
                    <span><?php echo esc_html(get_theme_mod('leave_message_text_' . get_theme_mod('site_language', 'en'), __('Leave a message', 'callamir'))); ?></span>
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
        <nav class="nav-mobile" id="mobile-menu" role="navigation" aria-label="<?php _e('Mobile navigation', 'callamir'); ?>">
            <div class="mobile-menu-content">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'one_page_menu',
                    'menu_class' => 'nav-menu-mobile',
                    'container' => false,
                    'fallback_cb' => 'callamir_fallback_menu',
                    'walker' => class_exists('Callamir_Walker_Nav_Menu_With_FA') ? new Callamir_Walker_Nav_Menu_With_FA() : '',
                ));
                ?>
                
                <!-- Mobile CTA -->
                <div class="mobile-cta">
                    <a href="<?php echo esc_url(get_theme_mod('leave_message_url', '#contact')); ?>" class="mobile-cta-button">
                        <i class="<?php echo esc_attr(get_theme_mod('leave_message_icon', 'fa-solid fa-envelope')); ?>" aria-hidden="true"></i>
                        <span><?php echo esc_html(get_theme_mod('leave_message_text_' . get_theme_mod('site_language', 'en'), __('Leave a message', 'callamir'))); ?></span>
                    </a>
                </div>
            </div>
        </nav>
    </div>
    
    <a href="#content" class="skip-link screen-reader-text"><?php _e('Skip to content', 'callamir'); ?></a>
</header>