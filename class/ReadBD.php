<?php

require_once "Dao.php";

class ReadBD extends Dao {

    protected function incidentesBaseDados():array {

        foreach ($this->select("SELECT incidente FROM tb_ocorrencia ORDER BY incidente") as $inc){
            $incidente[] = $inc[0];
        }

        return $incidente;
    }

    protected function totalOcorrencia(DateTime $now):int{
        $where = $now->format("Y-m-")."%";
        $tot = $this->select("SELECT COUNT(*) FROM tb_ocorrencia WHERE resolucao LIKE ?",$where);
        return $tot[0][0];
    }

    protected function colFirst(DateTime $now):array {
        $where = $now->format("Y-m-")."%";

        $grupoMesAno = $this->select("SELECT COUNT(o.incidente) as quantidade, tgd.grupo, empresa.descricao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado tgd on o.fk_grupo_designado = tgd.id_grupo_designado INNER JOIN tb_empresa empresa on tgd.fk_empresa = empresa.id_empresa INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade WHERE o.resolucao LIKE ? GROUP BY tgd.grupo ORDER BY empresa.descricao",$where);

        return $grupoMesAno;
    }

    protected function firstColGet(DateTime $now):array {

        $first = $this->colFirst($now);

        foreach ($first as $f){
            $empresa[] = $f[2];
            $grupo[] = $f[1];
        }

        return array($empresa,$grupo);
    }

    protected function colData(DateTime $now, String $prioridade, int $second):array {

        $first = $this->firstColGet($now);

        $empresa = $first[0];
        $grupo = $first[1];

        $data = $this->colsDataDB($now,$prioridade,$second);


        for($i=0; $i<count($empresa); $i++){
            $vencido[$i] = 0;
        }

        foreach ($data[0] as $ba){
            if(in_array($ba[1],$grupo) && in_array($ba[2], $empresa)){
                $vencido[array_search($ba[1],$grupo)] =  $ba[0];
            }
        }

        for($i=0; $i<count($empresa); $i++){
            $prazo[$i] = 0;
        }

        foreach ($data[1] as $ba){
            if(in_array($ba[1],$grupo) && in_array($ba[2], $empresa)){
                $prazo[array_search($ba[1],$grupo)] =  $ba[0];
            }
        }

        return array($prazo,$vencido);

    }


    protected function colsDataDB(DateTime $now, String $prioridade, int $second):array {
        $where = $now->format("Y-m-")."%";

        $vencido = $this->select("SELECT COUNT(o.incidente) as quantidade, tgd.grupo, empresa.descricao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado tgd on o.fk_grupo_designado = tgd.id_grupo_designado INNER JOIN tb_empresa empresa on tgd.fk_empresa = empresa.id_empresa INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade WHERE o.resolucao LIKE ? AND tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) > ? GROUP BY tgd.grupo ORDER BY empresa.descricao", array($where,$prioridade,$second));

        $noPrazo = $this->select("SELECT COUNT(o.incidente) as quantidade, tgd.grupo, empresa.descricao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado tgd on o.fk_grupo_designado = tgd.id_grupo_designado INNER JOIN tb_empresa empresa on tgd.fk_empresa = empresa.id_empresa INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade WHERE o.resolucao LIKE ? AND tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) <= ? GROUP BY tgd.grupo ORDER BY empresa.descricao", array($where,$prioridade,$second));

        return array($vencido,$noPrazo);

    }

    private function stringEmpresa(String $empresa):string {
        switch ($empresa){
            case "B2BR BUSINESS TO BUS INF DO BRASIL LTDA":
                return "+2X";
                break;
            case "CSC BRASIL SISTEMAS LTDA":
                return "CSC";
                break;
            case "STEFANINI CONS ASSESSORIA INFORMATICA SA":
                return "STEFANINI";
                break;
            case "TIVIT TERC DE PROC SERV TECN S/A":
                return "TIVIT";
                break;
            default:
                return $empresa;
                break;
        }
    }

    public function dataReturnFinalTotal($prioridade,$second, $data, $status):int {

        if ($status == "VENCIDO") {

            $dados = $this->select("SELECT count(o.incidente) FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) > ? AND o.resolucao LIKE ?", array($prioridade, $second, $data));

        } else {

            $dados = $this->select("SELECT count(o.incidente) FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) <= ? AND o.resolucao LIKE ?", array($prioridade, $second, $data));

        }

        if (!empty($dados) && isset($dados)) {
            return $dados[0][0];
        }

    }

