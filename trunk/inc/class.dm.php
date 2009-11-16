<?php
/**
 * Twitter_Dm
 *
 * @package php-twitter 2.0
 * @subpackage direct_messages
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Dm extends Twitter {
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
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York')
	{
		parent::__construct($username, $password, $user_agent, $headers, $timezone);
	}
	
	/**
	 * Send a request for Direct Messages
	 *
	 * @access public
	 * @since 2.0
	 * @param string $scope public for public timeline, user for user timeline, mentions for @ replies
	 * @param array $args Parameters that can be passed in key/pair format. All are optional. Some only apply to certain scopes.
	 *  - since_id: INT. Only tweets after the specified Status ID are returned
	 *  - max_id: INT. Only tweets up to a specified Status ID are returned
	 *  - count: INT. Number of tweets to return. Defaults to 20.
	 *  - page: INT: Paged result set to display.
	 * @return object
	 */
	public function get_dms( $args = array() )
	{
		if( !class_exists('Twitter_Timeline') )
			require('class.timeline.php');
		
		$defaults = array(
			'count'	=> 20
			);
		$args = wp_parse_args( $defaults, $args );
		
		return Twitter_Timeline::get_timeline( 'dm', $args )
	}
	
	/**
	 * Get a listing of direct messages sent by the authenticating user
	 *
	 * @access public
	 * @since 2.0
	 * @param string $scope public for public timeline, user for user timeline, mentions for @ replies
	 * @param array $args Parameters that can be passed in key/pair format. All are optional. Some only apply to certain scopes.
	 *  - since_id: INT. Only tweets after the specified Status ID are returned
	 *  - max_id: INT. Only tweets up to a specified Status ID are returned
	 *  - count: INT. Number of tweets to return. Defaults to 20.
	 *  - page: INT: Paged result set to display.
	 * @return object
	 */
	public function sent_dms( $args = array() )
	{
		if( !class_exists('Twitter_Timeline') )
			require('class.timeline.php');
		
		$defaults = array(
			'count'	=> 20
			);
		$args = wp_parse_args( $defaults, $args );
		
		return Twitter_Timeline::get_timeline( 'dmsent', $args )
	}
	
	public function __destruct() {}
}