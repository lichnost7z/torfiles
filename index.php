<?php
include_once "/sys/config.php";
	echo Template::Temp('head','Скачать пограммы через торрент','');
	echo "<div class='row'>
	<div class='span10 offset2'>";
	echo Header::head();
	echo "</div>
	</div>";
	echo "<div class='row'>";
	echo "<div class='span3 offset1 leftMenu'>
	";
	echo LeftMenu::LeftM();
	echo "</div>";
	echo "<div class='span7'>
	<div class='content'>";
	$center = new Center(1,0,0,0);
	$center->CenterMenu();
	echo "</div></div>
		</div>";
	echo "<button class='btn btn-large btn-info' id='up'>Вверх</button>";
	echo "<div class='span2'>";
	echo RightMenu::RightM();
	echo "</div>";
	echo Template::Temp('footer','','');
?>