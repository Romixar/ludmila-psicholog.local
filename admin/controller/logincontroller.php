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
//        if(isset($this->data['do_login'])){
//            
//            debug($this->data);
//            echo 'logincontroller';
//            
//        }
        
        //die;
        
        if(isset($_SESSION['loggedIn'])){
            
            
            $view -> display('head');
            //$view -> display('video');
            
            //echo 'start mainController';
            //die('session true');
            //$view -> display('main');
            $main = new MainController();
            $main -> actionAll();
            //$this -> view -> render('main');
            
        }else{
            $view -> display('login');
            //$this-> view -> render('login');
        }
            

        
    }

    public function actionLogout(){
        
        session_destroy();
        
        $view = new ViewsController();
        $view -> display('login');
        //$this -> view -> render('login');
        
        exit();
    }
    
    
    
    
    
    
    
    
}





?>