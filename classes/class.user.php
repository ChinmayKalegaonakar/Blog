<?php

include('class.password.php');

class User extends Password{

    private $db;
	
	function __construct($db){
		parent::__construct();
	
		$this->_db = $db;
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}		
	}
	public function currentuser(){
		if(isset($_SESSION['username'])){
			return $_SESSION['username'];
		}
		else{
			return 'Guest';
		}
	}
	public function getuserid($username){
		try {
			$stmt = $this->_db->prepare('SELECT  FROM tb_member WHERE name = :username');
			$stmt->execute(array('username' => $username));
			$row = $stmt->fetch();
			return $row['id'];
			} catch(PDOException $e) {
				echo '<p class="error">'.$e->getMessage().'</p>';
			}
	}
	private function get_user_hash($username){	

		try {

			$stmt = $this->_db->prepare('SELECT password FROM tb_member WHERE email = :username');
			$stmt->execute(array('username' => $username));
			$row = $stmt->fetch();
			return $row['password'];

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	
	public function login($username,$password){	

		$hashed = $this->get_user_hash($username);
		
		if($password===$hashed){
		    session_start();
			$_SESSION['loggedin'] = true;
			$_SESSION['username'] = $username;
		    return true;
		}		
	}
	//  public function name(){
	//  	$stmt = $this->_db->prepare('SELECT member_name FROM members WHERE member_name = :username');
	//  	$stmt->execute(array('username' => $username));
		
	//  	$row = $stmt->fetch();
	//  	return $row['member_pass'];
	//  }
	
	public function logout(){
		session_destroy();
	}
	
}


?>