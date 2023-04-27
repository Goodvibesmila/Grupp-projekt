<?php

/**
 * Woo_Swish_Log class
 *
 * @class        Woo_Swish_Log
 * @since        1.0.0
 * @package      Woocommerce_Swish/Classes
 * @category     Logs
 * @author       BjornTech
 */

defined('ABSPATH') || exit;

class Woo_Swish_Log
{

    /* The domain handler used to name the log */
    private $_domain = 'woo-swish-e-commerce';

    /* The WC_Logger instance */
    private $_logger;

    /* Info if messages should be written to file or not */
    private $_silent;

    /**
     * __construct.
     *
     * @access public
     * @param  bool
     * @return void
     */
    public function __construct($silent = false)
    {
        $this->_silent = $silent;
        if (!$this->_silent) {
            $this->_logger = new WC_Logger();
        }
    }

    /**
     * separator function.
     *
     * Inserts a separation line for better overview in the logs.
     *
     * @access public
     * @return void
     */
    public function separator()
    {
        $this->add('--------------------');
    }

    /**
     * add function.
     *
     * Uses the build in logging method in WooCommerce.
     * Logs are available inside the System status tab
     *
     * @access public
     * @param  string|array|object
     * @return void
     */
    public function add($param)
    {
        if (!$this->_silent) {
            if (is_array($param)) {
                $param = print_r($param, true);
            }
            $this->_logger->add($this->_domain, $param);
        }
    }

}
