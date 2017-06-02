<?php
require_once("lib/DBObj/php/lib.php");
require_once("lib/DBObj/php/sql.class.php");
require_once("lib/DBObj/php/user.class.php");
require_once("lib/DBObj/php/blog.class.php");
require_once("lib/DBObj/php/mediaLibrary.class.php");
session_start();
error_reporting(E_ALL);
/*$pdo = new Sql();
$pdo->init($_SESSION['db']['Host'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
$pdo->connect($_SESSION['db']['Name']);*/
$pdo = new PDO("mysql:host=".$_SESSION['db']['Host'].";dbname=".$_SESSION['db']['Name'],$_SESSION['db']['User'],$_SESSION['db']['Pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

if(isset($_REQUEST['ari']) || isset($_REQUEST['uri']) || isset($_REQUEST['bri']) || isset($_REQUEST['mri'])){
/*Site Ajax Requests*/
	if(isset($_REQUEST['ari']) && $_REQUEST['ari'] != NULL && $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 1: // Get Survey
				if(!isset($_SESSION['User']) || $_SESSION['User'] == NULL){ $data[] = 0; }
				else{
					$data[] = 1;
					$data[] = "";
					$data[] = "<iframe src='https://survey.zohopublic.com/zs/1bDXzF' frameborder='0' style='height:800px;width:100%;' marginwidth='0' marginheight='0' scrolling='auto'></iframe>";
				}
				echo json_encode($data);
			break;
			default:
				echo "BAD ARI"; 
			break;
		}
	}
/*User Requests*/
	if(isset($_REQUEST['uri']) && $_REQUEST['uri'] != NULL && $_REQUEST['uri'] != ""){
		switch($_REQUEST['uri']){
			case 1: //Login
				$user = $_REQUEST['user']; $pass = $_REQUEST['pass']; $auth = false;
				$_SESSION['User'] = new User(NULL);
				$auth = $_SESSION['User']->login($user,$pass,$pdo);
				if($auth){ echo 1; }else{ $_SESSION['User'] = NULL; echo 2; }
			break;
			case 2: //Logout
				$_SESSION['User'] = NULL;
			break;
			default:
				echo "BAD URI"; 
			break;
		}
		if(isset($_SESSION['db'])){ $pdo->disconnect($_SESSION['db']['Name']); }
	}
/*Blog Requests*/
	if(isset($_REQUEST['bri']) && $_REQUEST['bri'] != NULL && $_REQUEST['bri'] != ""){
		if(!isset($_SESSION['User']) || !$_SESSION['User']->getRelation("Group")->hasRel(6)){
			header("HTTP/1.1 401 Unauthorized");
    		exit;
		}
		switch($_REQUEST['bri']){
			case 1: //Create Post Form
				$blog = $_SESSION['Blog']->toArray();
				$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
				$user = $_SESSION['Users']->getFirstNode();
				$ua = $_SESSION['User']->toArray();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form enctype=\"multipart/form-data\">
					  <input id=\"bri\" type=\"hidden\" value=\"3\">
					  <div class=\"form-group\"><label for=\"Title\">Post Title</label><input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\"></div>
					  <div class=\"form-group\"><label for=\"Description\">Meta Description</label><input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\"></div>
					  <div class=\"form-group\"><label for=\"Keywords\">Meta Keywords</label><input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\"></div>
					  <div class=\"form-group\"><label for=\"Author\">Author</label><select class=\"form-control\" id=\"Author\">";
				while($user != NULL){ $u = $user->readNode()->toArray(); $html .= "<option value=\"".$u['ID']."\" ".($u['ID'] == $ua['ID'] ? " selected" : NULL ).">".$u['First']." ".$u['Last']."</option>";	$user = $user->getNext(); }
				$html .= "</select></div>
					  <div class=\"form-group\"><label for=\"Published\">Publish Date</label><input type=\"text\" class=\"form-control datetimepicker\" id=\"Published\" ></div>
					  <div class=\"form-group\"><label for=\"coverImage\">Cover Image</label><input type=\"file\" id=\"coverImage\" accept=\"image/jpeg,image/png,image/gif\"></div>
					  <div class=\"form-group\"><label>Categories</label><select multiple class=\"form-control\" id=\"Categories\">";
				while($bcat != NULL){ $bca = $bcat->readNode()->toArray(); $html .= "<option value=\"".$bca['KID']."\">".$bca['Definition']."</option>"; $bcat = $bcat->getNext(); }
				$html .= "</select></div>
					  <div class=\"form-group\"><textarea id=\"HTML\" class=\"form-control trumbowyg\"></textarea></div>
					  <div class=\"form-group\"><div class=\"checkbox\"><label><input id=\"Active\" type=\"checkbox\" > Make Live </label></div></div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
				  </form>
				";
				$html = preg_replace('/(\v|\s)+/', ' ', $html);
				$data = array("Create New Post",$html);
				echo json_encode($data);
			break;
			case 2: //Edit Post Form
				$id = $_REQUEST['i'];
				$post = new Post($id);
				$post->dbRead($pdo);
				$a = $post->toArray();
				$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
				$user = $_SESSION['Users']->getFirstNode();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form enctype=\"multipart/form-data\">
					  <input id=\"bri\" type=\"hidden\" value=\"3\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$a['ID']."\">
					  <div class=\"form-group\"><label for=\"Title\">Post Title</label><input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$a['Title']."\"></div>
					  <div class=\"form-group\"><label for=\"metaDescription\">Meta Description</label><input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\" value=\"".$a['Description']."\"></div>
					  <div class=\"form-group\"><label for=\"metaKeywords\">Meta Keywords</label><input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\" value=\"". implode(",",$a['Keywords']) ."\"></div>
					  <div class=\"form-group\"><label for=\"Author\">Author</label><select class=\"form-control\" id=\"Author\">";
				while($user != NULL){ $u = $user->readNode()->toArray(); $html .= "<option value=\"".$u['ID']."\" ".($u['ID'] == $a['Author'] ? " selected" : NULL ).">".$u['First']." ".$u['Last']."</option>"; $user = $user->getNext(); }
				$html .= "</select></div>
					  <div class=\"form-group\"><label for=\"Published\">Publish Date</label>".($a['Published'] > time() ? "<input type=\"text\" class=\"form-control datetimepicker\" id=\"Published\" value=\"".date("m/d/Y g:i A",$a['Published'])."\" >" : "<div><label>".date("m/d/Y g:i A",$a['Published'])."</label></div><input type=\"hidden\" class=\"form-control\" id=\"Published\" value=\"".date("m/d/Y g:i A",$a['Published'])."\" >")."</div>
					  <div class=\"form-group\"><label for=\"coverImage\">Cover Image</label>".($a['CoverImage'] != NULL && $a['CoverImage'] != "" ? "<div><img class='img-responsive img-thumbnail' src='".$a['CoverImage']."' style='max-height:150px;max-width:400px;margin-bottom:15px;'></div>" : NULL)."<input type=\"file\" id=\"coverImage\"></div>
					  <div class=\"form-group\"><label>Categories</label><select multiple class=\"form-control\" id=\"Categories\">";
					while($bcat != NULL){
						$sel = false;
						$bca = $bcat->readNode()->toArray();
						if(count($a['Rels']['Category'])){ for($i = 0; $i < count($a['Rels']['Category']); $i++){ if($bca['KID'] == $a['Rels']['Category'][$i]['KID']){ $sel = true; break; } } }
						$html .= "<option value=\"".$bca['KID']."\"".($sel ? " selected" : NULL ).">".$bca['Definition']."</option>";
						$bcat = $bcat->getNext();
					}
					$html .= "
						</select></div>
					  <div class=\"form-group\"><textarea id=\"HTML\" class=\"form-control trumbowyg\">".$a['HTML']."</textarea></div>
					  <div class=\"form-group\"><div class=\"checkbox\"><label><input id=\"Active\" type=\"checkbox\"".($a['Active'] == 1 ? " checked" : NULL)."> Make Live</label></div></div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
				  </form>
				";
				$html = preg_replace('/(\v|\s)+/', ' ', $html);
				$data = array("Edit Post",$html);
				echo json_encode($data);
			break;
			case 3: //Create and Update Post Records (Forms BRI: 1 & 2)
				$b = $_SESSION['Blog']->toArray();
				$dateTime = explode(" ",$_REQUEST['Published']);
				$date = explode("/",$dateTime[0]);
				$_REQUEST['Published'] = strtotime($date[2].'-'.$date[0].'-'.$date[1].' '.$dateTime[1].' '.$dateTime[2]);
				if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ //Update
					$id = $_REQUEST['ID'];
					$post = new Post($id);
					$post->dbRead($pdo);
					$in = array("Title"=>$_REQUEST['Title'],"Author"=>$_REQUEST['Author'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML'],"Published"=>$_REQUEST['Published']);
					if(isset($_FILES["coverImage"])){ $ext = explode(".", $_FILES["File"]["name"]); $img_path = "img/blog/".$id.".".end($ext); $in["CoverImage"] = "/".$img_path; }
					$post->initMysql($in);
				}else{ //Create
					$id = 0;
					$post = new Post(0);
					$in = array("Title"=>$_REQUEST['Title'],"Author"=>$_REQUEST['Author'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML'],"Published"=>$_REQUEST['Published']);
					$post->initMysql($in);
				}
				if($id == 0){ //Generate Parent Relationship
					$r = new Relation();
					$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>$b['ID'],"KID"=>3));
					$post->setParentRel($r);
				}
				//Generate Relations for Selected Categories
				$icats = explode(",",$_REQUEST['Categories']);
				for($i = 0; $i < count($icats); $i++){
					$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
					while($bcat != NULL){
						$ba = $bcat->readNode()->toArray();
						if($icats[$i] == $ba['KID']){ 
							$dup = false;
							if($id != 0 && $post->getCategories()->size() > 0){ //Check For Duplicate Relations
								$pcat = $post->getCategories()->getFirstNode();
								while($pcat != NULL){
									$pca = $pcat->readNode()->toArray();
									if($icats[$i] == $pca['KID']){ $dup = true; break; }
									$pcat = $pcat->getNext();
								}
							}
							if($id == 0 || !$dup){ //Add New Relationships
								$r = new Relation();
								$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>0,"KID"=>$ba['KID'],"Code"=>$ba['Code'],"Definition"=>$ba['Definition']));
								$post->getCategories()->insertLast($r);
							}
							break;
						}
						$bcat = $bcat->getNext();
					}
				}
				//Delete obsolete relations
				if($id != 0){ 
					$pcat = $post->getCategories()->getFirstNode();
					while($pcat != NULL){
						$pca = $pcat->readNode()->toArray();
						$obs = true;
						foreach($icats as $c){ if($c == $pca['KID'] || $pca['KID'] == 3){ $obs = false; break; } }
						if($obs){ $pcat->readNode()->dbDelete($pdo);}
						$pcat = $pcat->getNext();
					}
				}
				//Write to Database {and Update Session (obsolete)}
				$data = array();
				if($post->dbWrite($pdo)){ 
					$data[] = 1;
					if($id == 0){ 
						$data[] = "Post Created!"; 
						$a = $post->toArray();
						$id = $a['ID'];
						if(isset($_FILES["coverImage"])){ $img_path = "img/blog/".$id.".".end((explode(".", $_FILES["coverImage"]["name"]))); $in["CoverImage"] = "/".$img_path; }
						$post->initMysql($in);
						$post->dbWrite($pdo);
					}else{ $data[] = "Post Updated!"; }
					if(isset($_FILES["coverImage"]) && getimagesize($_FILES["coverImage"]["tmp_name"])){ move_uploaded_file($_FILES["coverImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$img_path); }
				}
				else{ $data[] = 0; $data[] = "Error!"; }
				echo json_encode($data);
			break;
			case 4: //Delete Post Form
				$id = $_REQUEST['i'];
				$post = new Post($id);
				$post->dbRead($pdo);
				$a = $post->toArray();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <div class=\"text-center\"><h5>Are you sure you want to Delete this Post:</h5><p>".$a['Title']."<p></div>
				  <form>
					  <input id=\"bri\" type=\"hidden\" value=\"5\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$a['ID']."\">
					  <label></label>
					  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
				  </form>
				";
				$data = array("Delete Post",$html);
				echo json_encode($data);
			break;
			case 5: //Delete Post
				$id = $_REQUEST['ID'];
				$post = new Post($id);
				$post->dbRead($pdo);

				if($post->dbDelete($pdo)){ 
					$data[] = 1;
					$data[] = "Post Deleted!";
					$pa = $post->toArray();
					unlink(rtrim($_SERVER['DOCUMENT_ROOT'],"/").$pa['CoverImage']);
				}
				else{ $data[] = 0; $data[] = "Error!"; }
				echo json_encode($data);
			break;
			case 6: // Create Category Form
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"bri\" type=\"hidden\" value=\"8\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Category Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
				  </form>
				";
				$data = array("Create New Category",$html);
				echo json_encode($data);
			break;
			case 7: // Edit Category Form
				$id = $_REQUEST['i'];
				$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
				while($cat != null){ $ca = $cat->readNode()->toArray(); if($ca['ID'] == $id){ break; } $cat = $cat->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"bri\" type=\"hidden\" value=\"8\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ca['ID']."\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Category Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$ca['Definition']."\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
				  </form>
				";
				$data = array("Update Category",$html);
				echo json_encode($data);
			break;
			case 8; //Create and Update Category Records (Forms BRI: 6 & 7)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$time = time();
				if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ $id = $_REQUEST['ID']; $sql = "Update `Keys` SET Definition = \"".$_REQUEST['Title']."\", Updated = ".$time." WHERE ID = ".$_REQUEST['ID']; }
				else{ $id = 0; $sql = "INSERT INTO `Keys` (`Key`,`Code`,`Definition`,`Created`,`Updated`) VALUES(\"Category\",\"Post\",\"".$_REQUEST['Title']."\",".$time.",".$time.")"; }
				//error_log("SQL DBObj->Relation: ".$sql);
				if(mysqli_query($pdo,$sql)){
					$data[] = 1;
					if($id == 0){ $data[] = "Category Created!"; }else{ $data[] = "Category Updated!"; }
					$_SESSION['Blog']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			case 9: //Delete Category Form
				$id = $_REQUEST['i'];
				$cat = $_SESSION['Blog']->getCategories()->getFirstNode();
				while($cat != null){ $ca = $cat->readNode()->toArray(); if($ca['ID'] == $id){ break; } $cat = $cat->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <div class=\"text-center\"><h5>Are you sure you want to Delete this Category:</h5><p>".$ca['Definition']."<p></div>
				  <form>
					  <input id=\"bri\" type=\"hidden\" value=\"10\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ca['ID']."\">
					  <label></label>
					  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
				  </form>
				";
				$data = array("Delete Category",$html);
				echo json_encode($data);
			break;
			case 10: // Delete Category (Form BRI: 9)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$id = $_REQUEST['ID'];
				$sql1 = "DELETE FROM `Keys` WHERE ID = ".$id; $sql2 = "DELETE FROM Relations WHERE KID = ".$id.";";
				if(mysqli_query($pdo,$sql1) && mysqli_query($pdo,$sql2)){
					$data[] = 1;
					$data[] = "Category Deleted!";
					$_SESSION['Blog']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			default:
				echo "BAD BRI"; 
			break;
		}
	}
/*Media Requests*/
	if(isset($_REQUEST['mri']) && $_REQUEST['mri'] != NULL && $_REQUEST['mri'] != ""){
		if(!isset($_SESSION['User']) || !$_SESSION['User']->getRelation("Group")->hasRel(6)){
			header("HTTP/1.1 401 Unauthorized");
    		exit;
		}
		switch($_REQUEST['mri']){
			case 1: //Create Media Form
				$media = $_SESSION['Media']->toArray();
				$mcat = $_SESSION['Media']->getCategories()->getFirstNode();
				$mgal = $_SESSION['Media']->getGalleries()->getFirstNode();
				$user = $_SESSION['Users']->getFirstNode();
				$ua = $_SESSION['User']->toArray();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"3\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Media Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\">
						<div class=\"form-group\"><label for=\"Description\">Meta Description</label><input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\"></div>
					  	<div class=\"form-group\"><label for=\"Keywords\">Meta Keywords</label><input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\"></div>
					  	<div class=\"form-group\"><label for=\"Author\">Author</label><select class=\"form-control\" id=\"Author\">";
					while($user != NULL){ $u = $user->readNode()->toArray(); $html .= "<option value=\"".$u['ID']."\" ".($u['ID'] == $ua['ID'] ? " selected" : NULL ).">".$u['First']." ".$u['Last']."</option>";	$user = $user->getNext(); }
				$html .= "</select></div>
						<div class=\"form-group\"><label>Galleries</label><select multiple class=\"form-control\" id=\"Galleries\">";
					while($mgal != NULL){ $mga = $mgal->readNode()->toArray(); $html .= "<option value=\"".$mga['KID']."\">".$mga['Definition']."</option>"; $mgal = $mgal->getNext(); }
				$html .= "</select></div>
						<div class=\"form-group\"><label>Categories</label><select multiple class=\"form-control\" id=\"Categories\">";
					while($mcat != NULL){ $mca = $mcat->readNode()->toArray(); $html .= "<option value=\"".$mca['KID']."\">".$mca['Definition']."</option>"; $mcat = $mcat->getNext(); }
				$html .= "</select></div>
						<div class=\"form-group\"><label for=\"file\">Media File</label><input type=\"File\" id=\"file\"></div>
						<div class=\"form-group\"><div class=\"checkbox\"><label><input id=\"Active\" type=\"checkbox\" > Make Live </label></div></div>
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Add</button></div>
				  </form>
				";
				$data = array("Add New Media",$html);
				echo json_encode($data);
			break;
			case 2:
				$id = $_REQUEST['i'];
				$media = new Media($id);
				$media->dbRead($pdo);
				$a = $media->toArray();
				$mcat = $_SESSION['Media']->getCategories()->getFirstNode();
				$mgal = $_SESSION['Media']->getGalleries()->getFirstNode();
				$user = $_SESSION['Users']->getFirstNode();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"3\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$a['ID']."\">
					  <div class=\"form-group\">
						<div class=\"form-group\"><label for=\"Title\">Post Title</label><input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$a['Title']."\"></div>
					  <div class=\"form-group\"><label for=\"metaDescription\">Meta Description</label><input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\" value=\"".$a['Description']."\"></div>
					  <div class=\"form-group\"><label for=\"metaKeywords\">Meta Keywords</label><input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\" value=\"". implode(",",$a['Keywords']) ."\"></div>
					  	<div class=\"form-group\"><label for=\"Author\">Author</label><select class=\"form-control\" id=\"Author\">";
					while($user != NULL){ $u = $user->readNode()->toArray(); $html .= "<option value=\"".$u['ID']."\" ".($u['ID'] == $a['Author'] ? " selected" : NULL ).">".$u['First']." ".$u['Last']."</option>"; $user = $user->getNext(); }
				$html .= "</select></div>
						<div class=\"form-group\"><label>Galleries</label><select multiple class=\"form-control\" id=\"Galleries\">";
					while($mgal != NULL){
						$sel = false;
						$mga = $mgal->readNode()->toArray();
						if(count($a['Rels']['Gallery'])){ for($i = 0; $i < count($a['Rels']['Gallery']); $i++){ if($mga['KID'] == $a['Rels']['Gallery'][$i]['KID']){ $sel = true; break; } } }
						$html .= "<option value=\"".$mga['KID']."\"".($sel ? " selected" : NULL ).">".$mga['Definition']."</option>";
						$mgal = $mgal->getNext();
					}
					$html .= "
						</select></div>
						<div class=\"form-group\"><label>Categories</label><select multiple class=\"form-control\" id=\"Categories\">";
					while($mcat != NULL){
						$sel = false;
						$mca = $mcat->readNode()->toArray();
						if(count($a['Rels']['Category'])){ for($i = 0; $i < count($a['Rels']['Category']); $i++){ if($mca['KID'] == $a['Rels']['Category'][$i]['KID']){ $sel = true; break; } } }
						$html .= "<option value=\"".$mca['KID']."\"".($sel ? " selected" : NULL ).">".$mca['Definition']."</option>";
						$mcat = $mcat->getNext();
					}
					$html .= "
						</select></div>
						<div class=\"form-group\"><label for=\"file\">Media File</label>".($a['URI'] != NULL && $a['URI'] != "" ? "<div><img class='img-responsive img-thumbnail' src='".$a['URI']."' style='max-height:150px;max-width:400px;margin-bottom:15px;'></div>" : NULL)."<input type=\"file\" id=\"File\"></div>
						<div class=\"form-group\"><div class=\"checkbox\"><label><input id=\"Active\" type=\"checkbox\"".($a['Active'] == 1 ? " checked" : NULL)."> Make Live</label></div></div>
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Add</button></div>
				  </form>
				";
				$data = array("Edit Media",$html);
				echo json_encode($data);
			break;
			case 3: /* Create and Update Media Object */
				$m = $_SESSION['Media']->toArray();
				if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ //Update
					$id = $_REQUEST['ID'];
					$media = new Media($id);
					$media->dbRead($pdo);
					$in = array("Title"=>$_REQUEST['Title'],"Author"=>$_REQUEST['Author'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active']);
					if(isset($_FILES["File"])){	$ext = explode(".", $_FILES["File"]["name"]); $img_path = "img/media/".$id.".".end($ext); $in["URI"] = "/".$img_path; $in['Type'] = $_FILES['File']['type']; }
					$media->initMysql($in);
				}else{ //Create
					$id = 0;
					$media = new Media(0);
					$in = array("Title"=>$_REQUEST['Title'],"Author"=>$_REQUEST['Author'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active']);
					$media->initMysql($in);
				}
				if($id == 0){ //Generate Parent Relationship
					$r = new Relation();
					$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>$m['ID'],"KID"=>10));
					$media->setParentRel($r);
				}
				//Generate Relations for Selected Galleries
				$igals = explode(",",$_REQUEST['Galleries']);
				for($i = 0; $i < count($igals); $i++){
					$mlgal = $_SESSION['Media']->getGalleries()->getFirstNode();
					while($mlgal != NULL){
						$mlga = $mlgal->readNode()->toArray();
						if($igals[$i] == $mlga['KID']){ 
							$dup = false;
							if($id != 0 && $media->getGalleries()->size() > 0){ //Check For Duplicate Relations
								$mgal = $media->getGalleries()->getFirstNode();
								while($mgal != NULL){
									$mca = $mgal->readNode()->toArray();
									if($igals[$i] == $mca['KID']){ $dup = true; break; }
									$mgal = $mgal->getNext();
								}
							}
							if($id == 0 || !$dup){ //Add New Relationships
								$r = new Relation();
								$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>0,"KID"=>$mlga['KID'],"Code"=>$mlga['Code'],"Definition"=>$mlga['Definition']));
								$media->getGalleries()->insertLast($r);
							}
							break;
						}
						$mlgal = $mlgal->getNext();
					}
				}
				//Generate Relations for Selected Categories
				$icats = explode(",",$_REQUEST['Categories']);
				for($i = 0; $i < count($icats); $i++){
					$mlcat = $_SESSION['Media']->getCategories()->getFirstNode();
					while($mlcat != NULL){
						$mlca = $mlcat->readNode()->toArray();
						if($icats[$i] == $mlca['KID']){
							$dup = false;
							if($id != 0 && $media->getCategories()->size() > 0){ //Check For Duplicate Relations
								$mcat = $media->getCategories()->getFirstNode();
								while($mcat != NULL){
									$mca = $mcat->readNode()->toArray();
									if($icats[$i] == $mca['KID']){ $dup = true; break; }
									$mcat = $mcat->getNext();
								}
							}
							if($id == 0 || !$dup){ //Add New Relationships
								$r = new Relation();
								$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>0,"KID"=>$mlca['KID'],"Code"=>$mlca['Code'],"Definition"=>$mlca['Definition']));
								$media->getCategories()->insertLast($r);
							}
							break;
						}
						$mlcat = $mlcat->getNext();
					}
				}
				//Delete obsolete relations
				if($id != 0){
					$mgal = $media->getGalleries()->getFirstNode();
					while($mgal != NULL){
						$mga = $mgal->readNode()->toArray();
						$obs = true;
						foreach($igals as $g){ if($g == $mga['KID'] || $mga['KID'] == 10){ $obs = false; break; } }
						if($obs){ $mgal->readNode()->dbDelete($pdo);}
						$mgal = $mgal->getNext();
					}
					$mcat = $media->getCategories()->getFirstNode();
					while($mcat != NULL){
						$mca = $mcat->readNode()->toArray();
						$obs = true;
						foreach($icats as $c){ if($c == $mca['KID'] || $mca['KID'] == 10){ $obs = false; break; } }
						if($obs){ $mcat->readNode()->dbDelete($pdo);}
						$mcat = $mcat->getNext();
					}
				}
				//Write to Database {and Update Session (obsolete)}
				$data = array();
				if($media->dbWrite($pdo)){ 
					$data[] = 1;
					if($id == 0){ 
						$data[] = "Media Added!"; 
						$a = $media->toArray();
						$id = $a['ID'];
						if(isset($_FILES["File"])){ $img_path = "img/media/".$id.".".end((explode(".", $_FILES["File"]["name"]))); $in["URI"] = "/".$img_path; $in['Type'] = $_FILES['File']['type']; }
						$media->initMysql($in);
						$media->dbWrite($pdo);
					}else{ 
						$data[] = "Media Updated!";
					}
					if(isset($_FILES["File"]) && getimagesize($_FILES["File"]["tmp_name"])){ move_uploaded_file($_FILES["File"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$img_path); }
				}
				else{ $data[] = 0; $data[] = "Error!"; }
				echo json_encode($data);
			break;
			case 4: //Delete Media Form
				$id = $_REQUEST['i'];
				$media = new Media($id);
				$post->dbRead($pdo);
				$a = $media->toArray();
				$html = "
				  <div class=\"alert hidden\"></div>
				  <div class=\"text-center\"><h5>Are you sure you want to Delete this Media:</h5><p>".$a['Title']."<p></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"5\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$a['ID']."\">
					  <label></label>
					  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
				  </form>
				";
				$data = array("Delete Media",$html);
				echo json_encode($data);
			break;
			case 5: //Delete Media
				$id = $_REQUEST['ID'];
				$media = new Media($id);
				$media->dbRead($pdo);
				if($media->dbDelete($pdo)){ 
					$data[] = 1;
					$data[] = "Post Deleted!";
					$ma = $media->toArray();
					unlink(rtrim($_SERVER['DOCUMENT_ROOT'],"/").$ma['URI']);
				}
				else{ $data[] = 0; $data[] = "Error!"; }
				echo json_encode($data);
			break;
			case 6: // Create Category Form
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"8\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Category Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
				  </form>
				";
				$data = array("Create New Category",$html);
				echo json_encode($data);
			break;
			case 7: // Edit Category Form
				$id = $_REQUEST['i'];
				$cat = $_SESSION['Media']->getCategories()->getFirstNode();
				while($cat != null){ $ca = $cat->readNode()->toArray(); if($ca['ID'] == $id){ break; } $cat = $cat->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"8\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ca['ID']."\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Category Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$ca['Definition']."\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
				  </form>
				";
				$data = array("Update Category",$html);
				echo json_encode($data);
			break;
			case 8; //Create and Update Category Records (Forms BRI: 6 & 7)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$time = time();
				if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ $id = $_REQUEST['ID']; $sql = "Update `Keys` SET Definition = \"".$_REQUEST['Title']."\", Updated = ".$time." WHERE ID = ".$_REQUEST['ID']; }
				else{ $id = 0; $sql = "INSERT INTO `Keys` (`Key`,`Code`,`Definition`,`Created`,`Updated`) VALUES(\"Category\",\"Media\",\"".$_REQUEST['Title']."\",".$time.",".$time.")"; }
				//error_log("SQL DBObj->Relation: ".$sql);
				if(mysqli_query($pdo,$sql)){
					$data[] = 1;
					if($id == 0){ $data[] = "Category Created!"; }else{ $data[] = "Category Updated!"; }
					$_SESSION['Media']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			case 9: //Delete Category Form
				$id = $_REQUEST['i'];
				$cat = $_SESSION['Media']->getCategories()->getFirstNode();
				while($cat != null){ $ca = $cat->readNode()->toArray(); if($ca['ID'] == $id){ break; } $cat = $cat->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <div class=\"text-center\"><h5>Are you sure you want to Delete this Category:</h5><p>".$ca['Definition']."<p></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"10\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ca['ID']."\">
					  <label></label>
					  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
				  </form>
				";
				$data = array("Delete Category",$html);
				echo json_encode($data);
			break;
			case 10: // Delete Category (Form BRI: 9)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$id = $_REQUEST['ID'];
				$sql1 = "DELETE FROM `Keys` WHERE ID = ".$id; $sql2 = "DELETE FROM Relations WHERE KID = ".$id.";";
				if(mysqli_query($pdo,$sql1) && mysqli_query($pdo,$sql2)){
					$data[] = 1;
					$data[] = "Category Deleted!";
					$_SESSION['Media']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			case 11: // Create Gallery Form
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"13\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Gallery Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
				  </form>
				";
				$data = array("Create New Category",$html);
				echo json_encode($data);
			break;
			case 12: // Edit Gallery Form
				$id = $_REQUEST['i'];
				$gal = $_SESSION['Media']->getGalleries()->getFirstNode();
				while($gal != null){ $ga = $gal->readNode()->toArray(); if($ga['ID'] == $id){ break; } $gal = $gal->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"13\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ga['ID']."\">
					  <div class=\"form-group\">
						<label for=\"categoryTitle\">Gallery Title</label>
						<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$ga['Definition']."\">
					  </div>
					  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
				  </form>
				";
				$data = array("Update Gallery",$html);
				echo json_encode($data);
			break;
			case 13; //Create and Update Gallery Records (Forms BRI: 11 & 12)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$time = time();
				if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ $id = $_REQUEST['ID']; $sql = "Update `Keys` SET Definition = \"".$_REQUEST['Title']."\", Updated = ".$time." WHERE ID = ".$_REQUEST['ID']; }
				else{ $id = 0; $sql = "INSERT INTO `Keys` (`Key`,`Code`,`Definition`,`Created`,`Updated`) VALUES(\"Gallery\",\"Media\",\"".$_REQUEST['Title']."\",".$time.",".$time.")"; }
				if(mysqli_query($pdo,$sql)){
					$data[] = 1;
					if($id == 0){ $data[] = "Gallery Created!"; }else{ $data[] = "Gallery Updated!"; }
					$_SESSION['Media']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			case 14: //Delete Category Form
				$id = $_REQUEST['i'];
				$gal = $_SESSION['Media']->getGalleries()->getFirstNode();
				while($gal != null){ $ga = $gal->readNode()->toArray(); if($ga['ID'] == $id){ break; } $gal = $gal->getNext(); }
				$html = "
				  <div class=\"alert hidden\"></div>
				  <div class=\"text-center\"><h5>Are you sure you want to Delete this Gallery:</h5><p>".$ga['Definition']."<p></div>
				  <form>
					  <input id=\"mri\" type=\"hidden\" value=\"15\">
					  <input id=\"ID\" type=\"hidden\" value=\"".$ga['ID']."\">
					  <label></label>
					  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
				  </form>
				";
				$data = array("Delete Gallery",$html);
				echo json_encode($data);
			break;
			case 15: // Delete Category (Form BRI: 14)
				// NEEDS UPDATING TO USE OO FUNCTIONALITY OF DBObj() CLASS
				$id = $_REQUEST['ID'];
				$sql1 = "DELETE FROM `Keys` WHERE ID = ".$id; $sql2 = "DELETE FROM Relations WHERE KID = ".$id.";";
				if(mysqli_query($pdo,$sql1) && mysqli_query($pdo,$sql2)){
					$data[] = 1;
					$data[] = "Gallery Deleted!";
					$_SESSION['Media']->load($pdo,false,true);
				}else{
					$data[] = 0;
					$data[] = "Error!";
				}
				echo json_encode($data);
			break;
			default:
				echo "BAD MRI"; 
			break;
		}
	}
}else{ echo "BAD REQUEST"; }
session_write_close();
?>	