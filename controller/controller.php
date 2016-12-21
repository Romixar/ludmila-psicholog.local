<?php

//require_once '../model/services.php';
//require_once '../view/view.php';

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
	//public $class = '';// имя класса, который к нам обращается
	
	
	
	public function __construct(){
		
		if(!empty($_POST)){
			
			
			$this -> xss($_POST);// отправляю на проверку
			//$this -> data = $this -> getObj($data);// создание двумерного массива
			//$this -> class = static::$class;// получаю название класса, который вызывает конструктора
			
		}
		if(!empty($_GET)){// открытие опред-й страницы
			if($_GET['ctrl'] == 1) $this -> getMainPage();//запуск контроллера для страницы ГЛАВНАЯ
			if($_GET['ctrl'] == 2) $this -> getAbout();//запуск контроллера для страницы УСЛУГИ
			if($_GET['ctrl'] == 3) $this -> getServices();//запуск контроллера для страницы УСЛУГИ
			if($_GET['ctrl'] == 4) $this -> getContacts();//запуск контроллера для страницы КОНТАКТЫ
			
		}
		
		
	}
	
	
	public function xss($data){
		
		if(is_array($data)){
			$req = '/script|http|www\.|SELECT|UNION|UPDATE|exe|exec|CREATE|DELETE|INSERT|tmp/i';
			
			foreach($data as  $key => $val){
				
				$val = trim($val);//очистка от пробелов
				
				$val = preg_replace($req,'',$val);
				
				$data[$key] = strip_tags($val); //удаление всех HTML тегов
			
			}
			
			$this -> getData($data);// отправляю провереный массив на создание двумерного
		}
		return false;
		
	}
	
	public function getData($data){// получение двумерного массива
		
		$newdata = [];
		
		foreach($data as $key => $val){
				
			if($pos = strpos($key,'_')){
				$num = substr($key,$pos+1);// взять номер поля
				$newdata[$num][substr($key,0,$pos)] = $val;// делать эл-т под этим номером
			}else
				$newdata[$key] = $val;// кнопка с какой формы пришел массив
		}
		print_r($newdata);die;
		
		$this -> save($newdata);
		
	}
	
	public function getServices(){// возвращаю все записи в таблице
		
		$pr = new Prices();
		$prices = $pr -> selectAll();// получаю таблицу цен
		$view = new View();
		for($i=0; $i<count($prices); $i++){// создаю внутренний двумерный массив объекта view
			
			$view -> data[$i] = $prices[$i] -> data;
			
		}
		
		$view -> display('price');// отправляю во view
		
		
		$serv = new Services();// получаю таблицу Описания услуг
		$items = $serv -> selectAll();// получаем из модели массив объектов строк таблицы БД
		$view = new View();   
		for($i=0; $i<count($items); $i++){// создаю внутренний двумерный массив объекта view
			
			$view -> data[$i] = $items[$i] -> data;
			
		}
		
        $view -> display('serv');// отправляю во view

	}
	
	public function getContacts(){
		
		$wor = new Works();
		$works = $wor -> SelectAll();
		
		$view = new View();
		for($i=0; $i<count($works); $i++){
			
			$view -> data[$i] = $works[$i] -> data;
			
		}
		$view -> display('work');// отправляю во view
		
		
		$con = new Contacts();
		$contacts = $con -> SelectAll();
		
		$view = new View();
		
		$view -> data = $contacts;
		$view -> display('contact');// отправляю во view
		
		
	}
	
	public function getMainPage(){
		
		
		echo 'открою скоро ГЛАВНАЯ!';
		
	}
	
	public function getAbout(){
		
		echo 'открою скоро ОБО МНЕ!';
		
	}
	
	
	
	
	
	
	
	
	
}















    