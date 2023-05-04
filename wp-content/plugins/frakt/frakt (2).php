<?php
/*
Plugin Name: Drone Shipping
Description: Drone Shipping description
Version: 1.0.0
Author: Grupp 3
*/

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function your_shipping_method_init() {
		if ( ! class_exists( 'WC_Your_Shipping_Method' ) ) {
			class WC_Your_Shipping_Method extends WC_Shipping_Method {
				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				
				public function __construct() {
					$this->id                 = 'your_shipping_method'; // Id for your shipping method. Should be uunique.
					$this->method_title       = __( 'Drone Shipping Method' );  // Title shown in admin
					$this->method_description = __( 'Description of your shipping method' ); // Description shown in admin
					// $this->cost = get_option( 'frakt_med_dronare_cost' );

					$this->init();
				}

				/**
				 * Init your settings
				 *
				 * @access public
				 * @return void
				 */
				function init() {
					// Load the settings API
					$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
					$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
					
        			// Ställ in namn på leveransmetoden som visas för kunderna.
        			$this->title = $this->get_option( 'title' );
        			$this->enabled = $this->get_option( 'enabled' );
					// Ställ in beskrivning av leveransmetoden som visas för kunderna.
					$this->method_description = $this->get_option( 'description' );
					// Save settings in admin if you have any defined
					$this->form_fields = array(
						'enabled' => array(
							'title'   => __( 'Aktivera/Avaktivera', 'woocommerce' ),
							'type'    => 'checkbox',
							'label'   => __( 'Aktivera Frakt med drönare', 'woocommerce' ),
							'default' => 'no',
						),
						'title' => array(
							'title'       => __( 'Namn', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'Namn på leveransmetoden.', 'woocommerce' ),
							'default'     => __( 'Frakt med drönare', 'woocommerce' ),
							'desc_tip'    => true,
						),
						'description' => array(
							'title'       => __( 'Beskrivning', 'woocommerce' ),
							'type'        => 'textarea',
							'description' => __( 'Beskrivning av leveransmetoden.', 'woocommerce' ),
							'default'     => __( 'Leverans med frakt med drönare.', 'woocommerce' ),
							'desc_tip'    => true,
						),
						'cost' => array(
							'title'       => __( 'Pris', 'woocommerce' ),
							'type'        => 'text',
							'description' => __( 'Priset för leveransmetoden.', 'woocommerce' ),
							'default' => '10',
							'desc_tip' => true,
							),
							);
							
					add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
				}

				/*** Visa inställningsfält i WooCommerce admin. */
				public function init_form_fields() {
					
							}

				/**
				 * calculate_shipping function.
				 *
				 * @access public
				 * @param array $package
				 * @return void
				 */
				public function calculate_shipping( $package = array() ) {
					
				if ( ! function_exists( 'wpf_wc_add_cart_fees_by_cart_total_weight' ) ) {
    				/**
     				* wpf_wc_add_cart_fees_by_cart_total_weight.
     				*/
    				function wpf_wc_add_cart_fees_by_cart_total_weight( $cart ) {
        				$weight = $cart->get_cart_contents_weight();

						$name      = 'Weight fee';
						$amount    = 0;
        				
						if ( $weight <= 30 ) {
            				$amount    = 50;
        				} elseif ($weight > 31) {
							$amount    = 100;
						}

						$cart->add_fee( $name, $amount );
    				}
				}

				

				if ( ! function_exists( 'wpf_wc_add_cart_fees_by_shipping_class' ) ) {
					function wpf_wc_add_cart_fees_by_shipping_class( $cart ) {
						// Define the shipping classes and fees
						$shipping_classes = array(
							'test' => 10,
							'test-1' => 20,
							'test-2' => 30,
						);
				
						// Loop through cart items to get shipping classes and quantities
						$total_fees = array();
						foreach( $cart->get_cart() as $cart_item ) {
							$product_shipping_class = $cart_item['data']->get_shipping_class();
							$product_quantity = $cart_item['quantity'];
				
							if ( array_key_exists( $product_shipping_class, $shipping_classes ) ) {
								$shipping_class_fee = $shipping_classes[ $product_shipping_class ] * $product_quantity;
								$total_fees[] = $shipping_class_fee;
							}
						}
				
						// Calculate the total fee for all shipping classes
						$total_fee = array_sum( $total_fees );
				
						// Add the fee to the cart
						if ( $total_fee > 0 ) {
							$name = 'Shipping class fee';
							$amount = $total_fee;
							$cart->add_fee( $name, $amount );
						}
					}
				}

				if ( ! function_exists( 'wpf_wc_add_cart_fees_by_city' ) ) {
    				/**
     				* wpf_wc_add_cart_fees_by_city.
     				*/
					 
    				function wpf_wc_add_cart_fees_by_city( $cart ) {
        				$city = WC()->customer->get_shipping_city();

						$name      = 'City fee';
            			$amount    = 0;
    
        				if ( $city === 'Stockholm' ) {
            				$amount    = 80;
        				} elseif ($city === 'Göteborg') {
							$amount    = 100;
						} elseif ($city === 'Malmö') {
							$amount    = 120;
						} else {
							$amount    = 200;
						}

						$cart->add_fee( $name, $amount );
    				}
				}

					$chosen_shipping_method_id = WC()->session->get( 'chosen_shipping_methods' )[0];
    				$chosen_shipping_method = explode(':', $chosen_shipping_method_id)[0];
					if($chosen_shipping_method == 'your_shipping_method') {
						add_action( 'woocommerce_cart_calculate_fees', 'wpf_wc_add_cart_fees_by_cart_total_weight' );
						add_action( 'woocommerce_cart_calculate_fees', 'wpf_wc_add_cart_fees_by_city' );
						add_action( 'woocommerce_cart_calculate_fees', 'wpf_wc_add_cart_fees_by_shipping_class' );
					}
			
					$rate = array(
						'label' => $this->title,
						'cost' => $this->settings['cost'],
						'calc_tax' => 'per_item'
					);

					// Register the rate
					$this->add_rate( $rate );

				}

			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'your_shipping_method_init' );

	function add_your_shipping_method( $methods ) {
		$methods['your_shipping_method'] = 'WC_Your_Shipping_Method';
		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_your_shipping_method' );
}