
=======
# TokopediaScrapper ( On Development )
Tokopedia Product Info Scrapper

### Requirements

* PHP 5.3.2x or later

###USAGE

```php
<?php

require '../src/TokopediaScrapper.php';
$productUrl = 'https://www.tokopedia.com/xxxx/xxxx';
try {
    $data = new TokopediaScrapper($productUrl);
    echo $data->generate();
} catch (Exception $e) {
    echo $e->getMessage();
}

```
