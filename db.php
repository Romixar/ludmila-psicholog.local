<?php

require 'rb.php';// подключение RedBeanPHP библиотеки
require 'config.php';// подключение кофигурационного файла


$config = new config();

if($config::HOST_ADDRESS == 'http://ludmila-psicholog.local/')//выбор БД к которой подключиться
    R::setup( 'mysql:host=localhost;dbname=psicholog-ludmila','root', '' );
else R::setup( 'mysql:host=localhost;dbname=vh190779_ludmila-psicholog','vh190779_rom', 'ERFKCgmu' );


session_start();// переменная сессии доступна на страницах где подключен этот файл



class DB{
    
    public function findRow($table,$field,$val){
        // поиск в таблице конкретной строки (получаю объект)
        return R::findOne($table, "$field = ?", array($val));
    }
    
	// Работа с REDBeanPHP https://www.sitepoint.com/introduction-redbean/
    public function getAllFields($table, $asc){// запрос и получение всего массива из таблицы БД
        //return R::getAll('SELECT * FROM testServixes WHERE view_main = ? ', [ 0 ]);
        return R::getAll('SELECT * FROM `'.$table.'` ORDER BY id '.$asc);
    }
    
    public function getArrayFromObj($obj){
        foreach($obj as $key => $val){// собираю ассоц массив из объекта
            $data[$key] = $val;
        }
        return $data;
    }
    
    // создание двумерного массива из полученной формы
    public function getData($post, $checkboxes = false){
        $data = [];// здесь будет двумерный массив

        foreach($post as $k => $v){
            $field = substr($k,0,strpos($k,'_'));//получаю назв-е поля
            //  HTML теги в HTML сущности для конкр поля (РАЗРЕШАЮ HTML теги) 
            if($field == 'description'||$field == 'body') $post[$k] = $this -> xss($v,true);
            else $post[$k] = $this -> xss($v);// проверка каждого значения поля (удаление HTML)
        }

        $post = $this -> checkData($post);//возвращ-ся проверенный массив данных или FALSE

        if(is_array($post)){// если не массив то значит ошибки в полях
            //$_POST = $this -> checkData($_POST);// Запись проверенных данных
            foreach($post as $key => $val){
                $pos = strpos($key, '_'); // номер позиции _
                if($pos !== false){// отсекаю лишние ключи из $_POST (те которые без _)
                    $num = (int) (substr($key, $pos+1)); // беру символы после _ у ключа (имени поля)
                     // замена пустой строкой последние символы начиная с _ и вовр-ю
                    $key = substr_replace($key, '', -strlen('_'.$num));
                    if(is_int($num)) $data[$num][$key] = $val;
                }
            }
            // проверка чекбоксов (только там где есть $checkboxes)
            if($checkboxes){// существуют чекбоксы если пришло true
                
                for($i=0; $i<count($data); $i++){
                    if($data[$i]['view'] == '') $data[$i]['view'] = 0;
                    else $data[$i]['view'] = 1;
                }
            }
            return $data;// возвращаю двумерный массив
        }else return false;
        
    }
    
    public function saveData($fields, $table){// $fields массив полей для вставки в БД - двум-й массив
        // ВСТАВИТЬ и ОБНОВИТЬ    
        if(count($this->getIDs($table)) !== count($fields)) return 'abc';
        else return $this -> update($fields, $table);// просто только обновить
    }
    
    public function getIDs($table){
        return $ids = R::getAll('SELECT id FROM `'.$table.'`');// получение массива всех id в таблице
    }
    
    public function update($fields, $table, $ids=false){
        
        if(!$ids) $ids = $this -> getIDs($table);// запрос IDs для обновления
        //$query = '';
        //$table = R::getRedBean() -> dispense( 'my_new_tab' );// подчеркивание в названии НЕЛЬЗЯ
        for($i=0; $i<count($ids); $i++){
            $query = '';//очистка чтобы следующ  строку записал
            foreach($fields[$i] as $key => $val){
                $query .= $key.'="'.$val.'",';//запись назв-й полей = `значений`
            }
            $query = substr($query, 0, -1);// удаляю последнюю запятую            
            R::exec( 'UPDATE `'.$table.'` SET '.$query.' WHERE id = '.$ids[$i]['id'] );
        }
        return true;
    }
    
