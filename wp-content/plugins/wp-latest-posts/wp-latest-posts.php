<?php
/**
 * Plugin Name: WP Latest Posts
 * Plugin URI: http://www.joomunited.com/wordpress-products/wp-latest-posts
 * Description: Advanced frontpage and widget news slider
 * Version: 5.0.3
 * Text Domain: wp-latest-posts
 * Domain Path: /languages
 * Author: JoomUnited
 * Author URI: http://www.joomunited.com
 * License: GPL2
 */

/*
 * @copyright 2014  Joomunited  ( email : contact _at_ joomunited.com )
 *
 *  Original development of this plugin was kindly funded by Joomunited
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if (!defined('CUSTOM_POST_NEWS_WIDGET_NAME')) {
    define('CUSTOM_POST_NEWS_WIDGET_NAME', 'wplp-news-widget');
}
if (!defined('CUSTOM_POST_NONCE_NAME')) {
    define('CUSTOM_POST_NONCE_NAME', 'wplp_editor_tabs');
}
if (!defined('POSITIVE_INT_GT1')) {
    define('POSITIVE_INT_GT1', 'positive_integer_1+');  //Those fields need to have a positive integer value greater than 1
}
if (!defined('BOOL')) {
    define('BOOL', 'bool');       //Booleans
}
if (!defined('FILE_UPLOAD')) {
    define('FILE_UPLOAD', 'file_upload');    //File uploads
}
if (!defined('LI_TO_ARRAY')) {
    define('LI_TO_ARRAY', 'li_to_array');    //Convert sortable lists to array
}
if (!defined('STRING_UNSET')) {
    define('STRING_UNSET', 'string_unset');  //Unset settings with checkbox
}
if (!defined('WPLP_PREFIX')) {
    define('WPLP_PREFIX', 'wplp_');
}
if (!defined('MAIN_FRONT_STYLESHEET')) {
    define('MAIN_FRONT_STYLESHEET', 'css/wplp_front.css');  //Main front-end stylesheet
}
if (!defined('MAIN_FRONT_SCRIPT')) {
    define('MAIN_FRONT_SCRIPT', 'js/wplp_front.js');  //Main front-end jQuery script
}
if (!defined('DEFAULT_IMG')) {
    define('DEFAULT_IMG', 'img/default-image.svg'); //Default thumbnail image
}
if (!defined('WPLP_PLUGIN_DIR')) {
    define('WPLP_PLUGIN_DIR', plugin_dir_url(__FILE__));
}
if (!defined('WPLP_PLUGIN_PATH')) {
    define('WPLP_PLUGIN_PATH', dirname(__FILE__));
}
if (!defined('WPLP_POST_VIEWS_COUNT_META_KEY')) {
    define('WPLP_POST_VIEWS_COUNT_META_KEY', 'wplp_post_views_count');
}
if (!defined('WPLP_TRANSIENT_KEY_PREFIX')) {
    define('WPLP_TRANSIENT_KEY_PREFIX', '');
}
if (!defined('WPLP_POST_VIEW_TRANSIENT_KEY')) {
    define('WPLP_POST_VIEW_TRANSIENT_KEY', WPLP_TRANSIENT_KEY_PREFIX . 'wp:wplp_post_view_' . md5('post_view_transient_key'));
}
if (!defined('WPLP_VERSION')) {
    define('WPLP_VERSION', '5.0.3'); // WP Latest Post current version
}
//Check plugin requirements
if (version_compare(PHP_VERSION, '5.6', '<')) {
    if (!function_exists('wplp_disable_plugin')) {
        /**
         * Disable plugin function
         *
         * @return void
         */
        function wplp_disable_plugin()
        {
            if (current_user_can('activate_plugins') && is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(__FILE__);
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Internal function used
                unset($_GET['activate']);
            }
        }
    }

    if (!function_exists('wplp_show_error')) {
        /**
         * Show error when active plugin at least PHP 5.6 version
         *
         * @return void
         */
        function wplp_show_error()
        {
            $echo = '<div class="error"><p><strong>WP Latest Posts</strong>';
            $echo .= ' need at least PHP 5.6 version, please update php before installing the plugin.</p></div>';
            //phpcs:ignore WordPress.Security.EscapeOutput -- Plain text html, no variables to escape
            echo $echo;
        }
    }

    //Add actions
    add_action('admin_init', 'wplp_disable_plugin');
    add_action('admin_notices', 'wplp_show_error');

    //Do not load anything more
    return;
}

if (!class_exists('\Joomunited\WPLP\JUCheckRequirements')) {
    require_once(trailingslashit(dirname(__FILE__)) . 'requirements.php');
}

if (class_exists('\Joomunited\WPLP\JUCheckRequirements')) {
    // Plugins name for translate
    $args = array(
        'plugin_name' => esc_html__('WP Latest Posts', 'wp-latest-posts'),
        'plugin_path' => 'wp-latest-posts/wp-latest-posts.php',
        'plugin_textdomain' => 'wp-latest-posts',
        'requirements' => array(
            'php_version' => '5.6',
            // Minimum addons version
            'addons_version' => array(
                'wplpAddons' => '4.4.0'
            )
        ),
    );
    $wplpCheck = call_user_func('\Joomunited\WPLP\JUCheckRequirements::init', $args);

    if (!$wplpCheck['success']) {
        // Do not load anything more
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Internal function used
        unset($_GET['activate']);
        return;
    }
}

//Include the jutranslation helpers
require_once 'jutranslation' . DIRECTORY_SEPARATOR . 'jutranslation.php';
call_user_func(
    '\Joomunited\WPLatestPosts\Jutranslation\Jutranslation::init',
    __FILE__,
    'wp-latest-posts',
    'WP Latest Posts',
    'wp-latest-posts',
    'languages' . DIRECTORY_SEPARATOR . 'wp-latest-posts-en_US.mo'
);

