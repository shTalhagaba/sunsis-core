YAHOO.namespace("am.scope");

var qual = new Array();
var oTextNodeMap = {};
var clipboard='';
var clipboardType='';
var clipboardNode='';
var tree=null;
var root=null;
var mytabs=null;
var tags = new Array();
var tagcount = 0;
var units=0;
var xml = '<root percentage="0">';
var xmltesting = "<root>";
var divCount=2;

// Get evidences through ajax
var request = ajaxBuildRequestObject();
request.open("GET", expandURI('do.php?_action=ajax_get_evidence_types'), false);
request.setRequestHeader("x-ajax", "1"); // marker for server code
request.send(null);

var arr = new Array();
arr[0] = "";
if(request.status == 200)
{
    var evidencexml = request.responseXML;
    var xmlDoc = evidencexml.documentElement;

    if(xmlDoc.tagName != 'error')
    {
        for(var i = 0; i < xmlDoc.childNodes.length; i++)
        {
            arr[i+1] = xmlDoc.childNodes[i].childNodes[0].nodeValue;
        }
    }
}

function viewEvidence(s)
{

    oCurrentTextNode = YAHOO.widget.TreeView.getNode('treeDiv1',s.id);


    YAHOO.am.scope.evidenceEditDialog.form.evidenceTitle.value=oCurrentTextNode.data.title;
    if(!(oCurrentTextNode.data.reference=='undefined' || oCurrentTextNode.data.reference=='null'))
        YAHOO.am.scope.evidenceEditDialog.form.evidenceReference.value=oCurrentTextNode.data.reference;
    if(!(oCurrentTextNode.data.portfolio=='undefined' || oCurrentTextNode.data.portfolio=='null'))
        YAHOO.am.scope.evidenceEditDialog.form.evidencePortfolio.value=oCurrentTextNode.data.portfolio;
    YAHOO.am.scope.evidenceEditDialog.form.evidenceAssessmentMethod.selectedIndex = oCurrentTextNode.data.method;
    YAHOO.am.scope.evidenceEditDialog.form.evidenceEvidenceType.selectedIndex = oCurrentTextNode.data.etype;
    YAHOO.am.scope.evidenceEditDialog.form.evidenceCategory.value = oCurrentTextNode.data.cat;
    if(!(oCurrentTextNode.data.delhours=='undefined' || oCurrentTextNode.data.delhours=='null'))
        YAHOO.am.scope.evidenceEditDialog.form.evidenceDeliveryHours.value = oCurrentTextNode.data.delhours;
    YAHOO.am.scope.evidenceEditDialog.show();
}


/*arr = new Array();
 arr[1] = "Evidence";
 arr[2] = "Observation";
 arr[3] = "Test";
 arr[4] = "Interview";
 */

function isAscii(chr)
{
    if ((('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!�$%^&*()_+[]{};:@#~,./<> "=').indexOf(chr) > -1))
        return chr;
    else
        return '';
}

function traverse(mytree)
{
    units=0; // global variable
    xml='<root percentage="0">'; // global variable
    traverserecurse(mytree);
    xml = xml.replace(/undefined/gi,'');

    return xml;
}

