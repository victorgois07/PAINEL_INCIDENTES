<?php

class ManipulacaoDados{

    public function printr($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    
    public function htmlModal($array, $id){
        $modalHeader = "<div class='modal fade' id='$id' tabindex='-1' role='dialog' aria-labelledby='exampleModalLongTitle' aria-hidden='true'>
                      <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                          <div class='modal-header'>
                            <h5 class='modal-title' id='exampleModalLongTitle'>Incidentes</h5>
                          </div>";

        $modalBody = "<div class='modal-body'>";
        
        $card = "";
        foreach ($array as $dados) {
            $card .= "<div class='card'>
                      <div class='card-header'>
                        <strong>".$dados[0]."</strong>
                      </div>
                      <div class='card-body'>
                        <h4 class='card-title'>Resolvido</h4>
                        <p class='card-text'>".$dados[1]."</p>
                      </div>
                    </div><br/>";
        }
        
        $modalBody .= $card."</div>";

        $modalFooter = "<div class='modal-footer'>
                            <button type='button' class='btn btn-primary btn-lg btn-block' data-dismiss='modal'>Close</button>
                          </div>
                        </div>
                      </div>
                    </div>";
        
        return $modalHeader.$modalBody.$modalFooter;
    }
}

?>