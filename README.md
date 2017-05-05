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

#### PHP 7
* php-xml 
* php-gd 
* php-curl 

## Documentation

### About
#### Purpose
  To create a foundation for building custom web solutions.
  
#### Aproach
  Lightweight PHP Driven LAMP Application Framework
  
### Basic Usage
```php
	$obj = new DBObj($id,"Posts");
	$obj->dbRead($mysqli_db_connection);
	$a = $obj->toArray();
	
	echo $a['ID'].", ".date("Y-m-d h:i:s",$a['Created']).", ".date("Y-m-d h:i:s",$a['Updated']);
	$a['Updated'] = time();
	
	$obj->initMysql($a);
	$obj->dbWrite($mysqli_db_connection);
```