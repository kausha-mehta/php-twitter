<?php
/**
 * Twitter Users
 *
 * @package php-twitter 2.0
 * @subpackage users
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Users extends Twitter {
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Users
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York')
	{
		parent::__construct($username, $password, $user_agent, $headers, $timezone);
	}
	
	/**
	 * Retrieve data about a specified user.
	 *
	 * @access public
	 * @since 2.0
	 * @param string/int $user_id The Twitter screen name or ID of a specified user
	 * @return object
	 */
	public function show_user( $user_id )
	{
		if( is_int( $user_id ) )
			$qs = array( 'user_id' => $user_id );
		else
			$qs = array( 'screen_name' => $user_id );
		$this->api_url = 'http://twitter.com/users/show.' . $this->type . $this->_glue( $qs );
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Retrieve data about a specified user.
	 *
	 * @access public
	 * @since 2.0
	 * @param string/int $user_id The Twitter screen name or ID of a specified user
	 * @return object
	 */	
	public function get_friends( $user_id )
	{
		if( is_int( $user_id ) )
			$qs = array( 'user_id' => $user_id );
		else
			$qs = array( 'screen_name' => $user_id );
		$this->api_url = 'http://twitter.com/statuses/friends.' . $this->type . $this->_glue( $qs );
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Retrieve data about a specified user.
	 *
	 * @access public
	 * @since 2.0
	 * @param string/int $user_id The Twitter screen name or ID of a specified user
	 * @return object
	 */	
	public function get_followers( $user_id )
	{
		if( is_int( $user_id ) )
			$qs = array( 'user_id' => $user_id );
		else
			$qs = array( 'screen_name' => $user_id );
		$this->api_url = 'http://twitter.com/statuses/followers.' . $this->type . $this->_glue( $qs );
		return $this->_get( $this->api_url );
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