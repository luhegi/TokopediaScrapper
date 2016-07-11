<?php

require '../src/TokopediaScrapper.php';
try {
    $data = new TokopediaScrapper('https://www.tokopedia.com/budgetgadget/car-bluetooth-music-receiver-with-handsfree');
    echo $data->generate('info');
} catch (Exception $e) {
    echo $e->getMessage();
}
