<?php

class LoginController extends Controller{
    
    public $user;
    
    public static $minlen = 3; // кол-во символов на логин?пароль
    public static $maxlen = 15;
    
    public function __construct(){

		$this->user = new User(); // инициализирую подмодель таблицы
		
		parent::__construct();// подключаю чтобы поймать POST
        

        
	}
    
    public function checkLogin(){
        if(!empty($this->user->auth)) return true;
        else return false;
    }
    
    public function authorized(){
        $view = new View();
        $view -> display('login');
        die;
    }
    
    public static function loginValidate($data){
        
        foreach($data as $k => $v){
            
            if(!self::checkLen($v, self::$minlen, self::$maxlen)) return false;
            else echo 'верно'; //return true;
            
        }
        
        
    }
    
    private static function checkLen($v,$min,$max){
        
        if(strlen($v) > $min && strlen($v) < $max){return true;}else{return false;}
        
    }
    
}





?>