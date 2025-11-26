<?php /* @var $vo CourseQualification */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Individual Learner Record</title>
<link rel="stylesheet" href="/common.css" type="text/css"/>
<link rel="stylesheet" href="niceforms.css" type="text/css"/>

<script src="/js/jquery.min.js" type="text/javascript"></script>
<script src="/common.js" type="text/javascript"></script>

<!-- Calendar popup: credit to Matt Kruse (www.javascripttoolbox.com) -->
<script type="text/javascript" src="/calendarPopup/CalendarPopup.js"></script>

<!-- Initialise calendar popup -->
<script type="text/javascript">
	<?php if(preg_match('/MSIE [1-6]/', $_SERVER['HTTP_USER_AGENT']) ) { ?>
	var calPop = new CalendarPopup();
	calPop.showNavigationDropdowns();
		<?php } else { ?>
	var calPop = new CalendarPopup("calPop1");
	calPop.showNavigationDropdowns();
	document.write(getCalendarStyles());
		<?php } ?>
</script>

<script type="text/javascript">
	if (typeof window.event != 'undefined') // IE
		document.onkeydown = function() // IE
			document.onkeydown = function() // IE
			{
				var t=event.srcElement.type;
				var kc=event.keyCode;
				return ((kc != 8 && kc != 13) || ( t == 'text' &&  kc != 13 ) ||
					(t == 'textarea') || ( t == 'submit' &&  kc == 13))
			}
	else
		document.onkeypress = function(e)  // FireFox/Others
		{
			var t=e.target.type;
			var kc=e.keyCode;
			if ((kc != 8 && kc != 13) || ( t == 'text' &&  kc != 13 ) ||
				(t == 'textarea') || ( t == 'submit' &&  kc == 13))
				return true
			else {
				alert('Sorry Backspace/Enter is not allowed here'); // Demo code
				return false
			}
		}

</script>

<script type="text/javascript">
var aims_counter = 0;
var xml="<ilr>";
var error="";




function getField(fieldName, element)
{
	if(element == null)
	{
		element = this;
	}

	// Check all children of the node first
	var children = element.childNodes;
	for(var i = 0; i < children.length; i++)
	{
		var ret = this.getField(fieldName, children[i]);
		if(ret != null)
		{
			return ret;
		}
	}

	// Then check the node itself
	if(element.name == fieldName)
	{
		return element;
	}

	return null;
}


function go()
{
	var container = document.getElementById('aimsContainer');
	var template = document.getElementById('sub')
	var newAim = template.cloneNode(true);
	newAim.getEsf = template.getEsf;
	newAim.id = "sub" + (++window.aims_counter);

// Shove values from Main aim to the newly created sub aim
	var div = newAim;
	if(div != null)
	{
		var elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i] != "radio" || (elements[i].type == "radio" && elements[i].checked) )
			{
				if(elements[i].name=='SA27')
					elements[i].value = document.ilr.A27.value;
				if(elements[i].name=='SA28')
					elements[i].value = document.ilr.A28.value;
				if(elements[i].name=='SA51a')
					elements[i].value = document.ilr.A51a.value;
				if(elements[i].name=='SA23')
					elements[i].value = document.ilr.A23.value;
			}
			if(elements[i].type == "checkbox")
			{
			}
		}
		elements = div.getElementsByTagName('select');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i].name=='SA10')
				elements[i].value = document.ilr.A10.value;
			if(elements[i].name=='SA15')
				elements[i].value = document.ilr.A15.value;
			if(elements[i].name=='SA16')
				elements[i].value = document.ilr.A16.value;
			if(elements[i].name=='SA26')
				elements[i].value = document.ilr.A26.value;
			if(elements[i].name=='SA18')
				elements[i].value = document.ilr.A18.value;
			if(elements[i].name=='SA22')
				elements[i].value = document.ilr.A22.value;
			if(elements[i].name=='SA71')
				elements[i].value = document.ilr.A71.value;
			if(elements[i].name=='SA66')
				elements[i].value = document.ilr.A66.value;
			if(elements[i].name=='SA67')
				elements[i].value = document.ilr.A67.value;
			if(elements[i].name=='SA47')
				elements[i].value = document.ilr.A47.value;
			if(elements[i].name=='SA35')
				elements[i].value = "9";
		}
	}
//

	container.appendChild(newAim);
	newAim.style.display = "block";



}


function deleteAim(butt)
{
	var node = butt;
	do
	{
		node = node.parentNode;
	} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));
	if(window.confirm("Do you want to delete this Subsidiary Aim"))
	{
		if(parseInt(node.id.substr(3,2))==window.aims_counter)
		{
			var aim = document.getElementById('sub'+window.aims_counter);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			window.aims_counter--;
		}
		else
		{
			var aim = document.getElementById(node.id);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			for(var a = parseInt(node.id.substr(3,2)); a<window.aims_counter; a++)
			{
				document.getElementById('sub'+(a+1)).id = 'sub'+a;
			}
			window.aims_counter--;
		}
	}
}

function addAim(butt)
{

	if(window.aims_counter==0)
		go();
	else
	{	var node = butt;
		do
		{
			node = node.parentNode;
		} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));

		var container = document.getElementById('aimsContainer');
		var template = document.getElementById('sub')
		var newAim = template.cloneNode(true);
		newAim.getEsf = template.getEsf;
		var newSequenceNumber = parseInt(node.id.substr(3,2)) + 1;
		newAim.id = "sub" + newSequenceNumber;

		container.insertBefore(newAim,document.getElementById('sub'+(parseInt(node.id.substr(3,2))+1)));
		newAim.style.display = "block";

		// Re-id subsidiary aims
		var children = container.childNodes;
		window.aims_counter = 0;
		for(var i = 0; i < children.length; i++)
		{
			// #115 - riche ensuring only divs get incremented
			if( children[i].tagName == 'DIV' ) {
				children[i].id = 'sub' + ++window.aims_counter;
			}
		}

	}
}

function addAimFromMain(butt)
{
	if(window.aims_counter==0)
		go();
	else
	{
		var container = document.getElementById('aimsContainer');
		var template = document.getElementById('sub')
		var newAim = template.cloneNode(true);
		newAim.getEsf = template.getEsf;
		container.insertBefore(newAim,document.getElementById('sub1'));

// Shove values from Main aim to the newly created sub aim
		var div = newAim;
		if(div != null)
		{
			var elements = div.getElementsByTagName('input');
			for(var i = 0; i < elements.length; i++)
			{
				if(elements[i] != "radio" || (elements[i].type == "radio" && elements[i].checked) )
				{
					if(elements[i].name=='SA27')
						elements[i].value = document.ilr.A27.value;
					if(elements[i].name=='SA28')
						elements[i].value = document.ilr.A28.value;
					if(elements[i].name=='SA51a')
						elements[i].value = document.ilr.A51a.value;
					if(elements[i].name=='SA23')
						elements[i].value = document.ilr.A23.value;
					if(elements[i].name=='SA31')
						elements[i].value = document.ilr.A31.value;
					if(elements[i].name=='SA40')
						elements[i].value = document.ilr.A40.value;
				}
				if(elements[i].type == "checkbox")
				{
				}
			}
			elements = div.getElementsByTagName('select');
			for(var i = 0; i < elements.length; i++)
			{
				if(elements[i].name=='SA10')
					elements[i].value = document.ilr.A10.value;
				if(elements[i].name=='SA15')
					elements[i].value = document.ilr.A15.value;
				if(elements[i].name=='SA16')
					elements[i].value = document.ilr.A16.value;
				if(elements[i].name=='SA26')
					elements[i].value = document.ilr.A26.value;
				if(elements[i].name=='SA18')
					elements[i].value = document.ilr.A18.value;
				if(elements[i].name=='SA22')
					elements[i].value = document.ilr.A22.value;
				if(elements[i].name=='SA71')
					elements[i].value = document.ilr.A71.value;
				if(elements[i].name=='SA66')
					elements[i].value = document.ilr.A66.value;
				if(elements[i].name=='SA67')
					elements[i].value = document.ilr.A67.value;
				if(elements[i].name=='SA47')
					elements[i].value = document.ilr.A47.value;
				if(elements[i].name=='SA35')
					elements[i].value = "9";
				if(elements[i].name=='SA31')
					elements[i].value = document.ilr.A31.value;
				if(elements[i].name=='SA40')
					elements[i].value = document.ilr.A40.value;
				if(elements[i].name=='SA34')
					elements[i].value = document.ilr.A34.value;
				if(elements[i].name=='SA35')
					elements[i].value = document.ilr.A35.value;
			}
		}
//


		newAim.style.display = "block";
		// Re-id subsidiary aims
		var children = container.childNodes;
		window.aims_counter = 0;
		for(var i = 0; i < children.length; i++)
		{
			// #115 - riche ensuring only divs get incremented
			if( children[i].tagName == 'DIV' ) {
				children[i].id = 'sub' + ++window.aims_counter;
			}
		}

	}
}

function A06_onchange(select)
{
	if(select.value=='01')
		document.getElementById('MainAimESF').style.display = "block";
	else
		document.getElementById('MainAimESF').style.display = "None";
}


function SA06_onchange(select)
{
	var node = select;
	do
	{
		node = node.parentNode;
	} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));

	var esf = node.getEsf();
	showHideBlock(esf, (select.value == '01') );

}


function del()
{
	if(window.confirm("Do you want to delete Subsidiary Aim?"))
	{
		if (window.aims_counter>0)
		{
			var aim = document.getElementById('sub'+window.aims_counter);
			var container = document.getElementById('aimsContainer');
			container.removeChild(aim);
			window.aims_counter--;
		}
	}
}

