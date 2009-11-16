<?php
/**
 * Twitter_Friends
 *
 * @package php-twitter 2.0
 * @subpackage friends
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Friends extends Twitter {
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Friends
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York')
	{
		parent::__construct($username, $password, $user_agent, $headers, $timezone);
	}
	
	/**
	 * Request to follow a designated user
	 * 
	 * Authenticating user follows another twitter user.
	 *
	 * @access public
	 * @since 2.0
	 * @param integer/string $user_id. Required the ID or screen name of the Twitter user to follow
	 * @param boolean $notifications. Optional. Whether to recieve notifications from the followed user. Default is false.
	 * @return object
	 **/
	public function follow( $user_id, $notifications = false)
	{
		$data = array( 'notifications' => false );
		if( is_int( $user_id) )
			$data['user_id'] = $user_id;
		else
			$data['screen_name'] = (string) $user_id;
			
		if( $notifications )
			$data['notifications'] = true;
			
		$this->api_url = 'http://twitter.com/friendships/create.' . $this->type . $this->_glue( $data );
		return $this->_post( $this->api_url );
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