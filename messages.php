<?php

class Messages{// для вывода системных сообщений
	
	public function getMessage($code){
		
		$row = parse_ini_file('/messages/messages.ini');
		
		$view = new View();
		
		foreach($row as $k => $v){
			if($k == $code) $view -> err = $v;		
		}
		
		if(strpos($code,'ERR') === false) $view -> display('success');
		else $view -> display('error');
		
	}
	
	
	
	
}
