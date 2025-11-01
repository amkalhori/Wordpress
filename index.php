<?php
/**
 * index.php - CallAmir Theme (fully parametric & editable via Customizer with cosmic effects)
 */
get_header();
// Helper: Get current visitor language
$lang = callamir_get_visitor_lang();
// Utility: Return the theme_mod for chosen language
?>
<main id="site-main" role="main" aria-live="polite">
    <!-- Hero Section with Black Hole Effect -->
    <section id="hero" class="callamir-hero text-center relative overflow-hidden" aria-labelledby="hero-title">
        <canvas id="blackhole" class="cosmic-canvas absolute top-0 left-0 w-full h-full z-[1]" aria-hidden="true"></canvas>
        <div class="wrap flex flex-col items-center gap-6 p-4 relative z-10">
            <h1 id="hero-title" class="text-4xl font-bold text-white">
                <?php echo esc_html(callamir_mod('hero_title', $lang, __('Simplifying Tech for Seniors & Small Businesses', 'callamir'))); ?>
            </h1>
            <p class="hero-sub text-lg text-gray-200">
                <?php echo esc_html(callamir_mod('hero_text', $lang, __('Friendly, professional, and reasonably priced IT support in Toronto.', 'callamir'))); ?>
            </p>
            <p class="hero-short text-base md:text-lg text-gray-200 max-w-2xl">
                <?php echo esc_html(callamir_mod('hero_short_desc', $lang, __('We help non-technical users solve tech problems quickly and kindly.', 'callamir'))); ?>
            </p>
            <div class="hero-ctas flex flex-col md:flex-row gap-4" role="group" aria-label="<?php esc_attr_e('Hero actions', 'callamir'); ?>">
                <?php
                $phone = callamir_mod('contact_phone', $lang, '416-123-4567');
                $call_label = callamir_mod('hero_btn_call', $lang, __('Call Now', 'callamir'));
                $support_label = callamir_mod('hero_btn_support', $lang, __('Get IT Support', 'callamir'));
                ?>
                <a class="callamir-btn callamir-btn-call flex items-center gap-2 px-6 py-3 bg-red-600 text-white hover:bg-red-700 rounded-lg"
                   href="tel:<?php echo esc_attr(preg_replace('/\D+/', '', $phone)); ?>"
                   aria-label="<?php echo esc_attr($call_label); ?>">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    <?php echo esc_html($call_label); ?>
                </a>
                <a class="callamir-btn callamir-btn-support flex items-center gap-2 px-6 py-3 bg-yellow-400 text-gray-800 hover:bg-yellow-500 rounded-lg"
                   href="#contact"
                   aria-label="<?php echo esc_attr($support_label); ?>">
                    <i class="fa-solid fa-headset" aria-hidden="true"></i>
                    <?php echo esc_html($support_label); ?>
                </a>
            </div>
        </div>
    </section>
    <!-- Modern Services Section -->
    <section id="services" class="modern-services-section py-16 relative overflow-hidden" aria-labelledby="services-title">
        <canvas id="services-canvas" class="cosmic-canvas absolute top-0 left-0 w-full h-full z-[1]" aria-hidden="true"></canvas>
        <div class="services-container">
            <div class="services-header text-center mb-12">
                <h2 id="services-title" class="services-title">
                    <?php echo esc_html(callamir_mod('services_title', $lang, __('Our Services', 'callamir'))); ?>
                </h2>
                <p class="services-subtitle">
                    <?php echo esc_html(callamir_mod('services_subtitle', $lang, __('Professional IT solutions tailored to your needs', 'callamir'))); ?>
                </p>
            </div>
            
            <!-- Services Carousel Container -->
            <div class="services-carousel-container">
                <!-- Navigation Arrows -->
                <button class="carousel-nav carousel-nav-prev" aria-label="<?php _e('Previous services', 'callamir'); ?>">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="carousel-nav carousel-nav-next" aria-label="<?php _e('Next services', 'callamir'); ?>">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                
                <!-- Services Carousel -->
                <div class="services-carousel">
                    <div class="services-track">
                        <?php
                        $count = get_theme_mod('callamir_services_count', 3);
                        for ($i = 1; $i <= $count; $i++) :
                            $icon = get_theme_mod("callamir_service_icon_{$i}", 'fa-solid fa-computer');
                            $title = callamir_mod("service_title_{$i}", $lang, "Service $i");
                            $desc = callamir_mod("service_desc_{$i}", $lang, "Description for service $i");
                            $full_desc = callamir_mod("service_full_desc_{$i}", $lang, "Detailed description for service $i");
                            $image = get_theme_mod("callamir_service_image_{$i}", '');
                            $price = callamir_mod("service_price_{$i}", $lang, '');
                            $contact_form = get_theme_mod("callamir_service_contact_form_{$i}", '');
                        ?>
                            <div class="service-card" data-service="<?php echo $i; ?>">
                        <div class="service-card-inner">
                            <div class="service-icon-wrapper">
                                <i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
                            </div>
                            <div class="service-content">
                                <h3 class="service-title"><?php echo esc_html($title); ?></h3>
                                <p class="service-description"><?php echo esc_html($desc); ?></p>
                                <?php if ($price) : ?>
                                    <div class="service-price"><?php echo esc_html($price); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="service-actions">
                                <button class="read-more-btn" data-service="<?php echo $i; ?>" aria-label="<?php echo esc_attr(sprintf(__('Read more about %s', 'callamir'), $title)); ?>">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    <span><?php echo esc_html(callamir_mod('read_more_text', $lang, __('Read More', 'callamir'))); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                        <?php endfor; ?>
                    </div>
                </div>
                
                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    <!-- Indicators will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Community Questions Section -->
    <section id="community" class="callamir-section py-10 relative overflow-hidden min-h-[300px]" aria-labelledby="community-title">
        <div class="services-container">
            <h2 id="community-title" class="services-title">
                <?php echo esc_html(callamir_mod('community_title', $lang, __('Community Questions', 'callamir'))); ?>
            </h2>
            <p class="services-subtitle mb-8">
                <?php echo esc_html(callamir_mod('community_subtitle', $lang, __('Common questions and helpful answers from our community', 'callamir'))); ?>
            </p>

            <!-- Community Question Form -->
            <div class="community-question-form mb-12">
                <h3 class="text-2xl font-bold text-white mb-4">
                    <?php echo esc_html(callamir_mod('community_question_form_title', $lang, __('Ask a Question', 'callamir'))); ?>
                </h3>
                <p class="text-gray-200 mb-6">
                    <?php echo esc_html(callamir_mod('community_question_form_desc', $lang, __('Have a question? Ask our community and get helpful answers.', 'callamir'))); ?>
                </p>
                <div class="max-w-2xl mx-auto">
                    <?php
                    $community_form_shortcode = get_theme_mod('callamir_community_question_form', '[contact-form-7 id="124" title="Community Question Form"]');
                    $community_form_output = do_shortcode($community_form_shortcode);
                    if (stripos($community_form_output, 'contact form not found') !== false) {
                        echo '<div class="callamir-contact-form-warning">' . esc_html__('Please update the community form shortcode in the Customizer.', 'callamir') . '</div>';
                    } else {
                        echo $community_form_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    ?>
                </div>
            </div>

            <div class="faq-list" role="list">
                <?php
                for ($i = 1; $i <= 5; $i++) :
                    $q = callamir_mod("faq_q_{$i}", $lang, $i === 1 ? __('How do I request support?', 'callamir') : '');
                    $a = callamir_mod("faq_a_{$i}", $lang, $i === 1 ? __('Use the Get IT Support button above or WhatsApp/Telegram in Contact.', 'callamir') : '');
                    if (!$q) continue;
                ?>
                <div class="faq-item" role="listitem">
                    <button class="faq-question" aria-expanded="false">
                        <span class="faq-q-text"><?php echo esc_html($q); ?></span>
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <div class="faq-answer" hidden>
                        <p><?php echo esc_html($a); ?></p>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Service Modal -->
    <div id="service-modal" class="service-modal" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <div class="modal-content">
                <button class="modal-close" aria-label="<?php _e('Close modal', 'callamir'); ?>">
                    <i class="fa-solid fa-times" aria-hidden="true"></i>
                </button>
                <div class="modal-body">
                    <div class="modal-image">
                        <img id="modal-service-image" src="" alt="" />
                    </div>
                    <div class="modal-info">
                        <h3 id="modal-service-title" class="modal-title"></h3>
                        <div id="modal-service-price" class="modal-price"></div>
                        <p id="modal-service-description" class="modal-description"></p>
                        <div id="modal-service-form" class="modal-form"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Section -->
    <section id="contact" class="callamir-section py-10 relative overflow-hidden min-h-[400px]" aria-labelledby="contact-title">
        <div class="wrap flex flex-col items-center gap-6 relative z-10">
            <h2 id="contact-title" class="text-3xl font-bold mb-6 text-white">
                <?php echo esc_html(callamir_mod('contact_title', $lang, __('Contact Us', 'callamir'))); ?>
            </h2>
            <div class="contact-links flex flex-wrap justify-center gap-4 mb-6">
                <?php
                $wh = get_theme_mod('whatsapp_url', '#');
                $tg = get_theme_mod('telegram_url', '#');
                if ($wh && $wh !== '#') {
                    echo '<a class="callamir-btn contact-link whatsapp flex items-center gap-2 px-4 py-2 bg-green-500 text-white hover:bg-green-600 rounded-lg" href="' . esc_url($wh) . '" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp"></i> ' . esc_html(callamir_mod('whatsapp_text', $lang, __('WhatsApp', 'callamir'))) . '</a>';
                }
                if ($tg && $tg !== '#') {
                    echo '<a class="callamir-btn contact-link telegram flex items-center gap-2 px-4 py-2 bg-blue-500 text-white hover:bg-blue-600 rounded-lg" href="' . esc_url($tg) . '" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-telegram"></i> ' . esc_html(callamir_mod('telegram_text', $lang, __('Telegram', 'callamir'))) . '</a>';
                }
                ?>
            </div>
            <div class="w-full md:w-2/3">
                <?php
                $contact_form_shortcode = get_theme_mod('callamir_contact_form', '[contact-form-7 id="123" title="Contact form 1"]');
                $contact_form_output = do_shortcode($contact_form_shortcode);
                if (stripos($contact_form_output, 'contact form not found') !== false) {
                    echo '<div class="callamir-contact-form-warning">' . esc_html__('Please update the Contact form shortcode in the Customizer.', 'callamir') . '</div>';
                } else {
                    echo $contact_form_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                ?>
            </div>
        </div>
    </section>
    <!-- Blog Section -->
    <section id="blog" class="callamir-section py-10 relative overflow-hidden min-h-[400px]" aria-labelledby="blog-title">
        <div class="wrap flex flex-col items-center gap-6 relative z-10">
            <h2 id="blog-title" class="text-3xl font-bold mb-6 text-white">
                <?php echo esc_html(callamir_mod('blog_title', $lang, __('Tips & Daily Quotes', 'callamir'))); ?>
            </h2>
            <p class="text-center mb-6 text-gray-200"><?php echo esc_html(callamir_mod('blog_desc', $lang, __('Latest insights, IT tips, and motivational posts.', 'callamir'))); ?></p>
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $blog_query = callamir_get_blog_items(3, $paged);
            if ($blog_query->have_posts()) {
                echo '<div class="callamir-service-grid grid grid-cols-1 md:grid-cols-3 gap-6">';
                while ($blog_query->have_posts()) {
                    $blog_query->the_post(); ?>
                    <article class="callamir-card p-6 bg-white/10 backdrop-blur-md rounded-lg shadow-lg text-center transition duration-300 hover:shadow-xl">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumb mb-4"><?php the_post_thumbnail('medium'); ?></div>
                        <?php endif; ?>
                        <h3 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="text-white hover:text-yellow-400"><?php the_title(); ?></a></h3>
                        <p class="text-gray-200"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        <p><a href="<?php the_permalink(); ?>" class="callamir-link text-yellow-400 hover:text-yellow-500"><?php esc_html_e('Read more', 'callamir'); ?></a></p>
                    </article>
                <?php }
                echo '</div>';
                echo '<div class="blog-pagination text-center mt-6">';
                echo paginate_links(array(
                    'total' => $blog_query->max_num_pages,
                    'current' => max(1, $paged),
                    'prev_text' => __('«', 'callamir'),
                    'next_text' => __('»', 'callamir'),
                ));
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p class="text-center text-gray-200">' . esc_html__('No posts yet. Add some posts!', 'callamir') . '</p>';
            }
            ?>
        </div>
    </section>
    <!-- Community Section -->
    <section id="community" class="callamir-section py-10 relative overflow-hidden min-h-[400px]" aria-labelledby="community-title">
        <div class="wrap flex flex-col items-center gap-6 relative z-10">
            <h2 id="community-title" class="text-3xl font-bold mb-6 text-white">
                <?php echo esc_html(callamir_mod('community_title', $lang, __('Community Questions', 'callamir'))); ?>
            </h2>
            <?php
            $community_query = new WP_Query(array(
                'post_type' => 'community_questions',
                'posts_per_page' => 5,
                'post_status' => 'publish',
            ));
            if ($community_query->have_posts()) {
                echo '<div class="callamir-service-grid grid grid-cols-1 md:grid-cols-3 gap-6">';
                while ($community_query->have_posts()) {
                    $community_query->the_post(); ?>
                    <article class="callamir-card p-6 bg-white/10 backdrop-blur-md rounded-lg shadow-lg text-center transition duration-300 hover:shadow-xl">
                        <h3 class="text-xl font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="text-white hover:text-yellow-400"><?php the_title(); ?></a></h3>
                        <p class="text-gray-200"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        <p><a href="<?php the_permalink(); ?>" class="callamir-link text-yellow-400 hover:text-yellow-500"><?php esc_html_e('Read more', 'callamir'); ?></a></p>
                    </article>
                <?php }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p class="text-center text-gray-200">' . esc_html__('No community questions yet.', 'callamir') . '</p>';
            }
            ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>