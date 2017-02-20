<?php

class LoginController extends Controller{
    
    
    
    public function __construct(){
		$user = new User(); // инициализирую подмодель таблицы
		
		parent::__construct($this -> arr = [$user]);// отправляю в родит-й конструктор эти объекты
        

        
	}
    
    
}





?>