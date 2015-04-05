<?php

class RightMenu
{
    private static $str; //Переменная для строки
    const TABLE = "<table class='table table-bordered' width='140'>
	<thead>
		<tr class='error'>
			<th>Популярные файлы</th>
		</tr>
	</thead>
	<tbody>";
    const ENDTABLE = "</tbody></table>";

    //Вывод поаулярных файлов
    public static function RightM()
    {

        $date = time() - 3600 * 48;
        $sql = "SELECT * FROM (SELECT * FROM static ORDER BY download DESC LIMIT 10)
		AS st LEFT JOIN files ON files.idfilesone = st.idfiles";

        $query = Db::getInst()->query($sql);
        self::$str = self::TABLE;

        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {

            $header = substr($result['header'], 0, 20);
            $header .= '...';

            self::$str .= "<tr class='info'><td><a href='files/" . $result['idfilesone'] . "'>" . $header . "</a></td></tr>";
        }

        return self::$str . self::ENDTABLE;

    }
}