<?php

/*
Plugin Name: Image Sizes Panel
Plugin URI: https://github.com/benhuson/image-sizes-panel
Description: Display a meta box when viewing a media item in the admin that display all generated images sizes.
Author: Husani Oakley, Ben Huson
Author URI: https://github.com/benhuson/image-sizes-panel
Version: 0.1
License: GPLv2
*/

// Version
define( 'IMAGE_SIZES_PANEL_VERSION', '0.1' );
define( 'IMAGE_SIZES_PANEL_DB_VERSION', '0.1' );

// Paths
define( 'IMAGE_SIZES_PANEL_BASENAME', plugin_basename( __FILE__ ) );
define( 'IMAGE_SIZES_PANEL_SUBDIR', '/' . str_replace( basename( __FILE__ ), '', IMAGE_SIZES_PANEL_BASENAME ) );
define( 'IMAGE_SIZES_PANEL_URL', plugins_url( IMAGE_SIZES_PANEL_SUBDIR ) );
define( 'IMAGE_SIZES_PANEL_DIR', plugin_dir_path( __FILE__ ) );

// Constants
define( 'IMAGE_SIZES_PANEL_TEXTDOMAIN', 'image-sizes-panel' );

if ( is_admin() ) {
	include( IMAGE_SIZES_PANEL_DIR . '/admin/admin.php' );
}
