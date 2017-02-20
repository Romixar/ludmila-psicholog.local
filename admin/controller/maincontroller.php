<?php


class MainController extends Controller{// контроллер страницы ГЛАВНАЯ


	
	public function __construct(){
		$vid = new Videos(); // инициализирую подмодель таблицы
		$testm = new Testmonials();
		parent::__construct($this -> arr = [$vid,$testm]);// отправляю в родит-й конструктор эти объекты
        

        
	}





}



?>