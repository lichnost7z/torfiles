<?php
//Вывод верхнего меню
class Header {

	static private $arrHeader = ['Главная' => '/','О нас' => 'about'];
	static private $str;
	const DIV = "<div class='btn btn-large btn-primary mybtn'>";
	const SEARCH = "<div class='btn btn-large btn-primary mybtn' id='search'>";
	const ENDDIV = "</div>";

	static public function head() {

		foreach(self::$arrHeader as $key => $val) {

			self::$str .= self::DIV."
				<a href=".$val.">".$key."</a>
			".self::ENDDIV; //Вывод верхнего меню записывается в одну строчку

		}
		self::$str .= self::SEARCH."<i class='icon-search'></i>".self::ENDDIV;

		return self::$str;

	}

}