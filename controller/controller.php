<?php

// http://easyregexp.ru/constructor - конструктор регулярок

class Controller{
	
	public $data = [];// POST GET параметры от пользователя
    
	public $cl; // будет конкретный объект нужной подмодели при получении POST
	
	public $arr = [];// массив для экземпяров объектов подмоделей (в них названий шаблона)
	
	public $mes;// Объект вывода системных сообщений
    
    public $err = [];// здесь буду собирать ошибки в полях ввода
    
    public $openfield;// название класса у которого открыть поля для добавления
    
    public $check;// объект проверка
    
    
    
	
	public function __construct(){
		
		$this -> mes = new Messages();// Объект вывода системных сообщений
        
        
		if(!empty($_POST)) $this -> xss($_POST);// отправляю на проверку
		
		if(!empty($_GET)){
			if(!empty($_GET['id'])) $this -> xss($_GET, true);// флаг, то что это GET id массив
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
			//else $this  -> checkData($data);// отправляю провереный массив на проверку типа данных
			else{
                $this -> check = new Check();// Объект для проверки входных данных
		        
                $data = $this -> check -> checkData($data);// отправляю провереный массив на проверку типа данных
                
                
                
                $this -> err = $this -> check -> err;// получаю массив ошибок
                
                
                
                
                $data = $this -> getDoubleArr($data);
                
                
                
                
                
                
                $this -> checkBox($newdata);// на проверку нужно ли установить чекбоксы
                
                
            }
            
		}
		return false;
		
	}
    
    // создание двумерног8о массива из ассоциативного
    public function getDoubleArr($data){
        $newdata = [];// здесь будет новый двумерный массив
        
		foreach($data as $key => $val){
				
			if($pos = strpos($key,'_')){
				$num = substr($key,$pos + 1);// взять номер поля
				$newdata[$num][substr($key,0,$pos)] = $val;// делать эл-т под этим номером
			}else $newdata[$key] = $val;// кнопка с какой формы пришел массив
		}

        //$this -> checkBox($newdata);// на проверку нужно ли установить чекбоксы
        return $newdata;
    }
	

    
    
    
    private function checkData($data){// отправляю на проверку каждое поле
        
        
        foreach($data as $key => $val){
            
            // удаляю теги у всех полей кроме описание услуг и тело отзыва
            $val = $this -> checkTags($key, $val);// возвращаю в $val потому что не ошибка
            
            // проверяю на длину каждое текстовое поле
            $data[$key] = $this -> checkLenFields($key, $val);
            
            // проверка и форматир цены для внесения в БД
            if(strpos($key,'price_') !== false) $data[$key] = $this -> checkPrice($key,$val,6,2);
            
            // проверка от внешних и внутр-х пользователей
            if(strpos($key,'phone') !== false) $data[$key] = $this -> checkPhone($key,$val);
            
            // возвращаю дату в виде TS
            if(strpos($key,'dateadd_') !== false) $data[$key] = $this -> isDate($key,$val);
            
            // проверка ЮТУБ адреса и возвращаю только код видео
            if(strpos($key,'url_') !== false) $data[$key] = $this -> checkYouTubeURL($key,$val);
            
            // проверка отзыва от внешних пользователей
            if(strpos($key,'message') !== false){
                $data[$key] = $this -> checkMessage($key,$val);// если успешный отзыв, то придёт пустой
    
                //if(empty($data[$key])) $this -> deleteElem($data, $key);// удаляю его
            }
            
            // проверка от внешнего пользователя либо от админа
            if(strpos($key,'email') !== false) $data[$key] = $this -> checkEmail($key,$val);
            
        }

        $data = $this -> getDoubleArr($data);// на создание двумерного массива

        $this -> checkBox($data);// на проверку нужно ли установить чекбоксы
        
    }
    
    
    private function checkTags($key, $val){
        
        
        // удаляю теги у всех полей кроме этих (описание услуг, тело отзыва)
        if(strpos($key,'description_') !== false || strpos($key,'body_') !== false){
            $val = htmlspecialchars_decode($val);
            //$data[$key] = $this -> checkLen($key,$val,3000);// заодно проверка на длину
            $val = $this -> checkLen($key,$val,3000);// заодно проверка на длину
        }else{
            $val = htmlspecialchars_decode($val);
            $val = strip_tags($val);// удаляю теги во всех остальных полях
        }
        return $val;
    }
    
