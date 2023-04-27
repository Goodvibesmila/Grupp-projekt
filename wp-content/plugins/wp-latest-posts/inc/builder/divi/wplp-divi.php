<?php
/*
Plugin Name: wplp divi
Plugin URI:  https://joomunited.com/
Description: Adds a custom module to Divi builder
Version:     1.0.1
Author:      joomunited
Author URI:  https://joomunited.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wplp-divi
Domain Path: /languages

divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with divi. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


if (! function_exists('wplp_divi')) :
    /**
     * Creates the extension's main class instance.
     *
     * @return void
     */
    function wplp_divi()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/WplpDivi.php';
        wp_enqueue_style(
            'wplp_divi_css',
            plugins_url('wp-latest-posts/css') . '/divi-widgets.css',
            array(),
            WPLP_VERSION,
            'all'
        );

        wp_enqueue_script(
            'wplp_addon_imagesloaded',
            plugins_url('wp-latest-posts/js/imagesloaded.pkgd.min.js'),
            array('jquery'),
            WPLP_VERSION,
            true
        );

        wp_enqueue_style('wplp-swiper-style', WPLP_PLUGIN_DIR . 'css/swiper-bundle.min.css');
        wp_enqueue_style('wplpStyleDefault', plugins_url('wp-latest-posts/themes/default/style.css'), array(), WPLP_VERSION);

        if (defined('WPLP_ADDON_VERSION')) {
            wp_enqueue_script('jquery-masonry');
            wp_enqueue_script(
                'wplp_isotope',
                WPLPADDON_PLUGIN_DIR . 'themes/portfolio/isotope.js',
                array('jquery'),
                WPLP_ADDON_VERSION
            );
            wp_enqueue_style('wplpStyleMasonry', WPLPADDON_PLUGIN_DIR . 'themes/masonry/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStyleMaterialVertical', WPLPADDON_PLUGIN_DIR . 'themes/material-vertical/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStyleMaterialHorizontal', WPLPADDON_PLUGIN_DIR . 'themes/material-horizontal/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStyleMasonryCategory', WPLPADDON_PLUGIN_DIR . 'themes/masonry-category/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStyleSmooth', WPLPADDON_PLUGIN_DIR . 'themes/smooth-effect/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStyleTimeline', WPLPADDON_PLUGIN_DIR . 'themes/timeline/style.css', array(), WPLP_ADDON_VERSION);
            wp_enqueue_style('wplpStylePortfolio', WPLPADDON_PLUGIN_DIR . 'themes/portfolio/style.css', array(), WPLP_ADDON_VERSION);

            wp_enqueue_script(
                'wplp-flexslider-js',
                plugins_url('wp-latest-posts/js/flexslider.min.js'),
                array(),
                WPLP_ADDON_VERSION
            );
        }

        wp_enqueue_script(
            'wplp-swiper-js',
            plugins_url('wp-latest-posts/js/swiper-bundle.js'),
            array(),
            WPLP_VERSION
        );
        wp_enqueue_script(
            'wplp-front-js',
            plugins_url('wp-latest-posts/js/wplp_front.js'),
            array(),
            WPLP_VERSION
        );
    }
    add_action('divi_extensions_init', 'wplp_divi');
endif;
