<?php
	session_start();
	require_once("script/_php/lib.php");
	if(isset($_REQUEST['ari']) || $_REQUEST['ari'] != NULL || $_REQUEST['ari'] != ""){
		switch($_REQUEST['ari']){
			case 0: //Image Preloader
				$root = __DIR__;
				$dir = "/img";
				$files = glob($root.$dir."/*.{jpeg,jpg,gif,png,svg}", GLOB_BRACE);

				$img = array();
				$s = "";
				for($i = 0;$i < count($files); $i++){
					$img[] = str_replace($root,"",$files[$i]);
				}
				$data = array();
				$data[0] = $_SESSION['Page'];
				$data[1] = $img;
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			case 1: //News Letter Signup Form Submit
				$name = $_REQUEST['n'];
				$company = $_REQUEST['c'];
				$email = $_REQUEST['e'];
				$reason = $_REQUEST['r'];
				
				//$to = 'draquel@webjynx.com';
				$to = 'info@impactseat.com';
				$subject = 'Newsletter Sign Up';
				$message = '<html>
					<head>
					<title>Newsletter Form Submission</title>
					<style>th{text-align:right;}</style>
					</head>
					<body>
						<h2>Newsletter Form Submission</h2>
						<table>
						<tr><th>Name:</th><td>'.$name.'</td></tr>
						<tr><th>Company:</th><td>'.$company.'</td></tr>
						<tr><th>Email:</th><td>'.$email.'</td></tr>
						<tr><th>Reason:</th><td>'.$reason.'</td></tr>
						<tr><th>Submitted on:</th><td>'.date("Y-m-d",time()).'</td></tr>
						</table>
					</body>
				</html>';
				$headers = 'From: doNotReply@impactseat.com' . "\r\n" .	'Reply-To: doNotReply@impactseat.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n". "MIME-Version: 1.0";
				mail($to, $subject, $message, $headers);
			break;
			case 2: //Work With Us Form Submit
				$name = $_REQUEST['n'];
				$company = $_REQUEST['c'];
				$email = $_REQUEST['e'];
				$msg = $_REQUEST['m'];
				
				//$to = 'draquel@webjynx.com';
				$to = 'teresa@impactseat.com, barbara@impactseat.com';
				$subject = 'Work With Us Form';
				$message = '<html>
					<head>
					<title>Work With Us Form</title>
					<style>th{text-align:right;}</style>
					</head>
					<body>
						<h2>Newsletter Form Submission</h2>
						<table>
						<tr><th>Name:</th><td>'.$name.'</td></tr>
						<tr><th>Company:</th><td>'.$company.'</td></tr>
						<tr><th>Email:</th><td>'.$email.'</td></tr>
						<tr><th>Message:</th><td>'.$msg.'</td></tr>
						<tr><th>Submitted on:</th><td>'.date("Y-m-d",time()).'</td></tr>
						</table>
					</body>
				</html>';
				$headers = 'From: doNotReply@impactseat.com' . "\r\n" .	'Reply-To: doNotReply@impactseat.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n". "MIME-Version: 1.0";
				mail($to, $subject, $message, $headers);
			break;
			case 3:
				if(isset($_SESSION['article']) && $_SESSION['article'] != "" && $_SESSION['article'] != "/" && $_SESSION['article'] != NULL){ 
					echo "http://wordpress.impactseat.com/index.php".$_SESSION['article']; 
				}else{ echo "http://wordpress.impactseat.com"; } 
				$_SESSION['article'] = NULL;
			break;
			case 4:
				$page = $_REQUEST['p'];
				$exist_i = false;
				$exist_f = false;
				for($i = 0; $i < count($_SESSION['Pages']); $i++){ if($_SESSION['Pages'][$i]['path-file'] == strtolower($page)){ $exist_i = true; $_SESSION['Page'] = $_SESSION['Pages'][$i]; break;} }
				$exists_f = file_exists(ltrim($_SESSION['Page']['path-file'],"/"));
				if($exist_i && $exists_f){
					ob_start();
					include ltrim($_SESSION['Page']['path-file'],"/");
					$html = ob_get_clean();
				}else{
					$html = "<h1>HTTP 404 - Page Not Found</h1>\n";
					if(!$exist_i){ $html .= "<p>The requested page ".$_REQUEST['p']." was not found in the index.</p>\n"; }
					if(!$exist_f){ $html .= "<p>The requested URL ".ltrim($_SESSION['Page']['path-file'],"/")." was not found on this server.</p>\n"; }
					$html .= "<hr>
					<address>".$_SERVER['SERVER_SOFTWARE']." Server at ".$_SERVER['SERVER_NAME']." Port 80</address>";
					$_SESSION['Page'] = array("id"=>-1,"meta-title"=>"HTTP 404 - Page Not Found","meta-description"=>"HTTP 404 - Page Not Found","path-ui"=>"/404","path-file"=>"");
				}
				$data = array($html,$_SESSION['Page']);
				header("Content-Type: application/json");
				echo json_encode($data);
			break;
			default:
				echo "BAD REQUEST";
			break;
		}
	}else{ echo "BAD REQUEST"; }
?>