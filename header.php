<?php
	//Initialize Session Datastructures
	if(!isset($_SESSION['db']['Obj']) || $_SESSION['Reset']){
		//echo "DATABASE CONNECTED <BR>";
		$_SESSION['db']['Obj'] = new Sql();
		$_SESSION['db']['Obj']->init($_SESSION['db']['Host'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
		$_SESSION['db']['Obj']->connect($_SESSION['db']['Name']);
	}elseif(!$_SESSION['db']['Obj']->con($_SESSION['db']['Name'])){ $_SESSION['db']['Obj']->connect($_SESSION['db']['Name']); }
	if(!isset($_SESSION['Blog']) || $_SESSION['Reset']){
		//echo "BLOG LOADED <BR>";
		$_SESSION['Blog'] = new Blog(1);
		$_SESSION['Blog']->dbRead($_SESSION['db']['Obj']->con($_SESSION['db']['Name']));
		$_SESSION['Blog']->load($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),false,true);
	}
	if(!isset($_SESSION['Media']) || $_SESSION['Reset']){
		//echo "BLOG LOADED <BR>";
		$_SESSION['Media'] = new MediaLibrary(18);
		$_SESSION['Media']->dbRead($_SESSION['db']['Obj']->con($_SESSION['db']['Name']));
		$_SESSION['Media']->load($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),false,true);
		
		/* Create standalone Gallery Page Definitions
		$gal = $_SESSION['Media']->getGalleries()->getFirstNode();
		while($gal != NULL){
			$g = $gal->readNode()->toArray();
			$_SESSION['Pages'][] = array("id"=>count($_SESSION['Pages']),"meta-title"=>$g['Definition'],"meta-description"=>$g['Definition'],"path-ui"=>"/".strtolower($g['Definition']),"meta-keywords"=>NULL,"path-file"=>"/page/gallery.php");
			$gal = $gal->getNext();
		}
		*/
	}
	if(!isset($_SESSION['Users']) || $_SESSION['Reset']){
		//echo "USERS LOADED <BR>";
		$_SESSION['Users'] = new DLList();
		$sql = "SELECT d.*, u.*, group_concat(distinct concat(r.ID,':',r.RID,':',r.KID,':',r.Key,':',r.Code,':',r.Definition) separator ';') AS `Groups`, group_concat(distinct concat(`p`.`DBO_ID`,':',`p`.`Name`,':',`p`.`PID`,':',`p`.`Primary`,':',`p`.`Region`,':',`p`.`Area`,':',`p`.`Number`,':',`p`.`Ext`) separator ';') AS `Phones`, group_concat(distinct concat(`a`.`DBO_ID`,':',`a`.`Name`,':',`a`.`PID`,':',`a`.`Primary`,':',`a`.`Address`,':',`a`.`Address2`,':',`a`.`City`,':',`a`.`State`,':',`a`.`Zip`) separator ';') AS `Addresses`, group_concat(distinct concat(`e`.`DBO_ID`,':',`e`.`Name`,':',`e`.`PID`,':',`e`.`Primary`,':',`e`.`Address`) separator ';') AS `Emails` FROM DBObj d INNER JOIN Users u ON d.ID = u.DBO_ID LEFT JOIN Relationships r ON d.ID = r.RID AND r.Key = 'Group' LEFT JOIN `Addresses` `a` on `a`.`PID` = `d`.`ID` LEFT JOIN `Phones` `p` on `p`.`PID` = `d`.`ID` LEFT JOIN `Emails` `e` on `e`.`PID` = `d`.`ID` GROUP BY d.ID ORDER BY d.Created DESC";
		$res = mysqli_query($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),$sql);
		while($row = mysqli_fetch_array($res)){
			$u = new User(NULL);
			$u->initMysql($row);
			$_SESSION['Users']->insertLast($u);
		}
	}
	if(!isset($_SESSION['User']) || $_SESSION['Reset']){ $_SESSION['User'] = NULL; }
	$_SESSION['Error'] = array("404"=>array("path-file"=>NULL,"path-ui"=>NULL),"401"=>NULL);
	//Process Page Address
	if(isset($_REQUEST['pg']) && $_REQUEST['pg'] != "" && $_REQUEST['pg'] != NULL){
		$found = false;
		foreach($_SESSION['Pages'] as $page){ if($page['path-ui'] == "/".strtolower($_REQUEST['pg'])){ $found = true; $_SESSION['Page'] = $page; if(!file_exists(ltrim($page['path-file'],"/"))){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-file'] = $page['path-file']; } break; } }
		if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = "/".$_REQUEST['pg']; }
	}else{ foreach($_SESSION['Pages'] as $page){ if($page['path-ui'] == "/"){ $_SESSION['Page'] = $page; break; } } }
	//Process Blog Page
	if($_SESSION['Page']['path-file'] == "/page/blog.php"){
		if(isset($_REQUEST['bpg'])){
			switch($_REQUEST['bpg']){
				default:
					if($_REQUEST['bpg'] != NULL && $_REQUEST['bpg'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['bpgi']) && $_REQUEST['bpgi'] != NULL && $_REQUEST['bpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['bpgn']) && !is_numeric($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL && $_REQUEST['bpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
				break;
				case "p":
					$_SESSION['db']['Obj']->connect($_SESSION['db']['Name']); $_SESSION['Page']['Current'] = NULL; $found = false;				
					$page = $_SESSION['Blog']->getContentPage($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),$_REQUEST['bpgi']);
					$post = $page->getFirstNode(); while($post != NULL){ $a = $post->readNode()->toArray(); if($a['ID'] == $_REQUEST['bpgi']){ $found = true; $_SESSION['Page']['Current'] = $post; break; } $post = $post->getNext();	}
					if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
					else{ $_SESSION['Page']['meta-title'] = "Blog Post - ".$a['Title']; $_SESSION['Page']['meta-description'] = $a['Description']; $_SESSION['Page']['meta-keywords'] = implode(",",$a['Keywords']); if($a['CoverImage'] != NULL && $a['CoverImage'] != ""){ $_SESSION['Page']['meta-og-image'] = $a['CoverImage']; list($_SESSION['Page']['meta-og-image-width'], $_SESSION['Page']['meta-og-image-height'], $_SESSION['Page']['meta-og-image-type']) = getimagesize(((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || $_SERVER['HTTPS'] == 'on' ? "https://" : "http://").$_SESSION['Domain'].$a['CoverImage']); $_SESSION['Page']['meta-og-type'] = "article"; } }
				break;
				case "c": $_SESSION['Page']['meta-title'] = "Blog Category - ".$_REQUEST['bpgi']; $_SESSION['Page']['meta-description'] = "Listing of blog entries tagged in ".$_REQUEST['bpgi']; break;
				case "u": $_SESSION['Page']['meta-title'] = "Blog Author - ".$_REQUEST['bpgi']; $_SESSION['Page']['meta-description'] = "Listing of blog entries written by ".$_REQUEST['bpgi']; break;
				case "a": $_SESSION['Page']['meta-title'] = "Blog Archive - ".date("F Y",strtotime($_REQUEST['bpgi'])); $_SESSION['Page']['meta-description'] = "Listing of blog entries posted in ".date("F, Y",strtotime($_REQUEST['bpgi']));	break;
				case "admin": $_SESSION['Page']['meta-title'] = "Blog Administration"; $_SESSION['Page']['meta-description'] = "Blog Admin Console"; if(isset($_REQUEST['bpgi'])){ $_SESSION['Page']['meta-title'] .= " - ".ucfirst($_REQUEST['bpgi']); } break;
			}
		}else{
			$_REQUEST['bpg'] = NULL;
			if(isset($_REQUEST['bpgi']) && $_REQUEST['bpgi'] != NULL && $_REQUEST['bpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
			if(isset($_REQUEST['bpgn']) && !is_numeric($_REQUEST['bpgn']) && $_REQUEST['bpgn'] != NULL && $_REQUEST['bpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
		}
	}
	//Process Media Page
	if($_SESSION['Page']['path-file'] == "/page/media.php"){
		if(isset($_REQUEST['mpg'])){
			switch($_REQUEST['mpg']){
				default:
					if($_REQUEST['mpg'] != NULL && $_REQUEST['mpg'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['mpgi']) && $_REQUEST['mpgi'] != NULL && $_REQUEST['mpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI'];  }
					if(isset($_REQUEST['mpgn']) && !is_numeric($_REQUEST['mpgn']) && $_REQUEST['mpgn'] != NULL && $_REQUEST['mpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
				break;
				case "d":
					$_SESSION['db']['Obj']->connect($_SESSION['db']['Name']); $_SESSION['Page']['Current'] = NULL; $found = false;
					$page = $_SESSION['Media']->getContentPage($_SESSION['db']['Obj']->con($_SESSION['db']['Name']),$_REQUEST['mpgi']);
					$media = $page->getFirstNode(); while($media != NULL){ $a = $media->readNode()->toArray(); if($a['ID'] == $_REQUEST['mpgi']){ $found = true; $_SESSION['Page']['Current'] = $media; break; } $media = $media->getNext(); }
					if(!$found){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
					else{ $_SESSION['Page']['meta-title'] = "Media Details - ".$a['Title']; $_SESSION['Page']['meta-description'] = $a['Description']; $_SESSION['Page']['meta-keywords'] = implode(",",$a['Keywords']); if($a['URI'] != NULL && $a['URI'] != ""){ $_SESSION['Page']['meta-og-image'] = $a['URI']; list($_SESSION['Page']['meta-og-image-width'], $_SESSION['Page']['meta-og-image-height'], $_SESSION['Page']['meta-og-image-type']) = getimagesize(((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') || $_SERVER['HTTPS'] == 'on' ? "https://" : "http://").$_SESSION['Domain'].$a['URI']); $_SESSION['Page']['meta-og-type'] = "article"; } }
				break;
				case "c": $_SESSION['Page']['meta-title'] = "Media Category - ".$_REQUEST['mpgi']; $_SESSION['Page']['meta-description'] = "Listing of media entries tagged in ".$_REQUEST['mpgi']; break;
				case "g": $_SESSION['Page']['meta-title'] = "Media Gallery - ".$_REQUEST['mpgi']; $_SESSION['Page']['meta-description'] = "Listing of media entries tagged in ".$_REQUEST['mpgi']; break;
				case "u": $_SESSION['Page']['meta-title'] = "Media Author - ".$_REQUEST['mpgi']; $_SESSION['Page']['meta-description'] = "Listing of media entries written by ".$_REQUEST['mpgi']; break;
				case "a": $_SESSION['Page']['meta-title'] = "Media Archive - ".date("F Y",strtotime($_REQUEST['mpgi'])); $_SESSION['Page']['meta-description'] = "Listing of media entries posted in ".date("F, Y",strtotime($_REQUEST['mpgi'])); break;
				case "admin": $_SESSION['Page']['meta-title'] = "Media Administration"; $_SESSION['Page']['meta-description'] = "Blog Admin Console"; if(isset($_REQUEST['mpgi'])){ $_SESSION['Page']['meta-title'] .= " - ".ucfirst($_REQUEST['mpgi']); } break;
			}
		}else{
			$_REQUEST['mpg'] = NULL;
			if(isset($_REQUEST['mpgi']) && $_REQUEST['mpgi'] != NULL && $_REQUEST['mpgi'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
			if(isset($_REQUEST['mpgn']) && !is_numeric($_REQUEST['mpgn']) && $_REQUEST['mpgn'] != NULL && $_REQUEST['mpgn'] != ""){ $_SESSION['Page'] = $_SESSION['Pages'][0]; $_SESSION['Error']['404']['path-ui'] = $_SERVER['REQUEST_URI']; }
		}
	}
	//Process User Page
	if($_SESSION['Page']['path-file'] == "/page/user.php"){ $upage = ""; }
	//HTTP 404
	if($_SESSION['Page']['id'] == 0){ header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); }
	//HTTP 401
	if($_SESSION['Page']['id'] == 1){ header($_SERVER["SERVER_PROTOCOL"]." 401 Not Authorized", true, 401); }
?>