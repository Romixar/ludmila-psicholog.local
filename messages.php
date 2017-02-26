<?php

class Messages{// для вывода системных сообщений
	
	public function getMessage($code, $cl_name=false){
		
		$row = parse_ini_file('../messages/messages.ini');
		
		$view = new ViewsController();
		
        $view -> err = $row[$code];
        
        //debug($view -> err);die;
        
        // добавлю к ключю код конкретного сообщения от класса
        if($cl_name) $view -> err = $row[strtoupper(substr($cl_name,0,4)).'_'.$code];
        else $view -> err = $row[$code];
        
        // если сообщение не ошибка то вывожу шаблон success
		if(strpos($code,'ERR') === false) return $view -> prerender('message',['class'=>'suc']);
		else return $view -> prerender('message',['class'=>'err']);
		
	}
	
	
	
	
}
