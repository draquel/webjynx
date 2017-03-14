<?php
	include("../_php/DBObj/dbobj.php");
	session_start();

	$_SESSION['dbName'] = "DBObj";
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init("webjynxrds.cjzpxtjfv2ad.us-east-1.rds.amazonaws.com","root","Ed17i0n!");
	$_SESSION['db']->connect($_SESSION['dbName']);
	
	$_SESSION['Blog'] = new Blog(1);
	$_SESSION['Blog']->dbRead($_SESSION['db']->con($_SESSION['dbName']));
	$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']));
	
	header("Content-Type:application/xml");
	
	$_SESSION['Domain']= "dev.webjynx.com";
	echo $_SESSION['Blog']->rssGenFeed("http://".$_SESSION['Domain'],$_SERVER['PHP_SELF']);
?>