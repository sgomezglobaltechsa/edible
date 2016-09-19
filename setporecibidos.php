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

$edi->SetDpt($dpt);

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

            $lineId=$row ["lineID"];
            $poNumber=$row ["poNumber"];
            $quant=$row ["quantity"];
            $receiveUom=$row ["receiveUom"];
            $weight=$row ["weight"];
            $weightUom=$row ["weightUom"];
            $date=(string)$row ["date"];
            /*{==============================================================================
              "lineId": "00531937",
              "poNumber": "023794",
              "quantity": 935,
              "receiveUom": "CS",
              "weight": 0,
              "weightUom": "CS",
              "date": "2016-09-16T09:37:58.2400000+00:00"
            } ===============================================================================*/
            $datos="{\r\n  \"lineId\": \"".$lineId."\",\r\n \"poNumber\": \"".$poNumber."\",\r\n \"quantity\": ".$quant.",\r\n \"receiveUom\": \"".$receiveUom."\",\r\n \"weight\": ".$weight.",\r\n \"weightUom\": \"".$weightUom."\",\r\n \"date\": \"".$date."\"\r\n}";
            
            //Me aseguro que este lockeada la PO para evitar algun posible error.
            $edi->PO_lockPurchaseOrder($poNumber, $verror);
            
            $ret=(boolean)$edi->EnviarOrdenesRecibidas($datos, $verror);
            
            if ($ret=="1"){
                
                $dpt->MarcarDevolucion($poCliente, $lineId);
                
                $dpt->GuardarLog("PO-EDIBLE-DEVOLUCION", "EnviarOrdenesRecibidas", "OK", $lineId, $poNumber);
                
            }else{
                
                $dpt->GuardarLog("PO-EDIBLE-DEVOLUCION", "EnviarOrdenesRecibidas", "ERR", $verror, $poNumber);
                
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

?>