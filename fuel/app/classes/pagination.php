<?php

class Pagination extends Fuel\Core\Pagination {

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
	 * @var	integer	The total number of links to show (x2, 1 on left, 1 on right)
	 */
	public static $num_links = 5;

	/**
	 * @var	integer	The URI segment containg page number
	 */
	protected static $uri_segment = 3;

	/**
	 * @var	mixed	The pagination URL
	 */
	protected static $pagination_url;


 
	/**
	 * @var	mixed	The replacement tag
	 */
	protected static $replacement_tag = '{p}';

	/**
	 * @var	mixed	The get_variable
	 */
	protected static $get_variable = 'page';
	
	/**
	 * @var	mixed	Hide pagination nr whe it == 1 (not supported in static::CLASSIC)
	 */
	protected static $hide_1 = true;
	
	/**
	 * @var	mixed	Optional attributes for each <a> tag
	 */
	protected static $attributes = array(
		'prev_link' => array(),
		'next_link' => array(),
		'number_link' => array(),
	);

	/**
	 * @var	mixed	Optional attributes for each <a> tag
	 */
	protected static $view = null;
		

	/**
	 * @var	string	static::CLASSIC | static::SEGMENT_TAG | static::GET_TAG
	 */
	protected static $method = 'classic'; // static::CLASSIC
	
	const CLASSIC = 'classic';
	const SEGMENT_TAG = 'segment_tag';
	const GET_TAG = 'get_tag';



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
	public static function create_links($config = array())
	{
	
		if ( ! empty($config))
		{
			parent::set_config($config);
		}
	
		if ( ! empty(static::$view))
		{		
			return (string) \View::factory(static::$view);
		}
	
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
			$pagination .= static::number_link($i);
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
	public static function next_link($value, $attributes = array())
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
			$attributes = static::attributes_merge('next_link', $attributes);
			$next_page = static::$current_page + 1;
			return \Html::anchor(static::pagination_url($next_page), $value, $attributes);
		}
	}

		
 	public function attributes_merge($link_type, $attributes)
 	{ 
 		if (array_key_exists($link_type, static::$attributes))
		{ 
			return array_merge(static::$attributes[$link_type], $attributes);
		}
		else
		{
			return $attributes;
		}
 	}


	// --------------------------------------------------------------------

	/**
	 * Pagination "Next" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return string|false    The next url
	 */
	 
/*	 
	 
	public static function next_url()
	{
		if (static::$total_pages == 1)
		{
			return false;
		}
		
		if (static::$current_page == static::$total_pages)
		{
			return false;
		}
		else
		{
			$next_page = static::$current_page + 1;
			return static::pagination_url($next_page);
		}
	}
*/

	// --------------------------------------------------------------------

	/**
	 * Pagination "Previous" link
	 *
	 * @access public
	 * @param string $value The text displayed in link
	 * @return mixed    The previous link
	 */
	public static function prev_link($value, $attributes = array())
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
			$attributes = static::attributes_merge('prev_link', $attributes);
			$previous_page = static::$current_page - 1;
			return \Html::anchor(static::pagination_url($previous_page), $value, $attributes);
		}
	}
	
	// --------------------------------------------------------------------

	/**


	public static function prev_url()
	{
		if (static::$current_page == 1)
		{
			return false;
		}
		else
		{
			$previous_page = static::$current_page - 1;
			return static::pagination_url($previous_page);
		}
	}
	 */	
	
	// --------------------------------------------------------------------

/*
	public static function number_link($number)
	{
		if (static::$current_page == $i)
		{
			return '<b>'.$i.'</b>';
		}
		else
		{
			return \Html::anchor(static::pagination_url($i), $i, static::$attributes);
		}
	}
*/	


/*
		// --------------------------------------------------------------------

		public static function number_url($number)
		{
			if (static::$current_page == $number)
			{
				return false;
			}
			else
			{
				return static::pagination_url($number);
			}
		}
*/	
	
		public static function number_link($i, $attributes = array())
		{
			if (static::$current_page == $i)
			{
				return '<b>'.$i.'</b>';
			}
			else
			{
				$attributes = static::attributes_merge('number_link', $attributes);
				return \Html::anchor(static::pagination_url($i), $i, $attributes);
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
		switch (strtolower(static::$method)) 
		{
			case static::CLASSIC:
			
				$page_nr = ($page_nr == 1) ? '' : '/'.$page_nr;
				return rtrim(static::$pagination_url, '/').$page_nr;
	
			case static::SEGMENT_TAG:
		  	case static::GET_TAG:
		  	
		  		if($page_nr == 1 AND static::$hide_1 === true)
				{
					//if found remove'/{p}'
					//else if found, remove'{p}&'
					//else if found, remove '&{p}'
					//else if found, remove'?{p}'
					if(is_int(strpos(static::$pagination_url, '/'.static::$replacement_tag)))
					{
						return str_replace('/'.static::$replacement_tag, '', static::$pagination_url);
					}
					elseif(is_int(strpos(static::$pagination_url, static::$replacement_tag.'&')))
					{
						return str_replace(static::$replacement_tag.'&', '', static::$pagination_url);
					}
					elseif(is_int(strpos(static::$pagination_url, '&'.static::$replacement_tag)))
					{
						return str_replace('&'.static::$replacement_tag, '', static::$pagination_url);
					}
					elseif(is_int(strpos(static::$pagination_url, '?'.static::$replacement_tag)))
					{
						return str_replace('?'.static::$replacement_tag, '', static::$pagination_url);
					}
					else
					{
						//throw exception ?
						// or:
						return static::$pagination_url;
					}
				}
				else
				{
					//if found '/{p}'
					if(is_int(strpos(static::$pagination_url, '/'.static::$replacement_tag)))
					{
						return str_replace(static::$replacement_tag, $page_nr, static::$pagination_url);
					}
					else
					{
						return str_replace(static::$replacement_tag, static::$get_variable.'='.$page_nr, static::$pagination_url);
					}
				}
				break;
			default:
				die("AAAAA  AAAAA".' '.__FILE__.'::Line:'.__LINE__);
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
		switch (strtolower(static::$method)) 
		{
			case static::CLASSIC:
		  	case static::SEGMENT_TAG:
		  		return (int) \URI::segment(static::$uri_segment);
		  	case static::GET_TAG:
		  		return (int) \Input::get(static::$get_variable, 1);
		  	default:
				die("AAAAA  AAAAA".' '.__FILE__.'::Line:'.__LINE__);
  		}
	}
 	
}

/* End of file pagination.php */