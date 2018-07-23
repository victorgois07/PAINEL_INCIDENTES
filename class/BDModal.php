<?php

require_once "ConectBD.php";

class BDModal extends ConectBD{
    public function printr($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
    
    public function BD_dados_modal($incidente){
        $sql = $this->conectBD()->prepare("SELECT `tb_ocorrencia`.`incidente`,`tb_sumario`.`descricao`,`tb_ic`.`descricao`,`tb_ocorrencia`.`criado`,`tb_ocorrencia`.`resolucao`,`tb_ocorrencia`.`descricao_solucao` FROM `tb_ocorrencia` INNER JOIN `tb_sumario` ON `tb_sumario`.`id_sumario` = `tb_ocorrencia`.`fk_sumario` INNER JOIN `tb_ic` ON `tb_ic`.`id_ic` = `tb_ocorrencia`.`fk_ic` WHERE `incidente` = '{$incidente}'");

        if($sql->execute()){

            foreach ($sql->fetchAll(\PDO::FETCH_NUM) as $dado){
                $result[] = $dado;
            }

            if (isset($result)) {
                return $result;
            }
        }else{
            return $sql->errorInfo();
        }

    }

}

?>