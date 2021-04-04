<?php
defined("BASEPATH") or die("<h1>El script no puede ser accedido directamente</h1>");
?>

<section class="footer">
    <div class="content">
        <div class="content-1">
            <a class="link brand">
                <img src="<?= base_url("statics/images/logo-navbar.svg") ?>" alt="logo-mts-corp">
            </a>
            <p>All rights reserved 2021</p>
            <div class="divisor">
                <a href="<?= get_route("public-terms") ?>" class="link underlined">Terms</a>
                <a href="<?= get_route("public-policy") ?>" class="link underlined">Polices</a>
            </div>
        </div>
        <div class="content-2">
            <a href="<?= get_route("public-contact", ["type" => "all"]) ?>" class="link underlined">Contact us</a>
            <a class="link">or</a>
            <a href="mailto:info@mtservicescorp.com" class="link underlined">Email us</a>
        </div>
    </div>
    <div class="content">
        <span class="developed">Designed and developed by Victorguz and Ivanlnd.</span>
        <span class="developed">Powered by CokyCMS</span>
    </div>
</section>
</div>
</div>

<!-- Scripts -->
<?php load_js(['base_url' => "", 'custom_url' => ""]) ?>
</body>

</html>