    public function arrayDatatfooter(DateTime $now){

        $total = array(
            ($this->dataReturnFinalTotal("Baixo",28800,$now->format("Y-m-")."%","NOPRAZO")+$this->dataReturnFinalTotal("Média",21600,$now->format("Y-m-")."%","NOPRAZO")+$this->dataReturnFinalTotal("Alto",14400,$now->format("Y-m-")."%","NOPRAZO")+$this->dataReturnFinalTotal("Crítico",7200,$now->format("Y-m-")."%","NOPRAZO")),
            ($this->dataReturnFinalTotal("Baixo",28800,$now->format("Y-m-")."%","VENCIDO")+$this->dataReturnFinalTotal("Média",21600,$now->format("Y-m-")."%","VENCIDO")+$this->dataReturnFinalTotal("Alto",14400,$now->format("Y-m-")."%","VENCIDO")+$this->dataReturnFinalTotal("Crítico",7200,$now->format("Y-m-")."%","VENCIDO"))
        );

        $td = array("TOTAL","");
        $dataTb = array(
            $this->dataReturnFinalTotal("Baixo",28800,$now->format("Y-m-")."%","NOPRAZO"),
            $this->dataReturnFinalTotal("Baixo",28800,$now->format("Y-m-")."%","VENCIDO"),
            $this->dataReturnFinalTotal("Média",21600,$now->format("Y-m-")."%","NOPRAZO"),
            $this->dataReturnFinalTotal("Média",21600,$now->format("Y-m-")."%","VENCIDO"),
            $this->dataReturnFinalTotal("Alto",14400,$now->format("Y-m-")."%","NOPRAZO"),
            $this->dataReturnFinalTotal("Alto",14400,$now->format("Y-m-")."%","VENCIDO"),
            $this->dataReturnFinalTotal("Crítico",7200,$now->format("Y-m-")."%","NOPRAZO"),
            $this->dataReturnFinalTotal("Crítico",7200,$now->format("Y-m-")."%","VENCIDO"),
            $total[0],
            $total[1],
            round((($total[0]) / (($total[0])+($total[1]))) * 100)."%",
            round((($total[1]) / (($total[0])+($total[1]))) * 100)."%"
        );

        return array_merge($td,$dataTb);
    }


    public function finalColData(DateTime $now):array{
        $first = $this->firstColGet($now);
        $empresa = $first[0];
        $grupo = $first[1];
        $baixo = $this->colData(new DateTime('now'),"Baixo",28800);
        $media = $this->colData(new DateTime('now'),"Média",21600);
        $alto = $this->colData(new DateTime('now'),"Alto",14400);
        $critico = $this->colData(new DateTime('now'),"Crítico",7200);

        foreach ($empresa as $key => $emp){

            $values[] = array(
                $this->stringEmpresa($emp),
                strtoupper($grupo[$key]),
                $baixo[0][$key],
                $baixo[1][$key],
                $media[0][$key],
                $media[1][$key],
                $alto[0][$key],
                $alto[1][$key],
                $critico[0][$key],
                $critico[1][$key],
                ($baixo[0][$key]+$media[0][$key]+$alto[0][$key]+$critico[0][$key]),
                ($baixo[1][$key]+$media[1][$key]+$alto[1][$key]+$critico[1][$key]),
                round((($baixo[0][$key]+$media[0][$key]+$alto[0][$key]+$critico[0][$key]) / (($baixo[0][$key]+$media[0][$key]+$alto[0][$key]+$critico[0][$key])+($baixo[1][$key]+$media[1][$key]+$alto[1][$key]+$critico[1][$key]))) * 100)."%",
                round((($baixo[1][$key]+$media[1][$key]+$alto[1][$key]+$critico[1][$key]) / (($baixo[0][$key]+$media[0][$key]+$alto[0][$key]+$critico[0][$key])+($baixo[1][$key]+$media[1][$key]+$alto[1][$key]+$critico[1][$key]))) * 100)."%"
                );
        }

        array_push($values,$this->arrayDatatfooter($now));

        return $values;
    }

    protected function dataReturnTableModal($prioridade,$second, $grupo, $empresa, $data, $status){

        try {

            if ($status == "VENCIDO") {

                $dados = $this->select("SELECT o.incidente, g.grupo as grupo_designado, tp.pri_descricao as prioridade, empresa.descricao as empresa,ti.descricao as  IC,sumario.descricao as sumario,o.criado as criado_em,o.resolucao as solucionado,TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) as duracao,o.descricao_problema,o.descricao_solucao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) > ? AND g.grupo LIKE ? AND empresa.descricao = ? AND o.resolucao LIKE ?", array($prioridade, $second, $grupo, $empresa, $data));

            } else {

                $dados = $this->select("SELECT o.incidente, g.grupo as grupo_designado, tp.pri_descricao as prioridade, empresa.descricao as empresa,ti.descricao as  IC,sumario.descricao as sumario,o.criado as criado_em,o.resolucao as solucionado,TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) as duracao,o.descricao_problema,o.descricao_solucao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) <= ? AND g.grupo LIKE ? AND empresa.descricao = ? AND o.resolucao LIKE ?", array($prioridade, $second, $grupo, $empresa, $data));

            }

            if (!empty($dados) && isset($dados)) {
                return $dados;
            }

        } catch (Exception $e) {

            ob_end_clean();

            $comma_separated = implode(",", array($prioridade, $second, $grupo, $empresa, $data));

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>DATA: ".$comma_separated);

        }

    }

