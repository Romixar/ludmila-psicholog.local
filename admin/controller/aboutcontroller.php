<?php


class AboutController extends Controller{ // контроллер страницы ОБО МНЕ
	

	
	public function __construct(){
		$wor = new Diploms(); // инициализирую подмодель таблицы цен и услуг
		//$cont = new Contacts();
		parent::__construct($this -> arr = [$wor]);// отправляю в родит-й конструктор эти объекты
	}


    



}



?>