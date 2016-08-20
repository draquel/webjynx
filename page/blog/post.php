<?php
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
	echo $html;
	
	$prev = $current->getPrev();
	$next = $current->getNext();	
	if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
	if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
	echo "<nav><ul class=\"pager\">";
	if(!$first){ echo "<li><a class=\"bnavl\" href=\"/blog/p/".$pre['ID']."\" target=\"#content\">".$pre['Title']."</a></li>"; }
	if(!$last){ echo "<li><a class=\"bnavl\" href=\"/blog/p/".$nex['ID']."\" target=\"#content\">".$nex['Title']."</a></li>"; }
	echo "</ul></nav>";
?>