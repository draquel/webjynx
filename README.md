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
* jQuery - http://jquery.com/
* bootstrap - http://getbootstrap.com/
* Google Analytics - https://www.google.com/analytics/
* Trumbowyg - https://github.com/Alex-D/Trumbowyg

#### PHP 7
* php-xml (dom extension)
* php-gd (dom extension)

## Documentation

### About
#### Purpose
  To create a foundation for building custom web solutions.
  
#### Aproach
  Lightweight PHP Driven LAMP Application
  
### Basic Usage
```php
	$obj = new DBObj($id,"Posts");
	$obj->readNode($mysqli_db_connection);
	$a = $obj->toArray();
	
	echo $a['Title'], date("Y-m-d h:i:s",$a['Created']);
```