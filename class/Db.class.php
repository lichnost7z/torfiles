<?php

class Db
{
    private static $_instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getInst()
    {
        if (!self::$_instance) {
            self::$_instance = new PDO("mysql:host=localhost;dbname=download", 'root', '',
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            self::$_instance->exec('SET CHARACTER SET utf8');
        }

        return self::$_instance;
    }
}