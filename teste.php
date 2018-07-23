<?php
    use SimpleExcel\SimpleExcel;
    require_once "lib/SimpleExcel/SimpleExcel.php";
    require_once "class/ManipuladorExcel.php";
    require_once "class/ManipulacaoDados.php";
    require_once "class/ReadBD.php";

    $txtData = new ManipulacaoDados();

    $read = new readBD(new ManipuladorExcel(new SimpleExcel('xml')));

    class ArrayValue implements JsonSerializable {
        public function __construct(array $array) {
            $this->array = $array;
        }

        public function jsonSerialize() {
            return $this->array;
        }
    }

    $fp = fopen('json/backlog_incidentes.json', 'x+');
    fwrite($fp, json_encode(new ArrayValue($read->todosIncidentes()), JSON_PRETTY_PRINT));
    fclose($fp);


?>