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
	require_once("_php/DBObj2/dbobj.php");
	session_start();
	if(isset($_REQUEST['reset']) && ($_REQUEST['reset'] == 1 || $_REQUEST['reset'] == "true" || $_REQUEST['reset'] == "yes")){ $_SESSION['Reset'] = true; }else{ $_SESSION['Reset'] = false; }
	include("config.php");
	include("header.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns#" lang="en">
   <!--Start Header-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name='viewport' content="width=device-width, initial-scale=1">
        <link rel="icon" href="/img/favicon.ico">
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <link rel="alternate" type="application/atom+xml" title="<?php echo $_SESSION['Title']; ?>" href="http://<?php echo $_SESSION['Domain']; ?>/rss/">
        <?php
		//Title, Meta-Description, Meta-Keywords & Open Graph Meta		
			echo "<title>".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."</title>
			<meta property=\"og:title\" content=\"".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."\" />
			<meta property=\"og:url\" content=\"".$_SESSION['Domain'].$_SERVER['REQUEST_URI']."\" />
			<meta name=\"description\" content=\"".$_SESSION['Page']['meta-description']."\">
			<meta property=\"og:description\" content=\"".$_SESSION['Page']['meta-description']."\" />
			<meta name=\"keywords\" content=\"".$_SESSION['Page']['meta-keywords']."\">";
			if(isset($_SESSION['Page']['meta-og-type'])){ echo "<meta property=\"og:type\" content=\"".$_SESSION['Page']['meta-og-type']."\" />";}
			else{ echo "<meta property=\"og:type\" content=\"website\" />"; }
			if(isset($_SESSION['Page']['meta-og-image'])){
				echo "<meta property=\"og:image\" content=\"".$_SESSION['Domain'].$_SESSION['Page']['meta-og-image']."\" />
				<meta property=\"og:image:secure_url\" content=\"".$_SESSION['Domain'].$_SESSION['Page']['meta-og-image']."\" />
				<meta property=\"og:image:type\" content=\"image/".pathinfo($_SESSION['Page']['meta-og-image'],PATHINFO_EXTENSION)."\">
				<meta property=\"og:image:height\" content=\"".$_SESSION['Page']['meta-og-image-height']."\" />
				<meta property=\"og:image:width\" content=\"".$_SESSION['Page']['meta-og-image-width']."\" />";
			}else{
				list($_SESSION['Page']['meta-og-image-width'], $_SESSION['Page']['meta-og-image-height'], $_SESSION['Page']['meta-og-image-type']) = getimagesize($_SESSION['Domain']."/img/logo.png");
				echo "<meta property=\"og:image\" content=\"".$_SESSION['Domain']."/img/logo.png\" />
				<meta property=\"og:image:secure_url\" content=\"".$_SESSION['Domain']."/img/logo.png\" />
				<meta property=\"og:image:type\" content=\"image/".pathinfo("/img/logo.png",PATHINFO_EXTENSION)."\">
				<meta property=\"og:image:height\" content=\"".$_SESSION['Page']['meta-og-image-height']."\" />
				<meta property=\"og:image:width\" content=\"".$_SESSION['Page']['meta-og-image-width']."\" />"; 
			}
		//Concatenate CSS Files
			$css = file_get_contents("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
			$css .= file_get_contents("css/bootstrap-datetimepicker.min.css");
			$css .= file_get_contents("css/trumbowyg.min.css");
			$css .= file_get_contents("css/lightbox.css");
			$css .= file_get_contents("css/main.css");
			$css .= file_get_contents("css/blog.css");
			$css .= file_get_contents("css/media.css");
			echo "<style>".$css."</style>";
		//Load JS Libs
			$js ="<!--Start Head Loader--><script type=\"text/javascript\">";
			$js .= file_get_contents("_js/head.min.js");
        	$js .= "head.load(\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\",\"/_js/moment.min.js\",\"/_js/bootstrap.min.js\",\"/_js/lightbox.js\",\"https://www.google-analytics.com/analytics.js\",\"https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57bf0be13e45dd09\",\"/_js/bootstrap-datetimepicker.min.js\",\"/_js/trumbowyg.min.js\",\"/_js/lib.js\"); </script><!--End Head Loader-->";
			echo $js;
		?>
    </head>
   <!--End Header-->
    <body role="document">
       <!--Start Page-->
        <div id="page" class="container-fluid">
            <nav id="menu" class="navbar navbar-default navbar-static-top<?php if($_SESSION['Page']['path-file'] == "/page/index.php"){ echo " hidden"; } ?>">
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
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center"><p>&copy;<?php echo $_SESSION['Title']. " ". date("Y",time()); ?> - All Rights Reserved</p><a href="http://www.kburkhart.com" target="_blank"><img src="/img/KBDicon.svg" alt="Katharine Burkhart Designs" /></a></div>
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
				$("#page").on("click","ul.nav a, .navbar-brand, .navl, .bnavl, .mnavl", function(event){ event.preventDefault(); if($(this).attr("href") != "#"){ var alink = $(this); $("#page").fadeOut(to,function(){ window.location.assign(alink.attr("href")); }); } });
				$("ul.nav a").each(function(index){ if($(this).attr("href") === window.location.pathname ){ $(this).parent().addClass("active");} });
			/* Window Scroll Event - Content Fade In */
				$(window).scroll(function(){
					$('.hideme').each(function(i){
						var bottom_of_object = $(this).offset().top + ($(this).outerHeight() * 0.25);
						var bottom_of_window = $(window).scrollTop() + $(window).height();
						if( bottom_of_window > bottom_of_object ){ $(this).animate({'opacity':'1'},350); }
					});
				});
			/* Hide Images with NULL src - needs improvement*/
				$("img").each(function(){ if($(this).attr("src") == ""){ $(this).addClass("hidden"); } });
			/* Trumbowyg Editor */
				$.trumbowyg.svgPath = '/img/trumbowyg_icons.svg';
			/* Google Analytics */
				gaTracker("<?php echo $_SESSION['Google']['gaID']; ?>");
				gaTrack();
			/* Mobile Menu - Toggle Page Scroll Lock */
				//$(".navbar-toggle").click(function(){ if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }else{ $("body").addClass("noscroll"); } });
			});
		});
		</script>
    </body>
</html>
<?php session_write_close(); ?>