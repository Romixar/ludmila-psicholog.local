<?php

class LoginController extends Controller{
    
    public $user;
    
    public $minlen = 3; // кол-во символов на логин?пароль
    public $maxlen = 15;
    
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
    
    public function loginValidate($data){
        
        foreach($data as $k => $v){
            
            if(!$this->checkLen($v, $this->minlen, $this->maxlen)) return false;
            else continue;
            //else echo 'верно'; //return true;
            
        }
        return true;
//        $this -> user -> login = $data['login'];
//        $this -> user -> password = $data['password'];
        
        //debug($this -> user);
        
        //$this -> user -> findUser();
        
    }
    
    private function checkLen($v,$min,$max){
        
        if(strlen($v) > $min && strlen($v) < $max){return true;}else{return false;}
        
    }
    
}





?>