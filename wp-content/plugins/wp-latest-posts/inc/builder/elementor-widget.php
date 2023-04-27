<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class WplpElementorWidget
 */
class WplpElementorWidget extends \Elementor\Widget_Base
{
    /**
     * Init html string params
     *
     * @var string
     */
    private $html = '';
    /**
     * Init script string params
     *
     * @var string
     */
    private $script = '';
    /**
     * Init stylesheet string params
     *
     * @var string
     */
    private $stylesheet = '';
    
    /**
     * Get widget name.
     *
     * Retrieve Gallery widget name.
     *
     * @return string Widget name.
     */
    public function get_name() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Method extends from \Elementor\Widget_Base class
    {
        return 'wplp';
    }

    /**
     * Get widget title.
     *
     * Retrieve Gallery widget title.
     *
     * @return string Widget title.
     */
    public function get_title() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Method extends from \Elementor\Widget_Base class
    {
        return esc_html__('WP Latest Posts', 'wp-latest-posts');
    }

    /**
     * Get widget icon.
     *
     * Retrieve Gallery widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Method extends from \Elementor\Widget_Base class
    {
        return 'fa wplp-elementor-icon';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the Gallery widget belongs to.
     *
     * @return array Widget categories.
     */
    public function get_categories() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Method extends from \Elementor\Widget_Base class
    {
        return array('general');
    }

