<?php require_once "inc/header.php"; ?>
    <body>

        <?php

            require_once "config.php";

            $excel = new InsertBD();

            echo "<pre>";
            print_r($excel->insertData());
            echo "</pre>";
            exit;

        ?>
        <div id="loader"></div>

        <div id="bodyDiv" class="container-fluir">
                
                <table id="tablePainelIncidente" class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th COLSPAN="13">
                                <h2>PAINEL INCIDENTES - <?= utf8_encode(strtoupper(strftime("%B"))) ?></h2><a id="buttonReadBaseAtual" class="btn btn-outline-success" href="leituraBaseXML.php?a=true" target="_blank" role="button" aria-pressed="true"><i class="fa fa-upload" aria-hidden="true"></i> UPDATE</a>
                            </th>
                        </tr>

                        <tr>
                            <td rowspan="2">EMPRESAS</td>
                            <td colspan="2">BAIXO</td>
                            <td colspan="2">MÉDIA</td>
                            <td colspan="2">ALTO</td>
                            <td colspan="2">CRÍTICO</td>
                            <td colspan="2">TOTAL</td>
                            <td colspan="2">%</td>
                        </tr>



                            <tr id="trPrazoVencido">
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                                <th><a class="btn btn-primary">NO PRAZO</a></th>
                                <th><a class="btn btn-danger">VENCIDO</a></th>
                            </tr>

                    </thead>

                    <tbody>
                        <?php

                        $grupo = $read->col_grupo_empresa();
                        $col = array(0,0,0,0,0,0,0,0,0,0,0,0);
                        foreach ($grupo as $k => $gp){
                            $ex = explode("*",$gp);
                            $troca = str_replace("*", "-", $gp);

                            $td[0] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","NOPRAZO"));
                            $td[1] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","VENCIDO"));
                            $td[2] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","NOPRAZO"));
                            $td[3] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","VENCIDO"));
                            $td[4] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","NOPRAZO"));
                            $td[5] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","VENCIDO"));
                            $td[6] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","NOPRAZO"));
                            $td[7] = count($read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","VENCIDO"));

                            $trocaId = str_replace("*", "&", $gp);

                            $id = array(
                                $trocaId."_Baixo_NOPRAZO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","NOPRAZO")),
                                $trocaId."_Baixo_VENCIDO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Baixo",$ex[0]),"Baixo","VENCIDO")),
                                $trocaId."_Média_NOPRAZO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","NOPRAZO")),
                                $trocaId."_Média_VENCIDO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Média",$ex[0]),"Média","VENCIDO")),
                                $trocaId."_Alto_NOPRAZO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","NOPRAZO")),
                                $trocaId."_Alto_VENCIDO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Alto",$ex[0]),"Alto","VENCIDO")),
                                $trocaId."_CríticoNO_PRAZO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","NOPRAZO")),
                                $trocaId."_Crítico_VENCIDO_*".implode("|",(array) $read->incidenteGrupoNoPrazoVencido($read->incidentesPrioridadeGrupo("Crítico",$ex[0]),"Crítico","VENCIDO"))
                            );

                            $tot[0] = $td[0]+$td[2]+$td[4]+$td[6];
                            $tot[1] = $td[1]+$td[3]+$td[5]+$td[7];

                            $porcetagem[0] = round(($tot[0]/$read->totalIncidente())*100,2);
                            $porcetagem[1] = round(($tot[1]/$read->totalIncidente())*100,2);

                            $col[0] += $td[0];
                            $col[1] += $td[1];
                            $col[2] += $td[2];
                            $col[3] += $td[3];
                            $col[4] += $td[4];
                            $col[5] += $td[5];
                            $col[6] += $td[6];
                            $col[7] += $td[7];
                            $col[8] += $tot[0];
                            $col[9] += $tot[1];
                            $col[10] += $porcetagem[0];
                            $col[11] += $porcetagem[1];

                            echo "<tr>
                                        <th>$troca</th>
                                        <th><a class='btn btn-primary aOpcao' id='$id[0]'>$td[0]</a></th>
                                        <th><a class='btn btn-danger aOpcao' id='$id[1]'>$td[1]</a></th>
                                        <th><a class='btn btn-primary aOpcao' id='$id[2]'>$td[2]</a></th>
                                        <th><a class='btn btn-danger aOpcao' id='$id[3]'>$td[3]</a></th>
                                        <th><a class='btn btn-primary aOpcao' id='$id[4]'>$td[4]</a></th>
                                        <th><a class='btn btn-danger aOpcao' id='$id[5]'>$td[5]</a></th>
                                        <th><a class='btn btn-primary aOpcao' id='$id[6]'>$td[6]</a></th>
                                        <th><a class='btn btn-danger aOpcao' id='$id[7]'>$td[7]</a></th>
                                        <th><a class='btn btn-primary'>$tot[0]</a></th>
                                        <th><a class='btn btn-danger'>$tot[1]</a></th>
                                        <th><a class='btn btn-primary'>$porcetagem[0]%</a></th>
                                        <th><a class='btn btn-danger'>$porcetagem[1]%</a></th>
                                 </tr>";
                        }

                        ?>
                    </tbody>

                    <tfoot>
                            <tr id="trTotal">
                                <th>TOTAL</th>
                                <th><a class='btn btn-primary'><?= isset($col[0])?$col[0]:0 ?></a></th>
                                <th><a class='btn btn-danger'><?= isset($col[1])?$col[1]:0 ?></a></th>
                                <th><a class='btn btn-primary'><?= isset($col[2])?$col[2]:0 ?></a></th>
                                <th><a class='btn btn-danger'><?= isset($col[3])?$col[3]:0 ?></a></th>
                                <th><a class='btn btn-primary'><?= isset($col[4])?$col[4]:0 ?></a></th>
                                <th><a class='btn btn-danger'><?= isset($col[5])?$col[5]:0 ?></a></th>
                                <th><a class='btn btn-primary'><?= isset($col[6])?$col[6]:0 ?></a></th>
                                <th><a class='btn btn-danger'><?= isset($col[7])?$col[7]:0 ?></a></th>
                                <th><a class='btn btn-primary'><?= isset($col[8])?$col[8]:0 ?></a></th>
                                <th><a class='btn btn-danger'><?= isset($col[9])?$col[9]:0 ?></a></th>
                                <th><a class='btn btn-primary'><?= isset($col[10])?$col[10]:0 ?>%</a></th>
                                <th><a class='btn btn-danger'><?= isset($col[11])?$col[11]:0 ?>%</a></th>
                            </tr>
                    </tfoot>
                </table>
            </div>
    <div id="ajaxReturn"></div>
<?php require_once "inc/footer.php"; ?>