<?php

class Template
{
    static public function Temp($fl, $header, $cont)
    {
        $file = file_get_contents('./tmp/tpl/' . $fl . ".tpl");
        $file = str_replace("{TITLE}", $header, $file);
        $file = str_replace("{CONTENT}", $cont, $file);
        $file = str_replace("{TMP}", TMP, $file);

        return $file;
    }
}