    /**
     * Register Gallery widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @return void
     */
    protected function register_controls() // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps, PSR2.Methods.MethodDeclaration.Underscore -- Method extends from \Elementor\Widget_Base class
    {
        $this->start_controls_section(
            'wplp_settings',
            array(
                'label' => esc_html__('WP Latest Posts Settings', 'wp-latest-posts'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

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

        $this->add_control(
            'news_widget_id',
            array(
                'label' => esc_html__('Choose a block', 'wp-latest-posts'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $list,
                'default' => 0,
            )
        );

        foreach ($list as $key => $value) {
            if ($key !== 0) {
                $name_control = 'wplp_description_'.$key;
                $this->add_control(
                    $name_control,
                    array(
                        'label' => 'description',
                        'type' => 'wplp_select_block',
                        'description' => (string)$key,
                        'condition' =>  array(
                            'news_widget_id' => (string)$key
                        )
                    )
                );
            }
        }

        $this->end_controls_section();
    }

    /**
     * Render Gallery widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @return void|string
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $news_widget_id = (!empty($settings['news_widget_id'])) ? $settings['news_widget_id'] : 0;
        if ($news_widget_id !== 0) {
            if (!is_admin()) {
                echo do_shortcode('[frontpage_news widget="' . esc_attr($news_widget_id) . '"]');
            } else {
                $type = 'stylesheet';
                $widget = get_post($news_widget_id);
                if (isset($widget) && !empty($widget)) {
                    $widget->settings = get_post_meta($widget->ID, '_wplp_settings', true);
                    $widget->settings = array_merge($this->field_defaults, $widget->settings);

                    $id               = $widget->ID;
                    $nbcol            = (isset($widget->settings['amount_cols']) ? $widget->settings['amount_cols'] : 3);
                    $nbrow            = (isset($widget->settings['amount_rows']) ? $widget->settings['amount_rows'] : 1);
                    $pagination       = (isset($widget->settings['pagination']) ? $widget->settings['pagination'] : 2);
                    $autoanimate      = (isset($widget->settings['autoanimation']) ? $widget->settings['autoanimation'] : 0);
                    $autoanimatetrans = (isset($widget->settings['autoanimation_trans']) ?
                        $widget->settings['autoanimation_trans'] : 1);
                    $animationloop    = (isset($widget->settings['autoanim_loop']) ?
                        $widget->settings['autoanim_loop'] : 1);
                    $slideshowspeed   = (isset($widget->settings['autoanim_slideshowspeed']) ?
                        $widget->settings['autoanim_slideshowspeed'] : 7000);
                    $slidespeed       = (isset($widget->settings['autoanim_slidespeed']) ?
                        $widget->settings['autoanim_slidespeed'] : 600);
                    $pausehover       = (isset($widget->settings['autoanim_pause_hover']) ?
                        $widget->settings['autoanim_pause_hover'] : 1);
                    $pauseaction      = (isset($widget->settings['autoanim_pause_action']) ?
                        $widget->settings['autoanim_pause_action'] : 1);
                    $slidedirection   = (isset($widget->settings['autoanimation_slidedir']) ?
                        $widget->settings['autoanimation_slidedir'] : 0);
                    $touchaction      = (isset($widget->settings['autoanim_touch_action']) ?
                        $widget->settings['autoanim_touch_action'] : 1);
                    $layzyload_img    = (isset($widget->settings['layzyload_default']) ?
                        $widget->settings['layzyload_default'] : 0);
                    $space_between    = (isset($widget->settings['space_between']) ?
                        $widget->settings['space_between'] : 15);
                    if (defined('WPLP_ADDON_VERSION')) {
                        $addon_enable     = 1;
                    } else {
                        $addon_enable     = 0;
                    }
                    $data_array = array(
                        'id'               => $id,
                        'nbcol'            => $nbcol,
                        'nbrow'            => $nbrow,
                        'pagination'       => $pagination,
                        'autoanimate'      => $autoanimate,
                        'autoanimatetrans' => $autoanimatetrans,
                        'animationloop'    => $animationloop,
                        'slideshowspeed'   => $slideshowspeed,
                        'slidespeed'       => $slidespeed,
                        'pausehover'       => $pausehover,
                        'pauseaction'      => $pauseaction,
                        'slidedirection'   => $slidedirection,
                        'touch'            => $touchaction,
                        'theme'            => $widget->settings['theme'],
                        'layzyload_img'    => $layzyload_img,
                        'space_between'    => $space_between,
                        'addon_enable'     => $addon_enable
                    );

                    $this->script .= '<script>var WPLP_'.$id.'='.json_encode($data_array).';</script>';
                    $style = '';
                    if ($widget->settings['theme'] === 'default') {
                        $style = WPLP_PLUGIN_DIR . 'themes/'. $widget->settings['theme'] .'/style.css';
                        
                        // wp_localize_script('scriptdefault-wplp-data', 'WPLP_' . (int) $id, $data_array);
                        $scr_url = plugins_url('wp-latest-posts/js/') . 'wplp_front.js?ver='.WPLP_VERSION;
                        $this->script .= '<script id="wplp-default-front-js" src="'.$scr_url.'"></script>';// phpcs:ignore
                        $this->stylesheet = '<link rel="'. esc_attr($type) .'" href="'.plugins_url('wp-latest-posts/css/') . 'swiper-bundle.min.css'.'" />';// phpcs:ignore
                    } else {
                        if (defined('WPLP_ADDON_VERSION')) {
                            $style = WPLPADDON_PLUGIN_DIR . 'themes/'. $widget->settings['theme'] .'/style.css';
                            if ($widget->settings['theme'] === 'masonry' || $widget->settings['theme'] === 'masonry-category' || $widget->settings['theme'] === 'material-vertical' || $widget->settings['theme'] === 'material-horizontal') {
                                $this->script .= '<script src="'. WPLPADDON_PLUGIN_DIR .'js/wplp_addon_front.js?ver='.WPLP_VERSION .'"></script>';// phpcs:ignore
                                $this->script .= '<script src="'. WPLP_PLUGIN_DIR .'js/imagesloaded.pkgd.min.js?ver='.WPLP_VERSION .'"></script>';// phpcs:ignore
                            }

                            $scr_url = WPLPADDON_PLUGIN_DIR.'themes/'. $widget->settings['theme'] . '/script.js?ver='.WPLP_ADDON_VERSION;
                            if ($widget->settings['theme'] !== 'material-horizontal') {
                                $this->script .= '<script id="wplp-'. $widget->settings['theme'] .'-js" src="'. $scr_url .'"></script>';
                            }

                            $ajax_non = wp_create_nonce('wplp-addon-front-nonce');
                            $this->script .= '<script id="wplp_addon_front-js-extra">var wpsolAddonFrontJS = {"ajaxnonce":"'.$ajax_non.'"};</script>';
                            
                            $this->stylesheet = '<style>'. $this->loadInlineStyle($widget->settings, $widget->settings['theme'], $widget->ID) .'</style>';
                        }
                    }
                    
                    $this->stylesheet .= '<link rel="'. esc_attr($type) .'" href="'.esc_url($style).'" />';
                    $front            = new WPLPFront($widget);
                    $this->stylesheet .= '<style>'. $front->loadThemeStyle(true) .'</style>';
                    $this->html .= $front->display(false);
                } else {
                    $this->html .= "\n<!-- WPFN: this News Widget is not initialized -->\n";
                }
                echo $this->stylesheet;// phpcs:ignore
                echo $this->html;// phpcs:ignore
                echo $this->script;// phpcs:ignore
            }
        } else {
            ?>
            <div class="wplp-elementor-placeholder" style="text-align: center; width: 100%;">
                <img style="display:block; margin: 0 auto; height: 200px" src="<?php echo esc_url(WPLP_PLUGIN_DIR . 'img/wplp-tmce-placeholder.svg'); ?>">
                <span style="font-size: 13px;"><?php echo esc_html_e('Please select a WP Latest Posts new block to activate the preview', 'wp-latest-posts'); ?></span>
            </div>
            <?php
        }
    }

    /**
     * Get script depends
     *
     * @return array
     */
    public function get_script_depends()// phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps -- Method extends from \Elementor\Widget_Base class
    {

        wp_register_script(
            'swiper-wplp',
            plugins_url('wp-latest-posts/js/') . 'swiper-bundle.js',
            array('jquery'),
            WPLP_VERSION,
            true
        );

        return array(
            'swiper-wplp',
            'jquery-masonry'
        );
    }

    /**
     * Field default values
     *
     * @var array
     */
    protected $field_defaults = array(
        'source_type'            => 'src_category',
        'cat_post_source_order'  => 'date',
        'cat_post_source_asc'    => 'desc',
        'cat_source_order'       => 'date',
        'cat_source_asc'         => 'desc',
        'pg_source_order'        => 'order',
        'pg_source_asc'          => 'desc',
        'cat_list_source_order'  => 'id',
        'cat_list_source_asc'    => 'desc',
        'content_language'       => 'en',
        'content_include'        => 1,
        'show_title'             => 1, // Wether or not to display the block title
        'amount_pages'           => 1,
        'amount_cols'            => 3,
        'amount_rows'            => 1,
        'pagination'             => 2,
        'max_elts'               => 30,
        'per_page'               => 10,
        'off_set'                => 0, //number posts to skip
        'total_width'            => 100,
        'total_width_unit'       => 0, //%
        'crop_title'             => 2,
        'crop_title_len'         => 1,
        'crop_text'              => 2,
        'crop_text_len'          => 2,
        'autoanimation'          => 0,
        'autoanimation_trans'    => 1,
        'autoanimation_slidedir' => 0,
        'autoanim_loop'          => 1,
        'autoanim_pause_hover'   => 1,
        'autoanim_pause_action'  => 1,
        'autoanim_touch_action'  => 1,
        'layzyload_default'      => 0,
        'open_link'              => 0,
        'load_more'              => 0,
        'force_icon'             => 0,
        'theme'                  => 'default',
        'box_top'                => array(),
        'box_left'               => array('Thumbnail'),
        'box_right'              => array('Title', 'Date', 'Text'),
        'box_bottom'             => array(),
        'thumb_img'              => 1, // 0 == use featured image
        'image_size'             => 'mediumSize',
        'thumb_width'            => 150, // in px
        'thumb_height'           => 150, // in px
        'crop_img'               => 0, // 0 == do not crop (== resize to fit)
        'margin_left'            => 0,
        'margin_top'             => 0,
        'margin_right'           => 4,
        'custom_css'             => '',
        'margin_bottom'          => 4,
        'date_fmt'               => '',
        'no_post_text'           => '',
        'read_more'              => '',
        'default_img_previous'   => '', // Overridden in constructor
        'default_img'            => '', // Overridden in constructor
        'dfThumbnail'            => 'Thumbnail',
        'dfTitle'                => 'Title',
        'dfAuthor'               => '',
        'dfText'                 => 'Text',
        'dfDate'                 => 'Date',
        'dfCategory'             => '',
        'dfReadMore'             => 'Read more',
        'image_position_width'   => '30',
        'slide_height'           => 500
    );

    /**
     * Load inline style
     *
     * @param array   $settings  List of settings
     * @param string  $theme_dir Directory of theme
     * @param integer $idWidget  Id of widget
     *
     * @return boolean|string
     */
    public function loadInlineStyle($settings, $theme_dir, $idWidget)
    {
        $colorTheme          = (isset($settings['defaultColor']) ? $settings['defaultColor'] : '');
        if ($settings['theme'] === 'smooth-effect' || $settings['theme'] === 'masonry-category') {
            $settings['overlay_background'] = '0.7';
        }
        if ($settings['theme'] === 'material-vertical' || $settings['theme'] === 'material-horizontal') {
            $overlay_background          = (isset($settings['overlay_background']) ? $settings['overlay_background'] : '0.2');
            $color               = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), $overlay_background);
        } else {
            $overlay_background          = (isset($settings['overlay_background']) ? $settings['overlay_background'] : '0.7');
            $color               = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), $overlay_background);
        }

        $icon_color = (isset($settings['addon_icon_color']) ? $settings['addon_icon_color'] : '#ffffff');
        $bg_icon_color = (isset($settings['addon_bg_icon_color']) ? $settings['addon_bg_icon_color'] : 'transparent');

        if (isset($settings['colorpicker']) && $settings['colorpicker'] !== 'transparent') {
            $colorfull           = $this->hex2rgba((isset($settings['colorpicker']) ? $settings['colorpicker'] : ''), 1);
        } else {
            $colorfull = 'transparent';
        }

        $nbcol               = $settings['amount_cols'];
        $theme_classDashicon = ' ' . basename($settings['theme']);
        if (($theme_classDashicon !== ' default')) {
            $hanlde = 'themes-wplp-' . $settings['theme'];
            wp_enqueue_style($hanlde, plugins_url('wp-latest-posts-addon/themes/') . $theme_dir . '/style.css', array('myStyleSheets'), WPLP_ADDON_VERSION);
            $css            = '';
            $widthtotal     = 100;
            $width1         = $widthtotal / $nbcol;
            $width2         = $widthtotal;
            $margin_element = 10;
            if ($theme_classDashicon === ' material-vertical') {
                $margin_element = 20;
            }

            $gui            = ($margin_element * ($nbcol - 1));

            if ($theme_classDashicon === ' masonry-category') {
                $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts li .img_cropper:after{color: '. $icon_color .';background: center center no-repeat ' . $color . '} ';
                $css .= '#wplp_widget_' . $idWidget .
                        ' .masonry-category .wpcu-front-box.bottom .category:before{background:' . $color . '}';

                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['dashicons_selector'])) {
                        if ($settings['dashicons_selector'] === '') {
                            $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                            $css .= 'content: none ;';
                            $css .= '}';
                        } else {
                            $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                            $css .= "content:'\\" . $settings['dashicons_selector'] . "';";
                            $css .= '}';
                        }
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: '. $color .';';
                        $css .= '}';
                    }

                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'background-color: '. $bg_icon_color .';';
                    $css .= 'color: '. $icon_color .';';
                    $css .= '}';
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'content: none ;';
                    $css .= '}';
                }



