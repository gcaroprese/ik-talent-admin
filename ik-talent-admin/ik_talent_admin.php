<?php
/*
Plugin Name: Talents Admin
Description: System to manage, search and upload talents
Version: 2.1.13
Author: Gabriel Caroprese
Author URI: https://inforket.com/
Requires at least: 5.3
Requires PHP: 7.3
Text Domain: ik_talent_admin
Domain Path: /languages/
*/ 

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$ik_talent_locationDir = dirname( __FILE__ );
$ik_talent_locationPublicDir = plugin_dir_url(__FILE__ );
define( 'IK_TALENT_DIR', $ik_talent_locationDir);
define( 'IK_TALENT_PUBLIC', $ik_talent_locationPublicDir);

require_once($ik_talent_locationDir . '/include/init.php');
register_activation_hook( __FILE__, 'ik_talent_basic_config' );

//I add a text domain for translations
function ik_talent_textdomain_init() {
    load_plugin_textdomain( 'ik_talent_admin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'ik_talent_textdomain_init' );

?>