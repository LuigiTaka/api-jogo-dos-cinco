<?php

class Database
{
    static public function get()
    {
        static $conn = false;
        if (empty($conn)) {
            $conn = new PDO("mysql:host=jdc-db;dbname=jogo_dos_cinco", "apresentador", "umasenhacomplicada");
        }

        return $conn;
    }

}