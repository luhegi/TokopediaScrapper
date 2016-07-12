# TokopediaScrapper
Scrap Product Info, Result Array or Json Data
* Get Product Info 
* Get HD Image URL
* Easy To Use

### Requirements

* PHP 5.3.2x or later

### USAGE
#### Get All Product Info and HD Image URL
```php
<?php

require 'TokopediaScrapper.php';
$productUrl = 'https://www.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree'; // URL Product Example
try {
    $data = new TokopediaScrapper($productUrl);
    echo $data->generate(); // Get Product Info and HD Image URL
    $info = $data->generate('info', FALSE); // Get Product Info Only in Array Return
    echo $data->generate('image'); // Get HD Image URL Only
} catch (Exception $e) {
    echo $e->getMessage();
}

```
#### Get Product Info Only in Array Data
```php
<?php

require 'TokopediaScrapper.php';
$productUrl = 'https://www.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree'; // URL Product Example
try {
    $data = new TokopediaScrapper($productUrl);
    $info = $data->generate('info', FALSE); // Get Product Info Only in Array Return
} catch (Exception $e) {
    echo $e->getMessage();
}

```
#### Get Product HD Image Only in Json Data
```php
<?php

require 'TokopediaScrapper.php';
$productUrl = 'https://www.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree'; // URL Product Example
try {
    $data = new TokopediaScrapper($productUrl);
    echo $data->generate('image'); // Get HD Image URL Only in JSON
} catch (Exception $e) {
    echo $e->getMessage();
}

```
#### RESULT $data->generate();
``` json
{
    "info": {
        "title": "Car Bluetooth Music Receiver with Handsfree",
        "description": "Garansi 1 Bulan<br/>Jaminan Garansi Produk by Budget Gadget<br/><br/>Favorit bluetooth car receiver di Budget Gadget!<br/>&quot;Recommended by Budget Gadget Team&quot;<br/><br/>APA KEGUNAANNYA?<br/>Tinggal colok ke lubang aux speaker mobil anda, atau speaker rumah anda, atau speaker apapun itu, dan langsung speaker anda dapat menerima music dan telepon via bluetooth !<br/><br/>The Bluetooth music receiver is designed to receive wmusic from mobile phones or transmitters that feature Bluetooth wireless technology, It can be used with almost any audio receiver with an audio input jack, including home A/V systems, home stereos, headphones, automobile or motorcycle speakers, boats, RVs and more.<br/><br/>Features<br/>Make It Wireless<br/>Listen to Wireless music from your mobile phones, tablets, PC and Apple devices.<br/><br/>Easy To Use<br/>Simple wireless connections to existing audio gear,compatible with any stereo line input. Touch a button,you can make a call hands-free and enjoy the music stored in the phone.<br/><br/>Microphone<br/>This device have microphone, Your voice will be transmited via bluetooth to speaker.<br/><br/>Long Battery Life<br/>Built-in sleep mode conserves the battery when the device is not in use.<br/><br/>Package Contents<br/>Barang-barang yang Anda dapat dalam kemasan produk:<br/><br/>1 x Car Bluetooth Music Receiver with Handsfree<br/>1 x 3.5mm Plug<br/>1 x USB Cable",
        "price": "71.900",
        "weight": "290gr",
        "condition": "Baru"
    },
    "image": [
        "https://ecs7.tokopedia.net/img/product-1/2016/5/31/19797972/19797972_5763dd66-2437-4998-a1dd-677c9946581e.jpg",
        "https://ecs7.tokopedia.net/img/product-1/2016/5/31/19797972/19797972_9efa36b2-0d47-4dd6-963f-7984582c8059.jpg",
        "https://ecs7.tokopedia.net/img/product-1/2016/5/31/19797972/19797972_97155746-785e-4552-8c70-d1c868193058.jpg",
        "https://ecs7.tokopedia.net/img/product-1/2015/9/22/441242/441242_85b4f3d8-965b-4e45-a38a-596d1593222e.jpg",
        "https://ecs7.tokopedia.net/img/product-1/2015/9/22/441242/441242_45e02f0c-2bea-486c-a99d-86b6113e039b.jpg"
    ]
}
```
