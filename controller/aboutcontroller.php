<?php


class AboutController extends Controller{
	

	
	public function __construct(){
		$wor = new Diploms(); // инициализирую подмодель таблицы цен и услуг
		//$cont = new Contacts();
		parent::__construct($this -> arr = [$wor]);// отправляю в родит-й конструктор эти объекты
	}





}



?>