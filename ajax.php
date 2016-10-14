<?php
	require_once("_php/lib.php");
	include("_php/DBObj/dbobj.php");
	session_start();
	
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init("localhost",$_SESSION['dbuser'],$_SESSION['dbPass']);
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
			if(isset($_SESSION['db'])){ session_write_close(); $_SESSION['db']->disconnect($_SESSION['dbName']); }
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
			if(isset($_SESSION['db'])){ session_write_close(); $_SESSION['db']->disconnect($_SESSION['dbName']); }
		}
	/*Blog Requests*/
		if(isset($_REQUEST['bri']) && $_REQUEST['bri'] != NULL && $_REQUEST['bri'] != ""){
			switch($_REQUEST['bri']){
				case 1: //Create Form
					$html = "
					  <form>
						  <div class=\"form-group\">
							<label for=\"postTitle\">Post Title</label>
							<input type=\"text\" class=\"form-control\" id=\"postTitle\" placeholder=\"Title\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaDescription\">Meta Description</label>
							<input type=\"text\" class=\"form-control\" id=\"metaDescription\" placeholder=\"Description\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaKeywords\">Meta Keywords</label>
							<input type=\"text\" class=\"form-control\" id=\"metaKeywords\" placeholder=\"Keywords\">
						  </div>
						  <div class=\"form-group\"><textarea class=\"form-control trumbowyg\"></textarea></div>
						  <div class=\"form-group\">
						  <div class=\"checkbox\">
							<label>
							  <input type=\"checkbox\"> Active
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
					$html = "
					  <form>
					  	  <input id=\"postID\" type=\"hidden\" value=\"".$a['ID']."\">
						  <div class=\"form-group\">
							<label for=\"postTitle\">Post Title</label>
							<input type=\"text\" class=\"form-control\" id=\"postTitle\" placeholder=\"Title\" value=\"".$a['Title']."\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaDescription\">Meta Description</label>
							<input type=\"text\" class=\"form-control\" id=\"metaDescription\" placeholder=\"Description\" value=\"".$a['Description']."\">
						  </div>
						  <div class=\"form-group\">
							<label for=\"metaKeywords\">Meta Keywords</label>
							<input type=\"text\" class=\"form-control\" id=\"metaKeywords\" placeholder=\"Keywords\" value=\"".$a['Keywords']."\">
						  </div>
						  <div class=\"form-group\"><textarea id=\"postHTML\" class=\"form-control trumbowyg\">".$a['HTML']."</textarea></div>
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
				case 3:
					$id = $_REQUEST['i'];
					$post = new Post($id);
					$post->dbRead($_SESSION['db']->con($_SESSION['dbName']));
					$a = $post->toArray();
					var_dump($a);
					$post->dbWrite($_SESSION['db']->con($_SESSION['dbName']));
				break;
				case 4:
				
				break;
				default:
					echo "BAD BRI"; 
				break;
			}
			if(isset($_SESSION['db'])){ session_write_close(); $_SESSION['db']->disconnect($_SESSION['dbName']); }
		}
	}else{ echo "BAD REQUEST"; }
	
	$_SESSION['db']->disconnect($_SESSION['dbName']);
	session_write_close();
?>	