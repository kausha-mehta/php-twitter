<?php
/**
 * Twitter Search
 *
 * @package php-twitter 2.0
 * @subpackage search
 * @author Aaron Brazell
 **/

require_once('../class.twitter.php');
class Twitter_Search extends Twitter {
	
	/**
	 * Setup Twitter client connection details
	 *
	 * Ensure you set a user agent, whether via the constructor or by assigning a value to the property directly.
	 * Also, if you are not running this from the Eastern timezone, be sure to set your proper timezone.
	 *
	 * @access public
	 * @since 2.0
	 * @return Twitter_Search
	 */
	public function __construct( $username = null, $password = null, $user_agent = null, $headers = null, $timezone = 'America/New_York', $debug = false)
	{
		parent::__construct();
	}
	
	/**
	 * Perform a search
	 *
	 * Pass an array of optional parameters (key => value) Possible parameters are:
	 *	- callback: Returns data in JSONP format using the specified callback
	 *  - lang: Restricts tweets to the designated language. Reference http://en.wikipedia.org/wiki/ISO_639-1
	 *  - locale: Twitter currently supports ja (Japan) or en (English). English is default if omitted
	 *  - rpp: Results per page. Maximum of 100 (default)
	 *  - page: The page number of results. Twitter only returns 1500 results per query. Default is 1
	 *  - since_id: Returns tweets with IDs greater than the specified number
	 *  - geocode: Returns tweets within a specified radius of lat/long coordinates. Designate as a value lat,long,rad 
	 *  - show_user: Returns results with username prepended to tweets. Default is false
	 *
	 * @access public
	 * @since 2.0
	 * @return object
	 */
	public function search( $query )
	{
		$this->api_url = 'http://search.twitter.com/search.' . $this->type;
		if( is_string( $query ) )
		{
			$newquery['q'] = $query;
			$query = $newquery;
		}
			
		$defaults = array(
			'lang'		=> 'en',
			'rpp'		=> 100,
			'q'			=> ''
			);
		$args = wp_parse_args( $query, $defaults );
		
		// Limit query to 140 URL encoded charachters per Twitter
		$query_string = substr( $this->_glue( $args ), 0, 140 );
		
		return $this->_get( $this->api_url . $query_string );
	}
	
	/**
	 * Retrieves top 10 trending topics
	 *
	 * @access public
	 * @since 2.0
	 * @param null/string $scope current/daily/weekly
	 * @param boolean $include_hashtags determines whether to filter out hashtags in results. Default is false
	 * @param boolean/string $date a formatted string like YYYY-MM-DD. If omitted, the current date is used
	 * @return object
	 */
	public function trends( $scope = 'current', $include_hashtags = false, $date = false )
	{
		switch( $scope )
		{
			case 'current' :
				$url_scope = '/current';
				$qs = array();
				$qs['exclude'] = 'hashtags';
				break;
			case 'daily' :
				$url_scope = '/daily';
				$qs = array();
				if( $include_hashtags )
					$qs['exclude'] = 'hashtags';
				if( !$date )
					$qs['date'] = date('Y-m-d');
				break;
			case 'weekly' :
				$url_scope = '/weekly';
				if( $include_hashtags )
					$qs['exclude'] = 'hashtags';
				if( !$date )
					$qs['date'] = date('Y-m-d');
				break;
			default :
				$url_scope = '';
				break;
		}
		$this->api_url = 'http://search.twitter.com/trends' . $url_scope . '.' . $this->type;
		if( $qs )
			$this->api_url = $this->api_url . $this->_glue( $qs );
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

class summize extends Twitter_Search {
	# Deprecated - Use Twitter_Search class instead
}