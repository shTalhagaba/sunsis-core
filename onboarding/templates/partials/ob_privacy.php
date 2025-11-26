<?php
$selected_rui = $tr->RUI != '' ? explode(',', $tr->RUI) : [];
$selected_pmc = $tr->PMC != '' ? explode(',', $tr->PMC) : [];
$selected_disclaimer = $tr->disclaimer != '' ? explode(',', $tr->disclaimer) : [];
?>
<div class="row">
    <div class="col-sm-12">
        <div style="text-align: justify; text-justify: inter-word;">
            <?php
            if(isset($tr) && $tr->practical_period_start_date >= '2023-05-01') 
                include_once(__DIR__ . '/privacy_notice/version_1stMay2023.php'); 
            else 
                include_once(__DIR__ . '/privacy_notice/version_old.php'); 
            ?>

            <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
                <img height="50px" style="background-color: #ffffff;" class="pull-right" src="<?php echo in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $header_image1 : $provider->provider_logo; ?>" />
                <h4>GDPR</h4>How we use your personal data
            </div>
            <div class="well">
                <p>As you are aware <?php echo $provider->legal_name; ?> is your training provider. We want to be transparent with you about how we collect, process and store your data</p>
                <h4><strong>What information do we need?</strong></h4>
                <ul style="margin-left: 15px;">
                    <li>Your contact details and personal characteristics</li>
                    <li>Medical information we need to know to keep you sake</li>
                    <li>Academic progress and attendance records</li>
                    <li>Support needs and other pastoral information</li>
                    <li>What you do next once you've finished your apprenticeship</li>
                </ul>
                <h4><strong>We will use your personal data in a number of ways, such as:</strong></h4>
                <ul style="margin-left: 15px;">
                    <li>Support and monitor your learning, progress and achievement</li>
                    <li>Provide you with advice, guidance and pastoral support</li>
                    <li>Analyse our performance</li>
                    <li>Meet our legal obligations</li>
                </ul>
                <h4><strong>Where do we keep your data?</strong></h4>
                <p>The information we collect about you is used by our staff in the UK. All of our data is stored in the UK, and our electronic data is stored on servers in the UK.</p>
                <h4><strong>How long do we keep your data?</strong></h4>
                <p>We are required to keep all documents, information, data, reports, accounts, records or written or verbal explanations relating to your apprenticeship for a minimum of 6 years after the end of your apprenticeship.</p>
                <h4><strong>Who will we share your information with?</strong></h4>
                <p>We may share information about you with certain other organizations, or get information about you from them. These other organisation's include government departments, local authorities and examination boards.</p>
                <p>We are required by law to provide certain information about you to the Education and Skills Funding Agency. We may also haveto provide information to the European Social Fund (ESF).</p>
                <p>We will not give your information about you to anyone without your consent unless the law or policies allow us to do so.</p>
                <h4><strong>Contacting you</strong></h4>
                <p>We will contact you about your attendance, learning, progress and assessment in respect of the course you are studying.</p>

                <div class="table-responsive">
                    <table class="table table-bordered text-blue">
                        <col width="70%">
                        <col width="30%">
                        <tr>
                            <th colspan="2">
                                You can <u>agree</u> to be contacted for other purposes by ticking any of the following boxes:
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <input class="clsICheck" type="checkbox" name="RUI[]" value="1" <?php echo in_array(1, $selected_rui) ? 'checked' : ''; ?> /><label>About courses or learning opportunities.</label>
                                <br>
                                <input class="clsICheck" type="checkbox" name="RUI[]" value="2" <?php echo in_array(2, $selected_rui) ? 'checked' : ''; ?> /><label>For surveys and research.</label>
                            </td>
                            <td>
                                <input class="clsICheck" type="checkbox" name="PMC[]" value="1" <?php echo in_array(1, $selected_pmc) ? 'checked' : ''; ?> /><label>By post</label>
                                <br>
                                <input class="clsICheck" type="checkbox" name="PMC[]" value="2" <?php echo in_array(2, $selected_pmc) ? 'checked' : ''; ?> /><label>By phone</label>
                                <br>
                                <input class="clsICheck" type="checkbox" name="PMC[]" value="3" <?php echo in_array(3, $selected_pmc) ? 'checked' : ''; ?> /><label>By email</label>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>



            <div class="table-responsive">
                <table class="table table-bordered  text-blue">
                    <tr>
                        <th>
                            <u>Consent</u>:
                        </th>
                    </tr>
                    <tr class="bg-gray">
                        <td>
                            <input class="clsICheck" type="checkbox" name="disclaimer[]" value="1" <?php echo in_array(1, $selected_disclaimer) ? 'checked' : ''; ?> /><label>I agree to adhere to the rules and regulations of the Data Protection Act 1998 and the Freedom of Information Act 2000, ensuring high standards in the returning and communication of personal information and giving a general right of access to all recorded information held by public authorities, including educational establishments.</label>
                        </td>
                    </tr>
                    <tr class="bg-gray">
                        <td>
                            <input class="clsICheck" type="checkbox" name="disclaimer[]" value="2" <?php echo in_array(2, $selected_disclaimer) ? 'checked' : ''; ?> /><label>I agree to promote and adhere to Equal Opportunity and Diversity policies on race, gender, age, disability, religion or belief and sexual orientation within the Apprenticeship Programme.</label>
                        </td>
                    </tr>
                    <tr class="bg-gray">
                        <td>
                            <input class="clsICheck" type="checkbox" name="disclaimer[]" value="3" <?php echo in_array(3, $selected_disclaimer) ? 'checked' : ''; ?> /><label>I have read and understood GDPR statement regarding my personal data.</label>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>