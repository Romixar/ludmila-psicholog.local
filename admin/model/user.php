<?php

class User extends DB{
    
    
    protected static $table = 'user';// название таблицы
    public static $checkBox = false;// флаг наличия чекбоксов
	
	public $tmpl = 'login';// шаблон для вывоа данной таблицы
    
    public static $auth = ''; //  будет ID авторизованного пользователя
//    public $login = '';
//    public $password = '';
    
    
//    public function findUser(){
//        
//        debug($this);
//        
//        
//    }
    
    public function __construct(){
        
        parent::__construct();
        
    }
    
    
    public function run(){
        $view = new View();
        
        
        if(isset($_POST['do_login'])){
            
            $sth = $this->dbh->prepare("SELECT `id` FROM `users` WHERE `login` = :login AND `password` = MD5(:password)");

        
            $sth->execute(array(
               ':login' => $_POST['login'],
               ':password' => $_POST['password']
            ));

            $data = $sth->fetchAll();        

            if(count($data) == 1){

               Session::init();// создание сессии
               Session::set('loggedIn', true);// установка значения в сессию

                $view->display('head');
               //header('Location: ../admin');
            }else{
                $view->display('login');
               //header('Location: ../login.php');
            }
            
            
            
            
            
        }
        
        
        
        
    }
    
    
    
    
    
    
}







?>