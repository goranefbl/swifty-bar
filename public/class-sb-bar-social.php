<?php

/**
 * Social sharing class.
 *
 * 
 * 
 *
 * @package    sb_bar
 * @subpackage sb_bar/public
 * @author     Danijel Predojevic <predojevic.danijel@gmail.com>
 */
class sb_bar_Social {


	public $shares = array();
	public $post_id; 

	protected $current_url;

	function __construct() {
		//$this->current_url = get_permalink($post_id);
		$this->current_url = 'http://techcrunch.com/2015/08/15/navigating-the-new-waters-of-fundraising'; //test
	}


	public function get_shares_all($post_id) {

		$networks = array('twitter', 'facebook', 'linkedin', 'pinterest', 'googleplus');

		foreach($networks as $network) {
			if(get_transient('sb_bar_option_' . $network .'_shares') == NULL) {
				$this->shares[$network] = $this->{get_shares_.$network}($post_id);
				set_transient('sb_bar_option_'.$network.'_shares', $this->shares[$network], 60*60);
			}
			else $this->shares[$network] = (int)get_transient('sb_bar_option_'.$network.'_shares');
		
		}
		return $this->shares;

	}

	public function get_shares_twitter($post_id) {

		$twitter = json_decode(file_get_contents('http://cdn.api.twitter.com/1/urls/count.json?url=' . $this->current_url), true);
		$twitter = $twitter['count'] == NULL ? 0 : $twitter['count'];

		return $twitter;
	}

	public function get_shares_facebook($post_id) {
		
		$facebook = json_decode(file_get_contents('http://graph.facebook.com/?id=' . $this->current_url), true);
		$facebook = $facebook['shares'] == NULL ? 0 : $facebook['shares'];
		
		return $facebook;
	}

	public function get_shares_linkedin($post_id) {
		//LinkedIn  doesn't return clean JSON so we use regex
		$linkedin = file_get_contents('http://www.linkedin.com/countserv/count/share?url=' . $this->current_url);
		$linkedin = json_decode(preg_replace('/^IN\.Tags\.Share\.handleCount\((.*)$/s', "\\1", $linkedin), true);
		$linkedin = $linkedin['count'] == NULL ? 0 : $linkedin['count'];

		return $linkedin;

	}

	public function get_shares_pinterest($post_id) { 

		$pinterest = file_get_contents('http://api.pinterest.com/v1/urls/count.json?url=' . $this->current_url);
		$pinterest = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1", $pinterest),true);
		$pinterest = $pinterest['count'] == NULL ? 0 : $pinterest['count'];

		return $pinterest;
	
	}

	public function get_shares_googleplus($post_id) {

		$data = array('method' => 'pos.plusones.get', 'id' => 'p', 'params' => array('nolog' => true, 'id' => $this->current_url, 'source' => 'widget', 'userId' => '@viewer', 'groupId' => '@self'), 'jsonrpc' => '2.0', 'key' => 'p', 'apiVersion' => 'v1');

		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/json\r\n",
		        'method'  => 'POST',
		        'content' => json_encode($data),
		    ),
		);

		$context  = stream_context_create($options);
		$googleplus = json_decode(file_get_contents('https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ', false, $context),true);
		$googleplus = $googleplus['result']['metadata']['globalCounts']['count'];

		return $googleplus;
	
	}

	/**
	 * Format the share count number to eg. 1k, 2.2k etc. 
	 *
	 * @since    1.0.6
	 */
	public function share_count_pretty($ugly_count) {

		$count = (int)$ugly_count;

		if($count < 1000) return $count;

		else {
			$count = round($count / 1000, 1) .'k';
		}
		
		return $count;
	}


	private function get_post_transient($transient) {

		$transient = get_transient('');
		if(!empty($transient)) 
			return $transient;
		else 
			return FALSE;

	}

	private function set_post_transient($new_transient) {


	}

}
