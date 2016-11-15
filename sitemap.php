<?php
	header('Content-type: application/xml');
	require_once("_php/lib.php");
	require_once("_php/DBObj/dbobj.php");
	$db = array();
	$db['dbName'] = "DBObj";
	$db['dbuser'] = "root";
	$db['dbPass'] = "Ed17i0n!";
	$db['url'] = "http://dev.webjynx.com/";
	
	$db['db'] = new Sql();
	$db['db']->init("localhost",$db['dbuser'],$db['dbPass']);
	$db['db']->connect($db['dbName']);

	$db['Blog'] = new Blog(1);
	$db['Blog']->dbRead($db['db']->con($db['dbName']));
	$db['Blog']->load($db['db']->con($db['dbName']));
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"> 
	<url>
	  <loc>http://dev.webjynx.com/</loc>
	  <changefreq>daily</changefreq>
	</url>
	<url>
	  <loc>http://dev.webjynx.com/about/</loc>
	  <changefreq>daily</changefreq>
	</url>
	<url>
	  <loc>http://dev.webjynx.com/about/other</loc>
	  <changefreq>daily</changefreq>
	</url>
	<url>
	  <loc>http://dev.webjynx.com/class</loc>
	  <changefreq>daily</changefreq>
	</url>
	<url>
	  <loc>http://dev.webjynx.com/sitemap</loc>
	  <changefreq>daily</changefreq>
	</url>
    <url>
	  <loc>http://dev.webjynx.com/blog/</loc>
	  <changefreq>daily</changefreq>
	</url>
<?php
	$posts = $db['Blog']->getPosts();
	$post = $posts->getFirstNode();
	for($i = 0; $i < $db['Blog']->getPosts()->size(); $i++){
		$p = $post->readNode()->toArray();
		echo "
			<url>
			  <loc>".$db['url']."blog/p/".$p['ID']."</loc>
		  	  <changefreq>daily</changefreq>
			</url>";
		$post = $post->getNext();
	}
	$cat = $db['Blog']->getCategories()->getFirstNode();
	for($i = 0; $i < $db['Blog']->getCategories()->size(); $i++){
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
