<?php


class ContactsController extends Controller{ // контроллер страницы ГЛАВНАЯ

    public static $title = 'Управление страницей КОНТАКТЫ';
	
	public function __construct(){
		$wor = new Works(); // инициализирую подмодель таблицы
		$cont = new Contacts();
		parent::__construct($this -> arr = [$wor,$cont]);// отправляю в родит-й конструктор эти объекты
        
        
	}



    


}



?>