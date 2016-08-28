<?php
	
?>
<!-- Page Specific Styles -->
	<style>	#pg .navbar-nav > li > a:hover{ background-color:transparent !important; text-decoration:underline; }</style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!-- Page Content -->
<div id="pg" class="container-fluid">
    <div class="row">
    	<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
	        <h1>Sitemap</h1>
            <p>	<?php $_REQUEST['dd'] = 0; include("menu.php"); ?> </p>
        </div>
    </div>
</div>
<script type="text/javascript">
	head.ready(function() {
		$(document).ready(function(){
			$("#pg .navbar-right").removeClass("navbar-right");
		});
	});
</script>