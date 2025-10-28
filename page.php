<?php
/**
 * Page Template for CallAmir Theme
 */
get_header();
$lang = callamir_get_visitor_lang();
?>
<main class="callamir-section relative overflow-hidden">
    <div class="wrap flex flex-col items-center gap-6 p-4">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1 class="text-3xl font-bold text-white"><?php the_title(); ?></h1>
            <div class="text-gray-200"><?php the_content(); ?></div>
        <?php endwhile; else : ?>
            <p class="text-gray-200"><?php echo $lang === 'fa' ? 'محتوایی یافت نشد.' : 'No content found.'; ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>