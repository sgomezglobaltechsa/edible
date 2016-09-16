<?php
class edible
{
    //=======================================================================================================
    //Declaracion de variables.
    //=======================================================================================================    
    private $service_url                    ="https://transapi.edible-online.com/api/login";
    private $service_PurchaseOrders         ="https://transapi.edible-online.com/Api/PurchaseOrders";
    private $service_PurchaseOrdersDetail   ="https://transapi.edible-online.com/Api/PurchaseOrders/";
    private $service_PurchaseOrderLock      ="https://transapi.edible-online.com/Api/PurchaseOrders/";
    private $service_PurchaseOrderUnLock    ="https://transapi.edible-online.com/Api/PurchaseOrders/unlock/";
    private $service_PurchaseOrderReceive   ="https://transapi.edible-online.com/Api/PurchaseOrders/receive";
    private $Usr="";
    private $password="";
    private $token="";
    //=======================================================================================================    
    //Declaracion de Propiedades.
    //=======================================================================================================
    public function SetUsuario($value){
        $this->Usr=$value;
    }
    public function GetUsuario(){
        return $this->Usr;
    }
    public function SetPassword($value){
        $this->password=$value;
    }
    public function GetPassword(){
        return $this->password;
    }
    public function GetTokenID(){
        return $this->token;
    }
    public function SetTokenID($value){
        $this->token=$value;
    }    
    //=======================================================================================================
    //Declaracion de metodos.
    //=======================================================================================================    
    public function GetToken(&$error){
        try{        
            $findme   ='token';
            $findusr  ='username';
            $pos = 0;
            $posusr=0;
            $token="";        
            $curl = curl_init();
            
            //Parametrizacion del cURL
            curl_setopt_array($curl, array(   CURLOPT_URL => $this->service_url,
                                              CURLOPT_RETURNTRANSFER => true,
                                              CURLOPT_ENCODING => "",
                                              CURLOPT_MAXREDIRS => 10,
                                              CURLOPT_TIMEOUT => 30,
                                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                              CURLOPT_CUSTOMREQUEST => "POST",
                                              CURLOPT_HEADER => 1,
                                              CURLOPT_POSTFIELDS => "{\n    \"username\": \"".$this->Usr."\",\n    \"password\": \"".$this->password."\"\n}",
                                              CURLOPT_HTTPHEADER => array(  "cache-control: no-cache",
                                                                            "content-type: application/json",
                                                                            "token: b9e6f353-c40e-5d4a-fb3a-e92d820db6cc"),
                                ));
        
            //Ejecucion del curl
            $response = curl_exec($curl);

            //recupero los datos del header.
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            $json_dec=json_decode($response);
            $hdec=json_decode($header,true);
            $err = curl_error($curl);
            $pos=strpos($response, $findme);
            $posusr=strpos($response,$findusr);
            $token=substr($response,$pos,($posusr-$pos)-2);
            
            //Le saco toda la basura al token.
            $token=str_replace("token","",$token);
            $token=str_replace(":","",$token);
            $token=str_replace('"','',$token);
            
            curl_close($curl);
            
            if ($err) {
                $error= "cURL Error #:" . $err;
            } else {
                $this->token=$token;
                return true;
            }
        }catch (Exception $e) {
            $error= 'Excepción capturada: '.$e->getMessage()."\n";
            return false;
        }//fin: try         
    }   
    //=======================================================================================================
    public function GetPO_Orders(&$vPO, &$verror){
        try{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->service_PurchaseOrders,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_POSTFIELDS => "{\n    \"username\": \"".$this->Usr."\",\n    \"password\": \"".$this->password."\"\n}",
              CURLOPT_HTTPHEADER => array(
                "authorization: ".$this->token,
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                $verror=$err;
                return false;
            } else {
                $vPO= $response;
                return true;
            }       
        }catch (Exception $e) {
            $verror= 'Excepción capturada: '.$e->getMessage()."\n";
            return false;
        }//fin: try              
    }
    //=======================================================================================================
    public function GetPO_OrderDetail($PONumber, &$vPODetail, &$verror){
        try{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->service_PurchaseOrdersDetail.$PONumber,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_POSTFIELDS => "{\n    \"username\": \"".$this->Usr."\",\n    \"password\": \"".$this->password."\"\n}",
              CURLOPT_HTTPHEADER => array(
                "authorization: ".$this->token,
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                $verror=$err;
                return false;
            } else {
                $vPODetail= $response;
                return true;
            }   
        }catch (Exception $e) {
            $verror= 'Excepción capturada: '.$e->getMessage()."\n";
            return false;
        }//fin: try             
    }
    //=======================================================================================================       
    public function PO_lockPurchaseOrder($PONumber, &$verror){
        try{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->service_PurchaseOrderLock.$PONumber,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => "{\n    \"username\": \"".$this->Usr."\",\n    \"password\": \"".$this->password."\"\n}",
              CURLOPT_HTTPHEADER => array(
                "authorization: ".$this->token,
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            echo "Retorno Lock</br>";
            var_dump(json_decode($response));
            
            if ($err) {
                
                $verror=$err;
                
                return false;
                
            } else {
                                
                return true;
            }   
        }catch (Exception $e) {
            $verror= 'Excepción capturada: '.$e->getMessage()."\n";
            return false;
        }//fin: try             
    }    
    //=======================================================================================================       
    public function PO_UnlockPurchaseOrder($PONumber, &$verror){
        try{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->service_PurchaseOrderUnLock.$PONumber,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => "{\n    \"username\": \"".$this->Usr."\",\n    \"password\": \"".$this->password."\"\n}",
              CURLOPT_HTTPHEADER => array(
                "authorization: ".$this->token,
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                
                $verror=$err;
                
                return false;
                
            } else {
                
                //echo "Retorno PO_UnlockPurchaseOrder </br>";
                
                //var_dump(json_decode($response));
                
                return true;
                
            }   
        }catch (Exception $e) {
            
            $verror= 'Excepción capturada: '.$e->getMessage()."\n";
            
            return false;
            
        }//fin: try             
    }    
    
    public function EnviarOrdenesRecibidas($data, $verror){
       try{
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $this->service_PurchaseOrderReceive,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "PUT",
              CURLOPT_POSTFIELDS => json_encode($data),
              CURLOPT_HTTPHEADER => array(
                "authorization: ".$this->token,
                "cache-control: no-cache",
                "content-type: application/json"
              ),
            ));
            
            $response = curl_exec($curl);
            
            $err = curl_error($curl);
            
            curl_close($curl);
            
            echo json_encode($response);
            echo "</br>";
            
            if ($err) {
              echo "cURL Error #:" . $err;
              return false;
            } else {
              return true;
            }        
       }catch(Exception $e){
            $verror= 'Excepción capturada: '.$e->getMessage()."\n";
            return false;        
       }
    }   
}
?>