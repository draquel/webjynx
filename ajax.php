<?php
	require_once("script/_php/lib.php");
	include("script/_php/DBObj/dbobj.php");
	session_start();
	
	if(isset($_REQUEST['ari']) || $_REQUEST['ari'] != NULL || $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 1: //Site Initial Call
				$data = array();
				if(isset($_REQUEST['pid'])){ for($i = 0; $i < count($_SESSION['Pages']); $i++){ if($_REQUEST['pid'] == $_SESSION['Pages'][$i]['id']){ $_SESSION['Page'] = $_SESSION['Pages'][$i]; break; } } }
				$data[0] = $_SESSION['Page'];
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			case 2: //Page Requests - Files
				$page = $_REQUEST['pp'];
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
				libxml_use_internal_errors(true);
				$img = parseImgs($root.$_SESSION['Page']['path-file']);
				libxml_clear_errors();
				
				$data = array($html,$img,$_SESSION['Page']);
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			case 3: //Blog Page Requests - Files
				$page = ltrim($_REQUEST['pp'],"/");
				$path = "/blog/index.php";
				$p = explode("/",$page);
				
				if(is_numeric($p[1])){ /* Blog Page*/ $_REQUEST['bpg'] = $p[1]; }
				else{
					if($p[1] == "a"){ /*Archive*/ $_REQUEST['a'] = $p[2]; if(isset($p[3])){ $_REQUEST['bap'] = $p[3]; } }
					elseif($p[1] == "c"){ /*Category*/ $_REQUEST['c'] = $p[2]; if(isset($p[3])){ $_REQUEST['bcp'] = $p[3]; } }
					elseif($p[1] == "p"){ /*Post*/ $_REQUEST['p'] = $p[2]; }
					elseif($p[1] == "u"){ /*Author*/ $_REQUEST['u'] = $p[2]; }
				}
				
				ob_start();
				include __DIR__."/page".$path;
				$html = ob_get_clean();
				libxml_use_internal_errors(true);
				$img = parseImgs(__DIR__."/page".$path);
				libxml_clear_errors();
				$data = array($html,$img,array("id"=>-1,"meta-title"=>"","meta-description"=>"","path-ui"=>"/".$page,"path-file"=>"/page".$path));
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			default:
				echo "BAD ARI REQUEST";
			break;
		}
		session_write_close();
	}else{ echo "BAD REQUEST"; }
?>	
