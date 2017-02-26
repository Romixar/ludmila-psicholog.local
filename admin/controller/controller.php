<?php

// http://easyregexp.ru/constructor - конструктор регулярок

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
    
	public $cl; // будет конкретный объект нужной подмодели при получении POST
	
	public $arr = [];// массив для экземпяров объектов подмоделей (в них названий шаблона)
	
	public $mes;// Объект вывода системных сообщений
    public $sysmes = ''; // шаблон системного сообщения
	public $login;// Объект 
    
    public $err = [];// здесь буду собирать ошибки в полях ввода
    
    public $openfield;// название класса у которого нажато добавить
    
    public $arr_func = [];// массив ключа по "-", у кнопки отправить на конкретной форме


    
    
    
	
	public function __construct(){
		
		$this -> mes = new Messages();// Объект вывода системных сообщений
        
		if(!empty($_POST)) $this -> xss($_POST);// отправляю на проверку
		
        // true флаг, то что это GET id массив
		if(!empty($_GET)) if(!empty($_GET['id'])) $this -> xss($_GET, true);
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
    
    // создание двумерного массива из ассоциативного
    public function getDoubleArr($data){
        $newdata = [];// здесь будет новый двумерный массив
        
		foreach($data as $key => $val){
				
			if($pos = strpos($key,'_')){
				$num = substr($key,$pos + 1);// взять номер поля
				$newdata[$num][substr($key,0,$pos)] = $val;// делать эл-т под этим номером
			}else $newdata[$key] = $val;// кнопка с какой формы пришел массив
		}
        return $newdata;
    }
	
    
    public function checkData($data){// отправка на проверку
        
        if(isset($data['do_login'])){
            $this -> data = $data;// передаю во внутр массив для логин контроллера
            return;
        }
        $this->validateAndCheck($data);
        
    }
    
    public function validateAndCheck($data){
        $check = new Check();// Объект для проверки входных данных
		        
        $data = $check -> checkData($data);// отправляю на проверку типа данных

        $this -> err = $check -> err;// получаю массив ошибок

        $this -> checkBox($this -> getDoubleArr($data));// на проверку нужно ли установить чекбоксы
    }
    
    
    
    public function getErrors($data){// добавляю коды ошибок в массив данных        
        
        $errors = array_unique($this -> err);// отфильтровывваю повторные ошибки

        foreach($errors as $k => $val){
            $this->sysmes .= $this -> mes -> getMessage($val);// вывод сообщений ошибок без повторных
        }
        $newerr = $this -> getDoubleArr($this -> err);//получаю двум-й массив кодов ошибок

        // создание доп. элемента с пометкой в ключе об ошибке для вывода в форму
        for($i=0; $i<count($data); $i++){
            foreach($data[$i] as $key => $val){
                if(isset($newerr[$i][$key])) $data[$i][$key.'_err'] = 1;
            }
        }
        //  ... теперь направить обратно в форму с указанием ошибок...
        return $data;
        
    }
    
    
    private function deleteElem($elem, $key=false){
        if(!$key) unset($elem);
        else{
            unset($elem[$key]);
            return $elem;// вернуть массив без элемента обратно тому кто просил
        }
    }
    
    // выбирается нужен чекбокс массиву формы или нет на основании флага в классе подмодели
    // если нужен, то проставляются соответсвующие значения
    private function checkBox($data){
                
        // получаю массив без submit и массмв с парам-ми этой кнопки
        //$data = $this -> getKeySubmit($data, $arr_func);
        $data = $this -> getKeySubmit($data);
        
        $arr_func = $this -> arr_func;

        // получаю флаг наличия чекбоксов (нужны ли они вообще)
        if($arr_func[1]::$checkBox){// если чеабоксы данная форма использует
            for($i=0; $i<count($data); $i++){
                if(isset($data[$i]['view'])) $data[$i]['view'] = 1;
                else $data[$i]['view'] = 0;   
            }
        }
        $this -> checkErrors($data);// вывести ошибки или дальше отправить в БД
        
    }
    

    private function checkErrors($data){
        
        if(!empty($this -> err)){// если ошибки есть
            $data = $this -> getErrors($data);// объединяю с массивом полей ошибки, если есть

            $data[implode('-',$this -> arr_func)] = 'кнопка отправить';// вернуть наместо кнопку
            
            $this -> displayErrorForm($data);// отправка на вывод массив с ошибками и кнопкой отправитть
            return;
        }
        // если нет ошибок
        $data[implode('-',$this -> arr_func)] = 'кнопка отправить';// вернуть наместо кнопку
        
        $this -> save($data);// определит на UPDATE или INSERT идут данные
    }
    
    
    private function getKeySubmit($data){// вернуть параметры (name) кнопки в массиве

        //$arr_func = [];// здесь будет ключ кнопки отправить, разбитый по дефису 
		$newdata = [];// здесь будет массив данных без кнокпи отправить

		foreach($data as $key => $val){
			
			if(strpos($key,'-')){
				//$arr_func = explode('-',$key);// получаю ключ кнопки отправить
				$this -> arr_func = explode('-',$key);// получаю ключ кнопки отправить
				continue;
			}
			$newdata[$key] = $val;// получаю чистый массив (без кнопки отправить)
		}
        return $newdata;// возвращаю массив без кнопки отправить
    }
  
    
    
    // будет создан объект, который прописан в submit кнопке 
    // и проверка какой форме открыть поле для добавления, при нажании ДОБАВИТЬ
	private function selectAction($data){// выбор действия по ключу кнопки отправить
        
        $newdata = $this -> getKeySubmit($data);// получаю ключ кнопки и массив без него
        
		$this -> cl = new $this -> arr_func[1]();//беру название класса, кот-й запустить
        
		// для actionAll название формы, в кот-й открыть поле
		if($this -> arr_func[0] == 'add') $this -> openfield = $this -> arr_func[1];

		return $newdata;
	}
		
	
	public function save($data){// определим добавить в БД или только обновить
        
        
        
		$data = $this -> selectAction($data);// получаю без submit массив для внесения в БД
        
        
        
        
        // запрос кол-ва записей в БД у полученного в selectAction объекта
		$count = $this -> cl -> countRow();
        
        
        
        
		if($count == count($data)) $this -> update($data);// отправляю на обновление            
        else{
			$up_data = array_slice($data, 0, count($data)-1);//UPDATE без одного последнего элемента
			$this -> update($up_data);// на обновление
			
			$ins_data = $data[count($data) - 1];//а последний элмент отправляю на INSERT
            $this -> insert($ins_data);// на вставку нового элемента
            
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
    
			$res = $this -> cl -> update($arr, $params);// построчная отправка на UPDATE
		}
        
        // проверка последнего ответа
		if(!$res){
            if($this -> arr_func[0] != 'add'){// чтобы не вывелось при нажатии ДОБАВИТЬ
                $this->sysmes = $this->mes->getMessage('SUC_SAVE',$this->getClassName($this->cl));
            }
        }else $this->sysmes = $this -> mes -> getMessage('ERR_SAVE');

	}
    
    
    public function insert($data){
        
        $cols = array_keys($data);// названия столбцов будут для подстановок и сами названия
		$params = [];// здесь будут параметры для подстановки
        
        foreach($data as $key => $val){
			
			$key = str_replace('`','',$key);
            $params[':'.$key] = $val;// указываю элемент без кавычек
		}
        if($this -> cl -> insert($cols, $params)) // должен прийти ID последней доб записи
            $this -> mes -> getMessage('SUC_ADD',$this -> getClassName($this -> cl));
        else $this -> mes -> getMessage('ERR_ADD');
        
    }
	
    
    
	public function checkOnDelete($get){//проверка надо ли удалять элемент из БД

		try{// отлов исключений при удалении из базы данных		
			if(strpos($get['id'],'_')){
                
				$arr = explode('_',$get['id']);
				if(count($arr) == 2){

					if(!$id = $this -> isIntNum($arr[1])) return false;
                    
					if($this -> checkClassName($arr[0])){
						
						$myclass = new $arr[0]();// созд объект, у которого удалить элемент
                        
						// проверить есть ли такой id и удалить
						if($myclass -> checkID($id)) $this -> mes -> getMessage('SUC_DEL');
                        //else $this -> mes -> getMessage('ERR_DEL');
					}
				}
			}
		}catch(Exception $e){
			//$view = new View();
            $view = new ViewsController();
			$view -> err = $e -> getMessage();
			$view -> display('error');
		}
	}

    
    
	private function checkClassName($cl_name_str){// проверяю наличие класса
		if(preg_match('/^[a-z]{4,11}$/',$cl_name_str))
			if(class_exists($cl_name_str)) return true;
		return false;
	}
	
    private function getClassName($obj){
		return strtolower(get_class($obj));// получаю строковое имя класса созданного объекта
	}
    
    
	private function isIntNum($num){// проверяю число может ли оно быть ID
		if((int)$num != 0 || is_int($num)) return (int)$num;
		return false;
	}
	
    
	
    
    
    public function displayErrorForm($data){// показ формы с ошибками (только одна форма)


        $data = $this -> getKeySubmit($data); // получаю форму БЕЗ кнопки отправить

        
        for($i=0; $i<count($this->arr); $i++){// перебор двух объектов страницы

            if($this->getClassName($this->arr[$i]) == $this -> arr_func[1]){
                    
                $tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы
                
                $func = $this -> arr_func[1];
                
                for($j=0; $j<count($data); $j++){
                    
                    $view = new ViewsController();
                    
                    foreach($data[$j] as $k => $v) $view -> $k = $v;
                    
                    $tmp[$j] = $view; // создаю массив объектов
                    
                }
                
                $data = $tmp;
                
                $content = $view -> prerender($tmpl,compact('data','func'));
            }
        }
        
        for($i=0; $i<count($this -> arr_func); $i++){// удаляю тот объект формы, в которой были ошибки
            if($this -> arr_func[1] == $this->getClassName($this->arr[$i])){
                $num_el = $i; // получаю номер удаляемого жлемента
                unset($this->arr[$i]);
            }
        }
        
        $this->prepareTmpl($buttons, $title); // получить шаблон кнопок и заголовок
        
        $mes = $this -> sysmes;   // сообщения об ошибках

        $view -> vars = compact('buttons','title','mes');

        
        $view -> render('content',[$tmpl=>$content]);

        sort($this->arr);// отсортировка, чтобы номера ключей стали с 0

    }
	
    
    private function prepareTmpl(&$buttons, &$title, &$mes){
        $view = new ViewsController();
        $buttons = $view -> prerender('buttons');// получаю кнопки в админке
        $cl_name = get_class($this);// title для текущ страницы
        $title = $cl_name::$title;   // title для текущ страницы
        if($this->sysmes) $mes = $this->sysmes;
        else $mes = '';
    }
	
	
	public function actionAll(){// вывести весь нужный контент (все таблицы на страницу)
        
        if(count($this->err) != 0) return;// если были ошибки от юзера, ничего не выводим
        
        $this->prepareTmpl($buttons, $title, $mes);
        
        $view = new ViewsController();
        
        $view -> vars = compact('buttons','title','mes');
        
        $content = [];// будут шаблоны форм для страницы
        
        for($i=0; $i<count($this -> arr); $i++){
            
            $arrObj = $this -> arr[$i] -> selectAll();// получаю массив объектов строк из БД
            
            $tmpl = $this -> arr[$i] -> tmpl;// получаю имя шаблона для вывода таблицы

            // получаю имя класса созданного объекта это будет ключ для submit
            $func = $this -> getClassName($this -> arr[$i]);

            $data = [];// перед началом второй итерации обнуляю
        
            for($j=0; $j<count($arrObj); $j++) $data[$j] = $arrObj[$j];
            
            if($this->openfield == $func) $open = true;// флаг д/откр поля в конкр форме
            else $open = false;
            
            $content[$tmpl] = $view -> prerender($tmpl,compact('data','func','open'));


        }
        $view -> render('content',$content);
        
	}
	
	
	
	
	
	
	
	
	
	
}















    
