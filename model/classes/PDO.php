<?php
class PDO_OPS
{
    public static function isConnection($value)
    {
        return (is_object($value) && ($value instanceof PDO));
    }
    
    public static function disconnect()
    {
        $this->connection = null;
        return true;
    }
}