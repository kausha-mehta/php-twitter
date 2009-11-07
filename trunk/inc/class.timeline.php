<?php
/**
 * Twitter Search
 *
 * @package php-twitter 2.0
 * @subpackage timeline
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Timeline extends Twitter {
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Timeline
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York', $debug = false)
	{
		parent::__construct();
	}

	/**
	 * Send a request for timeline statuses
	 *
	 * @access public
	 * @since 2.0
	 * @param string $scope public for public timeline, user for user timeline, mentions for @ replies
	 * @param array $args Parameters that can be passed in key/pair format. All are optional. Some only apply to certain scopes.
	 *  - since_id: INT. Only tweets after the specified Status ID are returned
	 *  - max_id: INT. Only tweets up to a specified Status ID are returned
	 *  - count: INT. Number of tweets to return. Defaults to 20.
	 *  - page: INT: Paged result set to display.
	 *  - id: INT or STRING: Twitter ID or screen name (Only User scope)
	 *  - user_id: INT Twitter ID (Only User scope)
	 *  - screen_name: STRING Twitter ID (Only User scope)
	 * @return object
	 */
	public function get_timeline( $scope = 'public', $args = array() )
	{
		extract( $args, EXTR_SKIP );
		$defaults = array(
			'count'		=> 20,
			'page'		=> 1
			);
		
		$qs = array();
		switch( $scope )
		{
			case 'home' :							#COMING SOON TO TWITTER
			case 'friends' :
				$url_scope = '/friends_timeline';
				if( $since_id )
					$qs['since_id'] = (int) $since_id;
				if( $max_id )
					$qs['max_id'] = (int) $max_id;
				if( $count )
					$qs['count'] = ( $count > 200 ) ? 200 : (int) $count;
				if( $page )
					$qs['page'] = (int) $page;
				$query_vars = wp_parse_args( $qs, $defaults );
				break;
			case 'user' :
				$url_scope = '/user_timeline';
				if( $id )
					$qs['id'] = $id;
				if( $user_id )
					$qs['user_id'] = (int) $user_id;
				if( $screen_name )
					$qs['screen_name'] = (string) $screen_name;
				if( $since_id )
					$qs['since_id'] = (int) $since_id;
				if( $max_id )
					$qs['max_id'] = (int) $max_id;
				if( $count )
					$qs['count'] = ( $count > 200 ) ? 200 : (int) $count;
				if( $page )
					$qs['page'] = (int) $page;
				$query_vars = wp_parse_args( $qs, $defaults );
				break;
			case 'mentions' :
				$url_scope = '/mentions';
				if( $since_id )
					$qs['since_id'] = (int) $since_id;
				if( $max_id )
					$qs['max_id'] = (int) $max_id;
				if( $count )
					$qs['count'] = ( $count > 200 ) ? 200 : (int) $count;
				if( $page )
					$qs['page'] = (int) $page;
				$query_vars = wp_parse_args( $qs, $defaults );
				break;
			case 'public' :
			default :
				$url_scope = '/public_timeline';
				break;
		}
		$this->api_url = 'http://twitter.com/statuses' . $url_scope . '.' . $this->type;
		if( $query_vars )
			$this->api_url = $this->api_url . $this->_glue( $query_vars );
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Quick method to return 20 most recent results from the public timeline
	 *
	 * @access public
	 * @since 2.0
	 * @return object
	 */
	public function get_public_timeline()
	{
		return get_timeline();
	}

	/**
	 * Quick method to return 20 most recent results from a user
	 *
	 * @access public
	 * @since 2.0
	 * @return object
	 */	
	public function get_user_timeline( $twitter_name )
	{
		return get_timeline( 'user', array('screen_name' => $twitter_name ) );
	}
	
	/**
	 * Quick method to return 20 most recent mentions
	 *
	 * @access public
	 * @since 2.0
	 * @return object
	 */
	public function get_mentions()
	{
		return get_timeline( 'mentions' );
	}
	
	public function retweets()
	{
		# COMING SOON TO TWITTER
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