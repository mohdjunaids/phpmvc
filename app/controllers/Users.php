<?php 

class Users extends Controller{
	private $userModel;

	public function __construct(){
		
		$this->userModel = $this->model('User');
	}

	public function register(){

		if ($_SERVER['REQUEST_METHOD']== 'POST') {			
			//Check For Post
			//Process The Forms	

			//Sanitize Post Data
			$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);	
			$data = [
				'name' => trim($_POST['name']),
				'email'=> trim($_POST['email']),
				'password'=> trim($_POST['password']),
				'confirm_password'=> trim($_POST['confirm_password']),
				'name_err'=> '',
				'email_err'=> '',
				'password_err'=> '',
				'confirm_password_err'=> ''
			];
			//Validate Name
			if (empty($data['name'])) {
				$data['name_err'] = 'Please Enter Name';			
			}
        // Validate email
			if(empty($data['email'])){
				$data['email_err'] = 'Please enter an email';
			} else{
          // Check Email
				if($this->userModel->findUserByEmail($data['email'])){
					$data['email_err'] = 'Email is already taken.';
				}
			}

			//Validate Password Length
			if (empty($data['password'])) {
				$data['password_err'] = 'Please Enter password';			
			}elseif (strlen($data['password']) <6 ) {
				$data['password_err'] = 'Password Must Be 6 Characters';	
			}
			//Valiadate Confirm Password
			if (empty($data['confirm_password'])) {
				$data['confirm_password_err'] = 'Please Confirm Password';			
			}else{
				if ($data['password'] != $data['confirm_password'] ) {
					$data['confirm_password_err'] = 'Password did not match';
				}

			}
			//Make sure Errors are empty
			if (empty($data['name_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
				//Validate	
				// die('success');	

				//Hash Password
				$data['password'] = password_hash($data['password'],PASSWORD_DEFAULT);

				//Register User Model
				if ($this->userModel->register($data)) {
					flash('register_success', 'You Are register login');
					redirect('users/login');

				} else {
					die('something wrong');					
				}

			} else {
				$this->view('users/register',$data);
				
			}

		} else {
			//Init Data
			$data = [
				'name' => '',
				'email'=> '',
				'password'=> '',
				'confirm_password'=> '',
				'name_err'=> '',
				'email_err'=> '',
				'password_err'=> '',
				'confirm_password_err'=> ''

			];
			//Load View
			$this->view('users/register',$data);

			
		}

	}
	public function login(){
		if ($_SERVER['REQUEST_METHOD']== 'POST') {
			//Check For Post
			//Sanitize Post Data
			$_POST = filter_input_array(INPUT_POST,FILTER_SANITIZE_STRING);	
			$data = [
				'email'=> trim($_POST['email']),
				'password'=> trim($_POST['password']),
				'email_err'=> '',
				'password_err'=> ''
			];
			    //Validate Email
			if (empty($data['email'])) {
				$data['email_err'] = 'Please Enter Email';				
			}
			//Validate Password
			if (empty($data['password'])) {
				$data['password_err'] = 'Please Enter password';			
			}elseif (strlen($data['password']) <6 ) {
				$data['password_err'] = 'Password Must Be 6 Characters';	
			}

            //Check For User/Email
			if ($this->userModel->findUserByEmail($data['email'])) {
                //User Found              
			}else{
				$data['email_err'] = 'No User Found';
			}

				//Make sure Errors are empty
			if (empty($data['email_err']) && empty($data['password_err'])) {
				//Validate	
                //Checked In Login Users
				$loggedInUser = $this->userModel->login($data['email'],$data['password']);

				if ($loggedInUser) {
                    //Create session
					$this->createUserSession($loggedInUser);

                    // die('succes');

				}else {
					$data['password_err'] = 'password Incorrect';
					$this->view('users/login',$data);
				}

			} else {
				//Load View with error
				$this->view('users/login',$data);				
			}			
		} else {
			//Init Data
			$data = [
				'email'=> '',
				'password'=> '',
				'email_err'=> '',
				'password_err'=> ''

			];
			//Load View
			$this->view('users/login',$data);			
		}		

	}

	public function createUserSession($user){
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_email'] = $user->email;
		$_SESSION['user_name'] = $user->name;
		redirect('posts');
	}

     // Logout & Destroy Session
	public function logout(){
		unset($_SESSION['user_id']);
		unset($_SESSION['user_email']);
		unset($_SESSION['user_name']);
		session_destroy();
		redirect('users/login');
	}

    // Check Logged In
	public function isLoggedIn(){
		if(isset($_SESSION['user_id'])){
			return true;
		} else {
			return false;
		}
	}

}








?>