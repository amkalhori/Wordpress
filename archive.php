<?php
/**
 * Archive Template for Callamir Theme
 */
get_header();
$lang = function_exists('callamir_get_visitor_lang') ? callamir_get_visitor_lang() : 'en';
?>
<main>
    <section id="blog" class="blog">
        <h2 id="blog-title">
            <?php
            if (is_category()) {
                echo $lang === 'fa' ? 'دسته‌بندی: ' : 'Category: ';
                single_cat_title();
            } elseif (is_tag()) {
                echo $lang === 'fa' ? 'برچسب: ' : 'Tag: ';
                single_tag_title();
            } elseif (is_author()) {
                echo $lang === 'fa' ? 'نویسنده: ' : 'Author: ';
                the_author();
            } elseif (is_day()) {
                echo $lang === 'fa' ? 'بایگانی روزانه: ' : 'Daily Archives: ';
                echo get_the_date();
            } elseif (is_month()) {
                echo $lang === 'fa' ? 'بایگانی ماهانه: ' : 'Monthly Archives: ';
                echo get_the_date('F Y');
            } elseif (is_year()) {
                echo $lang === 'fa' ? 'بایگانی سالانه: ' : 'Yearly Archives: ';
                echo get_the_date('Y');
            } else {
                echo $lang === 'fa' ? 'بایگانی' : 'Archives';
            }
            ?>
        </h2>
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article>
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
            <div class="pagination">
                <?php echo paginate_links(); ?>
            </div>
        <?php else : ?>
            <p><?php echo $lang === 'fa' ? 'هنوز پستی وجود ندارد.' : 'No posts yet.'; ?></p>
        <?php endif; ?>
    </section>
</main>
<?php get_footer(); ?>
