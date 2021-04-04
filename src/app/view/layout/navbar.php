<div class="navbar">
    <div class="content content-1" #container1>
        <a class="link brand" href="/">
            <img src="<?= base_url("statics/images/logo-navbar.svg") ?>" alt="logo-mts">
        </a>
        <a class="link" href="<?= get_route("public-index") ?>">Home</a>
        <a class="link" href="<?= get_route("public-services") ?>">Services</a>
        <a class="link" href="<?= get_route("public-plans") ?>">Plans</a>
        <a class="link" href="<?= get_route("public-about") ?>">About</a>
        <div class="not-dispose">
            <a class="link" href="<?= get_route("public-contact", ["type" => "all"]) ?>">Contact</a>
        </div>
        <div class=" not-dispose">
            <a class="ui button link primary" href="<?= base_url("users/login") ?>">Sign
                in</a>
        </div>
    </div>
    <div class="content content-2">
        <div class="dispose">
            <a class="link" href="<?= get_route("public-contact", ["type" => "all"]) ?>">Contact</a>
            <a class="ui button link primary" href="<?= base_url("users/login") ?>">Sign
                in</a>
        </div>
    </div>
</div>