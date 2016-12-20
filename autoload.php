<?php

function __autoload($class){
	
	if(file_exists(__DIR__.'/model/'.$class.'.php') include __DIR__.'/model/'.$class.'.php';
	if(file_exists(__DIR__.'/controller/'.$class.'.php') include __DIR__.'/controller/'.$class.'.php';
	if(file_exists(__DIR__.'/view/'.$class.'.php') include __DIR__.'/view/'.$class.'.php';
	
	
}

?>