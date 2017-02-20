<?php

class Messages{// для вывода системных сообщений
	
	public function getMessage($code, $cl_name=false){
		
		$row = parse_ini_file('/messages/messages.ini');
		
		$view = new View();
		
        //$view -> err = $row[$code];
        
        // добавлю к ключю код конкретного сообщения от класса
        if($cl_name) $view -> err = $row[strtoupper(substr($cl_name,0,4)).'_'.$code];
        else $view -> err = $row[$code];
        
        // если сообщение не ошибка то вывожу шаблон success
		if(strpos($code,'ERR') === false) $view -> display('success');
		else $view -> display('error');
		
	}
	
	
	
	
}
