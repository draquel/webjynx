<?php
	require_once("script/_php/lib.php");
	include("script/_php/DBObj/dbobj.php");
	session_start();
	//Initialize Database Connection
	$_SESSION['dbName'] = "DBObj";
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init("localhost","root","Ed17i0n!");
	$_SESSION['db']->connect($_SESSION['dbName']);
	
	//Initialize Blog Data
	$_SESSION['Blog'] = new Blog(1);
	$_SESSION['Blog']->dbRead($_SESSION['db']->con($_SESSION['dbName']));
	$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']));
	
	//Initialize Site Data
	$_SESSION['Pages'] = array(
		array("id"=>0,"meta-title"=>"HTTP 404 - Page Not Found","meta-description"=>"HTTP 404 - Page Not Found","meta-keywords"=>NULL,"path-ui"=>"/404","path-file"=>"/page/404.php"),
		array("id"=>1,"meta-title"=>"HTTP 401 - Unauthorized","meta-description"=>"HTTP 401 - Unauthorized","meta-keywords"=>NULL,"path-ui"=>"/401","path-file"=>"/page/401.php"),
		array("id"=>2,"meta-title"=>"Index","meta-description"=>"Welcome to our home page!","meta-keywords"=>NULL,"path-ui"=>"/","path-file"=>"/page/index.php"),
		array("id"=>3,"meta-title"=>"About","meta-description"=>"We like stuff and want to work together on your things!","meta-keywords"=>NULL,"path-ui"=>"/about/","path-file"=>"/page/about/index.php"),
		array("id"=>4,"meta-title"=>"Other","meta-description"=>"Some more stuff we think is neat.","meta-keywords"=>NULL,"path-ui"=>"/about/other","path-file"=>"/page/about/other.php"),
		array("id"=>5,"meta-title"=>"Sitemap","meta-description"=>"A sitemap, just incase you get lost.","meta-keywords"=>NULL,"path-ui"=>"/sitemap","path-file"=>"/page/sitemap.php"),
		array("id"=>6,"meta-title"=>"Class Testing","meta-description"=>"Class Unit Testing","meta-keywords"=>NULL,"path-ui"=>"/class/","path-file"=>"/page/class/index.php"),
		array("id"=>7,"meta-title"=>"Blog","meta-description"=>"Our Blog","path-ui"=>"/blog/","meta-keywords"=>NULL,"path-file"=>"/page/blog.php")
	);
	$_SESSION['Title'] = "Company Name";
	$_SESSION['Error'] = array("404"=>array("path-file"=>NULL,"path-ui"=>NULL),"401"=>NULL);
	
	//Process Initial Page Address
	if(isset($_REQUEST['pg']) && $_REQUEST['pg'] != "" && $_REQUEST['pg'] != NULL){	
		$found = false; 
		foreach($_SESSION['Pages'] as $page){ 
			if($page['path-ui'] == "/".strtolower($_REQUEST['pg'])){ 
				$found = true; $_SESSION['Page'] = $page;
				if(!file_exists(ltrim($page['path-file'],"/"))){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-file'] = $page['path-file']; }
				break;
			} 
		}
		if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = "/".$_REQUEST['pg']; }
	}else{ $_SESSION['Page'] = $_SESSION['Pages'][2]; }
	session_write_close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content="width=device-width, initial-scale=1">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <link rel="icon" href="/img/favicon.ico">
        <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
        <?php
		//Title & Meta-Description 
			echo "<title>".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."</title><meta name=\"description\" content=\"".$_SESSION['Page']['meta-description']."\"><meta name=\"keywords\" content=\"".$_SESSION['Page']['meta-keywords']."\">";
		//Concatenate CSS Files
			$css = file_get_contents("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
			$css .= file_get_contents("css/main.css");
			$css .= file_get_contents("css/blog.css");
			echo "<style>".$css."</style>";
		//Load JS Libs
			$headjs ="<!--Start Head Loader--><script type=\"text/javascript\">";
			$headjs .= file_get_contents("script/_js/head.min.js");
        	$headjs .= "head.load(\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js\",\"https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js\",\"https://www.google-analytics.com/analytics.js\",\"/script/_js/lib.js\"); </script><!--End Head Loader-->";
			echo $headjs;
		?>
    </head>
    <body role="document">
    	<img class="loader" id="loader-main" src="/img/loader-main.gif" alt="... Loading ..."/>
       <!--Start Page-->
        <div id="page" class="container-fluid">
            <div id="menu" class="row">
                <nav class="navbar navbar">
                    <div id="head" class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                        <div class="navbar-header col-sm-4 col-md-4 col-lg-4"><button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="/" target="#content"><img src="/img/logo.png" alt="Company Name" /></a></div>
                        <div id="navigation" class="collapse navbar-collapse navbar-ex1-collapse col-sm-8 col-md-8 col-lg-8"><?php $_REQUEST['dd'] = 1; include("menu.php"); ?></div>
                    </div>
                </nav>
            </div>
            <div id="contentWrapper" class="row"><div id="content"><!-- Page Content --><?php include(ltrim($_SESSION['Page']['path-file'],"/")); ?></div></div>
            <div id="footer" class="row">
            	<div class="col-xs-12 col-xs-offset-0 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4"><p>info@yourcompany.com<br>(123)456-7890</p><p><a href="https://twitter.com/" target="_blank"><img src="/img/twitter.svg" alt="Twitter" /></a><a href="https://www.facebook.com/" target="_blank"><img src="/img/facebook.svg" alt="Facebook" /></a><a href="https://www.linkedin.com/" target="_blank"><img src="/img/linkedin.svg" alt="LinkedIn" /></a></p></div>
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
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="myModalLabel">Lorem ipsum dolor</h4></div>
                    <div class="modal-body"><!-- Modal Content --><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p></div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
        <script type="text/javascript">
			head.ready(function() {
				$(document).ready(function(){
					var to = 500; var page = "";
				/* Initialize Page Status / Preload Page Images */
					$.ajax({
						url: '/ajax.php',cache:false,method:'POST',async:true,dataType:"json",data:"ari=1&pid=<?php echo $_SESSION['Page']['id']; /* ... Hacky ... */ ?>",
						complete: function(xhr){ 
							var data = JSON.parse(xhr.responseText); page = data[0];
							if(page['path-file'] == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
							setTimeout(function(){$("#loader-main").fadeOut(to,function(){$("#page").fadeIn(to);$(this).remove();});},to);
						}
					}).done(function(){
					/* Browser Nav Override */
						$(window).bind('popstate',function(event){
							if(event.originalEvent.state){
								if(event.originalEvent.state.url == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
								$("#content").html(event.originalEvent.state.html);
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
							$(this).setContent(2,"#menu");
							if($(this).siblings().not(this).length === 0){ $(".navbar-collapse").collapse('hide'); }
							if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }
						});
					/* Blog Page Navigation */
						$("#page").on("click",".bnavl", function(event){
							event.preventDefault();
							$(this).setContent(3,"#menu");
						});
					/* Mobile Menu - Toggle Page Scroll Lock */
						$(".navbar-toggle").click(function(){ if($("body").hasClass("noscroll")){ $("body").removeClass("noscroll"); }else{ $("body").addClass("noscroll"); } });
					/* Label Free Form Elements */
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