<!doctype html>
<html lang="pt-br">

<?php

    require_once "constants.php";

    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');

    $titulo = "PAINEL INCIDENTES - ".utf8_encode(strtoupper(strftime("%B")));

?>

<head>
    <title>Painel Incidentes - Criticidade</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= LINK ?>lib/DataTables/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= LINK ?>lib/font-awesome/web-fonts-with-css/css/fontawesome-all.min.css">
    <link rel="stylesheet" href="<?= LINK ?>lib/jqueryconfirm/jquery-confirm.min.css">
    <link rel="stylesheet" href="<?= LINK ?>lib/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?= LINK ?>css/estilo.css">
</head>


<body>

    <div id="loader"></div>

    <div id="bodyDiv" class="container-fluir">
                
        <table id="tablePainelIncidente" class="table table-striped table-bordered" width="100%" cellspacing="0">

            <thead>

                <tr>

                    <td COLSPAN="14">

                        <h2><?= $titulo ?></h2>

                    </td>

                </tr>

                <tr>

                    <td rowspan="2"></td>

                    <td rowspan="2">GRUPOS</td>

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

        </table>

        <!-- Modal -->
        <div class="modal fade" id="modalDataPainel" tabindex="-1" role="dialog" aria-labelledby="modalDataPainelTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <img id="imgModalClose" src="img/error.png" data-dismiss="modal" alt="">
                    <div class="modal-body"></div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="<?= LINK ?>lib/DataTables/js/jquery.dataTables.min.js"></script>
    <script src="<?= LINK ?>lib/DataTables/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?= LINK ?>lib/jqueryconfirm/jquery-confirm.min.js"></script>
    <script src="<?= LINK ?>lib/jquery-ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?= LINK ?>js/script.js"></script>
</body>
</html>