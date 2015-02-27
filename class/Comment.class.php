<?php
class Comment {
	private $getId;
	private $str;

	public function __construct($id) {
		if($id) {
			$this->getId = intval(abs($id));
			$query = Db::getInst()->query("SELECT MAX(idfilesone) FROM files");
			$result = $query->fetch(PDO::FETCH_ASSOC);
			if($this->getId < 0) $this->getId = 1;
			if($this->getId > $result['MAX(idfilesone)']) $this->getId = $result['MAX(idfilesone)'];
		} else {
			exit;
		}
	}

	public function Comm() {

		if(@isset($_POST['submess'])) {

			$name = trim(htmlspecialchars($_POST['nameuser']));
			$message = trim(htmlspecialchars($_POST['message']));

			try {

				if($_POST['user_code'] != $_SESSION['code'])
					throw new Exception("Код с картинки введен не верно!");
				if(strlen($name) > 50) 
					throw new Exception("Имя не должно превышать 50 символов!");
				if(strlen($message) > 550) 
					throw new Exception("Сообщения не должно превышать 550 символов!");
				if(strlen($name) <= 0) 
					$name = 'Anonymous';
				if(strlen($message) <= 0) 
					throw new Exception("Сообщения не может быть пустым");

				
				$sqlinsert = "INSERT INTO comment (idfiles, `name`, message, `date`) VALUES (".$this->getId.",'".$name."','".$message."',".time().")";

				$query = Db::getInst()->query($sqlinsert);

				if($query) {
					@header("Location: /files.php?id=".$this->getId);
				} else {
					throw new Exception("Error");
				}

			} catch (Exception $e) {
				echo $e->getMessage();
				exit();
			}

		}


		$sql = "SELECT * FROM comment WHERE idfiles = ".$this->getId;
		$query = Db::getInst()->query($sql);
		$count = $query->rowCount();
		$this->str .= "<div class='comment'><form class='form-horizontal' method='POST'>
							<legend>Комментaрии</legend>
							<div class='control-group'>
								<label class='control-label' for='inputNameUser'>Имя</label>
								<div class='controls'>
									<input type='text' name='nameuser' id='inputNameUser' placeholder='Anonymous'>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='inputComment'>Комментарий</label>
								<div class='controls'>
									<textarea rows='7' name='message'></textarea>
								</div>
							</div>
							<div class='control-group'>
								<label class='control-label' for='captcha'>Введите код</label>
								<div class='controls'>
									<img src='./sys/captcha.php' width='80' heigth='50' />
									<input type='text' name='user_code' />
								</div>
							</div>
							<div class='control-group'>
								<button type='submit' class='btn' name='submess'>Добавить Комментарий</button>
							</div>
						</form>";

		if($count) {

			while($result = $query->fetch(PDO::FETCH_ASSOC)) {
				$date = date('Y|m|d H:i:s', $result['date']);

				$this->str .= $result['name'].$result['message'].$date;

			}

		} else {
			$this->str .= "Коментарии ещо никто не добавлял";
		}


		return $this->str."</div>";
	}
}