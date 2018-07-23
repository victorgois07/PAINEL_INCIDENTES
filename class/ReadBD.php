<?php

require_once "Dao.php";

class ReadBD extends Dao {

    protected function incidentesBaseDados():array {

        foreach ($this->select("SELECT incidente FROM tb_ocorrencia ORDER BY incidente") as $inc){
            $incidente[] = $inc[0];
        }

        return $incidente;
    }

    public function col_grupo_empresa(){
        $empresa = $this->getObj()->empresa;
        $grupo = array_unique($this->getObj()->grupo);
        $key_grupo = array_keys($grupo);


        foreach ($key_grupo as $k => $g){
            $emp[] = $empresa[$g];
            $ex = explode(" ",$empresa[$g]);

            if($ex[0] == "B2BR"){
                $resul[] = $grupo[$g]." * +2X";
            }else{
                $resul[] = $grupo[$g]." * ".$ex[0];
            }
        }

        if (isset($resul)) {
            return $resul;
        }
    }

    private function calcutoArrayIncidente($var){
        foreach ($var as $v){
            $sql = $this->conectBD()->prepare("SELECT `criado`,`resolucao` FROM `tb_ocorrencia` WHERE `incidente` = '{$v}'");
            if($sql->execute()){
                $d = $sql->fetchAll(\PDO::FETCH_NUM);
                $result[$v] = $d[0];
            }else{
                return $sql->errorInfo();
            }
        }

        if (isset($result)) {
            foreach ($result as $k => $r){
                $comando = $this->conectBD()->prepare("SELECT TIMESTAMPDIFF(SECOND,'{$r[0]}','{$r[1]}')");
                if($comando->execute()){
                    $f = $comando->fetchAll(\PDO::FETCH_NUM);
                    $dado[$k] = intval($f[0][0]);
                }else{
                    return $comando->errorInfo();
                }
            }
        }

        if(isset($dado)){
            return $dado;
        }
    }

    public function incidentesPrioridadeGrupo($prioridade,$grupo){
        $date = new DateTime("now");
        $sql = $this->conectBD()->query("SELECT `tb_ocorrencia`.`incidente` FROM `tb_ocorrencia` INNER JOIN `tb_prioridade` ON `tb_ocorrencia`.`fk_prioridade` = `tb_prioridade`.`id_prioridade` INNER JOIN `tb_grupo_designado` ON `tb_ocorrencia`.`fk_grupo_designado` = `tb_grupo_designado`.`id_grupo_designado` WHERE (`tb_prioridade`.`pri_descricao` = '{$prioridade}') AND (`tb_grupo_designado`.`grupo` = '{$grupo}') AND (MONTH(`tb_ocorrencia`.`criado`) = '{$date->format("m")}')");

        if($sql->execute()){

            foreach ($sql->fetchAll(\PDO::FETCH_NUM) as $dado){
                $result[] = $dado[0];
            }

            if (isset($result)) {
                return $this->calcutoArrayIncidente($result);
            }
        }else{
            return $sql->errorInfo();
        }
    }
    
    public function totalIncidente(){
        $date = new DateTime("now");
        $sql = $this->conectBD()->prepare("SELECT `tb_ocorrencia`.`incidente` FROM `tb_ocorrencia` WHERE MONTH(`criado`) = '{$date->format("m")}'");
        if($sql->execute()){
            return $sql->rowCount();
        }else{
            return $sql->errorInfo();
        }
    }

    public function incidenteGrupoNoPrazoVencido($var,$prioridade,$tipo){
        foreach ((array) $var as $k => $v){
            switch ($prioridade){
                case "Baixo":
                    if($v <= 28800){
                        $prazo[] = $k;
                    }else{
                        $vencido[] = $k;
                    }
                    break;

                case "Média":
                    if($v <= 21600){
                        $prazo[] = $k;
                    }else{
                        $vencido[] = $k;
                    }
                    break;

                case "Alto":
                    if($v <= 14400){
                        $prazo[] = $k;
                    }else{
                        $vencido[] = $k;
                    }
                    break;

                case "Crítico":
                    if($v <= 7200){
                        $prazo[] = $k;
                    }else{
                        $vencido[] = $k;
                    }
                    break;
            }
        }
        
        if($tipo == "VENCIDO"){
            if (isset($vencido)) {
                return $vencido;
            }
        }else{
            if(isset($prazo)){
                return $prazo;
            }
        }
    }

    public function todosIncidentes(){
        $date = new DateTime("now");
        $sql = $this->conectBD()->prepare("SELECT `incidente` FROM `tb_ocorrencia` WHERE MONTH(`criado`) = '{$date->format("m")}' ORDER BY `incidente` ASC");

        if($sql->execute()){
            foreach ($sql->fetchAll(\PDO::FETCH_NUM) as $k => $var){
                $dado[] = $var[0];
            }

            if(isset($dado)) {
                $incidente = $this->getObj()->incidente;

                $diff = array_diff($incidente,$dado);

                return $diff;
            }
        }else{
            return $sql->errorInfo();
        }
    }

    public function getObj(){
        return $this->obj;
    }
    
    public function setObj($obj){
        $this->obj = $obj;
    }
}

?>