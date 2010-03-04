<?php

class Twitter {
	
	/**
	 * OAuth Consumer Key
	 * @var string
	 */
	protected $consumerKey;
	
	/**
	 * OAuth Consumer Secret
	 * @var string
	 */
	protected $consumerSecret;
	
	/**
	 * OAuth Token
	 * @var string
	 */
	protected $token;
	
	/**
	 * OAuth Token Secret
	 * @var string
	 */
	protected $tokenSecret;
	
	/**
	 * OAuth Request URL
	 * @var string
	 */
	protected $requestTokenUrl;
	
	/**
	 * OAuth Access Token URL
	 * @var string
	 */
	protected $accessTokenUrl;
	
	/**
	 * OAuth Authorize URL
	 * @var string
	 */
	protected $authorizeUrl;
		
	/**
	 * Sets the URL to be used for the API requests
	 * @var string
	 */
	public $api_url;

	/**
	 * Recommend setting a user-agent so Twitter knows how to contact you inc case of abuse. Include your email
	 * @var string
	 */
	protected $userAgent;

	/**
	 * Can be set to JSON (requires PHP 5.2 or the json pecl module) or XML - json|xml
	 * @var string
	 */
	protected $type;

	/**
	 * It is unclear if Twitter header preferences are standardized, but I would suggest using them.
	 * More discussion at http://tinyurl.com/3xtx66
	 * @var array
	 */
	public $send_headers;
	
	/**
	 * @var boolean
	 */
	 public $suppress_response_code;
	 
	/**
	 * @var boolean
	 */
	 protected $debug;
	
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
	 * @param array $send_headers Additional headers to be sent to Twitter as key/value pairs
	 * @param string $timezone Formatted like America/New_York
	 * @return Twitter
	 */
	public function __construct( $consumerKey, $consumerSecret, $token = null, $tokenSecret = null, $user_agent = null, $send_headers = null, $timezone = 'America/New_York', $debug = false )
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
		
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
		$this->token = ( $token != null ) ? $token : '';
		$this->tokenSecret = ( $tokenSecret != null ) ? $tokenSecret : '';

		$this->api_url = '';
		$this->userAgent = ( $userAgent ) ? $userAgent : 'php-twitter/2.x - To report abuse, contact ' . $_SERVER["SERVER_ADMIN"];
		$this->send_headers = ( $send_headers ) ? $send_headers : array('Expect:' , 'X-Twitter-Client: ','X-Twitter-Client-Version: ','X-Twitter-Client-URL: ');
		$this->debug = ( $debug ) ? true : false;
		$this->suppress_response_code = false;
		$this->type = 'json';
		$this->timezone = date_default_timezone_set( $timezone );
		$this->http = new WP_Http();
		
		if( $this->oauth_token == '' )
		{
			require_once('inc/class.oauth.php');
			$oauth = new EpiTwitter( $this->consumerKey, $this->consumerSecret );
			//$oauth->userAgent = $this->user_agent;
			echo '<pre>';
			$oauth->getAuthorizationUrl();
			print_r($oauth);
			echo '</pre>';
		}
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
