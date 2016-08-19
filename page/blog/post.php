<?php
	$aid = $_REQUEST['a'];
	$post = $_SESSION['Blog']->getPosts()->getFirstNode();
	while($post != NULL){
		$p = $post->readNode();
		$a = $p->toArray();
		if($a['ID'] == $aid){ break; }
		$post = $post->getNext();
	}
	$html = "<div class=\"blog-post\">
		<h2 class=\"blog-post-title\">".$a['Title']."</h2>
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
?>
<nav>
    <ul class="pager">
      <li><a href="#">Previous</a></li>
      <li><a href="#">Next</a></li>
    </ul>
</nav>