    protected function dataReturnTrTotal($prioridade,$second,$data, $status){

        try {

            if ($status == "VENCIDO") {

                $dados = $this->select("SELECT o.incidente, g.grupo as grupo_designado, tp.pri_descricao as prioridade, empresa.descricao as empresa,ti.descricao as  IC,sumario.descricao as sumario,o.criado as criado_em,o.resolucao as solucionado,TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) as duracao,o.descricao_problema,o.descricao_solucao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) > ? AND o.resolucao LIKE ?", array($prioridade, $second, $data));

            } else {

                $dados = $this->select("SELECT o.incidente, g.grupo as grupo_designado, tp.pri_descricao as prioridade, empresa.descricao as empresa,ti.descricao as  IC,sumario.descricao as sumario,o.criado as criado_em,o.resolucao as solucionado,TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) as duracao,o.descricao_problema,o.descricao_solucao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado g ON o.fk_grupo_designado = g.id_grupo_designado INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade INNER JOIN tb_empresa empresa on g.fk_empresa = empresa.id_empresa INNER JOIN tb_ic ti on o.fk_ic = ti.id_ic INNER JOIN tb_sumario sumario on o.fk_sumario = sumario.id_sumario WHERE tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) <= ? AND o.resolucao LIKE ?", array($prioridade, $second, $data));

            }

            if (!empty($dados) && isset($dados)) {
                return $dados;
            }

        } catch (Exception $e) {

            ob_end_clean();

            $comma_separated = implode(",", array($prioridade, $second, $data));

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>DATA: ".$comma_separated);

        }

    }

    public function filterDataReturnModal(DateTime $now,$cell):array{

        try {

            if ($cell['prioridade'] == "Total" || $cell['prioridade'] == "%") {

                $prioridade = array("Baixo", "Média", "Alto", "Crítico");

                $second = array(28800, 21600, 14400, 7200);

                if ($cell['empresa'] == "TOTAL" && $cell['grupo'] == "%%") {

                    foreach ($prioridade as $key => $pri) {

                        $tot = $this->dataReturnTrTotal($pri, $second[$key], $now->format("Y-m-") . "%", $cell['tipo']);

                        if (!empty($tot) || isset($tot)) {
                            $total[] = $this->dataReturnTrTotal($pri, $second[$key], $now->format("Y-m-") . "%", $cell['tipo']);
                        }

                    }

                    for ($i = 0; $i < count($total); $i++) {
                        foreach ($total[$i] as $item) {
                            $dados[] = $item;
                        }
                    }

                } else {


                    foreach ($prioridade as $key => $pri) {

                        $tot = $this->dataReturnTableModal($pri, $second[$key], $cell['grupo'], $cell['empresa'], $now->format("Y-m-") . "%", $cell['tipo']);

                        if (!empty($tot) || isset($tot)) {
                            $total[] = $this->dataReturnTableModal($pri, $second[$key], $cell['grupo'], $cell['empresa'], $now->format("Y-m-") . "%", $cell['tipo']);
                        }

                    }

                    for ($i = 0; $i < count($total); $i++) {
                        foreach ($total[$i] as $item) {
                            $dados[] = $item;
                        }
                    }

                }

            } else if($cell['empresa'] == "TOTAL" && $cell['grupo'] == "%%") {

                $dados = $this->dataReturnTrTotal($cell['prioridade'], $cell['second'], $now->format("Y-m-") . "%", $cell['tipo']);

            } else {

                $dados = $this->dataReturnTableModal($cell['prioridade'], $cell['second'], $cell['grupo'], $cell['empresa'], $now->format("Y-m-") . "%", $cell['tipo']);

            }

            return $dados;

        } catch (Exception $e) {

            ob_end_clean();

            $comma_separated = implode(",", $cell);

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile()."<br/>DATA: ".$comma_separated);

        }

    }

    public function funcDuracao($data):string{
        $d = new DateTime();
        $d->setTime(0,0,$data);
        return $d->format("H:i:s");
    }

    public function dateDuracaoOcorrencia($data,$prioridade):string{
        
        switch ($prioridade) {

            case "Baixo":
               $second = 28800;
                break;

            case "Média":
                $second = 21600;
                break;

            case "Alto":
                $second = 14400;
                break;

            case "Crítico":
                $second = 7200;
                break;

        }

        $d1 = new DateTime();
        $d2 = new DateTime();

        $d1->setTime(0,0,$data);
        $d2->setTime(0,0,$second);

        return $d1->diff($d2)->format("%H:%i:%s");

    }

}

?>