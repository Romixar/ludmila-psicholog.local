<?php

class Messages{// для вывода системных сообщений
	
	public function getMessage($code){
		
		$row = parse_ini_file('/messages/messages.ini');
		
		$view = new View();
		
        $view -> err = $row[$code];
        // если сообщение не ошибка то шаблон success
		if(strpos($code,'ERR') === false) $view -> display('success');
		else $view -> display('error');
		
	}
	
	
	
	
}
