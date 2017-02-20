<?php

class LoginController extends Controller{
    
    public $user;
    
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
    
}





?>