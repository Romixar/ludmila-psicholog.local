<?php

class Router{
    
    private $routes = [// маршруты
        
        'admin/login' => ['login/run'],
        'admin/logout' => ['login/logout'],
        'admin/main' => ['main/all','Главная'],
        'admin/about' => ['about/all','Обо мне'],
        'admin/services' => ['services/all','Услуги'],
        'admin/contacts' => ['contacts/all','Контакты'],
        
    ];
    
    
    
    public function getControllerAndAction(&$ctrl,&$act){
        
        //echo 'загрузка роутера';
        session_start();
        
        $url = $_SERVER['REQUEST_URI'];
         
        
        $arr = explode('/',$url);
        
        
        
        $reg = '/[a-zA-Z]*/';
        
        $tmp = [];
        
        if(count($arr) == 2 && empty($arr[1])){ // запрос пользовательского сайта
            include 'settings.php';
            exit();
        }

        // запрос в админскую часть
        if((count($arr) > 3) || (!isset($_SESSION['loggedIn'])) || ($arr[1] == 'admin' && $arr[2] == '')){
            
            
            $ctrl = 'login';
            $act = 'run';
            
            $ctrl = ucfirst($ctrl).'Controller';
            $act = 'action'.ucfirst($act);

            return;
        }

        // запросы страниц в админской части
        if(!empty($arr[2]) && preg_match($reg,$arr[2]) && $arr[1] == 'admin'){
            
            foreach($this->routes as $k => $v){
                
                $routes = explode('/',$k);
                
                if($arr[2] == $routes[1]) $tmp = explode('/',$v[0]);
                
            }
        }
        
        if(!empty($tmp)){
            $ctrl = $tmp[0];
            $act = $tmp[1];
        }else{
            $ctrl = 'main';
            $act = 'all';
        }
        
        $ctrl = ucfirst($ctrl).'Controller';
        $act = 'action'.ucfirst($act);
        
    }
    
    
    
    
    
    public function run(){
        
        $this -> getControllerAndAction($ctrl, $act);
        
        $controller = new $ctrl(); // запуск класса объекта и его метода
        $controller -> $act();
                
        
        echo 'CONTROLLER - '.$ctrl.'<br/>';
        echo 'ACTION - '.$act;

        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

	// возращаю контроллер и его метод
//	private function checkURL(&$ctrl_name, &$method){
//
//        
//		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
//        
//		$reg = '/^[a-zA-Z]*$/';// шаблон который должен быть в строке адреса
//        
//        $ctrl = explode('/',$url)[2];
//        
//		if(!empty($ctrl) && preg_match($reg,$ctrl)){// парсить URI и вставить ЧПУ красивый адрес
//            
//            $arr = []; // будет массив из имени КОНТРОЛЛЕРА и его МЕТОДА
//            
//			foreach(Config::$routes as $key => $val){// перебор массива маршрутов
//                
//				if($ctrl == $key) $arr = explode('/',$val[0]);
//
//			}
//            if(!empty($arr)){
//                $ctrl_name = $ctrl.'controller';
//                $method = 'action'.ucfirst($arr[1]);
//                return;
//            }
//		}         
//		$ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
//		$method = 'actionAll';
//	}
//    
//	
//	
//	public function run(){
//        
//        $view = new View();
//        
//        if(!Session::get('loggedIn')){
//            
//            //echo 'стр авторизации';
//            
//            $login = new LoginController();
//            $login->run();
//            //$login -> index();
//            
//            
//        }else{
//            
//            debug(Session::get('loggedIn'));
//            
//            $this -> checkURL($ctrl_name, $method);
//        
//            //$view = new View();
//            $view -> display('head');
//
//            echo 'контроллер - '.$ctrl_name.'<br/>';
//            echo 'метод - '.$method.'<br/>';
//
//            $cont = new $ctrl_name();// запуск выбранного контроллера и его метода
//            $cont -> $method();
//            
//            
//        }
//        
//		
//				
//		
//		
//		
//	}
	
	
	
	
	
}