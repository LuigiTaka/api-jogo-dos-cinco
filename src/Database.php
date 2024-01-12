<?php

class Database
{
    static public function get()
    {
        static $conn = false;
        if (empty($conn)) {
            $params = [
                "host" => $_ENV['MYSQL_HOST'],
                "dbname" => $_ENV['MYSQL_DATABASE'],
                "username" => $_ENV['MYSQL_USER'],
                'password' => $_ENV['MYSQL_PASSWORD']
            ];

            $conn = new PDO("mysql:host=$params[host];dbname=$params[dbname]", "$params[username]", "$params[password]");
        }

        return $conn;
    }
}