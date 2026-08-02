<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public string $defaultGroup = 'default';

    public array $default = [];

    public function __construct()
    {
        parent::__construct();

        // Read ENV supporting dots (database.default.X), underscores (database_default_X), and standard (DB_X)
        $hostname = env('database.default.hostname', env('database_default_hostname', env('DB_HOST', 'localhost')));
        $username = env('database.default.username', env('database_default_username', env('DB_USER', 'root')));
        $password = env('database.default.password', env('database_default_password', env('DB_PASS', '')));
        $database = env('database.default.database', env('database_default_database', env('DB_NAME', 'speed_boat_db')));
        $port     = (int) env('database.default.port', env('database_default_port', env('DB_PORT', 3306)));
        $driver   = env('database.default.DBDriver', env('database_default_DBDriver', env('DB_DRIVER', 'MySQLi')));

        $this->default = [
            'DSN'          => '',
            'hostname'     => $hostname,
            'username'     => $username,
            'password'     => $password,
            'database'     => $database,
            'DBDriver'     => $driver,
            'DBPrefix'     => '',
            'pConnect'     => false,
            'DBDebug'      => true,
            'charset'      => 'utf8mb4',
            'DBCollat'     => 'utf8mb4_general_ci',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => $port,
            'numberNative' => false,
            'foundRows'    => false,
            'dateFormat'   => [
                'date'     => 'Y-m-d',
                'datetime' => 'Y-m-d H:i:s',
                'time'     => 'H:i:s',
            ],
        ];
    }
}
