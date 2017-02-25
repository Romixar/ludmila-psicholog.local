<?php

class ViewsController{
    
	public $func = '';// здесь будет идентификатор класса, который создает страницу
    //public $data = [];
	public $open = false;// флаг открытия поля для добавления
	public $err = '';
    
    public $vars = []; // переменные для шаблона main

	
	public function render($tmpl,$data=[]){// подготовить но не выводить view
        
        $content = $this->prerender($tmpl,$data);
        
        $this -> display('main',compact('content'));

        
	}
	
	public function display($tmpl, $data=[]){// вывести на экран view
        
        if(!empty($this->vars)) $data = $this -> setVars($data);
		echo $this -> prerender($tmpl, $data);
	}
    
    public function prerender($tmpl,$data=[]){
        
        if(file_exists('view/'.$tmpl.'_tpl.php')){
            
            ob_start();
            extract($data); // названия ключей будут переменными

            include '/../view/'.$tmpl.'_tpl.php';
            
            return ob_get_clean();
            
            
            
            
        }
        return false;
        
    }
    
    private function setVars($data){ // добавляю переменные в шаблон main
        
        foreach($this->vars as $k => $v){
            $data[$k] = $v;
        }
        return $data;
        
    }

    
    
}



?>