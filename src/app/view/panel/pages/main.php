<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");

use App\Controller\BlackboardNewsController;

$langGroup = BlackboardNewsController::LANG_GROUP;
?>
<section class="main-view-admin dashboard">
	<div class="news" normal blackboard-list='<?= get_route('blackboard-news-get'); ?>'>
		<h1><?= __($langGroup, 'Últimas noticias'); ?></h1>
		<div class="content" content></div>
		<div class="paginate" paginate></div>
	</div>
</section>