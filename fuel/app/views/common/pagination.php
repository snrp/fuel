<?php

		if (\Pagination::$total_pages == 1)
		{
			echo '';
			return;
		}
		
		// Let's get the starting page number, this is determined using num_links
		$start = ((\Pagination::$current_page - \Pagination::$num_links) > 0) ? \Pagination::$current_page - (\Pagination::$num_links - 1) : 1;

		// Let's get the ending page number
		$end   = ((\Pagination::$current_page + \Pagination::$num_links) < \Pagination::$total_pages) ? \Pagination::$current_page + \Pagination::$num_links : \Pagination::$total_pages;


		echo \Pagination::prev_link('&laquo Previous').'&nbsp;';
		
		for($i = $start; $i <= $end; $i++)
		{
			echo '&nbsp;'.\Pagination::number_link($i, array('title' => "Page number $i"));
		}

		echo '&nbsp;&nbsp;'.\Pagination::next_link('Next &raquo;');