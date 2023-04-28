<?php
global $settings;
//phpcs:enable
$thumb_img_values = array(
    __('Use featured image', 'wp-latest-posts'),
    //'Use first attachment',
    __('Use first image', 'wp-latest-posts')
);

if (isset($settings['thumb_img'])) {
    $thumb_selected[$settings['thumb_img']] = ' checked="checked"';
}

$class_enabled_default = '';
$disabled_image_postion = '';
if (strpos($settings['theme'], 'masonry-category') !== false
    || strpos($settings['theme'], 'masonry') !== false
    || strpos($settings['theme'], 'material-vertical') !== false
    || strpos($settings['theme'], 'smooth-effect') !== false
    || strpos($settings['theme'], 'portfolio') !== false
    || strpos($settings['theme'], 'timeline') !== false
) {
    $class_enabled_default = ' disabled';
    $disabled_image_postion = "disabled = 'disabled'";
}

/**
 * Add an image option to load image with their full height
 **/
if (isset($settings['full_height'])) {
    $full_height_checked[$settings['full_height']] = ' checked="checked"';
}
?>
<div id="image-source-tab" class="tab-content">
    <div class="settings-wrapper">
        <h4 class="tooltip" title="<?php esc_html_e('Select the image source to load in the new block', 'wp-latest-posts') ?>"><?php esc_html_e('Images Source', 'wp-latest-posts') ?></h4>
        <div class="use-image settings-wrapper-field">
            <ul class="un-craft">
                <?php foreach ($thumb_img_values as $k => $v) : ?>
                    <li class="column-img-source">
                        <input type="radio" name="wplp_thumb_img" id="thumb_img<?php echo esc_html($k) ?>" value="<?php echo esc_html($k) ?>" class="ju-radiobox"
                            <?php echo (isset($thumb_selected[$k]) ?  esc_attr($thumb_selected[$k]) : '') ?>
                        />
                        <label for="thumb_img<?php echo esc_html($k) ?>" class="radio-label"><?php echo esc_html($v) ?></label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <hr>
        <div class="image-property settings-wrapper-field">
            <div class="image-size float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Select the image size that will be loaded on the fontend', 'wp-latest-posts') ?>"><?php esc_html_e('Image size', 'wp-latest-posts') ?></label>
                <select id="wplp_imageSize" name="wplp_image_size" style="width: 50%" class="wplp-font-style">
                    <?php
                    $sizes_list = array(
                        'automatic' => esc_html__('Automatic', 'wp-latest-posts'),
                        'thumbnailSize' => esc_html__('Thumbnail', 'wp-latest-posts'),
                        'mediumSize' => esc_html__('Medium', 'wp-latest-posts'),
                        'largeSize' => esc_html__('Large', 'wp-latest-posts'),
                    );
                    global $_wp_additional_image_sizes;
                    foreach ($_wp_additional_image_sizes as $image_size => $size_value) {
                        $sizes_list[$image_size] = $image_size;
                    }

                    foreach ($sizes_list as $size_name => $size_label) {
                        echo '<option value="'. esc_attr($size_name) .'" '. (selected($settings['image_size'], $size_name, false)) .'>'. esc_html($size_label) .'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr>
        <div class="image-ratio settings-wrapper-field">
            <div class="image-size float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Select the aspect ratio of the news images', 'wp-latest-posts') ?>"><?php esc_html_e('Aspect ratio', 'wp-latest-posts') ?></label>
                <select id="wplp_aspect_ratio" name="wplp_aspect_ratio" style="width: 50%" class="wplp-font-style">
                    <?php
                    if (!isset($settings['aspect_ratio'])) {
                        $settings['aspect_ratio'] = '4_3';
                    }
                    $aspect_ratio_list = array(
                        '1_1' => '1:1',
                        '3_2' => '3:2',
                        '2_3' => '2:3',
                        '4_3' => '4:3',
                        '3_4' => '3:4',
                        '16_9' => '16:9',
                        '9_16' => '9:16',
                        '21_9' => '21:9',
                        '9_21' => '9:21'
                    );
                    foreach ($aspect_ratio_list as $aspect_ratio_name => $aspect_ratio_label) {
                        echo '<option value="'. esc_attr($aspect_ratio_name) .'" '. (selected($settings['aspect_ratio'], $aspect_ratio_name, false)) .'>'. esc_html($aspect_ratio_label) .'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr>
        <div id="margin_sliders" class="margin-image settings-wrapper-field">
            <div class="margin-left margin-block float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Define the left margin in pixels to apply on the image', 'wp-latest-posts') ?>"><?php esc_html_e('Margin left', 'wp-latest-posts') ?></label>
                <span id="slider_margin_left" class="margin-slider"></span>
                <input id="margin_left" type="text" name="wplp_margin_left"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['margin_left']) ? $settings['margin_left'] : '0')) ?>"
                       class="wplp-short-text wplp-font-style" />
            </div>
            <div class="margin-top margin-block float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Define the top margin in pixels to apply on the image', 'wp-latest-posts') ?>"><?php esc_html_e('Margin top', 'wp-latest-posts') ?></label>
                <span id="slider_margin_top" class="margin-slider"></span><input id="margin_top" type="text" name="wplp_margin_top"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['margin_top']) ? $settings['margin_top'] : '0')) ?>"
                       class="wplp-short-text wplp-font-style" />
            </div>
            <div class="margin-right margin-block float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Define the right margin in pixels to apply on the image', 'wp-latest-posts') ?>"><?php esc_html_e('Margin right', 'wp-latest-posts') ?></label>
                <span id="slider_margin_right" class="margin-slider"></span><input id="margin_right" type="text" name="wplp_margin_right"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['margin_right']) ? $settings['margin_right'] : '4')) ?>"
                       class="wplp-short-text wplp-font-style" />
            </div>
            <div class="margin-bottom margin-block float half-width">
                <label class="settings-wrapper-title tooltip" title="<?php esc_html_e('Define the bottom margin in pixels to apply on the image', 'wp-latest-posts') ?>"><?php esc_html_e('Margin bottom', 'wp-latest-posts') ?></label>
                <span id="slider_margin_bottom" class="margin-slider"></span>
                <input id="margin_bottom" type="text" name="wplp_margin_bottom"
                       value="<?php echo esc_html(htmlspecialchars(isset($settings['margin_bottom']) ? $settings['margin_bottom'] : '4')) ?>"
                       class="wplp-short-text wplp-font-style" />
            </div>
            <div class="clearfix"></div>
        </div>
        <?php
        if (class_exists('WPLPAddonAdmin')) {
            do_action('wplp_addon_advanced_display_default_image', $settings);
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('.margin-slider').slider({
                min: 0,
                max: 50,
                slide: function (event, ui) {
                    field = event.target.id.substr(7);
                    $("#" + field).val(ui.value);
                }
            });
            $('.margin-slider').each(function () {
                var field = this.id.substr(7);
                $(this).slider({
                    min: 0,
                    max: 50,
                    value: $("#" + field).val(),
                    slide: function (event, ui) {
                        $("#" + field).val(ui.value);
                    }
                });
            });
            $('#margin_sliders input').change(function () {
                $('#slider_' + this.id).slider('value', $(this).val());
            });
        });
    })(jQuery);
</script>
