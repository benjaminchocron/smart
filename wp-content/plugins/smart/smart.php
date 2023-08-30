<?php
/**
 * @package Smart
 */
/*
Plugin Name: Smart
Plugin URI: https://playsmart.co.il/
Description: Create store via API.
Version: 1.0
Requires at least: 1.0
Requires PHP: 5.2
Author: Benjamin Chocron
Author URI: https://playsmart.co.il
License: GPLv2 or later
Text Domain: smart
*/

class Smart_Class {

    public function __construct()
    {
        require_once "api.php";
        add_action( "wp_ajax_smart_get_products", [ $smart_api, "get_products" ] );
        add_action( "wp_ajax_nopriv_smart_get_products", [ $smart_api, "get_products" ] );
        
        add_action( "wp_ajax_smart_get_product", [ $smart_api, "get_product" ] );
        add_action( "wp_ajax_nopriv_smart_get_product", [ $smart_api, "get_product" ] );
        
        add_action( "wp_ajax_smart_update_product_id", [ $smart_api, "update_product_id" ] );
        add_action( "wp_ajax_nopriv_smart_update_product_id", [ $smart_api, "update_product_id" ] );

        add_action( "wp_ajax_smart_get_cart_products", [ $smart_api, "get_cart_products" ] );
        add_action( "wp_ajax_nopriv_smart_get_cart_products", [ $smart_api, "get_cart_products" ] );

        add_action( "wp_ajax_smart_add_cart", [ $smart_api, "process_checkout" ] );
        add_action( "wp_ajax_nopriv_smart_add_cart", [ $smart_api, "process_checkout" ] );
    }
}

new Smart_Class;
