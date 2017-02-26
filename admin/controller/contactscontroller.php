<?php


class ContactsController extends Controller{ // контроллер страницы ГЛАВНАЯ

	
	public function __construct(){
		$wor = new Works(); // инициализирую подмодель таблицы
		$cont = new Contacts();
		parent::__construct($this -> arr = [$wor,$cont]);// отправляю в родит-й конструктор эти объекты
        
        $this->title = 'Управление страницей КОНТАКТЫ';
	}



    


}



?>