<?php
	$media = $_SESSION['Media']->toArray();
?>
<!-- Page Specific Styles -->
	<style>.blue_bg{ background-image:url('/img/stock_head1.svg'); }</style>
<!--Page Content -->
    <div id="pg" class="container-fluid">
    	<div class="row blue_bg"><div></div></div>
<?php	
/*DEBUG OUTPUT::*/
	/*echo "pg: ".$_REQUEST['pg']."<br>";
	echo "pgn: ".$_REQUEST['pgn']."<br>";*/

	$view = array();
	$view['media'] = "<div class=\"media-post col-md-12\"><div class=\"media-post-head col-md-12\"><h2 class=\"media-post-title\"><a class=\"mnavl\" href=\"/media/p/{ID}\">{Title}</a></h2><p class=\"media-post-meta\">{Created} by <a class=\"mnavl\" href=\"/media/u/{_Signature}\">{_Signature}</a></p></div><div class=\"media-post-body col-md-12\"><img class=\"img-responsive img-thumbnail center-block\" src=\"{URI}\" alt=\"{Title}\" ><hr></div><div class=\"col-md-6\"><h6 class=\"media-post-categories\">{Category}<br>{Gallery}</h6></div><div class=\"col-sm-6 text-right\"><div class=\"addthis_inline_share_toolbox\"></div></div></div>";
	$view['gallery'] = "<a class=\"bnavl\" href=\"/media/g/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>";
	$view['category'] = "<a class=\"bnavl\" href=\"/media/c/{Definition}\"><span class=\"label label-default\">{Definition}</span></a>";
		
	$page = "<div class=\"row\">
		<div class=\"media-header col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1\"><h1 class=\"media-title\">".$media['Title']."</h1><p class=\"lead media-description\">".$media['Description']."</p></div>
	<div class=\"media-main col-xs-12 col-sm-7 col-sm-offset-1 col-md-7 col-md-offset-1 col-lg-7 col-lg-offset-1\">";
	if(isset($_REQUEST['pgn']) && $_REQUEST['pgn'] != NULL){ $pageNum = $_REQUEST['pgn']; }else{ $pageNum = 1; }
	$medias = $_SESSION['Media']->getRelPage($pdo,$pageNum,"Gallery",$_REQUEST['pg']);
	$media = $medias->getFirstNode();
	$html = "<h2>".ucfirst($_REQUEST['pg'])."</h2>";
	while($post != NULL){
		$m = $media->readNode();
		$html .= $m->view(array($view['media'],$view['category'],$view['gallery']));
		$media = $media->getNext();
	}
	$page .= $html;
	$prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
	$nextPN = $pageNum + 1;if($_SESSION['Media']->getRelPage($pdo,$nextPN,"Gallery",ucfirst($_REQUEST['pg']))->size() > 0){ $last = false; }else{ $last = true; }
	$s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">"
	.(!$first ? "<li class=\"previous\"><a class=\"mnavl\" href=\"/".$_REQUEST['pg']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>" : "<li class=\"previous disabled\"><a class=\"mnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>")
	.(!$last ? "<li class=\"next\"><a class=\"mnavl\" href=\"/".$_REQUEST['pg']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>" : "<li class=\"next disabled\"><a class=\"mnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>")
	."</ul></nav></div>";
	$page .= $s;
	$display_sidebar = true;
	
	if(isset($pageNum) && $_REQUEST['pg'] != "p"){ $page .= "<div class=\"col-md-12\"><p class=\"media-pgnum text-center\">page ".$pageNum."</p></div>"; }
	$page .= "</div>";
	if($display_sidebar){
		$sidebar = "<div class=\"media-sidebar col-xs-12 col-sm-3\">";
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
		$sidebar .= "<div class=\"sidebar-module sidebar-module-inset\"><h4>About</h4><p>".$media['Description']."</p></div><div class=\"sidebar-module\"><h4>Archives</h4><ol class=\"list-unstyled\">";
		$archDates = $_SESSION['Media']->getArchiveDates($pdo);
		if($archDates && count($archDates) > 0){ foreach($archDates as $v){ $sidebar .= "<li><a class=\"bnavl\" href=\"/blog/a/".$v."\">".date("M Y",strtotime($v."01"))."</a></li>"; }	}
		$sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Categories</h4><ol class=\"list-unstyled\">";
		$cat = $_SESSION['Media']->getCategories()->getFirstNode();
		while($cat != NULL){
			$c = $cat->readNode()->toArray();
			$sidebar .= "<li><a class=\"bnavl\" href=\"/media/c/".$c['Definition']."\">".$c['Definition']."</a></li>";
			$cat = $cat->getNext();
		}
		$sidebar .= "</ol></div><div class=\"sidebar-module\"><h4>Galleries</h4><ol class=\"list-unstyled\">";
		$grp = $_SESSION['Media']->getGalleries()->getFirstNode();
		while($grp != NULL){
			$g = $grp->readNode()->toArray();
			$sidebar .= "<li><a class=\"bnavl\" href=\"/media/g/".$g['Definition']."\">".$g['Definition']."</a></li>";
			$grp = $grp->getNext();
		}
		$sidebar .= "</ol></div></div></div>";
		$page .= $sidebar;
	}
	echo $page;
	
	$_REQUEST['pg'] = NULL;
?>
    </div>