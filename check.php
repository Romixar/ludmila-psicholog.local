<?php

class Check{// проверка входящих данных
    
    
    public $err = [];// массив ошибок
    
    
    

    
    
    public function checkData($data){// отправляю на проверку каждое поле
        
        
        
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
        
        
        
        return $data;
//        $controller = new Controller();
//        $controller -> getDoubleArr($data);// на создание двумерного массива

//        $data = $this -> getDoubleArr($data);// на создание двумерного массива
//
//        $this -> checkBox($data);// на проверку нужно ли установить чекбоксы
        
    }
    
    
    
    public function checkTags($key, $val){
        
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
    
    
    
    public function checkLenFields($key, $val){
        
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
    
    
    
    
    private function checkPrice($key,$val,$int,$mnt){
        
        if(!preg_match("/^\d{0,".$int."}(\.|\,)?\d{0,".$int."}$/",$val)){
            $this -> err[$key] = 'ERR_PRICE';
            return $val;
        }
        return $this -> formatPrice($val,$mnt,'');// если все ок, вернуть форматир цену
    }
    
    
    
    private function formatPrice($val,$mnt,$sep){
        
        $val = str_replace(',','.',$val);// заменяю запятую на точку
        if(strpos($val,'.') == 0) $val = '0'.$val;// ексли первой пришла тчк
        
        return number_format($val, $mnt, ".", $sep);// окр до указанного кол-ва знаков после запятой
        
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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}




?>