                $css .= '#wplp_widget_' . $idWidget .
                        ' .read-more{border-top:1px solid ' . $color . '}';
                $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts li{ width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                        ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important; }}';
            } elseif ($theme_classDashicon === ' masonry') {
                wp_enqueue_style(
                    'wpmf-settings-google-icon',
                    plugins_url('wp-latest-posts-addon/themes/') . $theme_dir . '/fonts/material-icon.css',
                    array(),
                    WPLP_ADDON_VERSION
                );

                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['material_icon_selector']) && $settings['material_icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:after {';
                        $css .= "content:'\\". $settings['material_icon_selector'] ."';";
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: '. $color .';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:after {';
                        $css .= 'background-color: '. $bg_icon_color .';';
                        $css .= 'color: '. $icon_color .';';
                        $css .= '}';
                    } elseif (isset($settings['icon_selector']) && $settings['icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background: url(' .
                            $settings['icon_selector'] . ') center no-repeat '. $color .';';
                        $css .= '}';
                    } else {
                        $img_dir = plugins_url('wp-latest-posts-addon') . '/themes/masonry/img/overimage.png';
                        $css     .= '#wplp_widget_' . $idWidget .
                            " .wplp_listposts li .img_cropper:before{background: url('" .
                            $img_dir . "') center center no-repeat " . $color . ';}';
                    }
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'background: '. $color .';';
                    $css .= '}';
                }

                $css     .= '#wplp_widget_' . $idWidget .
                    ' .read-more{border-top:1px solid ' . $color . ';}';

                $css     .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li{
                width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css     .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                            ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important;}}';
            } elseif ($theme_classDashicon === ' material-vertical') {
                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['material_icon_selector']) && $settings['material_icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= "content:'\\" . $settings['material_icon_selector'] . "';";
                        $css .= 'background-color: ' . $bg_icon_color . ';';
                        $css .= 'color: ' . $icon_color . ';';
                        $css .= '}';
                    } elseif (isset($settings['icon_selector']) && $settings['icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= "opacity: 1; background: url('" .
                            $settings['icon_selector'] . "')  center no-repeat;";
                        $css .= 'background-size: cover;';
                        $css .= '}';
                    } else {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                        $css .= 'background-color: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                        $css .= 'background-color: transparent;';
                        $css .= '}';
                    }
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:before {';
                    $css .= 'background-color: '. $color .';';
                    $css .= '}';

                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li .img_cropper:hover:before {';
                    $css .= 'background-color: transparent;';
                    $css .= '}';
                }

                $css     .= '#wplp_widget_' . $idWidget . ' .wplp_listposts li{
                width: calc((' . $widthtotal . '% - ' . $gui . 'px)/' . $nbcol . ');}';
                $css     .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                    ' .wplp_listposts li {width: calc(' . $width2 . '% - ' . (2 * $margin_element) . 'px) !important;}}';
            } elseif ($theme_classDashicon === ' material-horizontal') {
                if (isset($settings['force_icon']) && (int) $settings['force_icon'] === 1) {
                    if (isset($settings['material_icon_selector']) && $settings['material_icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card .wplp-mh-post-image a:before {';
                        $css .= 'background-color: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card:hover .wplp-mh-post-image a:before {';
                        $css .= "content:'\\" . $settings['material_icon_selector'] . "';";
                        $css .= 'background-color: ' . $bg_icon_color . ';';
                        $css .= 'color: ' . $icon_color . ';';
                        $css .= '}';
                    } elseif (isset($settings['icon_selector']) && $settings['icon_selector'] !== '') {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card .wplp-mh-post-image a:before {';
                        $css .= 'background: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card:hover .wplp-mh-post-image a:before {';
                        $css .= "opacity: 1; background: url('" .
                            $settings['icon_selector'] . "')  center no-repeat;";
                        $css .= 'background-size: cover;';
                        $css .= '}';
                    } else {
                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card .wplp-mh-post-image a:before {';
                        $css .= 'background-color: ' . $color . ';';
                        $css .= '}';

                        $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card:hover .wplp-mh-post-image a:before {';
                        $css .= 'background-color: transparent;';
                        $css .= '}';
                    }
                } else {
                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card .wplp-mh-post-image a:before {';
                    $css .= 'background-color: '. $color .';';
                    $css .= '}';

                    $css .= '#wplp_widget_' . $idWidget . ' .wplp_listposts .wplp-mh-post-card:hover .wplp-mh-post-image a:before {';
                    $css .= 'background-color: transparent;';
                    $css .= '}';
                }
            } elseif ($theme_classDashicon === ' portfolio') {
                if ($colorTheme === 'yes') {
                    $css .= '#wplp_widget_' . $idWidget .
                            ' .wplp_listposts{background-color: ' . $colorfull . '}';
                } else {
                    $colorfull = 'transparent';
                }
                $css .= '#wplp_widget_' . $idWidget .
                        ' .portfolio .wpcu-front-box.bottom .category::before{ background:' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget . ' .read-more{border-top:1px solid ' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts li{margin: ' .
                        ($width1 / 20) . '%; width: ' . ($width1 - ($width1 * 2 / 20)) . '%;}';
                $css .= '@media screen and (max-width: 640px) {#wplp_widget_' . $idWidget .
                        ' .wplp_listposts li {width: ' . ($width2 - ($width1 * 2 / 20)) . '% !important;}}';
                $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts {background: center center no-repeat ' . $colorfull .
                        ';background-color: ' . $colorfull . ';}';
            } elseif ($theme_classDashicon === ' smooth-effect') {
                $css .= '#wplp_widget_' . $idWidget .
                        ' li.smooth-effect:hover .wpcu-front-box div .title { border-top: 1px solid ' . $color . ';}';
                $css .= '#wplp_widget_' . $idWidget .
                        ' li.smooth-effect:hover .wpcu-front-box div .text { border-bottom: 1px solid ' . $color . ';}';
            } elseif ($theme_classDashicon === ' timeline') {
                if ($colorfull === 'rgba(255,255,255,1)') {
                    $innercolor = '#000';
                } else {
                    $innercolor = '#fff';
                }
                $css .= '#wplp_widget_' . $idWidget .
                        ' .wplp_listposts  .wpcu-front-box.top .img_cropper:before {color:' .
                        $innercolor . '; background:' . $colorfull . ';}';
            }
            // wp_add_inline_style($hanlde, $css);
            return $css;
        }

        return true;

        // if ($theme_classDashicon === ' masonry-category' || $theme_classDashicon === ' timeline') {
        //     wp_enqueue_style('dashicons');
        // }
    }

    /**
     * Change color to rgb format hex2rgba
     *
     * @param string  $color   Color style
     * @param boolean $opacity Opacity style
     *
     * @return string
     */
    private function hex2rgba($color, $opacity = false)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color)) {
            return $default;
        }
        //Sanitize $color if "#" is provided
        if ($color[0] === '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) === 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) === 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1) {
                $opacity = 1.0;
            }
            $output = 'rgba(' . implode(',', $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(',', $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}
