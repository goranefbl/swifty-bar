<?php

/**
 * Global Part of Plugin, dashboard and options.
 *
 * @package    sb_bar
 * @subpackage sb_bar/admin
 */
class sb_bar_Global {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $sb_bar       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar, $version ) {

		$this->sb_bar = $sb_bar;
		$this->version = $version;

	}

	/**
	 * Register new image size
	 *
	 * @since    1.0.0
	 */
	public function add_image_size() {
		
		add_image_size( 'sb_image_size', 205, 155, true );
	
	}

}

?>