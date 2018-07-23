<?php
    use SimpleExcel\SimpleExcel;
    require_once "lib/SimpleExcel/SimpleExcel.php";
    require_once "class/ManipuladorExcel.php";    
    require_once "class/insertBD.php";

    $insert = new insertBD(new ManipuladorExcel(new SimpleExcel('xml')));

    if (isset($_GET["a"]) && $_GET["a"] == true) {
        echo $insert->cadastro_ocorrencia_base();
    }else{
        echo "ERRO";
    }

?>