var reAmp = new RegExp('&', 'g');
var reApos = new RegExp("'", 'g');
var reQuot = new RegExp('"', 'g');
var reLt = new RegExp('<', 'g');
var reGt = new RegExp('>', 'g');

/**
 * Javascript equivalent of the PHP function of the same name
 */
function htmlspecialchars(text)
{
	if(text != null && typeof(text) == 'string')
	{
		text = text.replace(reAmp, '&amp;');
		text = text.replace(reApos, '&apos;');
		text = text.replace(reQuot, '&quot;');
		text = text.replace(reLt, '&lt;');
		text = text.replace(reGt, '&gt;');
	}
	
	return text;
}

/**
 * Used to rip out any none ASCII characters (higher than 127) from form submissions
 */
function forceASCII(text)
{
	return text?text.replace(/[^\x00-\x7F]/, ' '):text;
}	

/**
 * Reads a bs7666 address from fields on a form and returns an array of
 * address lines
 */
/*function bs7666_to_lines(form, fieldname_prefix)
{
	var lines = new Array();
	var fields = form.elements;
	var prefix = fieldname_prefix?fieldname_prefix:'';
	
	// Get fields
	var saon_start_number = fields[prefix+'saon_start_number']?fields[prefix+'saon_start_number'].value:'';
	var saon_start_suffix = fields[prefix+'saon_start_suffix']?fields[prefix+'saon_start_suffix'].value:'';
	var saon_end_number = fields[prefix+'saon_end_number']?fields[prefix+'saon_end_number'].value:'';
	var saon_end_suffix = fields[prefix+'saon_end_suffix']?fields[prefix+'saon_end_suffix'].value:'';
	var saon_description = fields[prefix+'saon_description']?fields[prefix+'saon_description'].value:'';
	var paon_start_number = fields[prefix+'paon_start_number']?fields[prefix+'paon_start_number'].value:'';
	var paon_start_suffix = fields[prefix+'paon_start_suffix']?fields[prefix+'paon_start_suffix'].value:'';
	var paon_end_number = fields[prefix+'paon_end_number']?fields[prefix+'paon_end_number'].value:'';
	var paon_end_suffix = fields[prefix+'paon_end_suffix']?fields[prefix+'paon_end_suffix'].value:'';
	var paon_description = fields[prefix+'paon_description']?fields[prefix+'paon_description'].value:'';	

	var street_description = fields[prefix+'street_description']?fields[prefix+'street_description'].value:'';	
	var locality = fields[prefix+'locality']?fields[prefix+'locality'].value:'';
	var town = fields[prefix+'town']?fields[prefix+'town'].value:'';
	var county = fields[prefix+'county']?fields[prefix+'county'].value:'';
	var postcode = fields[prefix+'postcode']?fields[prefix+'postcode'].value:'';
	
	if(saon_start_number != ''
		|| saon_start_suffix != ''
		|| saon_end_number != ''
		|| saon_end_suffix != ''
		|| saon_description != '')
	{
		var line = '';
		if(saon_start_number != '' || saon_start_suffix != '')
		{
			line += saon_start_number + saon_start_suffix;
		}
		if(saon_end_number != '' || saon_end_suffix != '')
		{
			line += ' - ' + saon_end_number + saon_end_suffix;
		}
		if(saon_description != '')
		{
			line += ' ' + saon_description;
		}
		
		lines.push(line);
	}
	
	
	if(paon_description != '')
	{
		lines.push(paon_description);
	}
	
	if(paon_start_number != ''
		|| paon_start_suffix != ''
		|| paon_end_number != ''
		|| paon_end_suffix != '')
	{
		var line = '';
		if(paon_start_number != '' || paon_start_suffix != '')
		{
			line += paon_start_number + paon_start_suffix;
		}
		if(paon_end_number != '' || paon_end_suffix != '')
		{
			line += ' - ' + paon_end_number + paon_end_suffix;
		}
		
		line += ' ' + street_description;
		
		lines.push(line);
	}
	else if(street_description != '')
	{
		lines.push(street_description);
	}
	
	
	if(locality != '')
	{
		lines.push(locality);
	}

	if(town != '')
	{
		lines.push(town);
	}

	if(county != '')
	{
		lines.push(county);
	}

	if(postcode != '')
	{
		lines.push(postcode);
	}		

	
	return lines;	
}*/

