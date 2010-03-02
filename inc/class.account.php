<?php
/**
 * Twitter_Account_Name
 *
 * @package php-twitter 2.0
 * @subpackage users
 * @author Aaron Brazell
 **/

class Twitter_Account_Name extends Twitter {
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Account_Name
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York')
	{
		parent::__construct($username, $password, $user_agent, $headers, $timezone);
	}

	/**
	 * Verify whether supplied Twitter credentials are correct. Not rate-limited.
	 *
	 * @access public
	 * @since 2.0
	 * @return boolean
	 */
	public function verify_account()
	{
		$this->api_url = 'http://twitter.com/account/verify_credentials.' . $this->type;
		if( is_wp_error( $this->_get( $this->api_url ) ) )
			return false;
		
		return true;
	}
	
	/**
	 * Return information about the authenticating users rate limit status
	 *
	 * @access public
	 * @since 2.0
	 * @return object
	 **/
	public function ratelimit()
	{
		$this->api_url = 'http://twitter.com/account/rate_limit_status.' . $this->type;
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Ends authenticating user session. 
	 *
	 * @access public
	 * @since 2.0
	 * @return boolean
	 **/
	public function end()
	{
		$this->api_url = 'http://twitter.com/account/end_session.' . $this->type;
		if( $this->_get( $this->api_url ) == null )
			return true;
			
		return false;
	}
	
	/**
	 * Update message delivery device. Limited to SMS at this time
	 *
	 * @access public
	 * @since 2.0
	 * @param string $device_type. Required. 'sms' or 'none'. Defaults to 'none'
	 * @return object
	 **/
	public function update_device( $device_type = 'none' )
	{
		if( !in_array( $device_type, array('sms', 'none') ) )
			return false;
			
		$data = array();
		$data['device'] = $device_type;
		
		$this->api_url = 'http://twitter.com/account/update_delivery_device.' . $this->type . $this->_glue( $data );
		return $this->_post( $this->api_url );
	}
	
	/**
	 * Update profile colors for authenticating user
	 *
	 * @access public
	 * @since 2.0
	 * @param array $args. Optional. Pass 3 charachter or 6 charachter hex color codes for each optional parameter
	 *  - profile_background_color
	 *  - profile_text_color
	 *  - profile_link_color
	 *  - profile_sidebar_color
	 *  - profile_sidebar_border_color
	 * @return object
	 **/
	public function update_colors( $args = array() )
	{
		$defaults = array(
			'profile_text_color' 			=> '#000000'
			'profile_sidebar_border_color'	=> '#000'
			);
		$args = wp_parse_args( $default, $args );
		
		$this->api_url = 'http://twitter.com/account/update_profile_colors.' . $this->type;
		return $this->_post( $this->api_url, $args );
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