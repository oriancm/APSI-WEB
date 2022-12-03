<?php

namespace Admin\Database;

use PDO;

class Db extends \PDO

//Classe permettant la liaison la BDD
//Elle herite des attributs et des methodes de la classe PDO
//On peut donc creer une instance PDO et la paramétrer
//Normalement on ne créera qu'une seule instance de cette classe

{
    // Instance unique pour la classe DB
    private static ?Db $instance = null;

    private const DB_HOST = "localhost";

    private const DB_USER = "root";

    private const DB_PASS = "00rriiaann";

    private const DB_NAME = "apsi";


    public function __construct()
    {
        $host = self::DB_HOST;
        $user = self::DB_USER;
        $db_name = self::DB_NAME;
        $password = self::DB_PASS;

        $dsn = "mysql:host=$host;dbname=$db_name";

        try {
        //  PDO::construct
            parent::__construct($dsn, $user, $password);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}