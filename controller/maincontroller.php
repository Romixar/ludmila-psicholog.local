<?php


class MainController extends Controller{


	
	public function __construct(){
		$vid = new Videos(); // инициализирую подмодель таблицы цен и услуг
		$testm = new Testmonials();
		parent::__construct($this -> arr = [$vid,$testm]);// отправляю в родит-й конструктор эти объекты
	}





}



?>