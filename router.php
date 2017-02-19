<?php

class Router{

	// возращаю контроллер и его метод
	private function checkURL(&$ctrl_name, &$method){

//        if(isset($_GET)) print_r($_GET);
//        if(isset($_POST)) print_r($_POST);
			
        
        
		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
        
        //debug($_SERVER);
        
        //echo get_headers($url);
        
		$reg = '/^\/\?([a-z=_]{3,15})$/';// шаблон который должен быть в строке адреса
		
        //die;
        
		if(preg_match($reg,$url)){// парсить URI и вставить ЧПУ красивый адрес
            
            //debug($url);
            $arr = []; // будет массив из имени КОНТРОЛЛЕРА и его МЕТОДА
            $ctrl = substr($url,strpos($url,'=')+1); // беру все после =
            
            //$this -> setUrl($ctrl);
			
			foreach(Config::$routes as $key => $val){// перебор массива маршрутов
                
				if($ctrl == $key) $arr = explode('/',$val[0]);
			}
            
            $ctrl_name = substr($ctrl,1).'controller';
            $method = 'action'.ucfirst($arr[1]);
            
		}else{
			$ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
			$method = 'actionAll';
		}
		
//        echo 'контроллер - '.$ctrl_name.'<br/>';
//		echo 'метод - '.$method.'<br/>';
        //die;

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