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
					
					function get_cart_weight() {
						global $woocommerce;
						$cart_weight = 0;
					
						foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
							$product = $cart_item['data'];
							$weight = floatval( $product->get_weight() );
							$quantity = intval( $cart_item['quantity'] );
					
							$cart_weight += ( $weight * $quantity );
						}
					
						return $cart_weight;
					}

					function calculate_shipping_cost( $weight ) {
						$shipping_cost = 0;
					
						// Kolla om vikten är större än noll och lägg till en fraktkostnad beroende på viktintervallet
						if ( $weight > 0 && $weight <= 10 ) {
							$shipping_cost = 50;
						} elseif ( $weight > 10 && $weight <= 30 ) {
							$shipping_cost = 100;
						} elseif ( $weight > 68 ) {
							$shipping_cost = 150;
						}
					
						return $shipping_cost;
					}

					function add_shipping_cost_to_cart() {
						$weight = get_cart_weight();
						$shipping_cost = calculate_shipping_cost( $weight );
					
						// Lägg till fraktkostnaden till kundvagnen
						if ( $shipping_cost > 0 ) {
							WC()->cart->add_fee( 'Pris baserat på vikt', $shipping_cost );
						}
					}

					function get_cart_shipping_classes() {
						global $woocommerce;
						$cart_shipping_classes = array();
					
						foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
							$product = $cart_item['data'];
							$shipping_class = $product->get_shipping_class();
							$quantity = intval( $cart_item['quantity'] );
					
							// Lägg till antalet produkter i varje fraktklass
							if ( isset( $cart_shipping_classes[ $shipping_class ] ) ) {
								$cart_shipping_classes[ $shipping_class ] += $quantity;
							} else {
								$cart_shipping_classes[ $shipping_class ] = $quantity;
							}
						}
					
						return $cart_shipping_classes;
					}

					function calculate_shipping_cost_classes( $shipping_classes ) {
						$shipping_cost = 0;
					
						// Lägg till fraktkostnader för varje fraktklass
						// För att lägga fraktklasser, hämta slug från inställningarna
						foreach ( $shipping_classes as $shipping_class => $quantity ) {
							if ( $shipping_class == 'test' ) {
								$shipping_cost += 10 * $quantity;
							} elseif ( $shipping_class == 'test-2' ) {
								$shipping_cost += 30 * $quantity;
								continue;
							} elseif ( $shipping_class == 'test-3' ) {
								$shipping_cost += 50 * $quantity;
							}
						}
					
						return $shipping_cost;
					}

					function add_shipping_cost_to_cart_classes() {
						$shipping_classes = get_cart_shipping_classes();
						$shipping_cost = calculate_shipping_cost_classes( $shipping_classes );
					
						// Lägg till fraktkostnaden till kundvagnen
						if ( $shipping_cost > 0 ) {
							WC()->cart->add_fee( 'Pris baserat på fraktklass', $shipping_cost );
						}
					}

					$chosen_shipping_method_id = WC()->session->get( 'chosen_shipping_methods' )[0];
    				$chosen_shipping_method = explode(':', $chosen_shipping_method_id)[0];
					if($chosen_shipping_method == 'your_shipping_method') {
						add_action( 'woocommerce_cart_calculate_fees', 'add_shipping_cost_to_cart' );
						add_action( 'woocommerce_cart_calculate_fees', 'add_shipping_cost_to_cart_classes' );
						add_action( 'woocommerce_cart_calculate_fees', 'add_custom_shipping_method_city' );
					}
	
					function add_custom_shipping_method_city( $cart ) {
						// Hämta kundvagnens stad
						$city = WC()->customer->get_shipping_city();
						
						if ( $city === 'Stockholm') {
							$shipping_cost = 100;
							$cart->add_fee( 'Stockholm pris', $shipping_cost, true );
						} elseif ( $city === 'Malmö' ) {
							$shipping_cost = 150;
							$cart->add_fee( 'Malmö pris', $shipping_cost, true );
						} elseif ( $city === 'Göteborg' ) {
							$shipping_cost = 120;
							$cart->add_fee( 'Göteborg pris', $shipping_cost, true );
						}
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