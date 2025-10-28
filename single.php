<?php
/**
 * Single Post Template for CallAmir Theme
 */
get_header();
$lang = callamir_get_visitor_lang();
?>
<main class="callamir-section relative overflow-hidden">
    <div class="wrap flex flex-col items-center gap-6 p-4 text-white">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <h1 class="text-3xl font-bold"><?php the_title(); ?></h1>
            <div class="text-gray-200"><?php the_content(); ?></div>
            <?php if (get_post_type() === 'community_questions') : ?>
                <?php
                $answer = get_post_meta(get_the_ID(), 'answer', true);
                $submitter = get_post_meta(get_the_ID(), 'submitter_name', true);
                ?>
                <p><strong><?php echo $lang === 'fa' ? 'ارسال‌کننده: ' : 'Submitter: '; ?></strong><?php echo esc_html($submitter); ?></p>
                <?php if ($answer) : ?>
                    <p><strong><?php echo $lang === 'fa' ? 'پاسخ: ' : 'Answer: '; ?></strong><?php echo esc_html($answer); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        <?php endwhile; else : ?>
            <p class="text-gray-200"><?php echo $lang === 'fa' ? 'پستی یافت نشد.' : 'No post found.'; ?></p>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>