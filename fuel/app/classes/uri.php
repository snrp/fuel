<?php

class Uri extends Fuel\Core\Uri {

	/**
	 * Creates a url with the given uri, including the base url
	 *
	 * @param	string	the url
	 				-null | false = use  uri::string($verbose = false);
					-true = use uri::string($verbose = true);
	 				-string not containing '://' = 
						 	assume is a type of route ('controller/action')
						 	adds base url, and Config[index_file]
	 				-string containing '://' = 
					 		uri is of type 'http://...' leave as is, only add the vars
	 * @param	array	some segment variables for the url
	 * @param	array	some querystring variables for the url
	 */
	public static function create($uri = null, $variables = array(), $get_variables = array())
	{
		$base_url = \Config::get('base_url');
		if (\Config::get('index_file'))
		{
			$base_url .= \Config::get('index_file').'/';
		}
		
		if (is_string($uri))
		{	
			if (preg_match('#^\w+://# i', $uri))
			{
				//uri is of type 'http://...' dont touch it
				$url = $uri;
			}
			else
			{
				//uri is of type 'controller/action' prepend base_url
				$url = $base_url.ltrim($uri, '/');
			}
		}
		else 
		{
			//uri is null
			$url = $base_url.ltrim(static::string(), '/');
		}
		
		foreach($variables as $key => $val)
		{
			$url = str_replace(':'.$key, $val, $url);
		}
		
		$char = false === strpos($url, '?') ? '?' : '&';
		foreach($get_variables as $key => $val) 
		{
			$url .= $char.$key.'='.$val;
			$char = '&';
		}

		return $url;
	}
	
	/*
		when routing to default controller (ex going to localhost/fuel/public), 
		Uri::string($verbose = false); will return empty string
		Uri::string($verbose = true); will return 'default_controller/default_action'
		
	**/
	public static function string($verbose = false)
	{
		if ($verbose === true)
		{
			$ret = '';
			$ret .= empty(\Request::active()->module) ?  '' : (\Request::active()->module.'/');
			$ret .= empty(\Request::active()->directory) ? '' : (\Request::active()->directory.'/');
			$ret .= \Request::active()->controller.'/';
			$ret .= \Request::active()->action ?: 'index';
			return $ret;
		}
		else
		{
			return \Request::active()->uri->uri;
		}
	}
}

/* EOF */