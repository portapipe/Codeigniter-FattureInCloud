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

    private $articoli = array();
    private $pagamenti = array();

    public $campi_aggiuntivi = array();

    public $data; //Data attuale in gg/mm/aaaa

    function __construct($config = array()){
        //Istanza di codeigniter
        $this->ci = get_instance();
        $this->data = date("d/m/Y",time());

        if(sizeof($config)>0){
            foreach($config as $k=>$v){
                $this->{$k} = $v;
            }
        }
    }

    public function info(){
        $this->url = "https://api.fattureincloud.it/v1/richiesta/info";
        return $this->crea();
    }

    public function info_account($campi=array()){
        $this->url = "https://api.fattureincloud.it/v1/info/account";
        return $this->crea(array("campi"=>$campi));
    }

    public function lista_clienti(){
        $this->url = "https://api.fattureincloud.it/v1/clienti/lista";
        return $this->crea();
    }

    public function lista_fornitori(){
        $this->url = "https://api.fattureincloud.it/v1/fornitori/lista";
        return $this->crea();
    }

    public function lista_prodotti(){
        $this->url = "https://api.fattureincloud.it/v1/prodotti/lista";
        return $this->crea();
    }

    public function lista_iva(){
        return $this->info_account(array("lista_iva"));
    }

    public function aggiungi_articolo($dati){
        $articolo =
            array(
              "codice" => "",
              "nome" => "Articolo TEST",
              //"um" => "",
              "quantita" => 1,
              "descrizione" => "",
              //"categoria" => "",
              "prezzo_netto" => 0,
              "prezzo_lordo" => 0,
              "cod_iva" => 0,
              //"tassabile" => true,
              //"sconto" => 0,
              //"applica_ra_contributi" => true,
              //"ordine" => 0,
              //"sconto_rosso" => 0,
              //"in_ddt" => false,
              //"magazzino" => false
            );
        foreach($dati as $k=>$v){
            $articolo[$k]=$v;
        }
        $this->articoli[] = $articolo;
        return $articolo;
    }

    public function aggiungi_pagamento($dati=array(),$pagato=false){
        if($pagato===true) $pagato = "Pagato";
        $pagamento =
            array(
              "data_scadenza" => $this->data,
              "importo" => "auto",
              "metodo" => (!$pagato?"not":$pagato),
              "data_saldo" => $this->data
            );
        foreach($dati as $k=>$v){
            $pagamento[$k]=$v;
        }
        $this->pagamenti[] = $pagamento;
        return $pagamento;
    }


    public function crea_fattura($anagrafica,$extra_anagrafica=array()){
        $this->url = "https://api.fattureincloud.it/v1/fatture/nuovo";
        return $this->crea_documento($anagrafica,$extra_anagrafica);
    }
    public function crea_ricevuta($anagrafica,$extra_anagrafica=array()){
        $this->url = "https://api.fattureincloud.it/v1/ricevute/nuovo";
        return $this->crea_documento($anagrafica,$extra_anagrafica);
    }
    public function crea_nota_di_credito($anagrafica,$extra_anagrafica=array()){
        $this->url = "https://api.fattureincloud.it/v1/ndc/nuovo";
        return $this->crea_documento($anagrafica,$extra_anagrafica);
    }
    public function crea_proforma($anagrafica,$extra_anagrafica=array()){
        $this->url = "https://api.fattureincloud.it/v1/proforma/nuovo";
        return $this->crea_documento($anagrafica,$extra_anagrafica);
    }

    /*
    RITORNA:
    {
      "new_id": 12312312,
      "token": "1a2b3c4d5e6f7g8h9i72931bckwadg",
      "success": true
    }
    */
    public function crea_documento($anagrafica,$extra_anagrafica){

        $dati = array(
          "nome" => "Codeigniter FattureInCloud",
          //"indirizzo_via" => "Via Test TEST, 123",
          //"indirizzo_cap" => "21012",
          //"indirizzo_citta" => "Curno",
          //"indirizzo_provincia" => "BG",
          //"indirizzo_extra" => "",
          //"paese" => "Italia",
          //"paese_iso" => "IT",
          //"lingua" => "it",
          //"piva" => "IT1234567890",
          //"cf" => "ABCDEF12G34H567I",
          //"numero" => "",
          "autocompila_anagrafica" => true,
          "salva_anagrafica" => false,
          "data" => $this->data,
          "valuta" => "EUR",
          "valuta_cambio" => 1,
          "prezzi_ivati" => false,
          "note" => "",
          //"nascondi_scadenza" => false,
          //"mostra_info_pagamento" => false,
          //"metodo_pagamento" => "Bonifico",
          //"metodo_titolo1" => "IBAN",
          //"metodo_desc1" => "IT01A2345678900000000001234",
          //"metodo_titolo2" => "Ragione Sociale",
          //"metodo_desc2" => "Azienda.net",
          //"mostra_totali" => "tutti",
          //"mostra_bottone_bonifico" => false,
          //"mostra_bottone_paypal" => false,
          //"mostra_bottone_notifica" => false,
          "lista_articoli" => array(),
          "lista_pagamenti" => array(),
          //"ddt_numero" => "",
          //"ddt_data" => $this->data,
          //"ddt_colli" => "",
          //"ddt_peso" => "",
          //"ddt_causale" => "",
          //"ddt_luogo" => "",
          //"ddt_trasportatore" => "",
          //"ddt_annotazioni" => "",
          ////"PA" => false,
          //"PA_tipo_cliente" => "PA",
          //"PA_tipo" => "nessuno",
          //"PA_numero" => "",
          //"PA_data" => "16/05/2018",
          //"PA_cup" => "",
          //"PA_cig" => "",
          //"PA_codice" => "",
          //"PA_pec" => "",
          //"PA_esigibilita" => "N",
          //"PA_modalita_pagamento" => "MP01",
          //"PA_istituto_credito" => "",
          //"PA_iban" => "",
          //"PA_beneficiario" => "",
          "extra_anagrafica" => array(
            //"mail" => "your@mail.it",
            //"tel" => "012345678",
            //"fax" => "012345678"
          ),
          "split_payment" => false
        );

        foreach($anagrafica as $k=>$v){
            $dati[$k]=$v;
        }

        if(sizeof($this->articoli)>0)
            $dati['lista_articoli'] = $this->articoli;

        if(sizeof($this->pagamenti)>0)
            $dati['lista_pagamenti'] = $this->pagamenti;

        if(sizeof($extra_anagrafica)>0){
            $dati['extra_anagraica'] = $extra_anagrafica;
        }

        return $this->crea($dati);
    }


    public function crea($richiesta=array()){

        $richiesta['api_uid'] = $this->api_uid;
        $richiesta['api_key'] = $this->api_key;

        foreach($this->campi_aggiuntivi as $k=>$v){
            $richiesta[$k] = $v;
        }
        //print_r($richiesta);die;
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
