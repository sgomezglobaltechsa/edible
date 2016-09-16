<?php
class generales{
    
    private $idebug="0";
    
    //=======================================================================================================        
    public function IDebug($value){
        $this->idebug=$value;
    }
    //=======================================================================================================    	
    public function printsc($value, $salto){
        if($this->idebug=="1"){
            echo $value;
            if($salto==true){
                echo '</br>';
            }  
        }
    }
}
?>