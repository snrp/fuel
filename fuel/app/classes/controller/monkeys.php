<?php
class Controller_Monkeys extends Controller_Template {
	
	/* 
	***********************************************************************
	**/
	public function action_index()
	{
		$per_page = 2;
		
		Pagination::set_config(array(
		    'pagination_url' => \Uri::create('monkeys/index'),
		    'total_items' => Model_Monkey::count('all'),
		    'per_page' => $per_page,
		    'uri_segment' => 3,
		    'view' => 'common/pagination',
		    'attributes' => array(
				'prev_link' => array('title' => 'prev_link' ), 
				'next_link' => array('title' => 'next_link' ),
				'number_link' => array('title' => 'number_link' ),
			),
		));
		
		$data['pagination'] = Pagination::create_links();
		
		$data['monkeys'] = Model_Monkey::find('all', array(
							'limit' => $per_page ,
							'offset' => Pagination::$offset,
		));
		
		$this->template->title = "Monkeys";
		$this->template->content = View::factory('monkeys/index', $data);
	}
	
	/* 
	***********************************************************************
	**/
	public function action_view($id = null)
	{
		$data['monkey'] = Model_Monkey::find($id);
		
		$this->template->title = "Monkey";
		$this->template->content = View::factory('monkeys/view', $data);
	}
	
	public function action_create($id = null)
	{
		if ($_POST)
		{
			$monkey = Model_Monkey::factory(array(
				'name' => Input::post('name'),
				'description' => Input::post('description'),
			));

			if ($monkey and $monkey->save())
			{
				Session::set_flash('notice', 'Added ' . $monkey . ' #' . $monkey->id . '.');

				Output::redirect('monkeys');
			}

			else
			{
				Session::set_flash('notice', 'Could not save monkey.');
			}
		}

		$this->template->title = "Monkeys";
		$this->template->content = View::factory('monkeys/create');
	}
	
	public function action_edit($id = null)
	{
		$monkey = Model_Monkey::find($id);

		if ($_POST)
		{
			$monkey->name = Input::post('name');
			$monkey->description = Input::post('description');

			if ($monkey->save())
			{
				Session::set_flash('notice', 'Updated ' . $monkey . ' #' . $monkey->id);

				Output::redirect('monkeys');
			}

			else
			{
				Session::set_flash('notice', 'Could not update ' . $monkey . ' #' . $id);
			}
		}
		
		else
		{
			$this->template->set_global('monkey', $monkey);
		}
		
		$this->template->title = "Monkeys";
		$this->template->content = View::factory('monkeys/edit');
	}
	
	public function action_delete($id = null)
	{
		$monkey = Model_Monkey::find($id);

		if ($monkey and $monkey->delete())
		{
			Session::set_flash('notice', 'Deleted ' . $monkey . ' #' . $id);
		}

		else
		{
			Session::set_flash('notice', 'Could not delete ' . $monkey . ' #' . $id);
		}

		Output::redirect('monkeys');
	}
	
	
}

/* End of file monkeys.php */