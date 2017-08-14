<?php
set_time_limit(0);
ini_set('memory_limit', '1024M');
@set_time_limit(0);
 
/* проверочка. чтобы этот скрипт по неосторожности никто не вызвал из браузера */
if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');

define('CMD', 1);
 
/* подключаем framework */
include(dirname(__FILE__).'/index.php');
?>