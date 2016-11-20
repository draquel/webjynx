<!--Powered by:
     __      __          __       _____                            
    /\ \  __/\ \        /\ \     /\___ \                           
    \ \ \/\ \ \ \     __\ \ \____\/__/\ \  __  __    ___    __  _  
     \ \ \ \ \ \ \  /'__`\ \ '__`\  _\ \ \/\ \/\ \ /' _ `\ /\ \/'\ 
      \ \ \_/ \_\ \/\  __/\ \ \L\ \/\ \_\ \ \ \_\ \/\ \/\ \\/>  </ 
       \ `\___x___/\ \____\\ \_,__/\ \____/\/`____ \ \_\ \_\/\_/\_\
        '\/__//__/  \/____/ \/___/  \/___/  `/___/> \/_/\/_/\//\/_/
												 \__/
            ______                ___    __          __      
           /\__  _\              /\_ \  /\ \      __/\ \__   
           \/_/\ \/   ___     ___\//\ \ \ \ \/'\ /\_\ \ ,_\  
              \ \ \  / __`\  / __`\\ \ \ \ \ , < \/\ \ \ \/  
               \ \ \/\ \L\ \/\ \L\ \\_\ \_\ \ \\`\\ \ \ \ \_ 
                \ \_\ \____/\ \____//\____\\ \_\ \_\ \_\ \__\
                 \/_/\/___/  \/___/ \/____/ \/_/\/_/\/_/\/__/

Author: Dan Raquel (draquel@webjynx.com)-->
<?php
	error_reporting(E_ALL);
	require_once("_php/lib.php");
	require_once("_php/DBObj/dbobj.php");
	session_start();
	
	//Initialize Site Data
	$_SESSION['Title'] = "WebJynx Toolkit - Testing";
	$_SESSION['Domain'] = "dev.webjynx.com";
	if($_SERVER['SERVER_NAME'] == "dev2.webjynx.com"){ $_SESSION['dbHost'] = "localhost"; }else{ $_SESSION['dbHost'] = "webjynxrds.cjzpxtjfv2ad.us-east-1.rds.amazonaws.com"; }
	$_SESSION['dbName'] = "DBObj";
	$_SESSION['dbuser'] = "root";
	$_SESSION['dbPass'] = "Ed17i0n!";
	$_SESSION['Blog_GCS_ID'] = "011020224819443845085:btxae4osafm";
	
	if(isset($_REQUEST['reset']) && ($_REQUEST['reset'] == 1 || $_REQUEST['reset'] == "true" || $_REQUEST['reset'] == "yes")){ $reset = true; }else{ $reset = false; }
	if(!isset($_SESSION['Pages']) || $reset){
		/*echo "PAGES LOADED <BR>";*/
		$_SESSION['Pages'] = array(
			array("id"=>0,"meta-title"=>"HTTP 404 - Page Not Found","meta-description"=>"HTTP 404 - Page Not Found","meta-keywords"=>NULL,"path-ui"=>"/404","path-file"=>"/page/404.php"),
			array("id"=>1,"meta-title"=>"HTTP 401 - Unauthorized","meta-description"=>"HTTP 401 - Unauthorized","meta-keywords"=>NULL,"path-ui"=>"/401","path-file"=>"/page/401.php"),
			array("id"=>2,"meta-title"=>"Index","meta-description"=>"Welcome to our home page!","meta-keywords"=>NULL,"path-ui"=>"/","path-file"=>"/page/index.php"),
			array("id"=>3,"meta-title"=>"About","meta-description"=>"We like stuff and want to work together on your things!","meta-keywords"=>NULL,"path-ui"=>"/about/","path-file"=>"/page/about/index.php"),
			array("id"=>4,"meta-title"=>"Other","meta-description"=>"Some more stuff we think is neat.","meta-keywords"=>NULL,"path-ui"=>"/about/other","path-file"=>"/page/about/other.php"),
			array("id"=>5,"meta-title"=>"Sitemap","meta-description"=>"A sitemap, just incase you get lost.","meta-keywords"=>NULL,"path-ui"=>"/sitemap","path-file"=>"/page/sitemap.php"),
			array("id"=>6,"meta-title"=>"Class Testing","meta-description"=>"Class Unit Testing","meta-keywords"=>NULL,"path-ui"=>"/class","path-file"=>"/page/class.php"),
			array("id"=>7,"meta-title"=>"Blog","meta-description"=>"Our Blog","path-ui"=>"/blog/","meta-keywords"=>NULL,"path-file"=>"/page/blog.php"),
			array("id"=>8,"meta-title"=>"Authorize User","meta-description"=>"Authorized User Page","path-ui"=>"/auth/","meta-keywords"=>NULL,"path-file"=>"/page/user.php")
		);
	}
	if(!isset($_SESSION['db']) || $reset){
		/*echo "DATABASE CONNECTED <BR>";*/
		$_SESSION['db'] = new Sql();
		$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
		$_SESSION['db']->connect($_SESSION['dbName']);
	}elseif(!$_SESSION['db']->con($_SESSION['dbName'])){ $_SESSION['db']->connect($_SESSION['dbName']);	}
	if(!isset($_SESSION['Blog']) || $reset){
		/*echo "BLOG LOADED <BR>";*/
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']->con($_SESSION['dbName']));
		$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']));
	}
	if(!isset($_SESSION['Users']) || $reset){
		/*echo "USERS LOADED <BR>";*/
		$_SESSION['Users'] = new DLList();
		$sql = "SELECT u.*, group_concat(distinct concat(r.ID,':',r.RID,':',r.KID,':',r.Key,':',r.Code,':',r.Definition) separator ';') AS `Groups`, group_concat(distinct concat(`p`.`ID`,':',`p`.`Created`,':',`p`.`Updated`,':',`p`.`Name`,':',`p`.`PID`,':',`p`.`Primary`,':',`p`.`Region`,':',`p`.`Area`,':',`p`.`Number`,':',`p`.`Ext`) separator ';') AS `Phones`, group_concat(distinct concat(`a`.`ID`,':',`a`.`Created`,':',`a`.`Updated`,':',`a`.`Name`,':',`a`.`PID`,':',`a`.`Primary`,':',`a`.`Address`,':',`a`.`Address2`,':',`a`.`City`,':',`a`.`State`,':',`a`.`Zip`) separator ';') AS `Addresses`, group_concat(distinct concat(`e`.`ID`,':',`e`.`Created`,':',`e`.`Updated`,':',`e`.`Name`,':',`e`.`PID`,':',`e`.`Primary`,':',`e`.`Address`) separator ';') AS `Emails` FROM Users u LEFT JOIN Relationships r ON u.ID = r.RID AND r.Key = 'Group' LEFT JOIN `Addresses` `a` on `a`.`PID` = `u`.`ID` LEFT JOIN `Phones` `p` on `p`.`PID` = `u`.`ID` LEFT JOIN `Emails` `e` on `e`.`PID` = `u`.`ID` GROUP BY u.ID ORDER BY u.Created DESC";
		$res = mysqli_query($_SESSION['db']->con($_SESSION['dbName']),$sql);
		while($row = mysqli_fetch_array($res)){
			$u = new User(NULL);
			$u->initMysql($row);
			$_SESSION['Users']->insertLast($u);
		}
	}
	if(!isset($_SESSION['User']) || $reset){ $_SESSION['User'] = NULL; }
	$_SESSION['Error'] = array("404"=>array("path-file"=>NULL,"path-ui"=>NULL),"401"=>NULL);
	//Process Page Address
	if(isset($_REQUEST['pg']) && $_REQUEST['pg'] != "" && $_REQUEST['pg'] != NULL){	
		$found = false; 
		foreach($_SESSION['Pages'] as $page){ if($page['path-ui'] == "/".strtolower($_REQUEST['pg'])){ $found = true; $_SESSION['Page'] = $page; if(!file_exists(ltrim($page['path-file'],"/"))){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-file'] = $page['path-file']; } break; } }
		if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = "/".$_REQUEST['pg']; }
	}else{ $_SESSION['Page'] = $_SESSION['Pages'][2]; }
	//Process Blog Page
	if($_SESSION['Page']['path-file'] == "/page/blog.php"){
		if(isset($_REQUEST['bpg'])){
			switch($_REQUEST['bpg']){
				default:
					if($_REQUEST['bpg'] != NULL && $_REQUEST['bpg'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['bpgi']) && $_REQUEST['bpgi'] != NULL && $_REQUEST['bpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['bpgn']) && !is_numeric($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL && $_REQUEST['bpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
				break;
				case "p":
					$post = $_SESSION['Blog']->getPosts()->getFirstNode();
					$_SESSION['Page']['Current'] = NULL; $found = false;
					while($post != NULL){
						$a = $post->readNode()->toArray();
						if($a['ID'] == $_REQUEST['bpgi']){ $found = true; $_SESSION['Page']['Current'] = $post; break; }
						$post = $post->getNext();
					}
					if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
					$_SESSION['Page']['meta-title'] = "Blog Post - ".$a['Title']; $_SESSION['Page']['meta-description'] = $a['Description']; $_SESSION['Page']['meta-keywords'] = $a['Keywords'];
				break;
				case "c": $_SESSION['Page']['meta-title'] = "Blog Category - ".$_REQUEST['bpgi'];	$_SESSION['Page']['meta-description'] = "Listing of blog entries tagged in ".$_REQUEST['bpgi'];	break;
				case "u": $_SESSION['Page']['meta-title'] = "Blog Author - ".$_REQUEST['bpgi']; $_SESSION['Page']['meta-description'] = "Listing of blog entries written by ".$_REQUEST['bpgi']; break;
				case "a": $_SESSION['Page']['meta-title'] = "Blog Archive - ".date("F Y",strtotime($_REQUEST['bpgi'])); $_SESSION['Page']['meta-description'] = "Listing of blog entries posted in ".date("F, Y",strtotime($_REQUEST['bpgi']));	break;
				case "admin": $_SESSION['Page']['meta-title'] = "Blog Administration"; $_SESSION['Page']['meta-description'] = "Blog Admin Console"; if($_REQUEST['bpgi']){ $_SESSION['Page']['meta-title'] .= " - ".ucfirst($_REQUEST['bpgi']); } break;
			}
		}else{
			$_REQUEST['bpg'] = NULL;
			if(isset($_REQUEST['bpgi']) && $_REQUEST['bpgi'] != NULL && $_REQUEST['bpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
			if(isset($_REQUEST['bpgn']) && !is_numeric($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL && $_REQUEST['bpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
		}
	}
	//Process User Page
	if($_SESSION['Page']['path-file'] == "/page/user.php"){
		$upage = "";
	}
	//HTTP 404
	if($_SESSION['Page']['id'] == 0){ header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); }
?>
<!DOCTYPE html>
<html lang="en">
   <!--Start Header-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name='viewport' content="width=device-width, initial-scale=1">
        <link rel="icon" href="/img/favicon.ico">
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <link rel="alternate" type="application/atom+xml" title="<?php echo $_SESSION['Title']; ?>" href="http://<?php echo $_SESSION['Domain']; ?>/rss/">
        <?php
		//Title, Meta-Description & Meta-Keywords			
			echo "<title>".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."</title><meta name=\"description\" content=\"".$_SESSION['Page']['meta-description']."\"><meta name=\"keywords\" content=\"".$_SESSION['Page']['meta-keywords']."\">";
		//Concatenate CSS Files
			$css = file_get_contents("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
			$css .= file_get_contents("css/trumbowyg.min.css");
			$css .= file_get_contents("css/main.css");
			$css .= file_get_contents("css/blog.css");
			echo "<style>".$css."</style>";
		//Load JS Libs
			$js ="<!--Start Head Loader--><script type=\"text/javascript\">";
			$js .= file_get_contents("_js/head.min.js");
        	$js .= "head.load(\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\",\"/_js/bootstrap.min.js\",\"https://www.google-analytics.com/analytics.js\",\"https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57bf0be13e45dd09\",\"/_js/trumbowyg.min.js\",\"/_js/lib.js\"); </script><!--End Head Loader-->";
			echo $js;
		?>
    </head>
   <!--End Header-->
    <body role="document">
       <!--Start Page-->
        <div id="page" class="container-fluid">
            <nav id="menu" class="navbar navbar-default navbar-static-top<?php if($_SESSION['Page']['path-file'] == "/page/index.php" || (isset($_REQUEST['bpg']) && $_REQUEST['bpg'] == "admin") || $_SESSION['Page']['path-file'] == "/page/user.php"){ echo " hidden"; } ?>">
              <div class="container-fluid">
                <div class="navbar-header">
                  <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a href="/" class="navbar-brand">Project Name</a>
                </div>
                <div class="navbar-collapse collapse" id="navbar"><?php $_REQUEST['dd'] = 1; include("menu.php"); ?></div>
              </div>
            </nav>
            <div id="contentWrapper" class="row"><div id="content"><!-- Page Content --><?php include(ltrim($_SESSION['Page']['path-file'],"/")); ?></div></div>
            <div id="footer" class="row">
                <div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><p>info@yourcompany.com<br>(123) 456-7890</p><p><div class="addthis_inline_follow_toolbox"></div></p></div>
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8"><?php $_REQUEST['dd'] = 0; include("menu.php"); ?></div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"><p>&copy;<?php echo $_SESSION['Title']; ?> 2016 - All Rights Reserved</p><a href="http://www.kburkhart.com" target="_blank"><img src="/img/KBDicon.svg" alt="Katharine Burkhart Designs" /></a></div>
            </div>
        </div>
       <!--End Page-->
       <!-- Start Modal -->
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel"><!--Modal Title--></h4></div>
                    <div class="modal-body"><!-- Modal Content --></div>
                </div>
            </div>
        </div>
       <!-- End Modal -->
        <script type="text/javascript">
		head.ready(function(){
			$(document).ready(function(){
				var to = 250;
				$("#page").fadeIn(to);
			/* Navigation */
				$("#page").on("click","ul.nav a, .navbar-brand, .navl, .bnavl", function(event){ event.preventDefault(); if($(this).attr("href") != "#"){ var alink = $(this); $("#page").fadeOut(to,function(){ window.location.assign(alink.attr("href")); }); } });
				$("ul.nav a").each(function(index){ if($(this).attr("href") === window.location.pathname ){ $(this).parent().addClass("active");} });
			/* Window Scroll Event - Content Fade In */
				$(window).scroll(function(){
					$('.hideme').each(function(i){
						var bottom_of_object = $(this).offset().top + ($(this).outerHeight() * 0.25);
						var bottom_of_window = $(window).scrollTop() + $(window).height();
						if( bottom_of_window > bottom_of_object ){ $(this).animate({'opacity':'1'},350); }
					});
				});
			/* Trumbowyg Editor */
				$.trumbowyg.svgPath = '/img/trumbowyg_icons.svg';
				//$(".trumbowyg").trumbowyg({ btns: [	['viewHTML'],['formatting'],'btnGrp-semantic',['superscript', 'subscript'],['link'],['insertImage'],'btnGrp-justify','btnGrp-lists',['horizontalRule'],['removeformat'],['fullscreen'] ],autogrow: true	});
			/* Google Analytics */
				gaTracker("UA-83229001-1");
				gaTrack(window.location.pathname,document.title);
			/* Mobile Menu - Toggle Page Scroll Lock */
				//$(".navbar-toggle").click(function(){ if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }else{ $("body").addClass("noscroll"); } });
			});
		});
		</script>
    </body>
</html>
<?php /*$_SESSION['db']->disconnect($_SESSION['dbName']);*/ session_write_close(); ?>