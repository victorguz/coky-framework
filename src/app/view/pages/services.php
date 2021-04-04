<?php if (isset($home) && $home == true) : ?>
    <div class="services-home">
        <div class="header">
            <div class="title">Our services</div>
            <div class="slogan">What can we do for you today?</div>
        </div>
        <div class="grid">
            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back1.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service1.svg") ?>" alt="service-image" class="service">
                </div>
                <span>General Cleaning</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back2.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service2.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Plumbing</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back3.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service3.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Hausehold Laundry</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back4.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service4.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Cleaning Consumables</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back5.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service5.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Painting</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back6.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service6.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Sanitary Consumables</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back7.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service7.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Lighting</span>
            </div>

            <div class="item">
                <div class="images hover">
                    <img src="<?= base_url("statics/images/services/categories/trazados/back8.svg") ?>" alt="back" class="back">
                    <img src="<?= base_url("statics/images/services/categories/service8.svg") ?>" alt="service-image" class="service">
                </div>
                <span>Gardening</span>
            </div>
        </div>

        <a class="ui primary mini button" routerLink="/services">Details</a>

    </div>

<?php endif; ?>

<?php if (!isset($home) || (isset($home) && $home == false)) : ?>
    <div class="container services">
        <div class="header">
            <div class="title">Services</div>
            <div class="slogan">In which type of residence you need our assistance?</div>
            <div class="images-container">
                <div class="item left">
                    <div class="title">Apartment</div>
                    <img src="<?= base_url("statics/images/services/apartment.svg") ?>" alt="apartment">
                </div>
                <div class="item">
                    <div class="title">House</div>
                    <img src="<?= base_url("statics/images/services/house.svg") ?>" alt="house">
                </div>
            </div>
        </div>

        <div class="content">
            <div class="title">What can we do for you today?</div>
            <div class="grid">
                <div class="item card hover">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back1.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service1-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>General Cleaning</span>
                    </div>
                    <p>Detailed total cleaning of your house or apartment, with the best team of technicians
                        and
                        operators</p>
                </div>

                <div class="item card hover right">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back2.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service2-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Plumbing</span>
                    </div>
                    <p>Repairing of sanitary connections, taps, pipeparts, storm drains, sanitary parts,
                        cleaning and unclogging of drains</p>
                </div>

                <div class="item card hover">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back3.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service3-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Hausehold Laundry</span>
                    </div>
                    <p>Professional bedding laundry</p>
                </div>

                <div class="item card hover right">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back4.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service4-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Cleaning Consumables</span>
                    </div>
                    <p>All leaning consumables are included in the service charge.</p>
                </div>

                <div class="item card hover">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back5.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service5-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Painting</span>
                    </div>
                    <p>Major and minor repairs of walls and roofs, including exterior roofing. Painting is a
                        continous monthly service in order
                        to keep walls and ceilings impecable at all times.</p>
                </div>

                <div class="item card hover right">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back6.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service6-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Sanitary Consumables</span>
                    </div>
                    <p>Toillet, napkins and blotting paper as well as bath and hand soap are included.</p>
                </div>

                <div class="item card hover">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back7.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service7-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Lighting</span>
                    </div>
                    <p>Replacement of lighbubs/lamps; and refloectors in general. Fixture deteroration is
                        covered.</p>
                </div>

                <div class="item card hover right">
                    <div class="images-content">
                        <div class="images">
                            <img src="<?= base_url("statics/images/services/categories/trazados/back8.svg") ?>" alt="back" class="back">
                            <img src="<?= base_url("statics/images/services/categories/service8-dark.svg") ?>" alt="service-image" class="service">
                        </div>
                        <span>Gardening</span>
                    </div>
                    <p>Total gardening services including weeding, pruning, cleaning, spraying, fertilization
                        and plant replacement due to
                        biological deterioration</p>
                </div>
            </div>
        </div>

        <div class="list noselection">
            <div class="item card hover">Electricity</div>
            <div class="item card hover">Fire Detection System</div>
            <div class="item card hover">Civil works and bricklaying</div>
            <div class="item card hover">Automation in general</div>
            <div class="item card hover">Locksmith</div>
            <div class="item card hover">Fumigation</div>
            <div class="item card hover">Access control</div>
            <div class="item card hover">Resident personnel for VIP attention</div>
        </div>


        <div class="end">
            <div class="title">Interested?</div>
            <div class="buttons">
                <a class="ui primary mini button" [routerLink]="['/contact']">Contact us</a>
                <a class="ui primary mini button" [routerLink]="['/plans']">Our plans</a>
            </div>
        </div>

    </div>

<?php endif; ?>