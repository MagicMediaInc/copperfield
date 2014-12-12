<?php
/**
 * Plugin Name: WooCommerce VEF Currency
 * Plugin URI: http://www.mebanearondeadsense.com/
 * Description: Adds VEF (Venezuelan Bolivar) currency in WooCommerce
 * Author: Leslie Mc Curdy (Based on a script of claudiosanches.com)
 * Author URI: http://www.mebanearondeadsense.com/
 * Version: 1.0
 * License: GPLv2 or later
 */

if ( !class_exists( 'WC_VEF_Currency' ) ) {

    /**
     * Add VER Currency in WooCommerce.
     */
    class WC_VEF_Currency {

        /**
         * Class construct.
         */
        public function __construct() {

            // Actions
            add_action( 'plugins_loaded', array( &$this, 'load_textdomain' ), 0 );

            // Filters.
            add_filter( 'woocommerce_currencies', array( &$this, 'add_currency' ) );
            add_filter( 'woocommerce_currency_symbol', array( &$this, 'currency_symbol' ), 1, 2 );
        }

        /**
         * Load Plugin textdomain.
         *
         * @return void.
         */
        public function load_textdomain() {
            load_plugin_textdomain( 'wcars', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        }

        /**
         * Add VEF Currency in WooCommerce.
         *
         * @param  array $currencies Current currencies.
         *
         * @return array             Currencies with VEF.
         */
        public function add_currency( $currencies ) {
            $currencies['VEF'] = __( 'Venezuelan Bol&iacute;var ', 'wcars' );
            asort( $currencies );

            return $currencies;
        }

        /**
         * Add VEF Symbol
         *
         * @param  string $currency_symbol Currency symbol.
         * @param  array  $currency        Current currencies.
         *
         * @return string                  VEF currency symbol.
         */
        public function currency_symbol( $currency_symbol, $currency ) {
            switch( $currency ) {
                case 'VEF':
                    $currency_symbol = 'Bs ';
                    break;
            }

            return $currency_symbol;
        }

    } // close WC_VEF_Currency class.

    $WC_VEF_Currency = new WC_VEF_Currency();
}
