<?php
	//Initialize Site Data
	$_SESSION['Options'] = array();
	$_SESSION['Options']['Site'] = array();
	$_SESSION['Options']['Blog'] = array();
	$_SESSION['Options']['Media'] = array();

	$_SESSION['Title'] = "WebJynx Toolkit";
	$_SESSION['Domain'] = "https://dev.webjynx.com";
	$_SESSION['db'] = array("Name"=>"DBObj_2.0",
							"Host"=>"webjynxrds.cjzpxtjfv2ad.us-east-1.rds.amazonaws.com",
						    "User"=>"root",
						    "Pass"=>"Ed17i0n!");
	$_SESSION['Google'] = array('gaID'=>'UA-83229001-1','ssID'=>'011020224819443845085:btxae4osafm');
	$_SESSION['MailChimp'] = array('apiKey'=>null,'listID'=>null);
	
	if(!isset($_SESSION['Pages']) || $_SESSION['Reset']){
		$_SESSION['Pages'] = array(
			array("id"=>0,"meta-title"=>"HTTP 404 - Page Not Found","meta-description"=>"HTTP 404 - Page Not Found","meta-keywords"=>NULL,"path-ui"=>"/404","path-file"=>"/page/404.php"),
			array("id"=>1,"meta-title"=>"HTTP 401 - Unauthorized","meta-description"=>"HTTP 401 - Unauthorized","meta-keywords"=>NULL,"path-ui"=>"/401","path-file"=>"/page/401.php"),
			array("id"=>2,"meta-title"=>"Authorize User","meta-description"=>"Authorized User Page","path-ui"=>"/auth/","meta-keywords"=>NULL,"path-file"=>"/page/user.php"),
			array("id"=>3,"meta-title"=>"Blog","meta-description"=>"Our Blog","path-ui"=>"/blog/","meta-keywords"=>NULL,"path-file"=>"/page/blog.php"),
			array("id"=>4,"meta-title"=>"Media","meta-description"=>"Our Media","path-ui"=>"/media/","meta-keywords"=>NULL,"path-file"=>"/page/media.php"),
			array("id"=>5,"meta-title"=>"Index","meta-description"=>"Welcome to our home page!","meta-keywords"=>NULL,"path-ui"=>"/","path-file"=>"/page/index.php"),
			array("id"=>6,"meta-title"=>"About","meta-description"=>"We like stuff and want to work together on your things!","meta-keywords"=>NULL,"path-ui"=>"/about/","path-file"=>"/page/about/index.php"),
			array("id"=>7,"meta-title"=>"Other","meta-description"=>"Some more stuff we think is neat.","meta-keywords"=>NULL,"path-ui"=>"/about/other","path-file"=>"/page/about/other.php"),
			array("id"=>8,"meta-title"=>"Sitemap","meta-description"=>"A sitemap, just incase you get lost.","meta-keywords"=>NULL,"path-ui"=>"/sitemap","path-file"=>"/page/sitemap.php"),
			array("id"=>9,"meta-title"=>"Class Testing","meta-description"=>"Class Unit Testing","meta-keywords"=>NULL,"path-ui"=>"/class","path-file"=>"/page/class.php")
		);
	}
?>