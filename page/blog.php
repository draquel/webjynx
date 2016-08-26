<?php
	$blog = $_SESSION['Blog']->toArray();	//Create getMeta() for this - too expensive
?>
<!-- Page Specific Styles -->
	<style>	#pg > div:nth-child(1){ background-image:url('/img/stock_head1.svg'); }</style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!--Page Content -->
    <div id="pg" class="container-fluid">
        <div class="row blue_bg">
            <div></div>
        </div>
        <div class="row">
            <div class="blog-header col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                <h1 class="blog-title"><?php echo $blog['Title']; ?></h1>
                <p class="lead blog-description"><?php echo $blog['Description']; ?></p>
            </div>
            <div class="blog-main col-xs-12 col-sm-7 col-sm-offset-1 col-md-7 col-md-offset-1 col-lg-7 col-lg-offset-1">
        <?php 
            switch($bpage){
                case "post": /* POST PAGE */
                    $html = "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\">".$a['Title']."</h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                    if($_SESSION['Users']->size() > 0){ 
                        $user = $_SESSION['Users']->getFirstNode();
                        while($user != NULL){ 
                            $u = $user->readNode()->toArray();
                            if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."\">".$u['First']." ".$u['Last']."</a>"; break; } 
                            $user = $user->getNext();
                        }
                    }
                    $html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
                    if(count($a['Rels']['Category']) > 0){
                        $html .= "<div class=\"col-sm-6\"><h6 class=\"blog-post-categories\">";
                        for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
                        $html .= "</h6></div>";
                    }
                    $html .= "<div class=\"col-sm-6 text-right\"><div class=\"addthis_inline_share_toolbox\"></div></div></div>";
                    echo $html;
                    
                    if($_SESSION['Page']['Current'] != NULL){
                        $prev = $_SESSION['Page']['Current']->getPrev(); 
                        $next = $_SESSION['Page']['Current']->getNext();
                        if($prev == NULL){  $first = true; }else{ $first = false; $pre = $prev->readNode()->toArray(); }
                        if($next == NULL){ $last = true; }else{ $last = false; $nex = $next->readNode()->toArray();  }
                        $s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
                        if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/p/".$pre['ID']."\"><span aria-hidden=\"true\">&larr;</span> ".$pre['Title']."</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
                        if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/p/".$nex['ID']."\">".$nex['Title']." <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
                        $s .= "</ul></nav></div>";
                        echo $s;
                    }
                break;
                case "category": /* CATEGORY PAGE */
                    if(isset($_REQUEST['bcp']) && $_REQUEST['bcp'] != ""){ $pageNum = $_REQUEST['bcp']; }else{ $pageNum = 1; }
                    $posts = $_SESSION['Blog']->getCategoryPage($pageNum,$_REQUEST['c']);
                    $post = $posts->getFirstNode();
                    $html = "<h2>Category: ".$_REQUEST['c']."</h2>";
                    while($post != NULL){
                        $p = $post->readNode();
                        $a = $p->toArray();
                        $html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                        if($_SESSION['Users']->size() > 0){ 
                            $user = $_SESSION['Users']->getFirstNode();
                            while($user != NULL){ 
                                $u = $user->readNode()->toArray();
                                if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                $user = $user->getNext();
                            }
                        }
                        $html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."</div></div>";
                        $post = $post->getNext();
                    }
                    echo $html;
                    
                    $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                    $nextPN = $pageNum + 1;if($_SESSION['Blog']->getCategoryPage($nextPN,$_REQUEST['c'])->size() > 0){ $last = false; }else{ $last = true; }
                    $s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
                    if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
                    if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/c/".$_REQUEST['c']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
                    $s .= "</ul></nav></div>";
                    echo $s;
                break;
                case "author": /* AUTHOR PAGE */
                    if(isset($_REQUEST['bup']) && $_REQUEST['bup'] != ""){ $pageNum = $_REQUEST['bup']; }else{ $pageNum = 1; }
                    $posts = $_SESSION['Blog']->getAuthorPage($pageNum,$_REQUEST['u'],$_SESSION['Users']);
                    $post = $posts->getFirstNode();
                    $html = "<h2>Author: ".$_REQUEST['u']."</h2>";
                    while($post != NULL){
                        $p = $post->readNode();
                        $a = $p->toArray();
                        $html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created'])." by ".$_REQUEST['u']."</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
                        if(count($a['Rels']['Category']) > 0){
                            $html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
                            for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
                            $html .= "</h6></div>";
                        }
                        $html .= "</div>";
                        $post = $post->getNext();
                    }
                    echo $html;
                    
                    $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                    $nextPN = $pageNum + 1;if($_SESSION['Blog']->getAuthorPage($nextPN,$_REQUEST['u'],$_SESSION['Users'])->size() > 0){ $last = false; }else{ $last = true; }
                    $s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
                    if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['u']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
                    if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/u/".$_REQUEST['u']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
                    $s .= "</ul></nav></div>";
                    echo $s;
                break;
                case "archive": /* ARCHIVE PAGE */
                    if(isset($_REQUEST['bap']) && $_REQUEST['bap'] != ""){ $pageNum = $_REQUEST['bap']; }else{ $pageNum = 1; }
                    $posts = $_SESSION['Blog']->getArchivePage($pageNum,$_REQUEST['a']);
                    $post = $posts->getFirstNode();
                    $html = "<h2>Archive: ".date("F Y",strtotime($_REQUEST['a']))."</h2>";
                    while($post != NULL){
                        $p = $post->readNode();
                        $a = $p->toArray();
                        $html .= "<div class=\"blog-post col-md-12\"><div class=\"blog-post-head col-md-12\"><h2 class=\"blog-post-title\"><a class=\"bnavl\" href=\"/blog/p/".$a['ID']."\">".$a['Title']."</a></h2><p class=\"blog-post-meta\">".date("F j, Y, g:i a",$a['Created']);
                        if($_SESSION['Users']->size() > 0){ 
                            $user = $_SESSION['Users']->getFirstNode();
                            while($user != NULL){ 
                                $u = $user->readNode()->toArray();
                                if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                $user = $user->getNext();
                            }
                        }
                        $html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
                        if(count($a['Rels']['Category']) > 0){
                            $html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
                            for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
                            $html .= "</h6></div>";
                        }
                        $html .= "</div>";
                        $post = $post->getNext();
                    }
                    echo $html;
                    
                    $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                    $nextPN = $pageNum + 1;if($_SESSION['Blog']->getArchivePage($nextPN,$_REQUEST['a'])->size() > 0){ $last = false; }else{ $last = true; }
                    $s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
                    if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['a']."/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>";}
                    if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/a/".$_REQUEST['a']."/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
                    $s .= "</ul></nav></div>";
                    echo $s;
                break;
                default: /* MAIN PAGE */
                    if(isset($_REQUEST['bpg']) && $_REQUEST['bpg'] != ""){ $pageNum = $_REQUEST['bpg']; }else{ $pageNum = 1; }
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
                                if($u['ID'] == $a['Author']){ $html .= " by <a class=\"bnavl\" href=\"/blog/u/".$u['First']." ".$u['Last']."\">".$u['First']." ".$u['Last']."</a>"; break; } 
                                $user = $user->getNext();
                            }
                        }
                        $html .= "</p></div><div class=\"blog-post-body col-md-12\">".$a['HTML']."<hr></div>";
                        if(count($a['Rels']['Category']) > 0){
                            $html .= "<div class=\"col-md-12\"><h6 class=\"blog-post-categories\">";
                            for($i = 0; $i < count($a['Rels']['Category']); $i++){ $c = $a['Rels']['Category'][$i]; $html .= "<a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\"><span class=\"label label-default\">".$c['Definition']."</span></a>"; }
                            $html .= "</h6></div>";
                        }
                        $html .= "</div>";
                        $post = $post->getNext();
                    }
                    echo $html;
                    
                    $prevPN = $pageNum - 1;if($prevPN < 1){ $first = true; }else{ $first = false; }
                    $nextPN = $pageNum + 1;if($_SESSION['Blog']->getPage($nextPN)->size() > 0){ $last = false; }else{ $last = true; }
                    $s = "<div class=\"col-md-12\"><nav><ul class=\"pager\">";
                    if(!$first){ $s .= "<li class=\"previous\"><a class=\"bnavl\" href=\"/blog/".$prevPN."\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }else{ $s .= "<li class=\"previous disabled\"><a class=\"bnavl\" href=\"#\"><span aria-hidden=\"true\">&larr;</span> Previous</a></li>"; }
                    if(!$last){ $s .= "<li class=\"next\"><a class=\"bnavl\" href=\"/blog/".$nextPN."\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }else{ $s .= "<li class=\"next disabled\"><a class=\"bnavl\" href=\"#\">Next <span aria-hidden=\"true\">&rarr;</span></a></li>"; }
                    $s .= "</ul></nav></div>";
                    echo $s;
                break;
            }
            if(isset($pageNum)){ echo "<div class=\"col-md-12\"><p class=\"blog-pgnum text-center\">page ".$pageNum."</p></div>"; }
        ?>
            </div>
            <div class="blog-sidebar col-xs-12 col-sm-3">
                <div class="sidebar-module sidebar-module-inset">
                    <h4>About</h4>
                    <p><?php echo $blog['Description']; ?></p>
                </div>
                <div class="sidebar-module">
                    <h4>Archives</h4>
                    <ol class="list-unstyled">
				<?php
                    $archive = $_SESSION['Blog']->getPosts()->getArchive();
                    foreach($archive as $k => $v){ echo "<li><a class=\"bnavl\" href=\"/blog/a/".$k."\">".date("M Y",strtotime($k))."</a></li>"; }
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
                        echo "<li><a class=\"bnavl\" href=\"/blog/c/".$c['Definition']."\">".$c['Definition']."</a></li>";
                        $cat = $cat->getNext();
                        }
                ?>
                    </ol>
                </div>
            </div>
        </div>
    </div>