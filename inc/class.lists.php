<?php
/**
 * Twitter_Lists
 *
 * @package php-twitter 2.0
 * @subpackage users
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Lists extends Twitter {
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
	 * Create a new list
	 *
	 * @access public
	 * @since 2.0
	 * @param array $list An array of key/value pairs. The only required key is 'name'
	 *  - name: Required. A name for a list to create. It must be unique for the authenticating user
	 *  - mode: Optional. Privacy settings: public or private. By default, a list is public
	 * @return object
	 */
	public function create_list( $list )
	{
		if( !is_array( $list ) )
		{
			$newlist = array('name' => $list );
			$list = $newlist;
		}
		$this->api_url = 'http://api.twitter.com/1/user/lists.' . $this->type;
		return $this->_post( $this->api_url, $list );
	}

	/**
	 * Modify a list specified by List ID
	 *
	 * @access public
	 * @since 2.0
	 * @param array $list An array of key/value pairs. The only required key is 'name'
	 *  - name: Optional. A name for a list to create. It must be unique for the authenticating user
	 *  - mode: Optional. Privacy settings: public or private. By default, a list is public
	 * @return object
	 */
	public function edit_list( $list_id, $list = array() )
	{
		if( !is_array( $list ) )
			return false;
			
		$this->api_url = 'http://api.twitter.com/1/' . $this->username . '/lists/' .  $list_id . '.'. $this->type;
		return $this->_post( $this->api_url, $list );
	}
	
	/**
	 * Retrieve a list of Lists, pardon the pun, for a given user. Private lists are only shown if owned by the authenticating user
	 *
	 * @access public
	 * @since 2.0
	 * @param string $twitter_user: Required. The Twitter name of a user to retrieve lists for
	 * @param integer/boolean $page: Optional. Whether to return results in paged sets of 20. -1 initializes paging. A Tweet ID provided in paged results provides "next" "previous"
	 * @return object
	 */
	public function get_lists( $twitter_user, $page=false )
	{		
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists.'. $this->type;
		if( $page )
			$this->api_url = $this->api_url . '?cursor=' . (int) $page;
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Retrieves a list by slug. A private list is only returned if owned by the authenticating user.
	 *
	 * @access public
	 * @since 2.0
	 * @param string $twitter_user: Required. The twitter handle for a user
	 * @param string $list_id: Required. The slug (or ID) of a given Twitter list
	 * @return object
	 */
	public function get_list( $twitter_user, $list_id )
	{		
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists/'. $list_id . '.' . $this->type;
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Deletes a tweet given a slug.
	 *
	 * @access public
	 * @since 2.0
	 * @param string $list_id: Required. The slug (or ID) of a given Twitter list
	 * @return object
	 */
	public function delete_list( $list_id )
	{		
		$this->api_url = 'http://api.twitter.com/1/' . $this->username . '/lists/'. $list_id . '.' . $this->type;
		return $this->_post( $this->api_url, array( 'id' => $list_id, '_method' => 'DELETE') );
	}

	/**
	 * Send a request for a List timeline
	 *
	 * @access public
	 * @since 2.0
	 * @param string $twitter_user. Required: The twitter handle that owns a given list
	 * @param string $list. Required: The slug of a list to be retrieved.
	 * @param array $args Parameters that can be passed in key/pair format. All are optional. Some only apply to certain scopes.
	 *  - since_id: INT. Only tweets after the specified Status ID are returned
	 *  - max_id: INT. Only tweets up to a specified Status ID are returned
	 *  - per_page: INT. Number of tweets to return per page. Defaults to 20.
	 *  - page: INT: Paged result set to display.
	 * @return object
	 */	
	public function get_list_timeline( $twitter_user, $list, $args = array() )
	{
		extract( $args, EXTR_SKIP );
		$defaults = array(
			'per_page'	=> 200,
			'page'		=> 1
			);
		if( $since_id )
			$qs['since_id'] = (int) $since_id;
		if( $max_id )
			$qs['max_id'] = (int) $max_id;
		if( $count )
			$qs['per_page'] = ( $count > 200 ) ? 200 : (int) $count;
		if( $page )
			$qs['page'] = (int) $page;
		$query_vars = wp_parse_args( $qs, $defaults );
		
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists/' . $list . '/statuses.' . $this->type;
		if( $query_vars )
			$this->api_url = $this->api_url . $this->_glue( $query_vars );
		return $this->_get( $this->api_url );
	}
	
	/**
	 *  Retrieves a list of all Lists a given Twitter member is a part of
	 *
	 * @param string $twitter_user. Required: The Twitter ID of the user to find list membership of
	 * @param integer/boolean. Optional: If supplied, paging begins when set to -1. Other INTs include the Prev/Next IDs supplied with page results
	 * @return object
	 **/
	public function user_list_membership( $twitter_user, $page = false )
	{
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists/membership.' . $this->type;
		if( $page )
			$this->api_url = $this->api_url . '?cursor=' . $page;
		return $this->_get( $this->api_url );
	}
	
	/**
	 *  Retrieves a list of all Lists a given Twitter member subscribes to
	 *
	 * @param string $twitter_user. Required: The Twitter ID of the user to find list membership of
	 * @param integer/boolean. Optional: If supplied, paging begins when set to -1. Other INTs include the Prev/Next IDs supplied with page results
	 * @return object
	 **/
	public function user_list_subscriptions( $twitter_user, $page = false )
	{
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists/subscriptions.' . $this->type;
		if( $page )
			$this->api_url = $this->api_url . '?cursor=' . $page;
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Retrieves a list of all members on a given list
	 *
	 * @param string $list_owner. Required
	 * @param string $list_id. Required
	 * @param integer/boolean $page. Optional.
	 * @return object
	 */
	public function get_list_members( $list_owner, $list_id, $page = false )
	{
		$this->api_url = 'http://api.twitter.com/1/' . $list_owner . '/' . $list_id . '/members.' . $this->type;
		if( $page )
			$this->api_url = $this->api_url . '?cursor=' . $page;
		return $this->_get( $this->api_url );
	}
	
	/**
	 * Adds a Twitter user to a list owned by the authenticating user.
	 *
	 * @param string $list_id. Required
	 * @param integer $twitter_id. Required.
	 * @return object
	 */
	public function add_list_member( $list_id, $user_id )
	{
		$this->api_url = 'http://api.twitter.com/1/' . $this->username . '/' . $list_id . '/members.' . $this->type;
		return $this->_post( $this->api_url, array('list_id' => (string) $list_id, 'id' => (int) $user_id ) );
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
