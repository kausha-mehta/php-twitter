<?php
/**
 * Twitter_Account_Name
 *
 * @package php-twitter 2.0
 * @subpackage users
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
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
	 * Destroys the object
	 *
	 * @access public
	 * @since 2.0
	 * @return null
	 */
	public function __destruct() {}
}