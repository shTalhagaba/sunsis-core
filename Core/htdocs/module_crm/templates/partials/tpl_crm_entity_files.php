<?php $entity_type = strtolower(get_class($object)); ?>
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title"><span class="fa fa-files-o"></span> File Repository</h2></div>
		<div class="box-body" style="max-height: 500px; overflow-y: scroll;">
			<ul class="list-group list-group-unbordered">
				<?php
				if(count($files) == 0){
					echo '<i>No files uploaded</i>';
				}
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
					echo '<li class="list-group-item">';
					echo '<a href="'.$f->getDownloadURL().'"><i class="fa '.$image.'"></i> ' . htmlspecialchars((string)$f->getName()) . '</a><br>';
					echo '<span title="Uploaded timestamp" class="direct-chat-timestamp "><i class="fa fa-clock-o"></i> <small>' . date("d/m/Y H:i:s", $f->getModifiedTime()) .'</small></span><br>';
					$uploader = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users INNER JOIN crm_entities_files WHERE entity_id = '{$object->id}' AND entity_type = '{$entity_type}' AND file_name = '{$f->getName()}'");
					echo '<span title="Uploaded by" class="direct-chat-timestamp "><i class="fa fa-user"></i> <small>' . $uploader .'</small></span><br>';
					echo "<form action='do.php?_action=ajax_helper&subaction=delete_crm_entity_file' method='post'>";
					echo "<input type='hidden' name='entity_id' value='{$object->id}'>";
					echo "<input type='hidden' name='entity_type' value='{$entity_type}'>";
					echo "<input type='hidden' name='file_path' value='{$f->getAbsolutePath()}'>";
					echo '<span title="Delete this file" class="btn btn-xs btn-danger btn-block btnDeleteCRMEntityFile"><i class="fa fa-trash"></i></span>';
					echo "</form>";
					echo '</li>';
				}
				?>
			</ul>
		</div>
		<div class="box-footer">
			<form name="frmFiles" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>?_action=upload_crm_file" ENCTYPE="multipart/form-data">
				<input type="hidden" name="formName" value="frmFiles" />
				<input type="hidden" name="entity_id" value="<?php echo $object->id; ?>" />
				<input type="hidden" name="entity_type" value="<?php echo $entity_type; ?>" />
				<input class="compulsory" type="file" name="uploaded_crm_file" <?php echo $object->isLocked() ? 'disabled' : ''; ?> />
				<span id="uploadFileButton" class="btn btn-sm btn-primary pull-right <?php echo $object->isLocked() ? 'disabled' : ''; ?>" onclick="uploadFile();"><i class="fa fa-upload"></i></span>
			</form>
		</div>
	</div>
</form>
