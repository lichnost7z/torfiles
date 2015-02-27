<?php
function autoLoad($class) {
	include_once "/class/".$class.".class.php";
}
spl_autoload_register('autoLoad',true);
define("TMP","./tmp");
?>