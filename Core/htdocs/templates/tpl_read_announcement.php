<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Read Announcements</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="css/announcements.css" type="text/css" />
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
<script language="javascript" src="/js/jquery.min.js" type="text/javascript"></script>
<script language="JavaScript" src="/common.js"></script>



<script language="JavaScript">
var recordId = <?php echo $vo->id?>;

/**
$(function(){

	$('div.CommentEntryBox button').button();

	$('#dialogEditComment').dialog({ 
		modal: true,
		width: 600,
		height: 400,
		closeOnEscape: true,
		autoOpen: false,
		resizable: false,
		draggable: false,
		buttons: {  	
			'Ok': function() {
				var form = document.forms['editCommentForm'];
				form.elements['comment'].value = $('textarea', this).val();
				ajaxPostForm(form);
				$(this).dialog('close');
				window.location.reload();
			},
			'Cancel': function() {
				$(this).dialog('close');
			}
		}
    });
	
});

 */

function deleteRecord()
{
	if(window.confirm("Delete this record?"))
	{
		ajaxRequest('do.php?_action=delete_announcement&id=' + window.recordId);
		window.history.back();
	}
}


 /**
function saveComment()
{
	// Trim content
	
	// Submit content if the user has entered any
	ajaxPostForm(document.forms['commentForm']);
	window.location.reload();
}

function deleteComment(commentId)
{
	if(window.confirm("Delete comment?"))
	{	
		ajaxRequest("do.php?_action=read_announcement&subaction=deletecomment&id=" + recordId + "&comment_id=" + commentId);
		window.location.reload();
	}
}

function editComment(commentId)
{
	var $dialog = $('#dialogEditComment');
	var $textarea = $('textarea', $dialog);
	var form = document.forms['editCommentForm'];

	form.elements['comment_id'].value = commentId;

	var client = ajaxRequest("do.php?_action=read_announcement&subaction=loadcomment&id=" + recordId + "&comment_id=" + commentId);
	if(client)
	{
		var comment = $.parseJSON(client.responseText);
		$textarea.val(comment.content);
	}

	$dialog.dialog("open");
}
*/
</script>

<style type="text/css">
<?php if(preg_match('/MSIE [5-8]/', $_SERVER ['HTTP_USER_AGENT'])) { ?>
div.Announcement, div.Comment
{
	border-width: 1px 2px 2px 1px;
}
<?php } ?>

div.Announcement
{

}

div.Announcements
{
	padding: 10px;
	width: 35%;	
	
}

div.Subtitle
{
	margin-top:10px!important;
}


/*
div.CommentEntryBox
{
	border-width: 1px;
	border-style: solid;
	border-color: #EEEEEE #DDDDDD #DDDDDD #EEEEEE;
	
	padding: 12px;
	margin-bottom: 1.5em;
	
	background-color: white;
	
	
	zoom: 1;
	
	-moz-border-radius: 7px;
	-webkit-border-radius: 7px;
	border-radius: 7px;
	
	-moz-box-shadow: 3px 3px 5px rgba(150, 150, 150, 0.4);
	-webkit-box-shadow: 3px 3px 5px rgba(150, 150, 150, 0.4);
	box-shadow: 3px 3px 5px rgba(150, 150, 150, 0.4);
}

div.CommentEntryBox div.Title
{
	margin-top: 0px;
	font-family: Arial, sans-serif;
	font-size: 16pt;
	color: #555555;	
}

div.CommentEntryBox div.Body
{
	color: #555555;
	margin-top: 20px;
}

div.CommentEntryBox textarea
{
	resize: none;
}

div#dialogEditComment textarea:focus
{
	border-width: 1px;
	border-color: #7F9DB9;
	border-style: solid;
	padding: 2px;
}
*/
</style>
</head>


<body>
	<div class="banner">
		<div class="Title">Announcement</div>
		<div class="ButtonBar">
			<button onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
			<?php if((int)$_SESSION['user']->type!=14){?>
		  	<button onclick="window.location.replace('do.php?id=<?php echo $vo->id; ?>&_action=edit_announcement');">Edit</button>
			<?php }?>
			<?php if($_SESSION['user']->type!=12){?>			
			<button onclick="deleteRecord();">Delete</button>
			<?php }?>
		</div>
	</div>

	<div class="column">
	
	<div class="Announcements"><?php $this->renderContent($link, $vo); /*$this->renderComments($link, $vo);*/ ?></div>
		<?php if($vo->publication_date){ ?>
	<?php } ?>
		
	</div>


</body>
</html>