    public function delete($table,$id){
        // поиск строки в таблице БД по ID
        $row = R::findOne($table,'id = ?',array($id));
        // удаляем найденную строку
        if(!$row) return false;
        else R::trash($row);
        return true;
    }
    
    public function xss($data, $flag=false){// защиита входящих данных
        $data = trim($data);//очистка от пробелов
        //замена опасных слов пустой строкой
        $req = '/script|http|www\.|SELECT|UNION|UPDATE|exe|exec|INSERT|tmp/i';
        $data = preg_replace($req,'',$data);
        if($flag) return htmlspecialchars($data); //замена всех HTML тегов на HTML сущности
        return strip_tags($data); //удаление всех HTML тегов
    }
    
    public function checkData($data){
        foreach($data as $key => $val){
            if(strpos($key,'head')!==false){// превью отзыва
                if(!$this->length($val, 400)) return false;// проверка длины
            }
            
            if((strpos($key,'name')!==false) || (strpos($key,'title')!==false)){
                if(!$this->length($val, 255)) return false;// проверка длины
            }
            
            if(strpos($key,'dateadd')!==false){// дата добавления отзыва                
                if($this -> isdate($val)){
                    $data[$key] = $this -> isdate($val);// запись ТС в массив для БД
                }else return false;
            }
            
            if(strpos($key,'price') !== false){// цена услуги
                if(!$this->length($val, 15)) return false;
                if($this -> number($val) !== false) $data[$key] = $this -> number($val);
                else return false;
            }
            
            if(strpos($key,'phone') !== false){
                if(!$this->length($val, 20)) return false;
                if($num = $this -> phonenum($val)) $data[$key] = $num;
                else return false;
            }
            
            if(strpos($key,'duration') !== false){// длительность услуги
                if(!$this->length($val, 20)) return false;
            }
            
            if(strpos($key,'url') !== false){
                // на проверку ссылки на видео
                if($res = $this -> checkURL($val)) $data[$key] = $res;
                else return false;
            }
            
        }
        
        return $data;
    }
    
    public function checkURL($url){// проверка ссылок на ютуб
        
        if(!empty($url)){
            
            $code = '';// здесь будет код YOUTUBE видеоролика
            
            if(strpos($url,'youtube.com/watch?v=') !== false){
                // вырезаем строку после знака =
                $pos = strpos($url,'=');
                $code = substr($url, $pos + 1);

            }
            if(strpos($url,'youtu.be/') !== false){
                $pos = strpos($url,'.');
                $code = substr($url, $pos + 4);// вырезать и вернуть всё после /
                return $code;
            }
            if(strpos($url,'youtube.com/embed') !== false){
                $pos = strpos($url,'d');
                $code = substr($url, $pos + 2); // возращаю все доконца строки начиная с /
                return $code;
            }
            if(!$code){
                echo '<div class="err">Ссылка на видео не YOUTUBE!</div>';
                return false;
            }
            // если есть & в строке, то вырезаем все перед ним
            if(strpos($code,'&')) $code = substr($code,0,strpos($code,'&'));
            return $code;

        }else{
            echo '<div class="err">Поле для ссылки на видео не заполнено!</div>';
            return false;
        }        
    }
    
    public function checkTestmon($data, $mes){//проверка длину и рассортировка (введение, текст)
        
        $len = strlen($mes);// длина всего отзыва
        if($len > 3000) return false;// если большой, то остановить
        $arrwords = explode(' ',$mes);// разделим отзыв на отдельные слова
            
        if(count($arrwords) == 1){// если в отзыве одно слово то остановить
            if($len > 30) return false;
        }
            
        if($len < 200){
            $data['head'] = $mes;// тогда весь отзыв это вступление
            $data['body'] = '';
        }
            
        if($len > 200 && $len < 400) $otr = 150;
            
        if($len > 400) $otr = 350;// тогда вступление будет длиной 250
                
        if($len > 200){
                
            $str160 = substr($mes,0,$otr);// 1-й кусок от 0 длиной 160 символов
            
            $strEnd = substr($mes,$otr);// 3-й кусок будет телом отзыва

            $pos = strpos($strEnd, ' ');//номер позиции первого пробела после 160-го символов

            $prepost = substr($strEnd,0,$pos+1);// кусок строки до первого пробела (2-й кусок)

            $data['head'] = $str160.''.$prepost;//заголов сложить из первых двух кусков

            // остальной текст будет телом отзыва (вырезаю с символа окончания вступления)
            $data['body'] = substr($strEnd, strlen($prepost)-1);
                
        }
        return $data;
        
    }
    
