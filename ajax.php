<?php
	require_once("script/_php/lib.php"); include("script/_php/DBObj/dbobj.php");
	session_start();
	
	if(isset($_REQUEST['ari']) || $_REQUEST['ari'] != NULL || $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 1: //Site Initial Call
				$data = array();
				
				if(isset($_REQUEST['p'])){ for($i = 0; $i < count($_SESSION['Pages']); $i++){ if($_REQUEST['p'] == $_SESSION['Pages'][$i]['id']){ $_SESSION['Page'] = $_SESSION['Pages'][$i]; break; } } }
				$data[0] = $_SESSION['Page'];
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			case 2: //Page Requests - Files
				$page = $_REQUEST['p'];
				$exist_i = false;
				$exist_f = false;
				$root = __DIR__;
				
				$_SESSION['Error'] = array("404"=>array("path-file"=>NULL,"path-ui"=>NULL));
				for($i = 0; $i < count($_SESSION['Pages']); $i++){ if($_SESSION['Pages'][$i]['path-ui'] == strtolower($page)){ $exist_i = true; $_SESSION['Page'] = $_SESSION['Pages'][$i]; break;} }
				$exists_f = file_exists(ltrim($_SESSION['Page']['path-file'],"/"));
				if(!$exist_i || !$exists_f){ if(!$exist_i){ $_SESSION['Error']['404']['path-ui'] = strtolower($page); } if(!$exists_f){ $_SESSION['Error']['404']['path-file'] = $_SESSION['Page']['path-file']; }	$_SESSION['Page'] = $_SESSION['Pages'][0]; }
				
				ob_start();
				include ltrim($_SESSION['Page']['path-file'],"/");
				$html = ob_get_clean();
				$img = parseImgs($root.$_SESSION['Page']['path-file']);
				
				$data = array($html,$img,$_SESSION['Page']);
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			default:
				echo "BAD REQUEST";
			break;
		}
		session_write_close();
	}else{ echo "BAD REQUEST"; }
?>	