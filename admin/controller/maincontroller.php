<?php


class MainController extends Controller{// контроллер страницы ГЛАВНАЯ

    public static $title = 'Управление страницей ГЛАВНАЯ';
	
	public function __construct(){
        
        
		$vid = new Videos(); // инициализирую подмодель таблицы
		$testm = new Testmonials();
        
        // отправляю в родит-й конструктор эти объекты
		parent::__construct($this -> arr = [$vid,$testm]);
        
	}





}



?>