<?php if (isset($home) && $home == true) : ?>
    <div class="card about-home">
        <div class="images-content">
            <img src="<?= base_url("statics/images/about/cleaning.svg") ?>" alt="mts-cleaning" class="cleaning">
            <img src="<?= base_url("statics/images/about/painting.svg") ?>" alt="mts-painting" class="painting">
        </div>

        <div class="text-content">
            <div class="header right text-dark">
                <div class="title">About us</div>
                <p>
                    <strong>MTS Corporation</strong> is a company specialized in facility management, focused on
                    providing
                    highly efficient infrastructure maintenance, based on preventive, predictive and corrective
                    activities
                    in a very PROACTIVE way. Our mother Company, <strong>NATURARTE C.A</strong>, with more than
                    25 years of experience in the market, provides world class companies such as AIG, Procter &
                    Gamble and INDRA among others, high performance corporate maintenance services in Latin America and
                    the Caribbean.
                </p>
                <a class="ui primary padding button" href="<?= get_route("public-about") ?>">Read more</a>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!isset($home) || (isset($home) && $home == false)) : ?>
    <div class="container about">
        <div class="header">
            <div class="title">About us</div>
        </div>
        <div class="images-content">
            <img src="<?= base_url("statics/images/about/about-page.svg") ?>" alt="mts-about-page">
        </div>

        <div class="header">
            <div class="slogan bold">WE PROVIDE EXCEPTIONAL MAINTENANCE SERVICES, MINIMIZING ASSET DETERIORATION
            </div>

        </div>

        <div class="card text-content">
            <img src="<?= base_url("statics/images/logo-navbar-dark.svg") ?>" alt="logo-mts-corp">
            <p class=" presentation">
                <strong>MTS Corporation</strong> is a company specialized in facility management, focused on
                providing
                highly efficient infrastructure maintenance, based on preventive, predictive and corrective
                activities
                in a very PROACTIVE way. Our mother Company, <strong>NATURARTE C.A</strong>, with more than
                25 years of experience in the market, provides world class companies such as AIG, Procter &
                Gamble and INDRA among others, high performance corporate maintenance services in Latin America
                and
                the Caribbean. In the US, our goal is to provide a comprehensive highly competitive maintenance
                service for residential vacation properties.
            </p>

            <div class="contact-data">
                <div class="content-1">
                    <a>
                        152-11 89th Ave Queens Unit 631 11432
                    </a>
                </div>
                <div class="content-2">
                    <a href="tel:+1-917-496-0563">
                        +1-917-496-0563
                    </a>
                    <a href="mailto:info@mtservicescorp.com">
                        info@mtservicescorp.com
                    </a>
                </div>
            </div>

            <div class="buttons flex-row">

                <a href="<?= get_route("public-contact", ["type" => "all"]) ?>" class="ui primary fluid button">Contact us</a>
                <a href="http://naturarte.com/" class="ui secondary fluid button">Naturarte</a>

            </div>

        </div>

        <?= $this->render('pages/imports/carousel-home'); ?>
        <?= $this->render('pages/plans', ["home" => true]); ?>
    </div>
<?php endif; ?>