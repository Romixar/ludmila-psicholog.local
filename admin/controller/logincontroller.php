<?php

class LoginController extends Controller{
//class LoginController{
    
    public $user;
    
    public $minlen = 3; // кол-во символов на логин?пароль
    public $maxlen = 15;
    public $view;
    
    public function __construct(){
        $this -> user = new User();
        $this -> view = new View();
        
        Session::init();
        $logged = Session::get('loggedIn');
        
        if($logged == false){
            Session::destroy();
            
            $this->view->display('login');
            
        }
	}
    
//    public function __construct() {
//  parent::__construct();
//  Session::init();
//  $logged = Session::get('loggedIn');
//  if($logged == false) {
//   Session::destroy();
//   header('Location: ../login');
//   exit();
//  }
//}
    
    
    
    public function index(){
        $this -> view -> display('login');
        //die;
    }
    
    public function logout() {
      
        Session::destroy();
        header('Location: ../login.php');
        exit();
    }
    
    public function run(){
        
        $this -> user -> run();
        
    }
    
    
    
    
//    public static function checkLogin(){
//        if(!empty(User::$auth)) return true;
//        self::authorized();
//    }
//    
//    public static function authorized(){
//        $view = new View();
//        $view -> display('login');
//        die;
//    }
//    
//    public function loginValidate($data){
//        
//        foreach($data as $k => $v){
//            
//            if(!$this->checkLen($v, $this->minlen, $this->maxlen)) return false;
//            else continue;
//            //else echo 'верно'; //return true;
//            
//        }
//        
//        $this->user->auth = 'kjjhhgfgfd';
//        
//        return true;
////        $this -> user -> login = $data['login'];
////        $this -> user -> password = $data['password'];
//        
//        //debug($this -> user);
//        
//        //$this -> user -> findUser();
//        
//    }
//    
//    private function checkLen($v,$min,$max){
//        
//        if(strlen($v) > $min && strlen($v) < $max){return true;}else{return false;}
//        
//    }
    
}





?>