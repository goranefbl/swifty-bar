<?php

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    sb_bar
 * @subpackage sb_bar/public
 * @author     Goran Jakovljevic <goranefbl@gmail.com>
 */
class sb_bar_Public {

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
	 * @var      string    $sb_bar       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $sb_bar, $version ) {

		$this->sb_bar = $sb_bar;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Check if plugin is disabled in options to remove css also.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);
		// Check Post Type to load it only on active Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}

		if(!isset($options["disable-bar"]) && is_singular() && in_array(get_post_type(), $posttype)) {
			wp_enqueue_style( $this->sb_bar, plugin_dir_url( __FILE__ ) . 'assets/css/sb-bar-public.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Check if plugin is disabled in options to remove css also.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);
		//Post Type
		$posttype = array();
		if(isset($options["post-type"]) && $options["post-type"] != '') {
			$posttype = $options["post-type"];
		}

		if(!isset($options["disable-bar"]) && is_singular() && in_array(get_post_type(), $posttype)) {
			wp_enqueue_script( $this->sb_bar, plugin_dir_url( __FILE__ ) . 'assets/js/sb-bar-public.js', array( 'jquery' ), $this->version, false );
		}
	}

	/**
	 * This will add divs before and after content to better calculate ttr line
	 *
	 * @since    1.0.0
	 */
	public function ttr_container( $content ) {
		$custom_content = '<div class="ttr_start"></div>';
		$custom_content .= $content;
		$custom_content .= '<div class="ttr_end"></div>';
		return $custom_content;
	}


	/**
	 * Including front end
	 *
	 * @since    1.0.0
	 */
	public function front_end() {
		// Check if plugin is disabled in options to remove css also.
		$options = (get_option('sb_bar_options') ? get_option('sb_bar_options') : false);

		if(!isset($options["disable-bar"])) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/sb-bar-public-display.php';
		}

	}


	/**
	 * Social 
	 *
	 * @since    1.0.6
	 */
	public function social_shares($post_id) {

	$shares = array();
	//$current_url = get_permalink($post_id);
	$current_url = 'http://blog.newswhip.com/index.php/2014/11/biggest-linkedin-publishers-october-2014#7HboYvm2TzxAkhUX.97'; //test
		
	$twitter = json_decode(file_get_contents('http://cdn.api.twitter.com/1/urls/count.json?url=' . $current_url), true);
	$fb = json_decode(file_get_contents('http://graph.facebook.com/?id=' . $current_url), true);
	
	//LinkedIn and Pinterest don't return clean JSON so we use regex
	$linkedin = file_get_contents('http://www.linkedin.com/countserv/count/share?url=' . $current_url);
	$linkedin = json_decode(preg_replace('/^IN\.Tags\.Share\.handleCount\((.*)$/s', "\\1", $linkedin), true);
	
	$pinterest = file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . $current_url);
	$pinterest = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest),true);
	
	$shares['twitter'] = $twitter['count'] == NULL ? 0 : $twitter['count'];
	$shares['fb'] = $fb['shares'] == NULL ? 0 : $fb['shares'];
	$shares['linkedin'] = $linkedin['count'] == NULL ? 0 : $linkedin['count'];
	$shares['pinterest'] = $pinterest['count'] == NULL ? 0 : $pinterest['count'];

	return $shares;
	}

}
