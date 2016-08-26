<?php
	require_once("script/_php/lib.php");
	include("script/_php/DBObj/dbobj.php");
	session_start();
	
	if(isset($_REQUEST['ari']) || $_REQUEST['ari'] != NULL || $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 1: 
				
			break;
			default:
				echo "BAD REQUEST"; 
			break;
		}
		session_write_close();
	}else{ echo "BAD REQUEST"; }
?>	