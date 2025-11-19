<?php
namespace src\Model;
use PDO;
use PDOException;

class BDD{
    private static $instance = null;
    private const _DBHOSTNAME_ = "cours_php-mariadb106";
    private const _DBNAME_ = "docker";
    private const _DBUSERNAME_ = "docker";
    private const _DBPASSWORD_ = "docker";
    private const _DBPORT_ = 3306;

    public static function getInstance(): PDO{
        if(self::$instance == null){
            try{
                self::$instance = new PDO(
                    dsn: "mysql:host=".self::_DBHOSTNAME_.";port=".self::_DBPORT_.";dbname=".self::_DBNAME_.";charset=utf8",
                    username: self::_DBUSERNAME_,
                    password: self::_DBPASSWORD_
                );

            }catch (PDOException $e){
                die("Erreur {$e->getMessage()}");
            }
        }
        return self::$instance;

    }


}