// Include jufeedback helpers
require_once('jufeedback'. DIRECTORY_SEPARATOR . 'jufeedback.php');
call_user_func(
    '\Joomunited\WPLatestPosts\Jufeedback\Jufeedback::init',
    __FILE__,
    'wplp',
    'wp-latest-posts',
    'WP Latest Posts',
    'wp-latest-posts'
);


// Install
require_once dirname(__FILE__) . '/inc/install.php';
// Class includes
require_once dirname(__FILE__) . '/inc/wplp-admin.inc.php';            // custom classes
require_once dirname(__FILE__) . '/inc/wplp-widget.inc.php';        // custom classes
require_once dirname(__FILE__) . '/inc/wplp-front.inc.php';            // custom classes
require_once dirname(__FILE__) . '/inc/wplp-cache.php';            // custom classes
// WPML installed
// Polylang installed
require_once dirname(__FILE__) . '/inc/compatibility/class.language_content_wpml.php';
new WPLPLanguageContent();
// Require add image for category
require_once dirname(__FILE__) . '/inc/wplp-category-image.php';
new WPLPCategoryImage();


/**
 * Just fill up necessary settings in the configuration array
 * to create a new custom plugin instance...
 */
global $wpcu_wpfn;
$wpcu_wpfn = new WPLPAdmin(
    array(
        'version' => '5.0.3',
        'translation_domain' => 'wp-latest-posts', // must be copied in the widget class!!!
        'plugin_file' => __FILE__,
    )
);


// Load Addons
if (isset($wplpCheck) && !empty($wplpCheck['load'])) {
    foreach ($wplpCheck['load'] as $addonName) {
        if (function_exists($addonName . 'Init')) {
            call_user_func($addonName . 'Init');
        }
    }
}

/**
 * Load script for elementor
 *
 * @return void
 */
function wplpLoadElementorWidgetStyle()
{
    wp_enqueue_style(
        'wplp-widgets',
        WPLP_PLUGIN_DIR . 'css/elementor-widgets.css'
    );
}
add_action('elementor/editor/after_enqueue_styles', 'wplpLoadElementorWidgetStyle');

/**
 * Load elementor widget
 *
 * @return void
 */
function wplpLoadElementorWidget()
{
    require_once(WPLP_PLUGIN_PATH . '/inc/builder/elementor-widget.php');
    \Elementor\Plugin::instance()->widgets_manager->register(new \WplpElementorWidget());
}
add_action('elementor/widgets/widgets_registered', 'wplpLoadElementorWidget');

/**
 * Register new Elementor controls.
 *
 * @param \Elementor\Controls_Manager $controls_manager Elementor controls manager.
 *
 * @return void
 */
function register_new_controls_wplp($controls_manager)
{
    require_once(WPLP_PLUGIN_PATH . '/inc/builder/controls/select-block.php');
    $controls_manager->register(new \Elementor_Control_Wplp_Block());
}
add_action('elementor/controls/register', 'register_new_controls_wplp');

require_once dirname(__FILE__) . '/inc/builder/divi/wplp-divi.php';

if (is_plugin_active('js_composer/js_composer.php') || defined('WPB_VC_VERSION')) {
    add_action('vc_before_init', 'wplpVcBeforeInit');
}

/**
 * Add bakery widgets
 *
 * @return void
 */
function wplpVcBeforeInit()
{
    wp_enqueue_style(
        'wplp-bakery-style',
        WPLP_PLUGIN_DIR . 'css/vc_style.css',
        array(),
        WPLP_VERSION
    );

    wp_enqueue_script(
        'wplp_addon_imagesloaded',
        WPLP_PLUGIN_DIR.'js/imagesloaded.pkgd.min.js',
        array('jquery'),
        '0.1',
        true
    );

    if (defined('WPLP_ADDON_VERSION')) {
        wp_enqueue_script('jquery-masonry');
        wp_enqueue_script(
            'wplp_isotope',
            WPLPADDON_PLUGIN_DIR . 'themes/portfolio/isotope.js',
            array('jquery'),
            '1.0'
        );
    }

    if (is_admin()) {
        //backend enqueue
        add_action('vc_backend_editor_enqueue_js_css', 'wplp_wpbakery_enqueue_assets');

        //frontend enqueue
        add_action('vc_frontend_editor_enqueue_js_css', 'wplp_wpbakery_enqueue_assets');
    }

    include_once(WPLP_PLUGIN_PATH.'/inc/builder/bakery/wplp-bakery.php');
}

/**
 * WPBakery enqueue assets
 *
 * @return void
 */
function wplp_wpbakery_enqueue_assets()
{
    wp_enqueue_script(
        'wplp-bakery-js',
        WPLP_PLUGIN_DIR . 'js/vc_script.js',
        array(),
        WPLP_VERSION
    );
}

/**
 * Add avada widgets
 *
 * @return void
 */
function register_wplp_fusion_builder_element()
{
    if (class_exists('FusionBuilder')) {
        include_once(WPLP_PLUGIN_PATH.'/inc/builder/avada/wplp-avada.php');

        if (is_admin()) {
            add_action('admin_enqueue_scripts', 'wplp_avada_scripts');
        }
    }
}
add_action('init', 'register_wplp_fusion_builder_element');

/**
 * Add avada scripts
 *
 * @return void
 */
function wplp_avada_scripts()
{
    wp_enqueue_script(
        'wplp-avada-js',
        WPLP_PLUGIN_DIR . 'js/avada-widgets.js',
        array(),
        WPLP_VERSION,
        true
    );
}