/////////////////////////////////////////
// VIEW PRESENTATION (IE6 compatible)
/////////////////////////////////////////
function viewrow_onmouseover(row, event)
{
	row.oldBackgroundColor = row.style.backgroundColor; 
	row.style.backgroundColor = '#FFD9B5'; //'#BFE08F';
	row.style.cursor = 'pointer';
}

function viewrow_onmouseout(row, event)
{
	if(row.oldBackgroundColor)
	{
		row.style.backgroundColor = row.oldBackgroundColor;
	}
	else
	{
		row.style.backgroundColor = '';
	}
}
//////////////////////////////////////////

/**
 * Used by the filter reset button
 */
function resetViewFilters(form)
{
	// TODO: dirty fix - all templates need updating to pass in an id instead
	if(form.id == "applySavedFilter")
	{
		var form = document.getElementById('applyFilter');
	}
	
	form.reset();
	
	for(i = 0; i < form.elements.length; i++)
	{
		if(form.elements[i].resetToDefault)
		{
			form.elements[i].resetToDefault();
		}
	}
}


function emptySelectElement(element)
{
	while(element.options[0])
	{
		element.options[0] = null;
	}
}

/**
 * Generic validation routine. Attach a validate() method to form elements
 * that require more specific validation
 */
function validateForm(form, excludeFields)
{
	var e = form.elements;

	// If exclude fields is not specified, create an empty array
	if(excludeFields == null)
	{
		excludeFields = new Array();
	}
	excludeFields.sort();

	for(var i = 0; i < e.length; i++)
	{
		// Check if the field is to be excluded
		if(isInSortedArray(e[i].name, excludeFields))
			continue; // skip this field
		
		// Multi-node fields
		if(e[i].tagName == 'INPUT' && (e[i].type == 'checkbox' || e[i].type == 'radio'))
		{
			if(e[i].className.indexOf('compulsory') > -1)
			{
				var nodeList = e[e[i].name];
				var checked = false;
				var disabled = false;
				if(nodeList.length)
				{
					// Many nodes
					for(var j = 0; j < nodeList.length; j++)
					{
						disabled = (disabled || nodeList[j].disabled);
						checked = (checked || nodeList[j].checked);
					}
				}
				else
				{
					// Single element
					disabled = e[i].disabled;
					checked = e[i].checked;
				}
				
				if(checked == false && disabled == false)
				{
					alert("Please fill in all compulsory fields");
					e[i].focus();
					return false;
				}

			}
			
			// Don't check elements of this name again
			excludeFields.push(e[i].name);
			excludeFields.sort();	
		}
		
		// Single element fields
		if( (e[i].tagName == 'SELECT' || (e[i].tagName == 'INPUT' && (e[i].type == 'text' || e[i].type == 'password')))
			&& e[i].className.indexOf('compulsory') > -1
			&& e[i].disabled != true
			&& e[i].value == '')
		{
			alert("Please fill in all compulsory fields");
			e[i].focus();
			return false;
		}
		
		if(e[i].validate && e[i].validate() == false)
		{
			return false;
		}
	}
	
	return true;
}


/**
 * Populates a form's fields using the values of correspondingly named properties
 * of the supplied object.
 */
function populateFormFromObject(form, obj, exclude)
{
	if(exclude == null || !(exclude instanceof Array) )
	{
		exclude = new Array();
	}

	exclude.sort();

	var value = null;
	propertyLoop:for(var prop in obj)
	{
		value = obj[prop];
		
		// Ignore null values and properties in the exclude array
		if(value == null || isInSortedArray(prop, exclude) )
		{
			continue propertyLoop;
		}
		
		// Cycle through all the form elements, looking for fields that match
		var field = null;
		formLoop:for(var i = 0; i < form.elements.length; i++)
		{
			field = form.elements[i];
			if(field.name == prop || field.name == (prop+'[]'))
			{
				if(field.type == 'checkbox' || field.type == 'radio')
				{
					if(value instanceof Array)
					{
						for(var a = 0; a < value.length; a++)
						{
							if(field.value == value[a])
							{
								field.checked="checked";
								break;
							}
							else
							{
								field.checked = null;
							}
						}
					}
					else
					{
						field.checked = (field.value == value ? 'checked':null);
					}
				}
				else
				{
					// Normal text fields and single-selection SELECT elements
					field.value = (value instanceof Array ? value.join(',') : value);
					break formLoop;
				}
			} // if form element name matches property name
		} // formLoop
	} // propertyLoop	
}


