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
            // jQuery('.bck').backToTop();
            jQuery('.bck').backToTop({
                fxName: 'rightToLeft'
            });
        });
    </script>
<?php
}
add_action('wp_footer', 'sstti_scroll_script');
