<div class="container contact">
    <div class="header">
        <div class="title">Contact us</div>
    </div>
    <div class="content">

        <div class="images-content">
            <img src="<?= base_url("statics/images/contact/contact-message.svg") ?>" alt="contact-message.svg">
        </div>

        <form pcs-generic-handler-js class="text-content" contact-form action="<?= isset($action) ? $action : "" ?>" method="POST">

            <input type="hidden" is-local value="<?= isset($is_local) ? $is_local : 1 ?>">

            <div class="field mini">
                <label for="plans">Plans *</label>
                <select class="form-select" required name="plan">
                    <option value="basic">Basic</option>
                    <option value="standard">Standard</option>
                    <option value="premium">Premium</option>
                    <option value="ultra">Ultra</option>
                    <!-- <option value="desinfection">Desinfection Services</option> -->
                    <!-- <option value="nonocuppied">Non occupied unit</option> -->
                </select>
            </div>
            <div class="field mini">
                <label for="Description of Residence">Description of Residence *</label>
                <select class="form-select" required name="residence">
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                </select>
            </div>
            <div class="field">
                <label for="Full name">Full name *</label>
                <input type="text" class="form-control" placeholder="Full name" required name="full_name">
            </div>

            <div class="field middle">
                <label for="Phone">Phone *</label>
                <input type="text" class="form-control" placeholder="Phone" required name="phone">
            </div>

            <div class="field middle">
                <label for="E-mail">E-mail *</label>
                <input type="email" class="form-control" placeholder="E-mail" required name="email">
            </div>

            <div class="field middle">
                <label for="Address">Address *</label>
                <input type="text" class="form-control" placeholder="Address" required name="address">
            </div>

            <div class="field full">
                <label for="message">Tell us, how can we help you? *</label>
                <textarea rows="4" class="form-control" placeholder="Tell us, how can we help you?" required name="message"></textarea>
            </div>

            <div class="field check full">
                <input class="form-check-input primary" type="checkbox" id="policy-check" required name="privacy_policy">
                <label class="form-check-label" for="policy-check">
                    By using this form you agree with the storage and handling of your data by this website in
                    accordance with our <a class="text-primary" href="<?= get_route("public-policy") ?>">data treatment policy</a>
                    *
                </label>
            </div>
            <div class="field check full">
                <input class="form-check-input primary" type="checkbox" id="promotional-check" name="send_promo">
                <label class="form-check-label" for="promotional-check">
                    Yes, I would like to receive updates and special offers from MTS CORPORATION
                </label>
            </div>

            <div class="field full left">
                <button type="submit" class="g-recaptcha primary middle" contact-submit>Send</button>
            </div>
        </form>
    </div>
</div>