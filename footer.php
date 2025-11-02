<?php
/**
 * Footer Template for CallAmir Theme
 */
$lang = callamir_get_visitor_lang();
$footer_min_height = esc_attr(get_theme_mod('footer_min_height', '100px'));
$current_year = date_i18n('Y');
$default_copyright = array(
    'en' => __('CallAmir. All rights reserved © {year}', 'callamir'),
    'fa' => __('کال امیر. کلیه حقوق محفوظ است © {year}', 'callamir'),
);
$raw_copyright = callamir_mod('copyright_text', $lang, $default_copyright[$lang] ?? $default_copyright['en']);
$rendered_copyright = str_replace(
    array('{year}', '{YEAR}', '%year%', '%YEAR%'),
    $current_year,
    $raw_copyright
);
$alignment_class = ($lang === 'fa') ? 'footer-rtl text-right' : 'footer-ltr text-left md:text-center';
$direction = ($lang === 'fa') ? 'rtl' : 'ltr';
?>
<footer class="site-footer relative overflow-hidden <?php echo esc_attr($alignment_class); ?>" style="min-height: <?php echo esc_attr($footer_min_height); ?>;" dir="<?php echo esc_attr($direction); ?>">
    <canvas id="footer-stars" class="stars-footer absolute top-0 left-0 w-full h-full z-0" aria-hidden="true"></canvas>
    <div class="wrap flex flex-col items-center gap-4 p-4 relative z-10 text-white">
        <div class="copyright" dir="<?php echo esc_attr($direction); ?>">
            <?php echo esc_html($rendered_copyright); ?>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>