    private function checkLenFields($key, $val){
        
        
        // проверка на длину поле длительность занятий
        if(strpos($key,'duration_') !== false) return $this -> checkLen($key,$val,50);
            
        // проверка на длину поле превью и тело отзыва
        if(strpos($key,'head_') !== false) return $this -> checkLen($key,$val,400);
            
        //проверка на длину поле заголовков, имен от внеш и внутрн-х польз-й
        // авторов видео, адрес, скайп
        if((strpos($key,'title_') !== false) || (strpos($key,'author_') !== false) || (strpos($key,'addr_') !== false) || (strpos($key,'skype_') !== false) || (strpos($key,'name') !== false))
            return $this -> checkLen($key,$val,255);
        return $val;
    }
    
    private function checkLen($key,$val,$size){
        
        
        if(strlen($val) > $size) $this -> err[$key] = 'ERR_LEN';// превышена длина заполнения поля
        return $val;
    }
    
    
    
    
    
    public function getErrors($data){// добавляю коды ошибок в массив данных
        
        
        
        
        $errors = array_unique($this -> err);// отфильтровывваю повторные ошибки
        
        
        
            
        foreach($errors as $k => $val){
            $this -> mes -> getMessage($val);// вывод сообщений ошибок без повторных
        }
        
        $newerr = $this -> getDoubleArr($this -> err);//получаю двум-й массив кодов ошибок
        
        
        


        // создание доп. элемента с пометкой в ключе об ошибке для вывода в форму
        for($i=0; $i<count($data); $i++){
            foreach($data[$i] as $key => $val){
                if(isset($newerr[$i][$key])) $data[$i][$key.'-err'] = 1;
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
        
        
//        echo '<pre>';
//        print_r($this -> err);
//        echo '</pre>';
        
        // получаю массив без submit и массмв с парам-ми этой кнопки
        $data = $this -> getKeySubmit($data, $arr_func);
        
        
        
        // получаю флаг наличия чекбоксов (нужны ли они вообще)
        if($arr_func[1]::$checkBox){// если чеабоксы данная форма использует
            for($i=0; $i<count($data); $i++){
                if(isset($data[$i]['view'])) $data[$i]['view'] = 1;
                else $data[$i]['view'] = 0;   
            }
        }
        
        
        
        $this -> checkErrors($data, $arr_func);// вывести ошибки или дальше отправить в БД
        
    }
    

    private function checkErrors($data, $arr_func){
        
        
        
        if(!empty($this -> err)){// если ошибки есть
            $data = $this -> getErrors($data);// объединяю с массивом полей ошибки, если есть
            
            $data[implode('-',$arr_func)] = 'кнопка отправить';// вернуть наместо кнопку
            
            
            
            
            $this -> displayErrorForm($data);// отправка на вывод массив с ошибками и кнопкой отправитть
            return;
            
        }
        // если нет ошибок
        $data[implode('-',$arr_func)] = 'кнопка отправить';// вернуть наместо кнопку
        
        $this -> save($data);// определит на UPDATE или INSERT идут данные
        
        
    }
    
    
    private function getKeySubmit($data, &$arr_func){// вернуть параметры (name) кнопки в массиве

        
        $arr_func = [];// здесь будет ключ кнопки отправить, разбитый по дефису 
		$newdata = [];// здесь будет массив данных без кнокпи отправить

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
        
		foreach($data as $key => $val){
			
			if(strpos($key,'-')){
				$arr_func = explode('-',$key);// получаю ключ кнопки отправить
				continue;
			}
			$newdata[$key] = $val;// получаю чистый массив (без кнопки отправить)
		}
        return $newdata;// возвращаю массив без кнопки отправить
          
    }
    
    
    private function checkPrice($key,$val,$int,$mnt){
        
        
        if(!preg_match("/^\d{0,".$int."}(\.|\,)?\d{0,".$int."}$/",$val)){
            $this -> err[$key] = 'ERR_PRICE';
            return $val;
        }
        return $this -> formatPrice($val,$mnt,'');// если все ок, вернуть форматир цену
    }
    
    private function checkPhone($key,$val){
        
        
        $reg = ['/\+/','/\s/','/-/','/_/','/\(/','/\)/','/\./','/\,/'];// убрать из тел номера
        $val = preg_replace($reg,'',$val);
        
        if(preg_match('/^\d{4,15}$/', $val)) return $val;
        else{
            $this -> err[$key] = 'ERR_PHONE';
            return $val;
        }
        
        
    }
    
    private function formatPrice($val,$mnt,$sep){
        
        
        $val = str_replace(',','.',$val);// заменяю запятую на точку
        if(strpos($val,'.') == 0) $val = '0'.$val;// ексли первой пришла тчк
        
        return number_format($val, $mnt, ".", $sep);// окр до указанного кол-ва знаков после запятой
        
    }
    
    
    private function checkYouTubeURL($key, $val){
        
        
        if($val != ''){
            // если кол-во совпадений не равно 1, то ссылка НЕ правильная
            if(preg_match_all('/youtu/',$val) == 1){
                
                $code = '';// здесь будет код YOUTUBE видеоролика
            
                if(strpos($val,'youtube.com/watch?v=') !== false){
                    $pos = strpos($val,'=');// вырезаем строку после знака =
                    $code = substr($val, $pos + 1);
                }
                if(strpos($val,'youtu.be/') !== false){
                    $pos = strpos($val,'.');
                    $code = substr($val, $pos + 4);// вырезать и вернуть всё пос
                }
                if(strpos($val,'youtube.com/embed') !== false){
                    $pos = strpos($val,'d');
                    $code = substr($val, $pos + 2); // возращаю все доконца строки начиная с /
                }
                if($code != ''){// если код не пустой, то ещё проверим на GET парам-ры
                    // если есть & в строке, то вырезаем все перед ним
                    if(strpos($code,'&')) $code = substr($code,0,strpos($code,'&'));
                    return $code;
                }
            }
            $this -> err[$key] = 'ERR_VID';
            return $val;
        }
        $this -> err[$key] = 'ERR_VID_EMPTY';
        return '';
        
    }
    
    
    
    // получено поле $message от внешнего польз-ля либо админа
    // проверяю на длину и на корректность длины каждого слова 
    // и отправляю либо на проверку отзыва либо обратно для отправки письма
    private function checkMessage($key, $mes){
        
        
        $len = strlen($mes);// длина всего отзыва
        if($len < 3000){
            
            $arrwords = explode(' ',$mes);// разделим отзыв на отдельные слова
            $endCheck = 'false';// флаг досрочного завершения проверки
            
            for($i=0; $i<count($arrwords); $i++){// проверка каждого слова на длину
                
                if(strlen($arrwords[$i]) > 30){
                    $this -> err[$key] = 'ERR_MESS';// если найдено, то ошибка
                    $endCheck = true;
                    break;
                }    
            }
            if(!$endCheck){
                // если внешний польз-ль отправляет письмо
                if(isset($this -> data['newmail'])) return $mes;// для отправки письма
                else $this -> checkTestmon($mes, $len);// это отправлен отзыв, проверить
                return;// завершить
            }
        }else $this -> err[$key] = 'ERR_LEN_TESTM';// если большой, то ошибка превышения
        
        return $mes;// значит сгенерирована ошибка
    }
    
    
    
    
    // получение отзыва от внешнего пользователя
    // упаковка их в новые элементы body И head в зависимости от кол-ва текста
    public function checkTestmon($mes, $len){//проверка длину и рассортировка (введение, текст)
        
        
//        $len = strlen($mes);// длина всего отзыва
//        if($len < 3000){
//            
//            $arrwords = explode(' ',$mes);// разделим отзыв на отдельные слова
//            $endCheck = 'false';// флаг досрочного завершения проверки
//            
//            for($i=0; $i<count($arrwords); $i++){// проверка каждого слова на длину
//                
//                if(strlen($arrwords[$i]) > 30){
//                    $this -> err[] = 'ERR_TESTM';// если найдено, то ошибка
//                    $endCheck = true;
//                    break;
//                }    
//            }
//            
//            if(!$endCheck){// проверяю дальше, если цикл не завершился досрочно
                
                
                // создаем нужные элементы массива для вставки в БД и вывод на сайт
                if($len < 200){
                    $this -> data['head'] = $mes;// тогда весь отзыв это вступление
                    $this -> data['body'] = '';
                }

                if($len > 200 && $len < 400) $otr = 150;

                if($len > 400) $otr = 350;// тогда вступление будет длиной 350

                if($len > 200){

                    $str160 = substr($mes,0,$otr);// 1-й кусок от 0 длиной 160 символов

                    $strEnd = substr($mes,$otr);// 3-й кусок будет телом отзыва

                    $pos = strpos($strEnd, ' ');//номер позиции первого пробела после 160-го символов

                    $prepost = substr($strEnd,0,$pos+1);// кусок строки до первого пробела (2-й кусок)

                    $this -> data['head'] = $str160.''.$prepost;//заголов сложить из первых двух кусков

                    // остальной текст будет телом отзыва (вырезаю с символа окончания вступления)
                    $this -> data['body'] = substr($strEnd, strlen($prepost)-1);
                }
                return; // выходим из метода
        
                
//            }
//            
//            
//        }else $this -> err[] = 'ERR_LEN_TESTM';// если большой, то ошибка
//        
//        return $mes;// некорректный отзыв обратно внешнему пользователю
        
    }
    
    private function checkEmail($key, $val){
        
        
        if(empty($val)){
            $this -> err[$key] = 'ERR_EMPTY_EMAIL';
            return;// закончить выполнение метода
        }
        if(strpos($val,'@') !== false){
            $arr = explode('@',$val);
            if(count($arr) == 2){// значит несколько знаков @   
                // регулярка для проверки e-mail
                $reg = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i';
                if(preg_match($reg,$val)) return $val;
            }
        }
        $this -> err[$key] = 'ERR_EMAIL';
        return $val;
        
        
    }
    
    
    private function isDate($key, $val){
        
        
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
                      
                $this -> err[$key] = 'ERR_FORMAT_DATE';
                return $val;
            }
        }
        $this -> err[$key] = 'ERR_DATE';    
        return $val;
        //mktime(0,0,0,3,12,2016);// создание тайм стэмп
        //echo strftime ('%d-%m-%Y',mktime(0,0,0,$mon,$day,$year) );//создание отформатир даты из ТС
    }
    
