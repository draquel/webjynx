<?php
	if(isset($_REQUEST['bcp']) && $_REQUEST['bcp'] != ""){ $pageNum = $_REQUEST['bcp']; }else{ $pageNum = 1; }
	$posts = $_SESSION['Blog']->getCategoryPage($pageNum,$_REQUEST['c']);
	$post = $posts->getFirstNode();
	echo "<h2>Category: ".$_REQUEST['c']."</h2>";
	while($post != NULL){
		$p = $post->readNode();
		$a = $p->toArray();
		$html = "<div class=\"blog-post\">
			<h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\" target=\"#content\">".$a['Title']."</a></h2>
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
		echo $html;
		$post = $post->getNext();
	}
	
	$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
	$nextPN = $pageNum + 1;if($_SESSION['Blog']->getCategoryPage($nextPN,$_REQUEST['c'])->size() > 0){ $last = false; }else{ $last = true; }
	echo "<nav><ul class=\"pager\">";
	if(!$first){ echo "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$prevPN."\" target=\"#content\">Previous</a></li>"; }
	if(!$last){ echo "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$nextPN."\" target=\"#content\">Next</a></li>"; }
	echo "</ul></nav>";
	
?>