<?php
/*
*   Classe CURL. 
*   
*   @author: Jaume Cabrer
*/
class curl
{
	protected $urlCurl; 
    protected $parameters; 
    protected $post;
    protected $return_transfer;
    protected $timeoutType;
    protected $timeout;
    protected $httpHeader;
    protected $freshConnect;

    public function __construct($urlCurl, $parameters, $post=true, $timeoutType='s', $timeout=1, $return_transfer=true, $httpHeader='', $freshConnect=false)
    {
        $this->urlCurl          = $urlCurl;
        $this->parameters       = $parameters;
        $this->post             = $post;
        $this->return_transfer  = $return_transfer; 
        $this->timeoutType 	    = $timeoutType;
        $this->timeout          = $timeout;
        $this->httpHeader       = $httpHeader;
        $this->freshConnect     = $freshConnect;
     }

    /*
    *   FX que ejecuta el CURL
    *   
    *   Return:
    *       @result (array)
    *       @result[curl_result] (array) Resultado, si lo hay, que devuelve la ejecución del CURL
    *       @result[appConnectTime] (float) segundos que han transurrido desde el inicio hasta connect/handshake con el host remoto. 0 si no se ha podido conectar
    *       @result[error] (string) Mensaje de error, si lo hay, que devuelve el CURL 
    */
    public function curlExecute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->urlCurl);
        curl_setopt($ch, CURLOPT_POST, $this->post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->parameters); 

        if(!empty($this->httpHeader))
            curl_setopt($ch,CURLOPT_HTTPHEADER, $this->httpHeader);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->return_transfer);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, $this->freshConnect);
        
        if($this->timeoutType=='s')
        {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);   //seconds
        }else{
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->timeout);//miliseconds
        }    
        $result['curl_result'] = curl_exec($ch);
        $result['appConnectTime'] = curl_getinfo($ch, CURLINFO_APPCONNECT_TIME);
        $result['error'] = curl_error($ch);

        curl_close($ch);

        return $result;
    }
}

/*
*   Fx para mandar asincronamente a través de CURL datos a un webservice. 
*   Para evitar esperar que hasta que acabe la ejecución de un script de PHP, utilizamos este metedo para poder devolver el control al usuario.
*
*   Params:
*   @urlCurl (string) url del webservice al que se le manda el CURL
*   @parameters (string) string de parametros (http_build_query)
*
*   Se establece un timeout de 100ms. 
*   Si la conexión no se ha podido establecer en ese periodo de tiempo (el result de curlExecute devuelve appConnectTime=0), el WS que recibe la llamada 
*   no realizará las operaciones necesarias
*/
class curlAsync extends curl
{
	public function __construct($urlCurl, $parameters)
    {
        static $post=true; 
        static $timeoutType='ms'; //miliseconds
        static $timeout=150;
        parent::__construct($urlCurl, $parameters, $post, $timeoutType, $timeout);
    }
}
?>