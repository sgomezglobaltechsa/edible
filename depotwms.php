<?php

class depotwms_ing
{
    //==============================================================================================================================
    //METODOS Y VARIABLES PARA ACCEDER A LA BASE DE DATOS SQL SERVER.
    //==============================================================================================================================    
    private $_servidor = "RAMALLO-01";
    private $_usuario_db = "UGTWV400";
    private $_pwd_db = "DOBLEFALTA";
    private $_nombre_db = "WMS_DESARROLLO_V9X";
    private $_conn = null;
    private $_metodo;
    private $_argumentos;
    private $_session;
    
    //==============================================================================================================================   
    function __destruct() 
    {
       sqlsrv_close($this->_conn);     
    }
    //==============================================================================================================================
    public function __construct()
    {
        $this->conectarDB();
    }
    //==============================================================================================================================
    public function Session($value){
        $this->_session=$value;
    }    
    //==============================================================================================================================
    private function conectarDB()
    {
        $connectionInfo = array( "UID"=>$this->_usuario_db,"PWD"=>$this->_pwd_db,"Database"=>$this->_nombre_db);
        
        try {
            
            $this->_conn = sqlsrv_connect( $this->_servidor, $connectionInfo);
            
            if( $this->_conn === false ){
                
                echo "No es posible conectarse al servidor.</br>";
                
                die( print_r( sqlsrv_errors(), true));
                
            }else{
                
                return true;
                
            }
        }catch (PDOException $e) {
            
            echo 'Falló la conexión: ' . $e->getMessage();
            
        }
    }
    //==============================================================================================================================
    private function PO_ExisteCabecera($poCliente, $poNumber, &$verror)
    {
        try {
            
            $retorno="0";
            
            $ret=0;
            
            $xsql="select count(*) ctn from sys_int_det_documento where cliente_id= (?) and doc_ext= (?)";
            
            $param=array($poCliente,$poNumber);
            
            $stmt = sqlsrv_query($this->_conn, $xsql, $param);
            
            if( $stmt === false) {
                
                die( print_r( sqlsrv_errors(), true) );
                
            }

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                
                $ret= (int)$row["ctn"];
                
            }      

            if($ret==0){
                
                $retorno = "0";//false;
                
            }else{
                
                $retorno = "1";//true;
                
            }
            
            sqlsrv_free_stmt($stmt);
            
            return $retorno;
            
        }//fin: try
        catch (exception $e) {
            
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            
            return false;
            
        } //fin: try
    }
    //==============================================================================================================================
    public function PO_InsertCabecera($poCliente, $poNumber, $poVendorId, $poCompany, &$verror)
    {
        $tipo="I01";
        
        try {
            
            $mret=$this->PO_ExisteCabecera($poCliente,$poNumber,$verror);

            if($mret=="1"){
                
                return false;
                
            }else{
                
                $params=array(&$poCliente, &$poNumber, &$poVendorId, &$poCompany, &$this->_session);

                $query= $this->_nombre_db.".DBO.TRANS_EDI_INS_CABECERA ?, ?, ?, ?, ?";

                $result = sqlsrv_query($this->_conn, $query, $params);
                
                if( !$result ) {
                    
                    die( print_r( sqlsrv_errors(), true));
                    
                }    
                
                return true;
            }
      
        }
        catch (exception $e) {
            
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            
            return false;
            
        } //fin: try
    }
    //==============================================================================================================================
    public function PO_InsertDetalle($poCliente, $poNumber, $poDetailId, $poLineNumber, $itemCode,
        $itemDescription, $orderQuantity, $orderUom, $weight, $weightUom, &$verror)
    {
        try {

            $params=array(  &$poCliente,&$poNumber,&$poDetailId,&$poLineNumber,&$itemCode,&$itemDescription,&$orderQuantity,
                            &$orderUom,&$weight,&$weightUom,&$this->_session);

            $query= $this->_nombre_db.".DBO.TRANS_EDI_INS_DETALLE ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

            $result = sqlsrv_query($this->_conn, $query, $params);
            
            if( !$result ) {
                
                die( print_r( sqlsrv_errors(), true));
                
            }           
            
            return true;
        }
        catch (exception $e) {
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            return false;
        } //fin: try
    }
    //==============================================================================================================================
    public function GetIngresosAInformar($cliente_id, &$data, &$verror){
        try { 
            
            $query = "select lineID, poNumber, quantity, receiveUom, weight, weightUom, date  from dbo.view_trans_edi_recepciones where poClienteId=?";
            
            $params=array($cliente_id);
            
            $data = sqlsrv_query( $this->_conn, $query, $params);
            
            if( $data === false) {
                
                die( print_r( sqlsrv_errors(), true) );
                
            }
            /*
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
                  echo $row[0].", ".$row[1]."<br />";
            }*/
            
            return true;
        }
        catch (exception $e) {
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            return false;
        } //fin: try               
    }
    //==============================================================================================================================
    public function GuardarLog($proceso, $funcion, $estado, $error, $ref1){
        try {

            $params=array($proceso, $funcion, $estado, $error, $ref1, &$this->_session);

            $query= $this->_nombre_db.".DBO.INS_LOG_PROCESO ?, ?, ?, ?, ?, ?";

            $result = sqlsrv_query($this->_conn, $query, $params);
            
            if( !$result ) {
                
                die( print_r( sqlsrv_errors(), true));
                
            }           
            
            return true;
        }
        catch (exception $e) {
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            return false;
        } //fin: try            
    }  
    //==============================================================================================================================
    public function MarcarDevolucion($cliente_id, $customs_1){
        try {

            $params=array($cliente_id, $customs_1);

            $query= "update sys_dev_det_documento set flg_movimiento='1' where cliente_id=? and customs_1=?";

            $result = sqlsrv_query($this->_conn, $query, $params);
            
            if( !$result ) {
                
                die( print_r( sqlsrv_errors(), true));
                
            }           
            
            return true;
        }
        catch (exception $e) {
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            return false;
        } //fin: try            
    }      
    //==============================================================================================================================
    public function PO_InsertPurchaseOrder($poCliente, $poNumber, $poVendorId, $poCompany,$poDetailId, $poLineNumber, $itemCode,
        $itemDescription, $orderQuantity, $orderUom, $weight, $weightUom, &$verror)
    {
        try {

            $params=array(  &$poCliente, &$poNumber, &$poVendorId, &$poCompany,&$poDetailId,&$poLineNumber,
                            &$itemCode,&$itemDescription,&$orderQuantity,&$orderUom,&$weight,&$weightUom,&$this->_session);

            $query= $this->_nombre_db.".DBO.TRANS_EDI_INS_PURCHASE_ORDER ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";

            $result = sqlsrv_query($this->_conn, $query, $params);
            
            if( !$result ) {
                
                die( print_r( sqlsrv_errors(), true));
                
            }           
            
            return true;
        }
        catch (exception $e) {
            $verror = 'Excepción capturada: ' . $e->getMessage() . "\n";
            return false;
        } //fin: try
    }       
}

?>