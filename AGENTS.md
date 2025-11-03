# Repository Guidelines

- Follow WordPress coding standards with 4-space indentation for PHP and CSS.
- Place new theme functionality inside the `inc/` directory. Group related hooks and helpers together; avoid adding large blocks directly to `functions.php`.
- When modifying `functions.php`, limit changes to bootstrapping (e.g., requiring include files) and light glue code only.
- Keep enqueue handles and hook names consistent with existing naming patterns (`callamir_*`).
- Document new modules with a short PHPDoc block at the top describing their purpose.
- For styles in `style.css` or `style-rtl.css`, leverage CSS custom properties defined in `:root` and prefer logical properties (`margin-inline`, `padding-inline`, etc.) for RTL awareness.
