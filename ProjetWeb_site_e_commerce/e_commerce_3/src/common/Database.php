<?php


class Database
{
    private static $instance = null;
    private $bd;

    private function connect() {
        $this->bd = new PDO("mysql:host=localhost;dbname=e_commerce;charset=utf8",
            'root',
            '',
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getDatabase() {
        if(is_null(self::$instance)) {
            self::$instance = new Database();
            self::$instance->connect();
        }
        return self::$instance->bd;
    }

}