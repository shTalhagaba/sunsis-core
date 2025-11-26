<div class="row">
    <div class="col-sm-12">
        <div class="nts-secondary-teaser-gradient" style="padding: 5px; border-radius: 5px;">
            <h4>Details of Criminal Convictions</h4>
        </div><br>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="have_criminal_conviction" class="col-sm-4 control-label fieldLabel_compulsory">Do you have criminal conviction:</label>
            <div class="col-sm-8">
                <div class="col-sm-8">
                    <?php echo HTML::selectChosen('have_criminal_conviction', LookupHelper::getDDLYesNo(), $criminal_conviction_details->have_criminal_conviction, true, true); ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="motoring_offence" class="col-sm-4 control-label fieldLabel_compulsory">Is it a motoring offence:</label>
            <div class="col-sm-8">
                <div class="col-sm-8">
                    <?php echo HTML::selectChosen('is_it_motoring_conviction', LookupHelper::getDDLYesNo(), $criminal_conviction_details->is_it_motoring_conviction, true, true); ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-default text-info">
            <p>Please Note: You are not required to include details of criminal conviction/s which are spent in accordance
                with the Rehabilitation of Offenders Act 1974.  The National Association for the Care & Resettlement of
                Offenders (NACRO), the Youth Offending Service, the Probation Service and the Citizen's Advice Bureau
                are able to give advice on whether convictions are spent.  If you are applying to study on a course where
                an Enhanced DBS Check is required, please state convictions which are ‘Spent’ and ‘Unspent’, including Warnings,
                Reprimands, Cautions or Referral Orders.</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-responsive row-border cw-table-list ">
            <tr class="bg-gray">
                <th style="width: 15%;">Date of conviction</th>
                <th style="width: 15%;">Nature of offence</th>
                <th style="width: 20%;">Sentence (include both length & type of sentence, e.g. YRO, caution, custodial)</th>
            </tr>
            <?php
            $details_of_criminal_conviction = json_decode($criminal_conviction_details->details);
            for($i = 1; $i <= 8; $i++)
            {
                $co_date = '';$co_nature = '';$co_sentence = '';
                if(isset($details_of_criminal_conviction[$i-1]))
                {
                    $_co_entry = (array)$details_of_criminal_conviction[$i-1];
                    $co_date = $_co_entry["co_date_of_conviction{$i}"];
                    $co_nature = $_co_entry["co_nature_of_offence{$i}"];
                    $co_sentence = $_co_entry["co_sentence{$i}"];
                }
                echo '<tr>';
                echo '<td><input class="datecontrol form-control" type="text" name="co_date_of_conviction'.$i.'" id="input_co_date_of_conviction'.$i.'" value="'.$co_date.'" size="10" maxlength="10" placeholder="dd/mm/yyyy" /></td>';
                echo '<td><textarea name="co_nature_of_offence'.$i.'" id="co_nature_of_offence'.$i.'" rows="3" style="width: 100%;">'.$co_nature.'</textarea></td>';
                echo '<td><textarea name="co_sentence'.$i.'" id="co_sentence'.$i.'" rows="3" style="width: 100%;">'.$co_sentence.'</textarea></td>';
                echo '</tr>';
            }
            ?>
        </table>
    </div>
</div>

<p><br></p>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="working_with_agencies" class="col-sm-4 control-label fieldLabel_compulsory">Are you working with any other agencies</label>
            <div class="col-sm-8">
                <?php echo HTML::selectChosen('working_with_agencies', LookupHelper::getDDLYesNo(), $criminal_conviction_details->working_with_agencies, true, true); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="motoring_offence" class="col-sm-4 control-label fieldLabel_compulsory">Please include the name and contact details of the agencies/workers that you are working with:</label>
            <div class="col-sm-8">
                <textarea name="details_of_agencies" id="details_of_agencies" style="width: 100%;" rows="5"><?php echo $criminal_conviction_details->details_of_agencies; ?></textarea>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-default text-success">
            <p>The College recognises that the information on this form constitutes sensitive personal data and by
                signing below you explicitly consent for the College collecting, holding, and otherwise processing this data,
                which may include liaising with any other agencies you are working with. The College will process this data
                only for legitimate reasons and will do so in a way that does not unjustifiably prejudice your own interests. </p>
        </div>
    </div>
</div>
