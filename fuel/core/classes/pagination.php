<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package		Fuel
 * @version		1.0
 * @author		Dan Horrigan <http://dhorrigan.com>
 * @license		MIT License
 * @copyright	2010 - 2011 Fuel Development Team
 */

namespace Fuel\Core;



class Pagination {

	/**
	 * @var	integer	The current page
	 */
	public static $current_page = null;

	/**
	 * @var	integer	The offset that the current page starts at
	 */
	public static $offset = 0;

	/**
	 * @var	integer	The number of items per page
	 */
	public static $per_page = 10;

	/**
	 * @var	integer	The number of total pages
	 */
	public static $total_pages = 0;

	/**
	 * @var	integer	The total number of items
	 */
	protected static $total_items = 0;

	/**
	 * @var	integer	The total number of links to show
	 */
	protected static $num_links = 5;

	/**
	 * @var	integer	The URI segment containg page number
	 */
	protected static $uri_segment = 3;

	/**
	 * @var	string|array	The pagination URL @see $mode
	 */
	protected static $pagination_url;

	/**
	 * @var	mixed	The param name for uri::create
	 */
	protected static $uri_page = 'page';

	/**
	 * @var	string	static::CLASSIC | static::CLASSIC_PLUS | static::GET
	 */
	protected static $mode = 0; // static::CLASSIC
	
	const CLASSIC = 0;
	const CLASSIC_PLUS = 1;
	const GET = 2;
	

	/**
	 * Init
	 *
	 * Loads in the config and sets the variables
	 *
	 * @access	public
	 * @return	void
	 */
	public static function _init()
	{
		$config = \Config::get('pagination', array());

		static::set_config($config);
	}

	// --------------------------------------------------------------------

	/**
	 * Set Config
	 *
	 * Sets the configuration for pagination
	 *
	 * @access public
	 * @param array   $config The configuration array
	 * @return void
	 */
	public static function set_config(array $config)
	{

		foreach ($config as $key => $value)
		{
			static::${$key} = $value;
		}

		static::initialize();
	}

	// --------------------------------------------------------------------

	/**
	 * Prepares vars for creating links
	 *
	 * @access public
	 * @return array    The pagination variables
	 */
	protected static function initialize()
	{

		static::$total_pages = ceil(static::$total_items / static::$per_page) ?: 1;

		static::$current_page = static::current_page();

		if (static::$current_page > static::$total_pages)
		{
			static::$current_page = static::$total_pages;
		}
		elseif (static::$current_page < 1)
		{
			static::$current_page = 1;
		}

		// The current page must be zero based so that the offset for page 1 is 0.
		static::$offset = (static::$current_page - 1) * static::$per_page;
	}

	// --------------------------------------------------------------------

	/**
	 * Creates the pagination links
	 *
	 * @access public
	 * @return mixed    The pagination links
	 */
	public static function create_links()
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		$pagination = '';

		// Let's get the starting page number, this is determined using num_links
		$start = ((static::$current_page - static::$num_links) > 0) ? static::$current_page - (static::$num_links - 1) : 1;

		// Let's get the ending page number
		$end   = ((static::$current_page + static::$num_links) < static::$total_pages) ? static::$current_page + static::$num_links : static::$total_pages;

		$pagination .= '&nbsp;'.static::prev_link('&laquo Previous').'&nbsp;&nbsp;';

		for($i = $start; $i <= $end; $i++)
		{
			if (static::$current_page == $i)
			{
				$pagination .= '<b>'.$i.'</b>';
			}
			else
			{
				$pagination .= \Html::anchor(static::pagination_url($i), $i);
			}
		}

		$pagination .= '&nbsp;'.static::next_link('Next &raquo;');

		return $pagination;
	}

	// --------------------------------------------------------------------

	/**
	 * Pagination "Next" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return mixed    The next link
	 */
	public static function next_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == static::$total_pages)
		{
			return $value;
		}
		else
		{
			$next_page = static::$current_page + 1;
			return \Html::anchor(static::pagination_url($next_page), $value);
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Pagination "Previous" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return mixed    The previous link
	 */
	public static function prev_link($value)
	{
		if (static::$total_pages == 1)
		{
			return '';
		}

		if (static::$current_page == 1)
		{
			return $value;
		}
		else
		{
			$previous_page = static::$current_page - 1;
			return \Html::anchor(static::pagination_url($previous_page), $value);
		}
	}
		
		

	
	
	/**
	 * Get current page from uri with the configured static::$mode
	 *
	 * @access public
	 * @param string $page_nr The page nr for the url
	 * @return string    The pagination_url
	 */
	public static function current_page()
	{
		switch (strtolower(static::$mode)) 
		{
		  	case static::CLASSIC:
			case static::CLASSIC_PLUS:
		  		return (int) \URI::segment(static::$uri_segment);
		  	case static::GET:
		  		return (int) \Input::get(static::$uri_page, 1);
  		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Get the pagination url with the configured static::$mode
	 *
	 * @access public
	 * @param string $page_nr The page nr for the url
	 * @return string    The pagination_url
	 */
	public static function pagination_url($page_nr)
	{
		switch (strtolower(static::$mode)) 
		{
		  	case static::CLASSIC:
		  		$page_nr = ($page_nr == 1) ? '' : '/'.$page_nr;
				return rtrim(static::$pagination_url, '/').$page_nr;
			
			//static::CLASSIC but add support for get variables at the end of the url
			case static::CLASSIC_PLUS:	
				//defaults for Uri::create()
		  		$defaults = array('uri' => NULL,'segment_variables' => array(),	'get_variables' => array());
				$params = array_merge($defaults, static::$pagination_url);
				
				//process the uri (create it), then append the $page_nr to it
				$params['uri'] = \Uri::create($params['uri']);
				$page_nr = ($page_nr == 1) ? '' : '/'.$page_nr;
				$params['uri'] = rtrim($params['uri'], '/').$page_nr;
				
				// this time \Uri::create is for appending the get_variables
				return \Uri::create($params['uri'],	$params['segment_variables'], $params['get_variables']);
		  	
		  	//put $page_nr in a get_variable, then let Uri::create deal with it
			case static::GET:
			  	//defaults for Uri::create()
		  		$defaults = array('uri' => NULL,'segment_variables' => array(),	'get_variables' => array());
				$params = array_merge($defaults, static::$pagination_url);
				
		  		$params['get_variables'][static::$uri_page] = $page_nr;
		  		
				if ($page_nr == 1)
				{
					unset($params['get_variables'][static::$uri_page]);
				}
				
				return \Uri::create($params['uri'],	$params['segment_variables'], $params['get_variables']);
		}
	}
}

/* End of file pagination.php */
