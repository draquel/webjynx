# Webjynx AJAX Toolkit

## Requirements

### Apache2 Mods
* Rewrite
* Deflate
* Headers
* Expires

### Third Party Libs

#### JavaScript
* headJS - http://headjs.com/
* MomentJS - https://momentjs.com/
* jQuery - http://jquery.com/
* bootstrap - http://getbootstrap.com/
* Google Analytics - https://www.google.com/analytics/
* Trumbowyg - https://github.com/Alex-D/Trumbowyg
* Bootstrap-datetimepicker - https://github.com/Eonasdan/bootstrap-datetimepicker
* Lightbox 2 - http://lokeshdhakar.com/projects/lightbox2/

#### PHP 7
* php-xml 
* php-gd 
* php-curl
* draquel/DBObj - https://github.com/draquel/DBObj

## Documentation

### About
#### Purpose
  To create a foundation for building custom web solutions.
  
#### Aproach
  Lightweight PHP Driven LAMP Application Framework
  
### Basic Usage
```php
	//Create New DBObj
	$obj = new DBObj(0,"Posts");
	$obj->initMysql(array('Created'=>time(),'Updated'=>time()));
	$obj->dbWrite($mysqli_db_connection);

	//Edit existing DBObj
	$obj = new DBObj($id,"Posts");
	$obj->dbRead($mysqli_db_connection);
	$a = $obj->toArray();
	
	echo $a['ID'].", ".date("Y-m-d h:i:s",$a['Created']).", ".date("Y-m-d h:i:s",$a['Updated']);
	$a['Updated'] = time();
	
	$obj->initMysql($a);
	$obj->dbWrite($mysqli_db_connection);
```
