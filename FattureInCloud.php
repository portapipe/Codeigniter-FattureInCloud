<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter FattureInCloud
 *
 * Interfaccia API di FattureInCloud.it per Codeigniter
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          portapipe
 * @license         MIT License
 * @link            https://github.com/portapipe/Codeigniter-FattureInCloud */


class FattureInCloud
{

    private $ci;
    public $api_uid;
    public $api_key;
    private $url = "https://api.fattureincloud.it/v1/richiesta/info";

    public $richiesta = array();

    function __construct($config = array()){
        //Istanza di codeigniter
        $this->ci = get_instance();

        if(sizeof($config)>0){
            foreach($config as $k=>$v){
                $this->{$k} = $v;
            }
        }
    }

    public function info(){
        $this->url = "https://api.fattureincloud.it/v1/richiesta/info";
        $this->crea();
    }
    public function crea(){

        $richiesta['api_uid'] = $this->api_uid;
        $richiesta['api_key'] = $this->api_key;

        $options = array(
            "http" => array(
                "header"  => "Content-type: text/json\r\n",
                "method"  => "POST",
                "content" => json_encode($richiesta)
            ),
        );
        $context  = stream_context_create($options);
        $result = json_decode(file_get_contents($this->url, false, $context));
        print_r($result);
        return $result;
    }
}
