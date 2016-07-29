<?php
	session_start();
	
	/* Page Index */
	$_SESSION['Pages'] = array(	
		array("id"=>0,"meta-title"=>"Index","meta-description"=>"Welcome to our home page!","path-ui"=>"/","path-file"=>"/page/index.php"),
		array("id"=>1,"meta-title"=>"About","meta-description"=>"We like stuff and want to work together on your things!","path-ui"=>"/pg/about/","path-file"=>"/page/about/index.php"),
		array("id"=>2,"meta-title"=>"Other","meta-description"=>"Some more stuff we think is neat.","path-ui"=>"/pg/about/barbara","path-file"=>"/page/about/othe.php"),
		array("id"=>3,"meta-title"=>"Sitemap","meta-description"=>"A sitemap, just incase you get lost.","path-ui"=>"/pg/sitemap","path-file"=>"/page/sitemap.php")
	);
	
	$_SESSION['Title'] = "Company Name";
	
	require_once("script/_php/lib.php");
	if(isset($_REQUEST['pg']) && $_REQUEST['pg'] != "" && $_REQUEST['pg'] != NULL && $_REQUEST['pg'] != "/"){
		if(substr($_REQUEST['pg'],-1) != "/"){ $page = "/page/".$_REQUEST['pg'] .".php"; }else{ $page = "/page/".$_REQUEST['pg'] . "index.php";	}
		foreach($_SESSION['Pages'] as $key => $value){ if($value['path-file'] == strtolower($page)){ $_SESSION['Page'] = $value; break;} }
	}else{ $_SESSION['Page'] = $_SESSION['Pages'][0]; }
	if(isset($_REQUEST['a']) && $_REQUEST['a'] != "" && $_REQUEST['a'] != NULL){ $_SESSION['article'] = $_REQUEST['a']; }else{ $_SESSION['article'] = NULL; }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name='viewport' content="width=device-width, initial-scale=0.65">
        <?php
			//Title & Meta-Description 
			echo "<title>".$_SESSION['Title']." - ".$_SESSION['Page']['meta-title']."</title><meta name=\"description\" content=\"".$_SESSION['Page']['meta-description']."\">";
			//Concatenate CSS Files
			$bs_css = file_get_contents("css/bootstrap.min.css");
			$css = file_get_contents("css/main.css");
			if(isMobile()){ $r_css = file_get_contents("css/main_m.css"); }else{ $r_css = file_get_contents("css/main_d.css"); }
			echo "<style>\n\n".$bs_css."\n\n".$css."\n\n".$r_css."\n\n</style>";
		?>
       <!--Start Head Loader-->
        <script type="text/javascript">
			/*! head.core - v1.0.2 */
			(function(n,t){"use strict";function r(n){a[a.length]=n}function k(n){var t=new RegExp(" ?\\b"+n+"\\b");c.className=c.className.replace(t,"")}function p(n,t){for(var i=0,r=n.length;i<r;i++)t.call(n,n[i],i)}function tt(){var t,e,f,o;c.className=c.className.replace(/ (w-|eq-|gt-|gte-|lt-|lte-|portrait|no-portrait|landscape|no-landscape)\d+/g,"");t=n.innerWidth||c.clientWidth;e=n.outerWidth||n.screen.width;u.screen.innerWidth=t;u.screen.outerWidth=e;r("w-"+t);p(i.screens,function(n){t>n?(i.screensCss.gt&&r("gt-"+n),i.screensCss.gte&&r("gte-"+n)):t<n?(i.screensCss.lt&&r("lt-"+n),i.screensCss.lte&&r("lte-"+n)):t===n&&(i.screensCss.lte&&r("lte-"+n),i.screensCss.eq&&r("e-q"+n),i.screensCss.gte&&r("gte-"+n))});f=n.innerHeight||c.clientHeight;o=n.outerHeight||n.screen.height;u.screen.innerHeight=f;u.screen.outerHeight=o;u.feature("portrait",f>t);u.feature("landscape",f<t)}function it(){n.clearTimeout(b);b=n.setTimeout(tt,50)}var y=n.document,rt=n.navigator,ut=n.location,c=y.documentElement,a=[],i={screens:[240,320,480,640,768,800,1024,1280,1440,1680,1920],screensCss:{gt:!0,gte:!1,lt:!0,lte:!1,eq:!1},browsers:[{ie:{min:6,max:11}}],browserCss:{gt:!0,gte:!1,lt:!0,lte:!1,eq:!0},html5:!0,page:"-page",section:"-section",head:"head"},v,u,s,w,o,h,l,d,f,g,nt,e,b;if(n.head_conf)for(v in n.head_conf)n.head_conf[v]!==t&&(i[v]=n.head_conf[v]);u=n[i.head]=function(){u.ready.apply(null,arguments)};u.feature=function(n,t,i){return n?(Object.prototype.toString.call(t)==="[object Function]"&&(t=t.call()),r((t?"":"no-")+n),u[n]=!!t,i||(k("no-"+n),k(n),u.feature()),u):(c.className+=" "+a.join(" "),a=[],u)};u.feature("js",!0);s=rt.userAgent.toLowerCase();w=/mobile|android|kindle|silk|midp|phone|(windows .+arm|touch)/.test(s);u.feature("mobile",w,!0);u.feature("desktop",!w,!0);s=/(chrome|firefox)[ \/]([\w.]+)/.exec(s)||/(iphone|ipad|ipod)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(android)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(webkit|opera)(?:.*version)?[ \/]([\w.]+)/.exec(s)||/(msie) ([\w.]+)/.exec(s)||/(trident).+rv:(\w.)+/.exec(s)||[];o=s[1];h=parseFloat(s[2]);switch(o){case"msie":case"trident":o="ie";h=y.documentMode||h;break;case"firefox":o="ff";break;case"ipod":case"ipad":case"iphone":o="ios";break;case"webkit":o="safari"}for(u.browser={name:o,version:h},u.browser[o]=!0,l=0,d=i.browsers.length;l<d;l++)for(f in i.browsers[l])if(o===f)for(r(f),g=i.browsers[l][f].min,nt=i.browsers[l][f].max,e=g;e<=nt;e++)h>e?(i.browserCss.gt&&r("gt-"+f+e),i.browserCss.gte&&r("gte-"+f+e)):h<e?(i.browserCss.lt&&r("lt-"+f+e),i.browserCss.lte&&r("lte-"+f+e)):h===e&&(i.browserCss.lte&&r("lte-"+f+e),i.browserCss.eq&&r("eq-"+f+e),i.browserCss.gte&&r("gte-"+f+e));else r("no-"+f);r(o);r(o+parseInt(h,10));i.html5&&o==="ie"&&h<9&&p("abbr|article|aside|audio|canvas|details|figcaption|figure|footer|header|hgroup|main|mark|meter|nav|output|progress|section|summary|time|video".split("|"),function(n){y.createElement(n)});p(ut.pathname.split("/"),function(n,u){if(this.length>2&&this[u+1]!==t)u&&r(this.slice(u,u+1).join("-").toLowerCase()+i.section);else{var f=n||"index",e=f.indexOf(".");e>0&&(f=f.substring(0,e));c.id=f.toLowerCase()+i.page;u||r("root"+i.section)}});u.screen={height:n.screen.height,width:n.screen.width};tt();b=0;n.addEventListener?n.addEventListener("resize",it,!1):n.attachEvent("onresize",it)})(window);
			/*! head.load - v1.0.3 */
			(function(n,t){"use strict";function w(){}function u(n,t){if(n){typeof n=="object"&&(n=[].slice.call(n));for(var i=0,r=n.length;i<r;i++)t.call(n,n[i],i)}}function it(n,i){var r=Object.prototype.toString.call(i).slice(8,-1);return i!==t&&i!==null&&r===n}function s(n){return it("Function",n)}function a(n){return it("Array",n)}function et(n){var i=n.split("/"),t=i[i.length-1],r=t.indexOf("?");return r!==-1?t.substring(0,r):t}function f(n){(n=n||w,n._done)||(n(),n._done=1)}function ot(n,t,r,u){var f=typeof n=="object"?n:{test:n,success:!t?!1:a(t)?t:[t],failure:!r?!1:a(r)?r:[r],callback:u||w},e=!!f.test;return e&&!!f.success?(f.success.push(f.callback),i.load.apply(null,f.success)):e||!f.failure?u():(f.failure.push(f.callback),i.load.apply(null,f.failure)),i}function v(n){var t={},i,r;if(typeof n=="object")for(i in n)!n[i]||(t={name:i,url:n[i]});else t={name:et(n),url:n};return(r=c[t.name],r&&r.url===t.url)?r:(c[t.name]=t,t)}function y(n){n=n||c;for(var t in n)if(n.hasOwnProperty(t)&&n[t].state!==l)return!1;return!0}function st(n){n.state=ft;u(n.onpreload,function(n){n.call()})}function ht(n){n.state===t&&(n.state=nt,n.onpreload=[],rt({url:n.url,type:"cache"},function(){st(n)}))}function ct(){var n=arguments,t=n[n.length-1],r=[].slice.call(n,1),f=r[0];return(s(t)||(t=null),a(n[0]))?(n[0].push(t),i.load.apply(null,n[0]),i):(f?(u(r,function(n){s(n)||!n||ht(v(n))}),b(v(n[0]),s(f)?f:function(){i.load.apply(null,r)})):b(v(n[0])),i)}function lt(){var n=arguments,t=n[n.length-1],r={};return(s(t)||(t=null),a(n[0]))?(n[0].push(t),i.load.apply(null,n[0]),i):(u(n,function(n){n!==t&&(n=v(n),r[n.name]=n)}),u(n,function(n){n!==t&&(n=v(n),b(n,function(){y(r)&&f(t)}))}),i)}function b(n,t){if(t=t||w,n.state===l){t();return}if(n.state===tt){i.ready(n.name,t);return}if(n.state===nt){n.onpreload.push(function(){b(n,t)});return}n.state=tt;rt(n,function(){n.state=l;t();u(h[n.name],function(n){f(n)});o&&y()&&u(h.ALL,function(n){f(n)})})}function at(n){n=n||"";var t=n.split("?")[0].split(".");return t[t.length-1].toLowerCase()}function rt(t,i){function e(t){t=t||n.event;u.onload=u.onreadystatechange=u.onerror=null;i()}function o(f){f=f||n.event;(f.type==="load"||/loaded|complete/.test(u.readyState)&&(!r.documentMode||r.documentMode<9))&&(n.clearTimeout(t.errorTimeout),n.clearTimeout(t.cssTimeout),u.onload=u.onreadystatechange=u.onerror=null,i())}function s(){if(t.state!==l&&t.cssRetries<=20){for(var i=0,f=r.styleSheets.length;i<f;i++)if(r.styleSheets[i].href===u.href){o({type:"load"});return}t.cssRetries++;t.cssTimeout=n.setTimeout(s,250)}}var u,h,f;i=i||w;h=at(t.url);h==="css"?(u=r.createElement("link"),u.type="text/"+(t.type||"css"),u.rel="stylesheet",u.href=t.url,t.cssRetries=0,t.cssTimeout=n.setTimeout(s,500)):(u=r.createElement("script"),u.type="text/"+(t.type||"javascript"),u.src=t.url);u.onload=u.onreadystatechange=o;u.onerror=e;u.async=!1;u.defer=!1;t.errorTimeout=n.setTimeout(function(){e({type:"timeout"})},7e3);f=r.head||r.getElementsByTagName("head")[0];f.insertBefore(u,f.lastChild)}function vt(){for(var t,u=r.getElementsByTagName("script"),n=0,f=u.length;n<f;n++)if(t=u[n].getAttribute("data-headjs-load"),!!t){i.load(t);return}}function yt(n,t){var v,p,e;return n===r?(o?f(t):d.push(t),i):(s(n)&&(t=n,n="ALL"),a(n))?(v={},u(n,function(n){v[n]=c[n];i.ready(n,function(){y(v)&&f(t)})}),i):typeof n!="string"||!s(t)?i:(p=c[n],p&&p.state===l||n==="ALL"&&y()&&o)?(f(t),i):(e=h[n],e?e.push(t):e=h[n]=[t],i)}function e(){if(!r.body){n.clearTimeout(i.readyTimeout);i.readyTimeout=n.setTimeout(e,50);return}o||(o=!0,vt(),u(d,function(n){f(n)}))}function k(){r.addEventListener?(r.removeEventListener("DOMContentLoaded",k,!1),e()):r.readyState==="complete"&&(r.detachEvent("onreadystatechange",k),e())}var r=n.document,d=[],h={},c={},ut="async"in r.createElement("script")||"MozAppearance"in r.documentElement.style||n.opera,o,g=n.head_conf&&n.head_conf.head||"head",i=n[g]=n[g]||function(){i.ready.apply(null,arguments)},nt=1,ft=2,tt=3,l=4,p;if(r.readyState==="complete")e();else if(r.addEventListener)r.addEventListener("DOMContentLoaded",k,!1),n.addEventListener("load",e,!1);else{r.attachEvent("onreadystatechange",k);n.attachEvent("onload",e);p=!1;try{p=!n.frameElement&&r.documentElement}catch(wt){}p&&p.doScroll&&function pt(){if(!o){try{p.doScroll("left")}catch(t){n.clearTimeout(i.readyTimeout);i.readyTimeout=n.setTimeout(pt,50);return}e()}}()}i.load=i.js=ut?lt:ct;i.test=ot;i.ready=yt;i.ready(r,function(){y()&&u(h.ALL,function(n){f(n)});i.feature&&i.feature("domloaded",!0)})})(window);
		</script>
        <script> head.load("/script/_js/jquery-3.1.0.min.js","/script/_js/bootstrap.min.js","/script/_js/analytics.js","/script/_js/lib.min.js"); </script>
       <!--End Head Loader-->
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
                            <a class="navbar-brand" href="/pg/index" target="#content"><img src="/img/logo.png" alt="Company Name" /></a>
                        </div>
                        <div id="navigation" class="collapse navbar-collapse navbar-ex1-collapse col-sm-12 col-md-9 col-lg-8"><?php $_REQUEST['dd'] = 1; include("menu.php"); ?></div>
                    </div>
                </nav>
            </div>
            <div id="contentWrapper" class="row"><div id="content"> <?php include(ltrim($_SESSION['Page']['path-file'],"/")); ?></div></div>
            <div id="footer" class="row">
                <div class="col-md-10 col-md-offset-1 col-lg-3 col-lg-offset-1">
                    <p>info@yourcompany.com<br>(123)456-7890</p>
                    <p><a href="https://twitter.com/" target="_blank"><img src="/img/twitter.svg" alt="Twitter" /></a><a href="https://www.facebook.com/" target="_blank"><img src="/img/facebook.svg" alt="Facebook" /></a><a href="https://www.linkedin.com/" target="_blank"><img src="/img/linkedin.svg" alt="LinkedIn" /></a></p>
                </div>
                <div class="col-md-12 col-lg-7" id="fmenu"><?php $_REQUEST['dd'] = 0; include("menu.php"); ?></div>
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
                        <h4 class="modal-title" id="myModalLabel">Hire Us</h4>
                    </div>
                    <div class="modal-body">
                        <!-- Modal Content -->
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
					/* Wordpress Integration - iFrame Height Message Handler */
						/*$(window).on("message", function(event) {
							var h = 0;
							var page = window.location.pathname;
							if(event.originalEvent.origin !== "http://wordpress.company.com"){ return; } 
							if(event.originalEvent.data[0] == 0 || event.originalEvent.data[0] == 11120 || event.originalEvent.data[0] == 35025){ if(event.originalEvent.data[0] == 0 && event.originalEvent.data[1].replace("/index.php","") == "/"){ h = 1800; }else{h = 5000;} }else{ if(event.originalEvent.data[0] > 20000){ h = event.originalEvent.data[0]/28; }else{ h = event.originalEvent.data[0]; } }
							if(event.originalEvent.data[1] && page.includes("/pg/blog")){ window.history.pushState({"html":$("#content").html(),"url":"/page/blog"+encodeURI(event.originalEvent.data[1].replace("/index.php",""))},"","/pg/blog"+encodeURI(event.originalEvent.data[1].replace("/index.php",""))); }
							$("#blog").css({height: h+"px"});
						})
						if(typeof deferBlog != 'undefined' && deferBlog){ $.ajax({url:'/ajax.php',method:'POST',async:true,dataType:"html",data:"ari=3",complete:function(xhr){ $("#blog").attr("src",xhr.responseText); } }); }
						if(typeof deferBlogWidget != 'undefined' && deferBlogWidget){ loadBlog(blogAdd); }
						*/
						
					/* Browser Nav Override */
						$(window).bind('popstate',function(event){
							if(event.originalEvent.state){
								if(event.originalEvent.state.url == "/page/index.php"){ $("#menu").hide(); }else{ $("#menu").show(); }
								/*Wordpress Integration - Iframe Address handling */
								//if(!event.originalEvent.state.url.includes("/blog/")){ $("#content").html(event.originalEvent.state.html); }else{ goToPage("#content","/page/blog.php",null); }
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