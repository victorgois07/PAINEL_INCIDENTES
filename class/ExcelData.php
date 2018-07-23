<?php

require_once dirname(__FILE__)."/../class/PHPExcel/PHPExcel.php";

class ExcelData extends PHPExcel {

    public function validateFilesName():string{

        try {

            if (is_dir(dirname(__FILE__,2).DIRECTORY_SEPARATOR."files")) {

                $arquivos = glob("files/" . "{*.xlsx}", GLOB_BRACE);

                if (!empty($arquivos)) {

                    foreach ($arquivos as $nome) {

                        $arrayDados[] = $nome;

                    }

                    if (isset($arrayDados)) {

                        return $arrayDados[0];

                    }else{

                        throw new Exception("ERRO: Variavel arrayDados encontra-se sem valores!");

                    }

                } else {

                    throw new Exception("Não Existem arquivos dentro do diretorio files");

                }

            } else {

                throw new Exception("O Diretorio files não existe!");

            }
        } catch (Exception $e) {

            return "ERRO: ".$e->getMessage()."\nLinha: ".$e->getLine()."\nArquivos: ".$e->getFile();

        }
    }

    public function dataExcel():array {

        try {
            $excelReader = PHPExcel_IOFactory::createReaderForFile($this->validateFilesName());

            $excelObj = $excelReader->load($this->validateFilesName());

            $worksheet = $excelObj->getActiveSheet();

            $lastRow = $worksheet->getHighestRow();

            $col = ord($worksheet->getHighestColumn())-64;

            for ($i=0; $i<$col; $i++){
                $valCol[] = chr(65+$i);
            }

            if (isset($valCol)) {
                for ($i = 1; $i <= $lastRow; $i++) {
                    for ($j = 0; $j < $col; $j++) {
                        $excel[$i - 1][$j] = $worksheet->getCell($valCol[$j] . $i)->getValue();
                    }
                }

                if(isset($excel)){
                    return $excel;
                }
            }

        } catch (PHPExcel_Reader_Exception $e) {

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile());

        } catch (PHPExcel_Exception $e) {

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile());

        } catch (Exception $e){

            ob_end_clean();

            exit("ERRO: ".$e->getMessage()."<br/>LINHA: ".$e->getLine()."<br/>CODE: ".$e->getCode()."<br/>ARQUIVO: ".$e->getFile());

        }


    }



}