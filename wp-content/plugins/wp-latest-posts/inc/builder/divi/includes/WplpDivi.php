<?php

/**
 * Class WplpDivi
 */
class WplpDiviModule extends DiviExtension
{
    /**
     * The gettext domain for the extension's translations.
     *
     * @var string
     */
    public $gettext_domain = 'wplp-divi';

    /**
     * The extension's WP Plugin name.
     *
     * @var string
     */
    public $name = 'wplp-divi';

    /**
     * The extension's version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Wplp_Divi constructor.
     *
     * @param string $name Name extension
     * @param array  $args Parameter
     */
    public function __construct($name = 'wplp-divi', $args = array())
    {
        $this->plugin_dir     = plugin_dir_path(__FILE__);
        $this->plugin_dir_url = plugin_dir_url($this->plugin_dir);

        parent::__construct($name, $args);
    }
}

new WplpDiviModule;
