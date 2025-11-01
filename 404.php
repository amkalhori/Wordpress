<?php
/**
 * 404 Template for Callamir Theme
 */
get_header();
$lang = isset($_GET['lang']) && $_GET['lang'] === 'fa' ? 'fa' : 'en';
?>
<main>
    <section class="error-404">
        <h1><?php echo esc_html($lang === 'fa' ? __('صفحه یافت نشد', 'callamir') : __('Page Not Found', 'callamir')); ?></h1>
        <p><?php echo esc_html($lang === 'fa' ? __('متأسفیم، صفحه‌ای که به دنبال آن هستید وجود ندارد.', 'callamir') : __('Sorry, the page you’re looking for doesn’t exist.', 'callamir')); ?></p>
        <a href="<?php echo home_url(); ?>" class="hero-button"><?php echo esc_html($lang === 'fa' ? __('بازگشت به خانه', 'callamir') : __('Back to Home', 'callamir')); ?></a>
    </section>
</main>
<?php get_footer(); ?>