<?php

class ConectBD{

    protected $conn;

    public function __construct(){

        try {

            $this->conn = new PDO("mysql:host=localhost;dbname=db_table_incidentes","root","",
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
                )
            );

            $this->conn->exec("SET lc_time_names = 'pt_BR'");

        } catch (PDOException $e) {

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile());

        }

    }

    public function getConn(): PDO{
        return $this->conn;
    }

    public function setConn(PDO $conn){
        $this->conn = $conn;
    }

}

?>