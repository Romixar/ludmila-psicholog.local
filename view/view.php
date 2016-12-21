<?php

class View{
    
    public $data = [];
	
	private function render($tmpl){// подготовить но не выводить view
		
		if(count($this -> data) == 1){// значит одномерный массив
			
			foreach($this -> data as $key => $val){
				$$key = $val;
			}
			
		}
		
		
		ob_start();
		include $tmpl.'.php';
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