<div id="pg" class="container-fluid">
	<div class="row">
    	<div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
			<h1>Error</h1>
            <h2>401 - Unauthorized</h2>
            <?php if($_SESSION['Error']['401'] != NULL){ echo "<!-- Message -->"; } ?>
            <?php echo "<hr><address>".$_SERVER['SERVER_SOFTWARE']." Server at ".$_SERVER['SERVER_NAME']." Port 80</address>"; ?>
        </div>
    </div>
</div>