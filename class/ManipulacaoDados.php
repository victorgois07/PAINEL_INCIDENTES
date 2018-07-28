<?php

class ManipulacaoDados{

    public function printr($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function inputModalData($id,$col,$collabel,$colInput,$value,$name){
        return "<div class='$col'>
                   <div class='row'>
                       <label for='$id' class='$collabel col-form-label'>$name</label>
                       <div class='$colInput'>
                            <input type='text' class='form-control' id='$id' value='$value' readonly>
                       </div>
                    </div>
               </div>";
    }

    public function textareaModalData($id,$col,$collabel,$colInput,$value,$name){
        return "<div class='$col'>
                   <div class='row'>
                       <label for='$id' class='$collabel col-form-label'>$name</label>
                       <div class='$colInput'>
                            <textarea id='$id' name='$id' class='form-control' cols='30' rows='7' readonly>$value</textarea>
                        </div>
                   </div>
                </div>";
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