function traverserecurse(tree)
{
    if(tree.children.length>0)
    {

        for(var i=0; i<tree.children.length; i++)
        {
            tags[++tagcount] = "</" + tree.children[i].data.type + ">";
            if(tree.children[i].data.type=='units' || tree.children[i].data.type=='elements')
            {
                xml += '<' + tree.children[i].data.type + ' title="' + htmlspecialchars(tree.children[i].data.title) + '">' ;
            }
            if(tree.children[i].data.type=='unit')
            {

                window.units++;

                xml += '<' + tree.children[i].data.type + ' reference="' + htmlspecialchars(tree.children[i].data.reference) + '" ';

                if(tree.children[i].data.proportion==null)
                {
                    xml += 'proportion="0" ';
                }
                else
                {
                    xml += 'proportion="' + htmlspecialchars(tree.children[i].data.proportion) + '" ';
                }

                xml += 'percentage="0" mandatory="' + htmlspecialchars(tree.children[i].data.mandatory) + '" ';
                xml += 'track="' + htmlspecialchars(tree.children[i].data.track) + '" ';
                xml += 'op_title="' + htmlspecialchars(tree.children[i].data.op_title);
                xml += '" chosen="true" title="' + htmlspecialchars(tree.children[i].data.title);

                // Check if unit owner reference exists
                if(tree.children[i].data.owner_reference == null || tree.children[i].data.owner_reference == '' || tree.children[i].data.owner_reference == 'null')
                    own_ref = "Ref"+window.units;
                else
                    own_ref = tree.children[i].data.owner_reference;

                xml += '" owner_reference="' + htmlspecialchars(own_ref);

                xml += '" glh="' + htmlspecialchars(tree.children[i].data.glh);

                xml += '" credits="' + htmlspecialchars(tree.children[i].data.credits) + '">\n';

                /*	if(tree.children[i].data.description!='')
                 xml += '<description>' + tree.children[i].data.description + '</description>\n';
                 else
                 xml += '<description>' + "There is no description for this unit" + '</description>\n';
                 */
            }
            if(tree.children[i].data.type=='element')
            {
                xml += '<' + tree.children[i].data.type;

                ti = htmlspecialchars(tree.children[i].data.title);
                ti = ti.replace(/['"]/g,'');
                xml += ' title="' + htmlspecialchars(ti) + '" percentage="0">\n';

                if(tree.children[i].data.description!='')
                {
                    xml += '<description>' + htmlspecialchars(tree.children[i].data.description) + '</description>\n';
                }
                else
                {
                    xml += '<description>' + "There is no description for this element" + '</description>\n';
                }
            }

            if(tree.children[i].data.type=='evidence')
            {
                ti = htmlspecialchars(tree.children[i].data.title);
                ti = ti.replace(/['"]/g,'');
                xml += '<evidence title="' + htmlspecialchars(ti) + '" reference="' + htmlspecialchars(tree.children[i].data.reference)
                    + '" portfolio="' + htmlspecialchars(tree.children[i].data.portfolio)
                    + '" method="' + htmlspecialchars(tree.children[i].data.method)
                    + '" etype="' + htmlspecialchars(tree.children[i].data.etype)
                    + '" cat="' + htmlspecialchars(tree.children[i].data.cat)
                    + '" delhours="' + htmlspecialchars(tree.children[i].data.delhours)
                    + '" status="" comments="" vcomments="" verified="false" marks="">\n <description>';
                xml += htmlspecialchars(tree.children[i].data.description) + '</description>\n';
            }
            traverserecurse(tree.children[i]);

        }
        xml += tags[tagcount--];
    }
    else
    {
        xml += tags[tagcount--];
    }
}






function copySubTree(mytree)
{

    units=0;
    xml='<root>';
    rootType = mytree.data.type;
    if(rootType=='element')
    {
        xml += '<element title="' + mytree.data.title + '" ';
        xml += 'percentage="' + "0" + '">\n';
        if(mytree.data.description!='')
            xml += '<description>' + mytree.data.description + '</description>\n';
        else
            xml += '<description>' + "There is no description for this element" + '</description>\n';
    }

    if(rootType=='elements' || rootType=='units')
    {
        xml += '<' + rootType + ' title="' + mytree.data.title + '">' ;
    }

    if(rootType=='evidence')
    {
        xml += '<evidence title="' + mytree.data.title + '" reference="' + mytree.data.reference + '" portfolio="' + mytree.data.portfolio + '" method="' + mytree.data.method + '" etype="' + mytree.data.etype + '" cat="' + mytree.data.cat + '" delhours="' + mytree.data.delhours + '" status="" comments="" vcomments="" verified="false" marks="">\n';
        xml += '<description>' + mytree.data.description + '</description>\n';
    }

    if(rootType=='unit')
    {
        xml += '<unit reference="' + mytree.data.reference + '" ';
        if(mytree.data.proportion==null)
            xml += 'proportion="0" ';
        else
            xml += 'proportion="' + mytree.data.proportion + '" ';

        xml += 'percentage="0" mandatory="' + mytree.data.mandatory + '" chosen="true" title="' + mytree.data.title + '" track="' + mytree.data.track + '" op_title="' + mytree.data.op_title;
        xml += '" owner_reference="' + mytree.data.owner_reference + '" credits="' + mytree.data.credits + '" glh="' + mytree.data.glh + '">\n';
    }


    traverseSubTree(mytree);
    xml = xml.replace(/undefined/gi,'');
    xml += "</" + rootType + ">";
    xml += "</root>";

    return xml;
}

function traverseSubTree(tree)
{
    if(tree.children.length>0)
    {

        for(var i=0; i<tree.children.length; i++)
        {
            tags[++tagcount] = "</" + tree.children[i].data.type + ">";
            if(tree.children[i].data.type=='units' || tree.children[i].data.type=='elements')
            {
                xml += '<' + tree.children[i].data.type + ' title="' + tree.children[i].data.title + '">' ;
            }
            if(tree.children[i].data.type=='unit')
            {
                window.units++;
                xml += '<' + tree.children[i].data.type + ' reference="' + tree.children[i].data.reference + '" ';

                if(tree.children[i].data.proportion==null)
                    xml += 'proportion="' + "0" + '" ';
                else
                    xml += 'proportion="' + tree.children[i].data.proportion + '" ';

                xml += 'percentage="' + "0" + '" ';
                xml += 'title="' + tree.children[i].data.title + '" ';
                //xml += 'owner="' + tree.children[i].data.owner + '" ';
                xml += 'owner_reference="' + tree.children[i].data.owner_reference + '" credits="' + tree.children[i].data.credits + '" glh="' + tree.children[i].data.glh +'">\n';

                /*		if(tree.children[i].data.description!='')
                 xml += '<description>' + tree.children[i].data.description + '</description>\n';
                 else
                 xml += '<description>' + "There is no description for this unit" + '</description>\n';
                 */
            }
            if(tree.children[i].data.type=='element')
            {
                xml += '<' + tree.children[i].data.type;
                xml += ' title="' + tree.children[i].data.title + '" ';
                xml += 'percentage="' + "0" + '">\n';
                //xml += 'proportion="' + tree.children[i].data.proportion + '">\n';

                if(tree.children[i].data.description!='')
                    xml += '<description>' + tree.children[i].data.description + '</description>\n';
                else
                    xml += '<description>' + "There is no description for this element" + '</description>\n';
            }

            if(tree.children[i].data.type=='evidence')
            {
                xml += '<evidence title="' + tree.children[i].data.title + '" reference="' + tree.children[i].data.reference + '" portfolio="' + tree.children[i].data.portfolio + '" method="' + tree.children[i].data.method + '" etype="' + tree.children[i].data.etype + '" cat="' + tree.children[i].data.cat + '" delhours="' + tree.children[i].data.delhours + '" status="" comments="" vcomments="" verified="false" marks="">\n';
                xml += '<description>' + tree.children[i].data.description + '</description>\n';
            }
            traverseSubTree(tree.children[i]);

        }
        xml += tags[tagcount--];

    }
    else
    {
        xml += tags[tagcount--];
    }
}





function pasteSubTree(toproot)
{
    xmlUnits = loadDOM(clipboard);
    //xmlUnits = xmlobj.documentElement;
    //alert(xmlUnits);
    tags = new Array();
    tagcount = 0;
    traversePasteTree(xmlUnits, toproot);
    tree.draw();
}

function traversePasteTree(xmlUnits, parent)
{
    if(xmlUnits.hasChildNodes())
    {
        for(var i=0; i<xmlUnits.childNodes.length; i++)
        {
            groupx=null;
            if(xmlUnits.childNodes[i].tagName=='units')
            {
                myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};
                groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }

            if(xmlUnits.childNodes[i].tagName=='unit')
            {
                if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
                    prop = 0;
                else
                    prop =  xmlUnits.childNodes[i].getAttribute('proportion');

                myobj2new = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',
                    title: xmlUnits.childNodes[i].getAttribute('title'),
                    reference: xmlUnits.childNodes[i].getAttribute('reference'),
                    proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
                    credits: xmlUnits.childNodes[i].getAttribute('credits'),
                    glh: xmlUnits.childNodes[i].getAttribute('glh'),
                    owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
                    description: ''
                };

                /*	if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
                 {
                 myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
                 }
                 */
                groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }

            if(xmlUnits.childNodes[i].tagName=='elements')
            {
                myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'elements',
                    title: xmlUnits.childNodes[i].getAttribute('title'),
                    description: '' };
                groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }


            if(xmlUnits.childNodes[i].tagName=='element')
            {
                myobj2 = { label: "<div class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',
                    title: xmlUnits.childNodes[i].getAttribute('title'),
                    description: ''
                };

                if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
                {
                    myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
                }

                groupx = new YAHOO.widget.TextNode(myobj2, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }

            if(xmlUnits.childNodes[i].tagName=='evidence')
            {

                divCount++;
                //	contentBody = '';
                myobj_evidence = { label: "<div id='" + divCount + "' onclick='viewEvidence(this);' class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + xmlUnits.childNodes[i].getAttribute('reference') +  "</div></td></tr></table></div>" , type: 'evidence',
                    title: 		xmlUnits.childNodes[i].getAttribute('title').replace(/&apos;/g, "'").replace(/&quot;/g, '"'),
                    reference: 	xmlUnits.childNodes[i].getAttribute('reference'),
                    portfolio:	xmlUnits.childNodes[i].getAttribute('portfolio'),
                    method:		xmlUnits.childNodes[i].getAttribute('method'),
                    etype:		xmlUnits.childNodes[i].getAttribute('etype'),
                    cat:		xmlUnits.childNodes[i].getAttribute('cat'),
                    delhours:	xmlUnits.childNodes[i].getAttribute('delhours'),
                    status:		"",
                    comments:	"",
                    vcomments:	"",
                    verified:	"false"
                };
                groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;

            }

            tags[++tagcount] = groupx;
            traversePasteTree(xmlUnits.childNodes[i], tags[tagcount]);
        }

        parent = tags[tagcount--];
    }
    else
    {
        parent = tags[tagcount--];
    }
}




/**
 * Create a new Document object. If no arguments are specified,
 * the document will be empty. If a root tag is specified, the document
 * will contain that single root tag. If the root tag has a namespace
 * prefix, the second argument must specify the URL that identifies the
 * namespace.
 */


function treeInit()
{

    // Define various event handlers for Dialog
    var handleSubmit = function() {
        oCurrentTextNode.expand();
        alert(this.form.firstname.value);
        this.cancel();
    };


    var handleSaveEditedUnitGroup = function() {

        oCurrentTextNode.data.title = this.form.unitGroupTitle.value;
        oCurrentTextNode.data.label = "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ this.form.unitGroupTitle.value + "</div>";
        oCurrentTextNode.getLabelEl().innerHTML = "<div class='UnitGroup'><b>UNIT GROUP: </b>" + this.form.unitGroupTitle.value + "</div>";
        oCurrentTextNode.refresh();
        this.form.unitGroupTitle.value='';
        this.cancel();
    }

    var handleSaveEditedElementGroup = function() {

        oCurrentTextNode.data.title = this.form.elementGroupTitle.value;
        oCurrentTextNode.data.label = "<div class='ElementGroup'><span class=icon-doc><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</span></div>";
        oCurrentTextNode.getLabelEl().innerHTML = "<div class='ElementGroup'><span class=icon-prv><font color='DarkGreen'><b>" + this.form.elementGroupTitle.value + "</span></div>";

        oCurrentTextNode.refresh();

        this.form.elementGroupTitle.value='';
        this.cancel();
        //tree.draw();
    }

    var handleSaveUnit = function(){
        if(this.form.unitProportion.value>=0 && this.form.unitProportion.value<=100)
        {
            myobj = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ this.form.unitTitle.value + "</td><td align='right' width='1%'><div align='right'>" + this.form.unitProportion.value + "</div></td></tr></table></div>" , type: 'unit',
                title: this.form.unitTitle.value,
                reference: this.form.unitReference.value,
                owner_reference: this.form.unitOwnerReference.value,
                credits: this.form.unitCredits.value,
                glh: this.form.unitGLH.value,
                proportion: this.form.unitProportion.value,
                mandatory: this.form.mandatory.checked,
                track: this.form.track.checked,
                op_title: this.form.op_title.value
                //description: this.form.unitDescription.value
            };


            this.form.unitTitle.value='';
            this.form.unitReference.value='';
            this.form.unitProportion.value='';

            //this.form.unitOwner.value='';
            this.form.unitOwnerReference.value='';
            //this.form.unitDescription.value='';

            var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

            oCurrentTextNode.expand();
            oCurrentTextNode.refresh();
            oTextNodeMap[oChildNode.labelElId] = oChildNode;
            this.cancel();
            countGLH(root.children[0]);
            countCreditValue(root.children[0]);
        }
        else
        {
            alert("Proportion towards Qualification must be entered and must be between 0 and 100");
        }
    }

    var handleSaveEditedUnit = function() {
        if(this.form.unitProportion.value>=0 && this.form.unitProportion.value<=100)
        {
            oCurrentTextNode.data.label = "<div class='Unit'><span class=icon-dmg><font color='CornflowerBlue'><b>" + this.form.unitTitle.value + "</span><div align='right'>" + this.form.unitProportion.value + "</div></div>";
            oCurrentTextNode.data.title = this.form.unitTitle.value;
            oCurrentTextNode.data.reference = this.form.unitReference.value;
            oCurrentTextNode.data.owner_reference = this.form.unitOwnerReference.value;
            oCurrentTextNode.data.credits = this.form.unitCredits.value;
            oCurrentTextNode.data.glh = this.form.unitGLH.value;
            oCurrentTextNode.data.proportion = this.form.unitProportion.value;
            oCurrentTextNode.data.mandatory = this.form.mandatory.checked;
            oCurrentTextNode.data.track = this.form.track.checked;
            oCurrentTextNode.data.op_title = this.form.op_title.value;
            //oCurrentTextNode.data.description = this.form.unitDescription.value;

            oCurrentTextNode.getLabelEl().innerHTML = "<div class='Unit'><span class=icon-dmg><font color='CornflowerBlue'><b>" + this.form.unitTitle.value + "</span><div align='right'>" + this.form.unitProportion.value + "</div></div>";
            this.cancel();
            //tree.draw();
            countGLH(root.children[0]);
            countCreditValue(root.children[0]);
        }
        else
        {
            alert("Proportion towards Qualification must be entered and must be between 0 and 100");
        }
    }

    var handleSaveEditedElement = function() {
        oCurrentTextNode.data.label = "<div class='Element'><span class=icon-gen><font color='DarkCyan'><b>"+ this.form.elementTitle.value + "</font></span></div>";
        oCurrentTextNode.data.title = this.form.elementTitle.value;
        //oCurrentTextNode.data.reference = this.form.elementReference.value;
        //oCurrentTextNode.data.proportion = this.form.elementProportion.value;
        oCurrentTextNode.data.description = this.form.elementDescription.value;

        oCurrentTextNode.getLabelEl().innerHTML = "<span class='Element'><span class=icon-gen><font color='DarkCyan'><b>"+ this.form.elementTitle.value + "</font></span></span>" ;
        this.cancel();
    }

    var handleSaveElementGroup = function() {
        var myobj = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ this.form.elementGroupTitle.value + "</div>" , type: 'elements',
            title: this.form.elementGroupTitle.value,
            description: '' };

        var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

        oCurrentTextNode.expand();
        oCurrentTextNode.refresh();
        oTextNodeMap[oChildNode.labelElId] = oChildNode;
        this.cancel();
        //tree.draw();

    }


    var handleSaveUnitGroup = function() {
        myobj = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ this.form.unitGroupTitle.value + "</div>", title: this.form.unitGroupTitle.value , type: 'units'};
        var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

        oCurrentTextNode.expand();
        oCurrentTextNode.refresh();
        oTextNodeMap[oChildNode.labelElId] = oChildNode;
        this.cancel();
    }



    var handleSaveElement = function()
    {
        myobj = { label: "<div class='Element'><b>ELEMENT: </b>"+ this.form.elementTitle.value + "</div>" , type: 'element',
            title: this.form.elementTitle.value,
            //reference: this.form.elementReference.value,
            //proportion: this.form.elementProportion.value,
            description: this.form.elementDescription.value
        };

        var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

        oCurrentTextNode.expand();
        oCurrentTextNode.refresh();
        oTextNodeMap[oChildNode.labelElId] = oChildNode;
        this.cancel();
        //tree.draw();
    }


    var handleSaveEvidence = function() {
        //var contentBody = "<font color='black'><b>[" + arr[this.form.evidenceType.value] + "]";
        contentBody='';
        myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" ,
            type: 'evidence',
            title: this.form.evidenceTitle.value,
            reference: this.form.evidenceReference.value,
            portfolio: this.form.evidencePortfolio.value,
            method: this.form.evidenceAssessmentMethod[this.form.evidenceAssessmentMethod.selectedIndex].value,
            etype: this.form.evidenceEvidenceType[this.form.evidenceEvidenceType.selectedIndex].value,
            cat: this.form.evidenceCategory[this.form.evidenceCategory.selectedIndex].value,
            delhours: this.form.evidenceDeliveryHours.value,
            status: "",
            comments: "",
            vcomments: "",
            verified: "false"
        };

        var oChildNode = new YAHOO.widget.TextNode(myobj, oCurrentTextNode, false);

        oCurrentTextNode.expand();
        oCurrentTextNode.refresh();
        oTextNodeMap[oChildNode.labelElId] = oChildNode;
        this.cancel();
        //tree.draw();
    }

    var handleSaveEditedEvidence = function() {
        //var contentBody = "<font color='black'><b>[" + arr[this.form.evidenceType.value] + "]";
        contentBody = '';
        myobj = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',
            title: this.form.evidenceTitle.value
        };

        oCurrentTextNode.getLabelEl().innerHTML = "<div class='Evidence'><table><tr><td width='99%'><span class=icon-doc><font color='black'><b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'>" + contentBody + "</font></span></td></tr></table></div>";
        oCurrentTextNode.data.label = "<div class='Evidence'><table><tr><td width='99%'><span class=icon-doc><font color='black'><b>"+ this.form.evidenceTitle.value + "</td><td align='right' width='1%'>" + contentBody + "</font></span></td></tr></table></div>";
        oCurrentTextNode.data.title = this.form.evidenceTitle.value;
        oCurrentTextNode.data.reference = this.form.evidenceReference.value;
        oCurrentTextNode.data.portfolio = this.form.evidencePortfolio.value;
        oCurrentTextNode.data.method = this.form.evidenceAssessmentMethod[this.form.evidenceAssessmentMethod.selectedIndex].value;
        oCurrentTextNode.data.etype = this.form.evidenceEvidenceType[this.form.evidenceEvidenceType.selectedIndex].value;
        oCurrentTextNode.data.cat = this.form.evidenceCategory[this.form.evidenceCategory.selectedIndex].value;
        oCurrentTextNode.data.delhours = this.form.evidenceDeliveryHours.value;

        oCurrentTextNode.data.status = "";
        oCurrentTextNode.data.comments = "";
        oCurrentTextNode.data.vcomments = "";
        oCurrentTextNode.data.verified = "";

        this.cancel();
    }

    var handleClose = function() {
        this.cancel();
    };

    var handleCloseEvidence = function()
    {
        this.form.evidenceReference.value = '';
        this.form.evidenceTitle.value = '';
        this.form.evidencePortfolio.value = '';

        this.cancel();
    };



    var handleAddEvidence = function() {
        this.cancel();
    }

    // Instantiate the Dialog
    YAHOO.am.scope.unitEditGroupDialog = new YAHOO.widget.Dialog("unitEditGroupDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : false,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveEditedUnitGroup } ]
        } );

    YAHOO.am.scope.unitEditGroupDialog.render();

    YAHOO.am.scope.unitGroupDialog = new YAHOO.widget.Dialog("unitGroupDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : false,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveUnitGroup } ]
        } );

    YAHOO.am.scope.unitGroupDialog.render();

    YAHOO.am.scope.elementEditGroupDialog = new YAHOO.widget.Dialog("elementEditGroupDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : false,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveEditedElementGroup } ]
        } );

    YAHOO.am.scope.elementEditGroupDialog.render();


    YAHOO.am.scope.unitDialog = new YAHOO.widget.Dialog("unitDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : false,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveUnit }  ]
        } );

    YAHOO.am.scope.unitDialog.render();

    YAHOO.am.scope.unitEditDialog = new YAHOO.widget.Dialog("unitEditDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveEditedUnit }  ]
        } );

    YAHOO.am.scope.unitEditDialog.render();

    YAHOO.am.scope.elDialog = new YAHOO.widget.Dialog("elementDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveElement } ]
        } );

    YAHOO.am.scope.elDialog.render();

    YAHOO.am.scope.evidenceDialog = new YAHOO.widget.Dialog("evidenceDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Close", handler:handleCloseEvidence, isDefault:true } ,
                { text:"Save", handler:handleSaveEvidence } ]
        } );

    YAHOO.am.scope.evidenceDialog.render();

    YAHOO.am.scope.evidenceEditDialog = new YAHOO.widget.Dialog("evidenceEditDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Close", handler:handleCloseEvidence, isDefault:true } ,
                { text:"Save", handler:handleSaveEditedEvidence } ]
        } );

    YAHOO.am.scope.evidenceEditDialog.render();

    YAHOO.am.scope.elementEditDialog = new YAHOO.widget.Dialog("elementEditDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Close", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveEditedElement } ]
        } );

    YAHOO.am.scope.elementEditDialog.render();

    YAHOO.am.scope.elgrpDialog = new YAHOO.widget.Dialog("elementGroupDialog",
        {
            width: "600px",
            fixedcenter : true,
            visible : false,
            draggable: true,
            zindex: 4,
            modal: false,
            constraintoviewport : true,
            buttons : [ { text:"Cancel", handler:handleClose, isDefault:true } ,
                { text:"Save", handler:handleSaveElementGroup } ]
        } );

    YAHOO.am.scope.elgrpDialog.render();



    tree = new YAHOO.widget.TreeView("treeDiv1");


    function viewUnit()
    {
        //dialog1.form.unitTitle.value='ibrahim ok';
        //alert(dialog1.form.unitDescription);

        YAHOO.am.scope.unitEditDialog.form.unitReference.value=oCurrentTextNode.data.reference;
        YAHOO.am.scope.unitEditDialog.form.unitProportion.value=oCurrentTextNode.data.proportion;
        //YAHOO.am.scope.unitEditDialog.form.unitOwner.value=oCurrentTextNode.data.owner;
        YAHOO.am.scope.unitEditDialog.form.unitOwnerReference.value=oCurrentTextNode.data.owner_reference;
        YAHOO.am.scope.unitEditDialog.form.unitCredits.value=oCurrentTextNode.data.credits;
        YAHOO.am.scope.unitEditDialog.form.unitGLH.value=oCurrentTextNode.data.glh;
        YAHOO.am.scope.unitEditDialog.form.unitTitle.value=oCurrentTextNode.data.title;
        //YAHOO.am.scope.unitEditDialog.form.unitDescription.value=oCurrentTextNode.data.description;
        if(oCurrentTextNode.data.mandatory=='true' || oCurrentTextNode.data.mandatory==true)
            YAHOO.am.scope.unitEditDialog.form.mandatory.checked = true;
        else
            YAHOO.am.scope.unitEditDialog.form.mandatory.checked = false;
        if(oCurrentTextNode.data.track=='true' || oCurrentTextNode.data.track==true)
            YAHOO.am.scope.unitEditDialog.form.track.checked = true;
        else
            YAHOO.am.scope.unitEditDialog.form.track.checked = false;
        YAHOO.am.scope.unitEditDialog.form.op_title.value=oCurrentTextNode.data.op_title;
        YAHOO.am.scope.unitEditDialog.show();
    }


    function viewUnitGroup()
    {

        YAHOO.am.scope.unitEditGroupDialog.form.unitGroupTitle.value=oCurrentTextNode.data.title;
        YAHOO.am.scope.unitEditGroupDialog.show();

    }

    function viewElementGroup()
    {

        YAHOO.am.scope.elementEditGroupDialog.form.elementGroupTitle.value=oCurrentTextNode.data.title;
        YAHOO.am.scope.elementEditGroupDialog.show();

    }

    function addUnit()
    {
        YAHOO.am.scope.unitDialog.show();
    }


    function viewElement()
    {
        //dialog1.form.unitTitle.value='ibrahim ok';
        //alert(dialog1.form.unitDescription);

        //YAHOO.am.scope.elementEditDialog.form.elementReference.value=oCurrentTextNode.data.reference;
        YAHOO.am.scope.elementEditDialog.form.elementTitle.value= oCurrentTextNode.data.title;
        //YAHOO.am.scope.elementEditDialog.form.elementProportion.value=oCurrentTextNode.data.proportion;
        YAHOO.am.scope.elementEditDialog.form.elementDescription.value=oCurrentTextNode.data.description;

        YAHOO.am.scope.elementEditDialog.show();
    }


    function addElementGroup()
    {
        YAHOO.am.scope.elgrpDialog.form.elementGroupTitle.value= '';
        YAHOO.am.scope.elgrpDialog.show();
    }

    function addUnitGroup()
    {
        YAHOO.am.scope.unitGroupDialog.form.unitGroupTitle.value= '';
        YAHOO.am.scope.unitGroupDialog.show();
    }

    function addElement()
    {
        //YAHOO.am.scope.elDialog.form.elementReference.value='';
        YAHOO.am.scope.elDialog.form.elementTitle.value= '';
        //YAHOO.am.scope.elDialog.form.elementProportion.value='';
        YAHOO.am.scope.elDialog.form.elementDescription.value='';
        YAHOO.am.scope.elDialog.show();
    }

    function viewEvidence()
    {

        YAHOO.am.scope.evidenceEditDialog.form.evidenceTitle.value=oCurrentTextNode.data.title.replace("&gt;",">").replace("&lt;","<");
        if(!(oCurrentTextNode.data.reference=='undefined' || oCurrentTextNode.data.reference=='null'))
            YAHOO.am.scope.evidenceEditDialog.form.evidenceReference.value=oCurrentTextNode.data.reference;
        if(!(oCurrentTextNode.data.portfolio=='undefined' || oCurrentTextNode.data.portfolio=='null'))
            YAHOO.am.scope.evidenceEditDialog.form.evidencePortfolio.value=oCurrentTextNode.data.portfolio;
        YAHOO.am.scope.evidenceEditDialog.form.evidenceAssessmentMethod.selectedIndex = oCurrentTextNode.data.method;
        YAHOO.am.scope.evidenceEditDialog.form.evidenceEvidenceType.selectedIndex = oCurrentTextNode.data.etype;
        YAHOO.am.scope.evidenceEditDialog.form.evidenceCategory.value = oCurrentTextNode.data.cat;
        if(!(oCurrentTextNode.data.delhours=='undefined' || oCurrentTextNode.data.delhours=='null'))
            YAHOO.am.scope.evidenceEditDialog.form.evidenceDeliveryHours.value = oCurrentTextNode.data.delhours;
        YAHOO.am.scope.evidenceEditDialog.show();
    }

    function addEvidence()
    {
        YAHOO.am.scope.evidenceDialog.form.evidenceTitle.value='';
        //YAHOO.am.scope.evidenceDialog.form.evidenceType.value='';
        YAHOO.am.scope.evidenceDialog.show();
    }

    function deleteAnything()
    {
        delete oTextNodeMap[oCurrentTextNode.labelElId];
        tree.removeNode(oCurrentTextNode);
        tree.draw();
        countGLH(root.children[0]);
        countCreditValue(root.children[0]);
    }


    // Create the context menu for the tree
    function addNode() {
        var sLabel = window.prompt("Enter a label for the new node: " + oCurrentTextNode.data.customData, ""),
            oChildNode;

        if (sLabel && sLabel.length > 0) {
            oChildNode = new YAHOO.widget.TextNode(sLabel, oCurrentTextNode, false);

            oCurrentTextNode.expand();
            oCurrentTextNode.refresh();

            oTextNodeMap[oChildNode.labelElId] = oChildNode;
            tree.draw();
        }
    }


    function editNodeLabel() {

        var sLabel = window.prompt("Enter a new label for this node: ", oCurrentTextNode.getLabelEl().innerHTML);

        if (sLabel && sLabel.length > 0) {

            oCurrentTextNode.getLabelEl().innerHTML = sLabel;

        }

    }

    function copyNode()
    {
        clipboardType	=	oCurrentTextNode.data.type;
        clipboard 		= 	copySubTree(oCurrentTextNode);
        clipboardNode='';

        var postData = 'clipboardType=' + oCurrentTextNode.data.type
            + '&clipboard=' + clipboard
            + '&clipboardNode=' + '';

        var client = ajaxRequest('do.php?_action=copy_subtree', postData);

    }

    function cutNode()
    {
        clipboardType=oCurrentTextNode.data.type;
        clipboard = copySubTree(oCurrentTextNode);
        clipboardNode = oCurrentTextNode;

        // Submit form by AJAX (revised by Ian S-S 13th July)
        var postData = 'clipboardType=' + oCurrentTextNode.data.type
            + '&clipboard=' + clipboard
            + '&clipboardNode=' + '';

        var client = ajaxRequest('do.php?_action=copy_subtree', postData);
    }

    function pasteNode()
    {

        if(clipboardNode!='')
        {
            tree.removeNode(clipboardNode);
            clipboardNode='';
        }

        // Submit form by AJAX (revised by Ian S-S 13th July)
        var client = ajaxRequest('do.php?_action=paste_subtree', '');
        if(client != null)
            clipboard = client.responseText;


        var client = ajaxRequest('do.php?_action=get_clipboard_type', '');
        if(client != null)
            clipboardType = client.responseText;

        if(clipboardType=='evidence' && oCurrentTextNode.data.type=='element')
            pasteSubTree(oCurrentTextNode);
        else
        if(clipboardType=='element' && (oCurrentTextNode.data.type=='elements' || oCurrentTextNode.data.type=='unit'))
            pasteSubTree(oCurrentTextNode);
        else
        if(clipboardType=='elements' && (oCurrentTextNode.data.type=='elements' || oCurrentTextNode.data.type=='unit'))
            pasteSubTree(oCurrentTextNode);
        else
        if(clipboardType=='unit' && (oCurrentTextNode.data.type=='units' || oCurrentTextNode.data.type=='root'))
            pasteSubTree(oCurrentTextNode);
        else
        if(clipboardType=='units' && (oCurrentTextNode.data.type=='units' || oCurrentTextNode.data.type=='root'))
            pasteSubTree(oCurrentTextNode);
        else
            alert("Cannot paste ");
    }


    function deleteNode() {

        delete oTextNodeMap[oCurrentTextNode.labelElId];

        tree.removeNode(oCurrentTextNode);
        tree.draw();

    }



    oContextMenu = new YAHOO.widget.ContextMenu("mytreecontextmenu", {
        trigger: "treeDiv1",
        lazyload: true, itemdata: [

        ] });


    oContextMenu.triggerContextMenuEvent.subscribe(onTriggerContextMenu);


    /*
     "contextmenu" event handler for the element(s) that triggered the display of the context menu
     */
    function onTriggerContextMenu(p_oEvent) {


        /*
         Returns a TextNode instance that corresponds to the DOM
         element whose "contextmenu" event triggered the display
         of the context menu.
         */

        function GetTextNodeFromEventTarget(p_oTarget) {

            if (p_oTarget.tagName.toUpperCase() == "A" && p_oTarget.className == "ygtvlabel") {

                return oTextNodeMap[p_oTarget.id];

            }
            else {

                if (p_oTarget.parentNode && p_oTarget.parentNode.nodeType == 1) {

                    return GetTextNodeFromEventTarget(p_oTarget.parentNode);

                }

            }

        }

        var oTextNode = GetTextNodeFromEventTarget(this.contextEventTarget);

        if (oTextNode)
        {
            oCurrentTextNode = oTextNode;
            oContextMenu.clearContent();
            if (oTextNode.data.type == 'unit')
            {

                oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Unit');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnit});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Add Element Group');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addElementGroup});
                oContextMenu.getItem(2).cfg.setProperty("text", 'Add Element');
                oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addElement});
                oContextMenu.getItem(3).cfg.setProperty("text", 'Delete this Unit');
                oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});

                oContextMenu.render('treeDiv1');
            }
            else if (oTextNode.data.type == 'elements')
            {
                oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Element Group');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElementGroup});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Add Element Group');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addElementGroup});
                oContextMenu.getItem(2).cfg.setProperty("text", 'Add Element');
                oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addElement});
                oContextMenu.getItem(3).cfg.setProperty("text", 'Delete Element Group');
                oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});
                oContextMenu.render('treeDiv1');
            }
            else if (oTextNode.data.type == 'units')
            {
                oContextMenu.addItems(["placeholder1","placeholder2","placeholder3","placeholder4"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Unit Group');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewUnitGroup});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Add Unit Group');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addUnitGroup});
                oContextMenu.getItem(2).cfg.setProperty("text", 'Add Unit');
                oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: addUnit});
                oContextMenu.getItem(3).cfg.setProperty("text", 'Delete this Unit Group');
                oContextMenu.getItem(3).cfg.setProperty("onclick", {fn: deleteAnything});
                oContextMenu.render('treeDiv1');
            }
            else if (oTextNode.data.type == 'element')
            {
                oContextMenu.addItems(["placeholder1","placeholder2","placeholder3"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Element');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewElement});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Add Evidence Requirement');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addEvidence});
                oContextMenu.getItem(2).cfg.setProperty("text", 'Delete Element');
                oContextMenu.getItem(2).cfg.setProperty("onclick", {fn: deleteAnything});
                oContextMenu.render('treeDiv1');
            }
            else if (oTextNode.data.type == 'evidence')
            {

                oContextMenu.addItems(["placeholder1","placeholder2"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'View / Edit Evidence');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: viewEvidence});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Delete');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: deleteAnything});

                oContextMenu.render('treeDiv1');
            }
            else if (oTextNode.data.type == 'root')
            {
                oContextMenu.addItems(["placeholder1", "placeholder2"]);

                oContextMenu.getItem(0).cfg.setProperty("text", 'Add Unit Group');
                oContextMenu.getItem(0).cfg.setProperty("onclick", {fn: addUnitGroup});
                oContextMenu.getItem(1).cfg.setProperty("text", 'Add Unit');
                oContextMenu.getItem(1).cfg.setProperty("onclick", {fn: addUnit});
                oContextMenu.render('treeDiv1');
            }

        }
        else
        {
            this.cancel();
        }

    }

    getData();
    if(root){
        countGLH(root.children[0]);
        countCreditValue(root.children[0]);
    }
}


