<?php

class AppController{
    
    public $view; // объект видов
    
    
    public function __construct(){
        $this->view = new AppViewsController();
    }
    
    public function actionIndex(){
        
        $videos = $this -> getTmplVideos();
        $testmon = $this -> getTmplTestmon();
        
        $homepage = $this -> view -> prerender('homepage',compact('videos','testmon'));
        
        $diploms = $this -> getTmplDiploms();
        
        $gallery = $this -> view -> prerender('gallery',compact('diploms'));
        
        $services = $this -> getTmplServices();
        $prices = $this -> getTmplPrices();
        
        $about = $this -> view -> prerender('about',compact('services','prices'));
        
        $works = $this -> getTmplWorks();
        
        $contact = $this -> view -> prerender('contact',compact('works'));
        
        
        
        $this -> view -> render('main',compact('homepage','gallery','about','contact'));
    }
    
    
    public function getTmplVideos(){
        
        $model = new Videos();

        $data = $model -> selectAll();
        
        return $this -> view -> prerender('videos',compact('data'));

    }
    
    public function getTmplTestmon(){
        
        $model = new Testmonials();

        $data = $model -> selectAll();
        
        for($i=0; $i<count($data); $i++) $data[$i]->dateadd = date('d.m.Yг.',$data[$i]->dateadd);
        
        return $this -> view -> prerender('testmon',compact('data'));
        
        
    }
    
    public function getTmplDiploms(){
        
        $model = new Diploms();

        $data = $model -> selectAll();
        
        $tmp = [];
        for($i=0; $i<count($data); $i++) if(!$data[$i]->view) $tmp[] = $i;
        
        for($j=0; $j<count($tmp); $j++) unset($data[$tmp[$j]]);
        sort($data); // расстановка ключей с нуля
        
        return $this -> view -> prerender('diploms',compact('data'));
        
        
        
        
    }
    
    public function getTmplServices(){
        
        $model = new Services();

        $data = $model -> selectAll();
        
        return $this -> view -> prerender('services',compact('data'));

    }
    
    public function getTmplPrices(){
        
        $model = new Prices();

        $data = $model -> selectAll();
        
        return $this -> view -> prerender('prices',compact('data'));
        
        
    }
    
    public function getTmplWorks(){
        
        $model = new Works();

        $data = $model -> selectAll();
        
        return $this -> view -> prerender('works',compact('data'));
        
        
        
    }
    
    
    
    
    
    
}


?>