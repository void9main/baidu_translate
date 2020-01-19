<?php

require "./vendor/autoload.php";

use BaiduT\BaiduTran\Translate;


$tran = new Translate("","");

$data = $tran->server("你好","en");

var_dump($data);

?>