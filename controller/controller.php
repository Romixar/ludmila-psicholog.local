<?php

//require_once '../model/services.php';
//require_once '../view/view.php';

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
	//public $class = '';// имя класса, который к нам обращается
	public $cl; // будет экземпляр класса нужной подмодели
	
	
	
	public function __construct(){
		
		if(!empty($_POST)){
			
			
			$this -> xss($_POST);// отправляю на проверку
			//$this -> data = $this -> getObj($data);// создание двумерного массива
			//$this -> class = static::$class;// получаю название класса, который вызывает конструктора
			
		}
		//if(!empty($_GET)){// открытие опред-й страницы
			//if($_GET['ctrl'] == 1) $this -> getMainPage();//запуск контроллера для страницы ГЛАВНАЯ
			//if($_GET['ctrl'] == 2) $this -> getAbout();//запуск контроллера для страницы УСЛУГИ
			//if($_GET['ctrl'] == 3) $this -> getServices();//запуск контроллера для страницы УСЛУГИ
			//if($_GET['ctrl'] == 4) $this -> getContacts();//запуск контроллера для страницы КОНТАКТЫ
			
		//}
		
		
	}
	
	
	public function xss($data){
		
		if(is_array($data)){
			$req = '/script|http|www\.|\'|\`|SELECT|UNION|UPDATE|exe|exec|CREATE|DELETE|INSERT|tmp/i';
			
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

		$this -> save($newdata);
		
	}
	
	public function selectAction($data){
		
		$keys = array_keys($data);
		$action = array_pop($keys);// получаю ключ кнопки отправить
		
		switch($action){
			case 'savework':
			case 'addwork': $this -> cl = new Works();
			break;
			case 'savecontact': $this -> cl = new Contacts();// подмодель для страницы КОНТАКТЫ
			break;
			case 'saveserv':
			case 'addserv': $this -> cl = new Prices();
			break;
			case 'savedesc':
			case 'adddesc': $this -> cl = new Services();// подмодель для страницы УСЛУГИ
			break;
			case 'savevid':
			case 'addvid': $this -> cl = new Videos();
			break;
			case 'savetestmon':
			case 'addtestmon': $this -> cl = new Testmonials();// подмодель для страницы ГЛАВНАЯ
			break;
		}
		
	}
	
	public function save($data){// определим добавить в БД или только обновить

		$this -> selectAction($data);
		
		array_pop($data);// избавляюсь от послед эл-та (кнопки отправления формы)
		
		$count = $this -> cl -> countRow();// запрос кол-ва записей в БД
		
		if($count == count($data)){
			
			$this -> update($data);// отправляю на обновление

		}else{
			$up_data = array_slice($data, 0, count($data)-1);// без одного последнего элемента
			$this -> update($up_data);// на обновление
			
			$ins_data = $data[count($data) - 1];//последний элмент отправляю на вставку
			if($this -> cl -> insert($ins_data)) return true;
			else return false;
		}
	}
	
	public function update($data){
		
		for($i=0; $i<(count($data)); $i++){
			$arr = []; // массив для строки таблицы БД
			$params = []; // здесь будут параметры для подстановки
			foreach($data[$i] as $key => $val){
				
				if($key == 'id') $params[':id'] = $val;
				else{// чтобы поле id не прописывалось в запрос
					if(is_numeric($val)) $arr[] = '`'.$key.'` = '.$val;// если число то без кавычек
					else $arr[] = '`'.$key."` = '".$val."'";// если текстовое значение
				}
			}
			$this -> cl -> update($arr, $params);
		}
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
	
	public function actionAll(){
		
		$view = new View();
		
		for($i=0; $i<count($this -> arr); $i++){
			
			$arrObj = $this -> arr[$i] -> selectAll();// получаю массив объектов строк из БД
			$tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы

			$view -> data = [];// передачалом второй итерации обнуляю
			
			for($j=0; $j<count($arrObj); $j++){
				
				$view -> data[$j] = $arrObj[$j] -> data;
				
			}
			
			$view -> display($tmpl);// отправляю во view

		}
		
	}
	
	public function getMainPage(){
		
		
		echo 'открою скоро ГЛАВНАЯ!';
		
	}
	
	public function getAbout(){
		
		echo 'открою скоро ОБО МНЕ!';
		
	}
	
	
	
	
	
	
	
	
	
}















    