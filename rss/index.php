<?php
	require_once("../_php/DBObj2/dbobj.php");
	session_start();

	//Load Site Config
	include("../config.php");
	
	//Initialize Session Datastructures
	$_SESSION['db']['Obj'] = new Sql();
	$_SESSION['db']['Obj']->init($_SESSION['db']['Host'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
	$_SESSION['db']['Obj']->connect($_SESSION['db']['Name']);
	if(!isset($_SESSION['Blog']) || $_SESSION['Reset']){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']['Obj']->con($_SESSION['db']['Name']));
		$_SESSION['Blog']->load($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),false,true);
	}
	
	//Output RSS Feed
	header("Content-Type:application/xml");
	echo $_SESSION['Blog']->rssGenFeed($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),$_SESSION['Domain'],$_SERVER['PHP_SELF'],"/blog/p/");
?>