<div id="pg" class="container-fluid">
	<div class="row">
    	<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1>Error</h1>
            <h2>404 - Page Not Found</h2>
            <?php 
				if($_SESSION['Error']['404']['path-ui'] != NULL){ echo "<p>The Page '".$_SESSION['Error']['404']['path-ui']."' was not found in the page index.</p>";}
				if($_SESSION['Error']['404']['path-file'] != NULL){ echo "<p>The file '".$_SESSION['Error']['404']['path-file']."' was not found.</p>";}
			 ?>
            <?php echo "<hr><address>".$_SERVER['SERVER_SOFTWARE']." Server at ".$_SERVER['SERVER_NAME']." Port 80</address>"; ?>
        </div>
    </div>
</div>