function validate()
{
	var temp;
	var flag="";
	var ilr_form = document.getElementById('ilr');


	// Unique Learner Number
	if (parseFloat(gf("L45").value!=""))
	{if (parseFloat(gf("L45").value)<1000000000 || parseFloat(gf("L45").value)>9999999999)
	{gf("L45").className="empty";
		alert("The Valid Unique Learner Number Must be Between 1000000000 and 9999999999");
		if (flag=="")
			flag="L45";
	}
	else
		document.ilr.L45.value="0000000000";
	}

	// National Insurance Validation 	
	var v = gf("L26").value;
	if (v.length==9)
	{if(!(isAlpha(v.charCodeAt(0)) && v.charAt(0)!="D" && v.charAt(0)!="F" && v.charAt(0)!="I" && v.charAt(0)!="Q" && v.charAt(0)!="U" && v.charAt(0)!="V" && isAlpha(v.charCodeAt(1)) && v.charAt(1)!="D" && v.charAt(1)!="F" && v.charAt(1)!="I" && v.charAt(1)!="O" && v.charAt(1)!="Q" && v.charAt(1)!="U" && v.charAt(1)!="V" && isDigit(v.charCodeAt(2)) && isDigit(v.charCodeAt(3)) && isDigit(v.charCodeAt(4)) && isDigit(v.charCodeAt(5)) && isDigit(v.charCodeAt(6)) && isDigit(v.charCodeAt(7)) && isAlpha(v.charCodeAt(8))))
	{gf("L26").className="empty";
		alert("Invalid National Insurance Number");
		if (flag=="")
			flag="L26";
	}}
	else
	{gf("L26").className="empty";
		alert("Missing Characters");
		if (flag=="")
			flag="L26";
	}

	// Postcode Validation
	v = gf("L17").value;
	if (!(validPC(v)))
	{gf("L17").className="empty";
		if (flag=="")
			flag="L17";
	}

	v = gf("L22").value;
	if (!(validPC(v)))
	{gf("L22").className="empty";
		if (flag=="")
			flag="L22";
	}

	// A01 and L01 must be same
	if (document.ilr.L01.options[document.ilr.L01.selectedIndex].value != document.ilr.A01.options[document.ilr.A01.selectedIndex].value)
	{
		alert("UPIN must be same in learner data set and aim data set");
		if (flag=="")
			flag="L01";
	}

	// A55 and L45 must be same
	if (document.ilr.A55.value != document.ilr.L45.value)
	{
		alert("A55 and L45 fields must be the same");
		if (flag=="")
			flag="L45";
	}

	// A56 and L46 must be same
	if (document.ilr.A56.value != document.ilr.L46.value)
	{
		alert("A56 and L46 fields must be the same");
		if (flag=="")
			flag="L46";
	}


	// Learning support reason
	v = parseInt(gf("L34a").value);
	if (!(v==1 || v==25 || v==41 || v==99))
	{gf("L34a").className="empty";
		if (flag=="")
			flag="L34a";
	}

	// Guided learning hours
	/* 	v = parseInt(gf("A32").value);
	  if (!(v>=00000 && v<=10000))
		  {
		  alert("Hours must be between 00000 and 10000");
		  gf("A32").className="empty";
		  if (flag=="")
			  flag="A32";
		  }
 */

	// Learning support reason
	v = parseInt(gf("L34b").value);
	if (!(v==1 || v==25 || v==41 || v==99))
	{gf("L34b").className="empty";
		if (flag=="")
			flag="L34b";
	}

	// Learning support reason
	v = parseInt(gf("L34c").value);
	if (!(v==1 || v==25 || v==41 || v==99))
	{gf("L34c").className="empty";
		if (flag=="")
			flag="L34c";
	}
	// Learning support reason
	v = parseInt(gf("L34d").value);
	if (!(v==1 || v==25 || v==41 || v==99))
	{gf("L34d").className="empty";
		if (flag=="")
			flag="L34d";
	}


	// UK Provider Reference Number
	if (parseFloat(gf("A56").value)<10000000 || parseFloat(gf("A56").value)>99999999)
	{gf("A56").className="empty";
		//alert("The Valid UK Provider Reference Number Must be Between 10000000 and 99999999");
		if (flag=="")
			flag="A56";
	}

	// Unique Learner Number 	 	
	if (parseFloat(gf("A55").value)<1000000000 || parseFloat(gf("A55").value)>9999999999)
	{gf("A55").className="empty";
		//alert("The Valid Unique Learner Number Must be Between 1000000000 and 9999999999");
		if (flag=="")
			flag="A55";
	}


	// Proportion of Funding A51a
	v = parseInt(gf("A51a").value);
	if (!(v>=00 && v<=99))
	{gf("A51a").className="empty";
		//alert("Invalid Proportion of Funding");
		if (flag=="")
			flag="A51a";
	}


	// Special Projects and Pilots Codes
	v = gf("A49").value;
	if(v!='')
	{a=v.substr(0,2);
		var b = parseInt(v.substr(2,v.length-2));
		if (! (v.length==5 && ( (a=="SS" && b>=1 && b<=150) || (a=="CV" && b>=1 && b<=500) || (a=="SP" && b>=1 && b<=500) ) ))
		{gf("A49").className="empty";
			//alert("Invalid Special Projects and Pilots Codes");
			if (flag=="")
				flag="A49";
		}
	}
	// Postcode Validation
	v = gf("A45").value;
	if (!(validPC(v)))
	{gf("A45").className="empty";
		if (flag=="")
			flag="A45";
	}

	// Postcode Validation
	v = gf("A23").value;
	if (!(validPC(v)))
	{gf("A23").className="empty";
		if (flag=="")
			flag="A23";
	}


	if (flag!="")
	{gf(flag).focus()
		return false;}
	else
		return true;

}


function validPC(v)
{
	if (!(v.length>5 && v.length<9 && charCount(v," ")==1 && isAlpha(v.charCodeAt(0)) && isDigit(v.charCodeAt(v.indexOf(" ")+1)) && isAlpha(v.charCodeAt(v.indexOf(" ")+2)) && isAlpha(v.charCodeAt(v.indexOf(" ")+3)) && v.charAt(v.length-4)==" "))
		return false;
	else
		return true;
}

function isAlpha(ch)
{
	if( (ch>=65 && ch<=90) || (ch>=97 && ch<=122) )
		return true;
	else
		return false;
}

function isDigit(ch)
{
	if (ch>=48 && ch<=57)
		return true;
	else
		return false;
}

function charCount(st,ch)
{
	var c=0;
	for(var x=0;x<st.length;x++)
	{	if(st.charAt(x)==ch)
		c++;}
	return c;
}

function gf(f)
{ return document.getElementById(f);
}

function A10_onchange(value)
{
	a10 = value.value;
	a15 = document.ilr.A15.options[document.ilr.A15.selectedIndex].value;
	a18 = document.ilr.A18.options[document.ilr.A18.selectedIndex].value;
	if(a10 == 45 && a15 == 99 && (a18 == 22 || a18 == 23) )
		freezProgrammeAim(true);
	else
		freezProgrammeAim(false);
}

function PA10_onchange(value)
{
	pa10 = value.value;
	if(pa10=='70')
	{
		document.getElementById('PA09').value = 'ZESF0001';
		document.getElementById('PA04').value = '30';
	}
	else
	{
		document.getElementById('PA09').value = 'ZPROG001';
		document.getElementById('PA04').value = '35';
	}
}


/*function A18_onchange(value)
{
	a18 = value.value;
	a15 = document.ilr.A15.options[document.ilr.A15.selectedIndex].value;
	a18 = document.ilr.A18.options[document.ilr.A18.selectedIndex].value;
	if(a10 == 45 && a15 == 99 && (a18 == 22 || a18 == 23) )
		freezProgrammeAim(true);
	else
		freezProgrammeAim(false);

}
*/
function freezProgrammeAim(v)
{
	document.ilr.PA04.disabled = v;
	document.ilr.PA09.disabled = v;
	document.ilr.PA10.disabled = v;
	document.ilr.PA15.disabled = v;
	document.ilr.PA16.disabled = v;
	document.ilr.PA27.disabled = v;
	document.ilr.PA28.disabled = v;
	document.ilr.PA23.disabled = v;
	document.ilr.PA26.disabled = v;
	document.ilr.PA46a.disabled = v;
	document.ilr.PA46b.disabled = v;
	document.ilr.PA51a.disabled = v;
}

function validateDate(d)
{
	if(d.length!=10)
		return false;
	else
	if(parseFloat(d.substr(0,2))<1 || parseFloat(d.substr(0,2))>31)
		return false;
	else
	if(parseFloat(d.substr(2,2))<1 || parseFloat(d.substr(2,2))>12)
		return false;
	else
	if(parseFloat(d.substr(5,4))<1900 || parseFloat(d.substr(5,4))>2009)
		return false;
	else
		return true;
}

