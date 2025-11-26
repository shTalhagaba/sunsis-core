<div class="row">
    <div class="col-sm-12">
        <div class="callout callout-default">
            <p>
                <i class="text-muted">Please use the <span style="margin-top: 2px;" class="btn btn-info btn-sm" onclick="window.open('PriorAttainmentGuidance2018_19.pdf', '_blank')"><i class="fa fa-info-circle"></i> Guidance Notes</span>
                    to let us know the overall level of prior attainment of your qualifications achieved to date.<br>For example,</i>
            </p>
            <ul style="margin-left: 25px;">
                <li><i class="text-muted">if you have 4 GCSE's with Grades A - C, this would fall into Level 1</i></li>
                <li><i class="text-muted">if you have 5 GCSE's with Grades A - C, this would fall into Level 2</i></li>
            </ul>           
            
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="confidential_interview" class="fieldLabel_compulsory">I consider my Prior Attainment Level to be: *</label>
            <?php echo HTML::selectChosen('prior_attainment', $priorAttainDdl, $registration->prior_attainment, true, false, true);?>
        </div>
        
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="confidential_interview" class="fieldLabel_compulsory">
                If you have completed a level 6 qualification or higher, please select which subject this was in: *
            </label>
            <?php echo HTML::selectChosen('level6_subject', $subjectsDdl, $registration->level6_subject, true, false, true);?>
        </div>
        
    </div>
</div>