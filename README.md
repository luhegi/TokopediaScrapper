# TokopediaScrapper
Tokopedia Product Info and Image Scrapper

### Requirements

* PHP 5.3.2x or later

###USAGE

```php
<?php

require 'TokopediaScrapper.php';
$productUrl = 'https://www.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree'; // URL Product Example
try {
    $data = new TokopediaScrapper($productUrl);
    echo $data->generate();
} catch (Exception $e) {
    echo $e->getMessage();
}

```
