<?php
	require_once("_php/lib.php");
	include("_php/DBObj/dbobj.php");
	session_start();
	
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init("localhost",$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
				
	if(isset($_REQUEST['ari']) && $_REQUEST['ari'] != NULL && $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 1: //Login
				$user = $_REQUEST['user']; $pass = $_REQUEST['pass']; $auth = false;
				$_SESSION['User'] = new User(NULL);
				$auth = $_SESSION['User']->login($user,$pass,$_SESSION['db']->con($_SESSION['dbName']));
				if($auth){ echo 1; }else{ $_SESSION['User'] = NULL; echo 2; }
			break;
			default:
				echo "BAD REQUEST"; 
			break;
		}
		if(isset($_SESSION['db'])){ session_write_close(); $_SESSION['db']->disconnect($_SESSION['dbName']); }
	}else{ echo "BAD REQUEST"; }
	session_write_close();
	$_SESSION['db']->disconnect($_SESSION['dbName']);
?>	