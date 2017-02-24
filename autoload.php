<?php

function __autoload($cl_name){
    
    $file = strtolower($cl_name).'.php';
    
    
    if(file_exists($file)){
        
        include $file;
        
    }
    
    if(file_exists('../'.$file)){
        
        include '../'.$file;
        
    }
    
    if(file_exists(__DIR__.'/admin/'.$file)){
        
        include __DIR__.'/admin/'.$file;
        
    }
    
    if(file_exists(__DIR__.'/admin/model/'.$file)){
        
        include __DIR__.'/admin/model/'.$file;
        
    }
    
    if(file_exists(__DIR__.'/admin/controller/'.$file)){
        
        include __DIR__.'/admin/controller/'.$file;
        
    }
    
    if(file_exists(__DIR__.'/admin/view/'.$file)){
        
        include __DIR__.'/admin/view/'.$file;
        
    }
    
    
}

















//function __autoload($class){ // подключение запрашиваемых классов
//	
//	if(file_exists($class.'.php')) include $class.'.php';
//	if(file_exists(__DIR__.'/model/'.$class.'.php')) include __DIR__.'/model/'.$class.'.php';
//	if(file_exists(__DIR__.'/controller/'.$class.'.php')) include __DIR__.'/controller/'.$class.'.php';
//	if(file_exists(__DIR__.'/view/'.$class.'.php')) include __DIR__.'/view/'.$class.'.php';
//	
//}

function debug($data){// вывод на экран содержимого для отладки
    echo '<pre>'.print_r($data,true).'</pre>';
}


?>