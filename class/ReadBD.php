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


    protected function colsDataDB(DateTime $now, String $prioridade, int $second){
        $where = $now->format("Y-m-")."%";

        $vencido = $this->select("SELECT COUNT(o.incidente) as quantidade, tgd.grupo, empresa.descricao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado tgd on o.fk_grupo_designado = tgd.id_grupo_designado INNER JOIN tb_empresa empresa on tgd.fk_empresa = empresa.id_empresa INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade WHERE o.resolucao LIKE ? AND tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) > ? GROUP BY tgd.grupo ORDER BY empresa.descricao", array($where,$prioridade,$second));

        $noPrazo = $this->select("SELECT COUNT(o.incidente) as quantidade, tgd.grupo, empresa.descricao FROM tb_ocorrencia o INNER JOIN tb_grupo_designado tgd on o.fk_grupo_designado = tgd.id_grupo_designado INNER JOIN tb_empresa empresa on tgd.fk_empresa = empresa.id_empresa INNER JOIN tb_prioridade tp on o.fk_prioridade = tp.id_prioridade WHERE o.resolucao LIKE ? AND tp.pri_descricao = ? AND TIMESTAMPDIFF(SECOND, o.criado,o.resolucao) <= ? GROUP BY tgd.grupo ORDER BY empresa.descricao", array($where,$prioridade,$second));

        return array($vencido,$noPrazo);

    }


    public function finalColData(DateTime $now):array{
        $first = $this->firstColGet($now);
        $empresa = $first[0];
        $grupo = $first[1];
        $baixo = $this->colData(new DateTime('now'),"Baixo",28800);
        $media = $this->colData(new DateTime('now'),"Média",21600);
        $alto = $this->colData(new DateTime('now'),"Alto",14400);
        $critico = $this->colData(new DateTime('now'),"Crítico",7200);

        $totalVencido = 0;
        $totalPrazo = 0;

        foreach ($empresa as $key => $emp){
            $values[] = array(
                $emp,
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

        return $values;
    }

}

?>