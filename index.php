<?php
	session_start();
	
	/* Page Index */
	$_SESSION['Pages'] = array(
		array("id"=>0,"meta-title"=>"HTTP 404 - Page Not Found","meta-description"=>"HTTP 404 - Page Not Found","path-ui"=>"/404","path-file"=>"/page/404.php"),
		array("id"=>1,"meta-title"=>"Index","meta-description"=>"Welcome to our home page!","path-ui"=>"/","path-file"=>"/page/index.php"),
		array("id"=>2,"meta-title"=>"About","meta-description"=>"We like stuff and want to work together on your things!","path-ui"=>"/pg/about/","path-file"=>"/page/about/index.php"),
		array("id"=>3,"meta-title"=>"Other","meta-description"=>"Some more stuff we think is neat.","path-ui"=>"/pg/about/other","path-file"=>"/page/about/other.php"),
		array("id"=>4,"meta-title"=>"Sitemap","meta-description"=>"A sitemap, just incase you get lost.","path-ui"=>"/pg/sitemap","path-file"=>"/page/sitemap.php")
	);
	
	$_SESSION['Title'] = "Company Name";
	
	require_once("script/_php/lib.php");
	if(isset($_REQUEST['pg']) && $_REQUEST['pg'] != "" && $_REQUEST['pg'] != NULL){	
		$found = false;
		foreach($_SESSION['Pages'] as $page){ 
			if($page['path-ui'] == "/pg/".strtolower($_REQUEST['pg'])){ $found = true; $_SESSION['Page'] = $page; break; }
		}
		if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; }
	}else{ $_SESSION['Page'] = $_SESSION['Pages'][1]; }
	if(isset($_REQUEST['a']) && $_REQUEST['a'] != "" && $_REQUEST['a'] != NULL){ $_SESSION['article'] = $_REQUEST['a']; }else{ $_SESSION['article'] = NULL; }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content="width=device-width, initial-scale=0.65">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <script type="text/javascript">console.log("<?php echo $_REQUEST['pg']. " - ".$_SESSION['Page']['path-file']; ?>");</script>
        <?php
			//Title & Meta-Description 
			echo "<title>".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."</title><meta name=\"description\" content=\"".$_SESSION['Page']['meta-description']."\">";
			//Concatenate CSS Files
			$bs_css = file_get_contents("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
			$css = file_get_contents("css/main.css");
			if(isMobile()){ $r_css = file_get_contents("css/main_m.css"); }else{ $r_css = file_get_contents("css/main_d.css"); }
			echo "<style>\n\n".$bs_css."\n\n".$css."\n\n".$r_css."\n\n</style>";
			//Load JS Libs
			$head ="<!--Start Head Loader-->
        	<script type=\"text/javascript\">\n";
			$head .= file_get_contents("script/_js/head.min.js")."\n";
        	$head .= "</script>\n<script> head.load(\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\",\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\",\"https://www.google-analytics.com/analytics.js\",\"/script/_js/lib.js\"); </script>
       		<!--End Head Loader-->";
			echo $head;
		?>
    </head>
    <body role="document">
       <!--Start Page-->
        <div id="page" class="container-fluid">
            <div id="menu" class="row">
                <nav class="navbar navbar">
                    <div id="head" class="container-fluid col-lg-10 col-lg-offset-1">
                        <div class="navbar-header col-sm-12 col-md-12 col-lg-4">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="/" target="#content"><img src="/img/logo.png" alt="Company Name" /></a>
                        </div>
                        <div id="navigation" class="collapse navbar-collapse navbar-ex1-collapse col-sm-12 col-md-9 col-lg-8"><?php $_REQUEST['dd'] = 1; include("menu.php"); ?></div>
                    </div>
                </nav>
            </div>
            <div id="contentWrapper" class="row"><div id="content"><!-- Page Content --><?php include(ltrim($_SESSION['Page']['path-file'],"/")); ?></div></div>
            <div id="footer" class="row">
            	<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                    <div class="col-md-12 col-lg-4" style="padding-top:15px !important;">
                        <p>info@yourcompany.com<br>(123)456-7890</p>
                        <p><a href="https://twitter.com/" target="_blank"><img src="/img/twitter.svg" alt="Twitter" /></a><a href="https://www.facebook.com/" target="_blank"><img src="/img/facebook.svg" alt="Facebook" /></a><a href="https://www.linkedin.com/" target="_blank"><img src="/img/linkedin.svg" alt="LinkedIn" /></a></p>
                    </div>
                    <div class="col-md-12 col-lg-8" id="fmenu"><?php $_REQUEST['dd'] = 0; include("menu.php"); ?></div>
                </div>
            	<div class="col-md-12 text-center" style="margin-top:50px;"><p style="font-size:9px;">&copy;<?php echo $_SESSION['Title']; ?> 2016 - All Rights Reserved</p><a href="http://www.kburkhart.com" target="_blank"><img src="/img/KBDicon.svg" alt="Katharine Burkhart Designs" /></a></div>
            </div>
        </div>
       <!--End Page-->
       <!-- Start Modal -->
        <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Lorem ipsum dolor</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Modal Content -->
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <img class="loader" id="loader-main" src="/img/loader-main.gif" alt="... Loading ..."/>
        <a class="navl" style="display:none" href="/pg/sitemap" target="#content">Sitemap</a>
        <script>
			head.ready(function() {
				$(document).ready(function(){
					var to = 500; var page = "";
					/* Initialize Page Status / Preload img/* */
					$.ajax({
						url: '/ajax.php',cache:false,method:'POST',async:true,dataType:"json",data:"ari=0",
						complete: function(xhr){ 
							var data = JSON.parse(xhr.responseText); page = data[0]; var imgs = data[1];
							if(page['path-file'] == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
							preload(imgs,function(){setTimeout(function(){$("#loader-main").fadeOut(to,function(){$("#page").fadeIn(to);$(this).remove();});},to);});
						}
					}).done(function(){
					/* Browser Nav Override */
						$(window).bind('popstate',function(event){
							if(event.originalEvent.state){
								if(event.originalEvent.state.url == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
							}
						})
					/* Window Scroll Event - Content Fade */
						$(window).scroll(function(){
							$('.hideme').each(function(i){
								var bottom_of_object = $(this).offset().top + ($(this).outerHeight() * 0.25);
								var bottom_of_window = $(window).scrollTop() + $(window).height();
								if( bottom_of_window > bottom_of_object ){ $(this).animate({'opacity':'1'},750); }
							}); 
						});
					/* Page Navigation */
						$("#page").on("click","ul.nav a, .navbar-brand, .navl", function(event){
							event.preventDefault();
							$(this).setContent("#menu");
							if($(this).siblings().not(this).length === 0){ $(".navbar-collapse").collapse('hide'); }
							if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }
						});
					/* Mobile Menu - Toggle Page Scroll Lock */
						$(".navbar-toggle").click(function(){ if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }else{ $("body").addClass("noscroll"); } });
						$("input[type=text],textarea").inputDefault();
					/* Google Analytics */
						//gaTracker("GA Tracking ID");
						//gaTrack(page['path-ui'],page['meta-title']);
					/* Server Session Timer */
						setSessTimeout();
					});
				});
			});
		</script>
    </body>
</html>