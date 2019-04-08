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

        $config = Config::get_config();

        $db_user = $config['database']['primary']['username'];
        $db_name = $config['database']['primary']['name'];
        $db_pass = $config['database']['primary']['password'];
        $db_host = $config['database']['primary']['host'];

        self::$dbh = new \PDO("mysql:host=${db_host};dbname=${db_name}", $db_user, $db_pass);
        self::$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        self::$dbh->setAttribute(\PDO::ATTR_PERSISTENT, true);

        return self::$dbh;
    }
}
