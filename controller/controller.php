<?php

//require_once '../model/services.php';
//require_once '../view/view.php';

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
	//public $class = '';// имя класса, который к нам обращается
	public $cl; // будет экземпляр класса нужной подмодели при получении POST
	
	public $openfield;// название класса у которого открыть поля для добавления
	
	public $arr = [];// массив для экземплчяров объектов подмоделей и их названий шаблона
	
	public $mes;// Объект вывода системных сообщений
    
    public $err = [];// здесь буду собирать ошибки в полях ввода
	
	
	
	public function __construct(){
		
		$this -> mes = new Messages();// Объект вывода системных сообщений
		
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
	
	
	public function xss($data, $flag=false){
		
		if(is_array($data)){
			$req = '/script|http|www\.|\'|\`|SELECT|UNION|UPDATE|exe|exec|CREATE|DELETE|INSERT|tmp/i';
			
			foreach($data as  $key => $val){
				
				$val = trim($val);//очистка от пробелов
				
				$val = preg_replace($req,'',$val);
				
				$data[$key] = strip_tags($val); //удаление всех HTML тегов
			
			}
			if($flag) $this -> checkOnDelete($data);// пришел GET, отправляю проверить надо ли удалять
			else $this -> checkData($data);// отправляю провереный массив на проверку типа данных
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
    
    private function checkData($data){

        $err = []; // здесь буду собирать все ошибки
        $i = 0;
        foreach($data as $key => $val){
            
            if(strpos($key,'title_') !== false) $this -> checkLen($val,200);
            //echo $key.' => '.$val;
            if(strpos($key,'price_') !== false){
                
                $val = $this -> checkPrice($val,6,2);// проверка и форматир цены
                echo $val.'<br/>';
                ///if(empty($err[$i])) $val = $this -> formatPrice($val);
                //else echo $val.'<br/>';
                
            }
            
            //if(strpos($key,'price_') !== false) echo $val;
            
            $i++;
            
        }
        
        
        
        
        if(!empty($this->err)){
            
//            foreach($err as $k => $val){
//                $this -> mes -> getMessage($val);
//            }
            echo '<pre>';
            print_r($this -> err);
            echo '</pre>';
            
        }
            
        die;
        
    }
    
    private function checkLen($val,$size){
        
        if(strlen($val) > $size) $this -> err[] = 'ERR_LEN';// превышена длина заполнения поля
        
    }
    
    private function checkPrice($val,$int,$mnt){
        
        if(!preg_match("/^\d{0,".$int."}(\.|\,)?\d{0,".$mnt."}$/",$val)) $this -> err[] = 'ERR_PRICE';
        else return $this -> formatPrice($val);
    }
    
    private function formatPrice($val){
        $val = str_replace(',','.',$val);// заменяю запятые
        if(strpos($val,'.') == 0) $val = '0'.$val;// ексли первой пришла тчк
        return $val;
    }
	
    // будет создан объект, который прописан в submit
	private function selectAction($data){// выбор действия по ключу кнопки отправить
		
		$arr = [];// здесь будет ключ кнопки отправить, разбитый по дефису -
		$newdata = [];// здесь будет массив данных без кнокпи отправить
		
		foreach($data as $key => $val){
			
			if(strpos($key,'-')){
				$arr = explode('-',$key);// получаю ключ кнопки отправить
				continue;
			}
			$newdata[$key] = $val;// получаю чистый массив
		}
		
		$this -> cl = new $arr[1]();//беру элемент после дефиса - это название класса, кот-й запустить
		
		if($arr[0] == 'add') $this -> openfield = $arr[1];// для actionAll название поля в кот открыть

		return $newdata;
		
	}
		
	
	public function save($data){// определим добавить в БД или только обновить
	
		$data = $this -> selectAction($data);// получаю чистый массив данных для внесения в БД

		$count = $this -> cl -> countRow();// запрос кол-ва записей в БД

		if($count == count($data)){
			
			$this -> update($data);// отправляю на обновление

		}else{
			$up_data = array_slice($data, 0, count($data)-1);//UPDATE без одного последнего элемента
			$this -> update($up_data);// на обновление
			
			$ins_data = $data[count($data) - 1];//а последний элмент отправляю на INSERT
			if($this -> cl -> insert($ins_data)) $this -> mes -> getMessage('VID_ADD');
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
			$res = $this -> cl -> update($arr, $params);
		}
		if($res) $this -> mes -> getMessage('VID_SAVE');
        else $this -> mes -> getMessage('ERR_SAVE');
		// echo'<pre>';
		// print_r($params);
		// echo'</pre>';
		//die;
		
	}
	
	public function checkOnDelete($get){//проверка надо ли удалять элемент из БД
		
		try{// отлов исключений базы данных		
			if(strpos($get['id'],'_')){
				$arr = explode('_',$get['id']);
				if(count($arr) == 2){
					
					if(!$id = $this -> isIntNum($arr[1])) return false;
					
					if($this -> checkClassName($arr[0])){
						
						$myclass = new $arr[0]();
						
						if($myclass -> checkID($id)) return true;// проверить есть ли такой id и удалить		
					}
				}
			}
		}catch(Exception $e){
			$view = new View();
			$view -> err = $e -> getMessage();
			$view -> display('error');

		}
	}

	private function checkClassName($cl_name){// проверяю наличие класса
		if(preg_match('/^[a-z]{4,11}$/',$cl_name))
			if(class_exists($cl_name)) return true;
		return false;
	}
	
	private function isIntNum($num){// проверяю число может ли оно быть ID
		if((int)$num != 0 || is_int($num)) return (int)$num;
		return false;
	}
	
	private function getClassName($obj){
		return strtolower(get_class($obj));// получаю имя класс созданного объекта
	}
	
	
	
	public function actionAll(){// вывести весь нужный контент
		
		$view = new View();
		
		for($i=0; $i<count($this -> arr); $i++){
			
			$arrObj = $this -> arr[$i] -> selectAll();// получаю массив объектов строк из БД
			$tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы
			$view -> func = '';// здесь будет идентификатор класса, который создает страницу
			
			$cl_name = $this -> getClassName($this -> arr[$i]);// получаю имя класс созданного ообъекта
			
			$view -> data = [];// перед началом второй итерации обнуляю
			
			for($j=0; $j<count($arrObj); $j++){
				
				$view -> data[$j] = $arrObj[$j] -> data;
				
			}

			if($this->openfield == $cl_name) $view -> open = true;// открываю поле в конкретной форме
			else $view -> open = false;
			// добавлю потом в кадую строку название класса, её создавшего
			$view -> func = $cl_name;//также это будет идентификатор для submit

			$view -> display($tmpl);// отправляю во view

		}
		
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
}















    