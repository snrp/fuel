<?php

namespace Fuel\Migrations;

class Create_monkeys {

	function up()
	{
		\DBUtil::create_table('monkeys', array(
			'id' => array('type' => 'int', 'auto_increment' => true),
			'name' => array('type' => 'varchar', 'constraint' => 255),
			'description' => array('type' => 'text'),

		), array('id'));
	}

	function down()
	{
		\DBUtil::drop_table('monkeys');
	}
}