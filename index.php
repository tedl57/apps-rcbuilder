<?php // 2014-02-01  3:26:29 PM Sat
require_once("/lib/php/trl/oo/pagehtml.php");

displayPage();
exit();

/////////////////////////////////////////////////////////////////////////
// functions

function displayPage()
{
	$page = pageHTML::getInstance();

	// parse user URI and store in page
	$page->parseURI($_SERVER["REQUEST_URI"],$_GET);

	// default module to execute
//	$mod = $page->getURIParm( "mod", "list" );

	// do tasks before display, display any task output under toolbar

	$controller_results = doController();

	// HTML page is layered: head + body, where body is toolbar, [task output,] display, js, js-doready
	$page->addHead(getHeadLinks());
	$page->addHead(getHeadStyle("list"));

	$page->addBody($controller_results);

	// display content / show content / output content

	// display draggable buttons
	$buttons = array("pwr",/*"1","2","3","4","5","6","7","8","9","0",*/"ok","left","right","up","down","no");
	
	$js = "";
	$html = "<div class='buttons' id='buttons'>";
	foreach($buttons as $button)
	{
		$id = "button-$button";
		$html .= "<div class='button' id='$id'>";
//		$html .= "<p class='button'>$button</p>";

		$html .= "<img src='assets/icons/32/$button.ico'>";
		
		$html .= "</div>";
		$js .= "\$( \"#$id\" ).draggable( {revert: \"invalid\", snap: \".cell\" });\n";
	}
	$html .= "</div><hr>";

	$page->addBody($html);
	$page->addScript($js);
	$page->addScript(jsGetDroppable("buttons"));

	// display remote control
	$html = "<div class='remote-control'>";
	$rows = 8;
	$cols = 4;
	$js = "
		
// make remote cells droppable::
	";

	for($row = 0 ; $row < $rows ; $row++)
	{
		$html .= "<div>";
		for($col = 0 ; $col < $cols ; $col++)
		{
			$id = "cell-$row-$col";
			$html .= "<div class='cell' id='$id'>";
			$html .= "<span class='faint'>$row,$col</span>";
			$html .= "</div>";
			$js .= jsGetDroppable($id);
		}
		$html .= "</div>";
	}
	$html .= "</div>";

	$page->addBody($html);
	$page->addScript($js);
	
	// javascript goes at bottom of page to help display content sooner;
	// programatically generate javascript both outside and inside docready based on content above

	putScript();

	$page->addBody(getToolbar("list"),true);

	// finally, send HTML page to browser

	echo $page->getPage("rcbuilder");
}
function jsGetDroppable($id)
{
	return "
	$( '#$id' ).droppable({
	tolerance: 'pointer',
	hoverClass: 'highlight-droppable',
	drop: function( event, ui ) {
	movedropped(  ui.draggable.attr('mypath')+'/'+ui.draggable.attr('myname'),$(this).attr('id'))
}
});
";
}
function putScript()
{
	$page = pageHTML::getInstance();

	$js = "
			
function movedropped(x,y)
{
}
			
			";

	$page->addScript($js);
}
function doController()
{
	return "";
}
function getToolbar($mod)
{
}
function getHeadStyle($mod)
{
	//	$color = ($mod == "list") ? "#aaddff" : "white";	//eeddff = lavender
	$color = "#aaddff";	//eeddff = lavender
	$ret="
<style type='text/css'>
</style>";
	return $ret;
}
function getHeadLinks()
{
	$ret = '
<link href="app.css" rel="stylesheet" type="text/css" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">

<script type="text/javascript" src="http://localhost/apps/ajaxlaunch/index.js"></script>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="/lib/js/jquery/jquery-1.10.2.js"></script>

<script type="text/javascript" src="/lib/js/jquery/menu-context2/src/jquery.ui.position.js"></script>
<script type="text/javascript" src="/lib/js/jquery/menu-context2/src/jquery.contextMenu.js"></script>
<link href="/lib/js/jquery/menu-context2/src/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
		
<!-- 1/24/2014 - dled latest jquery ui - customized to have these 5:
<script src="/lib/js/jquery/ui/jquery.ui.core.js"></script>
<script src="/lib/js/jquery/ui/jquery.ui.widget.js"></script>
<script src="/lib/js/jquery/ui/jquery.ui.mouse.js"></script>
<script src="/lib/js/jquery/ui/jquery.ui.draggable.js"></script>
<script src="/lib/js/jquery/ui/jquery.ui.droppable.js"></script>
-->
<script src="/lib/js/jquery/ui/jquery-ui-1.10.4.custom.min.js"></script>

<script src="/lib/js/jquery/edit-in-line/jquery.editinplace.js"></script>

<script src="/lib/js/thomasfrank.se/sessvars.js" type="text/javascript"></script>
';
	return $ret;
}
?>