<?php

class FileDescr
{

    private $getId; //Получаем id файла переданого через GET
    private $strHeader; //Шапка сайта
    private $strCenter; //Центер сайта
    const ROW = "<div class='row'>";
    const ENDDIV = "</div>";
    const SPAN3 = "<div class='span3 offset1 leftMenu'>";
    const SPAN9 = "<div class='span9'>";
    const SPAN10 = "<div class='span10 offset2'>";

    public function __construct($get)
    {
        $this->getId = intval(abs($get)); //Получаем идентификатор файла
        //Смотрим максимальный id файла
        $dbh = Db::getInst()->query("SELECT MAX(idfilesone) FROM files"); //Получаем максимальный идентификатор файла с БД
        $result = $dbh->fetch(PDO::FETCH_ASSOC);

        //Если переданый идентификатор менше 0 то присваеваим ему 1;
        $this->getId = $this->getId < 0 ? $this->getId = 1 : $this->getId;
        //Если переданый идентификатор больше максимального идентификатора в базе то присваиваем ему макс значения из бд
        $this->getId = $this->getId > $result['MAX(idfilesone)'] ? $this->grtId = $result['MAX(idfilesone)'] : $this->getId;
    }

    public function view()
    {

        $sql = "UPDATE static SET view = view+1 WHERE idfiles = " . $this->getId;
        Db::getInst()->exec($sql);

    }

    public function FileD()
    {
        //Получаем данные о файле
        $sql = "SELECT idfilesone, idpodcat, hash, url, imgurltor, imgtitle, header, `date`, `download`, `view`, `descr`, `urlimage`, `required`, `innerd` FROM
				(SELECT * FROM files WHERE idfilesone = :id) AS file
				LEFT JOIN static ON file.idfilesone = static.idfiles LEFT JOIN 
				descr ON file.idfilesone = descr.idfiles LEFT JOIN
				img ON file.idfilesone = img.idfiles";
        $sqlImg = "SELECT * FROM img WHERE idfiles=" . $this->getId; //Получаем миниатюры к файлу

        $sth = Db::getInst()->prepare($sql);
        $sth->bindValue(':id', $this->getId, PDO::PARAM_INT);
        $sth->execute();

        $query = Db::getInst()->query($sqlImg);
        $count = $query->rowCount();
        $resultImg = $query->fetchAll(PDO::FETCH_ASSOC);

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        //////////////////////////////////////////////////////////////////////////////////////

        //Выводи верх html страницы
        echo Template::Temp('head', $result['header'], substr($result['descr'], 0, 200));

        //создаем шапку сайта
        $this->strHeader .= self::ROW . self::SPAN10;
        $this->strHeader .= Header::head();
        $this->strHeader .= self::ENDDIV . self::ENDDIV;
        $this->strHeader .= self::ROW . self::SPAN3;
        $this->strHeader .= LeftMenu::LeftM();
        $this->strHeader .= self::ENDDIV . "<button class='btn btn-large btn-info' id='up'>Вверх</button>";
        $this->strHeader .= self::SPAN9;
        //Выводим Шапку
        echo $this->strHeader;

        //Вывод остальных данных файла
        $this->strCenter .= "<img class='img-rounded im' src=" . $result['imgtitle'] . ">\r\n";
        $this->strCenter .= "<div class='desc well'><div class='alert alert-block'>" . nl2br($result['required']) . "</div>";
        $this->strCenter .= "<dl><dd>" . nl2br($result['descr']) . "</dd></dl><br />";
        $this->strCenter .= "<dl><dd>" . nl2br($result['innerd']) . "</dd></dl></div>";


        //Вывод миниатюр если есть
        if ($count) {
            $this->strCenter .= "<div class='row-fluid'>
				<ul class='thumbnails'>";
            foreach ($resultImg as $key) {

                $this->strCenter .= "
						<li class='span3'>
							<a class='thumbnail' href='#'>
								<br>
								<img class='img-rounded' alt='' src=" . $key['urlimage'] . ">
								<br>
							</a>
						</li>";

            }

            $this->strCenter .= "</ul><br />";

        }
        ///////////////////////////

        $this->strCenter .= "<a class='btn btn-large btn-success' href=download.php?download=" . $result['hash'] . ">Скачать .torrent файл</a><br /><br />";
        echo $this->strCenter;

    }

} 