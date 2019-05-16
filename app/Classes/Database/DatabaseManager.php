<?php

namespace App\Classes\Database;

use Illuminate\Database\DatabaseManager as BaseDatabaseManager;

class DatabaseManager extends BaseDatabaseManager
{
    protected function configuration($name)
    {
        $config = parent::configuration($name);

        $mod_config = $config;

        $mod_config['database'] = decrypt($config['database']);
        $mod_config['username'] = decrypt($config['username']);
        $mod_config['password'] = decrypt($config['password']);

        return $mod_config;
    }
}
