<?php 
require('../src/Get_productinfo.php');

$data = new Get_productinfo('http://wap.tokopedia.com/jsbsusksmbzls/oppo-f1-plus-selfie-expert-asli-original');
echo $data->generate();