<?php

require_once "ReadBD.php";
require_once "ExcelData.php";

class InsertBD extends ReadBD {
    public $obj,$dataExcel;
    protected $indice;

    public function __construct(){
        parent::__construct();
        $this->obj = new ExcelData();
        $this->dataExcel = $this->filtroExcel();
    }

    private function filtroExcel():array{
        $data = $this->obj->dataExcel();

        $count = count($data);

        foreach($data as $key => $dt){
            if(($count - 2) != $key){
                if ($key == 0) {
                    $this->setIndice($dt);
                }else{
                    $dado[] = $dt;
                }
            }else{
                break;
            }
        }

        if (isset($dado)) {
            return $dado;
        }
    }

    private function incidentesExcelData():array {
        foreach ($this->filtroExcel() as $data){
            $dt[] =  $data[0];
        }

        return $dt;
    }
    
    private function compararIncidentes():array {

        try {

            $incDB = $this->incidentesBaseDados();

            $inc = $this->incidentesExcelData();

            $intersect = array_intersect($inc, $incDB);

            $diff = array_diff($inc, $intersect);

            foreach ($diff as $d) {
                $ret[] = $d;
            }

            if (isset($ret)) {
                return $ret;
            }else{
                throw new Exception("A BASE JÁ FORAM ATUALIZADA!!");
            }

        } catch (Exception $e) {

            ob_end_clean();

            exit($e->getMessage());

        }

    }

    public function rowDataInsert():array {

        $inc = $this->filtroExcel();

        foreach ($this->compararIncidentes() as $comp){
            foreach ($inc as $i){
                if($comp == $i[0]){
                    $row[] = $i;
                    break;
                }
            }
        }

        if (isset($row)) {
            return $row;
        }

    }

    public function colExcel($num):array {
        foreach ($this->rowDataInsert() as $data){
            $dt[] =  $data[$num];
        }

        return $dt;
    }

    public function insertData(){

        $incidentes = $this->colExcel(array_search("ID do Incidente*+",$this->getIndice()));
        $criado = $this->dataTimeFormatInsertDB($this->colExcel(array_search("Criado em",$this->getIndice())));
        $resolucao = $this->dataTimeFormatInsertDB($this->colExcel(array_search("Data da Última Resolução",$this->getIndice())));
        $descricaoProblema = $this->colExcel(array_search("Notas",$this->getIndice()));
        $descricaoSolucao = $this->colExcel(array_search("Resolução",$this->getIndice()));
        $empresa = $this->insertArrayDataForeignKey("tb_empresa","descricao",$this->colExcel(array_search("Empresa de Suporte*",$this->getIndice())));
        $grupo = $this->insertArrayDataForeignKeyGrupo("tb_grupo_designado","grupo",$this->colExcel(array_search("Grupo Designado*+",$this->getIndice())),$empresa);
        $ic = $this->insertArrayDataForeignKey("tb_ic","descricao",$this->colExcel(array_search("IC+",$this->getIndice())));
        $prioridade = $this->insertArrayDataForeignKey("tb_prioridade","pri_descricao",$this->colExcel(array_search("Prioridade*",$this->getIndice())));
        $sumario = $this->insertArrayDataForeignKey("tb_sumario","descricao",$this->colExcel(array_search("Sumário*",$this->getIndice())));

        foreach ($incidentes as $key => $inc){
            $this->insert("INSERT INTO tb_ocorrencia (incidente, criado, resolucao, descricao_problema, descricao_solucao, fk_grupo_designado, fk_ic, fk_prioridade, fk_sumario) VALUES (?,?,?,?,?,?,?,?,?)", array($inc,$criado,$resolucao,$descricaoProblema[$key],$descricaoSolucao[$key],$grupo[$key],$ic[$key],$prioridade[$key],$sumario[$key]));
        }

        return true;

    }

    public function getDataExcel(): array{
        return $this->dataExcel;
    }

    public function setDataExcel(array $dataExcel){
        $this->dataExcel = $dataExcel;
    }

    public function getIndice(): array{
        return $this->indice;
    }

    public function setIndice(Array $indice){
        $this->indice = $indice;
    }

}

?>