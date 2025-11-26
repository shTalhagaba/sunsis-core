<?php /* @var $ob_learner OnboardingLearner */ ?>
<?php $frm_archive_learner_panel_class = $ob_learner->archive == 'N' ? 'danger' : 'success' ?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-<?php echo $frm_archive_learner_panel_class; ?>">
            <div class="box-header"><span class="box-title with-header"><?php echo $ob_learner->archive == 'N' ? 'Archive' : 'Unarchive'; ?> Learner (<?php echo $ob_learner->firstnames  . ' ' . $ob_learner->surname; ?>) </span></div>
            <div class="box-body">
                <div class="pad margin no-print">
                    <div class="callout callout-<?php echo $frm_archive_learner_panel_class; ?>" style="margin-bottom: 0!important;">
                        <i class="fa fa-info-circle"></i>
                        Use this panel to <?php echo $ob_learner->archive == 'N' ? 'archive' : 'unarchive'; ?> this learner record
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <form name="frm_archive_learner" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="_action" value="ajax_helper" />
                    <input type="hidden" name="subaction" value="update_archive_status" />
                    <input type="hidden" name="frm_archive_learner_ob_learner_id" value="<?php echo $ob_learner->id; ?>" />
                    <span onclick="updateArchiveStatus();" class="btn btn-xs btn-<?php echo $frm_archive_learner_panel_class; ?> btn-block">
                        <i class="fa fa-file-archive-o"></i> Click here to <?php echo $ob_learner->archive == 'N' ? 'archive' : 'unarchive'; ?>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateArchiveStatus()
    {
        var myForm = document.forms["frm_archive_learner"];
        client = ajaxPostForm(myForm);
        if(client)
        {
            alert(client.responseText);
            window.location.reload();
        }
    }
</script>