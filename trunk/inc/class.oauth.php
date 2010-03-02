<?php
/**
 * Twitter_OAuth
 *
 * @package php-twitter 2.0
 * @subpackage users
 * @author Aaron Brazell
 **/

require('inc/oauth/oauth.php');

class Twitter_OAuth extends EpiTwitter {
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Lists
	 */
	public function __construct( $oauth_consumer_key, $oauth_consumer_secret, $oauth_token = null, $oauth_token_secret = null, $user_agent = null, $headers = null, $timezone = 'America/New_York', $debug = false )
	{
		parent::__construct($oauth_consumer_key, $oauth_consumer_secret, $oauth_token, $oauth_token_secret, $user_agent, $headers, $timezone, $debug);
	}

	public function create_auth_link()
	{
		$this->api_url = 'http://twitter.com/oauth/request_token';
	}
	
	/**
	 * Destroys the object
	 *
	 * @access public
	 * @since 2.0
	 * @return null
	 */
	public function __destruct() {}
}