<!DOCTYPE html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>File Repository</title>
	<link rel="stylesheet" href="module_tracking/css/common.css" type="text/css" />
	<link rel="stylesheet" href="/assets/adminlte/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/adminlte/plugins/jQueryUI/jquery-ui.css">
	<link rel="stylesheet" href="/assets/adminlte/dist/css/AdminLTE.min.css">

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style>
        .row.is-flex {
            display: flex;
            flex-wrap: wrap;
        }
        .row.is-flex > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
    </style>

</head>

<body>

	<div class="row">
		<div class="col-lg-12">
			<div class="banner">
				<div class="Title" style="margin-left: 6px;">File Repository for <?php echo $tr->firstnames . ' ' . $tr->surname; ?></div>
				<div class="ButtonBar">
					<span class="btn btn-xs btn-default" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious(); ?>';"><i class="fa fa-arrow-circle-o-left"></i> Cancel</span>
					
				</div>
				<div class="ActionIconBar">

				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<?php $_SESSION['bc']->render($link); ?>
		</div>
	</div>
	<br>

	<div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel-body fieldValue">
                <span class="text-bold"><?php echo $tr->firstnames . ' ' . $tr->surname; ?></span><br>
                <span class="text-info"><?php echo DAO::getSingleValue($link, "SELECT title FROM contracts WHERE id = '{$tr->contract_id}'"); ?></span><br>
            </div>
		</div>
	</div>
    <p><br></p>
    <div class="row">
        <div class="col-sm-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <span class="box-title">Sections</span>
                    <div class="box-tools pull-right">
                        <span class="btn btn-primary btn-xs" onclick="createSection();return false;">Add Section</span>
                    </div>
                </div>
                <div class="box-body">
                    <a href="do.php?_action=training_file_repo&tr_id=<?php echo $tr->id; ?>">
                        <div class="divFolder panel-body fieldValue <?php echo (trim($folder) == '' || $folder == $tr->username) ? 'bg-warning' : ''; ?>">
                            <h5 class="text-bold">
                                <i class="fa fa-folder<?php echo (trim($folder) == '' || $folder == $tr->username) ? '-open' : ''; ?> fa-lg"></i> <?php echo trim($tr->username); ?>
                            </h5>
                            <span class="text-info small"><?php echo Repository::getNumberOfFilesInDirectory($learner_dir); ?> files</span>
                        </div>
                    </a>
                    <?php 
                        
                        foreach ($existing_folder_names as $_folder) 
                        {
                            echo '<a href="do.php?_action=training_file_repo&tr_id='.$tr->id.'&folder='.trim($_folder).'">';
                            echo $_folder == trim($folder) ? '<div style="margin-top: 5px;" class="divFolder panel-body fieldValue bg-warning">' : '<div style="margin-top: 5px;" class="divFolder panel-body fieldValue">';
                            echo $_folder == trim($folder) ? '<h5 class="text-bold"><i class="fa fa-folder-open fa-lg"></i> ' . $_folder . '</h5>' : '<h5 class="text-bold"><i class="fa fa-folder fa-lg"></i> ' . $_folder . '</h5>';
                            echo '<span class="text-info small"> ' . Repository::getNumberOfFilesInDirectory($learner_dir.'/'.$_folder) . ' files</span>';
                            echo '</div>';
                            echo '</a>';
                        }

			if(in_array(DB_NAME, ["am_crackerjack", "am_demo", "am_ela", "am_sd_demo"]))
                        {
                            echo '<a href="do.php?_action=training_file_repo&tr_id='.$tr->id.'&folder=SunesisOnboardingModule">';
                            echo $folder == 'SunesisOnboardingModule' ? '<div style="margin-top: 5px;" class="divFolder panel-body fieldValue bg-warning">' : '<div style="margin-top: 5px;" class="divFolder panel-body fieldValue">';
                            echo $folder == 'SunesisOnboardingModule' ? '<h5 class="text-bold"><i class="fa fa-folder-open fa-lg"></i> SunesisOnboardingModule</h5>' : '<h5 class="text-bold"><i class="fa fa-folder fa-lg"></i> SunesisOnboardingModule</h5>';
                            echo '</div>';
                            echo '</a>';
                        }

                    ?>                    
                </div>
            </div>            
        </div>
        <div class="col-sm-8">
            <div class="box box-success">
                <div class="box-header with-border">
                    <span class="box-title ">Files</span>
                    <div class="box-tools pull-right">
                        
                    </div>
                </div>
                <div class="box-body">
			<?php if($folder != 'SunesisOnboardingModule'){?> 
                    <div class="row">
                        <div class="col-sm-6">
                            <form name="uploadFileForm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=save_update" ENCTYPE="multipart/form-data">
                                <input type="hidden" name="_action" value="save_update" />
                                <input type="hidden" name="username" value = "<?php echo $tr->username;?>"/>
                                <input type="hidden" name="tr_id" value = "<?php echo $tr->id;?>"/>
                                <input type="hidden" name="section" value = "<?php echo $folder;?>"/>

                                <input class="compulsory" type="file" name="uploadedfile" id="uploadedfile"/><br>
                                <button type="button" id="uploadFileButton" class="btn btn-sm btn-primary" onclick="uploadFile();"><i class="fa fa-upload"></i> Upload File</button>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="callout callout-info">
                                Maximum file size is 6 MB<br>
                                Allowed file types are pdf, doc, docx, xls, xlsx, csv, txt, xml, zip, rar, 7z, amr, jpeg, jpg
                            </div>
                        </div>
                    </div>
			<?php } ?>
                    <hr>
                    <?php
                        if(count($files) > 0)
                        {
                            echo '<div class="row is-flex">';
                            foreach($files as $f)
                            {
                                if($f->isDir()){
                                    continue;
                                }
                                $ext = new SplFileInfo($f->getName());
                                $ext = $ext->getExtension();
                                $image = 'fa-file';
                                if($ext == 'doc' || $ext == 'docx')
                                    $image = 'fa-file-word-o';
                                elseif($ext == 'pdf')
                                    $image = 'fa-file-pdf-o';
                                elseif($ext == 'txt')
                                    $image = 'fa-file-text-o';
                
                                $html = '<li class="list-group-item">';
                                $html .= '<i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName());
                                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-clock-o"></i> ' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</span>';
                                $html .= '<br><span class="direct-chat-timestamp"><i class="fa fa-folder"></i> ' . Repository::formatFileSize($f->getSize()) .'</span>';
                
                                $html .= '<br><p><span title="Download file" class="btn btn-xs btn-info" onclick="window.location.href=\''.$f->getDownloadURL().'\';"><i class="fa fa-download"></i></span>';
                                if($this->isAbleToDelete())
                                {
                                    $html .= '<span title="Delete file" class="btn btn-xs btn-danger pull-right" onclick="deleteFile(\''.$f->getRelativePath().'\');"><i class="fa fa-trash"></i></span></p>';
                                }
                                echo '</li>';
                                echo '<div class="col-sm-4">';
                                echo $html;
                                echo '</div>';
                            }
                            echo '</div> ';
                        }
                        else
                        {
                            echo '<div class="panel-body fieldValue">';
                            echo '<i class="fa fa-info-circle"></i> No files in this section. ';
                            if(trim($folder) != '' && trim($folder) != trim($tr->username))
                                echo  'Do you want to <span class="btn btn-xs btn-danger" onclick="deleteSection();">Delete</span> the section?';
                            echo '</div> ';
                        }
                    ?>
                </div>
            </div>            
        </div>
    </div>

    <div id="dialogDeleteFile" style="display:none" title="Delete file"></div>
    <div id="dialogDeleteSection" style="display:none" title="Delete section"></div>

    <div id="dialogCreateSection" style="display:none" title="Create section">
        <form method="post" name="frmCreateSection" action="do.php?_action=training_file_repo">
            <input type="hidden" name="_action" value="training_file_repo" />
            <input type="hidden" name="subaction" value="create_section" />
            <input type="hidden" name="tr_id" value="<?php echo $tr->id; ?>" />
            
            <p>Enter a title for the new section. Section titles can be up to 25 characters in length, and may contain letter
                numbers, spaces, hyphens and underscores.</p>
            <p><input type="text" name="new_folder_name" value="" size="25" maxlength="25" /></p>
        </form>
    </div>


	<script src="/assets/adminlte/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="/assets/adminlte/plugins/jQueryUI/jquery-ui.min.js"></script>
	<script src="/assets/adminlte/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/adminlte/dist/js/app.min.js"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script type="text/javascript" src="/assets/js/jquery/jquery.timepicker.js"></script>

	<script language="JavaScript">
        
        <?php
        $js_array = json_encode($existing_folder_names);
        echo "var existing_sections = ". $js_array . ";\n";
        ?>
        
		$(function() {


            $('#dialogDeleteFile').dialog({
                modal: true,
                width: 450,
                closeOnEscape: true,
                autoOpen: false,
                resizable: false,
                draggable: false,
                buttons: {
                    'Delete': function() {
                        $(this).dialog('close');
                        var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
                        if(client){
                            window.location.reload();
                        }
                    },
                    'Cancel': function() {$(this).dialog('close');}
                }
            });

            $('#dialogCreateSection').dialog({
                modal: true,
                width: 450,
                closeOnEscape: true,
                autoOpen: false,
                resizable: false,
                draggable: false,
                buttons: {
                    'Create': function() {
                        var title = $('input[name="new_folder_name"]', this).val();
                        // Validate the title
                        title = jQuery.trim(title);
                        if(title == "" || title == "null" || title == null || title.toLowerCase() == "general"){
                            return;
                        }
                        if(title.length > 25){
                            alert("Section titles must be 25 characters or fewer in length");
                            return;
                        }
                        if(title.match(/[^A-Za-z0-9 \-_]/)){
                            alert("Illegal characters in title. Please use letters, numbers, spaces, underscores and hyphens only.");
                            return;
                        }
                        if(jQuery.inArray(title, existing_sections) !== -1) {
                            alert("There is already a section with this name.");
                            return;
                        }
                        $(this).dialog('close');
                        document.forms["frmCreateSection"].submit();

                    },
                    'Cancel': function() {$(this).dialog('close');}
                }
            });

		$('#dialogDeleteSection').dialog({
                modal: true,
                width: 450,
                closeOnEscape: true,
                autoOpen: false,
                resizable: false,
                draggable: false,
                buttons: {
                    'Delete': function() {
                        $(this).dialog('close');
                        var client = ajaxRequest('do.php?_action=training_file_repo&tr_id=<?php echo $tr->id; ?>&subaction=delete_section&section=' + encodeURIComponent('<?php echo trim($folder); ?>'));
                        if(client){
                            window.location.replace('do.php?_action=training_file_repo&tr_id=<?php echo $tr->id; ?>');
                        }
                    },
                    'Cancel': function() {$(this).dialog('close');}
                }
            });

		});

        function createSection()
        {
            var $dialog = $('#dialogCreateSection');
            $('input[name="new_folder_name"]', $dialog).val("");

            $dialog.dialog("open");
        }

        function deleteFile(path)
        {
            var $dialog = $('#dialogDeleteFile');

            $dialog.data('filepath', path);

            var filename = path.split('/').pop();
            $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

            $dialog.dialog("open");
        }

        function uploadFile()
        {
            var myForm = document.forms['uploadFileForm'];
            if(!validateForm(myForm))
            {
                return false;
            }
            
            var allowedFileSize = <?php echo $this->getMaximumFileSizeToUploadForTrainingRecord(); ?>;
            
            if($("#uploadedfile")[0].files[0].size > allowedFileSize)
            {
                var size1 = bytesToSize($("#uploadedfile")[0].files[0].size);
                var size2 = bytesToSize(allowedFileSize);
                alert('File size is almost ' + size1 + ' which exceeds the maximum file size limit of ' + size2 + ' ');
                myForm.reset();
                return;
            }
            $("button#uploadFileButton").attr('disabled', true);

            myForm.submit();
        }

        function bytesToSize(bytes)
        {
            if(bytes == 0) return '0 Byte';
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
        }

	function deleteSection()
        {
            var folder = <?php echo json_encode(trim($folder)); ?>;
            if(folder == '')
            {
                return;
            }

            var $dialog = $('#dialogDeleteSection');
            $dialog.html("<p>Delete <b>" + folder + "</b>.</p><p>Deletion is permanent and irrecoverable. Continue?</p>");
            $dialog.dialog("open");
        }
		

	</script>

</body>

</html>