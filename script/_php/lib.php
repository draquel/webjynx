<?php

	function ageReadable($sec){	if($sec > 60){ $age = $sec/60; if($age > 24){ $age = round($age/24,2) . " D"; }else{$age = round($age,2)." H";}}else{ $age = round($sec,2) . " M";}	return $age; }
	function isMobile() { return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]); }

?>