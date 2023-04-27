<?php
// phpcs:ignoreFile
/**
 * Class WplpDivi
 */
class WplpDivi extends ET_Builder_Module
{
    /**
     * Init slug params
     *
     * @var string
     */
    public $slug       = 'wplp_divi';
    /**
     * Init vb_support params
     *
     * @var string
     */
    public $vb_support = 'on';

    /**
     * Credits of all custom modules.
     *
     * @var array Credits of all custom modules
     */
    protected $module_credits = array(
        'module_uri' => 'https://www.joomunited.com/',
        'author' => 'Joomunited',
        'author_uri' => 'https://www.joomunited.com/',
    );

    /**
     * Init function
     *
     * @return void
     */
    public function init()
    {
        $this->name = esc_html__('WP Latest Posts', 'wp-latest-posts');
    }

    /**
     * Get Fields
     *
     * @return array
     */
    public function get_fields()
    {
        $blocks                   = get_posts(
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

        $list = array();
        $list[0] = esc_html__('Choose a block', 'wp-latest-posts');
        foreach ($blocks as $block) {
            $list[$block->ID] = $block->post_title;
        }
        $descriptions = esc_html__('Open and edit the news block settings from the plugin', 'wp-latest-posts');
        $descriptions .= ' <a href="admin.php?page=wplp-widget&amp;view=block&amp;id=0" target="_blank">';
        $descriptions .= esc_html__('here', 'wp-latest-posts');
        $descriptions .= '</a>';
    
        return array(
            'news_widget_id' => array(
                'label' => esc_html__('Choose a block', 'wp-latest-posts'),
                'type' => 'wplp_input',
                'option_category' => 'configuration',
                'default' => 0,
                'default_on_front' => 0
            )
        );
    }

    /**
     * Render content
     *
     * @param array  $attrs       List of attributes.
     * @param string $content     Content being processed.
     * @param string $render_slug Slug of module that is used for rendering output.
     *
     * @return string
     */
    public function render($attrs, $content, $render_slug) // phpcs:ignore PEAR.Functions.ValidDefaultValue.NotAtEnd -- Method extends from ET_Builder_Module class
    {
        if (empty($this->props['news_widget_id'])) {
            $html = '<div class="wplp-elementor-placeholder" style="text-align: center; background: #fafafa; width: 100%;">
                <img style="display:block; margin: 0 auto; height: 200px" src="'.esc_url(WPLP_PLUGIN_DIR . 'img/wplp-tmce-placeholder.svg').'">
                <span style="font-size: 13px;">'.esc_html_e('Please select a WP Latest Posts new block to activate the preview', 'wp-latest-posts').'</span>
            </div>';
            return $html;
        }
        return do_shortcode('[frontpage_news widget="' . esc_attr($this->props['news_widget_id']) . '"]');
    }
}

new WplpDivi;
