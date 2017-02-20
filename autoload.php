<?php

function __autoload($class){ // подключение запрашиваемых классов
	
	if(file_exists($class.'.php')) include $class.'.php';
	if(file_exists(__DIR__.'/model/'.$class.'.php')) include __DIR__.'/model/'.$class.'.php';
	if(file_exists(__DIR__.'/controller/'.$class.'.php')) include __DIR__.'/controller/'.$class.'.php';
	if(file_exists(__DIR__.'/view/'.$class.'.php')) include __DIR__.'/view/'.$class.'.php';
	
}

function debug($data){// вывод на экран содержимого для отладки
    echo '<pre>'.print_r($data,true).'</pre>';
}


?>