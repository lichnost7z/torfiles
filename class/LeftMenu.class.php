<?php
// Класс для вивода левого меню
class LeftMenu {

	private static $str; //Переменная в которой будет хранится всё меню в виде одной строки
	const DIVGROUP = "<div class='btn-group'>\r\n";
	const DIVEND = "</div>";
	const UL = "<ul class='dropdown-menu'>\r\n";
	const ULEND = "</ul>";

	public static function LeftM() { //Собственно сама функцыя

		$query = Db::getInst()->query('SELECT * FROM cat'); //ищем категории
		

		while ($result = $query->fetch(PDO::FETCH_ASSOC)) { 

			self::$str .= self::DIVGROUP."<button class='btn btn-warning'>
					".$result['namecat']."
			</button>
					<button class='btn btn-warning dropdown-toggle' data-toggle='dropdown'>
					<span class='caret'></span>
			</button>
			<p></p>
					".self::UL; //Вывод категорий
			
			$querypodcat = Db::getInst()->query('SELECT * FROM podcat WHERE idcat = '.$result['idcat'].''); //ищем подкатегории по идентификаторам категорий


			while ($resultpodcat = $querypodcat->fetch(PDO::FETCH_ASSOC)) { 

				self::$str .= "<li>
					<a href=".$resultpodcat['idpodcat'].">".$resultpodcat['namepodcat']."</a>
					</li>
					"; //Вывод подкатегорий

			}
				self::$str .= self::ULEND.self::DIVEND."<br />
				"; //Заканчиваем вывод меню
		}
		return self::$str;
	}


}