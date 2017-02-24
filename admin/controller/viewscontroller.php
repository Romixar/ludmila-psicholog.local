<?php

class ViewsController{
    
	public $func = '';// здесь будет идентификатор класса, который создает страницу
    public $data = [];
	public $open = false;// флаг открытия поля для добавления
	public $err = '';

	
	private function render($tmpl){// подготовить но не выводить view
		
		// если $this -> data - массив объектов
		//if(count($this -> data) == 1){// значит одномерный массив
			
			//foreach($this -> data as $key => $val){
				//$$key = $val;
			//}
			
		//}
		
		ob_start();
		include '/../view/'.$tmpl.'.php';
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
		
	}
	
	public function display($tmpl){// вывести на экран view
		echo $this -> render($tmpl);
	}
	
	public function __set($k, $v){
		$this -> data[$k] = $v;
	}
	
	public function __get($k){
		return $this -> data[$k];
	}
	

    
    
}



?>