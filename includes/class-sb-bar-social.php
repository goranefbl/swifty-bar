<?php

/**
 * Social sharing class.
 *
 * @package    sb_bar
 * @subpackage sb_bar/public
 * @author     Danijel Predojevic <predojevic.danijel@gmail.com>
 */
class sb_bar_Social {

	public $post_id; 

	protected $current_url;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $sb_bar    The ID of this plugin.
	 */
	private $sb_bar;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The name of the transient settings
	 *
	 * @since    1.0.6
	 * @access   private
	 * @var      string    $transient_name    The name of the transient settings
	 */
	private $transient_name;


	function __construct($post_id) {

		$this->current_url = get_permalink($post_id);
		//$this->current_url = 'http://www.phptherightway.com/pages/Design-Patterns.html'; //test

		$this->transient_name = 'sb_bar_' . $post_id . '_shares';
		$this->post_id = $post_id;


	}


	public function get_shares_all() {

		$networks = array('twitter', 'facebook', 'linkedin', 'pinterest', 'googleplus');

		$shares = $this->get_post_transient($this->transient_name);

		if(!is_array($shares)) {
			
			foreach($networks as $network) {
				$shares[$network] = $this->{get_shares_.$network}($this->post_id);	
			}
			$this->set_post_transient($shares, $this->post_id);
		}
	
		return $shares;

	}

	public function get_shares_twitter($post_id) {

		$twitter = wp_remote_get('http://cdn.api.twitter.com/1/urls/count.json?url=' . $this->current_url);
		if($twitter['response']['code'] === 200) {
			
			$twitter = json_decode($twitter['body'], true);
			$twitter = $twitter['count'] == NULL ? 0 : $twitter['count'];
		}
		else $twitter = 0;
		
		return $twitter;
	}

	public function get_shares_facebook($post_id) {
		
		$facebook = wp_remote_get('http://graph.facebook.com/?id=' . $this->current_url);
		if($facebook['response']['code'] === 200) {
			
			$facebook = json_decode($facebook['body'], true);
			$facebook = $facebook['shares'] == NULL ? 0 : $facebook['shares'];
		}
		else $facebook = 0;
		
		return $facebook;
	}

	public function get_shares_linkedin($post_id) {
		
		//LinkedIn  doesn't return clean JSON so we use regex
		$linkedin = wp_remote_get('http://www.linkedin.com/countserv/count/share?url=' . $this->current_url);
		if ($linkedin['response']['code'] == 200) {
			
			$linkedin = json_decode(preg_replace('/^IN\.Tags\.Share\.handleCount\((.*)$/s', "\\1", $linkedin['body']), true);
			$linkedin = $linkedin['count'] == NULL ? 0 : $linkedin['count'];
		}
		else $linkedin = 0;

		return $linkedin;

	}

	public function get_shares_pinterest($post_id) { 

		//Pinterest  doesn't return clean JSON so we use regex
		$pinterest = wp_remote_get('http://api.pinterest.com/v1/urls/count.json?url=' . $this->current_url);
		if($pinterest['response']['code'] === 200) {
			
			$pinterest = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest['body']),true);
			$pinterest = $pinterest['count'] == NULL ? 0 : $pinterest['count'];
		}
		else $pinterest = 0;
		
		return $pinterest;
	
	}

	public function get_shares_googleplus($post_id) {

		$data = array(
			'method' => 'pos.plusones.get', 
			'id' => 'p', 
			'params' => array(
				'nolog' => true, 
				'id' => $this->current_url, 
				'source' => 'widget', 
				'userId' => '@viewer', 
				'groupId' => '@self'
			), 
			'jsonrpc' => '2.0', 
			'key' => 'p', 
			'apiVersion' => 'v1'
		);
		
		$googleplus = wp_remote_post(
			'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', array(
				'method' => 'POST', 
				'headers'  => "Content-type: application/json\r\n",
				'body' => json_encode($data)
			)
		);
		
		if($googleplus['response']['code'] === 200) {
			
			$googleplus = json_decode($googleplus['body'],true);
			$googleplus = $googleplus['result']['metadata']['globalCounts']['count'];
		}
		else $googleplus = 0;

		return $googleplus;
	
	}

	/**
	 * Format the share count number to eg. 1k, 2.2k etc. 
	 *
	 * @since    1.0.6
	 */
	public function share_count_pretty($ugly_count) {

		$count = (int)$ugly_count;

		if($count < 1000) 
			return $count;

		else {
			$count = round($count / 1000, 1) .'k';
		}
		
		return $count;
	}


	private function get_post_transient($transient_name) {

		$transient = get_transient($transient_name);
		if(!empty($transient)) 
			return $transient;
		else 
			return FALSE;

	}

	private function set_post_transient($new_transient) {

		set_transient($this->transient_name, $new_transient, 1);
	}

}
