<?php
// phpcs:ignoreFile
/**
 * Elementor Image Source control.
 */
class Elementor_Control_Wplp_Block extends \Elementor\Base_Data_Control
{

    /**
     * Get currency control type.
     *
     * Retrieve the control type, in this case `currency`.
     *
     * @since  1.0.0
     * @access public
     * @return string Control type.
     */
    public function get_type()
    {
        return 'wplp_select_block';
    }

    /**
     * Get currencies.
     *
     * Retrieve all the available currencies.
     *
     * @since  1.0.0
     * @access public
     * @static
     * @return array Available currencies.
     */
    public static function get_block()
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
        foreach ($blocks as $block) {
            $list[$block->ID] = $block->post_title;
        }

        return $list;
    }

    /**
     * Get currency control default settings.
     *
     * Retrieve the default settings of the currency control. Used to return
     * the default settings while initializing the currency control.
     *
     * @since  1.0.0
     * @access protected
     * @return array Currency control default settings.
     */
    protected function get_default_settings()
    {
        return [
            'wplp_block' => self::get_block()
        ];
    }

    /**
     * Get currency control default value.
     *
     * Retrieve the default value of the currency control. Used to return the
     * default value while initializing the control.
     *
     * @since  1.0.0
     * @access public
     * @return array Currency control default value.
     */
    public function get_default_value()
    {
        return '';
    }

    /**
     * Render currency control output in the editor.
     *
     * Used to generate the control HTML in the editor using Underscore JS
     * template. The variables for the class are available using `data` JS
     * object.
     *
     * @since  1.0.0
     * @access public
     */
    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>

        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">
            <?php esc_html_e('Open and edit the news block settings from the plugin', 'wp-latest-posts') ?> <a href="admin.php?page=wplp-widget&amp;view=block&amp;id={{{ data.description }}}" target="_blank"> <?php esc_html_e('here', 'wp-latest-posts') ?> </a>
        </div>
        <# } #>
        <?php
    }
}