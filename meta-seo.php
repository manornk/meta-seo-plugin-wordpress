<?php
   /*
   Plugin Name: META SEO
   Description: This plugin adds meta description tags on posts and pages based on your content.
   Version: 0.1
   Author: Rajko Orman
   Author URI: http://manornk.me
   License: GPL2
   */


define( 'MANORNK_METASEO__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
require_once( MANORNK_METASEO__PLUGIN_DIR . 'class.metaseo.php' );

add_action( 'init', array( 'ManornkMetaSeo', 'init' ));

?>
