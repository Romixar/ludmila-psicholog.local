<?php


class ContactsController extends Controller{

	
	public function __construct(){
		$wor = new Works(); // инициализирую подмодель таблицы цен и услуг
		$cont = new Contacts();
		parent::__construct($this -> arr = [$wor,$cont]);// отправляю в родит-й конструктор эти объекты
	}





}



?>