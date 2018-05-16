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
