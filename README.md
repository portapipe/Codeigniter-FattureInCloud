# Codeigniter-FattureInCloud
### FattureInCloud.it - Libreria API per Codeigniter

Inserisci il file `FattureInCloud.php` in `application/libraries/`

Recupera il tuo "api_uid" e la tua "api_key" accedendo a https://secure.fattureincloud.it/api

## Includere la libreria
```php
$this->load->library("FattureInCloud",array("api_uid"=>"12345","api_key"=>"1a2b3c4d5e6f7g8h9"));
```

## Verificare che la chiamata funzioni e i dati siano corretti
```php
$this->fattureincloud->info();
```

## Ottenere le info/liste configurate nell'account
```php
$this->fattureincloud->info_account(
  array(
    "lista_iva",
    "lista_conti",
    "lista_metodi_pagamenti",
    "lista_valute",
    "lista_paesi",
    "lista_template",
    "lista_template_ddt",
    "lista_template_ddt_ftacc",
    "tipo_licenza",
    "durata_licenza",
    "nome"
  )
);
```
Puoi scegliere anche solo una delle opzioni.

## Ottieni la lista dei prodotti
```php
$this->fattureincloud->lista_prodotti();
```

## Ottieni la lista dei clienti
```php
$this->fattureincloud->lista_clienti();
```

## Crea una fattura
Ci sono 3 step obbligatori.

1. Aggiugere articoli alla fattura (obbligatorio)
```php
$this->fattureincloud->aggiungi_articolo(
  array(
    "nome"=>"Pennarello Viola",
    "quantita"=>2,
    "descrizione"=>"E' un pennarello <b>VIOLA</b><br/>Modello 2292",
    "prezzo_netto"=>10,
    "prezzo_lordo"=>12.2
  )
);
```

2. Aggiugere un pagamento alla fattura
```php
$this->fattureincloud->aggiungi_pagamento(
  array(
    "data_scadenza"=>"14/09/2019",
    "importo"=>24.40,
  )
  ,false
);
```
NOTA BENE: il secondo parametro (di default FALSE) indica se la fattura è stata pagata.
Se pagata puoi passare TRUE oppure il nome del conto su cui è stata pagata (es. PayPal o Stripe o IBAN).

3. Passare l'anagrafica cliente e creare la fattura. Ritorna un oggetto con id fattura FIC, token fattura FIC e stato della richiesta
```php
$this->fattureincloud->crea_fattura(
  array(
    "nome"=>"Pluto Doofie",
    "indirizzo_via"=>"Via di Casa Mia 13",
    "indirizzo_citta"=>"Milano",
    "indirizzo_cap"=>"10100",
    "indirizzo_provincia"=>"MI",
    "piva"=>123456789123,
    "cf"=>"ABCDEF01G23H456I",
    "salva_anagrafica"=>false,
    "valuta"=>"EUR",
    "note"=>"Le note le mettiamo qui",
    "metodo_pagamento"=>"Bonifico",
    "metodo_titoloN"=>"IBAN",
    "metodo_descN"=>"IT01A2345678900000000001234"
  ),
  array(
    "mail"=>"latua@mail.it",
    "tel"=>"019988776"
  )
);
```
Il primo parametro contiene l'anagrafica del cliente a cui la fattura andrà.
Il secondo parametro sono i campi extra dell'anagrafica (mail, tel, fax...).



## Creare una ricevuta, una nota d'ordine, una fattura proforma...
Anche qui ci sono tre punti e sono uguali alle fatture, con la differenza che invece di chiamare `crea_fattura(...` si userà:
```php
$this->fattureincloud->crea_ricevuta(...
$this->fattureincloud->crea_nota_di_credito(...
$this->fattureincloud->crea_proforma(...
```
