<?php

namespace App\Core;

use \PDO;

class DB
{
    private static $dsn = "mysql:host=localhost;dbname=plateforme_partage;charset=utf8";
    private static $username = "sub_admin";
    private static $password = "sub_admin";
    private static $username_low_privilege = "user";
    private static $password_low_privilege = "user";


    private static $pdo;

    public static function getPdo()
    {
        if (DB::$pdo == null) {
            DB::$pdo = new PDO(DB::$dsn, DB::$username, DB::$password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return DB::$pdo;
    }

    public static function getPdoLow()
    {
        if (DB::$pdo == null) {
            DB::$pdo = new PDO(DB::$dsn, DB::$username_low_privilege, DB::$password_low_privilege, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return DB::$pdo;
    }


    public static function query(string $requete_sql)
    {
        return DB::getPdo()->query($requete_sql);
    }

    public static function exec(string $requete_sql)
    {
        return DB::getPdo()->exec($requete_sql);
    }

    // Fonction de transaction SQL (permet de faire plusieurs requêtes en simultané)
    public static function beginTransaction()
    {
        return DB::getPdo()->beginTransaction();
    }

    public static function commit()
    {
        return DB::getPdo()->commit();
    }

    // Permet de remettre la BDD a l'état initial
    public static function rollback()
    {
        return DB::getPdo()->rollback();
    }
}
