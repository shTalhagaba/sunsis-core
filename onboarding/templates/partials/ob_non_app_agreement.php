<?php
$app_agreement_provider_url = in_array(DB_NAME, ["am_barnsley", "am_barnsley_demo"]) ? "www.barnsley.ac.uk" : "www.test.com";
$_clientName = (isset($provider) && !is_null($provider->legal_name)) ? 
    $provider->legal_name : 
    DAO::getSingleValue($link, "SELECT value FROM configuration WHERE entity = 'client_name'");
?>
<div class="row">
    <div class="col-sm-12">

        <div class="text-center">
            <img  width="230px" class="headerlogo" src="<?php echo $header_image1; ?>"/>
        </div>

	    <?php if($framework->fund_model == Framework::FUNDING_STREAM_ASF) { ?>
        <div class="well">
            <p>
                I confirm that I have received sufficient guidance from <?php echo $provider->legal_name; ?> about: the choice of courses available to me;
                course entry requirements; my suitability for the course; the financial and learning support available to me, as appropriate.
            </p>
            <p>
                I understand <?php echo $_clientName; ?> reserves the right to amend course arrangements as published, and merge or close classes if learner
                numbers cease to be viable. I agree to abide by the <?php echo $_clientName; ?>'s policies and procedures and Learner Code of Conduct (available
                at induction). I understand any breaches of these may result in disciplinary action being taken against me and my learning
                agreement terminated. I agree to provide evidence of eligibility to study with us including evidence of 3 years residency. I
                formally accept the learning programme specified on this form and confirm that all the information supplied on this form is
                correct. I understand if I have declared false information <?php echo $_clientName; ?> will take action against me to reclaim course fees and any
                associated costs. I give my consent to <?php echo $_clientName; ?> to record and process the information contained in this form where <?php echo $_clientName; ?>
                complies with its obligations under the GDPR guidelines. <?php echo $_clientName; ?>'s processing includes the use of CCTV to maintain the
                security of the premises, to prevent, detect and investigate crime. I understand <?php echo $_clientName; ?> have a no abuse tolerance to their
                staff or other learners, and that any breach of this will result in my quick removal from the course and premises, without the
                chance of returning. I agree to abide by the expectations of both <?php echo $_clientName; ?> as well as the course, and I am willing/able to
                attend all sessions of the course, as well as the set employment sessions. I understand that failure to attend the scheduled
                sessions may result in <?php echo $_clientName; ?> reclaiming course fees.
            </p>
            <p>
                By signing this enrolment declaration, I am confirming that the information I have provided is correct.
            </p>
            <p>
                By signing this form, I am giving consent for <?php echo $_clientName; ?> to process my enrolment in line with the guidance above. I have read
                and accept the terms of Employment Education Training Groups Privacy Policy.
            </p>
        </div>
        <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_BOOTCAMP) { ?>
        <div class="well">
            <p>
                I agree that initial assessment and information advice and guidance concerning the course has been provided to me, this
                included information about the course, its entry requirements, the implications of the choice of course, its suitability and the
                support which is available to me. I agree that the information given on this agreement is true, correct and completed to the
                best of my knowledge and I understand that (name of the provider) has the right to cancel my enrolment if it is found that I
                have provided false or inaccurate information. I agree that this information can be used to process my data for any purposes
                connected with my studies or my health and safety whilst on the premises. This also includes any other contractual
                requirements and, in particular to the disclosure of all the data on this form or otherwise collected about me to the DfE for the
                purposes noted in the GLA Privacy Notice and DfE Privacy Q&A which can be found below at Annex four and five. I also agree
                with the below points relating to my chosen programme:
            </p>
            <ul>
                <li>Take appropriate responsibility for my own learning, development and progression</li>
                <li>Attend and undertake training required to achieve the Skills Bootcamp identified in Programme Details in the ILP</li>
                <li>Promptly inform the Employer and/or <?php echo $_clientName; ?> if any matters or issues arise, or might arise, that will, or may, affect my
                learning, development and progression</li>
                <li>All times behave in a safe and responsible manner and in accordance with the statutory requirements of health and safety
                law relating to my responsibilities from time to time</li>
                <li>comply with the policies, regulations and procedures of my Employer and/or (name of provider), notified to me from time to
                time;</li>
            </ul>
            <p>
                If you wish to raise a complaint about how we have handled your personal data email to (provider's email address) or any
                other issues, please email (provider's emails address) with full details of your issue.
            </p>
            <p>
                If you are not satisfied how your complaint has been dealt with, please be aware of the Department of Education (DfE)
                Whistleblowing and Complaints policies and processes. Whistleblowing involves entering a 'whistleblowing' webform on the
                'Contact the Department for Education' page, which can be found on Complaints procedure - Department for Education - 
                GOV.UK (www.gov.uk)
            </p>
            <p>
                Whistleblowing entries for Skills Bootcamps must be clearly marked as 'Skills Bootcamps' and will submitted via the DfE's
                whistleblowing submission process and will be escalated to the relevant policy team. Please also copy in
                skillsbootcamps@london.gov.uk.
            </p>
        </div>
        <?php } elseif($framework->fund_model == Framework::FUNDING_STREAM_99) { ?>
        <div class="well">
            <p>
                I sign to confirm that the information I have given is correct and that I have read and understood the contents of this agreement. 
                I have received relevant information, advice, and guidance from the representative of <?php echo $provider->legal_name; ?>. 
                I have discussed the options available to me and have agreed this is the most appropriate training and funding based on my previous achievements and 
                future aspirations and eligibility.
            </p>
            <p>
                I agree to the payment structure and total negotiated programme price as detailed in the Agreement.
            </p>
            <p>
                The evidence I have provided confirms my eligibility to participate in this programme. 
                Should I not have already provided my Unique Learner Number (ULN), I consent to ELA Training Services accessing this on my behalf.
            </p>
            <p>
                <?php echo $provider->legal_name; ?> complaints procedure has been explained to me and should I wish to escalate any concerns.
            </p>
        </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table">
            <tr>
                <th><input class="clsICheck" type="checkbox" name="agree_app_agreement" value="" /><label>Click to agree</label></th>
                <td class="text-bold"><?php echo date('d/m/Y'); ?></td>
            </tr>
        </table>
    </div>
</div>