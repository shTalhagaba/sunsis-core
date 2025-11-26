<footer class="footer mt-auto footer footer-default">
    <hr style="width: 100%; background-color: grey; margin: 0px;">

    <div class="logo-container" style="margin-top: 15px; padding: 15px;">
        <?php if($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
            <img src="images/logos/SFL_SkillsBootcamp_BlackBox_RGB.png" alt="Skills For Life - Skills Bootcamp Logo">
            <img src="images/logos/Funded by UK Gov-01.png" alt="Funded by UK Govt. Logo">
            <?php if(DB_NAME == 'am_puzzled') { ?>
                <img src="images/logos/symca.jpg" alt="South Yorkshire Mayoral Combined Authority Logo">
            <?php } else { ?> ?>
                <img src="images/logos/Mayor_of_London_logo1.svg" alt="Mayor of London Logo">
            <?php } ?>            
        <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_ASF) { ?>
            <img src="images/logos/Mayor_of_London_logo1.svg" alt="Mayor of London Logo">
        <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_APP) { ?>
            <img src="images/logos/apprenticeship.png" alt="Apprenticeship Logo">
            <img src="images/logos/dfe-logo.png" height="100px" width="150px" alt="Department for Education Logo">
        <?php } ?>
    </div>

    <div class="container-fluid" style="margin-top: 15px; padding: 15px;">
        <div class="max-width-sections p-2 mt-5 p-5">
            <div class="row align-items-top">
                <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                    <?php echo (isset($provider)) ? '<i class="fa fa-bank"></i> ' . $provider->legal_name . '<br>' : ''; ?>
                    <?php echo (isset($provider_location) && $provider_location->telephone != '') ? '<i class="fa fa-phone"></i> <a href="tel:' . $provider_location->telephone . '">' . $provider_location->telephone . '</a> <br>' : ''; ?>
                    <?php echo (isset($provider_location) && $provider_location->contact_email != '') ? '<i class="fa fa-envelope"></i> <a href="mailto:' . $provider_location->contact_email . '" class="phone-email-inherit">' . $provider_location->contact_email . '</a> <br>' : ''; ?>
                    <?php if (isset($provider_location)) {
                        echo $provider_location->address_line_1 != '' ? '<i class="fa fa-building"></i> ' . $provider_location->address_line_1 . ', ' : '';
                        echo $provider_location->address_line_2 != '' ? $provider_location->address_line_2 . ', ' : '';
                        echo $provider_location->address_line_3 != '' ? $provider_location->address_line_3 . ', ' : '';
                        echo $provider_location->address_line_4 != '' ? $provider_location->address_line_4 . ', ' : '';
                        echo $provider_location->postcode != '' ? $provider_location->postcode . '<br>' : '';
                    } ?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-12 text-center p-2">
                    <?php
                    $twitter_handle = SystemConfig::getEntityValue($link, "twitter_handle");
                    $linkedin_handle = SystemConfig::getEntityValue($link, "linkedin_handle");
                    $facebook_handle = SystemConfig::getEntityValue($link, "facebook_handle");
                    $youtube_handle = SystemConfig::getEntityValue($link, "youtube_handle");

                    if ($twitter_handle != '') {
                        echo '<a href="' . $twitter_handle . '" class="p-2"><i class="fa fa-twitter fa-2x"></i></a> &nbsp;';
                    }
                    if ($linkedin_handle != '') {
                        echo '<a href="' . $linkedin_handle . '" class="p-2"><i class="fa fa-linkedin fa-2x"></i></a> &nbsp;';
                    }
                    if ($facebook_handle != '') {
                        echo '<a href="' . $facebook_handle . '" class="p-2"><i class="fa fa-facebook fa-2x"></i></a> &nbsp;';
                    }
                    if ($youtube_handle != '') {
                        echo '<a href="' . $youtube_handle . '" class="p-2"><i class="fa fa-youtube fa-2x"></i></a> &nbsp;';
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <hr style="width: 100%; background-color: grey; margin: 0px;">

    <section class="footer-copyright text-center">
        <span class="copy-right-txt-1 ">
            &copy; Perspective (UK) Ltd. Powered by <a href="https://www.perspectiveuk.org/index.html" target="_blank">Sunesis</a></span>
    </section>

</footer>