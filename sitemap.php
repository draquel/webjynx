<?php
	header('Content-type: application/xml');
	require_once("_php/DBObj2/dbobj.php");
	//Load Site Config
	include("config.php");
	
	//Initialize Session Datastructures
	if(!isset($_SESSION['db']) || $_SESSION['Reset']){
		$_SESSION['db'] = new Sql();
		$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
		$_SESSION['db']->connect($_SESSION['dbName']);
	}elseif(!$_SESSION['db']->con($_SESSION['dbName'])){ $_SESSION['db']->connect($_SESSION['dbName']);	}
	if(!isset($_SESSION['Blog']) || $_SESSION['Blog']->getContent()->size() == 0 || $_SESSION['Reset']){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']->con($_SESSION['dbName']));
		$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']),true,true);
	}
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
<?php
	foreach($_SESSION['Pages'] as $page){
		echo "
		<url>
		  <loc>".$_SESSION['Domain'].$page['path-ui']."</loc>
		  <changefreq>weekly</changefreq>
		</url>";
	}
	
	$posts = $_SESSION['Blog']->getContent();
	$post = $posts->getFirstNode();
	for($i = 0; $i < $_SESSION['Blog']->getContent()->size(); $i++){
		$p = $post->readNode()->toArray();
		echo "
			<url>
			  <loc>".$_SESSION['Domain']."/blog/p/".$p['ID']."</loc>
		  	  <changefreq>daily</changefreq>
			</url>";
		$post = $post->getNext();
	}
	$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
	for($i = 0; $i < $_SESSION['Blog']->getCategories()->size(); $i++){
		$c = $cat->readNode()->toArray();
		echo "
			<url>
			  <loc>".$db['url']."blog/c/".$c['Definition']."/</loc>
		  	  <changefreq>daily</changefreq>
			</url>";
		$cat = $cat->getNext();	
	}
	$arc = $posts->getArchive();
	foreach($arc as $k => $v){
		echo "
			<url>
			  <loc>".$db['url']."blog/a/".$k."/</loc>
		  	  <changefreq>daily</changefreq>
			</url>";
	}
?>
</urlset>
