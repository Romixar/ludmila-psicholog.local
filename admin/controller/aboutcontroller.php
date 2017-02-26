<?php


class AboutController extends Controller{ // контроллер страницы ОБО МНЕ
	
    public static $title = 'Управление страницей ОБО МНЕ';
	
	public function __construct(){
		$wor = new Diploms(); // инициализирую подмодель таблицы цен и услуг
        
		parent::__construct($this -> arr = [$wor]);// отправляю в родит-й конструктор эти объекты
        
	}


    



}



?>