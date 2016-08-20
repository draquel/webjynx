<?php
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
	echo $html;
	
	$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
	$nextPN = $pageNum + 1;if($_SESSION['Blog']->getCategoryPage($nextPN,$_REQUEST['c'])->size() > 0){ $last = false; }else{ $last = true; }
	$s = "<nav><ul class=\"pager\">";
	if(!$first){ $s .= "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$prevPN."\" target=\"#content\">Previous</a></li>"; }else{ $s .= "<li></li>";}
	if(!$last){ $s .= "<li><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$nextPN."\" target=\"#content\">Next</a></li>"; }
	$s .= "</ul></nav>";
	echo $s;
?>