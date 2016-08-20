<?php 
	if(!$_SESSION['db']->connect("DBObj")){	echo "CONNECTION FAILURE <br >"; } 
	elseif(!isset($_SESSION['Blog'])){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']->con("DBObj"));
		$_SESSION['Blog']->load($_SESSION['db']->con("DBObj"));
	}
	$blog = $_SESSION['Blog']->toArray();
	$blog_css = file_get_contents("css/blog.css");
	if(isset($_REQUEST['p'])){ $bpage = "post"; }
	elseif(isset($_REQUEST['c'])){ $bpage = "category"; }
	elseif(isset($_REQUEST['u'])){ $bpage = "author"; }
	elseif(isset($_REQUEST['a'])){ $bpage = "archive"; }
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
                    switch($bpage){
                        case "post": /* POST PAGE */
                            $aid = $_REQUEST['p'];
                            $post = $_SESSION['Blog']->getPosts()->getFirstNode();
                            while($post != NULL){
                                $p = $post->readNode();
                                $a = $p->toArray();
                                if($a['ID'] == $aid){ $current = $post; break; }
                                $post = $post->getNext();
                            }
                            $html = "<div class=\"blog-post\">
                                <h1 class=\"blog-post-title\">".$a['Title']."</h1>
                                <p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                            if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
                                $user = $_SESSION['Users']->getFirstNode();
                                while($user != NULL){ 
                                    $u = $user->readNode()->toArray();
                                    if($u['ID'] == $a['Author']){ $html .= " by <a href=\"#\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                    $user = $user->getNext();
                                }
                            }
                            $html .= "</p>
                                ".$a['HTML']."
                            </div>";
                            
                            $prev = $current->getPrev();
                            $next = $current->getNext();	
                            if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
                            if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
                            $html .= "<nav><ul class=\"pager\">";
                            if(!$first){ $html .= "<li><a class=\"bnavl\" href=\"/blog/p/".$pre['ID']."\" target=\"#content\">".$pre['Title']."</a></li>"; }else{ $html .= "<li></li>";}
                            if(!$last){ $html .= "<li><a class=\"bnavl\" href=\"/blog/p/".$nex['ID']."\" target=\"#content\">".$nex['Title']."</a></li>"; }
                            $html .= "</ul></nav>";
                            echo $html;
                        break;
                        case "category": /* CATEGORY PAGE */
                            if(isset($_REQUEST['bcp']) && $_REQUEST['bcp'] != ""){ $pageNum = $_REQUEST['bcp']; }else{ $pageNum = 1; }
                            $posts = $_SESSION['Blog']->getCategoryPage($pageNum,$_REQUEST['c']);
                            $post = $posts->getFirstNode();
                            $html = "<h2>Category: ".$_REQUEST['c']."</h2>";
                            while($post != NULL){
                                $p = $post->readNode();
                                $a = $p->toArray();
                                $html .= "<div class=\"blog-post\">
                                    <h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\" target=\"#content\">".$a['Title']."</a></h2>
                                    <p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                                if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
                                    $user = $_SESSION['Users']->getFirstNode();
                                    while($user != NULL){ 
                                        $u = $user->readNode()->toArray();
                                        if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$a['Author']."\" target=\"#content\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                        $user = $user->getNext();
                                    }
                                }
                                $html .= "</p>
                                    ".$a['HTML']."
                                </div>
                                ";
                                $post = $post->getNext();
                            }
                            
                            $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                            $nextPN = $pageNum + 1;if($_SESSION['Blog']->getCategoryPage($nextPN,$_REQUEST['c'])->size() > 0){ $last = false; }else{ $last = true; }
                            $html .= "<nav><ul class=\"pager\">";
                            if(!$first){ $html .= "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$prevPN."\" target=\"#content\">Previous</a></li>"; }else{ $html .= "<li></li>";}
                            if(!$last){ $html .= "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$nextPN."\" target=\"#content\">Next</a></li>"; }
                            $html .= "</ul></nav>";
                            echo $html;
                        break;
                        case "author": /* AUTHOR PAGE */
                        	if(isset($_REQUEST['bup']) && $_REQUEST['bup'] != ""){ $pageNum = $_REQUEST['bup']; }else{ $pageNum = 1; }
							if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
								$user = $_SESSION['Users']->getFirstNode();
								while($user != NULL){ 
									$u = $user->readNode()->toArray();
									if($u['ID'] == $a['Author']){ $name = $u['First']." ".$u['Last']; break; } 
									$user = $user->getNext();
								}
							}	
							$posts = $_SESSION['Blog']->getAuthorPage($pageNum,$_REQUEST['u']);
							$post = $posts->getFirstNode();
							$html = "<h2>Category: ".$_REQUEST['c']."</h2>";
							while($post != NULL){
								$p = $post->readNode();
								$a = $p->toArray();
								$html .= "<div class=\"blog-post\">
									<h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\" target=\"#content\">".$a['Title']."</a></h2>
									<p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created'])." by ".$name."</p>
									".$a['HTML']."
								</div>
								";
								$post = $post->getNext();
							}
							
							$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
							$nextPN = $pageNum + 1;if($_SESSION['Blog']->getAuthorPage($nextPN,$_REQUEST['u'])->size() > 0){ $last = false; }else{ $last = true; }
							$html .= "<nav><ul class=\"pager\">";
							if(!$first){ $html .= "<li><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['u']."/".$prevPN."\" target=\"#content\">Previous</a></li>"; }else{ $html .= "<li></li>";}
							if(!$last){ $html .= "<li><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['u']."/".$nextPN."\" target=\"#content\">Next</a></li>"; }
							$html .= "</ul></nav>";
							echo $html;
                        break;
                        case "archive": /* ARCHIVE PAGE */
                            if(isset($_REQUEST['bap']) && $_REQUEST['bap'] != ""){ $pageNum = $_REQUEST['bap']; }else{ $pageNum = 1; }
                            $posts = $_SESSION['Blog']->getArchivePage($pageNum,$_REQUEST['a']);
                            $post = $posts->getFirstNode();
                            $html = "<h2>Archive: ".date("F Y",strtotime($_REQUEST['a']))."</h2>";
                            while($post != NULL){
                                $p = $post->readNode();
                                $a = $p->toArray();
                                $html .= "<div class=\"blog-post\">
                                    <h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\" target=\"#content\">".$a['Title']."</a></h2>
                                    <p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                                if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
                                    $user = $_SESSION['Users']->getFirstNode();
                                    while($user != NULL){ 
                                        $u = $user->readNode()->toArray();
                                        if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$a['Author']."\" target=\"#content\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                        $user = $user->getNext();
                                    }
                                }
                                $html .= "</p>
                                    ".$a['HTML']."
                                </div>
                                ";
                                $post = $post->getNext();
                            }
                            
                            $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                            $nextPN = $pageNum + 1;if($_SESSION['Blog']->getArchivePage($nextPN,$_REQUEST['a'])->size() > 0){ $last = false; }else{ $last = true; }
                            echo "<nav><ul class=\"pager\">";
                            if(!$first){ echo "<li><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['a']."/".$prevPN."\" target=\"#content\">Previous</a></li>"; }else{ echo "<li></li>";}
                            if(!$last){ echo "<li><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['a']."/".$nextPN."\" target=\"#content\">Next</a></li>"; }
                            echo "</ul></nav>";
                        break;
                        default: /* MAIN PAGE */
                            if(isset($_REQUEST['bpg']) && $_REQUEST['bpg'] != ""){ $pageNum = $_REQUEST['bpg']; }else{ $pageNum = 1; }
                            $posts = $_SESSION['Blog']->getPage($pageNum);
                            $post = $posts->getFirstNode();
                            $html = "";
                            while($post != NULL){
                                $p = $post->readNode();
                                $a = $p->toArray();
                                $html .= "<div class=\"blog-post\">
                                    <h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\" target=\"#content\">".$a['Title']."</a></h2>
                                    <p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                                if($_SESSION['Users'] != NULL && $_SESSION['Users']->size() > 0){ 
                                    $user = $_SESSION['Users']->getFirstNode();
                                    while($user != NULL){ 
                                        $u = $user->readNode()->toArray();
                                        if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$a['Author']."\" target=\"#content\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                        $user = $user->getNext();
                                    }
                                }
                                $html .= "</p>
                                    ".$a['HTML']."
                                </div>
                                ";
                                $post = $post->getNext();
                            }
                            
                            $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                            $nextPN = $pageNum + 1;if($_SESSION['Blog']->getPage($nextPN)->size() > 0){ $last = false; }else{ $last = true; }
                            $html .= "<nav><ul class=\"pager\">";
                            if(!$first){ $html .= "<li><a class=\"bnavl\" href=\"/blog/".$prevPN."\" target=\"#content\">Previous</a></li>"; }else{ $html .= "<li></li>";}
                            if(!$last){ echo "<li><a class=\"bnavl\" href=\"/blog/".$nextPN."\" target=\"#content\">Next</a></li>"; }
                            $html .= "</ul></nav>";
                            echo $html;
                        break;
                    }
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
                        foreach($archive as $k => $v){ echo "<li><a class=\"bnavl\" href=\"/blog/a/".$k."\" target=\"#content\">".date("M Y",strtotime($k))."</a></li>"; }
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
							echo "<li><a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\" target=\"#content\">".$c['Definition']."</a></li>";
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