<?php
/**
 * Footer Template for CallAmir Theme
 */
$lang = callamir_get_visitor_lang();
$footer_min_height = esc_attr(get_theme_mod('footer_min_height', '100px'));
?>
<footer class="site-footer relative overflow-hidden" style="min-height: <?php echo $footer_min_height; ?>;">
    <canvas id="footer-stars" class="stars-footer absolute top-0 left-0 w-full h-full z-0" aria-hidden="true"></canvas>
    <div class="wrap flex flex-col items-center gap-4 p-4 relative z-10 text-white">
        <div class="copyright text-center">
            <?php echo esc_html(callamir_mod('copyright_text', $lang, __('© ' . date('Y') . ' CallAmir. All rights reserved.', 'callamir'))); ?>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>