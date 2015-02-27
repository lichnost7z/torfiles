<?php
function autoLoad($class) {
	include_once "../class/".$class.".class.php";
}
spl_autoload_register('autoLoad',true);

$center = new Center($_GET['page'],$_GET['cat'],$_GET['count'],$_GET['sort']);
echo $center->CenterMenu();
?>