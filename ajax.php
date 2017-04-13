<?php
	require_once("_php/DBObj2/dbobj.php");
	session_start();
	error_reporting(E_ALL);
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
	
	if(isset($_REQUEST['ari']) || isset($_REQUEST['uri']) || isset($_REQUEST['bri'])){
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
			if(isset($_SESSION['db'])){ $_SESSION['db']->disconnect($_SESSION['dbName']); }
		}
	/*User Requests*/
		if(isset($_REQUEST['uri']) && $_REQUEST['uri'] != NULL && $_REQUEST['uri'] != ""){
			switch($_REQUEST['uri']){
				case 1: //Login
					$user = $_REQUEST['user']; $pass = $_REQUEST['pass']; $auth = false;
					$_SESSION['User'] = new User(NULL);
					$auth = $_SESSION['User']->login($user,$pass,$_SESSION['db']->con($_SESSION['dbName']));
					if($auth){ echo 1; }else{ $_SESSION['User'] = NULL; echo 2; }
				break;
				case 2: //Logout
					$_SESSION['User'] = NULL;
				break;
				default:
					echo "BAD URI"; 
				break;
			}
			if(isset($_SESSION['db'])){ $_SESSION['db']->disconnect($_SESSION['dbName']); }
		}
	/*Blog Requests*/
		if(isset($_REQUEST['bri']) && $_REQUEST['bri'] != NULL && $_REQUEST['bri'] != ""){
			switch($_REQUEST['bri']){
				case 1: //Create Post Form
					$blog = $_SESSION['Blog']->toArray();
					$cats = $blog['Categories'];
					$html = "
					  <div class=\"alert hidden\"></div>
					  <form enctype=\"multipart/form-data\">
					  	  <input id=\"bri\" type=\"hidden\" value=\"3\">
						  <div class=\"form-group\">
							<label for=\"postTitle\">Post Title</label>
							<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaDescription\">Meta Description</label>
							<input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaKeywords\">Meta Keywords</label>
							<input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"coverImage\">Cover Image</label>
							<input type=\"file\" id=\"coverImage\" accept=\"image/jpeg,image/png,image/gif\">
						  </div>
						  <div class=\"form-group\">
							<label>Categories</label>
							<select multiple class=\"form-control\" id=\"Categories\">";
						foreach($cats as $val){ $html .= "<option value=\"".$val['KID']."\">".$val['Definition']."</option>"; }
						$html .= "
							</select>
						  </div>
						  <div class=\"form-group\"><textarea id=\"HTML\" class=\"form-control trumbowyg\"></textarea></div>
						  <div class=\"form-group\">
							  <div class=\"checkbox\">
								<label>
								  <input id=\"Active\" type=\"checkbox\" > Make Live
								</label>
							  </div>
						  </div>
						  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
					  </form>
					";
					$data = array("Create New Post",$html);
					echo json_encode($data);
				break;
				case 2: //Edit Post Form
					$id = $_REQUEST['i'];
					$post = new Post($id);
					$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
					$a = $post->toArray();
					$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
					$html = "
					  <div class=\"alert hidden\"></div>
					  <form enctype=\"multipart/form-data\">
					  	  <input id=\"bri\" type=\"hidden\" value=\"3\">
					  	  <input id=\"ID\" type=\"hidden\" value=\"".$a['ID']."\">
						  <div class=\"form-group\">
							<label for=\"postTitle\">Post Title</label>
							<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$a['Title']."\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaDescription\">Meta Description</label>
							<input type=\"text\" class=\"form-control\" id=\"Description\" placeholder=\"Description\" value=\"".$a['Description']."\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaKeywords\">Meta Keywords</label>
							<input type=\"text\" class=\"form-control\" id=\"Keywords\" placeholder=\"Keywords\" value=\"". implode(",",$a['Keywords']) ."\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"coverImage\">Cover Image</label>
							<input type=\"file\" id=\"coverImage\">
						  </div>
						  <div class=\"form-group\">
							<label>Categories</label>
							<select multiple class=\"form-control\" id=\"Categories\">";
						while($bcat != NULL){
							$sel = false;
							$bca = $bcat->readNode()->toArray();
							if(count($a['Rels']['Category'])){ for($i = 0; $i < count($a['Rels']['Category']); $i++){ if($bca['KID'] == $a['Rels']['Category'][$i]['KID']){ $sel = true; break; } } }
							$html .= "<option value=\"".$bca['KID']."\"";
							if($sel){ $html .= " selected"; }
							$html .= ">".$bca['Definition']."</option>";
							$bcat = $bcat->getNext();
						}
						$html .= "
							</select>
						  </div>
						  <div class=\"form-group\"><textarea id=\"HTML\" class=\"form-control trumbowyg\">".$a['HTML']."</textarea></div>
						  <div class=\"form-group\">
							  <div class=\"checkbox\">
								<label><input id=\"Active\" type=\"checkbox\"";
								if($a['Active'] == 1){ $html .= " checked"; }
								$html .= "> Make Live</label>
							  </div>
						  </div>
						  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
					  </form>
					";
					$data = array("Edit Post",$html);
					echo json_encode($data);
				break;
				case 3: //Create and Update Post Records (Forms BRI: 1 & 2)
					$u = $_SESSION['User']->toArray();
					$b = $_SESSION['Blog']->toArray();
					if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ //Update
						$id = $_REQUEST['ID'];
						$post = new Post($id);
						$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
						$in = array("Title"=>$_REQUEST['Title'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML']);
						if(isset($_FILES["coverImage"])){ $img_path = "img/blog/".$id.".".end((explode(".", $_FILES["coverImage"]["name"]))); $in["CoverImage"] = "/".$img_path; }
						$post->initMysql($in);
					}else{ //Create
						$id = 0;
						$post = new Post(0);
						$in = array("Title"=>$_REQUEST['Title'],"Author"=>$u['ID'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML']);
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
								if($id != 0 && $post->getCategories()->size() > 0){ //Check For Duplicate Relations
									$dup = false;
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
							if($obs){ $pcat->readNode()->dbDelete($_SESSION['db']->con($_SESSION['dbName']));}
							$pcat = $pcat->getNext();
						}
					}
					//Write to Database {and Update Session (obsolete)}
					$data = array();
					if($post->dbWrite($_SESSION['db']->con($_SESSION['dbName']))){ 
						$data[] = 1;
						if($id == 0){ 
							$data[] = "Post Created!"; 
							$_SESSION['Blog']->getPosts()->insertFirst($post);
							$a = $post->toArray();
							$id = $a['ID'];
							if(isset($_FILES["coverImage"])){ $img_path = "img/blog/".$id.".".end((explode(".", $_FILES["coverImage"]["name"]))); $in["CoverImage"] = "/".$img_path; }
							$post->initMysql($in);
							$post->dbWrite($_SESSION['db']->con($_SESSION['dbName']));
						}else{ 
							$data[] = "Post Updated!";
							$bp = $_SESSION['Blog']->getPosts()->getFirstNode();
							$i = 0;
							while($bp != NULL){
								$bpa = $bp->readNode()->toArray();
								if($bpa['ID' ]== $id){ $_SESSION['Blog']->getPosts()->getNodeAt($i)->data = $post; break; }
								$i++;
								$bp = $bp->getNext();
							}
						}
						if(isset($_FILES["coverImage"]) && getimagesize($_FILES["coverImage"]["tmp_name"])){ move_uploaded_file($_FILES["coverImage"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'].$img_path); }
					}
					else{ $data[] = 0; $data[] = "Error!"; }
					echo json_encode($data);
				break;
				case 4: //Delete Post Form
					$id = $_REQUEST['i'];
					$post = new Post($id);
					$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
					$a = $post->toArray();
					$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
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
					$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
					
					if($post->dbDelete($_SESSION['db']->con($_SESSION['dbName']))){ 
						$data[] = 1;
						$data[] = "Post Deleted!";
						$bp = $_SESSION['Blog']->getPosts()->getFirstNode();
						$i = 0;
						while($bp != NULL){
							$bpa = $bp->readNode()->toArray();
							if($bpa['ID']== $id){ $_SESSION['Blog']->getPosts()->deleteNodeAt($i); break; }
							$i++;
							$bp = $bp->getNext();
						}
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
					$blog = $_SESSION['Blog']->toArray();
					$cats = $blog['Categories'];
					for($i = 0; $i < count($cats); $i++){ if($cats[$i]['ID'] == $id){ $c = $cats[$i]; break;} }
					$html = "
					  <div class=\"alert hidden\"></div>
					  <form>
					  	  <input id=\"bri\" type=\"hidden\" value=\"8\">
						  <input id=\"ID\" type=\"hidden\" value=\"".$c['ID']."\">
						  <div class=\"form-group\">
							<label for=\"categoryTitle\">Category Title</label>
							<input type=\"text\" class=\"form-control\" id=\"Title\" placeholder=\"Title\" value=\"".$c['Definition']."\">
						  </div>
						  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Update</button></div>
					  </form>
					";
					$data = array("Update Category",$html);
					echo json_encode($data);
				break;
				case 8; //Create and Update Category Records (Forms BRI: 6 & 7)
					$time = time();
					if(isset($_REQUEST['ID']) && $_REQUEST['ID'] != 0 && $_REQUEST['ID'] != NULL){ $id = $_REQUEST['ID']; $sql = "Update `Keys` SET Definition = \"".$_REQUEST['Title']."\", Updated = ".$time." WHERE ID = ".$_REQUEST['ID']; }
					else{ $id = 0; $sql = "INSERT INTO `Keys` (`Key`,`Code`,`Definition`,`Created`,`Updated`) VALUES(\"Category\",\"\",\"".$_REQUEST['Title']."\",".$time.",".$time.")"; }
					//error_log("SQL DBObj->Relation: ".$sql);
					if(mysqli_query($_SESSION['db']->con($_SESSION['dbName']),$sql)){
						$data[] = 1;
						if($id == 0){ $data[] = "Category Created!"; }else{ $data[] = "Category Updated!"; }
						$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']),false,true);
					}else{
						$data[] = 0;
						$data[] = "Error!";
					}
					echo json_encode($data);
				break;
				case 9: //Delete Category Form
					$id = $_REQUEST['i'];
					$blog = $_SESSION['Blog']->toArray();
					$cats = $blog['Categories'];
					for($i = 0; $i < count($cats); $i++){ if($cats[$i]['ID'] == $id){ $c = $cats[$i]; break;} }
					$html = "
					  <div class=\"alert hidden\"></div>
					  <div class=\"text-center\"><h5>Are you sure you want to Delete this Category:</h5><p>".$c['Definition']."<p></div>
					  <form>
					  	  <input id=\"bri\" type=\"hidden\" value=\"10\">
					  	  <input id=\"ID\" type=\"hidden\" value=\"".$c['ID']."\">
						  <label></label>
						  <div class=\"form-group text-center\"><button type=\"submit\" class=\"btn btn-danger\">Yes, Delete</button><button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" aria-label=\"Cancel\">No, Cancel</div>
					  </form>
					";
					$data = array("Delete Category",$html);
					echo json_encode($data);
				break;
				case 10: // Delete Category (Form BRI: 9)
					$id = $_REQUEST['ID'];
					$sql1 = "DELETE FROM `Keys` WHERE ID = ".$id; $sql2 = "DELETE FROM Relations WHERE KID = ".$id.";";
					//error_log("SQL DBObj->Relation1: ".$sql1);
					//error_log("SQL DBObj->Relation2: ".$sql2);
					if(mysqli_query($_SESSION['db']->con($_SESSION['dbName']),$sql1) && mysqli_query($_SESSION['db']->con($_SESSION['dbName']),$sql2)){
						$data[] = 1;
						$data[] = "Category Deleted!";
						$_SESSION['Blog']->load($_SESSION['db']->con($_SESSION['dbName']),false,true);
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
			if(isset($_SESSION['db'])){ $_SESSION['db']->disconnect($_SESSION['dbName']); }
		}
	}else{ echo "BAD REQUEST"; }
	
	/*$_SESSION['db']->disconnect($_SESSION['dbName']);*/
	/*session_write_close();*/
?>	