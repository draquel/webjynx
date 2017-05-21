<?php 
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
?>
<!-- Page Specific Styles -->
	<style>	#pg > div:nth-child(1){ background-image:url('/img/stock_head1.svg'); } </style>
<!--Page Content -->
    <div id="pg" class="container-fluid">
        <div class="row blue_bg">
            <div></div>
        </div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                <h1>About</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus iaculis elementum scelerisque. Donec at facilisis est. Aenean at lorem et tortor tempus faucibus. Aliquam molestie quam et porttitor volutpat. Vestibulum eleifend neque sed viverra consectetur. Sed nec hendrerit ante. Morbi laoreet lectus id nibh convallis, at pulvinar tortor ultricies. Nulla eget nisl in dolor condimentum rhoncus. Vestibulum ornare, mauris ut ultricies sagittis, risus dui porttitor urna, mollis semper ligula lorem et tortor. Vestibulum at orci at erat efficitur mattis. Donec in libero vel dui consequat venenatis at at nulla. Phasellus faucibus vulputate dictum. Sed elementum luctus sapien, eu eleifend tortor sollicitudin vel. Pellentesque nec maximus magna. Cras nisi nisi, aliquet nec quam sed, venenatis eleifend dolor.</p>
                <?php echo $_SESSION['Media']->genCarousel($_SESSION['db']->con($_SESSION['dbName']),"Gallery","Gallery 1"); ?>
           		<a class='navl button' href="/about/other" target="#content" style="margin-left: 0;">Link Button</a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
		head.ready(function(){
			$(document).ready(function(){
				$('#carousel-example-generic').carousel({interval: 10000});
			});
		});
	</script>