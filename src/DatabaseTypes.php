<?php

declare(strict_types=1);

namespace Vendi\BASE;

final class DatabaseTypes
{
    public const MYSQL = 'mysqli';

    public const POSTGRES = 'postgres';

    public const MSSQL = 'mssql';

    public const ORACLE = 'oci8';

    public static function get_support_database_types(): array
    {
        return [
            self::MYSQL,
            self::POSTGRES,
            self::MSSQL,
            self::ORACLE,
        ];
    }
}
