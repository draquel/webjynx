<!-- Page Specific Styles -->
	<style>.blue_bg{ background-image:url('/img/stock_head1.svg'); }</style>
<!--Page Content -->
    <div id="pg" class="container-fluid">
<?php
	$_SESSION['Blog']->load($pdo,false,true);
	$blog = $_SESSION['Blog']->toArray(); //Create getMeta() for this - too expensive
	//No Headline Image on Post Page
	if($_REQUEST['bpg'] != "p" && (!isset($inc) || !$inc)){ echo "<div class=\"row blue_bg\"><div></div></div>"; }
/*DEBUG OUTPUT::*/
/*
	echo "bpg: ".$_REQUEST['bpg']."<br>";
	echo "bpgi: ".$_REQUEST['bpgi']."<br>";
	echo "bpgn: ".$_REQUEST['bpgn']."<br>";
	echo "bpgs: ".$_REQUEST['bpgs']."<br>";
*/
	// HTML View Definitions
	$view = array();
	$view['post_p'] = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\">{Title}</h2><p class=\"blog-post-meta\">{Published} by <a class=\"bnavl\" href=\"/blog/u/{_Signature}\">{_Signature}</a></p></div><div class=\"blog-post-body col-md-12\"><img class=\"img-responsive img-thumbnail center-block\" src=\"{CoverImage}\" alt=\"{Title}\" >{HTML}<hr></div><div class=\"col-md-6\"><h6 class=\"blog-post-categories\">{Category}</h6></div><div class=\"col-sm-6 text-right\"><div class=\"addthis_inline_share_toolbox\"></div></div></div>";
	$view['post_c'] = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/{ID}\">{Title}</a></h2><p class=\"blog-post-meta\">{Published} by <a class=\"bnavl\" href=\"/blog/u/{_Signature}\">{_Signature}</a></p></div><div class=\"blog-post-body col-md-12\">{HTML}<hr></div></div>";
	$view['post_u'] = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/{ID}\">{Title}</a></h2><p class=\"blog-post-meta\">{Published} by {_Signature}</p></div><div class=\"blog-post-body col-md-12\">{HTML}<hr></div><div class=\"col-md-12\"><h6 class=\"blog-post-categories\">{Category}</h6></div></div>";
	$view['post_a'] = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/{ID}\">{Title}</a></h2><p class=\"blog-post-meta\">{Published} by <a class=\"bnavl\" href=\"/blog/u/{_Signature}\">{_Signature}</a></p></div><div class=\"blog-post-body col-md-12\">{HTML}<hr></div><div class=\"col-md-12\"><h6 class=\"blog-post-categories\">{Category}</h6></div></div>";
	$view['post'] = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/{ID}\">{Title}</a></h2><p class=\"blog-post-meta\">{Published} by <a class=\"bnavl\" href=\"/blog/u/{_Signature}\">{_Signature}</a></p></div><div class=\"blog-post-body col-md-12\">{HTML}<hr></div><div class=\"col-md-12\"><h6 class=\"blog-post-categories\">{Category}</h6></div></div>";
	$view['Category'] = "<a class=\"bnavl\" href=\"/blog/c/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>";
	if($_REQUEST['bpg'] != "admin"){
		$bpage = "<div class=\"row\">
			<div class=\"blog-header col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\"><h1 class=\"blog-title\">".$blog['Title']."</h1><p class=\"lead blog-description\">".$blog['Description']."</p></div>
		<div class=\"blog-main col-xs-12 col-sm-7 col-sm-offset-1 col-md-7 col-md-offset-1 col-lg-7 col-lg-offset-1\">";
		if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
		switch($_REQUEST['bpg']){
			case "p": /* POST PAGE */
				$html = $_SESSION['Page']['Current']->readNode()->view(array($view['post_p'],$view['Category']));
				$bpage .= $html;
				if($_SESSION['Page']['Current'] != NULL){
					$prev = $_SESSION['Page']['Current']->getPrev(); 
					$next = $_SESSION['Page']['Current']->getNext();
					if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
					if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
					$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
					.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/p/".$pre['ID']."\"><span aria-hidden=\"true\">&larr;</span> ".$pre['Title']."</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
					.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/p/".$nex['ID']."\">".$nex['Title']." <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
					."</ul></nav></div>";
					$bpage .= $s;
					$display_sidebar = true;
				}
			break;
			case "c": /* CATEGORY PAGE */
				$posts = $_SESSION['Blog']->getRelPage($pdo,$pageNum,"Category",$_REQUEST['bpgi']);
				$post = $posts->getFirstNode();
				$html = "<h2>Category: ".$_REQUEST['bpgi']."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$html .= $p->view(array($view['post_c']));
					$post = $post->getNext();
				}
				$bpage .= $html;
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getRelPage($pdo,$nextPN,"Category",$_REQUEST['bpgi'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
				.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
				.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
				."</ul></nav></div>";
				$bpage .= $s;
				$display_sidebar = true;
			break;
			case "u": /* AUTHOR PAGE */
				$posts = $_SESSION['Blog']->getAuthorPage($pdo,$pageNum,$_REQUEST['bpgi']);
				$post = $posts->getFirstNode();
				$html = "<h2>Author: ".$_REQUEST['bpgi']."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$html .= $p->view(array($view['post_u'],$view['Category']));
					$post = $post->getNext();
				}
				$bpage .= $html;
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getAuthorPage($pdo,$nextPN,$_REQUEST['bpgi'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
				.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
				.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
				."</ul></nav></div>";
				$bpage .= $s;
				$display_sidebar = true;
			break;
			case "a": /* ARCHIVE PAGE */
				$posts = $_SESSION['Blog']->getArchivePage($pdo,$pageNum,$_REQUEST['bpgi']);
				$post = $posts->getFirstNode();
				$html = "<h2>Archive: ".date("F Y",strtotime($_REQUEST['bpgi']."01"))."</h2>";
				while($post != NULL){
					$p = $post->readNode();
					$html .= $p->view(array($view['post_a'],$view['Category']));
					$post = $post->getNext();
				}
				$bpage .= $html;
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getArchivePage($pdo,$nextPN,$_REQUEST['bpgi'])->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
				.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['bpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
				.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['bpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
				."</ul></nav></div>";
				$bpage .= $s;
				$display_sidebar = true;
			break;
			default: /* MAIN PAGE */
				$posts = $_SESSION['Blog']->getPage($pdo,$pageNum);
				$post = $posts->getFirstNode();
				$html = "";
				while($post != NULL){
					$p = $post->readNode();
					$html .= $p->view(array($view['post'],$view['Category']));
					$post = $post->getNext();
				}
				$bpage .= $html;
				$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
				$nextPN = $pageNum + 1;if($_SESSION['Blog']->getPage($pdo,$nextPN)->size() > 0){ $last = false; }else{ $last = true; }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
				.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
				.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
				."</ul></nav></div>";
				$bpage .= $s;
				$display_sidebar = true;
			break;
		}
		if(isset($pageNum) && $_REQUEST['bpg'] != "p"){ $bpage .= "<div class=\"col-md-12\"><p class=\"blog-pgnum text-center\">page ".$pageNum."</p></div>"; }
		$bpage .= "</div>";
		if($display_sidebar){
			$sidebar = "<div class=\"blog-sidebar col-xs-12 col-sm-3\">";
			if(isset($_SESSION['Blog_GCS_ID']) && $_SESSION['Blog_GCS_ID'] != ""){
				$sidebar .= "<script>
				  (function() {
					var cx = '".$_SESSION['Google']['ssID']."';
					var gcse = document.createElement('script');
					gcse.type = 'text/javascript';
					gcse.async = true;
					gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(gcse, s);
				  })();
				</script>
				<gcse:search></gcse:search>";
			}
			$sidebar .= "<div class=\"sidebar-module sidebar-module-inset\"><h4>About</h4><p>".$blog['Description']."</p></div><div class=\"sidebar-module\"><h4>Archives</h4><ol class=\"list-unstyled\">";
			$archDates = $_SESSION['Blog']->getArchiveDates($pdo);
			if($archDates && count($archDates) > 0){ foreach($archDates as $v){ $sidebar .= "<li><a class=\"bnavl\" href=\"/blog/a/".$v."\">".date("M Y",strtotime($v."01"))."</a></li>"; }	}
			$sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Categories</h4><ol class=\"list-unstyled\">";
			$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
			while($cat != NULL){
				$c = $cat->readNode()->toArray();
				$sidebar .= "<li><a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\">".$c['Definition']."</a></li>";
				$cat = $cat->getNext();
			}
			$sidebar .= "</ol></div></div></div>";
			$bpage .= $sidebar;
		}
		echo $bpage;
	}else{
		//LOGIN AND GROUP CHECKS SHOULD BE MOVED INTO PAGE PROCESSING IN \INDEX.PHP
		if(!isset($_SESSION['User']) || $_SESSION['User'] == NULL){
			/* Login Page */
			$inc = 1; include("page/user.php");
		}elseif(!$_SESSION['User']->getRelation("Group")->hasRel(6)){ 
			include("page/401.php");
		}else{
			/* Blog Admin Console */
			echo "<div class=\"row\">
				<div class=\"blog-console col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\">
					<h1>Blog Console</h1>
					<div class=\" blog-console-menu col-sm-3 col-md-2\">
						<ul class=\"nav nav-pills nav-stacked\">
						  <li role=\"presentation\"><a href=\"/blog/admin/\">Posts</a></li>
						  <li role=\"presentation\"><a href=\"/blog/admin/categories\">Categories</a></li>
						  <li role=\"presentation\"><a href=\"/blog/admin/users\">Users</a></li>
						</ul>
					</div>
					<div class=\"blog-console-content col-sm-9 col-md-10\">";
			if(!isset($_REQUEST['bpgi'])){ $_REQUEST['bpgi'] = NULL; }
			switch($_REQUEST['bpgi']){
				default:
					if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
					if(isset($_REQUEST['bpgs']) && $_REQUEST['bpgs'] != NULL){ $pageSize = $_REQUEST['bpgs']; }else{ $pageSize = 10; }
					$html = "
						<button type=\"button\" class=\"btn btn-default\" onClick=\"setForm('bri=1')\">Create New Post</button>
						  <table class=\"table\">
							<tr><th>ID</th><th>Title</th><th>Author</th><th class=\"hidden-xs\">Categories</th><th class=\"hidden-xs hidden-sm\">Created</th><th class=\"hidden-xs hidden-sm\">Published</th><th>Actions</th></tr>";
					$posts = $_SESSION['Blog']->getPage($pdo,$pageNum,$pageSize,true,true);
					$post = $posts->getFirstNode();
					while($post != NULL){ 
						$p = $post->readNode();
						$a = $p->toArray();
						$html .= "<tr><td>".$a['ID']."</td><td>".$a['Title']."</td><td>".$a['_Signature']."</td>";
						if(count($a['Rels']['Category']) > 0){
							$html .= "<td class=\"hidden-xs\">";
							for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<h6 class=\"blog-adm-post-categories\"><span class=\"label label-default\">".$c['Definition']."</span></h6>";  }
							$html .= "</td>";
						}else{ $html .= "<td class=\"hidden-xs\"></td>"; }
						$html .= "<td class=\"hidden-xs hidden-sm\">".date("Y-m-d g:i a",$a['Created'])."</td><td class=\"hidden-xs hidden-sm\">".date("Y-m-d g:i a",$a['Published'])."</td>
						<td>
						<div class=\"btn-group\">
						  <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
							 <span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> <span class=\"caret\"></span>
						  </button>
						  <ul class=\"dropdown-menu dropdown-menu-right\">
							<li><a onClick=\"setForm('bri=2&i=".$a['ID']."')\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a></li>
							<li><a onClick=\"setForm('bri=4&i=".$a['ID']."')\"><span class=\"glyphicon glyphicon-ban-circle\" aria-hidden=\"true\"></span>Delete</a></li>
						  </ul>
						</div>
						</td></tr>";
						$post = $post->getNext();
					}
					$html .= "</table>";
					$html .= "<div class=\"btn-group\">
					  <button type=\"button\" class=\"btn btn-default btn-sm dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
						Posts per page <span class=\"caret\"></span>
					  </button>
					  <ul class=\"dropdown-menu\">
						<li><a href=\"/blog/admin/posts/1/10\">10</a></li>
						<li><a href=\"/blog/admin/posts/1/25\">25</a></li>
						<li><a href=\"/blog/admin/posts/1/50\">50</a></li>
						<li><a href=\"/blog/admin/posts/1/100\">100</a></li>
					  </ul>
					</div>";
					$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
					$nextPN = $pageNum + 1;if($_SESSION['Blog']->getPage($pdo,$nextPN,$pageSize,true)->size() > 0){ $last = false; }else{ $last = true; }
					if(isset($_REQUEST['bpgs']) && $_REQUEST['bpgs'] != ""){ $prevPN .= "/".$_REQUEST['bpgs']; $nextPN .= "/".$_REQUEST['bpgs']; }
					$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
					.(!$first ? "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/admin/posts/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
					.(!$last ? "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/admin/posts/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
					."</ul></nav></div>";
					$html .= $s;
					$html .= "<div class=\"col-md-12\"><p class=\"blog-pgnum text-center\">page ".$pageNum."</p></div>";
					echo $html;
				break;
				case "categories":
					if(isset($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL){ $pageNum = $_REQUEST['bpgn']; }else{ $pageNum = 1; }
					$html = "<div>
						<button type=\"button\" class=\"btn btn-default\" onClick=\"setForm('bri=6')\">Create New Category</button>
						 <table class=\"table\">
							<tr><th>ID</th><th>Title</th><th class=\"hidden-xs hidden-sm\">Created</th><th>Actions</th></tr>";
					$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
					while($cat != NULL){
						$c = $cat->readNode()->toArray();
						$html .= "<tr><td>".$c['ID']."</td><td>".$c['Definition']."</td><td>".date("Y-m-d g:i a",$c['Created'])."</td><td>
							<div class=\"btn-group\">
							  <button type=\"button\" class=\"btn btn-default dropdown-toggle\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-cog\" aria-hidden=\"true\"></span> <span class=\"caret\"></span></button>
							  <ul class=\"dropdown-menu dropdown-menu-right\"><li><a onClick=\"setForm('bri=7&i=".$c['ID']."')\"><span class=\"glyphicon glyphicon-pencil\" aria-hidden=\"true\"></span> Edit</a></li><li><a onClick=\"setForm('bri=9&i=".$c['ID']."')\"><span class=\"glyphicon glyphicon-ban-circle\" aria-hidden=\"true\"></span>Delete</a></li></ul>
							</div>
						</td></tr>";
						$cat = $cat->getNext();
					}
					$html .= "</table></div>";
					echo $html;
				break;
				case "users":
					
				break;
			}	
			echo "</div>
				</div>
			</div>";
		}
	}
	$_REQUEST['bpg'] = NULL;
?>
    </div>