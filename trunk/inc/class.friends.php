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
	 * Request to unfollow a designated user
	 * 
	 * Authenticating user unfollows another twitter user.
	 *
	 * @access public
	 * @since 2.0
	 * @param integer/string $user_id. Required the ID or screen name of the Twitter user to follow
	 * @return object
	 **/
	public function unfollow( $user_id )
	{
		if( is_int( $user_id) )
			$data['user_id'] = $user_id;
		else
			$data['screen_name'] = (string) $user_id;
			
		$this->api_url = 'http://twitter.com/friendships/destroy.' . $this->type . $this->_glue( $data );
		return $this->_post( $this->api_url );
	}
	
	/**
	 * Returns information about a relationship between two twitter users
	 *
	 * @access public
	 * @since 2.0
	 * @param string/integer $target_user. Required
	 * @param string/integer/boolean $source_user. Optional. If omitted or false, the $source user will be the authenticating user
	 * @return object
	 **/
	public function show( $target_user, $source_user = false )
	{
		if( !$source_user )
			$source_user = $this->username;
		if( is_int( $source_user ) )
			$data['source_id'] = $source_user;
		else
			$data['source_sceen_name'] = (string) $source_user;

		if( is_int( $target_user) )
			$data['target_id'] = $target_user;
		else
			$data['target_screen_name'] = (string) $target_user;
		$this->api_url = 'http://twitter.com/friendships/show.' . $this->type . $this->_glue( $data );
		return $this->_get( $data );
	}
	
	/**
	 * Return true if User A is following User B. Wrapper around show() method
	 *
	 * @access public
	 * @since 2.0
	 * @param string/integer $user_a. Required.
	 * @param string/integer $user_b. Required.
	 * @return boolean
	 **/
	public function is_friend( $user_a, $user_b )
	{
		$relationship = $this->show( $user_a, $user_b );
		return $relationship->source->following;
	}
	
	/**
	 *  Returns a list of all Twitter user IDs that the specified user follows
	 *
	 * @access public
	 * @since 2.0
	 * @param string/integer $twitter_user
	 * @param boolean/integer $page
	 * @return object
	 */
	public function user_follows( $twitter_user, $page = false )
	{
		$data = array();
		if( is_int( $twitter_user ) )
			$data['user_id'] = $twitter_user;
		else
			$data['screen_name'] = $twitter_user;
		if( $page && is_int($page) )
			$data['cursor'] = $page;
		
		$this->api_url = 'http://twitter.com/friends/ids.' . $this->type;
		$this->api_url .= $this->_glue( $data );
		return $this->_get( $this->api_url );
	}
	
	/**
	 *  Returns a list of all Twitter user IDs that follows the specified user
	 *
	 * @access public
	 * @since 2.0
	 * @param string/integer $twitter_user
	 * @param boolean/integer $page
	 * @return object
	 */
	public function user_followers( $twitter_user, $page = false )
	{
		$data = array();
		if( is_int( $twitter_user ) )
			$data['user_id'] = $twitter_user;
		else
			$data['screen_name'] = $twitter_user;
		if( $page && is_int($page) )
			$data['cursor'] = $page;
		
		$this->api_url = 'http://twitter.com/followers/ids.' . $this->type;
		$this->api_url .= $this->_glue( $data );
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