    public function checkErrors($post, $suc, $res = false){
        if(isset($post['savevid'])){
            if($suc && $res !== 'abc') return '<div class="suc">Лента видеороликов сохранена!</div>';
            if($suc && $res === 'abc') return '<div class="suc">Новый видеоролик успешно добавлен!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        if(isset($post['addvid'])){
            if($suc && $res === 'abc') return '<div class="suc">Новый видеоролик успешно добавлен!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        }
        if(isset($post['savetestmon'])){
            if($suc && $res !== 'abc') return '<div class="suc">Таблица отзывов сохранена!</div>';
            if($suc && $res === 'abc') return '<div class="suc">Новый отзыв успешно добавлен!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        if(isset($post['addtestmon'])){
            if($suc && $res === 'abc') return '<div class="suc">Новый отзыв успешно добавлен!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        }
		
		
		
		if(isset($post['saveserv'])){
            if($suc && $res !== 'abc') return '<div class="suc">Таблица цен на услуги сохранена!</div>';
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        if(isset($post['addserv'])){
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        }
        if(isset($post['savedesc'])){
            if($suc && $res !== 'abc') return '<div class="suc">Названия и описания услуг сохранены!</div>';
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        if(isset($post['adddesc'])){
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        }
		
		
		if(isset($post['savecontact'])){
            if($suc && $res !== 'abc') return '<div class="suc">Личные данные сохранены!</div>';
            //if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        //if(isset($post['addserv'])){
            //if($suc && $res === 'abc') return '<div class="suc">Новая услуга добавлена!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        //}
        if(isset($post['savework'])){
            if($suc && $res !== 'abc') return '<div class="suc">Оказываемые услуги сохранены!</div>';
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга успешно добавлена!</div>';
            if(!$suc && $res !== 'abc') return '<div class="err">Ошибка при сохраненении!</div>';
        }
        if(isset($post['addwork'])){
            if($suc && $res === 'abc') return '<div class="suc">Новая услуга успешно добавлена!</div>';
            //else return '<div class="err">Ошибка при добавлении!</div>';
        }
		
        
        
        
    }
    
    public function phoneFormat($phone){// входят цифры, вывести в формате тел. номера
        
        $len = strlen($phone);
        if($len == 11){
            
            $phone1 = substr($phone,0,1);
            $phone2 = substr($phone,1,3);
            $phone3 = substr($phone,4,3);
            $phone4 = substr($phone,7,2);
            $phone5 = substr($phone,9,2);
            
            $phone = $phone1.'-'.$phone2.'-'.$phone3.'-'.$phone4.'-'.$phone5;
            if($phone[0] == 7) $phone = '+'.$phone;
            return $phone;
        }
        if($len == 6){
            
            $phone1 = substr($phone,0,2);
            $phone2 = substr($phone,2,2);
            $phone3 = substr($phone,4,2);
            
            $phone = $phone1.'-'.$phone2.'-'.$phone3;
            return $phone;
        }
        if($len == 9){
            $phone1 = substr($phone,0,3);
            $phone2 = substr($phone,3,2);
            $phone3 = substr($phone,5,2);
            $phone4 = substr($phone,7,2);
            
            $phone = '('.$phone1.') '.$phone2.'-'.$phone3.'-'.$phone4;
            return $phone;
        }
        if($len == 10){
            $phone1 = substr($phone,0,4);
            $phone2 = substr($phone,4,2);
            $phone3 = substr($phone,6,2);
            $phone4 = substr($phone,8,2);
            
            $phone = '('.$phone1.') '.$phone2.'-'.$phone3.'-'.$phone4;
            return $phone;
        }
        return $phone;
        
    }
    
    public function phonenum($val){// готовлю к загрузке в БД
        
        $val = trim($val);
        $reg = ['/\+/','/\s/','/-/','/_/','/\(/','/\)/','/\./','/\,/'];// убрать из тел номера
        $val = preg_replace($reg,'',$val);
        
        if(preg_match('/^\d+$/', $val)) return $val;
        else{
            echo '<div class="err">В поле ТЕЛЕФОН должно быть число!</div>';
            return false;
        }
        
    }
    
    public function number($val){// для вставки в БД подготовка числового значеия
        $val = str_replace(',','.', $val);//заменяю запятую в ценах, если есть
        //echo $val;
        if(is_numeric($val)) return $val;
        else{
            echo '<div class="err">В поле СТОИМОСТЬ должно быть число!</div>';
            return false;
        }
    }
    
    public function length($val, $num){//проверка длины поля набираемого пользователем
        if(strlen($val) <= $num) return true;
        else{
            echo '<div class="err">Превышена длина вводимых данных!</div>';
            return false;
        }
    }
    
    public function isdate($val){//проверка даты
        if($val != ''){
            $reg = '/^(\d\d){1,2}[\.\/-]\d{1,2}[\.\/-]\d{1,2}$/';// XXXX-XX-XX
            if(preg_match($reg,$val)){

                $arr = preg_split("/\.|\/|-/", $val);// разбив в массив по . / или -
                $day = $arr[2];//день
                $mon = $arr[1];//месяц
                $year = $arr[0];//год
                return mktime(0,0,0,$mon,$day,$year);//создание ТС из даты польз-ля

            }else{
                echo '<div class="err">Неверный формат даты добавления отзыва!</div>';
                return false;
            }          
            //mktime(0,0,0,3,12,2016);// создание тайм стэмп

            //echo strftime ('%d-%m-%Y',mktime(0,0,0,$mon,$day,$year) );//создание отформатир даты из ТС
        }else return time();//создание текущей ТС если польз-ль ничего не указал
    }
    
    
    
    
    
    
    
    
    
    public function uploadIMG($file, $max_size, $dir, $root = false, $source_name = false){
        
        $blacklist = array('.php','.phtml','.php3','.php4','.html','.htm');
        
        foreach($blacklist as $item){
            
            if(preg_match("/$item\$/", $file['name'])){
                echo '<div class="err">Недопустимое расширение файла!</div>';
                return false;
            }
            
        }
        
        $type = $file['type'];
        $size = $file['size'];
        if(($type != 'image/jpg') && ($type != 'image/jpeg') && ($type != 'image/gif') && ($type != 'image/png')){
            echo '<div class="err">Недопустимый тип файла!</div>';
            return false;
        }
        if($size > $max_size){
            echo '<div class="err">Превышен допустимый размер файла!</div>';
            return false;
        }
        // если имя IMG есть, то оставляем его иначе генерируем своё
        if($source_name) $avatar_name = $file['name'];
        // формируем имя и расширение
        else $avatar_name = uniqid().'.'.substr($type,strlen('image/'));
        $upload_file = $dir.$avatar_name;
        
        // получаем название корневой директории
        if(!$root) $upload_file = $_SERVER['DOCUMENT_ROOT'].$upload_file;
        
        // перемещаем в эту окончательную директорию
        if(!move_uploaded_file($file['tmp_name'], $upload_file)){
            echo '<div class="err">Неизвестная ошибка загрузки файла!</div>';
            return false;
        }
        return $avatar_name;
        //return true;
        
    }
    
    public function deleteFile($file, $root = false){
        
        if($file != ''){
            // если не корневая директория, то получаем её
            if(!$root) $file = $_SERVER['DOCUMENT_ROOT'].Config::DIR_IMG.$file;

            if(file_exists($file)){
                unlink($file);
                return true;
            }
        }
        return false;
        
    }
    
    // проверка наличия файла на сервере
//    public function isExists($file, $root = false){
//        // если не корневая директория, то получаем её
//        if(!root) $file = $_SERVER['DOCUMENT_ROOT'].$file;
//        return file_exists($file);
//    }
    
    
    
    
    
    
}