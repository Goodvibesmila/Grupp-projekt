<?php
/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');
if (fusion_is_element_enabled('wplp_fusion')) {
    if (!class_exists('WplpAvadaClass')) {
      /**
       * Fusion WP Latest Post shortcode class.
       */
        class WplpAvadaClass extends Fusion_Element
        {
          /**
           * Constructor.
           */
            public function __construct()
            {
                parent::__construct();
                add_shortcode('wplp_fusion', array($this, 'render'));
            }

          /**
           * Render the shortcode
           *
           * @param array $args Shortcode parameters.
           *
           * @return string
           */
            public function render($args)
            {
                $atts = (shortcode_atts(array(
                'wplp_id' => '0'
                ), $args));
                $html = '';
                if (empty($atts['wplp_id'])) {
                    $html = '<div class="wplp-avada-container">
          <div id="wplp-avada-placeholder" class="wplp-avada-placeholder"></div>
          <span class="wplp-avada-message">
          ' . esc_html__('Please select a WP Latest Posts new block to activate the preview', 'wp-latest-posts') . '
          </span>
          </div>';
                } else {
                    $wplp_id = $atts['wplp_id'];
                    $html = do_shortcode('[frontpage_news widget="' . esc_attr($atts['wplp_id']) . '"]');
                }

                return $html;
            }
        }
    }
}

new WplpAvadaClass();

/**
 * Map shortcode to Avada Builder.
 *
 * @return void
 */
function wplpFusionElement()
{
    if (!function_exists('fusion_builder_frontend_data')) {
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
    $block_list[0] = $label;
    $descriptions = '';
    foreach ($blocks as $block) {
        $block_list[$block->ID] = $block->post_title;
        $descriptions .= '<span class="wplp-avada-desc" data-id="'.$block->ID.'" style="display:none">' . esc_html__('Open and edit the news block settings from the plugin', 'wp-latest-posts');
        $descriptions .= ' <a href="admin.php?page=wplp-widget&amp;view=block&amp;id='.$block->ID.'" target="_blank">';
        $descriptions .= esc_html__('here', 'wp-latest-posts');
        $descriptions .= '</a></span>';
    }

    fusion_builder_map(
        fusion_builder_frontend_data(
            'WplpAvadaClass',
            array(
            'name' => esc_attr__('WP Latest Posts', 'wp-latest-posts'),
            'description' => esc_attr__('WP Latest Posts', 'wp-latest-posts'),
            'shortcode' => 'wplp_fusion',
            'icon' => 'wplp-icon',
            'allow_generator' => true,
            'params' => array(
              array(
                'type' => 'select',
                'heading' => esc_html__('Choose a Gallery', 'wp-latest-posts'),
                'description' => $descriptions,
                'param_name' => 'wplp_id',
                'value' => $block_list
              ),
            ),
            )
        )
    );

    wp_enqueue_style(
        'wplp-avada-style',
        WPLP_PLUGIN_DIR . 'css/avada-widgets.css',
        array(),
        WPLP_VERSION
    );
}

wplpFusionElement();
add_action('fusion_builder_before_init', 'wplpFusionElement');
