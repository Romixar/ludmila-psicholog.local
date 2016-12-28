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
                
                $data[$key] = htmlspecialchars($val);//все HTML теги в сущности
			
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
        
        foreach($data as $key => $val){
            
            // удаляю теги у всех полей кроме этих
            if(strpos($key,'description_') === false){
                $val = htmlspecialchars_decode($val);
                $data[$key] = strip_tags($val);
            }
            
            // проверка на длину поле длительность занятий
            if(strpos($key,'duration_') !== false) $this -> checkLen($val,50);
            
            // проверка на длину поле превью отзыва
            if(strpos($key,'head_') !== false) $this -> checkLen($val,400);
            
            //проверка на длину поле заголовков и имен
            if(strpos($key,'title_') !== false || (strpos($key,'name_') !== false))
                $this -> checkLen($val,255);
            
            // проверка и форматир цены для внесения в БД
            if(strpos($key,'price_') !== false) $data[$key] = $this -> checkPrice($val,6,2);
            
            if(strpos($key,'phone_') !== false) $data[$key] = $this -> checkPhone($val);
            
            // возвращаю дату в виде TS
            if(strpos($key,'dateadd_') !== false) $data[$key] = $this -> isDate($val);
            
            // проверка ЮТУБ адреса и возвращаю только код видео
            if(strpos($key,'url_') !== false) $data[$key] = $this -> checkYouTubeURL($val);
            
            
            
            
            
            
        }
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        
        
        
        
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
        
        if(!preg_match("/^\d{0,".$int."}(\.|\,)?\d{0,".$int."}$/",$val)){
            $this -> err[] = 'ERR_PRICE';
            return $val;
        }
        return $this -> formatPrice($val,$mnt,'');// если все ок, вернуть форматир цену
    }
    
    private function checkPhone($val){
        
        $reg = ['/\+/','/\s/','/-/','/_/','/\(/','/\)/','/\./','/\,/'];// убрать из тел номера
        $val = preg_replace($reg,'',$val);
        
        if(preg_match('/^\d{4,15}$/', $val)) return $val;
        else{
            $this -> err[] = 'ERR_PHONE';
            return $val;
        }
        
        
    }
    
    private function formatPrice($val,$mnt,$sep){
        
        $val = str_replace(',','.',$val);// заменяю запятую на точку
        if(strpos($val,'.') == 0) $val = '0'.$val;// ексли первой пришла тчк
        
        return number_format($val, $mnt, ".", $sep);// окр до указанного кол-ва знаков после запятой
        
    }
    
    private function checkYouTubeURL($val){
        
        if($val != ''){
            // http://easyregexp.ru/constructor - конструктор регулярок
            if(!preg_match('/^(youtu){1}$/i',$val)){
                
                $this -> err[] = 'ERR_VID';
                return $val;
            }
            
            $code = '';// здесь будет код YOUTUBE видеоролика
            
            if(strpos($val,'youtube.com/watch?v=') !== false){
                
                $pos = strpos($val,'=');// вырезаем строку после знака =
                $code = substr($val, $pos + 1);

            }
            if(strpos($val,'youtu.be/') !== false){
                $pos = strpos($val,'.');
                $code = substr($val, $pos + 4);// вырезать и вернуть всё после /
                return $code;
            }
            if(strpos($val,'youtube.com/embed') !== false){
                $pos = strpos($val,'d');
                $code = substr($val, $pos + 2); // возращаю все доконца строки начиная с /
                return $code;
            }
            if(!$code){
                
                $this -> err[] = 'ERR_VID';
                return $val;
                
            }
            // если есть & в строке, то вырезаем все перед ним
            if(strpos($code,'&')) $code = substr($code,0,strpos($code,'&'));
            return $code;

        }
        
        $this -> err[] = 'ERR_VID_EMPTY';
        return '';
        
        
    }
    
    private function isDate($val){
        
        if($val == '') return time();//создание текущей TS если польз-ль ничего не указал
        
        $reg = '/^(\d\d){1,2}[\.\/-]\d{1,2}[\.\/-](\d\d){1,2}$/';// XXXX-XX-XXXX
        if(preg_match($reg,$val)){

            $arr = preg_split("/\.|\/|-/", $val);// разбив в массив по . / или -
            $mon = $arr[1];// месяц
            
            if($mon <= 31){
                
                $len2 = strlen($arr[2]);// длина последнего элемента даты
                $len0 = strlen($arr[0]);// длина первого элемента даты

                // если 4 цифры в конце и 2 в начале то это год и день
                // или по две в конце и начале                        
                $ts = $this -> checkTS($len2, $len0, $mon, $arr[0], $arr[2]);
                if($ts != false && $ts > 0) return $ts;// TS получен
                
                // если 2 цифры в конце и 4 в начале то это день и год
                // или по две в конце и начале
                $ts = $this -> checkTS($len0, $len2, $mon, $arr[2], $arr[0]);
                if($ts != false && $ts > 0) return $ts;// TS получен
                      
                $this -> err[] = 'ERR_FORMAT_DATE';
                return $val;
            }
        }
        $this -> err[] = 'ERR_DATE';    
        return $val;
        //mktime(0,0,0,3,12,2016);// создание тайм стэмп
        //echo strftime ('%d-%m-%Y',mktime(0,0,0,$mon,$day,$year) );//создание отформатир даты из ТС
    }
    
    private function checkTS($len2, $len0, $mon, $day, $year){
        if(($len2 == 4) && ($len0 == 2) || ($len0 == 2) && ($len2 == 2))
            return mktime(0,0,0,$mon,$day,$year);//создание ТС из даты польз-ля    
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















    