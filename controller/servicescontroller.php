<?php


class ServicesController extends Controller{
	
	
	
	public function __construct(){
		$pr = new Prices(); // инициализирую подмодель таблицы цен и услуг
		$serv = new Services();
		parent::__construct($this -> arr = [$pr,$serv]);// отправляю в родит-й конструктор эти объекты
	}





}



?>