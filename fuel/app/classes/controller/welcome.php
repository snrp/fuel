<?php
/**
 * An example Controller.  This shows the most basic usage of a Controller.
 */
class Controller_Welcome extends Controller {

	public function action_index()
	{
		//$this->render('welcome/index');
		/*
		$config = array(
			'pagination_url' => \Uri::create('welcome/index'),
			'total_items' => 17,
			'per_page' => 5,
			'uri_segment' => 3,
		);*/
	
	
		$config = array(
			
			'pagination_url' => 'welcome/index/:page',
			'uri_segment' => 3, 

			//'uri' => 'welcome/index/:page/', // <--- notice the colon (:)
			'get_variables' => array('foo' => 'bar' ),

			// 'variable_name' => 'page', // <--- notice NO colon (:)
		
			'total_items' => 17,
			'per_page' => 5,
		);
		
		$config = array(	
			'pagination_url' => 'welcome/index',
			'uri_segment' => 3, 

			//'uri' => 'welcome/index/:page/', // <--- notice the colon (:)
			'get_variables' => array('foo' => 'bar' ),

			// 'variable_name' => 'page', // <--- notice NO colon (:)
			// 'place' => 'autodetect' (default) | segment | get
		
			'total_items' => 17,
			'per_page' => 5,
		);
		
		Pagination::set_config($config);
		echo Pagination::create_links();
	}

	public function action_404()
	{
		// Set a HTTP 404 output header
		Output::$status = 404;
		$this->render('welcome/404');
	}
}