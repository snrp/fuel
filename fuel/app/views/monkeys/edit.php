<h2>Editing monkey</h2>

<?php echo render('monkeys/_form'); ?>

<?php echo HTML::anchor('monkeys/view/'.$monkey->id, 'View'); ?> |
<?php echo HTML::anchor('monkeys', 'Back'); ?>