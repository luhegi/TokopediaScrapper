<?php 
require('../src/Tokopedia.php');

$data = new Tokopedia('http://wap.tokopedia.com/jsbsusksmbzls/oppo-f1-plus-selfie-expert-asli-original');
echo $data->generate();