<?php

class Config
{
    // this is public to allow better Unit Testing
    public static $config;
    public static $localconfig;

    private static function getlocal($key)
    {
        // load all vars in config into memory, usefull for database credentials. (Or overwriting the database settings via the config.)
        if (!self::$localconfig) {
            $config_file = '../application/config/config.' . Environment::get() . '.php';
            if (!file_exists($config_file)) {
                return false;
            }
            self::$localconfig = require $config_file;
        }
        return self::$localconfig[$key];
    }

    public static function get($setting, $full = false)
    {   
        // check if it is a local setting, ie database. 
        $local = self::getlocal($setting);
        if ($local != null OR $local != false) {
            return $local;
        }
        // go on to the database. 
        $database = DatabaseFactory::getFactory()->getConnection();
        if ($full == false) {
            $sql = "SELECT set_value FROM settings WHERE setting = :setting LIMIT 1";
        } else {
            $sql = "SELECT set_key, setting, set_value FROM settings WHERE setting = :setting LIMIT 1";
        }
        $query = $database->prepare($sql);
        $query->execute(array(':setting' => $setting));
        if ($query->rowCount() < 1) {
            return null;
        } else {
            return $query->fetch();
        }
        return null;
    }

    public static function set($setting, $value)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO settings (setting, set_value) VALUES (:setting, :set_value)";
        $query = $database->prepare($sql);
        $query->execute(array(':setting' => $setting, ':set_value' => $value));
        if ($query->rowCount() < 1) {
            return false;
        } else {
            return true;
        }
        return null;
    }

    // @TODO: Add other database calls here. 
}