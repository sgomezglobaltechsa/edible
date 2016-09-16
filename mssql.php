<?php

class mssql{
    
    private $_servidor = "RAMALLO-01";
    private $_usuario_db = "UGTWV400";
    private $_pwd_db = "DOBLEFALTA";
    private $_nombre_db = "WMS_DESARROLLO_V9X";
    private $_conn = null;

    //------------------------------------------------------------------------------------------------------------------------------    
    function __destruct() {
       sqlsrv_close($this->_conn);     
    }
    //------------------------------------------------------------------------------------------------------------------------------
    public function __construct()
    {
        parent::__construct();
        $this->conectarDB();
    }
    //------------------------------------------------------------------------------------------------------------------------------
    private function conectarDB()
    {
        $connectionInfo = array( "UID"=>$this->_usuario_db,
                                "PWD"=>$this->_pwd_db,
                                "Database"=>$this->_nombre_db);
        try {
            $this->_conn = sqlsrv_connect( $this->_servidor, $connectionInfo);
            if( $this->_conn === false ){
                echo "No es posible conectarse al servidor.</br>";
                die( print_r( sqlsrv_errors(), true));
            }
        }catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
        }
    }
    //------------------------------------------------------------------------------------------------------------------------------
    private function devolverError($id)
    {
        $errores = array(
            array('estado' => "error", "msg" => "petición no encontrada"),
            array('estado' => "error", "msg" => "petición no aceptada"),
            array('estado' => "error", "msg" => "petición sin contenido"),
            array('estado' => "error", "msg" => "email o password incorrectos"),
            array('estado' => "error", "msg" => "error borrando usuario"),
            array('estado' => "error", "msg" => "error actualizando nombre de usuario"),
            array('estado' => "error", "msg" => "error buscando usuario por email"),
            array('estado' => "error", "msg" => "error creando usuario"),
            array('estado' => "error", "msg" => "usuario ya existe"));
        return $errores[$id];
    }
	
}
?>