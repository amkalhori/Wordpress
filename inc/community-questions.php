<?php
/**
 * Community Questions custom post type and meta boxes.
 */

if (!defined('ABSPATH')) {
    exit;
}

function callamir_register_community_questions() {
    $labels = [
        'name' => __('Community Questions', 'callamir'),
        'singular_name' => __('Community Question', 'callamir'),
        'menu_name' => __('Community Questions', 'callamir'),
        'add_new' => __('Add New Question', 'callamir'),
        'add_new_item' => __('Add New Community Question', 'callamir'),
        'edit_item' => __('Edit Community Question', 'callamir'),
        'new_item' => __('New Community Question', 'callamir'),
        'view_item' => __('View Community Question', 'callamir'),
        'search_items' => __('Search Community Questions', 'callamir'),
        'not_found' => __('No community questions found', 'callamir'),
        'not_found_in_trash' => __('No community questions found in trash', 'callamir'),
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'community-question'],
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-format-chat',
        'supports' => ['title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'],
        'show_in_rest' => true,
    ];

    register_post_type('community_questions', $args);
}
add_action('init', 'callamir_register_community_questions');

function callamir_add_community_questions_meta_boxes() {
    add_meta_box(
        'community_question_details',
        __('Question Details', 'callamir'),
        'callamir_community_question_details_callback',
        'community_questions',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'callamir_add_community_questions_meta_boxes');

function callamir_community_question_details_callback($post) {
    wp_nonce_field('callamir_community_question_meta', 'callamir_community_question_meta_nonce');
    
    $question_status = get_post_meta($post->ID, '_question_status', true);
    $question_author_name = get_post_meta($post->ID, '_question_author_name', true);
    $question_author_email = get_post_meta($post->ID, '_question_author_email', true);
    $question_answer = get_post_meta($post->ID, '_question_answer', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="question_status"><?php _e('Question Status', 'callamir'); ?></label>
            </th>
            <td>
                <select name="question_status" id="question_status">
                    <option value="pending" <?php selected($question_status, 'pending'); ?>><?php _e('Pending', 'callamir'); ?></option>
                    <option value="answered" <?php selected($question_status, 'answered'); ?>><?php _e('Answered', 'callamir'); ?></option>
                    <option value="published" <?php selected($question_status, 'published'); ?>><?php _e('Published', 'callamir'); ?></option>
                </select>
                <p class="description"><?php _e('Set the status of this community question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_author_name"><?php _e('Author Name', 'callamir'); ?></label>
            </th>
            <td>
                <input type="text" name="question_author_name" id="question_author_name" value="<?php echo esc_attr($question_author_name); ?>" class="regular-text" />
                <p class="description"><?php _e('Name of the person who asked the question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_author_email"><?php _e('Author Email', 'callamir'); ?></label>
            </th>
            <td>
                <input type="email" name="question_author_email" id="question_author_email" value="<?php echo esc_attr($question_author_email); ?>" class="regular-text" />
                <p class="description"><?php _e('Email of the person who asked the question.', 'callamir'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="question_answer"><?php _e('Answer', 'callamir'); ?></label>
            </th>
            <td>
                <?php
                wp_editor($question_answer, 'question_answer', [
                    'textarea_name' => 'question_answer',
                    'textarea_rows' => 10,
                    'media_buttons' => true,
                    'teeny' => false,
                ]);
                ?>
                <p class="description"><?php _e('Provide the answer to this question.', 'callamir'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

function callamir_save_community_question_meta($post_id) {
    if (!isset($_POST['callamir_community_question_meta_nonce']) || 
        !wp_verify_nonce($_POST['callamir_community_question_meta_nonce'], 'callamir_community_question_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['question_status'])) {
        update_post_meta($post_id, '_question_status', sanitize_text_field($_POST['question_status']));
    }

    if (isset($_POST['question_author_name'])) {
        update_post_meta($post_id, '_question_author_name', sanitize_text_field($_POST['question_author_name']));
    }

    if (isset($_POST['question_author_email'])) {
        update_post_meta($post_id, '_question_author_email', sanitize_email($_POST['question_author_email']));
    }

    if (isset($_POST['question_answer'])) {
        update_post_meta($post_id, '_question_answer', wp_kses_post($_POST['question_answer']));
    }
}
add_action('save_post', 'callamir_save_community_question_meta');
