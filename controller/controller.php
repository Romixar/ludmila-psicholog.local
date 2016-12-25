<?php

//require_once '../model/services.php';
//require_once '../view/view.php';

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
	//public $class = '';// имя класса, который к нам обращается
	public $cl; // будет экземпляр класса нужной подмодели при получении POST
	
	public $arr = [];// массив для экземплчяров объектов подмоделей и их названий шаблона
	
	
	
	public function __construct(){
		
		if(!empty($_POST)){
			$this -> xss($_POST);// отправляю на проверку
			//$this -> data = $this -> getObj($data);// создание двумерного массива
			//$this -> cl = static::$class;// получаю класс, который вызывает конструктор при выводе
			
		}
		if(!empty($_GET)){
			if(!empty($_GET['id'])){
				$this -> xss($_GET, true);// флаг, то что это GET массив
			}
		}
		
	}
	
	
	public function xss($data, $flag){
		
		if(is_array($data)){
			$req = '/script|http|www\.|\'|\`|SELECT|UNION|UPDATE|exe|exec|CREATE|DELETE|INSERT|tmp/i';
			
			foreach($data as  $key => $val){
				
				$val = trim($val);//очистка от пробелов
				
				$val = preg_replace($req,'',$val);
				
				$data[$key] = strip_tags($val); //удаление всех HTML тегов
			
			}
			if($flag) $this -> checkOnDelete($data);// пришел GET, отправляю проверить надо ли удалять
			else $this -> getData($data);// отправляю провереный массив на создание двумерного
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

		$this -> save($newdata);// определит на UPDATE или INSERT идут данные
		
	}
	
	private function selectAction($data){
		
		$keys = array_keys($data);// получить послед элемент
		$clname = explode('-',array_pop($keys));// получаю ключ кнопки отправить
		$this -> cl = new $clname[1]();//беру элемент после дефиса - это название класса, кот-й запустить
	
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
			
			$ins_data = $data[count($data) - 1];//а последний элмент отправляю на вставку
			if($this -> cl -> insert($ins_data)) return true;
			else return false;
		}
	}
	
	public function update($data){
		
		for($i=0; $i<(count($data)); $i++){
			
			$arr = []; // массив для строки данных для запроса к БД
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
	
	public function checkOnDelete($get){
		
		if(strpos($get['id'],'_')){
			$arr = explode('_',$get['id']);
			if(count($arr) == 2){
				
				$id = (int)$arr[1];// получаю цифру, кот-я может быть ID, если можно получить
				
				if($id == 0 || !is_int($id)) return false;
				
				if(preg_match('/^[a-z]{4,11}$/',$arr[0])){
					
					if (class_exists($arr[0])){// если класс существует
						
						$myclass = new $arr[0]();
						
						// проверить есть ли такой id и удалить
						if($myclass -> checkID($id)) return true;
						else return false;
						
					}else return false;	
				}
			}
			return false;
		}
	}
	
	
	
	public function actionAll(){
		
		$view = new View();
		
		for($i=0; $i<count($this -> arr); $i++){
			
			$arrObj = $this -> arr[$i] -> selectAll();// получаю массив объектов строк из БД
			$tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы
			$view -> func = '';// здесь будет идентификатор класса, который создает страницу
			$cl_name = get_class($this -> arr[$i]);// получаю имя класс созданного ообъекта
			$view -> data = [];// перед началом второй итерации обнуляю
			
			for($j=0; $j<count($arrObj); $j++){
				
				$view -> data[$j] = $arrObj[$j] -> data;
				
			}
			// добавлю потом в кадую строку название класса, её создавшего
			$view -> func = strtolower($cl_name);//также это будет идентификатор для submit

			$view -> display($tmpl);// отправляю во view

		}
		
	}
	
	
	
	
	
	
	
	
	
	
}















    