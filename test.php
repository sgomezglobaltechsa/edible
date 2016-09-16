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
$login      = 'API';
$password   = 'TNF@pi1234';
$ptoken     = "";
$verror     = "";
$poNumber   = "";
$poVendorId = "";
$poCompany  = "";
$poCliente  = "PAPIER";
$session    = uniqid();

$edi = new edible();
$dpt = new depotwms_ing();
$gen = new generales();

$edi->SetUsuario($login);
$edi->SetPassword($password);
$dpt->Session($session);

//================================================================
$gen->IDebug(1);            //0. No quiero ver los printsc.
                            //1. Quiero ver los printsc.
                            
//================================================================
//Seteo Token invalido.
$edi->GetToken($verror);

$gen->printsc('Token Original: '.$edi->GetTokenID(),true);

$gen->printsc("test de correccion de validacion de token.",true);

$edi->SetTokenID("17d4bb17-bb74-46c2-978b-107fff2dc1d5");

$gen->printsc('Token actual: '.$edi->GetTokenID(),true);

$retorno=(boolean)$edi->GetPO_Orders($mdata, $verror);

if ($retorno==true){
    
    $gen->printsc("Todo ok... rarisimo...",true);
    
    var_dump(json_decode($mdata));
    
}else{
    
    $gen->printsc("Data Dump",true);
    
    var_dump(json_decode($mdata));
    
}

?>