function getData()
{
    // Select the root group element in the unit structure
    var mainForm = document.forms[0];
    // Attempt to load qualification

    if(mainForm.elements['id'].value!='')
    {

        postData = 'id=' + encodeURIComponent(__qualificationId)
            + '&internaltitle=' + encodeURIComponent(__internalTitle);
        var request = ajaxRequest('do.php?_action=ajax_get_qualification_xml', postData);


        if(request.status == 200)
        {
            var xml = request.responseXML;
            var xmlDoc = xml.documentElement;

            if(xmlDoc.tagName != 'error')
            {
                populateFields(xml);
            }
            else
            {
                delete tree;
                tree = new YAHOO.widget.TreeView("treeDiv1");
                root = tree.getRoot();

                myobjx = { label: "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + "</div>", title: 'root', type: 'root'};
                toproot= new YAHOO.widget.TextNode(myobjx, root, false);
                oTextNodeMap[toproot.labelElId]=toproot;
                tree.draw();
            }
        }
        else
        {
            ajaxErrorHandler(request);
        }
    }

    myTabs = new YAHOO.widget.TabView("demo");
}

YAHOO.util.Event.onDOMReady(treeInit);



var elements_counter = 0;
var oldReference = '';
var unitTitleElement = '';



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

    if(objID.value!='')
    {
        getData();
    }
}


