<?php
class Download {

	function file_force_download($file) {
		$file = "./down/".$file;
		var_dump($file);
	  	if (file_exists($file)) {
	    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
		    // если этого не сделать файл будет читаться в память полностью!
		    if (ob_get_level()) {
		      ob_end_clean();
		    }
		    // заставляем браузер показать окно сохранения файла
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename=' . basename($file));
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    // читаем файл и отправляем его пользователю
		    readfile($file);
		    exit;
	  	}{
	  		header("Location: /download");
	  	}
	}

	function Down() {
		if($_GET['download']) {
			
			$sql = "SELECT idfilesone, hash, url FROM files WHERE hash = ".Db::getInst()->quote($_GET['download']);

			$query = Db::getInst()->query($sql);
			$count = $query->rowCount();
			$result = $query->fetch(PDO::FETCH_ASSOC);
			//Проверяем есть ли такой файл
			if($count) {
				//Обновляем статистику файла
				$sql = "UPDATE static SET download=download+1 WHERE idfiles = ".$result['idfilesone'];

				Db::getInst()->exec($sql);
				//Отсылаем файл пользователю
				$this->file_force_download($result['url']);

			}

		}
		header("Location: /download");
	}
}