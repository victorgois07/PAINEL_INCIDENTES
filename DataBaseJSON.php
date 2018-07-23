<?php
    use SimpleExcel\SimpleExcel;
    require_once "lib/SimpleExcel/SimpleExcel.php";
    require_once "class/ManipuladorExcel.php";
    require_once "class/ManipulacaoDados.php";
    require_once "class/ReadBD.php";

    $txtData = new ManipulacaoDados();

    $read = new readBD(new ManipuladorExcel(new SimpleExcel('xml')));

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    $grupo = $read->col_grupo_empresa();
    foreach ($grupo as $k => $gp){
        $ex = explode("*",$gp);
        $row[$gp] = array();

        $dados = array(
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","NOPRAZO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","VENCIDO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","NOPRAZO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","VENCIDO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","NOPRAZO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","VENCIDO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","NOPRAZO")),
            count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","VENCIDO"))
        );

        array_push(
            $row[$gp],
            str_replace("*", "-", $gp),
            $dados[0],
            $dados[1],
            $dados[2],
            $dados[3],
            $dados[4],
            $dados[5],
            $dados[6],
            $dados[7],
            ($dados[0]+$dados[2]+$dados[4]+$dados[6]),
            ($dados[1]+$dados[3]+$dados[5]+$dados[7]),
            round((($dados[0]+$dados[2]+$dados[4]+$dados[6])/$read->totalIncidente())*100),
            round((($dados[1]+$dados[3]+$dados[5]+$dados[7])/$read->totalIncidente())*100)
        );
    }

    if (isset($row)) {

        $d = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach($row as $r){
            for($i=1; $i<count($r); $i++){
                $d[$i-1] += $r[$i];
            }
        }

        array_unshift($d,"TOTAL");

        ksort($row);

        array_push($row,$d);

        $rowB = array_values($row);

        header('Content-Type: application/json');
        echo json_encode($rowB);
    }




?>