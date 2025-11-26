<?php
		/**
		 * 
		 * Build the list of Tests in the test directory
		 */	
			$urls = Array();
			$TrackDir=opendir(".");
			// relmes - ensure evaluation of directory names
			// cannot stop the loop
			// - http://php.net/manual/en/function.readdir.php
			while ( false !== ( $file = readdir($TrackDir) ) ) { 
				if ($file != "." && $file != ".." && !is_dir($file) && $file != "config.php" ) {	
					$urls[$file] = '<li><a href="'.$file.'" >'.$file.'</a></li>';
				}
			}

			// relmes - sort the results to display alphabetically
			sort($urls);
			
			// build the Test List
			$html = "<div><ul>";
			foreach ( $urls as $test_file => $test_link ) {
				$html .= $test_link;
			}
			// relmes - remove empty tr if present ast end of html.
			$html .= "</ul></div>";
			closedir($TrackDir); 
			
			echo $html;
?>