/**
 * Translate the whole form into XML
 */
function toXML()
{
    var mainForm = document.forms[0];
    var levelGrid = document.getElementById('grid_level');
//	var performanceFigures = document.getElementById('table_performance_figures');
//	var canvas = document.getElementById('unitCanvas');

    var xml = '<qualification ';
    xml += 'title="' + htmlspecialchars(forceASCII(mainForm.elements['title'].value)) + '" ';
    xml += 'internaltitle="' + htmlspecialchars(forceASCII(mainForm.elements['internaltitle'].value)) + '" ';
    xml += 'lsc_learning_aim="' + htmlspecialchars(forceASCII(mainForm.elements['lsc_learning_aim'].value)) + '" ';
    xml += 'type="' + htmlspecialchars(forceASCII(mainForm.elements['qualification_type'].value)) + '" ';
    xml += 'level="' + htmlspecialchars(forceASCII(levelGrid.getValues().join(','))) + '" ';
    xml += 'reference="' + htmlspecialchars(forceASCII(mainForm.elements['id'].value.replace(/ /g, '')) ) + '" ';
    xml += 'awarding_body="' + htmlspecialchars(forceASCII(mainForm.elements['awarding_body'].value)) + '" ';
    xml += 'guided_learning_hours="' + htmlspecialchars(forceASCII(mainForm.elements['guided_learning_hours'].value)) + '" ';
    xml += 'total_credit_value="' + htmlspecialchars(forceASCII(mainForm.elements['total_credit_value'].value)) + '" ';
    xml += 'regulation_start_date="' + formatDateW3C(stringToDate(mainForm.elements['regulation_start_date'].value)) + '" ';
    xml += 'operational_start_date="' + formatDateW3C(stringToDate(mainForm.elements['operational_start_date'].value)) + '" ';
    xml += 'operational_end_date="' + formatDateW3C(stringToDate(mainForm.elements['operational_end_date'].value)) + '" ';
    xml += 'certification_end_date="' + formatDateW3C(stringToDate(mainForm.elements['certification_end_date'].value)) + '" ';
    xml += '>';
    xml += '<description>' + htmlspecialchars(forceASCII(mainForm.elements['description'].value)) + '</description>';
    xml += '<assessment_method>' + htmlspecialchars(forceASCII(mainForm.elements['assessment_method'].value)) + '</assessment_method>';
    xml += '<mainarea>' + htmlspecialchars(forceASCII(mainForm.elements['mainarea'].value)) + '</mainarea>';
    xml += '<subarea>' + htmlspecialchars(forceASCII(mainForm.elements['subarea'].value)) + '</subarea>';

//	xml += performanceFigures.toXML();
    // xml += canvas.toXML();

    xml += '</qualification>';

    return xml;


}


