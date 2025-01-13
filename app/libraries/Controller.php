<?php
/*
Base Controller
Loads the Models views 
*/
 

class Controller
{
	// load Model
	public function model($model){

		// Require Models file
		require_once '../app/models/' . $model . '.php';
		//Instatiate model
		return new $model();
		

	}
	//Load Views
	public function view($view, $data=[] ){
		if (file_exists('../app/views/'. $view . '.php')) {
			require_once '../app/views/'. $view . '.php';
			
		}else {
			die("view does not exit");
		}

	}
    
}