/**
 * Sort the array using Array.sort() before calling this
 */
function isInSortedArray(needle, haystack)
{
	if(haystack instanceof Array)
	{
		for(var i = 0; i < haystack.length; i++)
		{
			if(needle == haystack[i])
			{
				return true;
			}
			else if(needle < haystack[i])
			{
				return false;
			}
		}
	}
	
	return false;
}

////////////////////////////////////////////////////////////////////////////////
// AJAX
//
////////////////////////////////////////////////////////////////////////////////

/**
 * Simple AJAX request
 * @param uri Absolute or relative URI
 * @param postData [optional] querystring to submit by POST request (not MIME multipart)
 * @param contentType [optional] content type of POST data (default is application/x-www-form-urlencoded; charset=UTF-8)
 * @return XMLHTTPRequest object or null on failure
 */
function ajaxRequest(uri, postData, contentType)
{
	var req = ajaxBuildRequestObject();
	if(postData != null)
	{
		req.open('POST', expandURI(uri), false); // (method, uri, asynchronous)
		req.setRequestHeader("Content-Type", contentType != null?contentType:"application/x-www-form-urlencoded; charset=UTF-8");
		req.setRequestHeader("Accept", "application/ajax"); // marker for server code (does not work with Opera)
		req.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // marker for server code (does not work with IE)
		req.send(postData);
	}
	else
	{
		req.open('GET', expandURI(uri), false); // (method, uri, asynchronous)
		req.setRequestHeader("Accept", "application/ajax"); // marker for server code (does not work with Opera)
		req.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // marker for server code (does not work with IE)
		req.send(null);	
	}
	
	// Standard PHP fatal errors begin with <br />. We can test for this at the start
	// of a response, but for water-tight error detection use php.ini to wrap PHP errors
	// using the XML tag below
	if( req.status == 200 && req.getResponseHeader("X-Perspective") != 'Application error'
		&& req.responseText.indexOf('<php:error xmlns:php="http://php.net">') == -1
		&& (req.responseText.indexOf('<br />') > 0 || req.responseText.indexOf('<br />') == -1) )
	{
		// No errors detected
		return req;
	}
	else
	{
		// An error or an unexpected HTTP response code
		ajaxErrorHandler(req);
		return null;
	}
}






/**
 * Empties a <select> element and repopulates it with XML data from an AJAX request.
 * The XML should be in the format: <select><option value="value">label</option>.....</select>
 */
function ajaxPopulateSelect(select, url)
{
	// Store selected option value
	if(select.selectedIndex > -1)
	{
		var selectedOptionValue = select.options[select.selectedIndex].value;
	}
	else
	{
		var selectedOptionValue = null;
	}
	
	// Delete all current options
	emptySelectElement(select);

	// Get AJAX request object	
	var req = ajaxRequest(url);
	
	if(req != null)
	{
		var dom = req.responseXML;
		var root = dom.documentElement;
		var children = root.childNodes;
	
		var value = null, label = null;
		for(var i = 0; i < children.length; i++)
		{
			if(children[i].nodeType == 1 && children[i].nodeName == 'option') // 1 == ELEMENT
			{
				value = children[i].getAttribute('value');
				if(children[i].firstChild != null && children[i].firstChild.nodeType == 3) //3 == TEXTNODE
				{
					label = children[i].firstChild.nodeValue;
				}
				else
				{
					label = '';
				}
				
				select.options[select.options.length] =	new Option(label, value);
			}
		}
		
		// Reselect the previously selected option if it is still available
		if(selectedOptionValue != null)
		{
			for(var i = 0; i < select.options; i++)
			{
				if(select.options[i].value == selectedOptionValue)
				{
					select.selectedIndex = i;
					break;
				}
			}
		}
	}
}


