<?php

// #192 {0000000271} - word output ILJ - unique per client

class ttg_ilp_word implements IAction {
	
	public function execute(PDO $link) {
					
		// Validate data entry
		$qualification_id = isset($_GET['qualification_id']) ? $_GET['qualification_id'] : '';
		$framework_id = isset($_GET['framework_id']) ? $_GET['framework_id'] : '';
		$id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';
		$internaltitle = isset($_GET['internaltitle']) ? $_GET['internaltitle'] : '';
		
		$username = $_SESSION['user']->username;
		
		$target_path = DATA_ROOT."/uploads/".DB_NAME;
		$file_manager = new Docx('ilj.docx', DATA_ROOT.'/uploads/'.DB_NAME.'/ilj/', DATA_ROOT."/uploads/".DB_NAME."/".$username."/");		
		$file_manager->extract_docx();
		
		// ----
		// Obtain all the relevant data to present in the document
		$tr = TrainingRecord::loadFromDatabase($link,$id);
		
		$que = "select id from student_frameworks where tr_id='$id'";
		$framework_id = trim(DAO::getSingleValue($link, $que)?:'');
		
		// get the title of the course the learner is on
		$que = "select courses.title from courses LEFT JOIN courses_tr on courses_tr.course_id = courses.id where tr_id='$id'";
		$course_title = trim(DAO::getSingleValue($link, $que) ?: '');
		$pot_vo = TrainingRecord::loadFromDatabase($link, $id); /* @var $pot_vo TrainingRecord */
		$provider = Organisation::loadFromDatabase($link, $pot_vo->provider_id);
		$employer = Organisation::loadFromDatabase($link, $pot_vo->employer_id);		
		
		// Create Address presentation helper
		$home_bs7666 = new Address();
		$home_bs7666->set($pot_vo, 'home_');
		
		$work_bs7666 = new Address();
		$work_bs7666->set($pot_vo, 'work_');
		
		$provider_bs7666 = new Address();
		$provider_bs7666->set($pot_vo, 'provider_');		
		
		$page_record = 'Training Record';

		$file_manager->update_docx('learner_name', $pot_vo->firstnames.' '.$pot_vo->surname);	
		$d = new Date($pot_vo->dob);
		$file_manager->update_docx('learner_dob', $d->formatMedium());
		$d = new Date($pot_vo->start_date);
		$d->formatMedium();
		
		$d = new Date($pot_vo->target_date);
		$d->formatMedium();
		
		// qualification table
		// content to replace
	// 	$replace_tblrw = '<w:tr w:rsidR="005343EC" w:rsidRPr="0039019E" w:rsidTr="0061544E"><w:trPr><w:trHeight w:val="421"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="3976" w:type="dxa"/><w:gridSpan w:val="4"/></w:tcPr><w:p w:rsidR="00085002" w:rsidRDefault="00436A7E" w:rsidP="00AE72E7"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_learneron</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="850" w:type="dxa"/><w:tcBorders><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_lvl</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1270" w:type="dxa"/><w:tcBorders><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_aimref</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1247" w:type="dxa"/><w:gridSpan w:val="3"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_sdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1219" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_ecdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1264" w:type="dxa"/><w:gridSpan w:val="2"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_acdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1247" w:type="dxa"/><w:gridSpan w:val="2"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="005343EC"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_widate</w:t></w:r></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1190" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="004B2CC6"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awnm</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1142" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="004B2CC6"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awbd</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1162" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="00085002" w:rsidRPr="0039019E" w:rsidRDefault="004B2CC6"><w:pPr><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:r><w:rPr><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awda</w:t></w:r></w:p></w:tc></w:tr>';
		$replace_tblrw = '<w:tr w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidTr="0075185A"><w:trPr><w:trHeight w:val="421"/></w:trPr><w:tc><w:tcPr><w:tcW w:w="3947" w:type="dxa"/></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_learneron</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="895" w:type="dxa"/><w:tcBorders><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_lvl</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1269" w:type="dxa"/><w:tcBorders><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_aimref</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1240" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_sdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1437" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_ecdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1418" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_acdate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1417" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_widate</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1276" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awnm</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1417" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awbd</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1418" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr><w:t>qual_awda</w:t></w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
		// population variable.
		$docx_tablerow = '';
		$sql = "SELECT * FROM student_qualifications WHERE tr_id='$id'";
		$st = $link->query($sql);
		if( $st ) {
			while( $row = $st->fetch() ) {				
				$docx_tablerow .= '<w:tr w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidTr="0075185A">';
				$docx_tablerow .= '<w:trPr>';
				$docx_tablerow .= '<w:trHeight w:val="421"/></w:trPr>';
				$docx_tablerow .= '<w:tc><w:tcPr><w:tcW w:w="3947" w:type="dxa"/></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr>';
				$docx_tablerow .= '<w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/>';
				$docx_tablerow .= '</w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr>';
				$docx_tablerow .= '<w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['title'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="895" w:type="dxa"/><w:tcBorders>';
				$docx_tablerow .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr>';
				$docx_tablerow .= '<w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>';
				$docx_tablerow .= '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['level'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1269" w:type="dxa"/>';
				$docx_tablerow .= '<w:tcBorders><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1240" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8">';
				$docx_tablerow .= '<w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/>';
				$docx_tablerow .= '<w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['start_date'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1437" w:type="dxa"/><w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8">';
				$docx_tablerow .= '<w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9">';
				$docx_tablerow .= '<w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['end_date'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1418" w:type="dxa"/>';
				$docx_tablerow .= '<w:tcBorders><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>';
				$docx_tablerow .= '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['actual_end_date'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1417" w:type="dxa"/><w:tcBorders>';
				$docx_tablerow .= '<w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>';
				$docx_tablerow .= '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/>';
				$docx_tablerow .= '<w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1276" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8">';
				$docx_tablerow .= '<w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/>';
				$docx_tablerow .= '<w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['awarding_body'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1417" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr><w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8">';
				$docx_tablerow .= '<w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/>';
				$docx_tablerow .= '<w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/><w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc><w:tc><w:tcPr><w:tcW w:w="1418" w:type="dxa"/><w:tcBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/>';
				$docx_tablerow .= '<w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tcBorders></w:tcPr>';
				$docx_tablerow .= '<w:p w:rsidR="0075185A" w:rsidRPr="00A413D9" w:rsidRDefault="0075185A" w:rsidP="007141A8"><w:pPr><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr></w:pPr><w:proofErr w:type="spellStart"/><w:r w:rsidRPr="00A413D9"><w:rPr><w:rFonts w:ascii="Verdana" w:hAnsi="Verdana"/><w:sz w:val="20"/>';
				$docx_tablerow .= '<w:szCs w:val="20"/></w:rPr>';
				$docx_tablerow .= '<w:t>'.$row['awarding_body_reg'].'</w:t>';
				$docx_tablerow .= '</w:r><w:proofErr w:type="spellEnd"/></w:p></w:tc></w:tr>';
			}
		}
		$file_manager->update_docx($replace_tblrw, $docx_tablerow);
		
		// Get any review already done
		$reviews = array();
		$sql = <<<HEREDOC
SELECT
	tr_id, meeting_date, assessor, comments, assessor_comments, IF(paperwork_received=1,'yes','no') as paperwork_received
FROM
	assessor_review
where 
	tr_id = '$id'
order by 
	meeting_date;
HEREDOC;
		
		$st = $link->query($sql);	
		if( $st ) {	
			$assessor_review_count = 1;
			while( $row = $st->fetch() ) {
				$meet_date = new Date($row['meeting_date']);
				$review_content = array(
					'date' => $meet_date->formatShort(),
					'assessor' => $row['assessor'],
					'comments' => $row['comments'],
					'assessor_comments' => $row['assessor_comments'],
					'paperwork' => $row['paperwork_received'],
				);
				$reviews[$assessor_review_count] = $review_content;
				$assessor_review_count++;
				
			}
		}
		// Progress Review
		$end_date = new Date($pot_vo->target_date);

		$contract_title = trim((string) DAO::getSingleValue($link, "select title from contracts where id = $pot_vo->contract_id"));
		$weeks = trim((string) DAO::getSingleValue($link, "select frequency from contracts where id = $pot_vo->contract_id"));
		$last_review_date = new Date(DAO::getSingleValue($link, "select meeting_date from assessor_review where tr_id = '$id' order by meeting_date DESC LIMIT 1"));
		$loop_date = ($end_date->getDate()>$last_review_date->getDate())?$end_date:$last_review_date;	
		$start_date = new Date($pot_vo->start_date);
		
		// set a default number of weeks between reviews
		if( $weeks == '' ) { 
			$weeks = 12; 
		}
		else { 
			$weeks = (int)$weeks; 
		}

		$count = 1;
		while( $start_date->getDate() <= $loop_date->getDate() ) {
			$start_date->addDays($weeks*7);
			if( $start_date->getDate() <= $loop_date->getDate() ) {
				// get a two digit number, left padded with zero.	
				$padded_count = sprintf("%02d", $count);
				$file_manager->update_docx('pd_'.$padded_count, join("/", array(sprintf("%02d", $start_date->getDays()), sprintf("%02d", $start_date->getMonth()), $start_date->getYear())));
				if ( sizeof($reviews) >= $count ) {	
					$file_manager->update_docx('ad_'.$padded_count, $reviews[$count]['date']);
					$file_manager->update_docx('pr_'.$padded_count, $reviews[$count]['paperwork']);
					$file_manager->update_docx('rn_'.$padded_count, $reviews[$count]['assessor']);
				}
				else {
					$file_manager->update_docx('ad_'.$padded_count, '');
					$file_manager->update_docx('pr_'.$padded_count, '');
					$file_manager->update_docx('rn_'.$padded_count, '');
				}	
			}	
			$count++;
			if ( $count > 10 ) {
				break;
			}
		}
		
		// clean down any unrequired review columns
		$count--;
		while( $count <= 10 ) {
			// get a two digit number, left padded with zero.	
			$padded_count = sprintf("%02d", $count);
			$file_manager->update_docx('pd_'.$padded_count, '');
			$file_manager->update_docx('ad_'.$padded_count, '');
			$file_manager->update_docx('pr_'.$padded_count, '');
			$file_manager->update_docx('rn_'.$padded_count, '');
			$count++;
		}
		
		// course title on the 1st page
		$file_manager->update_docx('learner_qualtitle', $course_title);
		
		$o = Organisation::loadFromDatabase($link, $pot_vo->employer_id);
		$file_manager->update_docx('company_name', $o->legal_name);
		
		// get the employer address information
		$employer_address = Location::loadFromDatabase($link, $pot_vo->employer_location_id);
		$file_manager->update_docx('company_address', $employer_address->address_line_1);
		$file_manager->update_docx('company_line2_address', $employer_address->address_line_2);
		$file_manager->update_docx('company_line3_address', $employer_address->address_line_3);
		
		$assessor_name = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.assessor = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$pot_vo->id;");
		$verifier = DAO::getSingleValue($link, "select concat(firstnames, ' ', surname) from users inner join groups on groups.verifier = users.id inner join group_members on group_members.groups_id = groups.id where group_members.tr_id=$pot_vo->id;");

		if($assessor_name == '')
			$assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = '{$pot_vo->assessor}'");
		if($verifier == '')
			$verifier = DAO::getSingleValue($link, "SELECT CONCAT(users.firstnames, ' ', users.surname) FROM users WHERE id = '{$pot_vo->verifier}'");

		$file_manager->update_docx('assess_name', $assessor_name);
		$file_manager->update_docx('assess_telephone', '');
		$file_manager->update_docx('iv_name', $verifier);
		$file_manager->update_docx('iv_telephone', '');
		
		$file_manager->update_docx('company_telephone', $employer_address->telephone);
		$file_manager->update_docx('company_contactname', $employer_address->contact_name);
		$file_manager->update_docx('company_contactposition', '');
		$file_manager->update_docx('company_contactemail', $employer_address->contact_email);
		$file_manager->update_docx('company_postcode', $employer_address->postcode);
		$file_manager->update_docx('company_telephone', $employer_address->telephone);
		$file_manager->update_docx('company_edrs', '');
		
/*		$file_manager->update_docx('train_address', $provider_bs7666->paon_start_number.$provider_bs7666->paon_start_suffix.$provider_bs7666->paon_end_number.$provider_bs7666->paon_end_suffix.' '.$provider_bs7666->street_description);
		$file_manager->update_docx('train_line2_address', $provider_bs7666->locality.', '.$provider_bs7666->town);
		$file_manager->update_docx('train_line3_address', $provider_bs7666->county);*/
		$file_manager->update_docx('train_address', $tr->provider_address_line_1);
		$file_manager->update_docx('train_line2_address', $tr->provider_address_line_2);
		$file_manager->update_docx('train_line3_address', $tr->provider_address_line_3);


		$file_manager->update_docx('train_name', $provider->trading_name);
		$file_manager->update_docx('contact_name', '');
		$file_manager->update_docx('train_contactposition', '');
		$file_manager->update_docx('train_contactemail', '');
		$file_manager->update_docx('train_postcode', $provider_bs7666->postcode);
		$file_manager->update_docx('train_telephone', '');

		// write the updated document.xml to the temporary location.
		$file_manager->write_docx();
		// re-archive the temporary location files and place in write location
		$final_file = $file_manager->bundle_docx();
		// remove any temporary files created during the manipulations
	 	$file_manager->cleanup_docx();
		
		// relmes - updated to use the filename generated from the docx library
		http_redirect("do.php?_action=downloader&path=".$username."&f=".$final_file);		
	} 
}
?>