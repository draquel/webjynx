<?php
	require_once("script/_php/lib.php");
	$menu = "<ul class=\"nav navbar-nav\">
	  <!--HOME-->
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
		<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/pg/about/\" target=\"#content\">About<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu\">
		  <li><a href=\"/pg/about/principals\" target=\"#content\">The Principals</a></li>
		  <li><a href=\"/pg/about/speak\" target=\"#content\">Speaking</a></li>
		  <li><a href=\"/pg/about/consult\" target=\"#content\">Consulting</a></li>
		</ul>
	  </li>
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
		<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/pg/data/\" target=\"#content\">The Data<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu\">
		  <li><a href=\"/pg/data/ii\" target=\"#content\">The Case for Inclusive Innovation</a></li>
		  <li><a href=\"/pg/data/we\" target=\"#content\">Women's High-Growth Entrepreneurship</a></li>
		  <li><a href=\"/pg/data/clst\" target=\"#content\">Commercializing Life Sciences &amp; Tech</a></li>
		  <li><a href=\"/pg/data/id\" target=\"#content\">Impact Digest</a></li>
		</ul>
	  </li>
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
	  	<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/pg/project/\" target=\"#content\">Our Projects<span class=\"caret\"></span></a>
		<ul class=\"dropdown-menu\">
		  <li><a href=\"/pg/project/mw\" target=\"#content\">Men's Work</a></li>
		  <li><a href=\"/pg/project/lst\" target=\"#content\">Life Sciences &amp; Tech</a></li>
		  <li><a href=\"/pg/project/atc\" target=\"#content\">Access to Capital</a></li>
		  <!--<li><a href=\"/pg/project/he\" target=\"#content\">100 Entrepreneurs</a></li>
		  <li><a href=\"/pg/project/astia\" target=\"#content\">ASTIA</a></li>
		  <li><a href=\"/pg/project/nwbc\" target=\"#content\">NWBC</a></li>-->
		</ul>
	  </li>
	  <li><a href=\"/pg/blog\" target=\"#content\">Blog</a></li>
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
	  	<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/pg/media/\" target=\"#content\">Media<span class=\"caret\"></span></a>
		  <ul class=\"dropdown-menu\">
			<li><a href=\"/pg/media/inquiries\" target=\"#content\">Inquiries</a></li>
			<li><a href=\"/pg/media/releases\" target=\"#content\">Releases</a></li>
		  </ul>
	  </li>
	  <li class=\"dropdown\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
	  	<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"/pg/connect/\" target=\"#content\">Connect<!--<span class=\"caret\"></span>--></a>
		  <!--<ul class=\"dropdown-menu\">
			<li><a href=\"/pg/connect/calendar\" target=\"#content\">Calendar</a></li>
		  </ul>-->
	  </li>
	</ul>";
	
	$menu_nd = str_replace("\"dropdown-toggle\"","\"\"",$menu);
	$menu_nd = str_replace("\"dropdown-menu\"","\"\"",$menu_nd);
	$menu_nd = str_replace("\"dropdown\"","\"\"",$menu_nd);
	$menu_nd = str_replace("<span class=\"caret\"></span>","",$menu_nd);
	$fmenu = str_replace("\"collapse\"","\"\"",$menu_nd);
	$fmenu = str_replace("<!--HOME-->","<li><a href=\"index\" target=\"#content\">Home</a></li>",$fmenu);
	if(isset($_REQUEST['dd'])){ 
		if(!isMobile() && $_REQUEST['dd'] == 1){ echo $menu; }
		else{ 
			if($_REQUEST['dd'] == 0){ echo $fmenu; }else{ echo $menu_nd; }
		}
	}else{ echo $menu_nd; }
?>