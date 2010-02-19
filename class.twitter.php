<?php

class Twitter {
	
	/**
	 * Authenticating Twitter user
	 * @var string
	 */
	public $username;
	
	/**
	 * Autenticating Twitter user password
	 * @var string
	 */
	public $password;
	
	/**
	 * Sets the URL to be used for the API requests
	 * @var string
	 */
	public $api_url;

	/**
	 * Recommend setting a user-agent so Twitter knows how to contact you inc case of abuse. Include your email
	 * @var string
	 */
	public $user_agent;

	/**
	 * Can be set to JSON (requires PHP 5.2 or the json pecl module) or XML - json|xml
	 * @var string
	 */
	public $type;

	/**
	 * It is unclear if Twitter header preferences are standardized, but I would suggest using them.
	 * More discussion at http://tinyurl.com/3xtx66
	 * @var array
	 */
	public $headers;
	
	/**
	 * @var boolean
	 */
	 public $suppress_response_code;
	 
	/**
	 * @var boolean
	 */
	 public $debug;
	
	/**
	 * @var string
	 */
	 public $timezone;
	
	/**
	 * Setup Twitter client connection details.
	 *
	 * @access public
	 * @since 2.0
	 * @param string $username Twitter username
	 * @param string $password Twitter password
	 * @param string $user_agent Unique identifying user agent that identifies the App you're building. Include an email address that Twitter can use to reach you
	 * @param array $headers Additional headers to be sent to Twitter as key/value pairs
	 * @param string $timezone Formatted like America/New_York
	 * @return Twitter
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York' )
	{		
		// Don't load BackPress if the class is used inside WordPress
		if( !class_exists('WP_Query') ) :
			require_once('inc/backpress/functions.core.php');
			require_once('inc/backpress/functions.formatting.php');
			require_once('inc/backpress/functions.bp-options.php');
			require_once('inc/backpress/functions.plugin-api.php');
			require_once('inc/backpress/class.wp-http.php');
			require_once('inc/backpress/class.wp-error.php');
		endif;
		
		$this->username = $username;
		$this->password = $password;
		$this->api_url = '';
		$this->user_agent = ( $user_agent ) ? $user_agent : 'php-twitter/1.x - To report abuse, contact ' . $_SERVER["SERVER_ADMIN"];
		$this->headers = ( $headers ) ? $headers : array('Authorization' => 'Basic '. base64_encode($username . ':' . $password),'Expect:' , 'X-Twitter-Client: ','X-Twitter-Client-Version: ','X-Twitter-Client-URL: ');
		$this->debug = ( $debug ) ? true : false;
		$this->suppress_response_code = false;
		$this->type = 'json';
		$this->timezone = date_default_timezone_set( $timezone );
		$this->http = new WP_Http();
	}
	
	/**
	 * Constructs a URL encoded query string from an array of key/value pairs
	 *
	 * @access protected
	 * @since 2.0
	 * @param array $array query string key/value pairs
	 * @return string
	 */
	protected function _glue( $array )
	{
	    $query_string = '';
	    foreach( $array as $key => $val ) :
	        $query_string .= $key . '=' . rawurlencode( $val ) . '&';
	    endforeach;
	    
	    return '?' . substr( $query_string, 0, strlen( $query_string )-1 );
	}
	
	/**
	 * Performs an HTTP GET request
	 *
	 * @access protected
	 * @since 2.0
	 * @param string $url URI resource.
	 * @return object
	 */
	protected function _get( $url )
	{
		$json = $this->http->get( $url, array( 'headers' => $this->headers, 'user-agent' => $this->user_agent ) );
		if( is_wp_error( $json ) )
			return $json;
		
		if( $json['headers']['status'] == '500 Internal Server Error')
			return $json;
		
		if( $json['body'] )
			return (object) json_decode( $json['body'] );
		else
			return $json;
	}
	
	/**
	 * Performs an HTTP POST request
	 *
	 * @access protected
	 * @since 2.0
	 * @param string $url URI resource.
	 * @param array $data POSTdata in key/value pairs
	 * @return object
	 */
	protected function _post( $url, $data = array() )
	{
		$json = $this->http->post( $url, array( 'headers' => $this->headers, 'user-agent' => $this->user_agent, 'body' => $data ) );
		if( is_wp_error( $json ) )
			return $json;
		
		if( $json['headers']['status'] == '500 Internal Server Error')
			return $json;
			
		return (object) json_decode( $json['body'] );
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
