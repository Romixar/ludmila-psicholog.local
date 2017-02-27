<?php

class AppController{
    
    
    public function __construct(){
        
        
        //echo 'класс AppController подключен!';
        $view = new AppViewsController();
        
        $videos = $view->prerender('videos');
        
        $testmon = $view->prerender('testmon');
        
        $view->render('homepage',compact('videos','testmon'));
        
    }
    
    
    
    
    
    
    
    
    
    
    
}


?>