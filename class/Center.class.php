<?php
class Center {
	private $nowPage; //Сюда получаем текущюю страницу
	private $nowCat; //Сюда получаем текущий каталог
	private $getCountFile; //Сюда получаем количество файлов выводимых за раз на страницу
	private $sort; //Сюда получаем вид сортировки
	private $data; //Дата по которую файл считается новым
	private $sql; //SQL запрос к базе
	private $str; //Строчка в которую будет записыватся все данные
	private $step = 1; //Скольк должно быть ечеек в одной строку таблицы
	private $num = 10; //начальное количество файлов на страницу 
	private $start; //начало выборки с базы
	private $total; //Общее количество страниц
	private $ctPg; //Текущий каталог для добавления его в сылку на постраничну навигацыю
	private $header; //Здесь хранится вывод верхнего меню (сортировки)
	const TABLE = "<table cellpadding='25' align='center' cols='3' frame='void' width='95%'>
	<tr><td heigth='200'>";
	const CONT = "<div class='content'>";

	public function __construct($np /*текущая страница*/,$nc/*текущий каталог*/,$gcf/*колл файлов*/,$s/*сортировка*/) {

		$np = preg_replace("/[^0-9]/", "", $np);
		$nc = preg_replace("/[^0-9]/", "", $nc);
		$gcf = preg_replace("/[^0-9]/", "", $gcf);
		$s = preg_replace("/[^(data|view|download)]/", "", $s);

		$this->data = time()-3600*48; //Файл считается новым двое суток

		$this->nowPage = ceil($np) ? $np : 1; //Первая страница по умолчанию

		if($nc) {
			$this->nowCat = "SELECT * FROM `files` WHERE `idpodcat` = ".ceil($nc); //Если каталог передан
			$this->ctPg = ceil($nc);
		} else {
			$this->nowCat = "SELECT * FROM `files`"; //Если каталог не передан
			$this->ctPg = 0;
		}
		//Верхний блок постраничной навигации
		//получаем количество записей в базе
		
		$query = Db::getInst()->query($this->nowCat);

		$count = $query->rowCount();
		$this->total = $gcf ? ceil($count / ceil($gcf)) : ceil($count / $this->num);

		if ($this->nowPage < 0) $this->nowPage = 1;
		if ($this->nowPage > $this->total) $this->nowPage = $this->total;

		$this->start = $gcf ?  $this->nowPage * $gcf - $gcf : $this->nowPage * $this->num - $this->num;
		/////////////////////////////////////////////////////////


		$this->getCountFile = $gcf ? "LIMIT ".$this->start.", ".ceil($gcf) : "LIMIT ".$this->start.", ".$this->num;

		//Получаем вид сортировки
		switch ($s) {
			case 'data':
			$this->sort = "ORDER BY `date` DESC";
			break;

			case 'view':
			$this->sort = "ORDER BY `view` DESC";
			break;

			case 'download':
			$this->sort = "ORDER BY `download` DESC";
			break;

			default:
			$this->sort = "ORDER BY `date` DESC";
		}

			/////////////////////////////////////////////////////////////////////////
			////////////Вывод верхнего меню (сортировки)////////////////////////////////
			//////////////////////////////////////////////////////////////////////////

			$arrayCount = [10, 20, 30, 40, 50, 100];
			$arraySort = ['date' => 'Дате', 'view' => 'Просмотрах', 'download' => 'Загрузках'];
			$this->header .= "<form class='form-inline'>
			<span class='label'>Колл. файлов:</span><select class='span1 count'>\r\n";
			if ($gcf) {
				
				foreach ($arrayCount as $key) {
					
					if($gcf == $key) {
						$this->header .= "\t\t<option selected value=".$nc."|".$key.">".$key."</option>";
						continue;
					}

					$this->header .= "\t\t<option value=".$nc."|".$key.">".$key."</option>";
				}
			} else {
				
				foreach ($arrayCount as $key) {
					$this->header .= "\t\t<option value=".$nc."|".$key.">".$key."</option>\r\n";
				}
			}

            //dfkljs

			$this->header .= "</select>
			<span class='label'>Сортировать по:</span><select class='span2 sort'>";

			if ($s) {

				foreach ($arraySort as $key => $val) {

					if ($s == $key) {
						$this->header .= "\t\t<option selected value=".$key.">".$val."</option>";
						continue;
					}

					$this->header .= "\t\t<option value=".$key.">".$val."</option>";

				}

			} else {

				foreach ($arraySort as $key => $val) {
					$this->header .= "\t\t<option value=".$key.">".$val."</option>\r\n";
				}

			}
			$this->header .= "</select>\t
			<button class='btn btn-mini btn-primary' type='button'>Сортировать</button>\r\n</form>\r\n";



			////////////////////////////////////////////////////////////////////////
			//////////////////////END///////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////

		

	}	


