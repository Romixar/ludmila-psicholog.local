<?php

class Router{

	// возращаю контроллер и его метод
	private function checkURL(&$ctrl_name, &$method){

        
		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
        
		$reg = '/^[a-zA-Z]*$/';// шаблон который должен быть в строке адреса
        
        $ctrl = explode('/',$url)[2];
        
		if(!empty($ctrl) && preg_match($reg,$ctrl)){// парсить URI и вставить ЧПУ красивый адрес
            
            $arr = []; // будет массив из имени КОНТРОЛЛЕРА и его МЕТОДА
            
			foreach(Config::$routes as $key => $val){// перебор массива маршрутов
                
				if($ctrl == $key) $arr = explode('/',$val[0]);

			}
            if(!empty($arr)){
                $ctrl_name = $ctrl.'controller';
                $method = 'action'.ucfirst($arr[1]);
                return;
            }
		}         
		$ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
		$method = 'actionAll';
	}
    
	
	
	public function run(){
        
        $view = new View();
        
        $login = new LoginController();// проверка логинизации
        
        if(!$login->checkLogin()) $login -> authorized(); // не авторизован
		else{
            
            echo 'авторизован';
            
            //header('Location: /admin/');
            
        }
        
        die;
        
		$this -> checkURL($ctrl_name, $method);
        
		//$view = new View();
	    $view -> display('head');
        
        echo 'контроллер - '.$ctrl_name.'<br/>';
		echo 'метод - '.$method.'<br/>';
        
		$cont = new $ctrl_name();// запуск выбранного контроллера и его метода
		$cont -> $method();
				
		
		
		
	}
	
	
	
	
	
}