<?php
    require_once "../class/BDModal.php";
    $read = new BDModal();
    /*$read->printr($_POST);
    $read->printr($read->BD_dados_modal($_POST[0][0]));*/
?>

<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <div id="h4ModalTitulo" class="container-fluid"></div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <table id="tableModalInfo" class="table table-striped table-bordered center-all" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>INCIDENTE</th>
                            <th>SUMÁRIO</th>
                            <th>IC</th>
                            <th>CRIADO</th>
                            <th>DATA DA ÚLTIMA RESOLUÇÃO</th>
                            <th>RESOLVIDO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($_POST[0] as $tr){
                                $dado = $read->BD_dados_modal($tr);
                                echo "<tr>";
                                        foreach ($dado[0] as $d){
                                            echo "<th>$d</th>";
                                        }
                                 echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

</div>