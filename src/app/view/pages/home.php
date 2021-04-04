<div class="container">
    <?= $this->render('pages/imports/carousel-home'); ?>
    <div class="header">
        <div class="slogan bold">WE PROVIDE EXCEPTIONAL MAINTENANCE SERVICES, MINIMIZING ASSET DETERIORATION
        </div>
    </div>
    <?= $this->render('pages/about', ["home" => true]); ?>
    <?= $this->render('pages/services', ["home" => true]); ?>
    <?= $this->render('pages/plans', ["home" => true]); ?>

</div>