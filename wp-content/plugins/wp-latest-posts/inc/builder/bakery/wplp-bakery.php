<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

/**
 * Element Description: VC Gallery
 */
if (class_exists('WPBakeryShortCode')) {
    /**
     * Class WplpBakery
     */
    class WplpBakery extends WPBakeryShortCode
    {
        /**
         * WplpBakery constructor.
         *
         * @return void
         */
        function __construct() // phpcs:ignore Squiz.Scope.MethodScope.Missing -- Method extends from WPBakeryShortCode class
        {
            // Stop all if VC is not enabled
            if (!defined('WPB_VC_VERSION')) {
                return;
            }

            $blocks = get_posts(
                array(
                    'post_type'      => CUSTOM_POST_NEWS_WIDGET_NAME,
                    'post_status'    => array(
                        'publish',
                        'future',
                        'private'
                    ),
                    'posts_per_page' => - 1
                )
            );

            $block_list = array();
            $label = esc_html__('Choose a block', 'wp-latest-posts');
            $block_list[$label] = 0;
            $descriptions = '';
            foreach ($blocks as $block) {
                $block_list[$block->post_title] = $block->ID;
                $descriptions .= '<span class="wplp-avada-desc" data-id="'.$block->ID.'" style="display:none">' . esc_html__('Open and edit the news block settings from the plugin', 'wp-latest-posts');
                $descriptions .= ' <a href="admin.php?page=wplp-widget&amp;view=block&amp;id='.$block->ID.'" target="_blank">';
                $descriptions .= esc_html__('here', 'wp-latest-posts');
                $descriptions .= '</a></span>';
            }

            // Map the block with vc_map()
            vc_map(
                array(
                    'name' => esc_html__('WP Latest Posts', 'wp-latest-posts'),
                    'description' => esc_html__('WP Latest Posts element', 'wp-latest-posts'),
                    'base' => 'vc_wplp',
                    'category' => 'JoomUnited',
                    'icon' => 'wplp-icon',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => esc_html__('Choose a block', 'wp-latest-posts'),
                            'param_name' => 'wplp_id',
                            'class' => 'wplp_vc_dropdown',
                            'value' => $block_list,
                            'description' => $descriptions,
                            'group' => esc_html__('General', 'wp-latest-posts')
                        )
                    )
                )
            );
            add_shortcode('vc_wplp', array($this, 'vcWplpHtml'));
        }

        /**
         * Render html
         *
         * @param array $atts Param details
         *
         * @return string
         */
        public function vcWplpHtml($atts)
        {
            if (empty($atts['wplp_id'])) {
                $html = '<div class="wplp-vc-container">
                    <div id="vc-wplp-placeholder" class="vc-wplp-placeholder">
                        <span class="wplp-vc-message">' . esc_html__('Please select a WP Latest Posts new block to activate the preview', 'wp-latest-posts') . '</span>
                    </div>
                </div>';
            } else {
                $wplp_id = $atts['wplp_id'];
                $html = do_shortcode('[frontpage_news widget="' . esc_attr($atts['wplp_id']) . '"]');
            }
            return $html;
        }
    }

    new WplpBakery();
}