function loadFieldsFromNDAQ(filled)
{


    if(!confirm('All fields, performance figures and units will be replaced with data from the QCA.  Depending on the size of the qualification, this process can take up to a minute.  Continue?'))
    {
        return false;
    }

    // Switch on the globes
    var globe1 = document.getElementById('globe1');
    var globe2 = document.getElementById('globe2');
    var globe3 = document.getElementById('globe3');
//	var globe4 = document.getElementById('globe4');
//	var globe5 = document.getElementById('globe5');
    document.getElementById('savebutton').disabled = true;
    globe1.style.visibility = 'visible';
    globe2.style.visibility = 'visible';
    globe3.style.visibility = 'visible';
//	globe4.style.visibility = 'visible';
//	globe5.style.visibility = 'visible';

    var myForm = document.forms[0];
    var id = myForm.elements['id'];

    if(id.value == '')
    {
        alert("You need to enter a QCA reference number before you can import data for the qualification");
        id.focus();
        return false;
    }

//	var client = ajaxRequest('do.php?_action=ajax_ndaq_import_qualification&options=2&id=' + encodeURIComponent(id.value));
//	alert(client.responseText);

    var request = ajaxBuildRequestObject();
    if(request == null)
    {
        alert("Could not create XMLHTTPRequest object in order to connect to the Sunesis server");
    }
    // Place request to server
    var url = expandURI('do.php?_action=ajax_ndaq_import_qualification&options=2&id=' + encodeURIComponent(id.value));
    request.open("GET", url, true); // (method, uri, synchronous)
    request.onreadystatechange = function(e){
        if(request.readyState == 4){
            if(request.status == 200)
            {
                // DEBUG
                //var debug = document.getElementById('debug');
                //debug.innerHTML = request.responseText;
                var xmlDoc = request.responseXML;
                populateFields(xmlDoc, filled);
            }
            else
            {
                ajaxErrorHandler(request);
            }
            // Switch off globes
            globe1.style.visibility = 'hidden';
            globe2.style.visibility = 'hidden';
            globe3.style.visibility = 'hidden';
            document.getElementById('savebutton').disabled = false;
        }
    }

    request.setRequestHeader("x-ajax", "1"); // marker for server code
    request.send(null); // post data


}






function populateFields(xmlDoc, filled)
{
    var myForm = document.forms[0];
    var xmlQual = xmlDoc.documentElement;

    // Edexcel only check
    try
    {
        if(xmlQual.getAttribute('awarding_body') != null && ( xmlQual.getAttribute('awarding_body').search(/Pearson/i)<0 && (xmlQual.getAttribute('awarding_body').search(/edexcel/i)<0) ) && __dbName == 'am_edexcel')
        {
            alert("This request cannot be completed because you are restricted to downloading Edexcel qualifications only");
            return 0;
        }
    }
    catch(err)
    {
        alert("There is a technical issue with downloading this qualification. Please contact support for assistance 0121 5069667.");
        return 0;
    }
//	if(xmlQual.getAttribute('awarding_body') == null)
//	{
//		alert("There is a technical issue with downloading this qualification. Please contact support for assistance 0121 5069667.");
//		return 0;
//	}

    // Classification fields
    myForm.elements['awarding_body'].value = xmlQual.getAttribute('awarding_body');
    //myForm.elements['awarding_body'].disabled = 'true';
    myForm.elements['title'].value = xmlQual.getAttribute('title');
    myForm.elements['internaltitle'].value = xmlQual.getAttribute('internaltitle');

    if(myForm.elements['internaltitle'].value=='' || myForm.elements['internaltitle'].value=='null' || myForm.elements['internaltitle'].value==null)
        myForm.elements['internaltitle'].value = xmlQual.getAttribute('title');

    myForm.elements['internaltitle'].value = myForm.elements['internaltitle'].value.replace("&","and");
    myForm.elements['title'].value = myForm.elements['title'].value.replace("&","and");

    myForm.elements['qualification_type'].value = xmlQual.getAttribute('type');
    myForm.elements['guided_learning_hours'].value = xmlQual.getAttribute('guided_learning_hours');
    myForm.elements['total_credit_value'].value = xmlQual.getAttribute('total_credit_value');
    myForm.elements['lsc_learning_aim'].value = xmlQual.getAttribute('lsc_learning_aim');

    var grid_level = document.getElementById('grid_level');
    grid_level.clear();

    // added in to handle unit download - causing presentation issue
    // requires further investigations RE 01/09/2011
    if(xmlQual.getAttribute('level')!='undefined' && xmlQual.getAttribute('level')!=undefined && xmlQual.getAttribute('level')!='null' && xmlQual.getAttribute('level')!=null) {
        // ---
        grid_level.setValues(xmlQual.getAttribute('level').split(','));
    }
    // grid_level.setValues(xmlQual.getAttribute('level').split(','));
    // ---


    myForm.elements['mainarea'].value = xmlQual.getAttribute('mainarea');
    myForm.elements['subarea'].value = xmlQual.getAttribute('subarea');
    // Date fields
    var accredStart = stringToDate(xmlQual.getAttribute('regulation_start_date'));
    var opStart = stringToDate(xmlQual.getAttribute('operational_start_date'));
    var accredEnd = stringToDate(xmlQual.getAttribute('operational_end_date'));
    var certEnd = stringToDate(xmlQual.getAttribute('certification_end_date'));


    myForm.elements['regulation_start_date'].value = formatDateGB(accredStart);
    myForm.elements['operational_start_date'].value = formatDateGB(opStart);
    myForm.elements['operational_end_date'].value = formatDateGB(accredEnd);
    myForm.elements['certification_end_date'].value = formatDateGB(certEnd);

    // Descriptive fields
    var desc1 = xmlQual.getElementsByTagName('structure_requirements')[0];
    var desc2 = xmlQual.getElementsByTagName('description')[0];
    var assess = xmlQual.getElementsByTagName('assessment_method')[0];
//	var struct = xmlQual.getElementsByTagName('structure')[0];
    // added in to handle unit download - causing presentation issue
    // requires further investigations RE 01/09/2011
    if( typeof desc1 != 'undefined' && desc1 != null )
    {
        if(desc1.firstChild)
        {
            myForm.elements['description'].value = desc1.firstChild.nodeValue;
        }
    }

    if(typeof desc2 != 'undefined' && desc2 != null)
    {
        if(desc2.firstChild)
        {
            myForm.elements['description'].value = desc2.firstChild.nodeValue;
        }
    }

    // added in to handle unit download - causing presentation issue
    // requires further investigations RE 01/09/2011
    if(typeof assess != 'undefined' && assess != null) {
        // ---
        if(assess.firstChild)
        {
            myForm.elements['assessment_method'].value = assess.firstChild.nodeValue;
        }
    }


//	if(assess.firstChild) {
//		myForm.elements['assessment_method'].value = assess.firstChild.nodeValue;
//	}
    // ---

    // Units
    // Locate the <units> tag under <qualification>.  Because of the limitations
    // of XPATH under IE, we will use a simple loop to locate it.
    var xmlUnits = null;
    var t;

    for(var i = 0; i < xmlQual.childNodes.length; i++)
    {
        if(xmlQual.childNodes[i].tagName == 'root')
        {
            xmlUnits = xmlQual.childNodes[i];

            break;
        }
    }

    //alert(xmlUnits);
    if(xmlUnits != null)
    {
        delete tree;
        tree = new YAHOO.widget.TreeView("treeDiv1");
        root = tree.getRoot();
        myobjx = { label: "<div class='Root'>QUALIFICATION: " + document.forms[0].elements['title'].value + "</div>", title: 'root', type: 'root'};
        toproot= new YAHOO.widget.TextNode(myobjx, root, false);
        oTextNodeMap[toproot.labelElId]=toproot;

        showTree(xmlUnits, toproot, filled);
        /*		for(t=0;t<xmlUnits.childNodes.length;t++)
         {
         //alert(xmlUnits.childNodes[t].nodeName);
         if (xmlUnits.childNodes[t].tagName == 'units')
         newgenerateTree(xmlUnits.childNodes[t],toproot);
         }
         */
    }


}