    private function checkTS($len2, $len0, $mon, $day, $year){
        
        if(($len2 == 4) && ($len0 == 2) || ($len0 == 2) && ($len2 == 2))
        
            return mktime(0,0,0,$mon,$day,$year);//создание ТС из даты польз-ля    
    }
	
    
    
    
    
    
    
    // будет создан объект, который прописан в submit кнопке 
    // и проверка какой форме открыть поле для добавления, при нажании ДОБАВИТЬ
	private function selectAction($data){// выбор действия по ключу кнопки отправить
        
        $newdata = $this -> getKeySubmit($data, $arr_func);// получаю ключ кнопки и массив без него
		$this -> cl = new $arr_func[1]();//беру название класса, кот-й запустить
        
		// для actionAll название формы, в кот-й открыть поле
		if($arr_func[0] == 'add') $this -> openfield = $arr_func[1];

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
		if(!$res) $this -> mes -> getMessage('SUC_SAVE',$this -> getClassName($this -> cl));
        else $this -> mes -> getMessage('ERR_SAVE');

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
			$view = new View();
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
		return strtolower(get_class($obj));// получаю строковое имя класс созданного объекта
	}
    
    
	private function isIntNum($num){// проверяю число может ли оно быть ID
		if((int)$num != 0 || is_int($num)) return (int)$num;
		return false;
	}
	
    
	
    
    
