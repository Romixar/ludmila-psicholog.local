<?php

class Router{
	
	
	
	
	
	private function checkURL(&$ctrl_name, &$method){
			
		$url = $_SERVER['REQUEST_URI'];// строка адреса после домена
		$reg = '/ctrl=[1-4]/';// шаблон который должен быть в строке адреса
				
		if(preg_match($reg,$url)){
			
			foreach(Config::$routes as $key => $val){
				
				$pos = strpos($url,'ctrl=');
				$ctrl = substr($url,$pos,6);// получаю символы CTRL= и то что после равно (1 символ)
					
				if($ctrl == $key){
					$arr = explode('/',$val);// получаю массив из имени КОНТРОЛЛЕРА и его МЕТОДА
					
					$ctrl_name = $arr[0].'controller';
					$method = 'action'.ucfirst($arr[1]);	
				}			
			}
		}else{
			$ctrl_name = 'maincontroller';// запуск контроллера по умолчанию
			$method = 'actionAll';
		}
		
		
	}
	
	
	public function run(){
		
		$this -> checkURL($ctrl_name, $method);
		
		$cont = new $ctrl_name();// запуск выбранного контроллера и его метода
		$cont -> $method();
			
		//echo 'контроллер - '.$ctrl_name.'<br/>';
		//echo 'метод - '.$method.'<br/>';
		
	}
	
	
	
	
	
	
}