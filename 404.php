<?php
/**
 * 404 Template for Callamir Theme
 */
get_header();
$lang = isset($_GET['lang']) && $_GET['lang'] === 'fa' ? 'fa' : 'en';
?>
<main>
    <section class="error-404">
        <h1><?php echo $lang === 'fa' ? 'صفحه یافت نشد' : 'Page Not Found'; ?></h1>
        <p><?php echo $lang === 'fa' ? 'متأسفیم، صفحه‌ای که به دنبال آن هستید وجود ندارد.' : 'Sorry, the page you’re looking for doesn’t exist.'; ?></p>
        <a href="<?php echo home_url(); ?>" class="hero-button"><?php echo $lang === 'fa' ? 'بازگشت به خانه' : 'Back to Home'; ?></a>
    </section>
</main>
<?php get_footer(); ?>