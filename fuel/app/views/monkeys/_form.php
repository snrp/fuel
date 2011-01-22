<?php echo Form::open(); ?>
	<p>
		<label for="name">Name:</label>
		<?php echo Form::input('name', Input::post('name', isset($monkey) ? $monkey->name : '')); ?>
	</p>
	<p>
		<label for="description">Description:</label>
		<?php echo Form::textarea('description', Input::post('description', isset($monkey) ? $monkey->description : '')); ?>
	</p>

	<div class="actions">
		<?php echo Form::submit(); ?>	</div>

<?php echo Form::close(); ?>