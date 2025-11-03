<?php
/**
 * CallAmir Theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

$callamir_includes = array(
    'inc/language.php',
    'inc/navigation.php',
    'inc/sanitization.php',
    'inc/services.php',
    'inc/community-questions.php',
    'inc/setup.php',
    'inc/assets.php',
    'inc/dynamic-css.php',
    'inc/customizer.php',
    'inc/blog.php',
    'inc/integrations.php',
    'inc/performance.php',
    'inc/admin-tools.php',
    'inc/theme-colors.php',
);

foreach ($callamir_includes as $relative_path) {
    $path = get_template_directory() . '/' . $relative_path;

    if (file_exists($path)) {
        require_once $path;
    }
}
