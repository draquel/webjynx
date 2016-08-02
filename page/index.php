<style>
	/* Template Overrides */
	#pg .row{ padding:150px 0; }
	#pg h3{ font-size:30px; }
	/* Page Specific */
	#hmenu{ background-color:#262729 !important; padding:175px 0; }
	#hmenu a{ color:#FFF; font-size:36px; position:relative; }
	#hmenu a::after{ content:''; height:140%; width:80%; border-bottom: #FFF thin solid;  position: absolute; top: 0; left: 10%; -webkit-transition: width 1s, left 1s; transition: width 1s, left 1s;}
	#hmenu a:hover::after{ left:0; width:100%; border-bottom: #0071CE thin solid; }
	#hmenu a:hover{ color:#0071CE; text-decoration:none;}
	#hmenu > div > div{ margin-bottom:24px !important;}
</style>
<div id="pg" class="container-fluid">
	<div class="row">
    	<div class="col-xs-6 col-xs-offset-1 col-sm-6 col-sm-offset-1 col-md-8 col-md-offset-1 col-lg-7 col-lg-offset-1">
            <h1>Company Name</h1>
            <p>Tagline</p>
            <a class='navl button' href="/pg/about/" target="#content">Page 1</a>
        </div>
    </div>
	<div id="hmenu" class="row">
    	<div class="col-md-12 text-center">
	        <div class="col-md-4 text-center"><a class='navl' href="/pg/about/" target="#content">PAGE 1</a></div>
        	<div class="col-md-4 text-center"><a class='navl' href="/pg/about/other" target="#content">PAGE 2</a></div>
            <div class="col-md-4 text-center"><a class='navl' href="/pg/sitemap" target="#content">PAGE 3</a></div>
        </div>
	</div>
    <div class="row blue_bg" style="background-image:url('/img/stock_head1.svg');">
    	<div></div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-5 blue hideme">
    		<h2>Lorem ipsum dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
        	<a class='navl button' href="/pg/about/" target="#content">Page 1</a>
        </div>
    </div>
    <div class="row">
    	<div></div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-1 col-lg-6 col-lg-offset-1 hideme">
            <h2>Lorem ipsum dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
            <a class='navl button' href="/pg/about/other" target="#content" style="padding:5px 30px;">Page 2</a>
        </div>
    </div>
    <div class="row blue_bg" style="background-image:url('/img/stock_head2.svg');">
    	<div></div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-5 blue hideme">
            <h2>Lorem ipsum dolor</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
            <a class='navl button' href="/pg/sitemap" target="#content" style="padding:5px 30px;">Page 3</a>
        </div>
    </div>
    <div class="row">
    	<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 text-center hideme">
		    <h2>Lorem ipsum dolor</h2>
            <br>
            <a class="navl button" target="#" type="button" data-toggle="modal" data-target="#Modal">Modal Window</a><a class='navl button' href="/pg/about/" target="#content">Page 1</a><a class='navl button' href="/pg/about/other" target="#content">Page 2</a><a class='navl button' href="/pg/sitemap" target="#content">Page 3</a>
        </div>
    </div>
</div>