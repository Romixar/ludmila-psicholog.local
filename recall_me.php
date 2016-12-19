<?php

//require 'db.php';//подключаю redbeanPHP
require_once 'settings.php';

class actionPage extends HTMLPage{
    
    private $db;
    
    public function __construct(){
        $this -> db = new DB();
    }
    
    public function getArrayFromObj($obj){
        return $this -> db -> getArrayFromObj($obj);
    }
    
    public function add($fields, $table){
        $tab = R::getRedBean() -> dispense( $table );// подчеркивание в названии НЕЛЬЗЯ
        $tab -> view = $fields['view'];
        $tab -> name = $fields['name'];
        $tab -> dateadd = $fields['dateadd'];
        $tab -> head = $fields['head'];
        $tab -> body = $fields['body'];
        
        $id = R::store( $tab );
        if($id) return true;
        else return false;
    }
    
    public function checkData($data){
        foreach($data as $key => $val){
            //$data[$key] = $this -> xss($val);
            $data[$key] = $this -> db -> xss($val);
        }
        return $data;
    }
    
    public function checkTestmon($data, $mes){
        return $this -> db -> checkTestmon($data, $mes);
    }

    
    public function sendMail($data){
        // имя отправителя и email-отправителя в < ... >
        $from = "=?utf-8?B?".base64_encode($data['name'])."?="."<".$data['email'].">";
        $headers = 'From: '.$from."\r\nReply-To: ".$from."\r\nContent-type: text/html; charset=\"utf-8\"\r\n";
        $text = "Заполнена форма отправки сообщения на Вашем сайте.\n\n".
                "Данные отправителя:\n\nИмя: ".$data['name']." \n".
                'Телефон: '.$data['phone']." \n\n".
                'Сообщение: '.$data['message'];
        
        $lines = preg_split('/\\r\\n?|\\n/',$text); // делим по строкам в массив по переносу стр
        $subject = 'Сообщение с сайта Людмила Психолог от '.$data['name'];
        $subject = "=?utf-8?B?".base64_encode($subject)."?=";
        $email_body = '';
        $cnt = count($lines);
        for($i = 1; $i < $cnt; $i++){// формируем строки письма + перенос строки в конце
            $email_body .= $lines[$i];
            if($i != ($cnt - 1)) $email_body .= "\n";// если строка не послед, то переносим строку
        }
        $email_body = nl2br($email_body);// замена \n на <br/>
        //rommyb22@rambler.ru    admin@zolushka18.ru    anjelo4ka22@yandex.ru
        $to = 'duby.ludmila@yandex.ru'; // на указанный email - duby.ludmila@yandex.ru
        
        if(mail($to, $subject, $email_body, $headers)) return true;
        else return false;
    }
    
    
    
    
    
    
    
    
}
$config = new config();
$action = new actionPage('Отзыв успешно создан!');

$data = [];

    if(isset($_POST['newmail'])){// отправка письма
        // собираю ассоц массив из объекта stdClass из строки JSON
        $data = $action -> getArrayFromObj(json_decode($_POST['newmail']));
        
        $data = $action -> checkData($data);
        
//        echo '<pre>';
//        print_r($data);
//        echo '</pre>';
//        
//        exit();
        
        
        
        if($data){         
            if($action -> sendMail($data)){
                //header('Location: '.$config::HOST_ADDRESS.'?sendmail=success');
                echo 'Спасибо за обращение! Людмила Пьянкова обязательно свяжется с Вами';
                exit();
            } 
            else echo 'Ошибка отправки письма!';
            exit();
        }
    }

    if(isset($_POST['newtestmon'])){// добавление отзыва
        // собираю ассоц массив из объекта stdClass из строки JSON
        $data = $action -> getArrayFromObj(json_decode($_POST['newtestmon']));

        $data = $action -> checkData($data);// проверка на вредоносный код и др.
        
        if($data){
            $data['view'] = '1';// создаю недостающие поля
            $data['dateadd'] = time();// временная метка создания отзыва
            
            $data = $action -> checkTestmon($data, $data['message']);// разделяю на два поля
            
            if(is_array($data)){
                $res = $action -> add($data,'testmonials');
                if($res){
                    echo json_encode($data);// обратно отправляю пользователю на сайт его отзыв
                }else{
                    $data['error'] = 'Ошибка при добавлении отзыва!';
                    echo json_encode($data);
                }
            }else{
                $data['error'] = 'Слишком большой текст!';
                echo json_encode($data);
            }
        }
    }
    