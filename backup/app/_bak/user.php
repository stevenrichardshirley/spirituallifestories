<?php
/**
 * Author: Ben Jordan <ben@bigbadcollab.com>
 * user class.
 * 
 */
class user
{
	var $user_id;
	var $user_type_id;
	var $username;
	var $first_name;
	var $last_name;
	var $email;
	var $password;
	var $state;
	var $date;

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param string $user_id. (default: '')
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @param string $ip. (default: '')
	 * @param string $date. (default: '')
	 * @param string $actkey. (default: '')
	 * @param string $first_name. (default: '')
	 * @param string $last_name. (default: '')
	 * @return void
	 */
	function __construct($user_id='',$user_type_id='2',$username, $first_name, $last_name, $email, $password, $state, $date='') {
		$this->user_id = $user_id;
		$this->user_type_id = $user_type_id;
		$this->username = $username;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->password = $password;
		$this->state = $state;
		$this->date = $date;
	}

	/**
	 * setup_db function.
	 * 
	 * @access private
	 * @return void
	 */
	private function setup_db() {
		$sql = "CREATE TABLE `users` (
		  `user_id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_type_id` int(11) DEFAULT NULL,
		  `username` varchar(255) DEFAULT NULL,
		  `first_name` varchar(255) DEFAULT NULL,
		  `last_name` varchar(255) DEFAULT NULL,
		  `email` varchar(255) DEFAULT NULL,
		  `password` varchar(255) DEFAULT NULL,
		  `state` varchar(128) DEFAULT NULL,
		  `date` varchar(50) DEFAULT NULL,
		  PRIMARY KEY (`user_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	}

	/**
	 * check_exists function.
	 * 
	 * @access private
	 * @param mixed $username
	 * @param mixed $email
	 * @return true/false
	 */
	private static function check_exists($username, $email) {
		$db = new db();
		$q = "SELECT user_id FROM users WHERE username = '$username' OR email = '$email'";
		$check = $db->fetch( $db->query($q) );
		if( $check['user_id'] != '' ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * get_by_id function.
	 * 
	 * @access private
	 * @static
	 * @param mixed $user_id
	 * @return user object
	 */
	public static function get_by_id($user_id) {
		$db = new db();
		$q = "SELECT user_id, user_type_id, username, first_name, last_name, email, password, state FROM users WHERE user_id = '$user_id'";
		$u = $db->fetch( $db->query($q) );
		$user = new user(
			$u['user_id'],
			$u['user_type_id'],
			$u['username'],
			$u['first_name'],
			$u['last_name'],
			$u['email'],
			$u['password'],
			$u['state']
		);
		return $user;
	}

	/**
	 * get_by_username function.
	 * 
	 * @access private
	 * @static
	 * @param mixed $username
	 * @return user object
	 */
	private static function get_by_username($username) {
		$db = new db();
		$q = "SELECT * FROM users WHERE username = '$username'";
		$u = $db->fetch( $db->query($q) );
		$user = new user(
			$user_id,
			$user_type_id,
			$username,
			$first_name,
			$last_name,
			$email,
			$password,
			$state
		);
		return $user;
	}
	
	public static function get_user($username) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM users WHERE username = '$username'";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$user = new stdClass();
				$user->user_id = $row['user_id'];
				$user->user_type_id = $row['user_type_id'];
				$user->username = $row['username'];
				$user->first_name = $row['first_name'];
				$user->last_name = $row['last_name'];
				$user->email = $row['email'];
				$user->password = $row['password'];
				$user->state = $row['state'];
				$results[] = $user;
			}
			
		return $results;
	}

	/**
	 * insert function.
	 * 
	 * @access private
	 * @static
	 * @param mixed $user
	 * @return true/false
	 */
	private static function insert($user) {
		$db = new db();
		$q = "INSERT INTO users(user_type_id, username, first_name, last_name, email, password, state, date) 
		VALUES ('2', '$user->username', '$user->first_name', '$user->last_name', '$user->email', '$user->password', '$user->state', '$user->date')";
			if( $db->query( $q ) ) {
				return true;
			} else {
				return false;
			}
	}
	
	/**
	 * update function.
	 * 
	 * @access public
	 * @param mixed $user
	 * @return void
	 */
	public static function update($vars) {
		$db = new db();
		$update = false;
		$message = '';
		$result = '';
		$u ='';
		if( $vars['password'] != '' && $vars['password_confirmation'] != '' ) {
			if( $vars['password'] == $vars['password_confirmation'] ) {
				$password = utils::process( $vars['password'] );
				$password = utils::crypt( $password );
				$update = true;
			} else {
				$update = false;
				$message = 'Your passwords did not match.';
			}
		} else {
			$update = true;
		}
		
		if( $update == true ) {	
			$u .= "UPDATE users SET 
					username = '{$vars['username']}', 
					email = '{$vars['email']}',";
				if( isset($password) ) {
					$u .= "password = '{$password}',";
				}
				$u .= " first_name = '{$vars['first_name']}',
					last_name = '{$vars['last_name']}'
				 	WHERE user_id = {$vars['user_id']}
					";
			$result = $db->query( $u );
			if( $result == true ) {
				$message = 'Your account was updated';
			} else {
				if( $result == false ) {
					$message = 'There was a problem updating your account, please try again.';
				}
			}
		}
		return $message;
	}
	
	/**
	 * login function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $vars
	 * @return array
	 */
	public static function login($vars) {
		$db = new db();
		$username = $vars['username'];
		$password = $vars['password'];
		$username = utils::process( $username );
		$password = utils::process( $password );
		$password = utils::crypt( $password );
		
		$return = array();
		
		$q = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
		$u = $db->fetch( $db->query($q) );
		if( $u['user_id'] != '' ) {
			$_SESSION['user_id'] = $u['user_id'];
			$return['status'] = true;
		} else {
			$message = 'There was an error with your login, please try again.';
		}
		if( isset($message) ) {
			$return['status'] = false;
			$return['message'] = $message;
		}
		return $return;
	}

	/**
	 * logout function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function logout() {
		unset( $_SESSION['user_id'] );
	}

	/**
	 * identify function.
	 * 
	 * @access public
	 * @return user object/false
	 */
	public static function identify() {
		if( isset($_SESSION['user_id'] ) ) {
			$u_id = utils::process( $_SESSION['user_id'] );
			$user = self::get_by_id( $u_id );
			return $user;
		} else {
			return false;
		}
	}
	
	/**
	 * create function.
	 * 
	 * @access public
	 * @static
	 * @param mixed $vars
	 * @return array
	 */
	public static function create($vars) {
		$return = array();	
		$user_type_id = $vars['user_type_id'];
		$username = $vars['username'];
		$first_name = $vars['first_name'];
		$last_name = $vars['last_name'];
		$email = $vars['email'];
		$password = $vars['password'];
		$password_confirm = $vars['password_confirm'];
		$state = $vars['state'];
		//$ip = $vars['ip'];
		$date = date('Y-m-d');
		//$pass = $vars['password'];
		
		$username = utils::process( $username );
		$email = utils::process( $email );
		$password = utils::process( $password );
		$password_confirm = utils::process( $password_confirm ); 
		
		if( (empty($username)) || (empty($email)) || (empty($password)) || (empty($password_confirm)) ) {
			$return['status'] = false;
			$return['message'] = 'All form fields must be filled out.';
		} elseif( !strstr($email, '@') || !strstr($email, '.') ) {
				$return['status'] = false;
				$return['message'] = 'You must enter a valid email address.';
		} else {
		$check = self::check_exists($username, $email);
		if( $check == true ) {
			$return['status'] = false;
			$return['message'] = 'Looks like that username or email is already taken, please choose another.';
		} elseif( $password == $password_confirm ) {
				$password = utils::crypt( $password );
				$actkey = utils::activation_hash('f78dj899dd');
				$user = new user(
					$user_id,
					$user_type_id,
					$username,
					$first_name,
					$last_name,
					$email,
					$password,
					$state,
					$date
				);
				
				$username = $user->username;
				$new_user = self::insert($user);
				
				$return['username'] = $username;
				$return['status'] = true;
				$return['message'] = 'Your account has been created! Please check your email for our welcome email. Remember to check your junk mail or spam folder.';
		} else {
			if( $password != $password_confirm ) {
				$return['status'] = false;
				$return['message'] = 'Your passwords don\'t match.';
			}
		}
		}
		return $return;
	}
}
?>