function ajaxPostForm(form, callback)
{
	if(callback == null)
	{
		// SYNCHRONOUS
		return ajaxRequest(form.action, ajaxBuildPostData(form));
	}
	else
	{
		// ASYNCHRONOUS
		var uri = expandURI(form.action);
		var req = ajaxBuildRequestObject();
		req.open("POST", uri, true); // (method, uri, asynchronous)
		req.onreadystatechange = function(e){
			if(req.readyState == 4){
				if(req.status == 200){
					callback(req);
				}
				else {
					ajaxErrorHandler(req);
				}
			}
		}
		req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
		req.setRequestHeader("Accept", "application/ajax"); // marker for server code (does not work with Opera)
		req.setRequestHeader("X-Requested-With", "XMLHttpRequest"); // marker for server code (does not work with IE)
		req.send(ajaxBuildPostData(form)); // post data
	}
}


/**
 * Helper function
 */
function ajaxBuildRequestObject()
{
	var req = null;

   // branch for native XMLHttpRequest object (Opera, Mozilla, IE 7+)
	if(window.XMLHttpRequest)
	{
   		try
		{
			req = new XMLHttpRequest();
		}
		catch(e)
		{
			req = null;
		}
	}
	else
	{
		// branch for IE/Windows ActiveX version
		if(window.ActiveXObject)
		{
	       	try
			{
	        	req = new ActiveXObject("Msxml2.XMLHTTP");
	      	}
			catch(e)
			{
        		try
				{
          			req = new ActiveXObject("Microsoft.XMLHTTP");
        		}
				catch(e)
				{
          			req = null;
        		}
			}
    	}
	}
	
	return req;
}


/**
 * Helper function
 */
function ajaxErrorHandler(req)
{
	if(req.getResponseHeader("X-Perspective") == 'Application error' && req.responseText.indexOf('<'+'?xml') > -1)
	{
		// FRAMEWORK GENERATED ERROR
		var doc = req.responseXML.documentElement;
		var file, line, message, trace;
		if(doc.nodeName == "error")
		{
			// An error generated by our web framework
			if(doc.getElementsByTagName('file').length > 0 && doc.getElementsByTagName('file')[0].firstChild)
			{
				file = doc.getElementsByTagName('file')[0].firstChild.nodeValue;
			}	
			if(doc.getElementsByTagName('line').length > 0 && doc.getElementsByTagName('line')[0].firstChild)
			{
				line = doc.getElementsByTagName('line')[0].firstChild.nodeValue;
			}
			if(doc.getElementsByTagName('message').length > 0 && doc.getElementsByTagName('message')[0].firstChild)
			{
				message = doc.getElementsByTagName('message')[0].firstChild.nodeValue;
			}
			
			alert(file + '(' + line + '): ' + message);
		}
		else
		{
			// Not a framework error -- just display it
			alert(req.responseText);
		}
	}
	else
	{
		// Look for PHP generated errors. For this code to work, the following
		// entries need to be made in the Apache VirtualHost configuration
		//  php_value error_prepend_string 		'<php:error xmlns:php="http://php.net">'
		//  php_value error_append_string 		'</php:error>'
		var text = req.responseText;
		var openingTag = '<php:error xmlns:php="http://php.net">';
		var start = text.indexOf(openingTag);
		if(start > -1)
		{
			// extract error
			var end = text.indexOf('</php:error>');
			text = text.substring(start + openingTag.length, end);
		
			// remove HTML tags
			text = text.replace(/<[^>]+>/g, '');
		}
		else
		{
			// Check for the standard <br /> opening tag for PHP errors
			if(text.indexOf('<br />') == 0)
			{
				// remove HTML tags
				text = text.replace(/<[^>]+>/g, '');
			}
		}
		
		// Display whatever is left
		if(req.status == 200)
		{
			alert(text);
		}
		else
		{
			alert(req.status + ' ' + req.statusText + ': ' + text);
		}
	}
}



/**
 * @param HTMLForm form
 * @param Array excludeFields Fields to exclude
 */
