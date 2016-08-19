<?php 
	if(!$_SESSION['db']->connect("DBObj")){	echo "CONNECTION FAILURE <br >"; } 
	elseif(!isset($_SESSION['Blog'])){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']->con("DBObj"));
		$_SESSION['Blog']->load($_SESSION['db']->con("DBObj"));
	}
	$blog = $_SESSION['Blog']->toArray();
	$blog_css = file_get_contents("css/blog.css");
?>
<!-- Page Specific Styles -->
	<style>	#pg > div:nth-child(1){ background-image:url('/img/stock_head1.svg'); } <?php echo $blog_css; ?> </style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!--Page Content -->
    <div id="pg" class="container-fluid">
        <div class="row blue_bg">
            <div></div>
        </div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                <div class="blog-header">
                <h1 class="blog-title"><?php echo $blog['Title']; ?></h1>
                <p class="lead blog-description"><?php echo $blog['Description']; ?></p>
                </div>
                <div class="col-sm-8 blog-main">
                  <?php 
						if(isset($_REQUEST['p'])){ include("post.php"); }
						elseif(isset($_REQUEST['c'])){ include("category.php"); }
						elseif(isset($_REQUEST['u'])){ include("author.php"); }
						elseif(isset($_REQUEST['a'])){ include("archive.php"); }
						else{ include("page.php"); }
				  ?>
                </div>
                <div class="col-sm-3 col-sm-offset-1 blog-sidebar">
                  <div class="sidebar-module sidebar-module-inset">
                    <h4>About</h4>
                    <p><?php echo $blog['Description']; ?></p>
                  </div>
                  <div class="sidebar-module">
                    <h4>Archives</h4>
                    <ol class="list-unstyled">
					<?php
                        $archive = $_SESSION['Blog']->getPosts()->getArchive();
                        foreach($archive as $k => $v){ echo "<li><a href=\"#\">".date("M Y",strtotime($k))."</a></li>"; }
                    ?>
                    </ol>
                  </div>
                  <div class="sidebar-module">
                    <h4>Categories</h4>
                    <ol class="list-unstyled">
                    <?php
						$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
						while($cat != NULL){
							$c = $cat->readNode()->toArray();
							echo "<li><a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\" target=\"div.blog-main\">".$c['Definition']."</a></li>";
							$cat = $cat->getNext();
						}
					?>
                    </ol>
                  </div>
                  <div class="sidebar-module">
                    <h4>Elsewhere</h4>
                    <ol class="list-unstyled">
                      <li><a href="#">GitHub</a></li>
                      <li><a href="#">Twitter</a></li>
                      <li><a href="#">Facebook</a></li>
                    </ol>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <?php
		unset($_REQUEST['p']);
		unset($_REQUEST['c']);
		unset($_REQUEST['u']);
		unset($_REQUEST['a']);
	?>