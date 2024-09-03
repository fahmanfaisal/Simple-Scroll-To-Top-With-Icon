<?php

/**
 * Plugin Name: Simple Scroll To Top With Icon
 * Plugin URI: https://wordpress.org/plugins/simple-scroll-to-top-with-icon/
 * Description: Easily add a scroll to top button with a customizable icon to your WordPress site. Enhance user experience by allowing visitors to smoothly scroll back to the top of the page with a single click.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: mdFaisalChy
 * Author URI: https://scriptnotion.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: simple-scroll-to-top-with-icon
 * Domain Path: /languages
 */

// Enqueue CSS conditionally (only on the frontend)
function sstti_enqueue_style()
{
    if (!is_admin()) { // Only enqueue on the frontend
        wp_enqueue_style('sstti-fontawesome-css', plugins_url('fontawesome/css/all.css', __FILE__), array(), '6.0.0', 'all');
        wp_enqueue_style('sstti-style', plugins_url('css/sstti-style.css', __FILE__), array(), '1.0.0', 'all');
    }
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_style');

// Enqueue JS conditionally (only on the frontend)
function sstti_enqueue_scripts()
{
    if (!is_admin()) { // Only enqueue on the frontend
        wp_enqueue_script('jquery');
        wp_enqueue_script('sstti-fontawesome-js', plugins_url('fontawesome/js/all.min.js', __FILE__), array(), '6.0.0', true);
        wp_enqueue_script('sstti-fontawesome-js-core', plugins_url('fontawesome/js/fontawesome.min.js', __FILE__), array(), '6.0.0', true);
        wp_enqueue_script('sstti-plugin-script', plugins_url('js/sstti-plugin.js', __FILE__), array('jquery'), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_scripts');

// jQuery Plugin Setting Activation (safely outputting inline JS)
function sstti_scroll_script()
{
    if (!is_admin()) { // Only output on the frontend
?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                jQuery('.cssScrollTop').backToTop({
                    fxName: 'rightToLeft'
                });
            });
        </script>
    <?php
    }
}
add_action('wp_footer', 'sstti_scroll_script');

// Add Settings Page to the Admin Menu
function sstti_add_admin_menu()
{
    add_menu_page(
        __('Scroll To Top Settings', 'simple-scroll-to-top-with-icon'),
        __('Scroll To Top', 'simple-scroll-to-top-with-icon'),
        'manage_options',
        'sstti_scroll_to_top',
        'sstti_settings_page',
        'dashicons-arrow-up-alt',
        100
    );
}
add_action('admin_menu', 'sstti_add_admin_menu');

// Register Settings
function sstti_register_settings()
{
    register_setting('sstti_settings_group', 'sstti_default_color', 'sanitize_hex_color');
    register_setting('sstti_settings_group', 'sstti_icon_color', 'sanitize_hex_color');
    register_setting('sstti_settings_group', 'sstti_icon_class', 'sanitize_text_field');
}
add_action('admin_init', 'sstti_register_settings');

// Create the Settings Page
function sstti_settings_page()
{
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Scroll To Top Settings', 'simple-scroll-to-top-with-icon'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sstti_settings_group');
            do_settings_sections('sstti_settings_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Background Color', 'simple-scroll-to-top-with-icon'); ?></th>
                    <td>
                        <input type="text" name="sstti_default_color" value="<?php echo esc_attr(get_option('sstti_default_color', '#262626')); ?>" class="sstti-color-picker" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Icon Color', 'simple-scroll-to-top-with-icon'); ?></th>
                    <td>
                        <input type="text" name="sstti_icon_color" value="<?php echo esc_attr(get_option('sstti_icon_color', '#ffffff')); ?>" class="sstti-color-picker" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php esc_html_e('Icon (FontAwesome Class)', 'simple-scroll-to-top-with-icon'); ?></th>
                    <td>
                        <input type="text" name="sstti_icon_class" value="<?php echo esc_attr(get_option('sstti_icon_class', 'fa-solid fa-arrow-up')); ?>" />
                        <p><?php esc_html_e('Enter the FontAwesome icon class (e.g., <code>fa-solid fa-arrow-up</code>)', 'simple-scroll-to-top-with-icon'); ?></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Apply Custom Styles
function sstti_enqueue_config()
{
    // Get the saved custom colors and icon class
    $background_color = esc_attr(get_option('sstti_default_color', '#262626'));
    $icon_color = esc_attr(get_option('sstti_icon_color', '#ffffff'));
    $icon_class = esc_attr(get_option('sstti_icon_class', 'fa-solid fa-arrow-up'));

    // Add inline styles for background and icon color
    $custom_css = "
        .cssScrollTop {
            background-color: {$background_color};
            color: {$icon_color};
        }
        .cssScrollTop i {
            color: {$icon_color};
        }
    ";
    wp_add_inline_style('sstti-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_config');

// Add the Scroll-to-Top Button
function sstti_add_scroll_to_top_button()
{
    if (!is_admin()) { // Only output on the frontend
        $icon_class = esc_attr(get_option('sstti_icon_class', 'fa-solid fa-arrow-up'));
        echo '<div class="cssScrollTop"><i class="' . esc_attr($icon_class) . '"></i></div>';
    }
}
add_action('wp_footer', 'sstti_add_scroll_to_top_button');

// Enqueue Color Picker for Admin
function sstti_enqueue_color_picker($hook_suffix)
{
    if ($hook_suffix === 'toplevel_page_sstti_scroll_to_top') {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('sstti-color-picker', plugins_url('js/sstti-color-picker.js', __FILE__), array('wp-color-picker'), '1.0.0', true);
    }
}
add_action('admin_enqueue_scripts', 'sstti_enqueue_color_picker');
