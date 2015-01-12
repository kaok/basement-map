<?php
/**
 * Plugin Name: Basement Map
 * Plugin URI: http://aisconverse.com
 * Description: A Basement Framework map plugin
 * Version: 1.0
 * Author: Aisconverse team
 * Author URI: http://aisconverse.com
 * License: GPL2
 */

defined('ABSPATH') or die();

add_action( 'basement_loaded', 'basement_map_init', 999 );

function basement_map_init() {
	require 'modules/map/map.php';
}