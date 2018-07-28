<?php

    require_once "config.php";

    $read = new ReadBD();
    $manipular = new ManipulacaoDados();

?>


    <div id="tabs">

        <ul>

            <?php

            foreach($read->filterDataReturnModal(new DateTime('now'),$_POST) as $key => $value){
                echo "<li><a href='#tabs-$key'>$value[0]</a></li>";
            }

            ?>

        </ul>
            <?php

            foreach($read->filterDataReturnModal(new DateTime('now'),$_POST) as $key => $value){

                if (count(explode(".", $value[5])) == 3) {

                    $sumario = explode(".", $value[5]);

                } else {

                    $sumario = $value[5];

                }

                $criado = DateTime::createFromFormat("Y-m-d H:i:s",$value[6]);
                $resolucao = DateTime::createFromFormat("Y-m-d H:i:s",$value[7]);

                echo "<div id='tabs-$key' class='tabsClass'>
                        <div class='container-fluid'>
                            <div class='row'>";

                echo $manipular->inputModalData("sistema_".$value[0],'col-6','col-sm-2','col-sm-10',is_array($sumario)?$sumario[1]:$sumario,"Sistema");

                echo $manipular->inputModalData("ic_".$value[0],'col-6','col-sm-2','col-sm-10',$value[4],"IC");

                echo $manipular->inputModalData("falha_".$value[0],'col-6','col-sm-2','col-sm-10',is_array($sumario)?$sumario[2]:$sumario,"Falha");

                echo $manipular->inputModalData("sumario_".$value[0],'col-6','col-sm-2','col-sm-10',$value[5],"Sumário");

                echo $manipular->inputModalData("criado_".$value[0],'col-6','col-sm-2','col-sm-10',$criado->format('d/m/Y H:i:s'),"Criado em");

                echo $manipular->inputModalData("resolvido_".$value[0],'col-6','col-sm-2','col-sm-10',$resolucao->format('d/m/Y H:i:s'),"Resolvido em");

                echo $manipular->inputModalData("duracao_".$value[0],'col-6','col-sm-2','col-sm-10',$read->funcDuracao($value[8]),"Duração");

                echo $manipular->inputModalData("atraso_".$value[0],'col-6','col-sm-2','col-sm-10',$read->dateDuracaoOcorrencia($value[8],$value[2]),"Atraso");

                echo $manipular->textareaModalData("descricao_".$value[0],'col-6','col-sm-2','col-sm-10',$value[9],"Descrição");

                echo $manipular->textareaModalData("resolucao_".$value[0],'col-6','col-sm-2','col-sm-10',$value[10],"Resolução");

                echo "</div>
                        </div>
                      </div>";
            }

            ?>



    </div>
