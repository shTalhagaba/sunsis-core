/**
 * Created with JetBrains PhpStorm.
 * User: ianss
 * Date: 8/11/15
 * Time: 2:42 PM
 * To change this template use File | Settings | File Templates.
 */
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

    var zprog_found = false;
    for(subaims = 1; subaims < number_of_aims; subaims++)
    {
        var sf = getSubsidiaryAimFields(subaims);
        if(sf['LearnAimRef'] == 'ZPROG001')
        {
            zprog_found = true;
            var LearningDeliveryAimReference = sf['LearnAimRef'];
            // Date Validation
            var dBits =  sf['LearnStartDate'].split("/");
            var sd = new Date(dBits[2],(dBits[1]-1),dBits[0]);
            var dBits =  sf['LearnActEndDate'].split("/");
            var ed = new Date(dBits[2],(dBits[1]-1),dBits[0]);

            var fundinglastdate = new Date("07/31/2017");
            var fundingstartdate = new Date("08/01/2016");

            var cd = new Date();

            if((sf['AimType'] == '1' || sf['AimType'] == '4' || sf['AimType'] == '5') && (ed > fundinglastdate))
            {
                alert("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate'] + " is after this contract year, should be entered into 2016-17 ILR.");
//				custom_alert_OK_only("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate_'+LearningDeliveryAimReference] + " is after this contract year, should be entered into 2016-17 ILR.", "Error");
                return false;
            }

            if((sf['AimType'] == '1' || sf['AimType'] == '4' || sf['AimType'] == '5') && (ed < fundingstartdate))
            {
                alert("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate'] + " is before this contract year, should be entered into 2015-16 ILR.");
//				custom_alert_OK_only("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate_'+LearningDeliveryAimReference] + " is before this contract year, should be entered into 2014-15 ILR.", "Error");
                return false;
            }
            break;
        }
    }

    if(!zprog_found)
    {
        for(subaims = 1; subaims < number_of_aims; subaims++)
        {
            var sf = getSubsidiaryAimFields(subaims);
            if(sf != null)
            {
                if(sf['LearnAimRef']!='ToBeDeleted' && sf['LearnAimRef']!='')
                {
                    var LearningDeliveryAimReference = sf['LearnAimRef'];
                    // Date Validation
                    var dBits =  sf['LearnStartDate'].split("/");
                    var sd = new Date(dBits[2],(dBits[1]-1),dBits[0]);
                    var dBits =  sf['LearnActEndDate'].split("/");
                    var ed = new Date(dBits[2],(dBits[1]-1),dBits[0]);

                    var fundinglastdate = new Date("07/31/2017");
                    var fundingstartdate = new Date("08/01/2016");

                    var cd = new Date();

                    if((sf['AimType'] == '1' || sf['AimType'] == '4' || sf['AimType'] == '5') && (ed > fundinglastdate))
                    {
                        alert("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate'] + " is after this contract year, should be entered into 2016-17 ILR.");
//						custom_alert_OK_only("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate_'+LearningDeliveryAimReference] + " is after this contract year, should be entered into 2016-17 ILR.", "Error");
                        return false;
                    }

                    if((sf['AimType'] == '1' || sf['AimType'] == '4' || sf['AimType'] == '5') && (ed < fundingstartdate))
                    {
                        alert("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate'] + " is before this contract year, should be entered into 2015-16 ILR.");
//						custom_alert_OK_only("Learn Aim Ref: " + sf['LearnAimRef'] + " end date " + sf['LearnActEndDate_'+LearningDeliveryAimReference] + " is before this contract year, should be entered into 2014-15 ILR.", "Error");
                        return false;
                    }
                }
            }
        }
    }
    return true;
}


function formatXml(xml) {
    var formatted = '';
    var reg = /(>)(<)(\/*)/g;
    xml = xml.replace(/(\r\n|\n|\r|\t)/gm,"");
    xml = xml.replace(reg, '$1\r\n$2$3');
    var pad = 0;
    jQuery.each(xml.split('\r\n'), function(index, node) {
        var indent = 0;
        if (node.match( /.+<\/\w[^>]*>$/ )) {
            indent = 0;
        } else if (node.match( /^<\/\w/ )) {
            if (pad != 0) {
                pad -= 1;
            }
        } else if (node.match( /^<\w[^>]*[^\/]>.*$/ )) {
            indent = 1;
        } else {
            indent = 0;
        }

        var padding = '';
        for (var i = 0; i < pad; i++) {
            padding += '  ';
        }

        formatted += padding + node + '\r\n';
        pad += indent;
    });
    return formatted;
}

function formatDate(_date)
{
    return formatDateW3C(stringToDate(_date));
}

function toXML()
{
    var xml = '<Learner>';
    xml += "<LearnRefNumber>" + document.ilr.LearnRefNumber.value.trim() + "</LearnRefNumber>";
    if(document.ilr.PrevLearnRefNumber.value.trim() !='')
        xml += "<PrevLearnRefNumber>" + document.ilr.PrevLearnRefNumber.value.trim() + "</PrevLearnRefNumber>";
    if(document.ilr.PrevUKPRN.value.trim() !='')
        xml += "<PrevUKPRN>" + document.ilr.PrevUKPRN.value.trim() + "</PrevUKPRN>";
    xml += "<ULN>" + document.ilr.ULN.value.trim() + "</ULN>";
    xml += "<FamilyName>" + document.ilr.FamilyName.value.trim() + "</FamilyName>";
    xml += "<GivenNames>" + document.ilr.GivenNames.value.trim() + "</GivenNames>";
    if(document.ilr.DateOfBirth.value.trim() !='' && document.ilr.DateOfBirth.value.trim() !='dd/mm/yyyy' && typeof(document.ilr.DateOfBirth.value.trim()) !='undefined')
        xml += "<DateOfBirth>" + formatDate(document.ilr.DateOfBirth.value.trim()) + "</DateOfBirth>";
    xml += "<Ethnicity>" + document.ilr.Ethnicity.options[document.ilr.Ethnicity.selectedIndex].value.trim() + "</Ethnicity>";
    if (document.ilr.Sex[0].checked)
        xml += "<Sex>" + "M" + "</Sex>";
    else
        xml += "<Sex>" + "F" + "</Sex>";
    if(document.ilr.LLDDHealthProb.options[document.ilr.LLDDHealthProb.selectedIndex].value.trim()!='')
        xml += "<LLDDHealthProb>" + document.ilr.LLDDHealthProb.options[document.ilr.LLDDHealthProb.selectedIndex].value.trim() + "</LLDDHealthProb>";
    if(document.ilr.NINumber.value.trim() !='')
        xml += "<NINumber>" + document.ilr.NINumber.value.trim() + "</NINumber>";
    if(document.ilr.PriorAttain.options[document.ilr.PriorAttain.selectedIndex].value.trim()!='')
        xml += "<PriorAttain>" + document.ilr.PriorAttain.options[document.ilr.PriorAttain.selectedIndex].value.trim() + "</PriorAttain>";
    if(document.ilr.Accom.options[document.ilr.Accom.selectedIndex].value.trim()!='')
        xml += "\n<Accom>" + document.ilr.Accom.options[document.ilr.Accom.selectedIndex].value + "</Accom>";
    if(document.ilr.ALSCost.value.trim() !='')
        xml += "<ALSCost>" + document.ilr.ALSCost.value.trim() + "</ALSCost>";
    if(document.ilr.PlanLearnHours.value.trim() !='')
        xml += "<PlanLearnHours>" + document.ilr.PlanLearnHours.value.trim() + "</PlanLearnHours>";
    if(document.ilr.PlanEEPHours.value.trim() !='')
        xml += "<PlanEEPHours>" + document.ilr.PlanEEPHours.value.trim() + "</PlanEEPHours>";
    if(document.ilr.MathGrade.options[document.ilr.MathGrade.selectedIndex].value.trim()!='')
        xml += "<MathGrade>" + document.ilr.MathGrade.options[document.ilr.MathGrade.selectedIndex].value.trim() + "</MathGrade>";
    if(document.ilr.EngGrade.options[document.ilr.EngGrade.selectedIndex].value.trim()!='')
        xml += "<EngGrade>" + document.ilr.EngGrade.options[document.ilr.EngGrade.selectedIndex].value.trim() + "</EngGrade>";

    if(document.ilr.AddLine1.value.trim()!='' || document.ilr.AddLine2.value.trim()!='' || document.ilr.AddLine3.value.trim()!='' || document.ilr.AddLine4.value.trim()!='')
    {
        xml+= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
        if(document.ilr.AddLine1.value.trim()!='')
            xml+= "<AddLine1>" + document.ilr.AddLine1.value.trim() + "</AddLine1>";
        if(document.ilr.AddLine2.value.trim()!='')
            xml+= "<AddLine2>" + document.ilr.AddLine2.value.trim() + "</AddLine2>";
        if(document.ilr.AddLine3.value.trim()!='')
            xml+= "<AddLine3>" + document.ilr.AddLine3.value.trim() + "</AddLine3>";
        if(document.ilr.AddLine4.value.trim()!='')
            xml+= "<AddLine4>" + document.ilr.AddLine4.value.trim() + "</AddLine4>";
        xml += "</PostAdd></LearnerContact>";
    }

    if(document.ilr.CurrentPostcode.value.trim()!='')
    {
        xml+= "<LearnerContact><LocType>2</LocType><ContType>2</ContType>";
        xml+= "<PostCode>" + document.ilr.CurrentPostcode.value.trim() + "</PostCode>";
        xml += "</LearnerContact>";
    }

    if(document.ilr.PostcodePriorEnrolment.value.trim()!='')
    {
        xml+= "<LearnerContact><LocType>2</LocType><ContType>1</ContType>";
        xml+= "<PostCode>" + document.ilr.PostcodePriorEnrolment.value.trim() + "</PostCode>";
        xml += "</LearnerContact>";
    }

    if(document.ilr.Email.value.trim()!='')
    {
        xml+= "<LearnerContact><LocType>4</LocType><ContType>2</ContType>";
        xml+= "<Email>" + document.ilr.Email.value.trim() + "</Email>";
        xml += "</LearnerContact>";
    }


    if(document.ilr.TelNumber.value.trim()!='')
    {
        xml+= "<LearnerContact><LocType>3</LocType><ContType>2</ContType>";
        xml+= "<TelNumber>" + document.ilr.TelNumber.value.trim() + "</TelNumber>";
        xml += "</LearnerContact>";
    }

    var grid_PMC = document.getElementById('grid_PMC');
    var grid_PMC_inputs = grid_PMC.getElementsByTagName('INPUT');
    for(var i = 0; i < grid_PMC_inputs.length; i++)
    {
        if(grid_PMC_inputs[i].checked)
            xml+="<ContactPreference><ContPrefType>PMC</ContPrefType><ContPrefCode>"+ grid_PMC_inputs[i].value.trim() +"</ContPrefCode></ContactPreference>";
    }

    var grid_RUI = document.getElementById('grid_RUI');
    var grid_RUI_inputs = grid_RUI.getElementsByTagName('INPUT');
    for(var i = 0; i < grid_RUI_inputs.length; i++)
    {
        if(grid_RUI_inputs[i].checked)
            xml+="<ContactPreference><ContPrefType>RUI</ContPrefType><ContPrefCode>"+ grid_RUI_inputs[i].value.trim() +"</ContPrefCode></ContactPreference>";
    }

    //Save Primary LLDDCat first
    if(typeof(document.ilr.LLDDCat1) != 'undefined' && document.ilr.LLDDCat1 != undefined && document.ilr.LLDDCat1.value.trim() != '')
    {
        xml += "<LLDDandHealthProblem>";
        xml += "<LLDDCat>" + document.ilr.LLDDCat1.value.trim() + "</LLDDCat>";
        xml += "<PrimaryLLDD>1</PrimaryLLDD>";
        xml+= "</LLDDandHealthProblem>";
    }

    for(var lldd_index = 2; lldd_index < 15; lldd_index++)
    {
        var llddcat = "document.ilr.LLDDCat" + lldd_index;
        var llddcat_value = "document.ilr.LLDDCat" + lldd_index + ".value.trim()";
        if(typeof(eval(llddcat)) != 'undefined' && eval(llddcat_value) != '')
        {
            xml += "<LLDDandHealthProblem>";
            xml += "<LLDDCat>" + eval(llddcat_value) + "</LLDDCat>";
            xml+= "</LLDDandHealthProblem>";
        }
    }

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
    if(typeof(document.ilr.SEN)!='undefined')
        if(document.ilr.SEN.options[document.ilr.SEN.selectedIndex].value!='')
            xml+="<LearnerFAM><LearnFAMType>SEN</LearnFAMType><LearnFAMCode>"+ document.ilr.SEN.options[document.ilr.SEN.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.ALS)!='undefined')
        if(document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>ALS</LearnFAMType><LearnFAMCode>"+ document.ilr.ALS.options[document.ilr.ALS.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    /*if(typeof(document.ilr.MGA)!='undefined')
     if(document.ilr.MGA.options[document.ilr.MGA.selectedIndex].value.trim()!='')
     xml+="<LearnerFAM><LearnFAMType>MGA</LearnFAMType><LearnFAMCode>"+ document.ilr.MGA.options[document.ilr.MGA.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

     if(typeof(document.ilr.EGA)!='undefined')
     if(document.ilr.EGA.options[document.ilr.EGA.selectedIndex].value.trim()!='')
     xml+="<LearnerFAM><LearnFAMType>EGA</LearnFAMType><LearnFAMCode>"+ document.ilr.EGA.options[document.ilr.EGA.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";*/

    if(typeof(document.ilr.FME)!='undefined')
        if(document.ilr.FME.options[document.ilr.FME.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>FME</LearnFAMType><LearnFAMCode>"+ document.ilr.FME.options[document.ilr.FME.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.MCF)!='undefined')
        if(document.ilr.MCF.options[document.ilr.MCF.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>MCF</LearnFAMType><LearnFAMCode>"+ document.ilr.MCF.options[document.ilr.MCF.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.ECF)!='undefined')
        if(document.ilr.ECF.options[document.ilr.ECF.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>ECF</LearnFAMType><LearnFAMCode>"+ document.ilr.ECF.options[document.ilr.ECF.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.EDF1)!='undefined')
        if(document.ilr.EDF1.options[document.ilr.EDF1.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>EDF</LearnFAMType><LearnFAMCode>"+ document.ilr.EDF1.options[document.ilr.EDF1.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.EDF2)!='undefined')
        if(document.ilr.EDF2.options[document.ilr.EDF2.selectedIndex].value.trim()!='')
            xml+="<LearnerFAM><LearnFAMType>EDF</LearnFAMType><LearnFAMCode>"+ document.ilr.EDF2.options[document.ilr.EDF2.selectedIndex].value.trim() +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.PPE1)!='undefined')
        if(document.ilr.PPE1.options[document.ilr.PPE1.selectedIndex].value!='')
            xml+="<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>"+ document.ilr.PPE1.options[document.ilr.PPE1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.PPE2)!='undefined')
        if(document.ilr.PPE2.options[document.ilr.PPE2.selectedIndex].value!='')
            xml+="<LearnerFAM><LearnFAMType>PPE</LearnFAMType><LearnFAMCode>"+ document.ilr.PPE2.options[document.ilr.PPE2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";


    if(typeof(document.ilr.NLM1)!='undefined')
        if(document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value!='')
            xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM1.options[document.ilr.NLM1.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";

    if(typeof(document.ilr.NLM2)!='undefined')
        if(document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value!='')
            xml+="<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>"+ document.ilr.NLM2.options[document.ilr.NLM2.selectedIndex].value +"</LearnFAMCode></LearnerFAM>";




    if(document.ilr.ProvSpecLearnMonA.value.trim()!='')
        xml+="<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>"+ document.ilr.ProvSpecLearnMonA.value.trim() +"</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
    if(document.ilr.ProvSpecLearnMonB.value.trim()!='')
        xml+="<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>"+ document.ilr.ProvSpecLearnMonB.value.trim() +"</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

    for(a = 1; a<=5; a++)
    {
        v1 = "document.ilr.EmpStat" + a;
        v2 = "document.ilr.EmpStat" + a + ".options[document.ilr.EmpStat" + a + ".selectedIndex].value.trim()";
        var sei = "#SEI" + a;
        var pei = "#PEI" + a;
        var ron = "#RON" + a;
        var sem = "#SEM" + a;

        if(typeof(eval(v1))!='undefined' && eval(v2)!='')
        {
            xml+="<LearnerEmploymentStatus>";
            if(typeof(eval(v1))!='undefined')
                if(eval(v2)!='')
                    xml+="<EmpStat>"+ eval(v2) +"</EmpStat>";

            v3 = "document.ilr.DateEmpStatApp" + a + ".value.trim()";
            if(eval(v3)!='')
                xml+="<DateEmpStatApp>"+ formatDate(eval(v3)) +"</DateEmpStatApp>";

            v4 = "document.ilr.EmpId" + a + ".value.trim()";
            if(eval(v4)!='')
                xml+="<EmpId>"+ eval(v4) +"</EmpId>";

            if($(sei).is(':checked'))
                xml+="<EmploymentStatusMonitoring><ESMType>SEI</ESMType><ESMCode>" + 1 + "</ESMCode></EmploymentStatusMonitoring>";

            eii = "document.ilr.EII" + a + ".options[document.ilr.EII" + a + ".selectedIndex].value.trim()";
            if(eval(eii)!='')
                xml+="<EmploymentStatusMonitoring><ESMType>EII</ESMType><ESMCode>" + eval(eii) + "</ESMCode></EmploymentStatusMonitoring>";

            lou = "document.ilr.LOU" + a + ".options[document.ilr.LOU" + a + ".selectedIndex].value.trim()";
            if(eval(lou)!='')
                xml+="<EmploymentStatusMonitoring><ESMType>LOU</ESMType><ESMCode>" + eval(lou) + "</ESMCode></EmploymentStatusMonitoring>";

            loe = "document.ilr.LOE" + a + ".options[document.ilr.LOE" + a + ".selectedIndex].value.trim()";
            if(eval(loe)!='')
                xml+="<EmploymentStatusMonitoring><ESMType>LOE</ESMType><ESMCode>" + eval(loe) + "</ESMCode></EmploymentStatusMonitoring>";

            bsi = "document.ilr.BSI" + a + ".options[document.ilr.BSI" + a + ".selectedIndex].value.trim()";
            if(eval(bsi)!='')
                xml+="<EmploymentStatusMonitoring><ESMType>BSI</ESMType><ESMCode>" + eval(bsi) + "</ESMCode></EmploymentStatusMonitoring>";

            if($(pei).is(':checked'))
                xml+="<EmploymentStatusMonitoring><ESMType>PEI</ESMType><ESMCode>" + 1 + "</ESMCode></EmploymentStatusMonitoring>";

            if($(ron).is(':checked'))
                xml+="<EmploymentStatusMonitoring><ESMType>RON</ESMType><ESMCode>" + 1 + "</ESMCode></EmploymentStatusMonitoring>";

            if($(sem).is(':checked'))
                xml+="<EmploymentStatusMonitoring><ESMType>SEM</ESMType><ESMCode>" + 1 + "</ESMCode></EmploymentStatusMonitoring>";

            xml+="</LearnerEmploymentStatus>";
        }
    }

    var LearnerHE = "";
    if(document.ilr.UCASPERID != undefined && document.ilr.UCASPERID.value.trim() != '')
        LearnerHE += "<UCASPERID>" + document.ilr.UCASPERID.value.trim() + "</UCASPERID>";
    if(document.ilr.TTACCOM != undefined && document.ilr.TTACCOM.value.trim()!='')
        LearnerHE += "<TTACCOM>" + document.ilr.TTACCOM.value.trim() + "</TTACCOM>";
    if(document.ilr.FINAMOUNT1 != undefined && document.ilr.FINAMOUNT1.value.trim()!='')
        LearnerHE += "<LearnerHEFinancialSupport><FINTYPE>1</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT1.value.trim() + "</FINAMOUNT></LearnerHEFinancialSupport>";
    if(document.ilr.FINAMOUNT2 != undefined && document.ilr.FINAMOUNT2.value.trim()!='')
        LearnerHE += "<LearnerHEFinancialSupport><FINTYPE>2</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT2.value.trim() + "</FINAMOUNT></LearnerHEFinancialSupport>";
    if(document.ilr.FINAMOUNT3 != undefined && document.ilr.FINAMOUNT3.value.trim()!='')
        LearnerHE += "<LearnerHEFinancialSupport><FINTYPE>3</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT3.value.trim() + "</FINAMOUNT></LearnerHEFinancialSupport>";
    if(document.ilr.FINAMOUNT4 != undefined && document.ilr.FINAMOUNT4.value.trim()!='')
        LearnerHE += "<LearnerHEFinancialSupport><FINTYPE>4</FINTYPE><FINAMOUNT>" + document.ilr.FINAMOUNT1.value.trim() + "</FINAMOUNT></LearnerHEFinancialSupport>";
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
                var LearningDeliveryAimReference = sf['LearnAimRef'];
                xml += "<LearningDelivery>";
                if(typeof(sf['LearnAimRef']) !='undefined' && sf['LearnAimRef'] != '')
                    xml += "<LearnAimRef>" + sf['LearnAimRef'] + "</LearnAimRef>";
                if(typeof(sf['AimType']) !='undefined' && sf['AimType'] != '')
                    xml += "<AimType>" + sf['AimType'] + "</AimType>";
                xml += "<AimSeqNumber>" + subaims + "</AimSeqNumber>";
                if(typeof(sf['LearnStartDate']) !='undefined' && sf['LearnStartDate'] != '' && sf['LearnStartDate']!='dd/mm/yyyy')
                    xml += "<LearnStartDate>" + formatDate(sf['LearnStartDate']) + "</LearnStartDate>";
                if(typeof(sf['OrigLearnStartDate']) !='undefined' && sf['OrigLearnStartDate'] != '' && sf['OrigLearnStartDate']!='dd/mm/yyyy')
                    xml += "<OrigLearnStartDate>" + formatDate(sf['OrigLearnStartDate']) + "</OrigLearnStartDate>";
                if(typeof(sf['LearnPlanEndDate']) !='undefined' && sf['LearnPlanEndDate'] != '' && sf['LearnPlanEndDate']!='dd/mm/yyyy')
                    xml += "<LearnPlanEndDate>" + formatDate(sf['LearnPlanEndDate']) + "</LearnPlanEndDate>";
                if(typeof(sf['FundModel']) !='undefined' && sf['FundModel'] != '')
                    xml += "<FundModel>" + sf['FundModel'] + "</FundModel>";
                if(typeof(sf['ProgType']) !='undefined' && sf['ProgType'] != '')
                    xml += "<ProgType>" + sf['ProgType'] + "</ProgType>";
                if(typeof(sf['FworkCode']) !='undefined' && sf['FworkCode'] != '')
                    xml += "<FworkCode>" + sf['FworkCode'] + "</FworkCode>";
                if(typeof(sf['PwayCode']) !='undefined' && sf['PwayCode'] != '')
                    xml += "<PwayCode>" + sf['PwayCode'] + "</PwayCode>";
                if(typeof(sf['PartnerUKPRN']) !='undefined' && sf['PartnerUKPRN'] != '')
                    xml += "<PartnerUKPRN>" + sf['PartnerUKPRN'] + "</PartnerUKPRN>";
                if(typeof(sf['DelLocPostCode']) !='undefined' && sf['DelLocPostCode'] != '')
                    xml += "<DelLocPostCode>" + sf['DelLocPostCode'] + "</DelLocPostCode>";
                if(typeof(sf['AddHours']) !='undefined' && sf['AddHours'] != '')
                    xml += "<AddHours>" + sf['AddHours'] + "</AddHours>";
                if(typeof(sf['PriorLearnFundAdj']) !='undefined' && sf['PriorLearnFundAdj'] != '')
                    xml += "<PriorLearnFundAdj>" + sf['PriorLearnFundAdj'] + "</PriorLearnFundAdj>";
                if(typeof(sf['OtherFundAdj']) !='undefined' && sf['OtherFundAdj'] != '')
                    xml += "<OtherFundAdj>" + sf['OtherFundAdj'] + "</OtherFundAdj>";
                if(typeof(sf['ConRefNumber']) !='undefined' && sf['ConRefNumber'] != '')
                    xml += "<ConRefNumber>" + sf['ConRefNumber'] + "</ConRefNumber>";
                if(typeof(sf['EmpOutcome']) !='undefined' && sf['EmpOutcome'] != '')
                    xml += "<EmpOutcome>" + sf['EmpOutcome'] + "</EmpOutcome>";
                if(typeof(sf['CompStatus']) !='undefined' && sf['CompStatus'] != '')
                    xml +="<CompStatus>" + sf['CompStatus'] + "</CompStatus>";
                if(typeof(sf['LearnActEndDate']) !='undefined' && sf['LearnActEndDate']!='' && sf['LearnActEndDate']!='dd/mm/yyyy')
                    xml +="<LearnActEndDate>"+ formatDate(sf['LearnActEndDate'])  +"</LearnActEndDate>";
                if(typeof(sf['WithdrawReason']) !='undefined' && sf['WithdrawReason'] != '')
                    xml +="<WithdrawReason>" + sf['WithdrawReason'] + "</WithdrawReason>";
                if(typeof(sf['Outcome']) !='undefined' && sf['Outcome'] != '')
                    xml +="<Outcome>" + sf['Outcome'] + "</Outcome>";
                if(typeof(sf['AchDate']) !='undefined' && sf['AchDate']!='' && sf['AchDate']!='dd/mm/yyyy')
                    xml +="<AchDate>"+ formatDate(sf['AchDate'])  +"</AchDate>";
                if(typeof(sf['OutGrade']) !='undefined' && sf['OutGrade'] != '')
                    xml +="<OutGrade>" + sf['OutGrade'] + "</OutGrade>";

                if(typeof(sf['SOF']) !='undefined' && sf['SOF'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>" + sf['SOF'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['FFI']) !='undefined' && sf['FFI'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>FFI</LearnDelFAMType><LearnDelFAMCode>" + sf['FFI'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(sf['WPL'])
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>WPL</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
                if(sf['FLN'])
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>FLN</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['NSA']) !='undefined' && sf['NSA'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>NSA</LearnDelFAMType><LearnDelFAMCode>" + sf['NSA'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['EEF']) !='undefined' && sf['EEF'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>EEF</LearnDelFAMType><LearnDelFAMCode>" + sf['EEF'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['LDM1']) !='undefined' && sf['LDM1'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM1'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['LDM2']) !='undefined' && sf['LDM2'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM2'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['LDM3']) !='undefined' && sf['LDM3'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM3'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['LDM4']) !='undefined' && sf['LDM4'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>LDM</LearnDelFAMType><LearnDelFAMCode>" + sf['LDM4'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['SPP']) !='undefined' && sf['SPP'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>SPP</LearnDelFAMType><LearnDelFAMCode>" + sf['SPP'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(sf['RES'])
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>RES</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
                if(sf['ADL'])
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>ADL</LearnDelFAMType><LearnDelFAMCode>1</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['ASL']) !='undefined' && sf['ASL'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>ASL</LearnDelFAMType><LearnDelFAMCode>" + sf['ASL'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['POD']) !='undefined' && sf['POD'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>POD</LearnDelFAMType><LearnDelFAMCode>" + sf['POD'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['TBS']) !='undefined' && sf['TBS'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>TBS</LearnDelFAMType><LearnDelFAMCode>" + sf['TBS'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                for(a = 1; a<=5; a++)
                {
                    v1 = sf['LSF'+a];
                    if(typeof(eval(v1))!='undefined' && eval(v1) != '')
                    {
                        xml +="<LearningDeliveryFAM><LearnDelFAMType>LSF</LearnDelFAMType><LearnDelFAMCode>" + eval(v1) + "</LearnDelFAMCode>";
                        if(typeof(sf['LSFFrom'+a]) !='undefined' && sf['LSFFrom'+a] != '' && sf['LSFFrom'+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateFrom>" + (sf['LSFFrom'+a]) + "</LearnDelFAMDateFrom>";
                        if(typeof(sf['LSFTo'+a]) !='undefined' && sf['LSFTo'+a] != '' && sf['LSFTo'+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateTo>" + formatDate(sf['LSFTo'+a]) + "</LearnDelFAMDateTo>";
                        xml +="</LearningDeliveryFAM>";
                    }
                }

                for(a = 1; a<=5; a++)
                {
                    v1 = sf['ACT'+a];
                    if(typeof(eval(v1))!='undefined' && eval(v1) != '')
                    {
                        xml +="<LearningDeliveryFAM><LearnDelFAMType>ACT</LearnDelFAMType><LearnDelFAMCode>" + eval(v1) + "</LearnDelFAMCode>";
                        if(typeof(sf['ACTFrom_'+LearningDeliveryAimReference+a]) !='undefined' && sf['ACTFrom_'+LearningDeliveryAimReference+a] != '' && sf['ACTFrom_'+LearningDeliveryAimReference+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateFrom>" + formatDate(sf['ACTFrom_'+LearningDeliveryAimReference+a]) + "</LearnDelFAMDateFrom>";
                        if(typeof(sf['ACTTo_'+LearningDeliveryAimReference+a]) !='undefined' && sf['ACTTo_'+LearningDeliveryAimReference+a] != '' && sf['ACTTo_'+LearningDeliveryAimReference+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateTo>" + formatDate(sf['ACTTo_'+LearningDeliveryAimReference+a]) + "</LearnDelFAMDateTo>";
                        xml +="</LearningDeliveryFAM>";
                    }
                }

                for(a = 1; a<=5; a++)
                {
                    v1 = sf['ALB'+a];
                    if(typeof(eval(v1))!='undefined' && eval(v1) != '')
                    {
                        xml +="<LearningDeliveryFAM><LearnDelFAMType>ALB</LearnDelFAMType><LearnDelFAMCode>" + eval(v1) + "</LearnDelFAMCode>";
                        if(typeof(sf['ALBFrom'+a]) !='undefined' && sf['ALBFrom'+a] != '' && sf['ALBFrom'+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateFrom>" + formatDate(sf['ALBFrom'+a]) + "</LearnDelFAMDateFrom>";
                        if(typeof(sf['ALBTo'+a]) !='undefined' && sf['ALBTo'+a] != '' && sf['ALBTo'+a]!='dd/mm/yyyy')
                            xml +="<LearnDelFAMDateTo>" + formatDate(sf['ALBTo'+a]) + "</LearnDelFAMDateTo>";
                        xml +="</LearningDeliveryFAM>";
                    }
                }

                if(typeof(sf['HHS1']) !='undefined' && sf['HHS1'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" + sf['HHS1'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['HHS2']) !='undefined' && sf['HHS2'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" + sf['HHS2'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['HHS3']) !='undefined' && sf['HHS3'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" + sf['HHS3'] + "</LearnDelFAMCode></LearningDeliveryFAM>";
                if(typeof(sf['HHS4']) !='undefined' && sf['HHS4'] != '')
                    xml +="<LearningDeliveryFAM><LearnDelFAMType>HHS</LearnDelFAMType><LearnDelFAMCode>" + sf['HHS4'] + "</LearnDelFAMCode></LearningDeliveryFAM>";

                for(a = 1; a<=10; a++)
                {
                    v1 = sf['WorkPlaceStartDate'+a];
                    if(typeof(v1)!='undefined' && v1 != '')
                    {
                        xml +="<LearningDeliveryWorkPlacement>";
                        xml +="<WorkPlaceStartDate>" + formatDate(v1) + "</WorkPlaceStartDate>";
                        if(typeof(sf['WorkPlaceEndDate'+a]) !='undefined' && sf['WorkPlaceEndDate'+a] != '' && sf['WorkPlaceEndDate'+a]!='dd/mm/yyyy')
                            xml +="<WorkPlaceEndDate>" + formatDate(sf['WorkPlaceEndDate'+a]) + "</WorkPlaceEndDate>";
                        if(typeof(sf['WorkPlaceMode'+a]) !='undefined' && sf['WorkPlaceMode'+a] != '')
                            xml +="<WorkPlaceMode>" + sf['WorkPlaceMode'+a] + "</WorkPlaceMode>";
                        if(typeof(sf['WorkPlaceEmpId'+a]) !='undefined' && sf['WorkPlaceEmpId'+a] != '')
                            xml +="<WorkPlaceEmpId>" + sf['WorkPlaceEmpId'+a] + "</WorkPlaceEmpId>";
                        xml +="</LearningDeliveryWorkPlacement>";
                    }
                }

                if(sf['AimType'] == '1' && (sf['ProgType'] == '25' || sf['FundModel'] == '36'))
                {
                    for(a = 1; a<=10; a++)
                    {
                        v1 = sf['TBFinType'+a];
                        if(typeof(v1)!='undefined' && v1 != '')
                        {
                            xml +="<TrailblazerApprenticeshipFinancialRecord>";
                            xml +="<TBFinType>" + v1 + "</TBFinType>";
                            if(typeof(sf['TBFinCode'+a]) !='undefined' && sf['TBFinCode'+a] != '')
                                xml +="<TBFinCode>" + sf['TBFinCode'+a] + "</TBFinCode>";
                            if(typeof(sf['TBFinDate'+a]) !='undefined' && sf['TBFinDate'+a] != '')
                                xml +="<TBFinDate>" + formatDate(sf['TBFinDate'+a]) + "</TBFinDate>";
                            if(typeof(sf['TBFinAmount'+a]) !='undefined' && sf['TBFinAmount'+a] != '')
                                xml +="<TBFinAmount>" + sf['TBFinAmount'+a] + "</TBFinAmount>";
                            xml +="</TrailblazerApprenticeshipFinancialRecord>";
                        }
                    }
                }

                if(typeof(sf['ProvSpecDelMonA']) !='undefined' && sf['ProvSpecDelMonA'] != '')
                    xml +="<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>A</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonA'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
                if(typeof(sf['ProvSpecDelMonB']) !='undefined' && sf['ProvSpecDelMonB']!='')
                    xml +="<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>B</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonB'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
                if(typeof(sf['ProvSpecDelMonC']) !='undefined' && sf['ProvSpecDelMonC']!='')
                    xml +="<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>C</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonC'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";
                if(typeof(sf['ProvSpecDelMonD']) !='undefined' && sf['ProvSpecDelMonD']!='')
                    xml +="<ProviderSpecDeliveryMonitoring><ProvSpecDelMonOccur>D</ProvSpecDelMonOccur><ProvSpecDelMon>" + sf['ProvSpecDelMonD'] + "</ProvSpecDelMon></ProviderSpecDeliveryMonitoring>";

                var learningDeliveryHE = "";
                if(sf['NUMHUS'] != undefined && sf['NUMHUS'] != '')
                    learningDeliveryHE +="\n<NUMHUS>" + sf['NUMHUS'] + "</NUMHUS>";
                if(sf['SSN'] != undefined && sf['SSN'] != '')
                    learningDeliveryHE +="\n<SSN>" + sf['SSN'] + "</SSN>";
                if(sf['QUALENT3'] != undefined && sf['QUALENT3'] != '')
                    learningDeliveryHE +="\n<QUALENT3>" + sf['QUALENT3'] + "</QUALENT3>";
                if(sf['SOC2000'] != undefined && sf['SOC2000'] != '')
                    learningDeliveryHE +="\n<SOC2000>" + sf['SOC2000'] + "</SOC2000>";
                if(sf['SEC'] != undefined && sf['SEC'] != '')
                    learningDeliveryHE +="\n<SEC>" + sf['SEC'] + "</SEC>";
                if(sf['UCASAPPID'] != undefined && sf['UCASAPPID'] != '')
                    learningDeliveryHE +="\n<UCASAPPID>" + sf['UCASAPPID'] + "</UCASAPPID>";
                if(sf['TYPEYR'] != undefined && sf['TYPEYR'] != '')
                    learningDeliveryHE +="\n<TYPEYR>" + sf['TYPEYR'] + "</TYPEYR>";
                if(sf['MODESTUD'] != undefined && sf['MODESTUD'] != '')
                    learningDeliveryHE +="\n<MODESTUD>" + sf['MODESTUD'] + "</MODESTUD>";
                if(sf['FUNDLEV'] != undefined && sf['FUNDLEV'] != '')
                    learningDeliveryHE +="\n<FUNDLEV>" + sf['FUNDLEV'] + "</FUNDLEV>";
                if(sf['FUNDCOMP'] != undefined && sf['FUNDCOMP'] != '')
                    learningDeliveryHE +="\n<FUNDCOMP>" + sf['FUNDCOMP'] + "</FUNDCOMP>";
                if(sf['STULOAD'] != undefined && sf['STULOAD'] != '')
                    learningDeliveryHE +="\n<STULOAD>" + sf['STULOAD'] + "</STULOAD>";
                if(sf['YEARSTU'] != undefined && sf['YEARSTU'] != '')
                    learningDeliveryHE +="\n<YEARSTU>" + sf['YEARSTU'] + "</YEARSTU>";
                if(sf['MSTUFEE'] != undefined && sf['MSTUFEE'] != '')
                    learningDeliveryHE +="\n<MSTUFEE>" + sf['MSTUFEE'] + "</MSTUFEE>";
                if(sf['PCOLAB'] != undefined && sf['PCOLAB'] != '')
                    learningDeliveryHE +="\n<PCOLAB>" + sf['PCOLAB'] + "</PCOLAB>";
                if(sf['PCFLDCS'] != undefined && sf['PCFLDCS'] != '')
                    learningDeliveryHE +="\n<PCFLDCS>" + sf['PCFLDCS'] + "</PCFLDCS>";
                if(sf['PCSLDCS'] != undefined && sf['PCSLDCS'] != '')
                    learningDeliveryHE +="\n<PCSLDCS>" + sf['PCSLDCS'] + "</PCSLDCS>";
                if(sf['PCTLDCS'] != undefined && sf['PCTLDCS'] != '')
                    learningDeliveryHE +="\n<PCTLDCS>" + sf['PCTLDCS'] + "</PCTLDCS>";
                if(sf['SPECFEE'] != undefined && sf['SPECFEE'] != '')
                    learningDeliveryHE +="\n<SPECFEE>" + sf['SPECFEE'] + "</SPECFEE>";
                if(sf['NETFEE'] != undefined && sf['NETFEE'] != '')
                    learningDeliveryHE +="\n<NETFEE>" + sf['NETFEE'] + "</NETFEE>";
                if(sf['GROSSFEE'] != undefined && sf['GROSSFEE'] != '')
                    learningDeliveryHE +="\n<GROSSFEE>" + sf['GROSSFEE'] + "</GROSSFEE>";
                if(sf['DOMICILE'] != undefined && sf['DOMICILE'] != '')
                    learningDeliveryHE +="\n<DOMICILE>" + sf['DOMICILE'] + "</DOMICILE>";
                if(sf['ELQ'] != undefined && sf['ELQ'] != '')
                    learningDeliveryHE +="\n<ELQ>" + sf['ELQ'] + "</ELQ>";
                if(sf['HEPostCode'] != undefined && sf['HEPostCode'] != '')
                    learningDeliveryHE +="\n<HEPostCode>" + sf['HEPostCode'] + "</HEPostCode>";

                if(learningDeliveryHE != '')
                    xml += '<LearningDeliveryHE>' + learningDeliveryHE + '</LearningDeliveryHE>';

                xml+="</LearningDelivery>";
            }
        }
    }
    xml += "</Learner>";

    xml = xml.replace('&', '&amp;');
    xml = formatXml(xml);
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
                fields[elements[i].name] = elements[i].value.trim();
            }

            if(elements[i].type == "checkbox")
            {
                fields[elements[i].name] = elements[i].checked;
            }

        }

        elements = div.getElementsByTagName('select');
        for(var i = 0; i < elements.length; i++)
        {
            fields[elements[i].name] = elements[i].value.trim();
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

/*
 $(document).ready(function() {
 $('.tooltip').tooltipster({
 contentAsHTML: true,
 animation: 'fade',
 delay: 200
 });

 // binds form submission and fields to the validation engine
 $("#ilr").validationEngine();

 });
 */


jQuery(document).ready(function () {
    // binds form submission and fields to the validation engine
    jQuery("#formID").validationEngine();
});


function numbersonly99(myfield, e, dec) {
    var key;
    var keychar;

    if (window.event)
        key = window.event.keyCode;
    else if (e)
        key = e.which;
    else
        return true;

    keychar = String.fromCharCode(key);

// To check if it goes beyond 100
    if (parseFloat(myfield.value + keychar) < 0 || parseFloat(myfield.value + keychar) > 99)
        return false;

// control keys
    if ((key == null) || (key == 0) || (key == 8) ||
        (key == 9) || (key == 13) || (key == 27))
        return true;

// numbers
    else if ((("0123456789").indexOf(keychar) > -1))
        return true;

// decimal point jump
    else if (dec && (keychar == ".")) {
        myfield.form.elements[dec].focus();
        return false;
    }
    else
        return false;

}

function PostcodeValidation(postcode) {
    if (!postcode.match(/(^gir\s0aa$)|(^[a-pr-uwyz]((\d{1,2})|([a-hk-y]\d{1,2})|(\d[a-hjks-uw])|([a-hk-y]\d[abehmnprv-y]))\s\d[abd-hjlnp-uw-z]{2}$)/i)) {
        return false;
    }
    return true;
}



function validation() {
    var mainForm = document.forms[0];
    var canvas = document.getElementById('unitCanvas');

    $('#ilr').validationEngine('validate');

    $('div#contents td').each(function () {
        $(this).css('background-color', '#FFF');
        $(this).css('border', 'solid 1px #FFF');
    });

    $('a[href^=#tab] > em.invalidated').each(function () {
        $(this).removeClass('invalidated');
        $(this).addClass('unvalidated');
    });


    // Switch on the spinning wheel
    $("#progress").show();
    var request = ajaxBuildRequestObject();
    if (request == null) {
        alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
    }
    // Place request to server
    // Submit form by AJAX
    var postData = 'id=' + document.getElementById('LearnRefNumber').value
        + '&xml=' + encodeURIComponent(toXML())
        + '&active=' + document.getElementById('active').checked
        + '&sub=' + phpSubmission
        +'&contract_id=' + phpContractId
        +'&tr_id=' + phpTrId;
    $.ajax({
        url:"do.php?_action=validate_ilr2016",
        type:"post",
        async:true,
        data:postData,
        success:function (client) {
            var tags = client.getElementsByTagName('success');
            if (tags.length > 0) {
                // If success flag, move on
                var cell = document.getElementById("report");
                if (typeof(cell) != 'undefined' && cell.hasChildNodes()) {
                    while (cell.childNodes.length >= 1) {
                        cell.removeChild(cell.firstChild);
                    }
                }
                document.getElementById('report').style.display = 'none';
                alert("No errors! This ILR form is valid");
//				custom_alert_OK_only("No errors! This ILR form is valid", "Validation");
                $("#progress").hide();
            }
            else {
                var cell = document.getElementById("report");
                if (typeof(cell) != 'undefined' && cell.hasChildNodes()) {
                    while (cell.childNodes.length >= 1) {
                        cell.removeChild(cell.firstChild);
                    }
                }

                var x = client.getElementsByTagName('error');
                var repo = document.getElementById('report');
                var i = 0;

                repo.innerHTML = "<p class='heading'>Validation Report </p>";

                for (i = 0; i < x.length; i++) {
                    er = document.createElement('p');

                    er.innerHTML = htmlspecialchars(x[i].childNodes[0].nodeValue);

                    repo.appendChild(er);
                }
                repo.style.display = "Block";
                $("#progress").hide();
            }
        },
        error:function (client) {
            alert(client.responseXML);
        }
    });

}

function randomIntFromInterval(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function internal_validation() {
    var questions_xml = "<questions>";
    var tableName = 'tbl_validation_questions';

    $('#'+tableName+' select').each(function(){
        questions_xml += '<question><q_id>' + this.id + '</q_id><q_reply>' + $(this).val() + '</q_reply></question>';
    });

    questions_xml += '</questions>';

    // Switch on the spinning wheel
    $("#progress").show();
    var request = ajaxBuildRequestObject();
    if (request == null) {
        alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
    }
    // Place request to server
    // Submit form by AJAX
    var postData = 'questions_xml=' + questions_xml
        + '&submission=' + phpSubmission
        + '&tr_id=' + phpTrId;
    $.ajax({
        url:"do.php?_action=save_ilr_internal_validation",
        type:"post",
        async:true,
        data:postData,
        //dataType: "xml",
        success:function (client) {
            alert('ILR Internal Validation Saved');
//			custom_alert_OK_only("ILR Internal Validation Saved", "Internal Validation");
            $("#progress").hide();
        },
        error:function (client) {
            alert(client.responseText);
            $("#progress").hide();
        }
    });
}

function changeL03() {

    newL03 = prompt("Enter new L03/A03", '');
    oldL03 = document.getElementById('LearnRefNumber').value;

    var oldL03 = new RegExp(oldL03, "g");

    if (newL03.length > 0) {
        xml = encodeURIComponent(toXML());
        xml = xml.replace(oldL03, newL03);

        var mainForm = document.forms[0];
        var canvas = document.getElementById('unitCanvas');


        submission = phpSubmission;
        // Submit form by AJAX (revised by Ian S-S 13th July)
        postData = 'id=' + newL03
            + '&xml=' + xml
            //	+ '&submission_date=' + document.ilr.AA.value
            + '&L01=' + ''
            + '&A09=' + ''
            + '&active=' + document.getElementById('active').checked
            + '&sub=' + phpSubmission
            + '&contract_id=' + phpContractId
            + '&tr_id=' + phpTrId;


        var client = ajaxRequest('do.php?_action=save_ilr_2016', postData);
        if (client != null) {

            // Check if the response is a success flag or an error report
            var xml = client.responseXML;
            var report = client.responseXML.documentElement;

            var tags = report.getElementsByTagName('success');
            if (tags.length > 0) {
                alert("ILR Form saved!");
//				custom_alert_OK_only("ILR form saved", "Information");
                var client = ajaxRequest('do.php?_action=change_tr_l03', postData);
                window.history.go(-1);

            }
        }
    }

}


function RUI_onclick(el)
{
    var grid = document.getElementById('grid_RUI');
    var inputs = grid.getElementsByTagName('INPUT');
    switch (el.value)
    {
        case '1':
        case '2':
            inputs[2].checked = false;
            inputs[3].checked = false;
            break;
        case '4':
            inputs[0].checked = false;
            inputs[1].checked = false;
            inputs[3].checked = false;
            break;
        case '5':
            inputs[0].checked = false;
            inputs[1].checked = false;
            inputs[2].checked = false;
            break;
    }
}



jQuery(document).ready(function ($) {
    $("#tabbed-nav").zozoTabs({
        theme:"green",
        rounded:true,
        shadows:true,
        autoContentHeight:true,
        animation:{
            easing: "easeInOutExpo",
            duration:800,
            effects:"slideV"
        }
    });
    /* jQuery activation and setting options for child tabs within docs tab*/
    $("#tabbed-nav2").zozoTabs({
        position:"top-left",
        theme:"green",
        rounded:true,
        shadows:true,
        defaultTab:"tab1",
        autoContentHeight: true,
        animation:{
            easing:"easeInOutCirc",
            effects:"slideV"
        },
        size:"medium"
    });
});



function save() {
//	return console.log(toXML());
    if (phpTemplate != 1) {

        var x = document.getElementById("ilr");
        var txt = "";
        for (var i = 0; i < x.length; i++) {
            if (x.elements[i].value != '' && x.elements[i].value != 'ZZ99 9ZZ') {
                if ((x.elements[i].id.search("PostCode") != -1) || (x.elements[i].id.search("Postcode") != -1)) {
                    if (!PostcodeValidation(x.elements[i].value)) {
                        alert(x.elements[i].id + ' \'' + x.elements[i].value + '\' is invalid.');
                        return;
                    }
                }
            }
        }
    }


    /*if(!checkDatesValidity())
     {
     return;
     }*/

    var mainForm = document.forms[0];
    var canvas = document.getElementById('unitCanvas');



    var postData = 'id=' + document.getElementById('LearnRefNumber').value
        + '&xml=' + encodeURIComponent(toXML())
        //+ '&approve=' + document.getElementById('approve').checked
        + '&active=' + document.getElementById('active').checked
        + '&sub=' + phpSubmission
        + '&contract_id=' + phpContractId
        + '&tr_id=' + phpTrId
        + '&template=' + phpTemplate;

    var client = ajaxRequest('do.php?_action=save_ilr_2016', postData);
    if (client != null) {
        // Check if the response is a success flag or an error report
        var xml = client.responseXML;
        var report = client.responseXML.documentElement;
        var tags = report.getElementsByTagName('success');
        if (tags.length > 0) {
            window.location.href = phpHref;
        }
        else {
            alert("Could not save the ILR");
        }
    }

}