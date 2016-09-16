<?php
//=======================================================================================================
//segmento de includes.
//=======================================================================================================

include 'edible.php';

include 'depotwms.php';

include 'generales.php';

//=======================================================================================================
//Declaracion de variables.
//=======================================================================================================

$login = 'API';

$password = 'TNF@pi1234';

$ptoken = "";

$verror = "";

$poNumber = "";

$poVendorId = "";

$poCompany = "";

$poCliente = "PAPIER";

$session = uniqid();

$num=0;

//=======================================================================================================
//Instancia de objetos.
//=======================================================================================================

$edi = new edible();

$dpt = new depotwms_ing();

$gen = new generales();

$edi->SetUsuario($login);

$edi->SetPassword($password);

$dpt->Session($session);

$json = array();

$gen->IDebug(0);
//================================================================
//Obtengo token
//================================================================
if ($edi->GetToken($verror) == false) {
    
    $dpt->GuardarLog("PO-EDIBLE-DEVOLUCION", "GetToken", "ERR", "Ocurrio un error al recuperar el Token de seguridad. ", "");
    
} else {
    
    $gen->printsc("=============================================================", true);
    
    $gen->printsc("Token obtenido: " . $edi->GetTokenID(), true);
    
} //fin: if($edi->GetToken($verror)

$retorno=$dpt->GetIngresosAInformar($poCliente, $rdata, $verror);

if ($retorno==true){

    do {
        
         while ($row = sqlsrv_fetch_array($rdata, SQLSRV_FETCH_ASSOC)) {
            
            //$json[] = $row;
            
            $lineId=$row ["lineID"];
            
            $gen->printsc($lineId, true);
            
            if ($edi->EnviarOrdenesRecibidas($row, $verror)==true){
                
                $dpt->MarcarDevolucion($poCliente, $lineId);
                
            }else{
                
                $dpt->GuardarLog("PO-EDIBLE-DEVOLUCION", "EnviarOrdenesRecibidas", "ERR", $verror, "");
            }
            
            $num++;
            
         }
         
    } while ( sqlsrv_next_result($rdata) );   
    
}

//=======================================================================================================
//Destruccion de objetos.
//=======================================================================================================

unset($edi);

unset($dpt);

unset($gen);

echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
$this->
?>