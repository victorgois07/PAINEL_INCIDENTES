<?php
    require_once "config.php";

    $insert = new InsertBD();

    echo json_encode($insert->finalColData(new DateTime('now')), JSON_UNESCAPED_UNICODE);
    header('Content-Type: application/json');
?>