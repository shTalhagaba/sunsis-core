<?php /* @var $vo Organisation */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Organisation</title>
    <link rel="stylesheet" href="/common.css" type="text/css"/>
    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <script src="/common.js" type="text/javascript"></script>

    <script type="text/javascript" src="/yui/2.4.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>


    <script language="JavaScript">
        function deleteRecord()
        {
            if(window.confirm("Delete this College?"))
            {
                window.location.replace('do.php?_action=delete_college&id=<?php echo $vo->id; ?>');
            }
        }

        function populate()
        {
            var grid_level = document.getElementById('grid_level');
            grid_level.clear();
            var ty = "<?php echo $vo->organisation_type;?>";
            grid_level.setValues(ty.split(','));
        }

        function uploadFile() {
            var myForm = document.forms[1];
            myForm.submit();
        }


        //YAHOO.util.Event.onDOMReady(populate);


    </script>
</head>

<style type="text/css">
    .label
    {
        font-weight:bold;
    }

</style>

<body>
<div class="banner">
    <div class="Title"><?php echo $page_title ?></div>
    <div class="ButtonBar">
        <button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
        <?php if((int)$_SESSION['user']->type!=User::TYPE_SYSTEM_VIEWER){ ?>
        <button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_college');">Edit</button>

        <?php } ?>
    </div>
    <div class="ActionIconBar">
        <button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
        <button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
    </div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<h3>Name</h3>
<table border="0" cellspacing="4" cellpadding="4">
    <col width="150" />
    <tr><td class="fieldLabel">Legal name:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->legal_name); ?></td></tr>
	<tr><td class="fieldLabel">UKPRN:</td><td class="fieldValue"><?php echo htmlspecialchars((string)$vo->ukprn); ?></td></tr>
</table>

<!-- Hidden form for displaying a provider's UKRLP record -->
<form name="display_UKRLP_record" method="post" action="http://www.ukrlp.co.uk/ukrlp/ukrlp_provider.page_pls_searchProviders" target="_blank">
    <input type="hidden" name="pn_ukprn" value="<?php echo htmlspecialchars((string)$vo->ukprn); ?>" />
    <input type="hidden" name="x" value="" />
</form>

<h3>Locations</h3>
<?php if((int)$_SESSION['user']->type!=14){?>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_location&organisations_id=<?php echo $vo->id; ?>&back=<?php echo "college"; ?>'"> Add new location </span>
    <?php }$locations->render($link, 'read_college'); ?>


<h3>Notes</h3>
<?php if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==7 || $_SESSION['user']->type==8 || $_SESSION['user']->type==3 || $_SESSION['user']->type==4 || ($_SESSION['user']->type==1 && $_SESSION['user']->org->organisation_type!=2) || (DB_NAME=='am_baltic' && $_SESSION['user']->type==12) || (DB_NAME=='am_pathway' && $_SESSION['user']->type==22)) { ?>
<span class="button" style="margin-bottom: 15px;" onclick="window.location.href='do.php?_action=edit_crm_note&mode=new&organisations_id=<?php echo $vo->id; ?>&organisation_type=read_college'"> Add New Note</span>
    <?php } ?>
<?php $view2->render($link,'read_employer'); ?>


<h3>File Repository</h3>
<?php echo $html2;?>
<div>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_employer_repository" ENCTYPE="multipart/form-data">
        <input type="hidden" name="_action" value="save_employer_repository" />
        <input type="hidden" name="org_type" value="college" />
        <input type="hidden" name="emp_id" value="<?php echo $emp_id;?>" />

        <table border="0" cellpadding="4" cellspacing="4" style="margin-left:10px">
            <col width="150" />
            <tr>
                <td class="fieldLabel_compulsory">File to upload:</td>
                <?php
                // re - 01/03/2012 - changed the form element name #22414
                //    - there are too many things called uploadFile around here
                //    - for clarity.  Also removed camelcase and replaced with
                //    - underscored word separation as above support request
                //    - was caused by camelcase issue.
                ?>
                <td>
                    <input class="compulsory" type="file" name="uploaded_employer_file"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt,.xml,.zip,.rar,.7z" />&nbsp;
                    <span id="uploadFileButton" class="button" onclick="uploadFile()">&nbsp;Upload&nbsp;</span>
                </td>
            </tr>
        </table>
    </form>
</div>


<h3>Students</h3>
<?php $this->renderLearners($link, $vo);  ?>

</body>
</html>