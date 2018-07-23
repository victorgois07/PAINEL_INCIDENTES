<!doctype html>
<html lang="pt-br">

<head>
    <title>Acompanhamento incidentes</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="refresh" content="5000; url=index.php">
</head>
<style>
    *{
        font-family: 'Arial', sans-serif;
    }

    body{
        padding-top: 5px;
    }

    table {
        border: 2px solid #000;
    }

    table th, table tr{
        border: 2px solid #000 !important;
    }

    table thead{
        border: 2px solid #000;
    }

    h1{
        font-size: 35px;
    }

    h3{
        font-size: 45px;
        font-weight: 900;
    }

    #containerTable{
        padding: 0 30px 30px 30px;
    }

    #example thead{/* LINHA COM OS IDENFITICADORES */
        font-size: 35px;
        color: #000000;
    }

    #example tbody{/* Letras do Indice */
        font-size: 80px;
        font-weight: bolder;
    }

    #example{/* Tamanho da Tabela */
        font-size: 50px;
    }

    #example tbody tr{/* COR DA LINHAS */
        background-color: #ffffff;
    }

    #example tbody tr:nth-child(2),#example tbody tr:nth-child(4){ /* Cores azul claro nas linhas */
        background-color: #e0efff;
    }

    table.dataTable tbody th, table.dataTable tbody td {/* Espaçamento nas celulas */
        padding: 7px 5px;
    }

    @media only screen and (max-width: 1600px){/* Responsivo para tela de usúários */
        #example tbody {
            font-size: 50px;
        }
    }

    .blueTr{
        background-color: #E0EFFF !important;
    }
</style>

<script type="text/javascript">
    var larg = $(window).width();
    larg -= 60;

    $("#example").width(larg);

    if(larg <= 1600){
        $("#example tbody").css("font-size","50");
    }else{
        $("#example tbody").css("font-size","90");
    }
</script>
<?php

require_once "class/ManipulacaoDados.php";
$txtData = new ManipulacaoDados();
$texth1 = strtoupper("Acompanhamento incidentes - PERÍODO ".$txtData->h1InforDate());

require_once "class/ManipuladorExcel.php";
$classME = new ManipuladorExcel();

use SimpleExcel\SimpleExcel;
require_once "lib/SimpleExcel/SimpleExcel.php";
$excel = new SimpleExcel('xml');

$excel->parser->loadFile($classME->arquivosLocal());
$dataIncio = $excel->parser->getColumn(7);
$dataFinal = $excel->parser->getColumn(10);
array_shift($dataIncio);
array_shift($dataFinal);
$countTemp = $classME->countHora($dataIncio,$dataFinal);
$port = $classME->calcPorcentagemTemp($countTemp);
?>

<h1><strong><?= $texth1 ?></strong></h1>

<?php

if (array_key_exists("a", $_GET)){
    if(isset($_GET["a"])){
        $txtData->extracaoExcel($port,$countTemp);
    }
}

?>


