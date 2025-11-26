if (typeof window.event != 'undefined') // IE
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

function deleteAim(butt)
{
	var node = butt;
	do
	{
		node = node.parentNode;
	} 	while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));
	if(window.confirm("Do you want to delete this Subsidiary Aim "+$(this).attr('id')))
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

function addAim(butt) {
	var node = butt;
	do {
		node = node.parentNode;
	} while (!(node.tagName == 'DIV' && node.id.substr(0,3) == 'sub'));

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
	for( var i = 0; i < children.length; i++ ) {
		// #115 - riche ensuring only divs get incremented
		if( children[i].tagName == 'DIV' ) {
			children[i].id = 'sub' + ++window.aims_counter;
		}
	}
}

function addAimFromMain(butt) {

	var container = document.getElementById('contents');
	var template = document.getElementById('sub')
	var newAim = template.cloneNode(true);

	$("#ilr").validationEngine('detach');

	$("div:empty").remove();

	newAim.getEsf = template.getEsf;

	set_tabs();

	var childCount = $("ul#loc li").size();

	newAim.id = 'tab'+childCount++;
	container.appendChild(newAim);

	// Shove values from Main aim to the newly created sub aim
	var div = newAim;
	if(div != null) {
		var elements = div.getElementsByTagName('input');
		for( var i = 0; i < elements.length; i++ ) {
			if(elements[i] != "radio" || (elements[i].type == "radio" && elements[i].checked) ) {
				if(elements[i].name=='SA27')
					elements[i].value = document.ilr.A27.value;
				if(elements[i].name=='SA28')
					elements[i].value = document.ilr.A28.value;
				if(elements[i].name=='SA51a')
					elements[i].value = document.ilr.A51a.value;
				if(elements[i].name=='SA23')
					elements[i].value = document.ilr.A23.value;
			}
			if(elements[i].type == "checkbox")	{
			}
		}
		elements = div.getElementsByTagName('select');
		for( var i = 0; i < elements.length; i++ ) {
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

	$("#ilr").validationEngine('attach');

	newAim.style.display = "block";
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
	if ( document.ilr.L01.options[document.ilr.L01.selectedIndex].value != document.ilr.A01.options[document.ilr.A01.selectedIndex].value )
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

/*function A15_onchange(value)
 {
 a15 = value.value;
 a10 = document.ilr.A10.options[document.ilr.A10.selectedIndex].value;
 a18 = document.ilr.A18.options[document.ilr.A18.selectedIndex].value;
 if(a10 == 45 && a15 == 99 && (a18 == 22 || a18 == 23) )
 freezProgrammeAim(true);
 else
 freezProgrammeAim(false);

 document.ilr.PA15.selectedIndex = document.ilr.A15.selectedIndex;
 var sf = null;
 for(subaims = 1; subaims <= window.aims_counter; subaims++)
 {
 sf = getSubsidiaryAimElements(subaims);
 sf['SA15'].selectedIndex = document.ilr.A15.selectedIndex;
 }
 }
 */
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

function SOF_onchange(value)
{
	if(value.title != 1)
		return;
	sof = value.value;

	el = document.getElementsByName("SOF");

	for(i=0; i<el.length; i++)
	{
		if(el[i].title == 3)
			el[i].value = sof;
	}
}

function FFI_onchange(value)
{
	if(value.title != 1)
		return;
	ffi = value.value;
	el = document.getElementsByName("FFI");
	for(i=0; i<el.length; i++)
	{
		if(el[i].title == 3)
			el[i].value = ffi;
	}
}



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

function checkDatesValidity()
{
	var sf = null;
	var number_of_aims = ($('.Unit').length);

	for(subaims = 2; subaims <= number_of_aims; subaims++)
	{
		sf = getSubsidiaryAimFields(subaims);
		if(sf != null)
		{
			if(sf['LearnAimRef']!='ToBeDeleted' && sf['LearnAimRef']!='' && (sf['LearnAimRef'] == 'ZPROG001' || sf['AimType'] == '1' || sf['AimType'] == '4'))
			{
				// Date Validation
				dBits =  sf['LearnStartDate'].split("/");
				sd = new Date(dBits[2],(dBits[1]-1),dBits[0]);
				dBits =  sf['LearnActEndDate'].split("/");
				ed = new Date(dBits[2],(dBits[1]-1),dBits[0]);

				fundinglastdate = new Date("07/31/2015");
				fundingstartdate = new Date("08/01/2014");

				if(ed > fundinglastdate)
				{
					alert("Learn Aim Ref: "+sf['LearnAimRef']+" end date "+sf['LearnActEndDate']+" is after this contract year, should be entered into 2015-16 ILR.");
					return false;
				}

				if(ed < fundingstartdate && (sf['AimType'] == '1' || sf['AimType'] == '4'))
				{
					alert("Learn Aim Ref: "+sf['LearnAimRef']+" end date "+sf['LearnActEndDate']+" is before this contract year, should be entered into 2013-14 ILR.");
					return false;
				}
			}
		}
	}
	return true;
}


function toXML()
{
	var xml = '<Learner>';
	xml += "\n<LearnRefNumber>" + document.ilr.LearnRefNumber.value + "</LearnRefNumber>";
	xml += "\n<PrevLearnRefNumber>" + document.ilr.PrevLearnRefNumber.value + "</PrevLearnRefNumber>";
	xml += "\n<ULN>" + document.ilr.ULN.value + "</ULN>";
	xml += "\n<PrevUKPRN>" + document.ilr.PrevUKPRN.value + "</PrevUKPRN>";
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
	xml += "\n<PlanLearnHours>" + document.ilr.PlanLearnHours.value + "</PlanLearnHours>";
	xml += "\n<PlanEEPHours>" + document.ilr.PlanEEPHours.value + "</PlanEEPHours>";

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

	if(document.ilr.RUI1.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>1</ContPrefCode></ContactPreference>";
	if(document.ilr.RUI2.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>2</ContPrefCode></ContactPreference>";
	if(document.ilr.RUI4.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>4</ContPrefCode></ContactPreference>";
	if(document.ilr.RUI5.checked)
		xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>5</ContPrefCode></ContactPreference>";

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

	if(typeof(document.ilr.LDA)!='undefined')
		if(document.ilr.LDA.options[document.ilr.LDA.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>LDA</LearnFAMType><LearnFAMCode>"+ document.ilr.LDA.options[document.ilr.LDA.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.HNS)!='undefined')
		if(document.ilr.HNS.options[document.ilr.HNS.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>HNS</LearnFAMType><LearnFAMCode>"+ document.ilr.HNS.options[document.ilr.HNS.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.EHC)!='undefined')
		if(document.ilr.EHC.options[document.ilr.EHC.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>EHC</LearnFAMType><LearnFAMCode>"+ document.ilr.EHC.options[document.ilr.EHC.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.DLA)!='undefined')
		if(document.ilr.DLA.options[document.ilr.DLA.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>DLA</LearnFAMType><LearnFAMCode>"+ document.ilr.DLA.options[document.ilr.DLA.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.ALS)!='undefined')
		if(document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>"+ document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

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

	if(typeof(document.ilr.NLM1)!='undefined')
		if(document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.NLM2)!='undefined')
		if(document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.MGA)!='undefined')
		if(document.ilr.MGA.options[document.ilr.MGA.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>MGA</LearnFAMType><LearnFAMCode>"+ document.ilr.MGA.options[document.ilr.MGA.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.EGA)!='undefined')
		if(document.ilr.EGA.options[document.ilr.EGA.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>EGA</LearnFAMType><LearnFAMCode>"+ document.ilr.EGA.options[document.ilr.EGA.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.PPE1)!='undefined')
		if(document.ilr.PPE1.options[document.ilr.PPE1.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>"+ document.ilr.PPE1.options[document.ilr.PPE1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.PPE2)!='undefined')
		if(document.ilr.PPE2.options[document.ilr.PPE2.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>"+ document.ilr.PPE2.options[document.ilr.PPE2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

	if(typeof(document.ilr.FME)!='undefined')
		if(document.ilr.FME.options[document.ilr.FME.selectedIndex].value!='')
			xml+="<LearnerFAM><LearnFAMType>FME</LearnFAMType><LearnFAMCode>"+ document.ilr.FME.options[document.ilr.FME.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

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

			sei = "document.ilr.SEI" + a + ".options[document.ilr.SEI" + a + ".selectedIndex].value";
			if(eval(sei)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" + eval(sei) + "</ESMCode></EmploymentStatusMonitoring>";

			eii = "document.ilr.EII" + a + ".options[document.ilr.EII" + a + ".selectedIndex].value";
			if(eval(eii)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>" + eval(eii) + "</ESMCode></EmploymentStatusMonitoring>";

			lou = "document.ilr.LOU" + a + ".options[document.ilr.LOU" + a + ".selectedIndex].value";
			if(eval(lou)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" + eval(lou) + "</ESMCode></EmploymentStatusMonitoring>";

			loe = "document.ilr.LOE" + a + ".options[document.ilr.LOE" + a + ".selectedIndex].value";
			if(eval(loe)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>LOE</ESMType><ESMCode>" + eval(loe) + "</ESMCode></EmploymentStatusMonitoring>";

			bsi = "document.ilr.BSI" + a + ".options[document.ilr.BSI" + a + ".selectedIndex].value";
			if(eval(bsi)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>" + eval(bsi) + "</ESMCode></EmploymentStatusMonitoring>";

			pei = "document.ilr.PEI" + a + ".options[document.ilr.PEI" + a + ".selectedIndex].value";
			if(eval(pei)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>PEI</ESMType><ESMCode>" + eval(pei) + "</ESMCode></EmploymentStatusMonitoring>";

			ron = "document.ilr.RON" + a + ".options[document.ilr.RON" + a + ".selectedIndex].value";
			if(eval(ron)!='')
				xml+="<EmploymentStatusMonitoring><ESMType>RON</ESMType><ESMCode>" + eval(ron) + "</ESMCode></EmploymentStatusMonitoring>";

			//sem = "document.ilr.SEM" + a + ".options[document.ilr.SEM" + a + ".selectedIndex].value";
			//if(eval(sem)!='')
			//    xml+="<EmploymentStatusMonitoring><ESMType>SEM</ESMType><ESMCode>" + eval(sem) + "</ESMCode></EmploymentStatusMonitoring>";

			xml+="</LearnerEmploymentStatus>";
		}
	}
	var LearnerHE = "";
	if(document.ilr.UCASPERID != undefined && document.ilr.UCASPERID.value!='')
		LearnerHE += "\n<UCASPERID>" + document.ilr.UCASPERID.value + "</UCASPERID>";
	if(document.ilr.TTACCOM != undefined && document.ilr.TTACCOM.value!='')
		LearnerHE += "\n<TTACCOM>" + document.ilr.TTACCOM.value + "</TTACCOM>";
	if(document.ilr.FINAMOUNT1 != undefined && document.ilr.FINAMOUNT1.value!='')
		LearnerHE += "\n<LearnerHEFinancialSupport><FINTYPE>1</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT1.value + "</FINAMOUNT></LearnerHEFinancialSupport>";
	if(document.ilr.FINAMOUNT2 != undefined && document.ilr.FINAMOUNT2.value!='')
		LearnerHE += "\n<LearnerHEFinancialSupport><FINTYPE>2</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT2.value + "</FINAMOUNT></LearnerHEFinancialSupport>";
	if(document.ilr.FINAMOUNT3 != undefined && document.ilr.FINAMOUNT3.value!='')
		LearnerHE += "\n<LearnerHEFinancialSupport><FINTYPE>3</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT3.value + "</FINAMOUNT></LearnerHEFinancialSupport>";
	if(document.ilr.FINAMOUNT4 != undefined && document.ilr.FINAMOUNT4.value!='')
		LearnerHE += "\n<LearnerHEFinancialSupport><FINTYPE>4</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT1.value + "</FINAMOUNT></LearnerHEFinancialSupport>";
	if(LearnerHE != "")
		xml += '<LearnerHE>' + LearnerHE + '</LearnerHE>';
	var sf = null;
	var number_of_aims = ($('.Unit').length);

	for(subaims = 2; subaims <= number_of_aims; subaims++)
	{
		sf = getSubsidiaryAimFields(subaims);
		if(sf != null)
		{
			if(sf['LearnAimRef']!='ToBeDeleted' && sf['LearnAimRef']!='')
			{
				var hem1_name = "HEM1_" + sf['LearnAimRef'];
				var hem3_name = "HEM3_" + sf['LearnAimRef'];
				var hem5_name = "HEM5_" + sf['LearnAimRef'];

				xml += "<LearningDelivery>";
				xml += "\n<LearnAimRef>" + sf['LearnAimRef'] + "</LearnAimRef>";
				xml += "\n<AimType>" + sf['AimType'] + "</AimType>";
				xml += "\n<AimSeqNumber>" + subaims + "</AimSeqNumber>";
				xml += "\n<LearnStartDate>" + sf['LearnStartDate'] + "</LearnStartDate>";
				xml += "\n<OrigLearnStartDate>" + sf['OrigLearnStartDate'] + "</OrigLearnStartDate>";
				xml += "\n<LearnPlanEndDate>" + sf['LearnPlanEndDate'] + "</LearnPlanEndDate>";
				xml += "\n<FundModel>" + sf['FundModel'] + "</FundModel>";
				xml += "\n<ProgType>" + sf['ProgType'] + "</ProgType>";
				xml += "\n<FworkCode>" + sf['FworkCode'] + "</FworkCode>";
				xml += "\n<PwayCode>" + sf['PwayCode'] + "</PwayCode>";
				xml += "\n<PartnerUKPRN>" + sf['PartnerUKPRN'] + "</PartnerUKPRN>";
				xml += "\n<DelLocPostCode>" + sf['DelLocPostCode'] + "</DelLocPostCode>";
				xml += "\n<PriorLearnFundAdj>" + sf['PriorLearnFundAdj'] + "</PriorLearnFundAdj>";
				xml += "\n<OtherFundAdj>" + sf['OtherFundAdj'] + "</OtherFundAdj>";
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

				var LearnerHETagWritten = false;
				var condition1 = sf['SOF'] != 'undefined' && sf['SOF'] == '1';
				var condition2 = sf['SOF'] != 'undefined' && sf['SOF'] == '107' && sf['ProgType'] >= 20;
				var condition3 = sf['WPL'] != 'undefined' && sf['WPL'] != '1' && sf['FundModel'] == 35 && sf['ProgType'] >= 20;
				var condition4 = sf['FundModel'] == 99 && sf['ProgType'] >= 20;

				var learningDeliveryHETags = "";
				if(condition1 || condition2 || condition3 || condition4)
				{
					if(sf['NUMHUS'] != undefined && sf['NUMHUS'] != '')
						learningDeliveryHETags +="\n<NUMHUS>" + sf['NUMHUS'] + "</NUMHUS>";
					if(sf['SSN'] != undefined && sf['SSN'] != '')
						learningDeliveryHETags +="\n<SSN>" + sf['SSN'] + "</SSN>";
					if(sf['QUALENT3'] != undefined && sf['QUALENT3'] != '')
						learningDeliveryHETags +="\n<QUALENT3>" + sf['SOC2000'] + "</QUALENT3>";
					if(sf['SOC2000'] != undefined && sf['SOC2000'] != '')
						learningDeliveryHETags +="\n<SOC2000>" + sf['SOC2000'] + "</SOC2000>";
					if(sf['SEC'] != undefined && sf['SEC'] != '')
						learningDeliveryHETags +="\n<SEC>" + sf['SEC'] + "</SEC>";
					if(sf['UCASAPPID'] != undefined && sf['UCASAPPID'] != '')
						learningDeliveryHETags +="\n<UCASAPPID>" + sf['UCASAPPID'] + "</UCASAPPID>";
					if(sf['TYPEYR'] != undefined && sf['TYPEYR'] != '')
						learningDeliveryHETags +="\n<TYPEYR>" + sf['TYPEYR'] + "</TYPEYR>";
					if(sf['MODESTUD'] != undefined && sf['MODESTUD'] != '')
						learningDeliveryHETags +="\n<MODESTUD>" + sf['MODESTUD'] + "</MODESTUD>";
					if(sf['FUNDLEV'] != undefined && sf['FUNDLEV'] != '')
						learningDeliveryHETags +="\n<FUNDLEV>" + sf['FUNDLEV'] + "</FUNDLEV>";
					if(sf['FUNDCOMP'] != undefined && sf['FUNDCOMP'] != '')
						learningDeliveryHETags +="\n<FUNDCOMP>" + sf['FUNDCOMP'] + "</FUNDCOMP>";
					if(sf['STULOAD'] != undefined && sf['STULOAD'] != '')
						learningDeliveryHETags +="\n<STULOAD>" + sf['STULOAD'] + "</STULOAD>";
					if(sf['YEARSTU'] != undefined && sf['YEARSTU'] != '')
						learningDeliveryHETags +="\n<YEARSTU>" + sf['YEARSTU'] + "</YEARSTU>";
					if(sf['MSTUFEE'] != undefined && sf['MSTUFEE'] != '')
						learningDeliveryHETags +="\n<MSTUFEE>" + sf['MSTUFEE'] + "</MSTUFEE>";
					if(sf['PCOLAB'] != undefined && sf['PCOLAB'] != '')
						learningDeliveryHETags +="\n<PCOLAB>" + sf['PCOLAB'] + "</PCOLAB>";
					if(sf['PCFLDCS'] != undefined && sf['PCFLDCS'] != '')
						learningDeliveryHETags +="\n<PCFLDCS>" + sf['PCFLDCS'] + "</PCFLDCS>";
					if(sf['PCSLDCS'] != undefined && sf['PCSLDCS'] != '')
						learningDeliveryHETags+="\n<PCSLDCS>" + sf['PCSLDCS'] + "</PCSLDCS>";
					if(sf['PCTLDCS'] != undefined && sf['PCTLDCS'] != '')
						learningDeliveryHETags +="\n<PCTLDCS>" + sf['PCTLDCS'] + "</PCTLDCS>";
					if(sf['SPECFEE'] != undefined && sf['SPECFEE'] != '')
						learningDeliveryHETags +="\n<SPECFEE>" + sf['SPECFEE'] + "</SPECFEE>";
					if(sf['NETFEE'] != undefined && sf['NETFEE'] != '')
						learningDeliveryHETags +="\n<NETFEE>" + sf['NETFEE'] + "</NETFEE>";
					if(sf['DOMICILE'] != undefined && sf['DOMICILE'] != '')
						learningDeliveryHETags +="\n<DOMICILE>" + sf['DOMICILE'] + "</DOMICILE>";
					if(sf['ELQ'] != undefined && sf['ELQ'] != '')
						learningDeliveryHETags +="\n<ELQ>" + sf['ELQ'] + "</ELQ>";
					if(learningDeliveryHETags != "")
						xml += '<LearningDeliveryHE>' + learningDeliveryHETags + '</LearningDeliveryHE>';
				}
				if(sf['SOF1']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" + sf['SOF'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['FFI']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" + sf['FFI'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['WPL']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>WPL</LearnDelFAMType><LearnDelFAMCode>" + sf['WPL'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
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
				if(sf['LDM3']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM4'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['SPP']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SPP</LearnDelFAMType><LearnDelFAMCode>" + sf['SPP'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['SSP']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>SSP</LearnDelFAMType><LearnDelFAMCode>" + sf['SSP'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['RES']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>" + sf['RES'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['ADL']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>ADL</LearnDelFAMType><LearnDelFAMCode>" + sf['ADL'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['ASL']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" + sf['ASL'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['POD']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>POD</LearnDelFAMType><LearnDelFAMCode>" + sf['POD'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(sf['TBS']!='undefined')
					xml +="\n<LearningDeliveryFAM><LearnDelFAMType>TBS</LearnDelFAMType><LearnDelFAMCode>" + sf['TBS'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
				if(condition1 || condition2 || condition3 || condition4)
				{
					if(sf[hem1_name] != 'undefined' && sf[hem1_name] == true)
						xml +="\n<LearningDeliveryFAM><LearnDelFAMType>HEM</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
					if(sf[hem3_name] != 'undefined' && sf[hem3_name] == true)
						xml +="\n<LearningDeliveryFAM><LearnDelFAMType>HEM</LearnDelFAMType><LearnDelFAMCode>3</LearnDelFAMCode></LearningDeliveryFAM>";
					if(sf[hem5_name] != 'undefined' && sf[hem5_name] == true)
						xml +="\n<LearningDeliveryFAM><LearnDelFAMType>HEM</LearnDelFAMType><LearnDelFAMCode>5</LearnDelFAMCode></LearningDeliveryFAM>";
				}
				for(a = 1; a<=5; a++)
				{
					v1 = sf['LSF'+a];
					if(typeof(eval(v1))!='undefined')
					{
						xml +="\n<LearningDeliveryFAM><LearnDelFAMType>LSF</LearnDelFAMType><LearnDelFAMCode>" + eval(v1) + "</LearnDelFAMCode>";
						xml +="\n<LearnDelFAMDateFrom>" + sf['LSFFrom'+a] + "</LearnDelFAMDateFrom>";
						xml +="\n<LearnDelFAMDateTo>" + sf['LSFTo'+a] + "</LearnDelFAMDateTo></LearningDeliveryFAM>";
					}
				}

				for(a = 1; a<=5; a++)
				{
					v1 = sf['ALB'+a];
					if(typeof(eval(v1))!='undefined')
					{
						xml +="\n<LearningDeliveryFAM><LearnDelFAMType>ALB</LearnDelFAMType><LearnDelFAMCode>" + eval(v1) + "</LearnDelFAMCode>";
						xml +="\n<LearnDelFAMDateFrom>" + sf['ALBFrom'+a] + "</LearnDelFAMDateFrom>";
						xml +="\n<LearnDelFAMDateTo>" + sf['ALBTo'+a] + "</LearnDelFAMDateTo></LearningDeliveryFAM>";
					}
				}

				for(a = 1; a<=10; a++)
				{
					v1 = sf['TBFinType'+a];
					if(typeof(v1)!='undefined')
					{
						xml +="\n<ApprenticeshipTrailblazerFinancialDetails>";
						xml +="\n<TBFinType>" + v1 + "</TBFinType>";
						xml +="\n<TBFinCode>" + sf['TBFinCode'+a] + "</TBFinCode>";
						xml +="\n<TBFinDate>" + sf['TBFinDate'+a] + "</TBFinDate>";
						xml +="\n<TBFinAmount>" + sf['TBFinAmount'+a] + "</TBFinAmount>";
						xml +="\n</ApprenticeshipTrailblazerFinancialDetails>";
					}
				}

				for(a = 1; a<=10; a++)
				{
					v1 = sf['WorkPlaceStartDate'+a];
					if(typeof(v1)!='undefined')
					{
						xml +="\n<LearningDeliveryWorkPlacement>";
						xml +="\n<WorkPlaceStartDate>" + v1 + "</WorkPlaceStartDate>";
						xml +="\n<WorkPlaceEndDate>" + sf['WorkPlaceEndDate'+a] + "</WorkPlaceEndDate>";
						xml +="\n<WorkPlaceMode>" + sf['WorkPlaceMode'+a] + "</WorkPlaceMode>";
						xml +="\n<WorkPlaceEmpId>" + sf['WorkPlaceEmpId'+a] + "</WorkPlaceEmpId>";
						xml +="\n</LearningDeliveryWorkPlacement>";
					}
				}

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
		}
	}
	xml += "</Learner>";

	xml = xml.replace('&', '&amp;');
	//xml = xml.replace("'", '&apos;');
	return xml;
}


function getSubsidiaryAimFields(aimNumber)
{
	var div = document.getElementById('tab' + aimNumber);
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


function shouldMigrate()
{
	var sf = null;
	var number_of_aims = ($('.Unit').length);
	migrate = 0;
	for(subaims = 2; subaims <= number_of_aims; subaims++)
	{
		sf = getSubsidiaryAimFields(subaims);
		if(sf != null)
		{
			if(sf['LearnAimRef']!='ToBeDeleted' && sf['LearnAimRef']!='')
			{
				if(sf['LearnActEndDate']=='' || sf['LearnActEndDate']=='dd/mm/yyyy' || sf['CompStatus']=='6')
					migrate = 1;
			}
		}
	}
	return migrate;
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

	var mainForm = document.forms[0];
	var canvas = document.getElementById('unitCanvas');

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

function E16only(myfield, e, dec)
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
	else if ((("ABCDE").indexOf(keychar) > -1))
		return true;

// decimal point jump
	else
		return false;
}

function E18only(myfield, e, dec)
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
	if ((key==null) || (key==0) || (key==8) ||
		(key==9) || (key==13) || (key==27) )
		return true;

// numbers
	else if ((("ABCD").indexOf(keychar) > -1))
		return true;

// decimal point jump
	else
		return false;
}

function E19only(myfield, e, dec)
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
	else if ((("ABCDEFGHI").indexOf(keychar) > -1))
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

function changeDates()
{
	var number_of_aims = ($('.Unit').length);
	for(subaims = 2; subaims <= number_of_aims; subaims++)
	{
		var div = document.getElementById('tab' + subaims);
		if(div != null)
		{
			elements = div.getElementsByTagName('input');
			for(var i = 0; i < elements.length; i++)
			{
				if(elements[i].name=='LearnStartDate' || elements[i].name=='LearnPlanEndDate' || elements[i].name=='OrigLearnStartDate')
				{
					elements[i].disabled = false;
				}
			}
		}
	}
	alert("You are allowed to ammend start dates and planned end dates now");
}

$(function() {
	$('a#add').live('click', function() {
		var i=$("ul#loc li").size()+1;
		$('<li class="selected" title="active"><a href="#tab'+i+'"><em>Learning Aim</em></a></li>').insertBefore($(this).parent());
		$(this).parent().attr('title', '');
		$(this).parent().attr('class', '');

		set_tabs();

		treeInit();
	});

	$('a#remove').click(function(){
		$('#loc li:last').remove();
		i--;
	});

	$('ul#loc li a[id!=add]').live('click', function() {
		$("#ilr").validationEngine('hideAll');

		$('div#contents td').each( function() {
			$(this).css('background-color','#FFF');
			$(this).css('border','solid 1px #FFF');
		});

		var show_tab = $(this).attr('href');

		$("ul#loc li").each(function() {
			$(this).attr('title', '');
			$(this).attr('class', '');
		});

		$("div#contents > div").each(function() {
			$(this).css('display','none');
			// disable hidden inputs to prevent validation engine kicking off
			$('#'+$(this).attr('id')+' :input').each(function() {
				$(this).attr('disabled', true);
			});
		});


		$(this).closest('li').attr('title', 'active');
		$(this).closest('li').attr('class', 'selected');

		// get the active tab to renable the inputs
		var this_tab = $('div '+show_tab);

		$(this_tab).css('display','block');

		$('#'+$(this_tab).attr('id')+' :input').each(function(){
			$(this).removeAttr('disabled');
		});
	});

	$('a.delete-la').live('click', function(){
		var name_of_tab = $(this).closest('div[id^=tab]').attr('id').replace('tab','');
		$('#deleteILR').dialog();

		var theDialog = $("#deleteILR").dialog({
			modal: true,
			width: 300,
			closeOnEscape: true,
			autoOpen: true,
			resizable: false,
			draggable: false,
			position:"centre",
			buttons:
			{
				"OK":function(){
					var active_tab = name_of_tab;
					active_tab--;
					var rm_tab = $('ul#loc li a[href=#tab'+name_of_tab+']');
					var act_tab = $('ul#loc li a[href=#tab'+active_tab+']');
					$(act_tab).closest('li').attr('title','active');
					$(act_tab).closest('li').attr('class','selected');
					$('div#tab'+active_tab).css('display','block');
					obj = document.getElementsByName('LearnAimRef');
					for(a = 0; a<obj.length; a++)
						if(obj[a].parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.id==('tab'+name_of_tab))
							obj[a].value='ToBeDeleted';
					$(rm_tab).closest('li').remove();
					$(this).closest('div[id^=tab]').remove();
					set_tabs();
					$(this).dialog('close');
				},
				"Cancel": function() {$(this).dialog("close");}
			}
		});

	});
});

$(document).ready(function(){
	set_tabs();
});


function set_tabs() {

	/*	var tab_count = 1
	 $("ul#moc li a").each(function() {
	 if ( $(this).attr('id') != 'add' ) {
	 $(this).attr('href', '#tab'+tab_count);
	 tab_count++;
	 }
	 });
	 */

	var tab_count = 1
	$("div#conten > div").each(function() {
		$(this).attr('id', 'tab'+tab_count);
		tab_count++;
	});
}