function showTree(xmlUnits, toproot, filled)
{
    tags = new Array();
    tagcount = 0;
    traverseShowTree(xmlUnits, toproot, filled);
    tree.draw();
    //tree.expandAll();
}

function traverseShowTree(xmlUnits, parent, filled)
{
    var groupx = '';
    if(xmlUnits.hasChildNodes())
    {
        for(var i=0; i<xmlUnits.childNodes.length; i++)
        {
            if(xmlUnits.childNodes[i].tagName=='units')
            {
                divCount++;
                myobj2new = { label: "<div id='" + divCount + "' class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" ,title: xmlUnits.childNodes[i].getAttribute('title'), type: 'units'};

                groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;

                parent.expand();
                groupx.expand();

            }

            if(xmlUnits.childNodes[i].tagName=='unit')
            {
                divCount++;
                if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
                    prop = 0;
                else
                    prop =  xmlUnits.childNodes[i].getAttribute('proportion');

                myobj2new = { label: "<div id='" + divCount + "' class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',

                    title: xmlUnits.childNodes[i].getAttribute('title').replace(/&apos;/g, "'").replace(/&quot;/g, '"'),
                    reference: xmlUnits.childNodes[i].getAttribute('reference'),
                    proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
                    mandatory: xmlUnits.childNodes[i].getAttribute('mandatory'),
                    track: xmlUnits.childNodes[i].getAttribute('track'),
                    op_title: xmlUnits.childNodes[i].getAttribute('op_title'),
                    owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
                    credits: xmlUnits.childNodes[i].getAttribute('credits'),
                    glh: xmlUnits.childNodes[i].getAttribute('glh'),
                    description: ''
                };

                groupx = new YAHOO.widget.TextNode(myobj2new, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;


                // Shove elements
                if(filled == 1)
                {
                    divCount++;
                    myobj2 = { label: "<div id='" + divCount + "' class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',

                        title: xmlUnits.childNodes[i].getAttribute('title'),
                        description: ''
                    };

//					if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
//					{
//						myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
//					}

                    groupx = new YAHOO.widget.TextNode(myobj2, groupx, false);
                    oTextNodeMap[groupx.labelElId]=groupx;

                    // Shove evidences
                    divCount++;
                    contentBody = '';
                    myobj_evidence = { label: "<div id='" + divCount + "' onclick='viewEvidence(this);' class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + "" +  "</div></td></tr></table></div>" , type: 'evidence',
                        title: 		xmlUnits.childNodes[i].getAttribute('title').replace(/&apos;/g, "'").replace(/&quot;/g, '"'),
                        reference: 	"",
                        portfolio:	"",
                        method:		"",
                        etype:		"",
                        cat:		"",
                        delhours:	"",
                        status:		"",
                        comments:	"",
                        vcomments:	"",
                        verified:	"false"
                    };
                    groupx = new YAHOO.widget.TextNode(myobj_evidence, groupx, false);
                    oTextNodeMap[groupx.labelElId]=groupx;

                }
                /* 	      		else
                 {
                 if(xmlUnits.childNodes[i].tagName=='element')
                 {
                 divCount++;
                 myobj2 = { label: "<div id='" + divCount + "' class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',

                 title: xmlUnits.childNodes[i].getAttribute('title'),
                 description: ''
                 };

                 if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
                 {
                 myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
                 }

                 groupx = new YAHOO.widget.TextNode(myobj2, groupx, false);
                 oTextNodeMap[groupx.labelElId]=groupx;
                 }
                 }
                 */
            }

            if(xmlUnits.childNodes[i].tagName=='elements')
            {
                divCount++;
                myobj3 = { label: "<div id='" + divCount + "' class='ElementGroup'><b>ELEMENT GROUP: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'elements',
                    title: xmlUnits.childNodes[i].getAttribute('title'),
                    description: '' };
                groupx = new YAHOO.widget.TextNode(myobj3, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }


            if(xmlUnits.childNodes[i].tagName=='element')
            {
                divCount++;
                myobj2 = { label: "<div id='" + divCount + "' class='Element'><b>ELEMENT: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</div>" , type: 'element',

                    title: xmlUnits.childNodes[i].getAttribute('title'),
                    description: ''
                };

                if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
                {
                    myobj2.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
                }

                groupx = new YAHOO.widget.TextNode(myobj2, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;
            }

            if(xmlUnits.childNodes[i].tagName=='evidence')
            {

                divCount++;
                //var contentBody = "[" + arr[xmlUnits.childNodes[i].getAttribute('type')] + "]";
                contentBody = '';
                //myobj_evidence = { label: "<div id='" + divCount + "' onclick='viewEvidence(this);' class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',
                myobj_evidence = { label: "<div id='" + divCount + "' onclick='viewEvidence(this);' class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + xmlUnits.childNodes[i].getAttribute('reference') +  "</div></td></tr></table></div>" , type: 'evidence',
                    title: 		xmlUnits.childNodes[i].getAttribute('title').replace(/&apos;/g, "'").replace(/&quot;/g, '"'),
                    reference: 	xmlUnits.childNodes[i].getAttribute('reference'),
                    portfolio:	xmlUnits.childNodes[i].getAttribute('portfolio'),
                    method:		xmlUnits.childNodes[i].getAttribute('method'),
                    etype:		xmlUnits.childNodes[i].getAttribute('etype'),
                    cat:		xmlUnits.childNodes[i].getAttribute('cat'),
                    delhours:	xmlUnits.childNodes[i].getAttribute('delhours'),
                    status:		"",
                    comments:	"",
                    vcomments:	"",
                    verified:	"false"
                };
                groupx = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
                oTextNodeMap[groupx.labelElId]=groupx;

            }

            tags[++tagcount] = groupx;
            traverseShowTree(xmlUnits.childNodes[i], tags[tagcount], filled);
        }

        parent = tags[tagcount--];
    }
    else
    {
        parent = tags[tagcount--];
    }
}


function newgenerateTree(xmlUnits,parent)
{
    var myobj2new;

    if ( xmlUnits.tagName == 'units' )
        myobj2new = { label: "<div class='UnitGroup'><b>UNIT GROUP: </b>"+ xmlUnits.getAttribute('title') + "</div>" ,title: xmlUnits.getAttribute('title'), type: 'units'};
    else
        myobj2new = { label: "<span class=icon-ppt><font color='red'>"+ xmlUnits.getAttribute('title') + "</font></span>" , type: 'unit',
            title: xmlUnits.getAttribute('title'),
            reference: xmlUnits.getAttribute('reference'),
            proportion: xmlUnits.getAttribute('proportion'),
            credits: xmlUnits.getAttribute('credits'),
            glh: xmlUnits.getAttribute('glh'),
            owner_reference: xmlUnits.getAttribute('owner_reference'),
            description: ''
        };


    groupx= new YAHOO.widget.TextNode(myobj2new, parent, false);
    oTextNodeMap[groupx.labelElId]=groupx;

    for(var i = 0; i < xmlUnits.childNodes.length; i++)
    {
        if(xmlUnits.childNodes[i].tagName == 'units')
        {
            newgenerateTree(xmlUnits.childNodes[i],groupx);

        }
        else if(xmlUnits.childNodes[i].tagName == 'unit')
        {

            if(xmlUnits.childNodes[i].getAttribute('proportion')==null || xmlUnits.childNodes[i].getAttribute('proportion')=='null')
                prop = 0;
            else
                prop =  xmlUnits.childNodes[i].getAttribute('proportion');

            myobj2new = { label: "<div class='Unit'><table><tr><td width='99%'><b>UNIT: </b> "+ xmlUnits.childNodes[i].getAttribute('title') + "</td><td align='right' width='1%'><div align='right'>" + prop + "</div></td></tr></table></div>" , type: 'unit',
                title: xmlUnits.childNodes[i].getAttribute('title'),
                reference: xmlUnits.childNodes[i].getAttribute('reference'),
                proportion: xmlUnits.childNodes[i].getAttribute('proportion'),
                mandatory: 'true',
                track: xmlUnits.childNodes[i].getAttribute('track'),
                op_title: xmlUnits.childNodes[i].getAttribute('op_title'),
                credits: xmlUnits.childNodes[i].getAttribute('credits'),
                glh: xmlUnits.childNodes[i].getAttribute('glh'),
                owner_reference: xmlUnits.childNodes[i].getAttribute('owner_reference'),
                description: ''
            };

            /*	if(xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild)
             {
             myobj2new.description=xmlUnits.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
             }
             */
            tmpNode2 = new YAHOO.widget.TextNode(myobj2new, groupx, false);
            oTextNodeMap[tmpNode2.labelElId]=tmpNode2;

            //tmpNode2.labelStyle = "icon-gen";
            //tmpNode2.onLabelClick = clickalert;
            //alert(xmlUnits.childNodes[i].getElementsByTagName('element')[0].getAttribute('title'));
            //alert(xmlUnits.childNodes[i].getElementsByTagName('element').length);




            for(var j=0; j < xmlUnits.childNodes[i].getElementsByTagName('elements').length; j++)
            {

                //alert("calling gt");
                //alert(tmpNode2);
                generateElementTree(xmlUnits.childNodes[i].getElementsByTagName('elements')[j],tmpNode2);

            }


        }

    }
    tree.draw();

}




function generateElementTree(elements,parent)
{
    //root = tree.getRoot();

//	   			myobj3 = { label: "<span class=icon-doc><font color='green'>"+ elements.getAttribute('title') + "</font>" , type: 'elements',
    myobj3 = { label: "<div class='ElementGroup'><b>ELEMENT GROUP: </b>"+ elements.getAttribute('title') + "</div>" , type: 'elements',
        title: elements.getAttribute('title'),
        /*				reference: xmlUnits.childNodes[j].getAttribute('reference'),
         owner: xmlUnits.childNodes[j].getAttribute('owner'),
         owner_reference: xmlUnits.childNodes[j].getAttribute('owner_reference'),
         customDate: 'unit', */
        description: '' };

    tmpNode3 = new YAHOO.widget.TextNode(myobj3, parent, false);
    oTextNodeMap[tmpNode3.labelElId]=tmpNode3;


    for(var i = 0; i < elements.childNodes.length; i++)
    {
        if(elements.childNodes[i].tagName == 'elements')
        {
            generateElementTree(elements.childNodes[i],tmpNode3);
        }
        else if(elements.childNodes[i].tagName == 'element')
        {

            myobj2 = { label: "<div class='Element'><b>ELEMENT: </b>"+ elements.childNodes[i].getAttribute('title') + "</div>" , type: 'element',

                title: elements.childNodes[i].getAttribute('title'),
                description: ''

            };

            if(elements.childNodes[i].getElementsByTagName('description')[0].firstChild)
            {
                myobj2.description=elements.childNodes[i].getElementsByTagName('description')[0].firstChild.nodeValue;
            }


            tmpNode4 = new YAHOO.widget.TextNode(myobj2, tmpNode3, false);
            oTextNodeMap[tmpNode4.labelElId]=tmpNode4;


            for( var k=0; k < elements.childNodes[i].getElementsByTagName('evidence').length; k++)
            {
                generateEvidenceTree(elements.childNodes[i].getElementsByTagName('evidence')[k],tmpNode4);
            }
        }
    }
}

function generateEvidenceTree(evidence, parent)
{

    //var contentBody = "[" + arr[evidence.getAttribute('type')] + "]";
    contentBody = '';
    myobj_evidence = { label: "<div class='Evidence'><table><tr><td width='99%'><b>EVIDENCE: </b>"+ evidence.getAttribute('title')               + "</td><td align='right' width='1%'><div align='right'>" + contentBody + "</div></td></tr></table></div>" , type: 'evidence',

        title: evidence.getAttribute('title')
    };

    tmpNode_evidence = new YAHOO.widget.TextNode(myobj_evidence, parent, false);
    oTextNodeMap[tmpNode_evidence.labelElId]=tmpNode_evidence;

    tree.expandAll();
}

// This is the master piece which traverse any kind of tree as a whole
function countProportion(xmlUnits)
{
    childr = 0;
    traverseCountProportion(xmlUnits);
    return childr;
}

function traverseCountProportion(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {
            if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true') )
            {
                if(parseFloat(xmlUnits.children[i].data.proportion)>0)
                    childr+=parseFloat(xmlUnits.children[i].data.proportion);
            }
            traverseCountProportion(xmlUnits.children[i]);
        }
    }
}


function setProportion()
{
    var qualification = root.children[0];
    total = prompt("What total credit value is required to achieve this qualification?","0");
    traverseSetProportion(qualification, total);
    alert("Proportions have been set. Please save and open again to see the effect");
    return childr;
}

function traverseSetProportion(xmlUnits, total)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {
            if(xmlUnits.children[i].data.type=='unit')
            {
                xmlUnits.children[i].data.proportion = Math.round((parseFloat(xmlUnits.children[i].data.credits)/total*100));
            }
            traverseSetProportion(xmlUnits.children[i], total);
        }
    }
}


