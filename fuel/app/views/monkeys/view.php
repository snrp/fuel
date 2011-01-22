<p>
	<strong>Name:</strong>
	<?php echo $monkey->name; ?></p>
<p>
	<strong>Description:</strong>
	<?php echo $monkey->description; ?></p>

<?php echo HTML::anchor('monkeys/edit/'.$monkey->id, 'Edit'); ?> | 
<?php echo HTML::anchor('monkeys', 'Back'); ?>