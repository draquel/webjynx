<?php
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
	$blog = $_SESSION['Media']->toArray(); //Create getMeta() for this - too expensive
?>
<!-- Page Specific Styles -->
	<style>.blue_bg{ background-image:url('/img/stock_head1.svg'); }</style>
<!--Page Content -->
    <div id="pg" class="container-fluid">
<?php
	//No Headline Image on Detail Page
	if($_REQUEST['mpg'] != "p" && (!isset($inc) || !$inc)){ echo "<div class=\"row blue_bg\"><div></div></div>"; }
	
/*DEBUG OUTPUT::*/
/*
	echo "mpg: ".$_REQUEST['mpg']."<br>";
	echo "mpgi: ".$_REQUEST['mpgi']."<br>";
	echo "mpgn: ".$_REQUEST['mpgn']."<br>";
	echo "mpgs: ".$_REQUEST['mpgs']."<br>";
*/
		
	$view = array();
	$view['media'] = "<div class=\"media-post col-md-12 col-lg-4\"><div class=\"media-post-head col-md-12\"><a href=\"{URI}\" data-lightbox=\"Media\" data-title=\"{Description}\"><div class=\"thumbimg\" style=\"background-image:url('{URI}')\">{Gallery}<br>{Category}</div></a><h3 class=\"blog-post-title text-center\"><a class=\"mnavl\" href=\"/media/d/{ID}\">{Title}</a></h3></div></div>";
	$view['media_details'] = "<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\">{Title}</h2><p class=\"media-post-meta\">{Created} by <a class=\"mnavl\" href=\"/media/u/{_Signature}\">{_Signature}</a></p></div><div class=\"media-post-body col-md-12\"><img class=\"img-responsive img-thumbnail center-block\" src=\"{URI}\" alt=\"{Title}\" >{Description}<hr></div><div class=\"col-md-6\"><h6 class=\"media-post-categories\">{Gallery}<br>{Category}</h6></div><div class=\"col-sm-6 text-right\"><div class=\"addthis_inline_share_toolbox\"></div></div></div>";
	$view['gallery'] = "<span class=\"label label-default\">{Definition}</span>";
	$view['category'] = "<span class=\"label label-default\">{Definition}</span>";
	$view['gallery_link'] = "<a class=\"mnavl\" href=\"/media/g/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>";
	$view['category_link'] = "<a class=\"mnavl\" href=\"/media/c/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>";
		

	$mpage = "<div class=\"row\">
		<div class=\"media-header col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\"><h1 class=\"media-title\">".$blog['Title']."</h1><p class=\"lead media-description\">".$blog['Description']."</p></div>
	<div class=\"media-main col-xs-12 col-sm-8 col-sm-offset-1 col-md-8 col-md-offset-1 col-lg-8 col-lg-offset-1\">";
	if(isset($_REQUEST['mpgn']) && $_REQUEST['mpgn'] != NULL){ $pageNum = $_REQUEST['mpgn']; }else{ $pageNum = 1; }
	switch($_REQUEST['mpg']){
		case "d": /* MEDIA DETAILS PAGE */
			$html = $_SESSION['Page']['Current']->readNode()->view(array($view['media_details'],$view['category_link'],$view['gallery_link']));
			$mpage .= $html;
			if($_SESSION['Page']['Current'] != NULL){
				$prev = $_SESSION['Page']['Current']->getPrev();
				$next = $_SESSION['Page']['Current']->getNext();
				if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
				if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
				$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
				.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/p/".$pre['ID']."\"><span aria-hidden=\"true\">&larr;</span> ".$pre['Title']."</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
				.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/p/".$nex['ID']."\">".$nex['Title']." <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
				."</ul></nav></div>";
				$mpage .= $s;
				$display_sidebar = true;
			}
		break;
		case "c": /* CATEGORY PAGE */
			$posts = $_SESSION['Media']->getRelPage($_SESSION['db']->con($_SESSION['dbName']),$pageNum,"Category",$_REQUEST['mpgi']);
			$post = $posts->getFirstNode();
			$html = "<h2>Category: ".$_REQUEST['mpgi']."</h2>";
			while($post != NULL){
				$p = $post->readNode();
				$html .= $p->view(array("<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\"><a class=\"mnavl\" href=\"/media/p/{ID}\">{Title}</a></h2><p class=\"media-post-meta\">{Published} by <a class=\"mnavl\" href=\"/media/u/{_Signature}\">{_Signature}</a></p></div><div class=\"media-post-body col-md-12\">{HTML}<hr></div></div>"));
				$post = $post->getNext();
			}
			$mpage .= $html;
			$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
			$nextPN = $pageNum + 1;if($_SESSION['Media']->getRelPage($_SESSION['db']->con($_SESSION['dbName']),$nextPN,"Category",$_REQUEST['mpgi'])->size() > 0){ $last = false; }else{ $last = true; }
			$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
			.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/c/".$_REQUEST['mpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
			.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/c/".$_REQUEST['mpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
			."</ul></nav></div>";
			$mpage .= $s;
			$display_sidebar = true;
		break;
		case "g": /* GALLERY PAGE */
			$posts = $_SESSION['Media']->getRelPage($_SESSION['db']->con($_SESSION['dbName']),$pageNum,"Gallery",$_REQUEST['mpgi']);
			$post = $posts->getFirstNode();
			$html = "<h2>Gallery: ".$_REQUEST['mpgi']."</h2>";
			while($post != NULL){
				$p = $post->readNode();
				$html .= $p->view(array("<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\"><a class=\"mnavl\" href=\"/media/p/{ID}\">{Title}</a></h2><p class=\"media-post-meta\">{Published} by <a class=\"mnavl\" href=\"/media/u/{_Signature}\">{_Signature}</a></p></div><div class=\"media-post-body col-md-12\">{HTML}<hr></div></div>"));
				$post = $post->getNext();
			}
			$mpage .= $html;
			$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
			$nextPN = $pageNum + 1;if($_SESSION['Media']->getRelPage($_SESSION['db']->con($_SESSION['dbName']),$nextPN,"Category",$_REQUEST['mpgi'])->size() > 0){ $last = false; }else{ $last = true; }
			$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
			.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/c/".$_REQUEST['mpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
			.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/c/".$_REQUEST['mpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
			."</ul></nav></div>";
			$mpage .= $s;
			$display_sidebar = true;
		break;
		case "u": /* AUTHOR PAGE */
			$posts = $_SESSION['Media']->getAuthorPage($_SESSION['db']->con($_SESSION['dbName']),$pageNum,$_REQUEST['mpgi']);
			$post = $posts->getFirstNode();
			$html = "<h2>Author: ".$_REQUEST['mpgi']."</h2>";
			while($post != NULL){
				$p = $post->readNode();
				$html .= $p->view(array("<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\"><a class=\"mnavl\" href=\"/media/p/{ID}\">{Title}</a></h2><p class=\"media-post-meta\">{Published} by {_Signature}</p></div><div class=\"media-post-body col-md-12\">{HTML}<hr></div><div class=\"col-md-12\"><h6 class=\"media-post-categories\">{Category}</h6></div></div>","<a class=\"mnavl\" href=\"/media/c/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>"));
				$post = $post->getNext();
			}
			$mpage .= $html;
			$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
			$nextPN = $pageNum + 1;if($_SESSION['Media']->getAuthorPage($_SESSION['db']->con($_SESSION['dbName']),$nextPN,$_REQUEST['mpgi'])->size() > 0){ $last = false; }else{ $last = true; }
			$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
			.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/u/".$_REQUEST['mpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
			.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/u/".$_REQUEST['mpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
			."</ul></nav></div>";
			$mpage .= $s;
			$display_sidebar = true;
		break;
		case "a": /* ARCHIVE PAGE */
			$posts = $_SESSION['Media']->getArchivePage($_SESSION['db']->con($_SESSION['dbName']),$pageNum,$_REQUEST['mpgi']);
			$post = $posts->getFirstNode();
			$html = "<h2>Archive: ".date("F Y",strtotime($_REQUEST['mpgi']."01"))."</h2>";
			while($post != NULL){
				$p = $post->readNode();
				$html .= $p->view(array("<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\"><a class=\"mnavl\" href=\"/media/p/{ID}\">{Title}</a></h2><p class=\"media-post-meta\">{Published} by <a class=\"mnavl\" href=\"/media/u/{_Signature}\">{_Signature}</a></p></div><div class=\"media-post-body col-md-12\">{HTML}<hr></div><div class=\"col-md-12\"><h6 class=\"media-post-categories\">{Category}</h6></div></div>","<a class=\"mnavl\" href=\"/media/c/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>"));
				$post = $post->getNext();
			}
			$mpage .= $html;
			$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
			$nextPN = $pageNum + 1;if($_SESSION['Media']->getArchivePage($_SESSION['db']->con($_SESSION['dbName']),$nextPN,$_REQUEST['mpgi'])->size() > 0){ $last = false; }else{ $last = true; }
			$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
			.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/a/".$_REQUEST['mpgi']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
			.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/a/".$_REQUEST['mpgi']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
			."</ul></nav></div>";
			$mpage .= $s;
			$display_sidebar = true;
		break;
		default: /* MAIN PAGE */
			$posts = $_SESSION['Media']->getPage($_SESSION['db']->con($_SESSION['dbName']),$pageNum);
			$post = $posts->getFirstNode();
			$html = "";
			while($post != NULL){
				$p = $post->readNode();
				$html .= $p->view(array($view['media'],$view['category'],$view['gallery']));
				$post = $post->getNext();
			}
			$mpage .= $html;
			$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
			$nextPN = $pageNum + 1;if($_SESSION['Media']->getPage($_SESSION['db']->con($_SESSION['dbName']),$nextPN)->size() > 0){ $last = false; }else{ $last = true; }
			$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
			.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/media/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
			.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/media/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
			."</ul></nav></div>";
			$mpage .= $s;
			$display_sidebar = true;
		break;
	}
	if(isset($pageNum) && $_REQUEST['mpg'] != "p"){ $mpage .= "<div class=\"col-md-12\"><p class=\"media-pgnum text-center\">page ".$pageNum."</p></div>"; }
	$mpage .= "</div>";
	if($display_sidebar){
		$sidebar = "<div class=\"media-sidebar col-xs-12 col-sm-2\">";
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
		$archDates = $_SESSION['Media']->getArchiveDates($_SESSION['db']->con($_SESSION['dbName']));
		if($archDates && count($archDates) > 0){ foreach($archDates as $v){ $sidebar .= "<li><a class=\"mnavl\" href=\"/media/a/".$v."\">".date("M Y",strtotime($v."01"))."</a></li>"; }	}
		$sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Categories</h4><ol class=\"list-unstyled\">";
		$cat = $_SESSION['Media']->getCategories()->getFirstNode();
		while($cat != NULL){
			$c = $cat->readNode()->toArray();
			$sidebar .= "<li><a class=\"mnavl\" href=\"/media/c/".$c['Definition']."\">".$c['Definition']."</a></li>";
			$cat = $cat->getNext();
		}
		$sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Galleries</h4><ol class=\"list-unstyled\">";
		$grp = $_SESSION['Media']->getGalleries()->getFirstNode();
		while($grp != NULL){
			$g = $grp->readNode()->toArray();
			$sidebar .= "<li><a class=\"mnavl\" href=\"/media/g/".$g['Definition']."\">".$g['Definition']."</a></li>";
			$grp = $grp->getNext();
		}
		$sidebar .= "</ol></div></div></div>";
		$mpage .= $sidebar;
	}
	echo $mpage;
		
	$_REQUEST['mpg'] = NULL;
?>
    </div>