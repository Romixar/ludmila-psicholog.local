<?php


class MainController extends Controller{



	public function actionAll(){
		
		echo 'попал сюды!';die;
		
		$pr = new Prices();
		
		$res = $pr -> selectAll();
		
		var_dump($res);
		die;
		
	}



}



?>