function countAllProportion(xmlUnits)
{
    childr = 0;
    traverseCountAllProportion(xmlUnits);
    return childr;
}

function traverseCountAllProportion(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {
            if(xmlUnits.children[i].data.type=='unit')
            {
                if(parseFloat(xmlUnits.children[i].data.proportion)>0)
                    childr+=parseFloat(xmlUnits.children[i].data.proportion);
            }
            traverseCountAllProportion(xmlUnits.children[i]);
        }
    }
}


function countUnitsWithEvidence(xmlUnits)
{
    childr = 0;
    evidences = 0;
    traverseCountUnitsWithEvidence(xmlUnits);
    return childr;
}

function traverseCountUnitsWithEvidence(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='unit')
            {
                evidences = 0;
            }

            if(xmlUnits.children[i].data.type=='evidence')
            {
                if(evidences==0)
                    childr++;
                evidences++;
            }

            traverseCountUnitsWithEvidence(xmlUnits.children[i]);
        }
    }
}

function countElementsWithEvidence(xmlUnits)
{
    childr = 0;
    traverseCountElementsWithEvidence(xmlUnits);
    return childr;
}

function traverseCountElementsWithEvidence(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='element')
            {
                if(xmlUnits.children[i].children.length>0)
                    childr++;
            }

            traverseCountElementsWithEvidence(xmlUnits.children[i]);
        }
    }
}

function countElements(xmlUnits)
{
    childr = 0;
    traverseCountElements(xmlUnits);
    return childr;
}

function traverseCountElements(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='element')
            {
                childr++;
            }

            traverseCountElements(xmlUnits.children[i]);
        }
    }
}


function countMandatoryUnits(xmlUnits)
{
    mandatory_units=0;
    traverseCountMandatoryUnits(xmlUnits);
    return mandatory_units;
}

function traverseCountMandatoryUnits(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true'))
            {
                mandatory_units++;
            }

            traverseCountMandatoryUnits(xmlUnits.children[i]);
        }
    }
}

function countGLH(xmlUnits)
{
    glh = 0;
    traverseCountGLH(xmlUnits);
    document.getElementById("units_guided_learning_hours").value = glh;
}
function traverseCountGLH(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {
            if(xmlUnits.children[i].data.type=='unit')
            {
                if (xmlUnits.children[i].data.glh == parseInt(xmlUnits.children[i].data.glh))
                    glh += parseInt(xmlUnits.children[i].data.glh);
            }
            traverseCountGLH(xmlUnits.children[i]);
        }
    }
}

function countCreditValue(xmlUnits)
{
    cv = 0;
    traverseCountCreditValue(xmlUnits);
    document.getElementById("units_credit_value").value = cv;
}

function traverseCountCreditValue(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {
            if(xmlUnits.children[i].data.type=='unit')
            {
                if (xmlUnits.children[i].data.credits == parseInt(xmlUnits.children[i].data.credits))
                    cv += parseInt(xmlUnits.children[i].data.credits);
            }
            traverseCountCreditValue(xmlUnits.children[i]);
        }
    }
}


function countUnits(xmlUnits)
{
    units=0;
    traverseCountUnits(xmlUnits);
    return units;
}

function traverseCountUnits(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='unit')
            {
                units++;
            }

            traverseCountUnits(xmlUnits.children[i]);
        }
    }
}

function getValidationResults(xmlUnits)
{
    var validationResults = {mandatoryUnits: 0, isProportionSet: 0, countProportion: 0, countAllProportion: 0, countUnitsWithEvidence: 0, countElements: 0, countElementsWithEvidence: 0 };

    // These, I think, are globals
    r = true;
    mandatory_units=0;
    proportion = 0;
    all_proportion = 0;
    units_with_evidence = 0;
    evidences = 0;
    count_elements = 0;
    count_elements_with_evidence = 0;
    traverseGetValidationResults(xmlUnits); // sets undeclared globals (zeroed above)

    validationResults.isProportionSet = r;
    validationResults.mandatoryUnits = mandatory_units;
    validationResults.countProportion = proportion;
    validationResults.countAllProportion = all_proportion;
    validationResults.countUnitsWithEvidence = units_with_evidence;
    validationResults.countElements = count_elements;
    validationResults.countElementsWithEvidence = count_elements_with_evidence;

    return validationResults;
}

function traverseGetValidationResults(xmlUnits)
{
    if(xmlUnits.children.length>0)
    {
        for(var i=0; i<xmlUnits.children.length; i++)
        {

            if(xmlUnits.children[i].data.type=='unit' && xmlUnits.children[i].data.proportion=='')
            {
                r = false;
            }

            if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true'))
            {
                mandatory_units++;
            }

            if(xmlUnits.children[i].data.type=='unit' && (xmlUnits.children[i].data.mandatory==true || xmlUnits.children[i].data.mandatory=='true') )
            {
                if(parseFloat(xmlUnits.children[i].data.proportion)>0)
                    proportion+=parseFloat(xmlUnits.children[i].data.proportion);
            }

            if(xmlUnits.children[i].data.type=='unit')
            {
                evidences = 0;
                if(parseFloat(xmlUnits.children[i].data.proportion)>0)
                    all_proportion+=parseFloat(xmlUnits.children[i].data.proportion);
            }

            if(xmlUnits.children[i].data.type=='evidence')
            {
                if(evidences==0)
                    units_with_evidence++;
                evidences++;
            }

            if(xmlUnits.children[i].data.type=='element')
            {
                count_elements++;
                if(xmlUnits.children[i].children.length>0)
                    count_elements_with_evidence++;
            }

            traverseGetValidationResults(xmlUnits.children[i]);
        }
    }
}