function toXML()
{
	var xml = '<Learner>';
	xml += "\n<LearnRefNumber>" + document.ilr.LearnRefNumber.value + "</LearnRefNumber>";
	xml += "\n<ULN>" + document.ilr.ULN.value + "</ULN>";
	xml += "\n<FamilyName>" + document.ilr.FamilyName.value + "</FamilyName>";
	xml += "\n<GivenNames>" + document.ilr.GivenNames.value + "</GivenNames>";
	xml += "\n<DateOfBirth>" + document.ilr.DateOfBirth.value + "</DateOfBirth>";
	xml += "\n<Ethnicity>" + document.ilr.Ethnicity.options[document.ilr.Ethnicity.selectedIndex].value + "</Ethnicity>";
	if (document.ilr.Sex[0].checked)
		xml += "\n<Sex>" + "M" + "</Sex>";
	else
		xml += "\n<Sex>" + "F" + "</Sex>";
	xml += "\n<LLDDHealthProb>" + document.ilr.LLDDHealthProb.options[document.ilr.LLDDHealthProb.selectedIndex].value + "</LLDDHealthProb>";
	xml += "\n<NINumber>" + document.ilr.NINumber.value + "</NINumber>";
	if(typeof(document.ilr.Domicile)!='undefined')
		xml += "\n<Domicile>" + document.ilr.Domicile.options[document.ilr.Domicile.selectedIndex].value + "</Domicile>";
	xml += "\n<PriorAttain>" + document.ilr.PriorAttain.options[document.ilr.PriorAttain.selectedIndex].value + "</PriorAttain>";
	if(typeof(document.ilr.Accom)!='undefined')
		xml += "\n<Accom>" + document.ilr.Accom.options[document.ilr.Accom.selectedIndex].value + "</Accom>";
	if(typeof(document.ilr.ALSCost)!='undefined')
		xml += "\n<ALSCost>" + document.ilr.ALSCost.value + "</ALSCost>";
	if(typeof(document.ilr.DisUpFact)!='undefined')
		xml += "\n<DisUpFact>" + document.ilr.DisUpFact.value + "</DisUpFact>";
	xml += "\n<Dest>" + document.ilr.Dest.options[document.ilr.Dest.selectedIndex].value + "</Dest>";

	if(document.ilr.AddLine1.value!='' || document.ilr.AddLine2.value!='' || document.ilr.AddLine3.value!='' || document.ilr.AddLine4.value!='')
	{
		xml+= "\n<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
		if(document.ilr.AddLine1.value!='')
			xml+= "\n<AddLine1>" + document.ilr.AddLine1.value + "</AddLine1>";
		if(document.ilr.AddLine2.value!='')
			xml+= "\n<AddLine2>" + document.ilr.AddLine2.value + "</AddLine2>";
		if(document.ilr.AddLine3.value!='')
			xml+= "\n<AddLine3>" + document.ilr.AddLine3.value + "</AddLine3>";
		if(document.ilr.AddLine4.value!='')
			xml+= "\n<AddLine4>" + document.ilr.AddLine4.value + "</AddLine4>";
		xml += "\n</PostAdd></LearnerContact>";
	}

	if(document.ilr.CurrentPostcode.value!='')
	{
		xml+= "<LearnerContact><LocType>2</LocType><ContType>2</ContType>";
		xml+= "<PostCode>" + document.ilr.CurrentPostcode.value + "</PostCode>";
		xml += "</LearnerContact>";
	}

	if(document.ilr.PostcodePriorEnrolment.value!='')
	{
		xml+= "<LearnerContact><LocType>2</LocType><ContType>1</ContType>";
		xml+= "<PostCode>" + document.ilr.PostcodePriorEnrolment.value + "</PostCode>";
		xml += "</LearnerContact>";
	}

	if(document.ilr.Email.value!='')
	{
		xml+= "<LearnerContact><LocType>4</LocType><ContType>2</ContType>";
		xml+= "<Email>" + document.ilr.Email.value + "</Email>";
		xml += "</LearnerContact>";
	}

	if(document.ilr.TelNumber.value!='')
	{
		xml+= "<LearnerContact><LocType>3</LocType><ContType>2</ContType>";
		xml+= "<TelNumber>" + document.ilr.TelNumber.value + "</TelNumber>";
		xml += "</LearnerContact>";
	}

	if(document.ilr.RUI1.checked && document.ilr.RUI2.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>3</ContPrefCode></ContactPreference>";
	else if(document.ilr.RUI1.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";
	else if(document.ilr.RUI2.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";

	if(document.ilr.PMC1.checked)
		xml+="<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";
	if(document.ilr.PMC2.checked)
		xml+="<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";
	if(document.ilr.PMC3.checked)
		xml+="<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>3</ContPrefCode></ContactPreference>";

	if(document.ilr.DS.options[document.ilr.DS.selectedIndex].value!='')
		xml+="<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>"+ document.ilr.DS.options[document.ilr.DS.selectedIndex].value +"</LLDDCode></LLDDandHealthProblem>";

	if(document.ilr.LD.options[document.ilr.LD.selectedIndex].value!='')
		xml+="<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>"+ document.ilr.LD.options[document.ilr.LD.selectedIndex].value +"</LLDDCode></LLDDandHealthProblem>";

	if(typeof(document.ilr.EFE)!='undefined')
		if(document.ilr.EFE.options[document.ilr.EFE.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>EFE</LearnFAMType><LearnFAMCode>"+ document.ilr.EFE.options[document.ilr.EFE.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.LDA)!='undefined')
		if(document.ilr.LDA.options[document.ilr.LDA.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>"+ document.ilr.LDA.options[document.ilr.LDA.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.ALS)!='undefined')
		if(document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>"+ document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DUE)!='undefined')
		if(document.ilr.DUE.options[document.ilr.DUE.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DUE</LearnFAMType><LearnFAMCode>"+ document.ilr.DUE.options[document.ilr.DUE.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.LSR1)!='undefined')
		if(document.ilr.LSR1.options[document.ilr.LSR1.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>"+ document.ilr.LSR1.options[document.ilr.LSR1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.LSR2)!='undefined')
		if(document.ilr.LSR2.options[document.ilr.LSR2.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>"+ document.ilr.LSR2.options[document.ilr.LSR2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.LSR3)!='undefined')
		if(document.ilr.LSR3.options[document.ilr.LSR3.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>"+ document.ilr.LSR3.options[document.ilr.LSR3.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.LSR4)!='undefined')
		if(document.ilr.LSR4.options[document.ilr.LSR4.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>"+ document.ilr.LSR4.options[document.ilr.LSR4.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DSF1)!='undefined')
		if(document.ilr.DSF1.options[document.ilr.DSF1.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>"+ document.ilr.DSF1.options[document.ilr.DSF1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DSF2)!='undefined')
		if(document.ilr.DSF2.options[document.ilr.DSF2.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>"+ document.ilr.DSF2.options[document.ilr.DSF2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DSF3)!='undefined')
		if(document.ilr.DSF3.options[document.ilr.DSF3.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>"+ document.ilr.DSF3.options[document.ilr.DSF3.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DSF4)!='undefined')
		if(document.ilr.DSF4.options[document.ilr.DSF4.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>"+ document.ilr.DSF4.options[document.ilr.DSF4.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DSF5)!='undefined')
		if(document.ilr.DSF5.options[document.ilr.DSF5.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DSF</LearnFAMType><LearnFAMCode>"+ document.ilr.DSF5.options[document.ilr.DSF5.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.NLM1)!='undefined')
		if(document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.NLM2)!='undefined')
		if(document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(document.ilr.ProvSpecLearnMonA.value!='')
		xml+="<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>"+ document.ilr.ProvSpecLearnMonA.value +"</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
	if(document.ilr.ProvSpecLearnMonB.value!='')
		xml+="<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>"+ document.ilr.ProvSpecLearnMonB.value +"</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

	for(a = 1; a<=5; a++)
	{
		v1 = "document.ilr.EmpStat" + a;
		v2 = "document.ilr.EmpStat" + a + ".options[document.ilr.EmpStat" + a + ".selectedIndex].value";
		if(typeof(eval(v1))!='undefined' && eval(v2)!='')
		{
			xml+="<LearnerEmploymentStatus>";
			if(typeof(eval(v1))!='undefined')
				if(eval(v2)!='')
					xml+="<EmpStat>"+ eval(v2) +"</EmpStat>";

			v3 = "document.ilr.DateEmpStatApp" + a + ".value";
			if(eval(v3)!='')
				xml+="<DateEmpStatApp>"+ eval(v3) +"</DateEmpStatApp>";

			v4 = "document.ilr.EmpId" + a + ".value";
			if(eval(v4)!='')
				xml+="<EmpId>"+ eval(v4) +"</EmpId>";

			v5 = "document.ilr.WorkLocPostCode" + a + ".value";
			if(eval(v5)!='')
				xml+="<WorkLocPostCode>"+ eval(v5) +"</WorkLocPostCode>";

			sei = "document.ilr.SEI" + a + ".options[document.ilr.SEI" + a + ".selectedIndex].value";
			if(eval(sei)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" + eval(sei) + "</ESMCode></EmploymentStatusMonitoring>";

			eii = "document.ilr.EII" + a + ".options[document.ilr.EII" + a + ".selectedIndex].value";
			if(eval(eii)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>" + eval(eii) + "</ESMCode></EmploymentStatusMonitoring>";

			lou = "document.ilr.LOU" + a + ".options[document.ilr.LOU" + a + ".selectedIndex].value";
			if(eval(lou)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" + eval(lou) + "</ESMCode></EmploymentStatusMonitoring>";

			bsi = "document.ilr.BSI" + a + ".options[document.ilr.BSI" + a + ".selectedIndex].value";
			if(eval(bsi)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>" + eval(bsi) + "</ESMCode></EmploymentStatusMonitoring>";

			pei = "document.ilr.PEI" + a + ".options[document.ilr.PEI" + a + ".selectedIndex].value";
			if(eval(pei)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>PEI</ESMType><ESMCode>" + eval(pei) + "</ESMCode></EmploymentStatusMonitoring>";

			ron = "document.ilr.RON" + a + ".options[document.ilr.RON" + a + ".selectedIndex].value";
			if(eval(ron)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>RON</ESMType><ESMCode>" + eval(ron) + "</ESMCode></EmploymentStatusMonitoring>";

			xml+="</LearnerEmploymentStatus>";
		}
	}

	var sf = null;
	for(subaims = 1; subaims <= window.aims_counter; subaims++)
	{
		sf = getSubsidiaryAimFields(subaims);
		if(sf == null)
		{
			alert("Cannot find subaim 'sub" + subaims + "'");
		}

		xml += "<LearningDelivery>";
		xml += "\n<LearnAimRef>" + sf['LearnAimRef'] + "</LearnAimRef>";
		xml += "\n<AimType>" + sf['AimType'] + "</AimType>";
		xml += "\n<AimSeqNumber>" + subaims + "</AimSeqNumber>";
		xml += "\n<LearnStartDate>" + sf['LearnStartDate'] + "</LearnStartDate>";
		xml += "\n<LearnPlanEndDate>" + sf['LearnPlanEndDate'] + "</LearnPlanEndDate>";
		xml += "\n<FundModel>" + sf['FundModel'] + "</FundModel>";
		xml += "\n<GLH>" + sf['GLH'] + "</GLH>";
		xml += "\n<PlanCredVal>" + sf['PlanCredVal'] + "</PlanCredVal>";
		xml += "\n<ProgType>" + sf['ProgType'] + "</ProgType>";
		xml += "\n<FworkCode>" + sf['FworkCode'] + "</FworkCode>";
		xml += "\n<PwayCode>" + sf['PwayCode'] + "</PwayCode>";
		xml += "\n<ProgEntRoute>" + sf['ProgEntRoute'] + "</ProgEntRoute>";
		xml += "\n<MainDelMeth>" + sf['MainDelMeth'] + "</MainDelMeth>";
		xml += "\n<DelMode>" + sf['DelMode'] + "</DelMode>";
		xml += "\n<PartnerUKPRN>" + sf['PartnerUKPRN'] + "</PartnerUKPRN>";
		xml += "\n<DelLocPostCode>" + sf['DelLocPostCode'] + "</DelLocPostCode>";
		xml += "\n<DistLearnSLN>" + sf['DistLearnSLN'] + "</DistLearnSLN>";
		xml += "\n<FeeYTD>" + sf['FeeYTD'] + "</FeeYTD>";
		xml += "\n<FeeSource>" + sf['FeeSource'] + "</FeeSource>";
		xml += "\n<PropFundRemain>" + sf['PropFundRemain'] + "</PropFundRemain>";
		xml += "\n<EmpRole>" + sf['EmpRole'] + "</EmpRole>";
		xml += "\n<ESFProjDosNumber>" + sf['ESFProjDosNumber'] + "</ESFProjDosNumber>";
		xml += "\n<ESFLocProjNumber>" + sf['ESFLocProjNumber'] + "</ESFLocProjNumber>";
		xml += "\n<ContOrg>" + sf['ContOrg'] + "</ContOrg>";
		xml += "\n<EmpOutcome>" + sf['EmpOutcome'] + "</EmpOutcome>";
		xml +="\n<CompStatus>" + sf['CompStatus'] + "</CompStatus>";
		if(sf['LearnActEndDate']!='' && sf['LearnActEndDate']!='dd/mm/yyyy')
			xml +="\n<LearnActEndDate>"+ sf['LearnActEndDate']  +"</LearnActEndDate>";
		xml +="\n<WithdrawReason>" + sf['WithdrawReason'] + "</WithdrawReason>";
		xml +="\n<Outcome>" + sf['Outcome'] + "</Outcome>";
		if(sf['AchDate']!='' && sf['AchDate']!='undefined' && sf['AchDate']!='dd/mm/yyyy')
			xml +="\n<AchDate>"+ sf['AchDate']  +"</AchDate>";
		xml +="\n<CredAch>"+ sf['CredAch']  +"</CredAch>";
		xml +="\n<OutGrade>" + sf['OutGrade'] + "</OutGrade>";
		xml +="\n<ActProgRoute>" + sf['ActProgRoute'] + "</ActProgRoute>";
		if(sf['SOF1']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" + sf['SOF1'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['SOF2']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" + sf['SOF2'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['FFI']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" + sf['FFI'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['ALN']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>ALN</LearnDelFAMType><LearnDelFAMCode>" + sf['ALN'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['ASN']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>ASN</LearnDelFAMType><LearnDelFAMCode>" + sf['ASN'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['NSA']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" + sf['NSA'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['EEF']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" + sf['EEF'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['LDM1']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM1'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['LDM2']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM2'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['LDM3']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM3'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['SPP']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SPP</LearnDelFAMType><LearnDelFAMCode>" + sf['SPP'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['SSP']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SSP</LearnDelFAMType><LearnDelFAMCode>" + sf['SSP'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['CVE']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>CVE</LearnDelFAMType><LearnDelFAMCode>" + sf['CVE'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['RES']!='undefined')
			xml +="\n<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>" + sf['RES'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
		if(sf['ProvSpecDelMonA']!='')
			xml +="\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonA'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
		if(sf['ProvSpecDelMonB']!='')
			xml +="\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonB'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
		if(sf['ProvSpecDelMonC']!='')
			xml +="\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>C</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonC'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
		if(sf['ProvSpecDelMonD']!='')
			xml +="\n<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>D</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonD'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";

		xml+="</LearningDelivery>";
	}
	xml += "</Learner>";

	xml = xml.replace('&', '&amp;');
	return xml;
}

function getSubsidiaryAimFields(aimNumber)
{
	var div = document.getElementById('sub' + aimNumber);
	if(div != null)
	{
		var fields = new Array();

		var elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i] != "radio"
				|| (elements[i].type == "radio" && elements[i].checked) )
			{
				fields[elements[i].name] = elements[i].value;
			}

			if(elements[i].type == "checkbox")
			{
				fields[elements[i].name] = elements[i].checked;
			}

		}

		elements = div.getElementsByTagName('select');
		for(var i = 0; i < elements.length; i++)
		{
			fields[elements[i].name] = elements[i].value;
		}

		return fields;
	}

	return null;
}

function getSubsidiaryAimElements(aimNumber)
{
	var div = document.getElementById('sub' + aimNumber);
	if(div != null)
	{
		var fields = new Array();

		var elements = div.getElementsByTagName('input');
		for(var i = 0; i < elements.length; i++)
		{
			if(elements[i] != "radio"
				|| (elements[i].type == "radio" && elements[i].checked) )
			{
				fields[elements[i].name] = elements[i];
			}
		}

		elements = div.getElementsByTagName('select');
		for(var i = 0; i < elements.length; i++)
		{
			fields[elements[i].name] = elements[i];
		}

		return fields;
	}

	return null;
}

function getDate(d)
{
	r = d.substr(7,4);
	if (r=='1900')
	{r='00000000';
		return r;}
	var m=d.substr(3,3)
	var mon = new Array("JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
	var mm = new Array("01","02","03","04","05","06","07","08","09","10","11","12");
	for (var a = 0; a<= mon.length ; a++)
		if(m==mon[a])
			r += mm[a];
	r += d.substr(0,2);
	return r;
}


function PDF()
{
	f = document.forms[1];
	f.xml.value = toXML();
	f.submit();
}

function TtGPDF()
{
	f = document.forms[2];
	f.xml.value = toXML();
	f.submit();
}

function TtGDCF()
{
	f = document.forms[3];
	f.xml.value = toXML();
	f.submit();
}



function save()
{
	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');


	// Submit form by AJAX (revised by Ian S-S 13th July)
	var postData = 'id=' + document.getElementById('LearnRefNumber').value
		+ '&xml=' + encodeURIComponent(toXML())
		//	+ '&submission_date=' + ''
		//+ '&L01=' + document.getElementById('L01').value
		//+ '&A09=' + document.getElementById('A09').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'"; ?>
		+ '&contract_id=' + <?php echo $contract_id; ?>
		+ '&tr_id=' + <?php echo $tr_id; ?>
		+ '&template=' + <?php echo $template; ?>;

	var client = ajaxRequest('do.php?_action=save_ilr_2012', postData);
	if(client != null)
	{

		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		var tags = report.getElementsByTagName('success');
		if(tags.length > 0)
		{
			alert("ILR Form saved!");
			// <button onclick="window.history.go(-1);">Cancel</button>
			window.history.back();
		}
	}
}


function validation()
{


	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');

	// Validate the main form text fields
	//if(validateForm(mainForm) == false)
	//{
	//	return false;
	//}

//	if(validate()==false)
//		return true;

	// Submit form by AJAX 
	var postData = 'id=' + document.getElementById('LearnRefNumber').value
		+ '&xml=' + encodeURIComponent(toXML())
//		+ '&submission_date=' + document.ilr.AA.value
//		+ '&L01=' + document.getElementById('L01').value
//		+ '&A09=' + document.getElementById('A09').value
		+ '&approve=' + document.getElementById('approve').checked
		+ '&active=' + document.getElementById('active').checked
		+ '&sub='     + <?php echo "'".$submission."'";?>
		+ '&contract_id='     + <?php echo $contract_id;?>
		+ '&tr_id='     + <?php echo $tr_id;?>;

	var client = ajaxRequest('do.php?_action=validate_ilr2012', postData);
	if(client != null)
	{

		// Check if the response is a success flag or an error report
		var xml = client.responseXML;
		var report = client.responseXML.documentElement;
		var tags = report.getElementsByTagName('success');
		if(tags.length > 0)
		{
			// If success flag, move on
			var cell = document.getElementById("report");
			if ( cell.hasChildNodes() )
			{
				while ( cell.childNodes.length >= 1 )
				{
					cell.removeChild( cell.firstChild );
				}
			}
			document.getElementById('report').style.display='none';
			alert("No errors! This form is valid");
			// window.location.replace('do.php?_action=view_ilrs&id=' + document.getElementById('L03').value);
			document.getElementById('approve').checked = true;
		}
		else
		{
			var cell = document.getElementById("report");
			if ( cell.hasChildNodes() )
			{
				while ( cell.childNodes.length >= 1 )
				{
					cell.removeChild( cell.firstChild );
				}
			}

			var x = report.getElementsByTagName('error');
			var repo = document.getElementById('report');
			var i=0;

			repo.innerHTML = "<p class='heading'> Validation Report </p>";

			for(i=0;i<x.length;i++)
			{
				er = document.createElement('p');
				er.innerHTML = htmlspecialchars(x[i].childNodes[0].nodeValue).replace(/([ALE]\d\d[a-z]*)/g, "<span class=\"fieldLink\" onclick=\"gotoField('$1');\"><b>$1</b></span>");
				repo.appendChild(er);
			}
			repo.style.display="Block";
		}
	}
}


function gotoField(f)
{
	document.getElementById(f).scrollIntoView();
	window.scrollBy(0,-15);
}

/**
 * Debug code
 */
function viewXML()
{
	var debug = document.getElementById('debug');
	debug.textContent = toXML();
}


/**
 * The ID field is often cut & paste from the NDAQ website, and unfortunately
 * contains white space, tabs and other gunk.
 */
function id_onchange(objID)
{
	objID.value = objID.value.replace(/\s/g, '');
}


function enableButtons()
{
<?php
if($max_submission == $submission) { ?>
	if(document.getElementById("b1") != null)
		document.getElementById("b1").disabled = false;
	<?php } ?>
	if(document.getElementById("b2") != null)
		document.getElementById("b2").disabled = false;
	if(document.getElementById("b3") != null)
		document.getElementById("b3").disabled = false;
	if(document.getElementById("b4") != null)
		document.getElementById("b4").disabled = false;
	if(document.getElementById("b5") != null)
		document.getElementById("b5").disabled = false;

<?php if(SOURCE_BLYTHE_VALLEY && DB_NAME=='am_ligauk') echo 'document.getElementById("b1").disabled = false;' ?>

<?php if($_SESSION['user']->username=='bfeighery' && DB_NAME=='am_pera') echo 'document.getElementById("b1").disabled = false;' ?>

}


//YAHOO.util.Event.onDOMReady(enableButtons);


</script>

<style type="text/css">

	.fieldLink
	{
		cursor: pointer;
		font-style: italic;
	}

</style>
<script type="text/javascript">
	function numbersonly(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;
		keychar = String.fromCharCode(key);

// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) )
			return true;

// numbers
		else if ((("0123456789").indexOf(keychar) > -1))
			return true;

// decimal point jump
		else if (dec && (keychar == "."))
		{
			myfield.form.elements[dec].focus();
			return false;
		}
		else
			return false;
	}



	function validLearnerReference(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;
		keychar = String.fromCharCode(key);

// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) )
			return true;

// numbers
		else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ").indexOf(keychar) > -1))
			return true;

// decimal point jump
		else
			return false;
	}

	function validName(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;
		keychar = String.fromCharCode(key);

// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) || (key==39))
			return true;

// numbers
		else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ- ").indexOf(keychar) > -1))
			return true;

// decimal point jump
		else
			return false;
	}

	function validAddress(myfield, e, dec)
	{
		var key;
		var keychar;

		if (window.event)
			key = window.event.keyCode;
		else if (e)
			key = e.which;
		else
			return true;
		keychar = String.fromCharCode(key);

// control keys
		if ((key==null) || (key==0) || (key==8) ||
			(key==9) || (key==13) || (key==27) || (key==39))
			return true;

// numbers
		else if ((("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ,-/.&![]+:;@").indexOf(keychar) > -1))
			return true;

// decimal point jump
		else
			return false;
	}

	function changeL03()
	{

		newL03 = prompt("Enter new L03/A03",'');
		oldL03 = document.getElementById('LearnRefNumber').value;

		var oldL03 = new RegExp(oldL03, "g");

		if(newL03.length>0)
		{
			xml = encodeURIComponent(toXML());
			xml = xml.replace(oldL03, newL03);

			var mainForm = document.forms[0];
			var canvas = document.getElementById('unitCanvas');


			submission = <?php echo "'".$submission."'"; ?>
				// Submit form by AJAX (revised by Ian S-S 13th July)
				postData = 'id=' + newL03
					+ '&xml=' + xml
					//	+ '&submission_date=' + document.ilr.AA.value
					+ '&L01=' + ''
					+ '&A09=' + ''
					+ '&approve=' + document.getElementById('approve').checked
					+ '&active=' + document.getElementById('active').checked
					+ '&sub='     + <?php echo "'".$submission."'"; ?>
					+ '&contract_id=' + <?php echo $contract_id; ?>
					+ '&tr_id=' + <?php echo $tr_id; ?>;


			var client = ajaxRequest('do.php?_action=save_ilr_2012', postData);
			if(client != null)
			{

				// Check if the response is a success flag or an error report
				var xml = client.responseXML;
				var report = client.responseXML.documentElement;

				var tags = report.getElementsByTagName('success');
				if(tags.length > 0)
				{
					alert("ILR Form saved!");
					var client = ajaxRequest('do.php?_action=change_tr_l03', postData);
					window.history.go(-1);

				}
			}
		}
	}

	function changeDates()
	{
		for(subaims = 1; subaims <= window.aims_counter; subaims++)
		{
			var div = document.getElementById('sub' + subaims);
			if(div != null)
			{
				var fields = new Array();

				var elements = div.getElementsByTagName('input');
				for(var i = 0; i < elements.length; i++)
				{
					if(elements[i] != "radio" || (elements[i].type == "radio" && elements[i].checked) )
					{
						if(elements[i].name=='LearnStartDate' || elements[i].name=='LearnPlanEndDate');
						elements[i].disabled = false;
					}
				}
			}
		}
		alert("You are allowed to ammend start dates and planned end dates now");
	}

</script>


</head>

<body onload="enableButtons();">
<div class="banner">
	<div class="Title">
		<?php if($template!=1) { ?>
		Individual Learner Record 2012/13
		<?php } else { ?>
		ILR Template
		<?php }  ?>
	</div>
	<div class="ButtonBar">
		<?php if($_SESSION['user']->isAdmin() || ($_SESSION['user']->type==8 && DB_NAME!='am_raytheon')){?>
		<button disabled id="b1" onclick="return save();">Save</button>
		<?php }

		if($template!=1) {
			?>

			<button disabled id="b2" onclick="return validation();">Validate</button>
			<button disabled id="b3" onclick="window.location.href='<?php echo $_SESSION['bc']->getPrevious();?>';">Close</button>
			<button disabled id="b4" onclick="PDF();">PDF</button>
			<button disabled id="b5" onclick="if(prompt('Password','')=='pscd2012')changeL03();">Change LRN</button>

			<?php } ?>

		<?php if($_SESSION['user']->isAdmin()) { ?>
		<button onclick="if(prompt('Password','')=='pscd2011')changeDates();">Change Dates</button>
		<button onclick="addAimFromMain();">Add Aim</button>
		<?php } ?>

		<?php
		if($is_approved==1)
			echo "<input type=checkbox id='approve' checked> Approve";
		else
			echo "<input type=checkbox id='approve'> Approve";

		if($is_active==1)
			echo "<input type=checkbox id='active' checked> Active";
		else
			echo "<input type=checkbox id='active'> Active";
		?>
	</div>
	<div class="ActionIconBar">
		<?php

		if($vo->learnerinformation->L08=="Y")
			echo "<input type=checkbox id='L08' checked> Deletion Flag";
		else
			echo "<input type=checkbox id='L08'> Deletion Flag";
		?>
	</div>
</div>

<?php $_SESSION['bc']->render($link); ?>

<div id='report' style="color: black;	border: 2px solid navy; -moz-border-radius: 5px; background-color: #E6E6FA;	padding: 10px; margin: 0px 10px 20px 10px; font-family: arial,sans-serif; font-size: 12px; display: None" >
	<p class='heading'> Validation Report </p>
</div>

<br><br>


<form class="niceforms" name="ilr" id="ilr" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<fieldset>
<legend style='width: 801px'>Individualised Learner Record 2012/13 - Learner Details Data Capture Form</legend>
<table border="0" cellspacing="4" cellpadding="4">
<col width="200"/><col />
<tr>
	<td class="fieldLabel_compulsory">UKPRN<br>
		<?php echo HTML::select('UKPRN', $UKPRN_dropdown, $con->ukprn, true, false, false); ?></td>

	<td class="fieldLabel_compulsory"> ULN  <br>
		<?php
		if($vo->ULN=='')
			$vo->ULN = '9999999999';
		echo "<input class='compulsory' type='text' value='" . $vo->ULN . "' style='' id='ULN' name='ULN' maxlength=10 size=10 onKeyPress='return numbersonly(this, event)'> </td>";
		?>
</tr>

<tr>
	<td class="fieldLabel_compulsory"> Learner reference number <br>
		<?php echo "<input class='compulsory' disabled type='text' value='" . $vo->LearnRefNumber . "' style='' id='LearnRefNumber' name='LearnRefNumber' maxlength=12 size=12 onKeyPress='return validLearnerReference(this, event)'> </td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_compulsory">Family name<br>
		<?php echo '<input class=compulsory type=text value="' . $vo->FamilyName . '" id="FamilyName" name="FamilyName" maxlength=20 size=30 onKeyPress="return validName(this, event)"> </td>'; ?>
	<td class="fieldLabel_compulsory">Given names<br>
		<?php echo "<input class='compulsory' type='text' value='" . htmlspecialchars((string)$vo->GivenNames) . "' id='GivenNames' name='GivenNames' maxlength=40 size=40 onKeyPress='return validName(this, event)'> </td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_compulsory">Date of birth <br>
		<?php
		if($vo->DateOfBirth!='00000000' && $vo->DateOfBirth!='' && $vo->DateOfBirth!='00/00/0000')
			echo HTML::datebox('DateOfBirth', Date::toShort($vo->DateOfBirth));
		else
			echo HTML::datebox('DateOfBirth','');
		?> </td>

	<?php
	if($funding_type!='ASL' && $funding_type!='ESF')
	{
		echo '<td class="fieldLabel_compulsory">Country of Domicile <br>';
		echo HTML::select('Domicile', $Domicile_dropdown, $vo->Domicile, true, true) . '</td>';
	}
	?>
</tr>

<?php
if($funding_type=='1618LR' || $funding_type=='ALR')
{
	echo '<tr><td class="fieldLabel_compulsory">Accommodation <br>';
	echo HTML::select('Accom', $Accom_dropdown, $vo->Accom, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Additional learning support cost<br>';
	echo "<input class='compulsory' type='text' value='" . $vo->ALSCost . "' id='ALSCost' name='ALSCost' maxlength=40 size=40 onKeyPress='return validName(this, event)'> </td></tr>";
}
?>


<tr>
	<td class="fieldLabel_compulsory"> Current address line 1 <br>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine1'); $add1 = (empty($xpath))?'':(string)$xpath[0]; echo "<input class='compulsory' type='text' value='" . $add1 . "' style='' id='AddLine1' name='AddLine1' maxlength=30 size=28 onKeyPress='return validAddress(this, event)'> </td>"; ?>
	<td class="fieldLabel_compulsory"> Current address line 2 <br>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine2'); $add2 = (empty($xpath))?'':(string)$xpath[0]; echo "<input class='compulsory' type='text' value='" . $add2 . "' style='' id='AddLine2' name='AddLine2' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_compulsory"> Current address line 3 <br>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine3'); $add3 = (empty($xpath))?'':(string)$xpath[0]; echo "<input class='compulsory' type='text' value='" . $add3 . "' style='' id='AddLine3' name='AddLine3' maxlength=30 size=30 onKeyPress='return validAddress(this, event)'> </td>"; ?>
	<td class="fieldLabel_compulsory"> Current address line 4 <br>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/PostAdd/AddLine4'); $add4 = (empty($xpath))?'':(string)$xpath[0]; echo "<input class='compulsory' type='text' value='" . $add4 . "' style='' id='AddLine4' name='AddLine4' maxlength=30 size=35 onKeyPress='return validAddress(this, event)'> </td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_optional"> Telephone number <br>
		<?php $xpath = $vo->xpath('/Learner/LearnerContact/TelNumber'); $tel = (empty($xpath))?'':$xpath[0]; echo "<input class='optional' type='text' value='" . $tel . "' style='' id='TelNumber' name='TelNumber' maxlength=15 size=15 onKeyPress='return numbersonly(this, event)'> </td>"; ?>
		<?php
		if($funding_type!="ASL")
		{
			echo '<td class="fieldLabel_compulsory">National insurance number <br>';
			echo "<input class='compulsory' type='text' value='" . $vo->NINumber . "' style='' id='NINumber' name='NINumber' maxlength=9 size=20> </td>";
		}
		?>
</tr>

<tr>
	<td class="fieldLabel_compulsory"> Current postcode <br>
		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='2']/PostCode"); $cp = (empty($xpath))?'':$xpath[0];  echo "<input class='compulsory' type='text' value='" . $cp . "' style='' id='CurrentPostcode' name='CurrentPostcode' maxlength=8 size=8> </td>"; ?>
	<td class="fieldLabel_compulsory">Postcode prior to enrolment<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='1' and LocType='2']/PostCode"); $ppe = (empty($xpath))?'':$xpath[0]; echo "<input class='compulsory' type='text' value='" . $ppe . "' style='background-color: white' id='PostcodePriorEnrolment' name='PostcodePriorEnrolment' maxlength=8 size=8> </td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_optional"> Email Address <br>
		<?php $xpath = $vo->xpath("/Learner/LearnerContact[ContType='2' and LocType='4']/Email"); $email = (empty($xpath))?'':$xpath[0];  echo "<input class='optional' type='text' value='" . $email . "' style='' id='Email' name='Email' maxlength=100 size=30></td>"; ?>
	<td valign="top" class="fieldLable_compulsory"> Sex (M or F)
		<?php
		if($vo->Sex=='M')
		{	$male = "checked"; $female = "";}
		else
		{	$female = "checked"; $male = "";}

		echo "<input type='Radio' name='Sex' value='M' " . $male . "> Male";
		echo "<input type='Radio' name='Sex' value='F' " . $female . "> Female</td>";

		?>
</tr>

<tr>
	<td class="fieldLabel_compulsory"> Ethnicity <br>
		<?php echo HTML::select('Ethnicity', $Ethnicity_dropdown, $vo->Ethnicity, true, true); ?></td>

	<?php
	if($funding_type!="ASL")
	{
		echo '<td class="fieldLabel_compulsory">Prior attainment <br>';
		echo HTML::select('PriorAttain', $PriorAttain_dropdown, $vo->PriorAttain, true, true) . '</td>';
	}
	?>
</tr>
<tr>
	<td colspan=4 align="center" valign="middle"> <b> <ul> LLDD & Health Problems and Learner Funding and Monitoring </ul> </b> </td>
</tr>
<tr>
	<td class="fieldLabel_compulsory">Do you consider yourself to have a long term disability,<br> health problem or any learning difficulties<br>
		<?php echo HTML::select('LLDDHealthProb', $LLDDHealthProb_dropdown, $vo->LLDDHealthProb, true, true); ?></td>
</tr>
<tr>
	<td class="fieldLabel_compulsory">DS <br>
		<?php $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode"); $ds = (empty($xpath))?'':$xpath[0]; echo HTML::select('DS', $LLDDDS_dropdown, $ds, true, true); ?></td>
	<td class="fieldLabel_compulsory">LD<br>
		<?php $xpath = $vo->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode"); $ld = (empty($xpath))?'':$xpath[0]; echo HTML::select('LD', $LLDDLD_dropdown, $ld, true, true); ?></td>
</tr>

<?php
if($funding_type=='1618LR' || $funding_type=='ALR')
{
	echo '<tr><td class="fieldLabel_compulsory">Eligibility for 16-18 funding entitlement (EFE)<br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='EFE']/LearnFAMCode"); $efe = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('EFE', $EFE_dropdown, $efe, true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory">Disadvantage uplift factor<br>';
	echo "<input class='compulsory' type='text' value='" . $vo->DisUpFact . "' style='background-color: white' id='DisUpFact' name='DisUpFact' maxlength=8 size=8> </td></tr>";
}
if($funding_type=='1618LR' || $funding_type=='ALR')
{
	echo '<tr><td class="fieldLabel_compulsory">Learning difficulty assessment <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LDA']/LearnFAMCode"); $lda = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('LDA', $LDA_dropdown, $lda, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Additional learning support <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='ALS']/LearnFAMCode"); $als = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('ALS', $ALS_dropdown, $als, true, true) . '</td></tr>';
}
if($funding_type=='1618LR' || $funding_type=='ALR')
{
	echo '<tr><td class="fieldLabel_compulsory">Disadvantage uplift eligibility <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DUE']/LearnFAMCode"); $due = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('DUE', $DUE_dropdown, $due, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Discretionary support funds <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DSF']/LearnFAMCode"); $dsf = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('DSF1', $DSF_dropdown, $dsf, true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory">Discretionary support funds <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DSF']/LearnFAMCode"); $dsf = (empty($xpath[1]))?'':(string)$xpath[1]; echo HTML::select('DSF2', $DSF_dropdown, $dsf, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Discretionary support funds <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DSF']/LearnFAMCode"); $dsf = (empty($xpath[2]))?'':(string)$xpath[2]; echo HTML::select('DSF3', $DSF_dropdown, $dsf, true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory">Discretionary support funds <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DSF']/LearnFAMCode"); $dsf = (empty($xpath[3]))?'':(string)$xpath[3]; echo HTML::select('DSF4', $DSF_dropdown, $dsf, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Discretionary support funds <br>';
	$xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='DSF']/LearnFAMCode"); $dsf = (empty($xpath[4]))?'':(string)$xpath[4]; echo HTML::select('DSF5', $DSF_dropdown, $dsf, true, true) . '</td></tr>';
}
?>


<tr>
	<td class="fieldLabel_compulsory">LSR<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr1 = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('LSR1', $LSR_dropdown, $lsr1, true, true); ?></td>
	<td class="fieldLabel_compulsory">LSR<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr2 = (empty($xpath[1]))?'':(string)$xpath[1]; echo HTML::select('LSR2', $LSR_dropdown, $lsr2, true, true); ?></td>
</tr>

<tr>
	<td class="fieldLabel_compulsory">LSR<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr3 = (empty($xpath[2]))?'':(string)$xpath[2]; echo HTML::select('LSR3', $LSR_dropdown, $lsr3, true, true); ?></td>
	<td class="fieldLabel_compulsory">LSR<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr4 = (empty($xpath[3]))?'':(string)$xpath[3]; echo HTML::select('LSR4', $LSR_dropdown, $lsr4, true, true); ?></td>
</tr>
<tr>
	<td class="fieldLabel_compulsory">NLM<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm1 = (empty($xpath[0]))?'':(string)$xpath[0]; echo HTML::select('NLM1', $NLM_dropdown, $nlm1, true, true); ?></td>
	<td class="fieldLabel_compulsory">NLM<br>
		<?php $xpath = $vo->xpath("/Learner/LearnerFAM[LearnFAMType='NLM']/LearnFAMCode"); $nlm2 = (empty($xpath[1]))?'':(string)$xpath[1]; echo HTML::select('NLM2', $NLM_dropdown, $nlm2, true, true); ?></td>
</tr>

<tr>
	<td colspan=2><b>Tick any of the following boxes if you do not wish to be contacted:</b></td>
</tr>

<tr>
	<?php
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='1']/ContPrefCode"));
	$rui1 = "" . (empty($xpath))?'':$xpath[0];
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='2']/ContPrefCode"));
	$rui2 = "" . (empty($xpath))?'':$xpath[0];
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='RUI' and ContPrefCode='3']/ContPrefCode"));
	$rui3 = "" . (empty($xpath))?'':$xpath[0];

	if( ($rui3=='3') || ($rui1=='1' && $rui2=='2'))
	{
		echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" checked name="RUI1"> </td>';
		echo '<td>For surveys and research &nbsp;<input type="checkbox" checked name="RUI2"> </input> </td>';
	}
	else
	{
		if($rui1=='1')
		{
			echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" checked name="RUI1"> </td>';
			echo '<td>For surveys and research &nbsp;<input type="checkbox" name="RUI2"> </input> </td>';
		}
		elseif($rui2=='2')
		{
			echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" name="RUI1"> </td>';
			echo '<td>For surveys and research &nbsp;<input type="checkbox" checked name="RUI2"> </input> </td>';
		}
		else
		{
			echo '<td valign="top">About courses or learning opportunities &nbsp;<input type="checkbox" name="RUI1"> </td>';
			echo '<td>For surveys and research &nbsp;<input type="checkbox" name="RUI2"> </input> </td>';
		}
	}
	?>
</tr>

<tr colspan=2>
	<?php
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='1']/ContPrefCode"));
	$pmc1 = (empty($xpath))?'':$xpath[0];
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='2']/ContPrefCode"));
	$pmc2 = (empty($xpath))?'':$xpath[0];
	$xpath = ($vo->xpath("/Learner/ContactPreference[ContPrefType='PMC' and ContPrefCode='3']/ContPrefCode"));
	$pmc3 = (empty($xpath))?'':$xpath[0];

	if($pmc1=='1')
		echo '<td valign="top"> By post&nbsp;<input type="checkbox" checked name="PMC1"> </input>';
	else
		echo '<td valign="top"> By post&nbsp;<input type="checkbox" name="PMC1"> </input>';

	if($pmc2=='2')
		echo 'By phone&nbsp;<input type="checkbox" checked name="PMC2"> </input>';
	else
		echo 'By phone&nbsp;<input type="checkbox" name="PMC2"> </input>';

	if($pmc3=='3')
		echo 'By e-mail&nbsp;<input type="checkbox" checked name="PMC3"> </input> </td>';
	else
		echo 'By e-mail&nbsp;<input type="checkbox" name="PMC3"> </input> </td>';
	?>
</tr>

<tr>
	<td colspan=4 align="center" valign="middle"> <b> <ul> Single Individualised Learner Record 2012/13 - Learner Details Data Capture Form - Employment and Monitoring Information </ul> </b> </td>
</tr>

<?php
$index = 0;
$SEI_dropdown = array(array('1','1 Learner is self employed'));
$EII_dropdown = array(array('1','1 Learner is employed for 16 hours or more per week'), array('2','2 Learner is employed for less than 16 hours per week'));
$LOU_dropdown = array(array('1','1 Learner has been unemployed for less than 6 months'), array('2','2 Learner has been unemployed for 6-11 months'), array('3','3 Learner has been unemployed for 12-23 months'),array('4','4 Learner has been unemployed for 24-35 months'), array('5','5 Learner has been unemployed for over 36 months'));
$BSI_dropdown = array(array('1','1 Learner is in receipt of JSA'), array('2','2 Learner is in receipt of ESA WRAG'), array('3','3 Learner is in receipt of another state benefit'), array('4','4 Learner is in receipt of Universal Credit'), array('5','5 Unassigned'), array('6','6 Unassigned'), array('7','7 Unassigned'), array('8','8 Unassigned'), array('9','9 Unassigned'), array('10','10 Unassigned'));
$PEI_dropdown = array(array('1','1 Learner was in full time education or training prior to enrolment'));
$RON_dropdown = array(array('1','1 Learner is aged 14-15 and is at risk of becoming NEET'));
foreach($vo->LearnerEmploymentStatus as $empstatus)
{
	echo '<tr><td colspan=4 align="center" valign="middle"> <b> <ul> Employment Status </ul> </b> </td></tr>';
	$index++;
	if($index=='1')
		echo '<tr><td class="fieldLabel_optional"> Prior to enrolment Learning Employment Status <br>';
	else
		echo '<tr><td class="fieldLabel_optional"> Updated Employment Status <br>';
	$id = "EmpStat" . $index;
	echo HTML::select($id, $EmpStat_dropdown, $empstatus->EmpStat, true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Date employment status applies <br>';
	$id = "DateEmpStatApp" . $index;
	echo HTML::datebox($id,Date::toShort($empstatus->DateEmpStatApp), true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Employer ID <br>';
	echo "<input class='compulsory' type='text' value='" . $empstatus->EmpId . "' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30></td>";
	echo '<td class="fieldLabel_compulsory"> Workplace Location Postcode <br>';
	echo "<input class='compulsory' type='text' value='" . $empstatus->WorkLocPostCode . "' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=8></td>";
	echo '<tr><td class="fieldLabel_compulsory"> Employment status monitoring type and codes <br> </td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Self Employment Indicator (SEI) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='SEI']/ESMCode");
	$sei = (empty($xpath[0]))?'':$xpath[0];
	$id = "SEI".$index;
	echo HTML::select($id, $SEI_dropdown, $sei, true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Employment intensity indicator (EII) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
	$eii = (empty($xpath[0]))?'':$xpath[0];
	$id = "EII" . $index;
	echo HTML::select($id, $EII_dropdown, $eii, true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Length of unemployment (LOU) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='LOU']/ESMCode");
	$lou = (empty($xpath[0]))?'':$xpath[0];
	$id = "LOU" . $index;
	echo HTML::select($id, $LOU_dropdown, $lou, true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Benefit status indicator (BSI) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
	$bsi = (empty($xpath[0]))?'':$xpath[0];
	$id = "BSI" . $index;
	echo HTML::select($id, $BSI_dropdown, $bsi, true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Previous education indicator (PEI) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='PEI']/ESMCode");
	$pei = (empty($xpath[0]))?'':$xpath[0];
	$id = "PEI" . $index;
	echo HTML::select($id, $PEI_dropdown, $pei, true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Risk of NEET (RON) <br>';
	$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='RON']/ESMCode");
	$ron = (empty($xpath[0]))?'':$xpath[0];
	$id = "RON" . $index;
	echo HTML::select($id, $RON_dropdown, $ron, true, true) . '</td></tr>';

}
if($index==0)
{
	echo '<tr><td colspan=4 align="center" valign="middle"> <b> <ul> Employment Status </ul> </b> </td></tr>';
	$index++;
	echo '<tr><td class="fieldLabel_optional"> Prior to enrolment Learning Employment Status <br>';
	$id = "EmpStat" . $index;
	echo HTML::select($id, $EmpStat_dropdown, '', true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Date employment status applies <br>';
	$id = "DateEmpStatApp" . $index;
	echo HTML::datebox($id,'', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Employer ID <br>';
	echo "<input class='compulsory' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30></td>";
	echo '<td class="fieldLabel_compulsory"> Workplace Location Postcode <br>';
	echo "<input class='compulsory' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=8></td>";
	echo '<tr><td class="fieldLabel_compulsory"> Employment status monitoring type and codes <br> </td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Self Employment Indicator (SEI) <br>';
	$id = "SEI".$index;
	echo HTML::select($id, $SEI_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Employment intensity indicator (EII) <br>';
	$id = "EII" . $index;
	echo HTML::select($id, $EII_dropdown, '', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Length of unemployment (LOU) <br>';
	$id = "LOU" . $index;
	echo HTML::select($id, $LOU_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Benefit status indicator (BSI) <br>';
	$id = "BSI" . $index;
	echo HTML::select($id, $BSI_dropdown, '', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Previous education indicator (PEI) <br>';
	$id = "PEI" . $index;
	echo HTML::select($id, $PEI_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Risk of NEET (RON) <br>';
	$id = "RON" . $index;
	echo HTML::select($id, $RON_dropdown, '', true, true) . '</td></tr>';
}
else
{
	echo '<tr><td colspan=4 align="center" valign="middle"> <b> <ul> Employment Status </ul> </b> </td></tr>';
	$index++;
	echo '<tr><td class="fieldLabel_optional"> Updated Learning Employment Status <br>';
	$id = "EmpStat" . $index;
	echo HTML::select($id, $EmpStat_dropdown, '', true, true) . '</td>';
	echo '<td class="fieldLabel_compulsory">Date employment status applies <br>';
	$id = "DateEmpStatApp" . $index;
	echo HTML::datebox($id,'', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Employer ID <br>';
	echo "<input class='compulsory' type='text' value='' style='' id='EmpId$index' name='EmpId$index' maxlength=30 size=30></td>";
	echo '<td class="fieldLabel_compulsory"> Workplace Location Postcode <br>';
	echo "<input class='compulsory' type='text' value='' style='background-color: white' id='WorkLocPostCode$index' name='WorkLocPostCode$index' maxlength=8 size=8></td>";
	echo '<tr><td class="fieldLabel_compulsory"> Employment status monitoring type and codes <br> </td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Self Employment Indicator (SEI) <br>';
	$id = "SEI".$index;
	echo HTML::select($id, $SEI_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Employment intensity indicator (EII) <br>';
	$id = "EII" . $index;
	echo HTML::select($id, $EII_dropdown, '', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Length of unemployment (LOU) <br>';
	$id = "LOU" . $index;
	echo HTML::select($id, $LOU_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Benefit status indicator (BSI) <br>';
	$id = "BSI" . $index;
	echo HTML::select($id, $BSI_dropdown, '', true, true) . '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Previous education indicator (PEI) <br>';
	$id = "PEI" . $index;
	echo HTML::select($id, $PEI_dropdown, '', true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Risk of NEET (RON) <br>';
	$id = "RON" . $index;
	echo HTML::select($id, $RON_dropdown, '', true, true) . '</td></tr>';
}
?>

<tr>
	<td class="fieldLabel_compulsory"> Provider Specified Monitoring Information <br> </td>
</tr>
<tr>
	<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>
		<?php $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon"); $ProvSpecLearnMon1 = (empty($xpath[0]))?'':$xpath[0]; echo "<input class='optional' type='text' value='" . $ProvSpecLearnMon1 . "' style='' id='ProvSpecLearnMonA' name='ProvSpecLearnMonA' maxlength=12 size=30></td>"; ?>
	<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>
		<?php $xpath = $vo->xpath("/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon"); $ProvSpecLearnMon2 = (empty($xpath[0]))?'':$xpath[0]; echo "<input class='optional' type='text' value='" . $ProvSpecLearnMon2 . "' style='' id='ProvSpecLearnMonB' name='ProvSpecLearnMonB' maxlength=12 size=30></td>"; ?>
</tr>

<tr>
	<td class="fieldLabel_compulsory">Destination <br>
		<?php echo HTML::select('Dest', $Dest_dropdown, $vo->Dest, true, true); ?></td>
</tr>

</table>
</fieldset>
<?php
echo "<div id='aimsContainer'>";
$aims = 0;foreach($vo->LearningDelivery as $delivery){$aims++;}
$a = 0;
foreach($vo->LearningDelivery as $delivery)
{
	$a++;
	echo "<script type='text/javascript'> window.aims_counter=" . $aims . "</script>";
	echo "<script type='text/javascript'>";
	echo "var container = document.getElementById('aimsContainer');";
	echo "</script>";
	echo "<div id='sub" . $a . "' class='Unit3'>";
	echo "<fieldset>";
	echo "<legend style='width: 801px;'> Individualised Learner Record 2012-13 - Learning Aim <button id='butt".$a."' onClick='deleteAim(this);'> X </button></legend>";

	echo '<table><tr>';
	echo '<td colspan=4 valign="middle"> <b> Learning Start Information</b> </td></tr>';
	echo '<tr><td colspan=4>&nbsp;</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Aim Type<br>';
	echo "<input class='compulsory' type='text' value='" . $delivery->AimType . "' style='' id='AimType' name='AimType' maxlength=8 size=8></td>";
	echo '<td class="fieldLabel_compulsory"> Learning aim reference <br>';
	echo "<input class='compulsory' type='text' value='" . $delivery->LearnAimRef . "' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8></td></tr>";

	echo '<tr><td class="fieldLabel_compulsory"> Learning start date <br>';
	echo HTML::datebox('LearnStartDate', $delivery->LearnStartDate, true, $how_many);
	echo '</td><td class="fieldLabel_compulsory"> Learing planned end date <br>';
	echo HTML::datebox('LearnPlanEndDate', $delivery->LearnPlanEndDate, true, $how_many);
	echo '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Funding Model <br>';
	if($delivery->FundModel=='')
		echo HTML::select('FundModel', $FundModel_dropdown, "45", true, true) . '</td>';
	else
		echo HTML::select('FundModel', $FundModel_dropdown, $delivery->FundModel, true, true) . '</td>';

	echo '<td class="fieldLabel_compulsory"> Contracting organisation <br>';
	echo HTML::select('ContOrg', $ContOrg_dropdown, $delivery->ContOrg, true, true) . '</td>';

	echo '</td></tr>';

	if( ($delivery->FundModel=="21" || $delivery->FundModel=="22" || $delivery->FundModel=="10") && $delivery->LearnAimRef!='ZPROG001')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Guided learning hours <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->GLH . "' style='' id='GLH' name='GLH' maxlength=8 size=8></td></tr>";
	}

	if($delivery->LearnAimRef!='ZPROG001')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Planned credit value <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->PlanCredVal . "' style='' id='PlanCredVal' name='PlanCredVal' maxlength=8 size=8></td></tr>";
	}

	if($delivery->FundModel!="10")
	{
		echo '<tr><td class="fieldLabel_compulsory"> Programme type <br>';
		echo HTML::select('ProgType', $ProgType_dropdown, $delivery->ProgType, true, true).'</td>';
	}

	if(($delivery->FundModel=="45" || $delivery->FundModel=="70" || $delivery->FundModel=="99" || $delivery->FundModel=="21") && $delivery->ProgType!='99')
	{
		echo '<td class="fieldLabel_compulsory"> Framework code <br>';
		if($delivery->ProgType=='2')
			echo HTML::select('FworkCode', $FworkCode2_dropdown, $delivery->FworkCode, true, true).'</td></tr>';
		elseif($delivery->ProgType=='3')
			echo HTML::select('FworkCode', $FworkCode3_dropdown, $delivery->FworkCode, true, true).'</td></tr>';
		else
			echo HTML::select('FworkCode', $FworkCode_dropdown, $delivery->FworkCode, true, true).'</td></tr>';
	}

	if($delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Main Delivery Method <br>';
		echo HTML::select('MainDelMeth', $MainDelMeth_dropdown, $delivery->MainDelMeth, true, true).'</td>';
		echo '<td class="fieldLabel_compulsory"> Proportion of funding remaining <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->PropFundRemain . "' style='' id='PropFundRemain' name='PropFundRemain' maxlength=8 size=8></td></tr>";
	}

	if(($delivery->FundModel=="21" || $delivery->FundModel=="22") && ($delivery->AimType!='1' || $delivery->AimType!='4'))
	{
		echo '<tr><td class="fieldLabel_compulsory"> Delivery Mode <br>';
		echo HTML::select('DelMode', $DelMode_dropdown, $delivery->DelMode, true, true).'</td>';
		echo '<td class="fieldLabel_compulsory"> Distance Learning SLN <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->DistLearnSLN . "' style='' id='DistLearnSLN' name='DistLearnSLN' maxlength=8 size=8></td></tr>";
	}

	if(($delivery->FundModel=="45" || $delivery->FundModel=="70" || $delivery->FundModel=="99") && $delivery->ProgType!='99')
	{
		if($delivery->FworkCode=='')
			$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks order by Framework_Code;", DAO::FETCH_NUM);
		else
			$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks where frameworks.framework_code = '$delivery->FworkCode' and frameworks.FRAMEWORK_TYPE_CODE='$delivery->ProgType' order by Framework_Code;", DAO::FETCH_NUM);

		echo '<tr><td class="fieldLabel_compulsory"> Apprenticeship pathway <br>';
		echo HTML::select('PwayCode', $PwayCode_dropdown, $delivery->PwayCode, true, true).'</td>';
	}

	if($delivery->FundModel=="45" && $delivery->LearnAimRef=='ZPROG001')
	{
		echo '<td class="fieldLabel_compulsory"> Programme entry route <br>';
		echo HTML::select('ProgEntRoute', $ProgEntRoute_dropdown, $delivery->ProgEntRoute, true, true) . '</td></tr>';
	}

	if( ($delivery->FundModel=="21" || $delivery->FundModel=="22" || $delivery->FundModel=="45") && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Partner UKPRN <br>';
		echo HTML::select('PartnerUKPRN', $PartnerUKPRN_dropdown, $delivery->PartnerUKPRN, true, true) . '</td></tr>';
	}

	if( ($delivery->FundModel=="10" || $delivery->FundModel=="22") && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Tuition fees received year to date <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->FeeYTD . "' style='' id='FeeYTD' name='FeeYTD' maxlength=8 size=8></td></tr>";
	}

	if($delivery->FundModel=="22" && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Source of tuition fees <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->FeeSource . "' style='' id='FeeSource' name='FeeSource' maxlength=8 size=8></td></tr>";
	}

	if( ($delivery->FundModel=="21" || $delivery->FundModel=="22") && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Employer role <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->EmpRole . "' style='' id='EmpRole' name='EmpRole' maxlength=8 size=8></td></tr>";
	}

	if( ($delivery->FundModel=="45" || $delivery->FundModel=="22" || $delivery->FundModel=="70") && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Employment outcome <br>';
		echo HTML::select('EmpOutcome', $EmpOutcome_dropdown, $delivery->EmpOutcome, true, true) . '</td></tr>';
	}

	if($delivery->FundModel=="70" && $delivery->LearnAimRef!='ZPROG001')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Project dossier number <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->ESFProjDosNumber . "' style='' id='ESFProjDosNumber' name='ESFProjDosNumber' maxlength=9 size=9></td>";
		echo '<td class="fieldLabel_compulsory"> Local project number <br>';
		echo "<input class='compulsory' type='text' value='" . $delivery->ESFLocProjNumber . "' style='' id='ESFLocProjNumber' name='ESFLocProjNumber' maxlength=8 size=8></td></tr>";
	}

	echo '<tr><td class="fieldLabel_compulsory"> Delivery location postcode <br>';
	echo "<input class='compulsory' type='text' value='" . $delivery->DelLocPostCode . "' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8></td></tr>";

	echo '<tr><td class="fieldLabel_compulsory">Learning Delivery Funding and Monitoring Information <br> </td></tr>';

	$sof = 0;
	echo '<tr>';
	foreach($delivery->LearningDeliveryFAM as $ldf)
	{
		if($ldf->LearnDelFAMType=='SOF')
		{
			$sof++;
			echo '<td class="fieldLabel_compulsory"> Source of funding (SOF) <br>';
			echo HTML::select(('SOF'.$sof), $SOF_dropdown, $ldf->LearnDelFAMCode, true, true) . '</td>';
		}
	}
	if($sof==1)
	{
		echo '<td class="fieldLabel_compulsory"> Source of funding (SOF) <br>';
		echo HTML::select('SOF2', $SOF_dropdown, '', true, true) . '</td>';
	}
	echo '</tr>';

	if($sof==0)
	{
		echo '<tr>';
		echo '<td class="fieldLabel_compulsory"> Source of funding (SOF) <br>';
		echo HTML::select(('SOF'.$sof), $SOF_dropdown, '', true, true) . '</td>';
		echo '<td class="fieldLabel_compulsory"> Source of funding (SOF) <br>';
		echo HTML::select('SOF2', $SOF_dropdown, '', true, true) . '</td>';
		echo '</tr>';
	}

	echo '<tr><td class="fieldLabel_compulsory"> Full or co-funding indicator (FFI) <br>';
	$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
	echo HTML::select('FFI', $FFI_dropdown, $ffi, true, true) . '</td></tr>';

	if($delivery->FundModel=='10')
	{
		echo '<tr><td class="fieldLabel_compulsory"> ASL provision type <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode"); $asl = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('ASL', $ASL_dropdown, $asl, true, true) . '</td></tr>';
	}

	if(($delivery->FundModel=='21' || $delivery->FundModel=='22') && $delivery->AimType!='1')
	{
		echo '<tr><td class="fieldLabel_compulsory"> Re-take indicator <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RET']/LearnDelFAMCode"); $ret = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('RET', $RET_dropdown, $ret, true, true) . '</td></tr>';
	}

	if($delivery->AimType=="1" || $delivery->AimType=="4")
	{
		echo '<tr><td class="fieldLabel_optional"> National Skills Academy (NSA) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='NSA']/LearnDelFAMCode"); $nsa = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('NSA', $NSA_dropdown, $nsa, true, false) . '</td>';
	}

	if($delivery->AimType=="1" || $delivery->AimType=="4")
	{
		$ldm=0;
		foreach($delivery->LearningDeliveryFAM as $ldf)
		{
			if($ldf->LearnDelFAMType=='LDM')
			{
				$ldm++;
				echo '<td class="fieldLabel_compulsory"> Learning delivery monitoring (LDM) <br>';
				echo HTML::select(('LDM'.$ldm), $LDM_dropdown, $ldf->LearnDelFAMCode, true, true) . '</td>';
				if($ldm==1)
				{
					echo '</tr><tr>';
				}
			}
		}
		for($ldm++; $ldm <=3 ; $ldm++)
		{
			if($ldm==2)
			{
				echo '</tr><tr>';
			}
			echo '<td class="fieldLabel_compulsory"> Learning delivery monitoring (LDM) <br>';
			echo HTML::select(('LDM'.$ldm), $LDM_dropdown, "", true, true) . '</td>';
		}
		echo '</tr>';
	}

	if($delivery->AimType=="1")
	{
		echo '<tr><td class="fieldLabel_optional"> Eligibility for enhanced funding (EEF) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='EEF']/LearnDelFAMCode"); $eef = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('EEF', $EEF_dropdown, $eef, true, false) . '</td>';
	}

	echo '<td class="fieldLabel_optional"> Restart indicator (RES) <br>';
	$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode"); $res = (empty($xpath[0]))?'':$xpath[0];
	echo HTML::select('RES', $RES_dropdown, $res, true, false) . '</td></tr>';

	if($delivery->AimType=="1" || $delivery->AimType=="4")
	{
		echo '<tr><td class="fieldLabel_optional"> Sector strategy pilots (SSP) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SSP']/LearnDelFAMCode"); $ssp = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('SSP', $SSP_dropdown, $ssp, true, false) . '</td>';
		echo '<td class="fieldLabel_optional"> Special projects and pilots (SPP) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='SPP']/LearnDelFAMCode"); $spp = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('SPP', $SPP_dropdown, $spp, true, false) . '</td></tr>';
		echo '<tr><td class="fieldLabel_optional"> CoVE indicator (CVE) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='CVE']/LearnDelFAMCode"); $cve = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('CVE', $CVE_dropdown, $cve, true, false) . '</td></tr>';
	}

	if($delivery->AimType!="1" && $delivery->FundModel=="45")
	{
		echo '<tr><td class="fieldLabel_optional"> Additional learning need (ALN) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ALN']/LearnDelFAMCode"); $aln = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('ALN', $ALN_dropdown, $aln, true, false) . '</td>';
		echo '<td class="fieldLabel_optional"> Additional social need (ASN) <br>';
		$xpath = $delivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASN']/LearnDelFAMCode"); $asn = (empty($xpath[0]))?'':$xpath[0];
		echo HTML::select('ASN', $ASN_dropdown, $asn, true, false) . '</td></tr>';
	}



	echo '<tr><td class="fieldLabel_compulsory">Provider Specified Delivery Monitoring Information<br> </td></tr>';

	echo '<tr><td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
	$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='A']/ProvSpecDelMon");
	$ProvSpecDelMonA = (empty($xpath[0]))?'':$xpath[0];
	echo "<input class='optional' type='text' value='" . $ProvSpecDelMonA . "' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30></td>";
	echo '<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
	$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='B']/ProvSpecDelMon");
	$ProvSpecDelMonB = (empty($xpath[0]))?'':$xpath[0];
	echo "<input class='optional' type='text' value='" . $ProvSpecDelMonB . "' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30></td></tr>";

	echo '<tr><td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
	$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='C']/ProvSpecDelMon");
	$ProvSpecDelMonC = (empty($xpath[0]))?'':$xpath[0];
	echo "<input class='optional' type='text' value='" . $ProvSpecDelMonC . "' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30></td>";
	echo '<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
	$xpath = $delivery->xpath("./ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur='D']/ProvSpecDelMon");
	$ProvSpecDelMonD = (empty($xpath[0]))?'':$xpath[0];
	echo "<input class='optional' type='text' value='" . $ProvSpecDelMonD . "' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30></td></tr>";

	echo '<tr><td class="fieldLabel_compulsory">Learning End Information<br> </td></tr>';

	echo '<tr><td class="fieldLabel_optional"> Learning actual end date <br>';
	echo HTML::datebox('LearnActEndDate', $delivery->LearnActEndDate);
	echo '</td><td class="fieldLabel_compulsory"> Completion status <br>';
	echo HTML::select('CompStatus', $CompStatus_dropdown, $delivery->CompStatus, false, true);
	echo '</td></tr>';

	echo '<tr><td class="fieldLabel_compulsory"> Withdrawal reason <br>';
	echo HTML::select('WithdrawReason', $WithdrawReason_dropdown, $delivery->WithdrawReason, true, true);
	echo '</td><td class="fieldLabel_compulsory"> Outcome indicator <br>';
	echo HTML::select('Outcome', $Outcome_dropdown, $delivery->Outcome, true, true) . '</td></tr>';

//	if($delivery->FundModel=="45")
//	{
	echo '<tr><td class="fieldLabel_optional"> Achievement date <br>';
	echo HTML::datebox('AchDate', $delivery->AchDate);
//	}

	echo '</td><td class="fieldLabel_compulsory"> Actual progression route <br>';
	echo HTML::select('ActProgRoute', $ActProgRoute_dropdown, $delivery->ActProgRoute, true, true);
	echo '</td></tr>';

	if($delivery->AimType!="1")
	{
		echo '<tr><td class="fieldLabel_optional"> Credits achieved <br>';
		echo "<input class='optional' type='text' value='" . $delivery->CredAch . "' style='' id='CredAch' name='CredAch' maxlength=12 size=30></td>";
		echo '</td><td class="fieldLabel_compulsory"> Outcome Grade <br>';
		echo HTML::select('OutGrade', $OutGrade_dropdown, $delivery->OutGrade, true, true);
		echo '</td></tr>';
	}



	echo '</table></fieldset></div>';
	echo "<script type='text/javascript'>";
	echo "container.appendChild(document.getElementById('sub'+" . $a . "));";
	echo "</script>";
}
?>
</div>
<div id="sub" style="Display: None; " class='Unit' >
	<fieldset>
		<legend style='width: 801px; color: red'>Individualised Learner Record 2012-13 - Learning Aim (New) <button id='butt' onClick='deleteAim(this);'> X </button></legend>
		<table>
			<tr>
				<td colspan=4 align="left" valign="middle"> <b> <ul> Learning Start Information  </ul> </b> </td>
			</tr>
			<?php
			echo '<tr><td class="fieldLabel_compulsory"> Aim Type<br>';
			echo "<input class='compulsory' type='text' value='' style='' id='AimType' name='AimType' maxlength=8 size=8></td>";
			echo '<td class="fieldLabel_compulsory"> Learning aim reference <br>';
			echo "<input class='compulsory' type='text' value='' style='' id='LearnAimRef' name='LearnAimRef' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Learning start date <br>';
			echo HTML::datebox('LearnStartDate', $vo->LearningDelivery->LearnStartDate, true);
			echo '</td><td class="fieldLabel_compulsory"> Learing planned end date <br>';
			echo HTML::datebox('LearnPlanEndDate', $vo->LearningDelivery->LearnPlanEndDate, true);
			echo '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> Funding Model <br>';
			echo HTML::select('FundModel', $FundModel_dropdown, $vo->LearningDelivery->FundModel, true, true) . '</td>';
			echo '<td class="fieldLabel_compulsory"> Contracting organisation <br>';
			echo HTML::select('ContOrg', $ContOrg_dropdown, isset($delivery->ContOrg)?$delivery->ContOrg:'', true, true);
			echo '</td></tr>';

			if( ($vo->LearningDelivery->FundModel=="21" || $vo->LearningDelivery->FundModel=="22" || $vo->LearningDelivery->FundModel=="10") && $vo->LearningDelivery->LearnAimRef!='ZPROG001')
			{
				echo '<tr><td class="fieldLabel_compulsory"> Guided learning hours <br>';
				echo "<input class='compulsory' type='text' value='' style='' id='GLH' name='GLH' maxlength=8 size=8></td></tr>";
			}

			echo '<tr><td class="fieldLabel_compulsory"> Planned credit value <br>';
			echo "<input class='compulsory' type='text' value='' style='' id='PlanCredVal' name='PlanCredVal' maxlength=8 size=8></td></tr>";

			if($vo->LearningDelivery->FundModel!="10")
			{
				echo '<tr><td class="fieldLabel_compulsory"> Programme type <br>';
				echo HTML::select('ProgType', $ProgType_dropdown, $vo->LearningDelivery->ProgType, true, true).'</td>';
			}

			if(($vo->LearningDelivery->FundModel=="45" || $vo->LearningDelivery->FundModel=="70") && $vo->LearningDelivery->ProgType!='99')
			{
				echo '<td class="fieldLabel_compulsory"> Framework code <br>';
				echo HTML::select('FworkCode', $FworkCode_dropdown, $vo->LearningDelivery->FworkCode, true, true).'</td></tr>';
			}

			echo '<tr><td class="fieldLabel_compulsory"> Main Delivery Method <br>';
			echo HTML::select('MainDelMeth', $MainDelMeth_dropdown, $vo->LearningDelivery->MainDelMeth, true, true).'</td>';
			echo '<td class="fieldLabel_compulsory"> Proportion of funding remaining <br>';
			echo "<input class='compulsory' type='text' value='100' style='' id='PropFundRemain' name='PropFundRemain' maxlength=8 size=8></td></tr>";

			if(($vo->LearningDelivery->FundModel=="21" || $vo->LearningDelivery->FundModel=="22"))
			{
				echo '<tr><td class="fieldLabel_compulsory"> Delivery Mode <br>';
				echo HTML::select('DelMode', $DelMode_dropdown, $vo->LearningDelivery->DelMode, true, true).'</td>';
				echo '<td class="fieldLabel_compulsory"> Distance Learning SLN <br>';
				echo "<input class='compulsory' type='text' value='' style='' id='DistLearnSLN' name='DistLearnSLN' maxlength=8 size=8></td></tr>";
			}

			if($delivery->FworkCode=='')
				$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks order by Framework_Code;", DAO::FETCH_NUM);
			else
				$PwayCode_dropdown = DAO::getResultset($link,"SELECT DISTINCT Framework_Pathway_Code, LEFT(CONCAT(Framework_Pathway_Code, ' ', Framework_Pathway_Desc),50) ,null from lad201213.frameworks where frameworks.framework_code = '$delivery->FworkCode' order by Framework_Code;", DAO::FETCH_NUM);

			echo '<tr><td class="fieldLabel_compulsory"> Apprenticeship pathway <br>';
			echo HTML::select('PwayCode', $PwayCode_dropdown, $vo->LearningDelivery->PwayCode, true, true).'</td>';

			echo '<tr><td class="fieldLabel_compulsory"> Partner UKPRN <br>';
			echo HTML::select('PartnerUKPRN', $PartnerUKPRN_dropdown, $vo->LearningDelivery->PartnerUKPRN, true, true) . '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> Tuition fees received year to date <br>';
			echo "<input class='compulsory' type='text' value='' style='' id='FeeYTD' name='FeeYTD' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Source of tuition fees <br>';
			echo "<input class='compulsory' type='text' value='' style='' id='FeeSource' name='FeeSource' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Employer role <br>';
			echo "<input class='compulsory' type='text' value='" . $vo->LearningDelivery->EmpRole . "' style='' id='EmpRole' name='EmpRole' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Employment outcome <br>';
			echo HTML::select('EmpOutcome', $EmpOutcome_dropdown, $vo->LearningDelivery->EmpOutcome, true, true) . '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> Project dossier number <br>';
			echo "<input class='compulsory' type='text' value='" . $vo->LearningDelivery->ESFProjDosNumber . "' style='' id='ESFProjDosNumber' name='ESFProjDosNumber' maxlength=8 size=8></td>";
			echo '<td class="fieldLabel_compulsory"> Local project number <br>';
			echo "<input class='compulsory' type='text' value='" . $vo->LearningDelivery->ESFLocProjNumber . "' style='' id='ESFLocProjNumber' name='ESFLocProjNumber' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Delivery location postcode <br>';
			echo "<input class='compulsory' type='text' value='" . $vo->LearningDelivery->DelLocPostCode . "' style='' id='DelLocPostCode' name='DelLocPostCode' maxlength=8 size=8></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory"> Full or co-funding indicator (FFI) <br>';
			$xpath = $vo->LearningDelivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='FFI']/LearnDelFAMCode"); $ffi = (empty($xpath[0]))?'':$xpath[0];
			echo HTML::select('FFI', $FFI_dropdown, $ffi, true, true) . '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> ASL provision type <br>';
			$xpath = $vo->LearningDelivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='ASL']/LearnDelFAMCode"); $asl = (empty($xpath[0]))?'':$xpath[0];
			echo HTML::select('ASL', $ASL_dropdown, $asl, true, true) . '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> Re-take indicator <br>';
			$xpath = $vo->LearningDelivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RET']/LearnDelFAMCode"); $ret = (empty($xpath[0]))?'':$xpath[0];
			echo HTML::select('RET', $RET_dropdown, $ret, true, true) . '</td></tr>';

			echo '<td class="fieldLabel_optional"> Restart indicator (RES) <br>';
			$xpath = $vo->LearningDelivery->xpath("./LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode"); $res = (empty($xpath[0]))?'':$xpath[0];
			echo HTML::select('RES', $RES_dropdown, $res, true, false) . '</td></tr>';

			echo '<tr><td class="fieldLabel_optional"> Additional learning need (ALN) <br>';
			echo HTML::select('ALN', $ALN_dropdown, '', true, false) . '</td>';
			echo '<td class="fieldLabel_optional"> Additional social need (ASN) <br>';
			echo HTML::select('ASN', $ASN_dropdown, '', true, false) . '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory">Provider Specified Delivery Monitoring Information<br> </td></tr>';
			echo '<tr><td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
			echo "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonA' name='ProvSpecDelMonA' maxlength=12 size=30></td>";
			echo '<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
			echo "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonB' name='ProvSpecDelMonB' maxlength=12 size=30></td></tr>";
			echo '<tr><td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
			echo "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonC' name='ProvSpecDelMonC' maxlength=12 size=30></td>";
			echo '<td class="fieldLabel_optional"> Provider Specified Learner Monitoring <br>';
			echo "<input class='optional' type='text' value='' style='' id='ProvSpecDelMonD' name='ProvSpecDelMonD' maxlength=12 size=30></td></tr>";

			echo '<tr><td class="fieldLabel_compulsory">Learning End Information<br> </td></tr>';

			echo '<tr><td class="fieldLabel_optional"> Learning actual end date <br>';
			echo HTML::datebox('LearnActEndDate', $vo->LearningDelivery->LearnActEndDate);
			echo '</td><td class="fieldLabel_compulsory"> Completion status <br>';
			echo HTML::select('CompStatus', $CompStatus_dropdown, $vo->LearningDelivery->CompStatus, false, true);
			echo '</td></tr>';

			echo '<tr><td class="fieldLabel_compulsory"> Withdrawal reason <br>';
			echo HTML::select('WithdrawReason', $WithdrawReason_dropdown, $vo->LearningDelivery->WithdrawReason, true, true);
			echo '</td><td class="fieldLabel_compulsory"> Outcome indicator <br>';
			echo HTML::select('Outcome', $Outcome_dropdown, $vo->LearningDelivery->Outcome, true, true) . '</td></tr>';

			echo '</td><td class="fieldLabel_compulsory"> Actual progression route <br>';
			echo HTML::select('ActProgRoute', $ActProgRoute_dropdown, '', true, true);
			echo '</td></tr>';

			echo '<tr><td class="fieldLabel_optional"> Achievement date <br>';
			echo HTML::datebox('AchDate', $vo->LearningDelivery->AchDate);
			echo '</td></tr>';

			echo '<tr><td class="fieldLabel_optional"> Credits achieved <br>';
			echo "<input class='optional' type='text' value='' style='' id='CredAch' name='CredAch' maxlength=12 size=30></td>";
			echo '</td><td class="fieldLabel_compulsory"> Outcome Grade <br>';
			echo HTML::select('OutGrade', $OutGrade_dropdown, '', true, true);
			echo '</td></tr>';
			?>

		</table>
	</fieldset>
</div>

<input id="counter" type="hidden" value="0">
</form>

<form  name="pdf" id="pdf" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<input type="hidden" name="_action" value="pdf_from_ilr2012" />
	<input type="hidden" name="xml" value="" />
	<input type="hidden" name="contract_id" value="<?php echo $contract_id;?>" />
</form>


<div id="debug"></div>

<?php
// Audit Data
$sql = <<<HEREDOC
SELECT 
	*
FROM 
	ilr_audit
Where
	tr_id = '$tr_id' and contrat_id = '$contract_id'
Order by 
	date
HEREDOC;

$count = DAO::getSingleValue($link, "select count(*) from ilr_audit where tr_id = '$tr_id' and contrat_id = '$contract_id'");
if($count>0)
{
	echo '<h3>Audit Trail</h3>';

	$st = $link->query($sql);
	if($st)
	{
		$c=0;
		echo '<table class="resultset" border="0" cellspacing="0" cellpadding="6">';
		echo '<thead><tr><th>&nbsp;</th><th>Name</th><th>Aim</th><th>Field</th><th>From</th><th>To</th><th>On</th><th>User Agent</th></tr></thead>';
		echo '<tbody>';
		$ids = array();
		while($row = $st->fetch())
		{
			echo '<td align="center"><img height="80%" width = "80%" src="/images/event.jpg" /></td>';
			echo '<td align="left">' . HTML::cell($row['username']) . "</td>";
			echo '<td align="left">' . HTML::cell(str_replace("A","",$row['A09'])) . "</td>";
			echo '<td align="left">' . HTML::cell($row['changed']) . "</td>";
			echo '<td align="left">' . HTML::cell($row['from']) . "</td>";
			echo '<td align="left">' . HTML::cell($row['to']) . "</td>";
			echo '<td align="left">' . HTML::cell($row['date']) . "</td>";
			echo '<td align="left">' . HTML::cell($row['user_agent']) . "</td>";
			echo '</tr>';
		}
		echo '</table>';
	}
}
?>
<!-- Popup calendar -->
<div id="calPop1" style="position:absolute;visibility:hidden;background-color:white;"></div>


</body>
</html>


