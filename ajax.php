<?php
	require_once("_php/lib.php");
	require_once("_php/DBObj/dbobj.php");
	session_start();
	
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init($_SESSION['dbHost'],$_SESSION['dbuser'],$_SESSION['dbPass']);
	$_SESSION['db']->connect($_SESSION['dbName']);
	
	if(isset($_REQUEST['ari']) || isset($_REQUEST['uri']) || isset($_REQUEST['bri'])){
	/*Site Ajax Requests*/
		if(isset($_REQUEST['ari']) && $_REQUEST['ari'] != NULL && $_REQUEST['ari'] != ""){
			switch($_REQUEST['ari']){
				case 1: 
					
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
				case 1: //Create Form
					$blog = $_SESSION['Blog']->toArray();
					$cats = $blog['Categories'];
					$html = "
					  <div class=\"alert hidden\"></div>
					  <form>
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
							  <input id=\"Active\" type=\"checkbox\" > Active
							</label>
						  </div>
						  </div>
						  <div class=\"form-group\"><button type=\"submit\" class=\"btn btn-default\">Create</button></div>
					  </form>
					";
					$data = array("Create New Post",$html);
					echo json_encode($data);
				break;
				case 2: //Edit Form
					$id = $_REQUEST['i'];
					$post = new Post($id);
					$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
					$a = $post->toArray();
					$bcat = $_SESSION['Blog']->getCategories()->getFirstNode();
					$html = "
					  <div class=\"alert hidden\"></div>
					  <form>
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
								$html .= "> Active</label>
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
						$post->initMysql(array("Title"=>$_REQUEST['Title'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML']));
					}else{ //Create
						$id = 0;
						$post = new Post(0); 
						$post->initMysql(array("Title"=>$_REQUEST['Title'],"Author"=>$u['ID'],"Description"=>$_REQUEST['Description'],"Keywords"=>$_REQUEST['Keywords'],"Active"=>$_REQUEST['Active'],"HTML"=>$_REQUEST['HTML']));
					}
					
					if($id == 0){ //Generate Parent Relationship
						$r = new Relation();
						$r->initMysql(array("ID"=>0,"Created"=>0,"Updated"=>0,"RID"=>$b['ID'],"KID"=>7));
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
									while($pcat!= NULL){
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
					if($id != 0){ //Delete obsolete relations
						$pcat = $post->getCategories()->getFirstNode();
						while($pcat != NULL){
							$pca = $pcat->readNode()->toArray();
							$obs = true;
							foreach($icats as $c){ if($c == $pca['KID']){ $obs = false; break; } }
							if($obs){ $pcat->readNode()->dbDelete($_SESSION['db']->con($_SESSION['dbName']));}
							$pcat = $pcat->getNext();
						}
					}
					
					// Write to Database and Update Session
					$data = array();
					if($post->dbWrite($_SESSION['db']->con($_SESSION['dbName']))){ 
						$data[] = 1;
						if($id == 0){ 
							$data[] = "Post Created!"; 
							$_SESSION['Blog']->getPosts()->insertFirst($post); 
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
					}
					else{ $data[] = 0; $data[] = "Error!"; }
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
	session_write_close();
?>	