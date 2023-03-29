<?php

class Database {
    private static $dbName = 'sagar';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbUserpassword = '';
    private static $con = null;

    public function __construct()
    {
        die('Init function is not allowed');
    }

    public static function connect() {
        if(null == self::$con) {
            try {
                self::$con= new PDO('mysql:host='.self::$dbHost.';'.'dbname='.self::$dbName,self::$dbUsername,self::$dbUserpassword);
                self::$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $s) {
                // echo 'connection failed '.$s->getMessage();
                die($s->getMessage());
            }
        }
        return self::$con;
    }
    public static function disconnect() {
        self::$con = null;
    }
}

?>