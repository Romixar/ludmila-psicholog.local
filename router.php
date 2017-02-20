<?php

class Router{

	// возращаю контроллер и его метод
	private function checkURL(&$ctrl_name, &$method){

        
		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
        
		$reg = '/^\/([a-z=_]{3,15})$/';// шаблон который должен быть в строке адреса
        
		if(preg_match($reg,$url)){// парсить URI и вставить ЧПУ красивый адрес
            
            $arr = []; // будет массив из имени КОНТРОЛЛЕРА и его МЕТОДА
            $ctrl = substr($url,1);
            
			foreach(Config::$routes as $key => $val){// перебор массива маршрутов
                
				if($ctrl == $key) $arr = explode('/',$val[0]);
			}
            
            $ctrl_name = $ctrl.'controller';
            $method = 'action'.ucfirst($arr[1]);
            
		}else{
			$ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
			$method = 'actionAll';
		}

	}
    
	
	
	public function run(){
		
		$this -> checkURL($ctrl_name, $method);
        
		$view = new View();
	    $view -> display('head');
        
		$cont = new $ctrl_name();// запуск выбранного контроллера и его метода
		$cont -> $method();
				
		
		echo 'контроллер - '.$ctrl_name.'<br/>';
		echo 'метод - '.$method.'<br/>';
		
	}
	
	
	
	
	
	
}