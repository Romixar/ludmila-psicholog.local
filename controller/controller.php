<?php

require_once '../model/services.php';
require_once '../view/view.php';

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
	//public $class = '';// имя класса, который к нам обращается
	
	
	
	public function __construct(){
		
		if(!empty($_POST)){
			
			$data = $this -> xss($_POST);// отправляю на проверку
			//$this -> data = $this -> getObj($data);// создание двумерного массива
			//$this -> class = static::$class;// получаю название класса, который вызывает конструктора
			
		}
		
		
	}
	
	
	public function xss($data){
		
		if(is_array($data)){
			
			for($i=0; $i<count($data); $i++){
				
				$data[$i] = trim($data[$i]);//очистка от пробелов
				//замена опасных слов пустой строкой
				$req = '/script|http|www\.|SELECT|UNION|UPDATE|exe|exec|CREATE|DELETE|INSERT|tmp/i';
				$data[$i] = preg_replace($req,'',$data[$i]);
				
				if($flag) $data[] = htmlspecialchars($data[$i]); //замена всех HTML тегов на HTML сущности
				
				$data[] = strip_tags($data[$i]); //удаление всех HTML тегов
				
			}
			return $data;// возварщаю провереный массив
		}
		return false;
		
	}
	
	public function actionAll(){// возвращаю все записи в таблице
		
		$serv = new Services();
		$items = $serv -> selectAll();// получаем из модели массив объектов строк таблицы БД
		
		$view = new View();
        
		//var_dump($items);die;
		
		for($i=0; $i<count($items); $i++){// создаю внутренний двумерный массив объекта view
			
			$view -> data[$i] = $items[$i] -> data;
			
		//foreach($items -> data as $k => $v){
				
			//$view -> $k = $v;
				//var_dump($view -> $k);
		}
		
		//$view -> data = $items -> data;

		
			
			//var_dump($view);
			//die;
		//}
		
        
        $view -> display('serv');// отправляю во view
	}
	
	
	
	
	
	
	
	
	
}

$ctrl = new Controller();
$ctrl -> actionAll();













    