<?php

require_once "ConectBD.php";

class Dao extends ConectBD {

    private function setParams($statement, $parameters = array()){

        foreach ($parameters as $key => $value) {

            $statement->bindValue($key+1, $value);

        }

    }

    private function setParam($statement, $key, $value){

        $statement->bindValue($key+1, $value);

    }

    protected function query($rawQuery, $params = null){

        try {

            $stmt = $this->conn->prepare($rawQuery);

            if (is_string($params)) {

                $this->setParam($stmt, 0, $params);

            } elseif (is_array($params)) {

                $this->setParams($stmt, $params);

            }

            $stmt->execute();

            return $stmt;

        } catch (PDOException $e) {

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>COMANDO: ".$rawQuery."<br/>DATA: ".$params);

        }

    }

    protected function select($rawQuery, $params = array()):array {

        $stmt = $this->query($rawQuery, $params);

        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    protected function insert($rawQuery, $params = null):bool {

        try {

            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare($rawQuery);

            if (is_array($params)) {

                $this->setParams($stmt, $params);

            }else{

                $this->setParam($stmt, 0, $params);

            }

            $this->conn->commit();

            return $stmt->execute();

        } catch (PDOException $e) {

            $this->conn->rollBack();

            ob_end_clean();

            $comma_separated = implode(",", $params);

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>COMANDO: ".$rawQuery."<br/>DATA: ".$comma_separated);

        }

    }

    protected function firstColumn($table):string{
        $dado = $this->select("DESC $table");
        return $dado[0][0];
    }

    protected function dataExist($rawQuery, $params = array()):bool {

        $query = $this->query($rawQuery,$params);

        if($query->rowCount() == 0){

            return false;

        }else{

            return true;

        }

    }

    protected function jailNumberId($table){

        $col = $this->firstColumn($table);

        $jail = $this->select("SELECT a.$col - 1 AS livre FROM $table AS a LEFT JOIN $table AS b ON a.$col - 1 = b.$col WHERE b.$col IS NULL AND a.$col > 1;");

        if(empty($jail)){
            return null;
        }else{
            return $jail[0][0];
        }
    }

    protected function idValueReturn($table,$column,$val){
        $col = $this->firstColumn($table);
        $jail = $this->select("SELECT $col FROM $table WHERE $column = ?",strval($val));
        if (isset($jail[0][0])) {
            return $jail[0][0];
        }
    }

    protected function insertDataNotExist($table,$col,$data,$data2 = null){

        try {
            if ($this->dataExist("SELECT * FROM $table WHERE $col = ?", $data) == false) {

                if ($data2 != null) {

                    $this->insert("INSERT INTO tb_grupo_designado (grupo, fk_empresa) VALUES (?,?)", array($data, $data2));

                    return $this->idValueReturn($table, $col, $data);

                } else {

                    $this->insert("INSERT INTO $table VALUES (?,?)", array($this->jailNumberId($table),$data));

                    return $this->idValueReturn($table, $col, $data);

                }

            } else {

                return $this->idValueReturn($table, $col, $data);

            }
        } catch (PDOException $e) {

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>TABLE: ".$table."<br/>COLUNA: ".$col."<br/>VALOR: ".$data);

        }

    }

    protected function insertArrayDataForeignKeyGrupo($table, $col, $array, $array2):array {

        foreach ($array as $key => $ary){
            $data[] = $this->insertDataNotExist($table, $col, $ary,$array2[$key]);
        }

        return $data;

    }

    protected function insertArrayDataForeignKey($table, $col, $array):array {

        foreach ($array as $ary){
            $data[] = $this->insertDataNotExist($table, $col, $ary);
        }

        return $data;

    }

    protected function arrangingArrayDB($array):array {
        foreach ($array as $v){
            $p[] = $v[0];
        }

        if (isset($p)) {
            return $p;
        }
    }

    protected function dataTimeFormatInsertDB($data):array {

        foreach($data as $dt){
            $d[] = \DateTime::createFromFormat('d/m/Y H:i:s', $dt)->format('Y-m-d h:i:s');
        }

        if (isset($d)) {
            return $d;
        }

    }

    public function removeString($string, $caractere){
        return str_replace($caractere, "", $string);
    }
}

?>