		public function CenterMenu() {

			$this->sql = "SELECT `idfilesone`, `idpodcat`, `hash`, `url`, `imgurltor`, `imgtitle`, `header`, `date`, `download`, `view`, `descr` FROM 
			( ".$this->nowCat." ) AS `new` LEFT JOIN `static` ON `new`.`idfilesone` = `static`.`idfiles` LEFT JOIN
							`descr` ON `new`.`idfilesone` = `descr`.`idfiles` ".$this->sort." ".$this->getCountFile;
			try {
				$sth = Db::getInst()->prepare($this->sql);
				$sth->execute();
			} catch (PDOException $e) {
				echo $e->getMessage();
			}
			
			$this->str .= self::TABLE;
			while ($result = $sth->fetch(PDO::FETCH_ASSOC)) {

				$sqlStat = "SELECT * FROM static WHERE idfiles = :stat";
				$sthStat = Db::getInst()->prepare($sqlStat);
				$sthStat->bindValue(':stat', $result['idfilesone'], PDO::PARAM_STR);
				$sthStat->execute();
				$resultStat = $sthStat->fetch(PDO::FETCH_ASSOC);

				if (!$this->step <= 0) {
					$this->str .= "<a href=file.php?id=".$result['idfilesone'].">
						<div class='tb'><img class='img-rounded' src=".$result['imgtitle']." width='150' heigth='250'>
						<span><i class='icon-download-alt icon-white'></i> ".$resultStat['download']." <i class='icon-eye-open icon-white'></i> ".$resultStat['view']."</span></div>
					</a></td><td heigth='350'>";
				$this->step--;
				} else {
						$this->str .= "<a href=file.php?id=".$result['idfilesone'].">
							<div class='tb'><img class='img-rounded' src=".$result['imgtitle']." width='150' heigth='250'>
							<span><i class='icon-download-alt icon-white'></i> ".$resultStat['download']." <i class='icon-eye-open icon-white'></i> ".$resultStat['view']."</span></div>
						</a></td></tr><tr><td heigth='200'>";
					$this->step = 1;
				}

			}

			$this->str .= "</table>\r\n";

			echo self::CONT.$this->header;
			echo $this->str;


			/////////////////////////////////////////////////////////////////////////
			////////Вывод самой навигации///////////////////////////////////////////
			///////////////////////////////////////////////////////////////////////


			// Проверяем нужны ли стрелки назад
			if ($this->nowPage != 1) $pervpage = '<li><a href='.$this->ctPg.'/1>Первая</a></li><li><a href='.$this->ctPg.'/'. ($this->nowPage - 1) .'>Предыдущая</a></li>';
			// Проверяем нужны ли стрелки вперед
			if ($this->nowPage != $this->total) $nextpage = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 1) .'>Следующая</a></li><li><a href='.$this->ctPg.'/'.$this->total. '>Последняя</a></li>';

			// Находим две ближайшие станицы с обоих краев, если они есть
			if($this->nowPage - 5 > 0) $page5left = '<li><a href='.$this->ctPg.'/'. ($this->nowPage - 5) .'>'. ($this->nowPage - 5) .'</a></li>';
			if($this->nowPage - 4 > 0) $page4left = '<li><a href='.$this->ctPg.'/'. ($this->nowPage - 4) .'>'. ($this->nowPage - 4) .'</a></li>';
			if($this->nowPage - 3 > 0) $page3left = '<li><a href='.$this->ctPg.'/'. ($this->nowPage - 3) .'>'. ($this->nowPage - 3) .'</a></li>';
			if($this->nowPage - 2 > 0) $page2left = '<li><a href='.$this->ctPg.'/'. ($this->nowPage - 2) .'>'. ($this->nowPage - 2) .'</a></li>';
			if($this->nowPage - 1 > 0) $page1left = '<li><a href='.$this->ctPg.'/'. ($this->nowPage - 1) .'>'. ($this->nowPage - 1) .'</a></li>';

			if($this->nowPage + 5 <= $this->total) $page5right = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 5) .'>'. ($this->nowPage + 5) .'</a></li>';
			if($this->nowPage + 4 <= $this->total) $page4right = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 4) .'>'. ($this->nowPage + 4) .'</a></li>';
			if($this->nowPage + 3 <= $this->total) $page3right = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 3) .'>'. ($this->nowPage + 3) .'</a></li>';
			if($this->nowPage + 2 <= $this->total) $page2right = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 2) .'>'. ($this->nowPage + 2) .'</a></li>';
			if($this->nowPage + 1 <= $this->total) $page1right = '<li><a href='.$this->ctPg.'/'. ($this->nowPage + 1) .'>'. ($this->nowPage + 1) .'</a></li>';

			// Вывод меню если страниц больше одной

			if ($this->total > 1)
			{
			echo "<div class='pagination pagination-left'>
			<ul>";
			echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left."<li class=active><span>".$this->nowPage."</span></li>".$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
			echo "</ul></div>";
			}

			////////////////////////////////////////////////////////////////////////
			//////////////////////END///////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////


		}	

 	
}