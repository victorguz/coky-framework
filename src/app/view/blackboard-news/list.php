<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\BlackboardNewsController;

$langGroup = BlackboardNewsController::LANG_GROUP;
?>

<h3 class="ui dividing header">
	<?= __($langGroup, 'Noticias'); ?>
</h3>
<a href="<?= get_route('blackboard-news-create-form'); ?>" class="ui primary button"><?= __($langGroup, 'Nueva noticia'); ?></a>
<br>
<br>

<table process="<?= $process_table; ?>" style='width:100%;' class="ui table striped nowrap celled">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Start</th>
			<th>End</th>
			<th order='false'>Actions</th>
		</tr>
	</thead>
</table>


<script>
	window.onload = () => {

		let table = $(`[process]`)
		let processURL = table.attr('process')
		dataTableServerProccesing(table, processURL, 10)

	}
</script>