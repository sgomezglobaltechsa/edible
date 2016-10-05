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

//=======================================================================================================
//Declaracion de funciones.
//=======================================================================================================
function limpiarString($texto)
{
      $textoLimpio = preg_replace('([^A-Za-z0-9-"])', '', $texto);	     					
      return $textoLimpio;
}

//---------------------------------------------------------------------------------
//Instancia de objetos.
//---------------------------------------------------------------------------------
$edi = new edible();
$dpt = new depotwms_ing();
$gen = new generales();

$edi->SetUsuario($login);
$edi->SetPassword($password);
$edi->SetSession($session);
$edi->SetDpt($dpt);
$dpt->Session($session);



//================================================================
$gen->IDebug(0);    //0. No quiero ver los printsc.
                    //1. Quiero ver los printsc.

//================================================================
//Obtengo token
if ($edi->GetToken($verror) == false) {

    $dpt->GuardarLog("PO-EDIBLE-INGRESO", "GetToken", "ERR",
        "Ocurrio un error al recuperar el Token de seguridad. ", "");

} else {

    $gen->printsc("=============================================================", true);

    $gen->printsc("Token obtenido: " . $edi->GetTokenID(), true);

} //fin: if($edi->GetToken($verror)


//=================================================================================
//Obtengo las po del w.s. Rest.
//=================================================================================
if ($edi->GetPO_Orders($POrders, $verror) == false) {

    $dpt->GuardarLog("PO-EDIBLE-INGRESO", "GetPO_Orders", "ERR",
        "Ocurrio un error al recuperar las PO. ", "");

} else {

    $POrders = json_decode($POrders);

    //Limpio la tabla de los registros_procesados.
    $dpt->clsTf_po_proc();
    
    //Loop de las po
    for ($i = 0; $i < $POrders; $i++) {

        try {

            $poNumber = $POrders[$i]->poNumber;
            $poVendorId = $POrders[$i]->vendorId;
            $poCompany = $POrders[$i]->company;

            $gen->printsc("=============================================================", true);
            $gen->printsc('poNumber: ' . $poNumber, true);
            $gen->printsc('poVendorId: ' . $poVendorId, true);
            $gen->printsc('poCompany: ' . $poCompany, true);
  
            //obtengo el detalle del po.
            if ($edi->GetPO_OrderDetail($poNumber, $PODetail, $verror) == true) {

                $PODetail = json_decode($PODetail, true);

                foreach ($PODetail["purchaseOrderLines"] as $p) {

                    $gen->printsc('------------------------------------------------------------', true);
                    $gen->printsc('Detalle PO: ', true);
                    $gen->printsc('------------------------------------------------------------', true);
                    $gen->printsc('<blockquote>', true);
                    $gen->printsc(' poDetailID: ' . $p["poDetailId"], true);
                    $gen->printsc(' poLineNumber: ' . $p["poLineNumber"], true);
                    $gen->printsc(' itemCode: ' . $p["itemCode"], true);
                    $gen->printsc(' itemDescription: ' . $p["itemDescription"], true);
                    $gen->printsc(' orderQuantity: ' . $p["orderQuantity"], true);
                    $gen->printsc(' orderUom: ' . $p["orderUom"], true);
                    $gen->printsc(' weight: ' . $p["weight"], true);
                    $gen->printsc(' weightUom: ' . $p["weightUom"], true);
                    $gen->printsc('</blockquote>', true);
                    
                    $ret=$dpt->PO_InsertPurchaseOrder(  $poCliente,     $poNumber, $poVendorId, $poCompany,         $p["poDetailId"],   $p["poLineNumber"],
                                                        $p["itemCode"], $p["itemDescription"],  $p["orderQuantity"],$p["orderUom"],     $p["weight"],
                                                        $p["weightUom"],$verror);

                    if ($ret == false) {

                        $dpt->GuardarLog("PO-EDIBLE-INGRESO", "PO_INSERT", "ERR", $verror, $poNumber);

                    } //fin: if ($ret==false)

                } //fin:  foreach($PODetail["purchaseOrderLines"] as $p)
                $edi->PO_UnlockPurchaseOrder($poNumber, $verror);
                
                if ($edi->PO_lockPurchaseOrder($poNumber, $verror) == false) {

                    $dpt->GuardarLog("PO-EDIBLE-INGRESO", "PO_lockPurchaseOrder", "ERR", $verror, $poNumber);

                } else {

                    $gen->printsc(' PO_lockPurchaseOrder: OK', true);

                } //fin: ($edi->PO_lockPurchaseOrder($poNumber, $verror)==false)
                
            } else {

                $dpt->GuardarLog("PO-EDIBLE-INGRESO", "GetPO_OrderDetail", "ERR", $verror, $poNumber);

            } //fin: if($edi->GetPO_OrderDetail($poNumber,$PODetail,$verror)==true)

            unset($PODetail);

        }
        catch (exception $e) {

            echo 'Excepción capturada: ', $e->getMessage(), "\n";

        } //fin: try
        
        //Para debug, interrupcion de la interfaz para desarrollo.
   
        if ($i == 1) {

            break;

        } //fin: if($i==1)
        
    } //fin: for($i=0; $i<$POrders; $i++)

} //fin: if($edi->GetPO_Orders($POrders, $verror)==false)

$dpt->clsTf_po_NoProcesadas($poCliente);
//=======================================================================================================
//Destruccion de objetos.
//=======================================================================================================
unset($edi);

unset($dpt);

unset($gen);

echo "##FIN_PROCESO##";

?>