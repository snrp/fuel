<h2>Listing monkeys</h2>

<table>
	<tr>
		<th>Name</th>
		<th>Description</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>

	<?php foreach ($monkeys as $monkey): ?>	<tr>

		<td><?php echo $monkey->name; ?></td>
		<td><?php echo $monkey->description; ?></td>
		<td><?php echo HTML::anchor('monkeys/view/'.$monkey->id, 'View'); ?></td>
		<td><?php echo HTML::anchor('monkeys/edit/'.$monkey->id, 'Edit'); ?></td>
		<td><?php echo HTML::anchor('monkeys/delete/'.$monkey->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?></td>
	</tr>
	<?php endforeach; ?></table>
	<?php echo $pagination; ?>
<br />

<?php echo HTML::anchor('monkeys/create', 'Add new Monkey'); ?>