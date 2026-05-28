<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $defaultGroup = 'default';

    public array $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'postgres',       // sesuaikan username PostgreSQL kamu
        'password' => 'postgres123',       // sesuaikan password kamu
        'database' => 'medansehat',
        'DBDriver' => 'Postgre',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 5432,
        'numberNative' => false,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->default['hostname'] = env('database.default.hostname', $this->default['hostname']);
        $this->default['database'] = env('database.default.database', $this->default['database']);
        $this->default['username'] = env('database.default.username', $this->default['username']);
        $this->default['password'] = env('database.default.password', $this->default['password']);
        $this->default['port']     = (int) env('database.default.port', $this->default['port']);
    }
}