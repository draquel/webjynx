<?php
	$blog = $_SESSION['Blog']->toArray();	//Create getMeta() for this - too expensive
?>
<!-- Page Specific Styles -->
	<style>.blue_bg{ background-image:url('/img/stock_head1.svg'); } .trumbo{ background-color:#FFF;}</style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!--Page Content -->
    <div id="pg" class="container-fluid">
    <div class="row blue_bg"><div></div></div>
        
<?php
/*    echo "bpg: ".$_REQUEST['bpg']."<br>";
	echo "bpgi: ".$_REQUEST['bpgi']."<br>";
	echo "bpgn: ".$_REQUEST['bpgn']."<br>";*/
	if($_REQUEST['bpg'] != "admin"){
		$bpage = "<div class=\"row\"><div class=\"blog-header col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\">
			<h1 class=\"blog-title\">".$blog['Title']."</h1>
			<p class=\"lead blog-description\">".$blog['Description']."</p>
		</div>
		<div class=\"blog-main col-xs-12 col-sm-7 col-sm-offset-1 col-md-7 col-md-offset-1 col-lg-7 col-lg-offset-1\">";
		if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
		switch($_REQUEST['bpg']){
			case "p": /* POST PAGE */
				$html = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\">".$a['Title']."</h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
				if($_SESSION['Users']->size() > 0){ 
					$user = $_SESSION['Users']->getFirstNode();
					while($user != NULL){ 
						$u = $user->readNode()->toArray();
						if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."/\">".$u['First']." ".$u['Last']."</a>"; break; } 
						$user = $user->getNext();
					}
				}
				$html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
				if(count($a['Rels']['Category']) > 0){
					$html .= "<div class=\"col-sm-6\"><h6 class=\"blog-post-categories\">";
					for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."/\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
					$html .= "</h6></div>";
				}
				$html .= "<div class=\"col-sm-6 text-right\"><div class=\"addthis_inline_share_toolbox\"></div></div></div>";
				$bpage .= $html;
				
				if($_SESSION['Page']['Current'] != NULL){
					$prev = $_SESSION['Page']['Current']->getPrev(); 
					$next = $_SESSION['Page']['Current']->getNext();
					if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
					if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
					$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
					if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/p/".$pre['ID']."\"><span aria-hidden=\"true\">&larr;</span> ".$pre['Title']."</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
					if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/p/".$nex['ID']."\">".$nex['Title']." <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
					$s .= "</ul></nav></div>";
					$bpage .= $s;
				}
			break;
			case "c": /* CATEGORY PAGE */
				$posts = $_SESSION['Blog']->getCategoryPage($pageNum,$_REQUEST['bpgi']);
				$post = $posts->getFirstNode();
				$html = "<h2>Category: ".$_REQUEST['bpgi']."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$a = $p->toArray();
					$html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
					if($_SESSION['Users']->size() > 0){ 
						$user = $_SESSION['Users']->getFirstNode();
						while($user != NULL){ 
							$u = $user->readNode()->toArray();
							if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."/\">".$u['First']." ".$u['Last']."</a>"; break; } 
							$user = $user->getNext();
						}
					}
					$html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."</div></div>";
					$post = $post->getNext();
				}
				$bpage .= $html;
				
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getCategoryPage($nextPN,$_REQUEST['bpgi'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
				if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
				if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
				$s .= "</ul></nav></div>";
				$bpage .= $s;
			break;
			case "u": /* AUTHOR PAGE */
				$posts = $_SESSION['Blog']->getAuthorPage($pageNum,$_REQUEST['bpgi'],$_SESSION['Users']);
				$post = $posts->getFirstNode();
				$html = "<h2>Author: ".$_REQUEST['bpgi']."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$a = $p->toArray();
					$html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created'])." by ".$_REQUEST['bpgi']."</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
					if(count($a['Rels']['Category']) > 0){
						$html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
						for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."/\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
						$html .= "</h6></div>";
					}
					$html .= "</div>";
					$post = $post->getNext();
				}
				$bpage .= $html;
				
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getAuthorPage($nextPN,$_REQUEST['bpgi'],$_SESSION['Users'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
				if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
				if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
				$s .= "</ul></nav></div>";
				$bpage .= $s;
			break;
			case "a": /* ARCHIVE PAGE */
				$posts = $_SESSION['Blog']->getArchivePage($pageNum,$_REQUEST['bpgi']);
				$post = $posts->getFirstNode();
				$html = "<h2>Archive: ".date("F Y",strtotime($_REQUEST['bpgi']))."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$a = $p->toArray();
					$html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
					if($_SESSION['Users']->size() > 0){ 
						$user = $_SESSION['Users']->getFirstNode();
						while($user != NULL){ 
							$u = $user->readNode()->toArray();
							if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."/\">".$u['First']." ".$u['Last']."</a>"; break; } 
							$user = $user->getNext();
						}
					}
					$html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
					if(count($a['Rels']['Category']) > 0){
						$html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
						for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."/\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
						$html .= "</h6></div>";
					}
					$html .= "</div>";
					$post = $post->getNext();
				}
				$bpage .= $html;
				
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getArchivePage($nextPN,$_REQUEST['bpgi'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
				if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>";}
				if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
				$s .= "</ul></nav></div>";
				$bpage .= $s;
			break;
			default: /* MAIN PAGE */
				$posts = $_SESSION['Blog']->getPage($pageNum);
				$post = $posts->getFirstNode();
				$html = "";
				while($post != NULL){
					$p = $post->readNode();
					$a = $p->toArray();
					$html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
					if($_SESSION['Users']->size() > 0){ 
						$user = $_SESSION['Users']->getFirstNode();
						while($user != NULL){ 
							$u = $user->readNode()->toArray();
							if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."/\">".$u['First']." ".$u['Last']."</a>"; break; } 
							$user = $user->getNext();
						}
					}
					$html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
					if(count($a['Rels']['Category']) > 0){
						$html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
						for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."/\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
						$html .= "</h6></div>";
					}
					$html .= "</div>";
					$post = $post->getNext();
				}
				$bpage .= $html;
				
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getPage($nextPN)->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
				if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
				if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
				$s .= "</ul></nav></div>";
				$bpage .= $s;
			break;
		}
		if(isset($pageNum) && $_REQUEST['bpg'] != "p"){ $bpage .= "<div class=\"col-md-12\"><p class=\"blog-pgnum text-center\">page ".$pageNum."</p></div>"; }
		$bpage .= "</div>";
		
		$sidebar = "<div class=\"blog-sidebar col-xs-12 col-sm-3\"><div class=\"sidebar-module sidebar-module-inset\"><h4>About</h4><p>".$blog['Description']."</p></div><div class=\"sidebar-module\"><h4>Archives</h4><ol class=\"list-unstyled\">";
		if($_SESSION['Blog']->getPosts()->size() > 0){
			$archive = $_SESSION['Blog']->getPosts()->getArchive();
			foreach($archive as $k => $v){ $sidebar .= "<li><a class=\"bnavl\" href=\"/blog/a/".$k."/\">".date("M Y",strtotime($k))."</a></li>"; }
		}
	    $sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Categories</h4><ol class=\"list-unstyled\">";
		$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
		while($cat != NULL){
			$c = $cat->readNode()->toArray();
			$sidebar .= "<li><a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."/\">".$c['Definition']."</a></li>";
			$cat = $cat->getNext();
		}
	    $sidebar .= "</ol></div></div></div>";
		$bpage .= $sidebar;
		echo $bpage;
	}else{
		if(!isset($_SESSION['User']) || $_SESSION['User'] == NULL){
			/* Login Page */
			$inc = 1; include("page/user.php");
		}else{
			/* Blog Admin Console */
			echo "<div class=\"row\">
				<div class=\"blog-console col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\">
					<h1>Blog Management Console</h1>
					<div class=\" blog-console-menu col-sm-2\">
						<ul class=\"nav nav-pills nav-stacked\">
						  <li role=\"presentation\"><a href=\"/blog/admin/\">Status</a></li>
						  <li role=\"presentation\"><a href=\"/blog/admin/posts\">Posts</a></li>
						  <li role=\"presentation\"><a href=\"/blog/admin/categories\">Categories</a></li>
						</ul>
					</div>
					<div class=\"blog-console-content col-sm-10\">";
					switch($_REQUEST['bpgi']){
						default:
							echo "<div><h2>Status</h2></div>";
						break;
						case "posts":
							if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
							echo "<div>
								<h2>Posts</h2>
								
								<div class=\"trumbo\"></div>
							</div>";
						break;
						/*case "post":
							$pid = $_REQUEST['bpgn'];
							echo "<div>
								<h2>Posts</h2>
								
								
								<div class=\"trumbo\"></div>
							</div>";
						break;*/
						case "categories":
							if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
							echo "<div>
								<h2>Categories</h2>
								
							</div>";
						break;
						/*case "category":
							$cid = $_REQUEST['bpgn'];
							echo "<div>
								<h2>Category</h2>
								
							</div>";
						break;*/
					}	
				echo "</div>
				</div>
			</div>";
		}
	}
	$_REQUEST['bpg'] = NULL;
?>
    </div>