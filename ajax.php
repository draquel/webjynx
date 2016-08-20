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
				if(!$exist_i || !$exists_f){ if(!$exist_i){ $_SESSION['Error']['404']['path-ui'] = strtolower($page); }
				if(!$exists_f){ $_SESSION['Error']['404']['path-file'] = $_SESSION['Page']['path-file']; }	$_SESSION['Page'] = $_SESSION['Pages'][0]; }
				
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
				$page = $_REQUEST['pp'];
				$exist_i = false;
				$exist_f = false;
				$root = __DIR__;
				
				$_SESSION['Error'] = array("404"=>array("path-file"=>NULL,"path-ui"=>NULL));
				for($i = 0; $i < count($_SESSION['Pages']); $i++){ if($_SESSION['Pages'][$i]['path-ui'] == strtolower($page) || ($_SESSION['Pages'][$i]['path-ui'] == "/blog/" && substr($page, 0, strlen($_SESSION['Pages'][$i]['path-ui'])) == $_SESSION['Pages'][$i]['path-ui'])){ $exist_i = true; $_SESSION['Page'] = $_SESSION['Pages'][$i]; break;} }
				$exists_f = file_exists(ltrim($_SESSION['Page']['path-file'],"/"));
				if(!$exist_i || !$exists_f){ if(!$exist_i){ $_SESSION['Error']['404']['path-ui'] = strtolower($page); } }
				if(!$exists_f){ $_SESSION['Error']['404']['path-file'] = $_SESSION['Page']['path-file']; }

				
				if($_SESSION['Page']['path-file'] == "/page/blog.php"){
					$p = explode("/",ltrim($page,"/"));
					if(is_numeric($p[1])){ /* Blog Page*/ $_REQUEST['bpg'] = $p[1]; }
					else{
						$_SESSION['Page']['path-ui'] = $page;
						if($p[1] == "a"){
							/*Archive*/
							$_REQUEST['a'] = $p[2];
							if(isset($p[3])){ $_REQUEST['bap'] = $p[3]; } 
							$_SESSION['Page']['meta-title'] = "Blog Archive - ".date("F Y",strtotime($_REQUEST['a']));
							$_SESSION['Page']['meta-description'] = "Listing of blog entries posted in ".date("F, Y",strtotime($_REQUEST['a']));
						}
						elseif($p[1] == "c"){
							/*Category*/
							$_REQUEST['c'] = $p[2];
							if(isset($p[3])){ $_REQUEST['bcp'] = $p[3]; }
							$_SESSION['Page']['meta-title'] = "Blog Category - ".$_REQUEST['c'];
							$_SESSION['Page']['meta-description'] = "Listing of blog entries tagged in ".$_REQUEST['c'];
						}
						elseif($p[1] == "u"){ 
							/*Author*/
							$_REQUEST['u'] = $p[2];
							if(isset($p[3])){ $_REQUEST['bup'] = $p[3]; }
							$_SESSION['Page']['meta-title'] = "";
							$_SESSION['Page']['meta-description'] = "";
						}
						elseif($p[1] == "p"){
							/*Post*/
							$_REQUEST['p'] = $p[2];
							$_SESSION['Page']['meta-title'] = "";
							$_SESSION['Page']['meta-description'] = "";
						}
					}
				}
				
				ob_start();
				include ltrim($_SESSION['Page']['path-file'],"/");
				$html = ob_get_clean();
				libxml_use_internal_errors(true);
				$img = parseImgs($root.$_SESSION['Page']['path-file']);
				libxml_clear_errors();
				$data = array($html,$img,array("id"=>$_SESSION['Page']['id'],"meta-title"=>$_SESSION['Page']['meta-title'],"meta-description"=>$_SESSION['Page']['meta-description'],"path-ui"=>$_SESSION['Page']['path-ui'],"path-file"=>$_SESSION['Page']['path-file']));
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