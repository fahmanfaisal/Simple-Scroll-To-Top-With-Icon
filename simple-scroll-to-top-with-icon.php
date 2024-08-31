<?php

/**
 * Plugin name: Simple Scroll To Top With Icon
 * Plugin Uri: https://wordpress.org/plugins/simple-scroll-to-top-with-icon/
 * Description: Easily add a scroll to top button with a customizable icon to your WordPress site. Enhance user experience by allowing visitors to smoothly scroll back to the top of the page with a single click.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: MD Faisal Chy
 * Author URI: https://scriptnotion.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Update URI: https://github.com/fahmanfaisal/Simple-Scroll-To-Top-With-Icon
 * Text Domain: sstti
 */



// Including CSS
function sstti_enqueue_style()
{
    wp_enqueue_style('sstti-style', plugins_url('css/sstti-style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_style');

// Including JS
function sstti_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('sstti-plugin-script', plugins_url('js/sstti-plugin.js', __FILE__), array(), '1.0.0', 'true');
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_scripts');

// jQuery Plugin Setting Activation
function sstti_scroll_script()
{
?>
    <script>
        jQuery(document).ready(function($) {
            // jQuery('.cssScrollTop').backToTop();
            jQuery('.cssScrollTop').backToTop({
                fxName: 'rightToLeft'
            });
        });
    </script>
<?php
}
add_action('wp_footer', 'sstti_scroll_script');


//Plugin Customization Settings 


/// Add Settings Page to the Admin Menu
function sstti_add_admin_menu()
{
    add_menu_page(
        'Scroll To Top Settings',     // Page Title
        'Scroll To Top',              // Menu Title
        'manage_options',             // Capability
        'sstti_scroll_to_top',        // Menu Slug
        'sstti_settings_page',        // Callback Function
        'dashicons-arrow-up-alt',     // Icon
        100                           // Position
    );
}
add_action('admin_menu', 'sstti_add_admin_menu');

// Register Settings
function sstti_register_settings()
{
    register_setting('sstti_settings_group', 'sstti_default_color');
}
add_action('admin_init', 'sstti_register_settings');

// Create the Settings Page
function sstti_settings_page()
{
?>
    <div class="wrap">
        <h1>Scroll To Top Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('sstti_settings_group'); ?>
            <?php do_settings_sections('sstti_settings_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td>
                        <input type="text" name="sstti_default_color" value="<?php echo esc_attr(get_option('sstti_default_color', '#262626')); ?>" class="sstti-color-picker" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}

// Enqueue Color Picker
function sstti_enqueue_color_picker($hook_suffix)
{
    // Load color picker styles and scripts for settings page only
    if ($hook_suffix === 'toplevel_page_sstti_scroll_to_top') {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('sstti-color-picker', plugins_url('js/sstti-color-picker.js', __FILE__), array('wp-color-picker'), false, true);
    }
}
add_action('admin_enqueue_scripts', 'sstti_enqueue_color_picker');


// Enqueue Styles and Add Inline Styles for Custom Background Color
function sstti_enqueue_custom_styles()
{
    // Enqueue your main stylesheet
    wp_enqueue_style('sstti-main-style', plugins_url('css/sstti-style.css', __FILE__));

    // Get the saved custom background color
    $custom_color = get_option('sstti_default_color', '#262626');

    // Add inline styles
    $custom_css = "
        .cssScrollTop {
            background-color: {$custom_color};
        }
    ";
    wp_add_inline_style('sstti-main-style', $custom_css);
}
add_action('wp_enqueue_scripts', 'sstti_enqueue_custom_styles');
