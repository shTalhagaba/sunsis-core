<?php /* @var $view View */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Candidate Reporting</title>
<link rel="stylesheet" href="common.css" type="text/css"/>
<?php 
		$selected_theme = SystemConfig::getEntityValue($link, 'module_theme');
		if ( $selected_theme ) {
			echo '<link rel="stylesheet" href="/css/'.$selected_theme.'/common.css" type="text/css"/>';	
		}

		$current_tab = '#tab-1';

?>
<link rel="stylesheet" type="text/css" media="print" href="/print.css" />
</head>

<body id="candidates">
	<div class="banner">
		<div class="Title">Regional Candidate Screening</div>
		<div class="ButtonBar"></div>
		<div class="ActionIconBar">
			<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" /></button>
			<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" /></button>
		</div>
	</div>
	<?php $_SESSION['bc']->render($link); ?>

	<div id="maincontent">
		<div align="center" style="margin-top:50px;">

		<?php 
  			echo $view->render_report($link, $view->getSelectedColumns($link));
  		?>
  		</div>
	</div>
	<?php 
		// include the footer options
		include_once('layout/tpl_footer.php'); 
	?>
	
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">
	$(document).ready(function(){
		$('#tabs > div').hide();
		// ---------------------------
		// default load position
		$('#tabs <?php echo $current_tab; ?>').show();
		$('#tabs ul li a[href=<?php echo $current_tab; ?>]').parent().addClass('active');
		
		$('#tabs ul li a').click(function(){
			$('#tabs ul li').removeClass('active');
			$(this).parent().addClass('active');
			var currentTab = $(this).attr('href');
			$('#tabs > div').hide();
			$(currentTab).show();
			return false;
		});
		// --------------------------
		
		<?php 
			if ( isset($_REQUEST['display']) ) {
				echo '$("[id^=detail_]").filter("[id$='.$_REQUEST['display'].']").each( function() {';
				echo "\n  candidate_location = $(this).prop('id').split('_');\n";
				echo "	$('#tabs > div').hide();\n";
				echo "  $('#tabs ul li').removeClass('active');\n";
				echo "  $('#tabs #tab-'+candidate_location[1]).show();\n";
				echo "  $('#tabs ul li a[href=#tab-'+candidate_location[1]+']').parent().addClass('active');\n";
				echo "  displaydetail(candidate_location[1]+'_'+candidate_location[2]);\n";
				echo '});';
			}	
		?>
	});


	
		function div_filter_crumbs_onclick(div) {
			showHideBlock(div);
			showHideBlock('div_filters');
		}

		/*
		function changeColumns() {
			var myForm = document.forms[0];

			data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>;
			var request = ajaxRequest('do.php?_action=ajax_delete_columns',data);
	
			for(a = 0; a<myForm.length; a++) {	
				data = 'view=' + <?php echo "'" . $view->getViewName() . "'"; ?>
				+ '&colum=' + myForm[a].parentNode.title
				+ '&visible=' + ((myForm[a].checked==true)?1:0);
		
				if( myForm[a].checked==false ) {	
					var request = ajaxRequest('do.php?_action=ajax_save_columns',data);
				}
			}	

			var myForm = document.forms[1];
			myForm.submit();
		}
		*/

		function changeColumns()
		{
			var viewName = "<?php echo $view->getViewName()?>";
			var $checkboxes = $('input[type="checkbox"][name^="columns"]:not(:checked)'); // find unchecked boxes
			var columns = new Array();
			for(var i = 0; i < $checkboxes.length; i++)
			{
				var obj = {
					view:viewName,
					colum:$checkboxes[i].parentNode.title,
					visible:0
				};
				columns.push(obj);
			}
			var json = JSON.stringify(columns);
			var post = "json=" + encodeURIComponent(json) + "&view=" + encodeURIComponent(viewName);
			var client = ajaxRequest("do.php?_action=ajax_save_columns", post);
			if(client){
				window.location.reload();
			}
		}

		function displaydetail(tr) {
			var detail_tr = 'detail_'+tr;
			var user_tr = 'user_'+tr;
			var table_row = document.getElementById(detail_tr);
			var user_row = document.getElementById(user_tr);

			var current_status = table_row.style.display;
			
			$("tr[id^=detail]").each(function() {
				$(this).css('display','none');
			});
			$("tr[id^=user]").each(function() {
				//$(this).css('background-color','#fff');
			});
			// IE sillyness - check for table-row conformance IE7 +
			if( $.browser.msie && $.browser.version < 7 ) {
				if ( current_status != 'block' ) {
					table_row.style.display = 'block';
					//user_row.style.backgroundColor = '#DCE5CD';
				}
			}
			else {
				if ( current_status != 'table-row' ) {
		    		table_row.style.display = 'table-row';
		    		//user_row.style.backgroundColor = '#DCE5CD';
				}
			}
		}

<?php 
		// if we have candidate notes, then output the functionality to allow
		// saving of the note.
		echo CandidateNotes::render_js(); 
?>
		
	</script>
	<noscript>
		<?php include_once('templates/tpl_noscript.php'); ?>
	</noscript>
</body>
</html>