function ajaxBuildPostData(form, excludeFields)
{
	// Sanity check for excludeFields
	if(excludeFields == null || !(excludeFields instanceof Array || excludeFields instanceof String))
	{
		excludeFields = new Array();
	}
	
	// Convert string to an array
	if(!(excludeFields instanceof Array))
	{
		excludeFields = excludeFields.split(',');
	}

	excludeFields.sort();

	var qs = '';
	var e = form.elements;
	
	for(var i = 0; i < e.length; i++)
	{
		// Fieldsets show up in elements and must be skipped
		if(e[i].tagName == 'FIELDSET') continue;
		
		// Skip any fields in the excludeFields array
		if(isInSortedArray(e[i].name, excludeFields)) continue;
		
		if(e[i].tagName == 'INPUT' && (e[i].type == "checkbox" || e[i].type == "radio"))
		{
			// Only include checkboxes and radio buttons if they are checked
			if(e[i].checked == true)
			{
				if(qs.length > 0) qs += '&';
				qs += encodeURIComponent(e[i].name) + "=" + encodeURIComponent(e[i].value);
			}
		}
		else
		{
			if(qs.length > 0) qs += '&';
			qs += encodeURIComponent(e[i].name) + "=" + encodeURIComponent(e[i].value);
		}
	}

	return qs;
}


/**
 * Helper function
 */
function expandURI(uri)
{
	var url = '';
	
	if(uri.match(/^http/))
	{
		// do nothing
		url = uri;
	}
	else if(uri.charAt(0) == '/')
	{
		// Add prefix
		url = location.protocol + "//" + location.host + uri;
	}
	else
	{
		// Add prefix and path
		var i = location.pathname.lastIndexOf('/');
		var path = '';
		if(i > 0)
		{
			path = location.pathname.substring(0, i);
		}
		else
		{
			path = location.pathname;
		}
		
		url = location.protocol + "//" + location.host + path + "/" + uri;
	}
	
	return url;
}


/////////////////////// END AJAX ///////////////////////////////////////////////






function showHideBlock(element, display)
{
	var e = null;
	
	if(typeof(element) == 'string')
	{
		e = document.getElementById(element);
	}
	else
	{
		e = element;
	}
	
	if(e == null)
	{
		return false;
	}
	
	// Get computed style
	var computedStyle = null;
	if(window.getComputedStyle)
	{
		// DOM 2 compliant browsers
		computedStyle = window.getComputedStyle(e, "");
		
		if(display == null)
		{
			display = (computedStyle.display == 'none'); // Toggle effect
		}

		if(display == true)
		{
			if(e.nodeName == 'TR')
			{
				e.style.display = 'table-row';
			}
			else if(e.nodeName == 'TD' || e.nodeName == 'TH')
			{
				e.style.display = 'table-cell';
			}
			else
			{
				e.style.display = 'block';
			}
		}
		else
		{
			e.style.display = 'none';
		}		
	}
	else
	{
		// Internet Explorer
		computedStyle = e.currentStyle;
		
		if(display == null)
		{
			display = (computedStyle.display == 'none');
		}
		
		if(display)
		{
			e.style.display = 'block';
		}
		else
		{
			e.style.display = 'none';
		}
	}
}

////////////////////////////////////////////////////////////////////////////////

/**
 * Creates a new XML DOM structure for a given string of XML.
 * Cross-browser compatible.
 */
function loadDOM(strXML)
{
	var root = null;
	
	if(window.ActiveXObject)
	{
		// Internet Explorer code
		var msxml = null;
		
		// Load the most recent XML parser possible
		var aVersions = [ "MSXML2.DOMDocument.6.0",
			"MSXML2.DOMDocument.5.0",
			"MSXML2.DOMDocument.4.0",
			"MSXML2.DOMDocument.3.0",
			"MSXML2.DOMDocument",
			"Microsoft.XmlDom"];
	    for(var i = 0; i < aVersions.length; i++)
	    {
	    	try
	    	{
	            msxml = new ActiveXObject(aVersions[i]); 
	            break;
	        } 
	        catch (oError)
	        {}
	    }
		
		if(msxml == null)
			throw new Error("MSXML is not installed.");
		
		msxml.async = false; // Not strictly necessary
		msxml.loadXML(strXML);
		root = msxml.documentElement;
	}
	else
	{
		if(window.DOMParser)
		{
			// Firefox and Opera
			var parser = new DOMParser();
			var doc = parser.parseFromString(strXML, "application/xml");
			root = doc.documentElement;
		}
		else
		{
			alert("Cannot initialise an XML parser");
			return;
		}
	}

	return root;
}

////////////////////////////////////////////////////////////////////////////////


