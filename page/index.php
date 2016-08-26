<!-- Page Specific Styles -->
	<style>
		#pg .row{ padding:150px 0; }
		#pg h3{ font-size:30px; }
		#pg > div:nth-child(3){ background-image:url('/img/stock_head1.svg'); }
		#pg > div:nth-child(5){ background-image:url('/img/stock_head2.svg'); }
		#hmenu{ background-color:#262729 !important; padding:175px 0; }
		#hmenu a{ color:#FFF; font-size:36px; position:relative; }
		#hmenu a::after{ content:''; height:140%; width:80%; border-bottom: #FFF thin solid;  position: absolute; top: 0; left: 10%; -webkit-transition: width 1s, left 1s; transition: width 1s, left 1s;}
		#hmenu a:hover::after{ left:0; width:100%; border-bottom: #0071CE thin solid; }
		#hmenu a:hover{ color:#0071CE; text-decoration:none;}
		#hmenu > div > div{ margin-bottom:24px !important;}
	</style>
<!-- Preload CSS Images -->
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
    <img class="hidden" src="/img/stock_head2.svg" alt="Header img 2" />
<!--Page Content -->
    <div id="pg" class="container-fluid">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-1 col-sm-8 col-sm-offset-1 col-md-8 col-md-offset-1 col-lg-7 col-lg-offset-1">
                <h1><?php echo $_SESSION['Title']; ?></h1>
                <p>Tagline</p>
                <a class='navl button' href="/about/" target="#content">Page 1</a>
            </div>
        </div>
        <div id="hmenu" class="row">
            <div class="col-md-12 text-center">
                <div class="col-md-4 text-center"><a class='navl' href="/about/" target="#content">PAGE 1</a></div>
                <div class="col-md-4 text-center"><a class='navl' href="/about/other" target="#content">PAGE 2</a></div>
                <div class="col-md-4 text-center"><a class='navl' href="/sitemap" target="#content">PAGE 3</a></div>
            </div>
        </div>
        <div class="row blue_bg">
            <div></div>
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-5 blue hideme">
                <h2>Lorem ipsum dolor</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
                <a class='navl button' href="/about/" target="#content">Page 1</a>
            </div>
        </div>
        <div class="row">
            <div></div>
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-1 col-lg-6 col-lg-offset-1 hideme">
                <h2>Lorem ipsum dolor</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
                <a class='navl button' href="/about/other" target="#content" >Page 2</a>
            </div>
        </div>
        <div class="row blue_bg">
            <div></div>
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-5 blue hideme">
                <h2>Lorem ipsum dolor</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer aliquam, quam vitae semper fringilla, nunc lectus pulvinar ante, a malesuada ante mi sed neque. Morbi sagittis metus quam, sed semper mi venenatis at. Praesent in diam eu justo consectetur dignissim. Donec pharetra, tortor et maximus hendrerit, ex risus semper erat, ac auctor odio nisi eget est. Fusce eget pulvinar nunc, vitae ultricies sapien. Aliquam at facilisis erat, accumsan scelerisque elit. Aenean viverra quis lacus vel dapibus. Fusce vestibulum ligula sed magna fringilla, non fringilla lectus faucibus. Sed dignissim dui a arcu ultricies facilisis. Donec pretium egestas lectus, vitae luctus lorem malesuada in.</p>
                <a class='navl button' href="/sitemap" target="#content">Page 3</a>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 text-center hideme">
                <h2>Lorem ipsum dolor</h2>
                <br>
                <a class="button" target="#" type="button" data-toggle="modal" data-target="#Modal">Modal Window</a><a class='navl button' href="/about/" target="#content">Page 1</a><a class='navl button' href="/about/other" target="#content">Page 2</a><a class='navl button' href="/sitemap" target="#content">Page 3</a>
            </div>
        </div>
    </div>