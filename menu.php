<?php
	require_once("_php/DBObj2/lib.php");
	$menu = "<ul class=\"nav navbar-nav navbar-right\">
	  <!--HOME-->
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
		<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/about/\">About<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu\">
		  <li><a href=\"/about/other\">Other</a></li>
		</ul>
	  </li>
	   <li><a href=\"/class\">Class Testing</a></li>
	   <li><a href=\"/blog/\">Blog</a></li>
	   <li><a href=\"/media/\">Media</a></li>
	  <li><a href=\"/sitemap\">Sitemap</a></li>
	</ul>";
	
	$menu_nd = str_replace("\"dropdown-toggle\"","\"\"",$menu);
	$menu_nd = str_replace("\"dropdown-menu\"","\"\"",$menu_nd);
	$menu_nd = str_replace("\"dropdown\"","\"\"",$menu_nd);
	$menu_nd = str_replace("<span class=\"caret\"></span>","",$menu_nd);
	$fmenu = str_replace("\"collapse\"","\"\"",$menu_nd);
	$fmenu = str_replace("<!--HOME-->","<li><a href=\"/\">Home</a></li>",$fmenu);
	if(isset($_REQUEST['dd'])){ 
		if(!isMobile() && $_REQUEST['dd'] == 1){ echo $menu; }
		else{ if($_REQUEST['dd'] == 0){ echo $fmenu; }else{ echo $menu_nd; } }
	}else{ echo $menu_nd; }
?>