function save()
{
    // No & in internal title
    it = document.getElementById('internaltitle').value;
    if(it.indexOf("&")>=0)
    {
        alert("You cannot use '&' anywhere in Internal Title");
        return false;
    }

    if(!root){
        alert("Error: no root found");
        return false;
    }

    // Validate the main form text fields
    var mainForm = document.forms[0];
    if(validateForm(mainForm) == false)
    {
        return false;
    }

    // Validate the qualification level (at least one level must be specified)
    var levelGrid = document.getElementById('grid_level');
    var levelValues = levelGrid.getValues();
    if(levelValues.length == 0)
    {
        alert("Please select the level(s) of this qualification");
        return false;
    }


    var qualification = root.children[0];
    var validationResults = getValidationResults(qualification);

    // check if proportion is given for all units
    if(validationResults.isProportionSet==false)
    {
        alert("please set proportion for all units");
        return false;
    }

    var mandatory_units = validationResults.mandatoryUnits;
    var proportion = validationResults.countProportion;
    var all_proportion = validationResults.countAllProportion;
    var unitsWithEvidence = validationResults.countUnitsWithEvidence;
    var elementsWithoutEvidences = validationResults.countElements - validationResults.countElementsWithEvidence;
    var total_units = 0;

    var note = '';
    var noten = 1;

    // The sum of proportion of mandatory units must be equal to 100 if there are no optional units.
    if(proportion!=100 && units==mandatory_units){
        //note += "\n\n" + noten++ + ". The sum of proportion of all mandatory units must be equal to 100 since there is no optional unit in the qualification";
    }

    // The sum of proportion of mandatory units must be less than 100 if there are optional units
    if(proportion>=100 && units>mandatory_units){
        //note += "\n\n" + noten++ + ". The sum of proportion of all mandatory units must be less than 100 since there are optional units in the qualification";
    }

    // All units must have at least one evidence requirement
    if(unitsWithEvidence<window.units){
        note += "\n\n" + noten++ + ". All units must have at least one evidence requirement";
    }

    // All elements must have at least one evidence requirement
    if(elementsWithoutEvidences>0){
        note += "\n\n" + noten++ + ". All elements must have at least one evidence requirement";
    }

    if(note != '')
    {
        if(!confirm("This qualification has following problems: " + note + "\n\n Do you still want to save it?")){
            return false;
        }
    }


    var st = null;
    if(document.forms[0].elements['qual_status'][0].checked==true)
    {
        st = 1;
    }
    else
    {
        if(document.forms[0].elements['qual_status'][1].checked==true)
            st = 0;
        else
            st = '';
    }

    var active = document.forms[0].elements['is_active'].checked ? 1 : 0;
    var lsc_learning_aim = document.forms[0].elements['lsc_learning_aim'].value;
    var ebs_ui_code = document.forms[0].elements['ebs_ui_code'].value;
    var tqt = document.forms[0].elements['tqt'].value;


    var postData = 'id=' + encodeURIComponent(document.forms[0].elements['id'].value)
        + '&qan_before_editing=' + encodeURIComponent(document.forms[0].elements['qan_before_editing'].value)
        + '&xml=' + encodeURIComponent(toXML())
        + '&internaltitle=' + encodeURIComponent(__internalTitle)
        + '&blob=' + encodeURIComponent(traverse(tree.getRoot()))
        + '&units=' + encodeURIComponent(window.units)
        + '&proportion=' + encodeURIComponent(proportion)
        + '&unitswithevidence=' + encodeURIComponent(unitsWithEvidence)
        + '&elementswithoutevidences=' + encodeURIComponent(elementsWithoutEvidences)
        + '&unitsrequired=' + encodeURIComponent(all_proportion)
        + '&mandatoryunits=' + encodeURIComponent(mandatory_units)
        + '&status=' + encodeURIComponent(st)
        + '&isactive=' + encodeURIComponent(active)
        + '&lsc_learning_aim=' + encodeURIComponent(lsc_learning_aim)
        + '&ebs_ui_code=' + encodeURIComponent(ebs_ui_code)
	    + '&tqt=' + encodeURIComponent(tqt)
    ;


    var request = ajaxRequest('do.php?_action=save_qualification', postData);
    if(request){
        window.location.href = __bcPrevious;
    }
}



//function addPerformanceRow()
//{
//	var myForm = document.forms[1];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	var __grade = myForm.elements['__grade'];
//	var __thresh1 = myForm.elements['__thresh1'];
//	var __thresh12 = myForm.elements['__thresh12'];
//	var __thresh3 = myForm.elements['__thresh3'];
//	var __points = myForm.elements['__points'];

//	var firstCell;
//	for(var i = 1; i < rows.length; i++)
//	{
//		firstCell = rows[i].firstChild.firstChild.nodeValue;
//		if(firstCell == __grade.value)
//		{
//			alert('You cannot add figures for the same grade twice');
//			return false;
//		}
//	}

// Remove all characters except for numerals
//	__thresh1.value = __thresh1.value.replace(/[^0-9\.]*/g, '');
//	__thresh12.value = __thresh12.value.replace(/[^0-9\.]*/g, '');
//	__thresh3.value = __thresh3.value.replace(/[^0-9\.]*/g, '');
//	__points.value = __points.value.replace(/[^0-9\.]*/g, '');

// Fill any blank cells with zeros
//	if(__thresh1.value == '') __thresh1.value = 0;
//	if(__thresh12.value == '') __thresh12.value = 0;
//	if(__thresh3.value == '') __thresh3.value = 0;
//	if(__points.value == '') __points.value = 0;

// Force grade to ASCII characters only
//	__grade.value = forceASCII(__grade.value);

//	var row = insertPerformanceRow(__grade.value, __thresh1.value, __thresh12.value, __thresh3.value, __points.value, -1);
//}


//function insertPerformanceRow(grade, thresh1, thresh12, thresh3, points, index)
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(index == null)
//	{
//		index = -1;
//	}

//	var row = table.insertRow(index);
//	row.onclick = function(event){
//		var tbody = this.parentNode.parentNode; // <tr>.<tbody>.<table>
//		table.onRowSelect(this);
//		if(event.stopPropagation){
//			event.stopPropagation(); // DOM 2
//		} else {
//			event.cancelBubble = true; // IE
//		}};

//	var cell0 = row.insertCell(0);
//	var cell1 = row.insertCell(1);
//	var cell2 = row.insertCell(2);
//	var cell3 = row.insertCell(3);
//	var cell4 = row.insertCell(4);

// Presentation
//	cell0.align = 'left';
//	cell1.align = 'center';
//	cell1.style.color = (thresh1 == 0 ? 'silver':'');
//	cell2.align = 'center';
//	cell2.style.color = (thresh12 == 0 ? 'silver':'');
//	cell3.align = 'center';
//	cell3.style.color = (thresh3 == 0 ? 'silver':'');
//	cell4.align = 'center';
//	cell4.style.color = (points == 0 ? 'silver':'');

//	var textNode = document.createTextNode(grade);
//	cell0.appendChild(textNode);
//	textNode = document.createTextNode(thresh1);
//	cell1.appendChild(textNode);
//	textNode = document.createTextNode(thresh12);
//	cell2.appendChild(textNode);
//	textNode = document.createTextNode(thresh3);
//	cell3.appendChild(textNode);
//	textNode = document.createTextNode(points);
//	cell4.appendChild(textNode);

//	row.getGrade = function(){
//		return this.childNodes[0].firstChild.nodeValue;
//	}
//	row.getThresh1 = function(){
//		return this.childNodes[1].firstChild.nodeValue;
//	}
//	row.getThresh12 = function(){
//		return this.childNodes[2].firstChild.nodeValue;
//	}
//	row.getThresh3 = function(){
//		return this.childNodes[3].firstChild.nodeValue;
//	}
//	row.getPoints = function(){
//		return this.childNodes[4].firstChild.nodeValue;
//	}

//	return row;
//}


//function deletePerformanceRow()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			table.deleteRow(i);
//		break;
//		}
//	}

//	table.selectedRow = null;
//}


//function movePerformanceRowUp()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

// Get index of selected row
//	var index;
//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			index = i;
//			break;
//		}
//	}

//	if(index == 1)
//	{
// Cannot move any further up
//		return false;
//	}

//	table.deleteRow(index);
//	var row = insertPerformanceRow(
//		table.selectedRow.getGrade(),
//		table.selectedRow.getThresh1(),
//		table.selectedRow.getThresh12(),
//		table.selectedRow.getThresh3(),
//		table.selectedRow.getPoints(),
//		index - 1);

//	row.style.backgroundColor = '#FDF1E2';
//	table.selectedRow = row;
//}


//function movePerformanceRowDown()
//{
//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');

//	if(table.selectedRow == null)
//	{
//		alert('No row selected');
//		return false;
//	}

// Get index of selected row
//	var index;
//	for(var i = 0; i < rows.length; i++)
//	{
//		if(rows[i] == table.selectedRow)
//		{
//			index = i;
//			break;
//		}
//	}

//	if( (index + 1) >= rows.length)
//	{
// Cannot move any further down
//		return false;
//	}

//	table.deleteRow(index);
//	var row = insertPerformanceRow(
//		table.selectedRow.getGrade(),
//		table.selectedRow.getThresh1(),
//		table.selectedRow.getThresh12(),
//		table.selectedRow.getThresh3(),
//		table.selectedRow.getPoints(),
//		index + 1);

//	row.style.backgroundColor = '#FDF1E2';
//	table.selectedRow = row;
//}


//function deleteAllPerformanceRows()
//{

//	var myForm = document.forms[0];
//	var table = document.getElementById('table_performance_figures');
//	var rows = table.getElementsByTagName('tr');
//	var bodyRows = rows.length - 1;
//	for(var i = 0; i < bodyRows; i++)
//	{
//		table.deleteRow(-1);
//	}
//}
