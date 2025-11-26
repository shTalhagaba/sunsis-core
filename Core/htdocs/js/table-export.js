/**
 * User: Richard Elmes
 * Date: 10/08/12
 * Time: 10:02
 */

$(document).ready(function() {
	// try to assign a download option to all tables on the page
	$('table').each(function() {
		// it'll only do ones with an id
		if (typeof $(this).attr('id') != 'undefined' ) {
			var complete = 1;
			var our_table = $(this);
			// find out if we have the viewNavigator
			// on the screen, if so put the download
			// button in that instead of next to the table
			// !!! what if there are more than one navigable tables on page !!!
			var table_width = $(this).width();
			if ( table_width > 0 ) {
				var table_container = $(this).parent().parent();
				if ( $(table_container).find('div.viewNavigator').length > 0 ) {
					$('<div style="z-index: -1; height: 26px; width: '+table_width+'px; text-align: right; padding-bottom: 0px; margin-bottom: 0px;"><a href="#" class="download-button" id="export-'+$(this).attr('id')+'" alt="Export as csv file">&nbsp;</a></div>').insertBefore(our_table);
					$('<div style="z-index: -1; height: 26px; width: '+table_width+'px; text-align: right; padding-bottom: 0px; margin-bottom: 0px;"><a href="#" class="download-button" id="export-'+$(this).attr('id')+'" alt="Export as csv file">&nbsp;</a></div>').insertAfter(our_table);
					complete = 0;
				}

				// get the width of the table
				// so the button sits in the right place
				if ( complete == 1 ) {
					$('<div style="z-index: -1; height: 26px; width: '+table_width+'px; text-align: right; padding-bottom: 0px; margin-bottom: 0px;"><a href="#" class="download-button" id="export-'+$(this).attr('id')+'" alt="Export as csv file">&nbsp;</a></div>').insertBefore(our_table);
				}
			}
		}
	});

	$("a.download-button").click(function() {

		// set the complete csv flog
		var complete = 1;

		// check we don't have the better option
		// to export the entire data set
		$('button').each(function() {
			if ( $(this).attr('onclick') ) {
				var export_matches = $(this).attr('onclick').match(/^exportToExcel\(\'(.*)\'\);$/)
				if( export_matches ) {
					exportToExcel(export_matches[1]);
					complete = 0;
				}
			}
		});

		// get what is on the page and export
		if ( complete == 1 ) {
			var table_name = $(this).attr('id').replace('export-', '');
			var data = $('#'+table_name).table2CSV();
			data = data.replace(/\&nbsp;/g, '');
            // alert(data);
			window.location.href = 'do.php?_action=downloader_table&csv_name='+table_name+'&csv_text=' + data;
		}
	});
});

//

jQuery.fn.table2CSV = function(options) {
	var options = jQuery.extend({
			separator: ',',
			header: [],
			delivery: 'value' // popup, value
		},
		options);

	var csvData = [];
	var headerArr = [];
	var el = this;

	//header
	var numCols = options.header.length;
	var tmpRow = []; // construct header avalible array

//	if (numCols > 0) {
//		for (var i = 0; i < numCols; i++) {
//			tmpRow[tmpRow.length] = formatData(options.header[i]);
//		}
//	} else {
//		$(el).filter(':visible').find('th').each(function() {
//			if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
//		});
//	}

	row2CSV(tmpRow);

	// actual data
	$(el).find('tr').each(function() {
		var tmpRow = [];
		$(this).filter(':visible').find('th, td').each(function() {
			if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
		});
		row2CSV(tmpRow);
	});
	if (options.delivery == 'popup') {
		var mydata = csvData.join('\n');
		return popup(mydata);
	} else {
		var mydata = csvData.join('||');
		return mydata;
	}

	function row2CSV(tmpRow) {
		var tmp = tmpRow.join('') // to remove any blank rows
		// alert(tmp);
		if (tmpRow.length > 0 && tmp != '') {
			var mystr = tmpRow.join(options.separator);
			csvData[csvData.length] = mystr;
		}
	}

	function formatData(input) {
		// replace " with â€œ
		var regexp = new RegExp(/["]/g);
		var output = input.replace(regexp, "â€œ");
		//HTML
		var regexp = new RegExp(/\<[^\<]+\>/g);
		var output = output.replace(regexp, "");
		if (output == "") return '';
		return '"' + output + '"';
	}

	function popup(data) {
		 var generator = window.open('', 'csv', 'height=400,width=600');
		 generator.document.write('<html><head><title>CSV</title>');
		 generator.document.write('</head><body >');
		 generator.document.write('<textArea cols=70 rows=15 wrap="off" >');
		 generator.document.write(data);
		 generator.document.write('</textArea>');
		 generator.document.write('</body></html>');
		 generator.document.close();

		return true;
	}
};
