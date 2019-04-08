<?php

declare(strict_types=1);

namespace Vendi\BASE;

final class Database
{
    private static $dbh;

    final public static function get_pdo() : \PDO
    {
        if (self::$dbh) {
            return self::$dbh;
        }

        include(VENDI_BASE_ROOT_DIR . '/base_conf.php');

        dump($alert_dbname);

        $db_user = $alert_user;
        $db_name = $alert_dbname;
        $db_pass = $alert_password;
        $db_host = $alert_host;

        self::$dbh = new \PDO("mysql:host=${db_host};dbname=${db_name}", $db_user, $db_pass);
        self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$dbh->setAttribute(\PDO::ATTR_PERSISTENT, true);

        return self::$dbh;
    }
}
