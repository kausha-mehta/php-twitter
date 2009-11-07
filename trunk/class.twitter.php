<?php
class Twitter {
	
	/**
	 * Authenticating Twitter user
	 * @var string
	 */
	var $username;
	
	/**
	 * Autenticating Twitter user password
	 * @var string
	 */
	var $password;
	
	/**
	 * Sets the URL to be used for the API requests
	 * @var string
	 */
	var $api_url;

	/**
	 * Recommend setting a user-agent so Twitter knows how to contact you inc case of abuse. Include your email
	 * @var string
	 */
	var $user_agent;

	/**
	 * Can be set to JSON (requires PHP 5.2 or the json pecl module) or XML - json|xml
	 * @var string
	 */
	var $type;

	/**
	 * It is unclear if Twitter header preferences are standardized, but I would suggest using them.
	 * More discussion at http://tinyurl.com/3xtx66
	 * @var array
	 */
	var $headers;
	
	/**
	 * @var boolean
	 */
	 var $suppress_response_code;
	 
	/**
	 * @var boolean
	 */
	 var $debug;
	
	/**
	 * @var string
	 */
	 var $timezone;
	
	function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York', $debug = false )
	{		
		require_once('inc/backpress/functions.core.php');
		require_once('inc/backpress/functions.formatting.php');
		require_once('inc/backpress/functions.bp-options.php');
		require_once('inc/backpress/functions.plugin-api.php');
		require_once('inc/backpress/class.wp-http.php');
		require_once('inc/backpress/class.wp-error.php');
		
		$this->username = $username;
		$this->password = $password;
		$this->api_url = '';
		$this->user_agent = ( $user_agent ) ? $user_agent : 'php-twitter/1.x - To report abuse, contact ' . $_SERVER["SERVER_ADMIN"];
		$this->headers = ( $headers ) ? $headers : array('Expect:', 'X-Twitter-Client: ','X-Twitter-Client-Version: ','X-Twitter-Client-URL: ');
		$this->debug = ( $debug ) ? true : false;
		$this->suppress_response_code = false;
		$this->type = 'json';
		$this->timezone = date_default_timezone_set( $timezone );
		$this->http = new WP_Http();
	}
	
	function _glue( $array )
	{
	    $query_string = '';
	    foreach( $array as $key => $val ) :
	        $query_string .= $key . '=' . rawurlencode( $val ) . '&';
	    endforeach;
	    
	    return '?' . substr( $query_string, 0, strlen( $query_string )-1 );
	}
	
	function get( $url )
	{
		return $this->http->get( $url, array('user-agent'=>$this->user_agent) );
	}
	
	function post( $url, $data )
	{
		
	}
	
	function __destruct() {}
}