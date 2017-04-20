<?php
	require_once("../_php/DBObj2/dbobj.php");
	session_start();

	//Load Site Config
	include("../config.php");
	
	//Initialize Session Datastructures
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
	if(!isset($_SESSION['Blog']) || $_SESSION['Reset']){
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']->con($_SESSION['dbName']));
		$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']),false,true);
	}
	
	//Output RSS Feed
	header("Content-Type:application/xml");
	echo $_SESSION['Blog']->rssGenFeed($_SESSION['db']->con($_SESSION['dbName']),$_SESSION['Domain'],$_SERVER['PHP_SELF'],"/blog/p/");
?>