<?php if (isset($home) && $home == true) : ?>
    <div class="plans-home">
        <div class="header">
            <div class="title">Prepaid assett preservation coverage</div>
        </div>
        <div class="content">
            <div class="images-content">
                <img src="<?= base_url("statics/images/prices/prices-building.svg") ?>" alt="prices-building">
                <div class="buttons">
                    <a class="ui white button fluid" href="<?= get_route("public-plans") ?>">Our plans</a>
                    <a class=" ui secondary button fluid" href="<?= get_route("public-contact", ["type" => "all"]) ?>">Contact us</a>
                </div>
            </div>
            <div class=" grid">
                <div class="item">
                    <div class="title">Basic</div>
                    <p>Basic maintenance services, easy to carry out.
                        <br>Highly competitive in the actual maintenance
                        service
                        market
                    </p>
                    <a class="ui primary button" href="<?= get_route("public-plans") ?>">Read more</a>
                </div>
                <div class=" item right">
                    <div class="title">Premium</div>
                    <p>Highly effective maintenance services that upgrade BASIC Coverage scope by including additional
                        assets of the Unit</p>
                    <a class="ui primary button" href="<?= get_route("public-plans") ?>">Read more</a>
                </div>
                <div class=" item">
                    <div class="title">Standard</div>
                    <p>High profile maintenance services covering all the household appliances and equipment of the Unit
                    </p>
                    <a class="ui primary button" href="<?= get_route("public-plans") ?>">Read more</a>
                </div>
                <div class=" item right">
                    <div class="title">Ultra</div>
                    <p>Broad and sustained service supported by semi-resident or resident personnel. This option
                        includes additional services
                        provided by the assigned domestic personnel</p>
                    <a class="ui primary button" href="<?= get_route("public-plans") ?>">Read more</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!isset($home) || (isset($home) && $home == false)) : ?>
    <div class=" container plans">

        <div class="header">
            <div class="title">Plans</div>
            <div class="slogan">Leave your problems in our hands</div>
        </div>

        <div class="squares">
            <?php foreach ($plans as $key => $plan) : ?>

                <div class="square card hover <?= $plan ?>">
                    <span class="tooltiptext <?= $plan ?>"><?= $plan ?></span>
                    <div class="list">
                        <?php foreach ($microservices as $key => $item) : ?>

                            <div class="item" *ngFor="let item of microservices">
                                <img src="<?= base_url("statics/images/icons/" . ($item[$plan] ? 'check' : 'close')) . ".svg" ?>" alt="<?= $item[$plan] ? 'check' : 'close' ?>" class="<?= $item[$plan] ? 'check' : 'close' ?>">
                                <span><?= $item["name"] ?></span>
                            </div>

                        <?php endforeach; ?>
                    </div>
                    <a href="<?= get_route("public-contact",  ["type" => $plan]) ?>" mat-button class="primary">Get quote</a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="aditional">
            <div class="images-content">
                <img src="<?= base_url("statics/images/prices/prices-people.svg") ?>" alt="prices-people">
            </div>
            <div class="text-content">
                <div class="header right">
                    <div class="title">Aditional<br>Services</div>
                    <p>We have two extra special services which you <br> can self-quote on our website at any time
                        you
                        wish. <br><br><strong>Get a quote now!</strong></p>

                    <div class="buttons nowrap md">
                        <a class="ui white button padding" href="<?= get_route("public-contact",  ["type" => "desinfection"]) ?>">Desinfection
                            services</a>
                        <a class="ui white button padding" href="<?= get_route("public-contact",  ["type" => "nonoccupied"]) ?>">Non occupied
                            unit</a>
                    </div>
                </div>
            </div>
        </div>
        <?= $this->render('pages/services', ["home" => true]); ?>
    </div>
<?php endif; ?>