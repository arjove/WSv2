<?php

class Log
{
    // this is public to allow better Unit Testing
    public static $log;

    public static function put($type, $unit, $main_des, $description, $parameters)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO logging (type, unit, main_des, description, parameters) VALUES (:type, :unit, :main_des, description, :parameters)";
        $query = $database->prepare($sql);
        $query->execute(array(':type' => $type, ':unit' => $unit, ':main_des' => $main_des, ':description' => $description, ':parameters' => $parameters,));
        if ($query->rowCount() < 1) {
            return false;
        } else {
            return true;
        }
        return null;
    }

    // @TODO: Add other database calls here. 
}