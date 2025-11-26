<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title></title>
 
    
 
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/assets/yui.css?v=3" >
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/assets/dpSyntaxHighlighter.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/container/assets/skins/sam/container.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yuiloader/yuiloader-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/dom/dom-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/event/event-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/button/button-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/container/container-min.js"></script>
 
<!--there is no custom header content for this example-->
 
 
 
</head>
<body id="yahoo-com" class="yui-skin-sam">
<script> 
YAHOO.namespace("example.container");
 
function init() {
	
	// Define various event handlers for Dialog
	var handleYes = function() {
		alert(this.form.name.value);
		this.hide();
	};
	var handleNo = function() {
		this.hide();
	};
 
	// Instantiate the Dialog
	YAHOO.example.container.simpledialog1 = new YAHOO.widget.SimpleDialog("simpledialog1", 
																			 { width: "300px",
																			   fixedcenter: true,
																			   visible: false,
																			   draggable: false,
																			   close: true,
																			   constraintoviewport: true,
																			   buttons: [ { text:"Yes", handler:handleYes, isDefault:true },
																						  { text:"No",  handler:handleNo } ]
																			 } );
	YAHOO.example.container.simpledialog1.setHeader("Are you sure?");
	
	// Render the Dialog
	YAHOO.example.container.simpledialog1.render("container");
 
	YAHOO.util.Event.addListener("show", "click", YAHOO.example.container.simpledialog1.show, YAHOO.example.container.simpledialog1, true);
	YAHOO.util.Event.addListener("hide", "click", YAHOO.example.container.simpledialog1.hide, YAHOO.example.container.simpledialog1, true);
 
}
 
YAHOO.util.Event.addListener(window, "load", init);
</script>
 
<button id="show">Show simpledialog1</button> 
<button id="hide">Hide simpledialog1</button>
 
<div id="container">
<div id='simpledialog1'>
<form>
Enter Your name <input type = text name = 'name' />
</form>
</div>
</div>

</body>
</html>