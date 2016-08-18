<?php 
	if(!$_SESSION['db']->connect("DBObj")){	echo "CONNECTION FAILURE <br >"; } 

	$_SESSION['Blog'] = new Blog(1);
	$_SESSION['Blog']->dbRead($_SESSION['db']->con("DBObj"));
	$blog = $_SESSION['Blog']->toArray();
	$_SESSION['Blog']->load($_SESSION['db']->con("DBObj"));
	
	$blog_css = file_get_contents("css/blog.css");
	
	/*if(isset($_REQUEST['a'])){ echo "A - ".$_REQUEST['a']; }
	if(isset($_REQUEST['c'])){ echo "C - ".$_REQUEST['c']; }
	if(isset($_REQUEST['p'])){ echo "P - ".$_REQUEST['p']; }*/
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
				 	$posts = $_SESSION['Blog']->getPage(1);
					$post = $posts->getFirstNode();
					while($post != NULL){
						$p = $post->readNode();
						$a = $p->toArray();
						$html = "<div class=\"blog-post\">
							<h2 class=\"blog-post-title\">".$a['Title']."</h2>
							<p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created'])." by <a href=\"#\">";
						if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
							$user = $_SESSION['Users']->getFirstNode();
							while($user != NULL){ 
								$u = $user->readNode()->toArray();
								if($u['ID'] == $a['Author']){ $html .= $u['First']." ".$u['Last']; break; } 
								$user = $user->getNext();
							}
						}else{ $html .= $a['Author']; }
						$html .= "</a></p>
							".$a['HTML']."
						</div>";
						echo $html;
						$post = $post->getNext();
					}
				  ?>
                  <nav>
                    <ul class="pager">
                      <li><a href="#">Previous</a></li>
                      <li><a href="#">Next</a></li>
                    </ul>
                  </nav>
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
                        foreach($archive as $k => $v){
                            echo "<li><a href=\"#\">".$k."</a></li>";
                        }
                    ?>
                    </ol>
                  </div>
                  <div class="sidebar-module">
                    <h4>Categories</h4>
                    <ol class="list-unstyled">
                    <?php
						$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
						while($cat != NULL){
							$c = $cat->readNode();
							$a = $c->toArray();
							echo "<li><a href=\"".$a['Code']."\">".$a['Definition']."</a></li>";
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