function stringToDate(strDate)
{
	var pattern_w3c = /^(\d\d\d\d)[-\/ ](\d{1,2})[-\/ ](\d{1,2})(?:\s(\d\d):(\d\d):(\d\d))?$/;
	var pattern_gb = /^(\d{1,2})[-\/ ](\d{1,2})[-\/ ](\d\d\d\d)(?:\s(\d\d):(\d\d):(\d\d))?$/;
	var day, month, year, hours, minutes, seconds;
	
	var matches = null;
	if(matches = pattern_w3c.exec(strDate))
	{
		year = matches[1];
		month = matches[2];
		day = matches[3];
		hours = (matches.length >= 4 && matches[4] != null && matches[4] != '') ? $matches[4]:'0';
		minutes = (matches.length >= 5 && matches[5] != null && matches[5] != '') ? $matches[5]:'0';
		seconds = (matches.length >= 6 && matches[6] != null && matches[6] != '') ? $matches[6]:'0';
	}
	else if (matches = pattern_gb.exec(strDate))
	{
		year = matches[3];
		month = matches[2];
		day = matches[1];
		hours = (matches.length >= 4 && matches[4] != null && matches[4] != '') ? $matches[4]:'0';
		minutes = (matches.length >= 5 && matches[5] != null && matches[5] != '') ? $matches[5]:'0';
		seconds = (matches.length >= 6 && matches[6] != null && matches[6] != '') ? $matches[6]:'0';
	}
	else
	{
		return null;
	}
	
		
	// Remove any zero pre-fixes
	if(month.length == 2 && month.charAt(0) == '0')
	{
		month = month.charAt(1);
	}
	if(day.length == 2 && day.charAt(0) == '0')
	{
		day = day.charAt(1);
	}
	if(hours.length == 2 && hours.charAt(0) == '0')
	{
		hours = hours.charAt(1);
	}
	if(minutes.length == 2 && minutes.charAt(0) == '0')
	{
		minutes = minutes.charAt(1);
	}
	if(seconds.length == 2 && seconds.charAt(0) == '0')
	{
		seconds = seconds.charAt(1);
	}
	
	// Convert strings to numbers
	year = parseInt(year);
	month = parseInt(month);
	day = parseInt(day);
	hours = parseInt(hours);
	minutes = parseInt(minutes);
	seconds = parseInt(seconds);
	
	
	// Create date object and check that the user has entered a valid calendar date
	// (NB months in JavaScript run from 0-11)
	var d = new Date(year, month - 1, day, hours, minutes, seconds);
	if( (d.getFullYear() != year) || ((d.getMonth() + 1) != month) || (d.getDate() != day)
		|| (d.getHours() != hours) || (d.getMinutes() != minutes) || (d.getSeconds() != seconds) )
	{
		// Invalid calendar date
		return null;
	}
	
	return d;
}



function formatDateW3C(objDate)
{
	if(!(objDate instanceof Date))
	{
		return "";
	}

	var year = "" + objDate.getFullYear();
	var month = "" + (objDate.getMonth() + 1); // Jan == 0, Dec == 11
	var day = "" + objDate.getDate();
	
	while(year.length < 4)
		year = "0" + year;
	while(month.length < 2)
		month = "0" + month;
	while(day.length < 2)
		day = "0" + day;
		
	return year + "-" + month + "-" + day;
}


function formatDateGB(objDate)
{
	if(!(objDate instanceof Date))
	{
		return "";
	}
	
	var year = "" + objDate.getFullYear();
	var month = "" + (objDate.getMonth() + 1); // Jan == 0, Dec == 11
	var day = "" + objDate.getDate();

	while(year.length < 4)
		year = "0" + year;
	while(month.length < 2)
		month = "0" + month;
	while(day.length < 2)
		day = "0" + day;
	
	return day + "/" + month + "/" + year;
}

// Khushnood
function exportToExcel(view)
{
	headers = document.getElementsByTagName('th');
	line = '';
	for(a=0; a<headers.length; a++)
	{	
		if(headers[a].innerText!='' && headers[a].innerText!=' ')
		{
			if(line.length>0)
				line += ',';
			line += headers[a].innerText.toLowerCase();		
		}
	}
	line = line.replace(/ /g,'_');	
	line = line.replace(/&/g,'and');	
	line = line.replace(/%/g,'percentage');
	window.location.href = "do.php?_action=export_current_view_to_excel&key=" + view + "&columns=" + line;
}

