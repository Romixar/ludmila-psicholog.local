<?php

class LoginController extends Controller{
    
    public $model;
    
    
    
    public function __construct(){
        
        $this -> model = new User();
        
        parent::__construct();
        
        
    }
    
    
    
    
    public function actionRun(){
        
        //echo 'запуск логин ран';
        
        $view = new ViewsController();
        
        if(isset($this->data['do_login'])) $this -> model -> run();
        
        if(isset($_SESSION['loggedIn'])){
            

            $main = new MainController();
            $main -> actionAll();
            
        }else{
            
            $title = 'Страница авторизации'; // переменная для шаблона main
      
            $view -> vars = compact('title');
            $view -> render('login');
            

        }
            

        
    }

    public function actionLogout(){
        
        session_destroy();
        
        $view = new ViewsController();
        
        $title = 'Страница авторизации'; // переменная для шаблона main
      
        $view -> vars = compact('title');
        $view -> render('login');
        
        exit();
    }
    
    
    
    
    
    
    
    
}





?>