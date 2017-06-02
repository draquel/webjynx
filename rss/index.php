<?php
	require_once("lib/DBObj/php/lib.php");
	require_once("lib/DBObj/php/sql.class.php");
	require_once("lib/DBObj/php/blog.class.php");
	session_start();

	//Load Site Config
	include("../config.php");
	
	//Initialize Session Datastructures
	/*$_SESSION['db']['Obj'] = new Sql();
	$_SESSION['db']['Obj']->init($_SESSION['db']['Host'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
	$_SESSION['db']['Obj']->connect($_SESSION['db']['Name']);*/
	$_SESSION['db']['Obj'] = new PDO("mysql:host=".$_SESSION['db']['Host'].";dbname=".$_SESSION['db']['Name'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
	$_SESSION['db']['Obj']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	if(!isset($_SESSION['Blog']) || $_SESSION['Reset']){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']['Obj']);
		$_SESSION['Blog']->load($_SESSION['db']['Obj'],false,true);
	}
	
	//Output RSS Feed
	header("Content-Type:application/xml");
	echo $_SESSION['Blog']->rssGenFeed($_SESSION['db']['Obj'],$_SESSION['Domain'],$_SERVER['PHP_SELF'],"/blog/p/");
?>