    public function displayErrorForm($data){
        
        $view = new View();
        
        $data = $this -> getKeySubmit($data, $arr_func);
        
        
        
        for($i=0; $i<count($this->arr); $i++){// перебор двух объектов страницы

            if($this->getClassName($this->arr[$i]) == $arr_func[1]){
                    
                $tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы
                
                for($j=0; $j<count($data); $j++){
                    
                    $view -> data[$j] = $data[$j];// добавляю массив с ошибками в объект
                         
                }
            }
        }
               
        for($i=0; $i<count($arr_func); $i++){// удаляю тот объект формы, в которой были ошибки
            if($arr_func[1] == $this->getClassName($this->arr[$i])){
                $num_el = $i; // получаю номер удаляемого жлемента
                unset($this->arr[$i]);
            }
        }
        
        $view -> func = $arr_func[1];//идентификатор для submit
        $view -> display($tmpl);// вывод неверно заполненной формы
        sort($this->arr);// отсортировка, чтобы номера ключей стали с 0
        
        

    }
	
	
	
	public function actionAll(){// вывести весь нужный контент

        if(count($this->err) != 0) return;// значит были ошибки от юзера, поэтому ничего не выводим
        
        $view = new View();
        // вывожу все из БД, если нет ошибок
        for($i=0; $i<count($this -> arr); $i++){// иначе выводим из БД, что в ней сохранилось
			
            $arrObj = $this -> arr[$i] -> selectAll();// получаю массив объектов строк из БД
            $tmpl = $this -> arr[$i] -> tmpl;// получаю свойство имя шаблона для вывода таблицы
            $view -> func = '';// здесь будет идентификатор класса, который создает страницу

            $class_name = $this -> getClassName($this -> arr[$i]);// получаю имя класс созданного ообъекта

            $view -> data = [];// перед началом второй итерации обнуляю
        
            for($j=0; $j<count($arrObj); $j++){

                $view -> data[$j] = $arrObj[$j] -> data;

            }

            if($this->openfield == $class_name) $view -> open = true;// открываю поле в конкретной форме
            else $view -> open = false;
            
            // добавлю потом в кадую строку название класса, её создавшего
            $view -> func = $class_name;//также это будет идентификатор для submit

            $view -> display($tmpl);// отправляю во view


        }
        
	}
	
	
	
	
	
	
	
	
	
	
}















    