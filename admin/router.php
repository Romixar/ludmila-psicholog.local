<?php

class Router{

	// возращаю контроллер и его метод
	private function checkURL(&$ctrl_name, &$method){

        
		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
        
		$reg = '/^[a-zA-Z]*$/';// шаблон который должен быть в строке адреса
        
        $ctrl = explode('/',$url)[2];
        
		if(!empty($ctrl) && preg_match($reg,$ctrl)){// парсить URI и вставить ЧПУ красивый адрес
            
            debug($url);
            
            $arr = []; // будет массив из имени КОНТРОЛЛЕРА и его МЕТОДА
            //$ctrl = substr($url,6);// беру после слова /admin
            
			foreach(Config::$routes as $key => $val){// перебор массива маршрутов
                
				if($ctrl == $key) $arr = explode('/',$val[0]);
			}
            
            $ctrl_name = $ctrl.'controller';
            if($this->checkClassName($ctrl_name)) $method = 'action'.ucfirst($arr[1]);
            else{
                $ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
			    $method = 'actionAll';
            }
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
	
	private function checkClassName($cl){// проверка есть ли такой класс
        echo 'попал'.$cl;
        return class_exists($cl);
    }
	
	
	
	
}