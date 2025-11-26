<div class="row">
    <div class="col-sm-12">
        <p class="pull-right">
            <!-- <span class="btn btn-xs btn-info" onclick=""><i class="fa fa-file-pdf-o"></i> Download</span> -->
        </p>
    </div>
    <div class="col-sm-12">
        <?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN || $framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL) { ?>
        <div class="well">
            <h5 class="text-bold lead">Learner</h5>
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
                Should I not have already provided my Unique Learner Number (ULN), I consent to <?php echo $provider->legal_name; ?> accessing this on my behalf.
            </p>
            <p>
                <?php echo $provider->legal_name; ?> complaints procedure has been explained to me and should I wish to escalate any concerns.
            </p>
        </div>
        <div class="well">
            <h5 class="text-bold lead">Training Provider</h5>
            <p>
                I  sign to confirm that, to the best of my knowledge, the information on this Agreement is correct. I declare that I have supported the learner in the completion of this document where necessary, and that the above-named learner meets the eligibility conditions to commence on this programme. I can confirm that all parties will receive a copy of this learning agreement. 
            </p>
            <p>
                The training programme has been explained to the Learner and Employer and will define the training and competence objectives to be achieved. <?php echo $provider->legal_name; ?> representatives will undertake regular reviews of progress and provide the Learner with on-going information, advice, and guidance. <?php echo $provider->legal_name; ?> representatives will support the Learner to achieve the learning aims and objectives outlined in this agreement. Alongside this, the Learner will be given access to their portfolio system which will show details of their training programme at the commencement of training and will be updated throughout the programme.
            </p>
        </div>
        <?php } ?>
    </div>
    <div class="col-sm-12">
        <div class="box box-primary box-solid">
            <div class="box-header">
                <span class="box-title">Signatures</span>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size: medium;">
                        <tr>
                            <th>Learner</th>
                            <td>
                                <img id="img_learner_sign" src="do.php?_action=generate_image&<?php echo $tr->learner_sign != '' ? $tr->learner_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo Date::toShort($tr->learner_sign_date); ?>
                            </td>
                        </tr>
                        <?php if($tr->employer_id != null && $tr->employer_id != Organisation::notEmployerId($link)) { ?>
                        <tr>
                            <th>Employer</th>
                            <td>
                                <img id="img_emp_sign" src="do.php?_action=generate_image&<?php echo $tr->emp_sign != '' ? $tr->emp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo Date::toShort($tr->emp_sign_date); ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <th>Training Provider</th>
                            <td>
                                <img id="img_tp_sign" src="do.php?_action=generate_image&<?php echo $tr->tp_sign != '' ? $tr->tp_sign : 'title=Not yet signed&font=Signature_Regular.ttf&size=25'; ?>" style="border: 2px solid;border-radius: 15px;" /><br>
                                <?php echo Date::toShort($tr->tp_sign_date); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>