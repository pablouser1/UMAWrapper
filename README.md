# UMA Wrapper
Wrapper para API de la Universidad de Málaga

## Requisitos
Para poder usar UMAWrapper necesitas como mínimo:
- PHP >= 8.3
- ext-curl
- ext-mbstring

## Instalación
```bash
composer require pablouser1/umawrapper
```

### Ejemplo
```php
use UMA\Api;
use UMA\Options;
$options = new Options(/* ... */);
$api = new Api($options);

$res = $api->centros();
var_dump($res);
```

## Docs
Con PHPDoc instalado, ejecuta:
```bash
phpdoc
```

## Tests
```bash
./vendor/bin/pest
```