////////////////////////////////////////////////////////////////////////////////
// ACCESS CONTROL FUNCTIONS
////////////////////////////////////////////////////////////////////////////////

function acl_identity_onclick(identity, content_pane, max_selected)
{
	if(max_selected == 0 || identity.checked == false)
	{
		// No limit to selection or this is a deselection
		identity.nextSibling.checked=!identity.checked;
		identity.parentNode.parentNode.className=identity.checked?'aclRowSelected':'aclRowUnselected';
		content_pane.update(identity);
	}
	else
	{
		// Count the number of identities already selected
		var elements = identity.form.elements[identity.name];
		var total = 0;
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i] != identity && elements[i].checked)
			{
				total++;
			}
		}
		
		identity.checked = total < max_selected;

		identity.nextSibling.checked=!identity.checked;
		identity.parentNode.parentNode.className=identity.checked?'aclRowSelected':'aclRowUnselected';
		content_pane.update(identity);		

	}
}

//////////////////////////////////////////////////
// Submit the filter form
function submitSavedFilter(obj)
{
	document.getElementById('applySavedFilter').submit();
}

function doSaveFilter()
{
	var select = document.getElementById('savedFilter');
	
	// if the element exists, we know we have some filters to play with
	if(select != null)
	{
		var selText = select.options[select.selectedIndex].text;
		var selValue = select.options[select.selectedIndex].value;
	
		if(selValue != 'g0')
		{
			// global filter over-riding not allowed
			if(selValue.substr(0,1) == 'g')
			{
				alert('You cannot overwrite a pre-defined filter');
				return;
			}
			else
			{		
				if(confirm('You are UPDATING a filter because you have an item chosen in the dropdown. Click okay if you want to update the filter, otherwise click cancel and change the dropdown to add a new one.'))
				{
					var name = prompt('You are updating the filter. If you wish to change its name, please do so below.', selText);
					document.getElementById('filter_id').value = selValue;
				}
				else
				{
					return
				}
			}
		}
		else
		{
			var name = prompt('Please enter a short name for this new filter you are saving');
		}
	}
	else
	{
		var name = prompt('Please enter a short name for this new filter you are saving');
	}
	
	if(name == "" || name == null)
	{
		alert('You must enter a name for the filter');
		return false;
	}
	
	document.getElementById('filter_name').value = name;
	document.getElementById('applyFilter').submit();	
}

function populateFilter(viewName)
{
	var select = document.getElementById('savedFilter');
	var value = select.options[select.selectedIndex].value;
	var request = ajaxBuildRequestObject();
	request.open("GET", expandURI('do.php?_action=ajax_get_saved_filter&id=' + value), false);
	request.setRequestHeader("x-ajax", "1"); // marker for server code
	request.send(null);
	 
	arr = new Array();
	arr[0] = "";
	if(request.status == 200)
	{

		var evidencexml = request.responseXML;
		var xmlDoc = evidencexml.documentElement;
		
		if(xmlDoc.tagName != 'error')
		{
			var filters = xmlDoc.childNodes[0].childNodes[4].childNodes;

			for(var i = 0; i < filters.length; i++)
			{
				var key = filters[i].attributes[0].nodeValue;
				if(filters[i].childNodes[0] != null)
				{
					var value = filters[i].childNodes[0].nodeValue;
				}
				else
				{
					var value = "";
				}
				
				var id = (viewName.length > 0 ? viewName + "_" + key : key)

				//console.log("looking for " + id);
				var element = document.getElementById(id);
				if(element != null)
				{
					//console.log("found: " + id)
					if(element.tagName == "INPUT" || element.tagName == "SELECT")
					{
						if(element.tagName == "INPUT")
						{
							element.value = value;
						}
						else if(element.tagName == "SELECT")
						{
							for(var j = 0; j < element.options.length; j++)
							{
								//console.log("Comparing " + element.options[j].value + " to " + value);
								if(element.options[j].value == value)
								{
									//console.log("Found a match for " + key + " with value = " + value + " and option value = " + element.options[j].value);
									element.selectedIndex = j;
								}
							}
						}
					}
				}

			}
		}
	}	
}
