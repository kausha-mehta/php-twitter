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
	 * @param string $list_id: Required. The slug (or ID) of a given Twitter list
	 * @param integer/boolean $page: Optional. Whether to return results in paged sets of 20. -1 initializes paging. A Tweet ID provided in paged results provides "next" "previous"
	 * @return object
	 */
	public function get_list( $twitter_user, $list_id )
	{		
		$this->api_url = 'http://api.twitter.com/1/' . $twitter_user . '/lists/'. $list_id . '.' . $this->type;
		echo $this->api_url;
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