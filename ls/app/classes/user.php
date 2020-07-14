<?php
/**
 * Author: Ben Jordan <hello@benjo.co>
 * user class.
 * 
 */
class user
{
	var $user_id;
	var $first_name;
	var $last_name;
	var $email;
	var $password;
	var $date;
    var $free_limit;
    var $activation_limit;
    
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
	function __construct($user_id='', $first_name, $last_name, $email, $password, $date='', $freelimit='', $actlimit='') {
		$this->user_id = $user_id;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->email = $email;
		$this->password = $password;
		$this->date = $date;
        $this->free_limit = $freelimit;
        $this->activation_limit = $actlimit;
	}
    
    function is_activated_user()
    {
        if (strlen($this->activation_limit) == 10)
        {
            if ($this->activation_limit > date('Y-m-d'))
                return true;
            else
                return false;
        }
        else
            return false;
    }
    
    function activated_free_days()
    {
        if (strlen($this->activation_limit) == 0 && strlen($this->free_limit) == 10)
        {
            if ($this->free_limit > date('Y-m-d'))
            {
                list($y,$m,$d) = explode('-', $this->free_limit);
                return round((mktime(12,0,0,$m,$d,$y) - mktime(12,0,0))/86400,0);
            }
            else
                return 0;
        }
        else
            return 0;
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
		  `first_name` varchar(255) DEFAULT NULL,
		  `last_name` varchar(255) DEFAULT NULL,
		  `email` varchar(255) DEFAULT NULL,
		  `password` varchar(255) DEFAULT NULL,
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
	public static function check_exists($email) {
		$db = new db();
		$q = "SELECT user_id FROM users WHERE email = '$email'";
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
		$q = "SELECT user_id, first_name, last_name, email, password, free_limit, activation_limit FROM users WHERE user_id = '$user_id'";
		$u = $db->fetch( $db->query($q) );
		$user = new user(
					$u['user_id'],
					$u['first_name'],
					$u['last_name'],
					$u['email'],
					$u['password'], '', $u['free_limit'], $u['activation_limit']
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
	private static function get_by_email($email) {
		$db = new db();
		$q = "SELECT user_id, first_name, last_name, email, password, free_limit, activation_limit FROM users WHERE email = '$email'";
		$u = $db->fetch( $db->query($q) );
		$user = new user(
                    $u['user_id'],
                    $u['first_name'],
                    $u['last_name'],
                    $u['email'],
                    $u['password'], '', $u['free_limit'], $u['activation_limit']
				);
		return $user;
	}
	
	public static function get_user($email) {
		$db = new db();
		$results = array();
		$sql = "SELECT user_id, first_name, last_name, email, password, free_limit, activation_limit FROM users WHERE email = '$email'";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$user = new stdClass();
				$user->user_id = $row['user_id'];
				$user->first_name = $row['first_name'];
				$user->last_name = $row['last_name'];
				$user->email = $row['email'];
				$user->password = $row['password'];
                $user->free_limit = $row['free_limit'];
                $user->activation_limit = $row['activation_limit'];
				$results[] = $user;
			}
			
		return $results;
	}
	
	public static function security_questions() {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM security_questions";
		$books = $db->query( $sql );
			while( $row = $db->fetch($books) ) {
				$user = new stdClass();
				$user->id = $row['id'];
				$user->question = $row['question'];
				$results[] = $user;
			}
			
		return $results;
	}
	
	public static function security_question_by_id($id) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM security_questions WHERE id = $id";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->question = $row['question'];
				$results[] = $question;
			}
			
		return $results;
	}
	
	public static function insert_token($email) {
		$db = new db();
		$return = array();
		$time = date('Ymdhisu');
		$token = $token = md5($email.$time);
		$timestamp = date('Y-m-d H:i:s');
		$q = "INSERT into user_password_reset values('','$email', '$token', '$timestamp')";
		if( $db->query( $q ) ) {
			return $token;
		} else {
			return false;
		}
	}
	
	public static function get_token($token) {
		$db = new db();
		$results = array();
		$sql = "SELECT * FROM user_password_reset WHERE token = '$token'";
		$questions = $db->query( $sql );
			while( $row = $db->fetch($questions) ) {
				$question = new stdClass();
				$question->id = $row['id'];
				$question->email = $row['email'];
				$question->token = $row['token'];
				$results[] = $question;
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
        $freelimit = date('Y-m-d',mktime()+30*86400); // 30 days free !
        
		$q = "INSERT INTO users(first_name, last_name, email, password, date, free_limit) 
		VALUES ('$user->first_name', '$user->last_name', '$user->email', '$user->password', '$user->date', '$freelimit')";
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
	public static function update($vars, &$result) {
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
	 * update function.
	 * 
	 * @access public
	 * @param mixed $user
	 * @return void
	 */
	public static function update_password($vars) {
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
			$u .= "UPDATE users SET ";
				if( isset($password) ) {
					$u .= "password = '{$password}' ";
				}
				$u .= "WHERE email = '{$vars['email']}'";

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
		$email = $vars['email'];
		$password = $vars['password'];
		$email = utils::process( $email );
		$password = utils::process( $password );
		$password = utils::crypt( $password );
		
		$return = array();
		
		$q = "SELECT user_id, first_name, last_name, email, password FROM users WHERE email = '$email' AND password = '$password'";
		$u = $db->fetch( $db->query($q) );
		if( $u['email'] != '' ) {
			$_SESSION['user_id'] = $u['user_id'];
			$return['status'] = true;
            // logging
            $now = date('Y-m-d H:i:s');
            $db->query("UPDATE users SET last_login='$now' WHERE user_id=".$u['user_id']);
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
	public static function identify($secure=false) {
		if( isset($_SESSION['user_id'] ) ) {
			$u_id = utils::process( $_SESSION['user_id'] );
			$user = self::get_by_id( $u_id );
			return $user;
		} else {
            if ($secure)
            {
                header('location: '.ROOT_DIRECTORY);
                exit;
            }
            else
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
		$first_name = $vars['first_name'];
		$last_name = $vars['last_name'];
		$email = $vars['email'];
		$password = $vars['password'];
		$password_confirm = $vars['password_confirm'];
		//$ip = $vars['ip'];
		$date = date('Y-m-d');
		//$pass = $vars['password'];
		
		$email = utils::process( $email );
		$password = utils::process( $password );
		$password_confirm = utils::process( $password_confirm ); 
		
		if( (empty($email)) || (empty($password)) || (empty($password_confirm)) ) {
			$return['status'] = false;
			$return['message'] = 'All form fields must be filled out.';
		} elseif( !strstr($email, '@') || !strstr($email, '.') ) {
				$return['status'] = false;
				$return['message'] = 'You must enter a valid email address.';
		} else {
		$check = self::check_exists($email);
		if( $check == true ) {
			$return['status'] = false;
			$return['message'] = 'Looks like that email is already taken, please choose another.';
		} elseif( $password == $password_confirm ) {
				$password = utils::crypt( $password );
				$actkey = utils::activation_hash('f78dj899dd');
				$user = new user(
					$user_id,
					$first_name,
					$last_name,
					$email,
					$password,
					$date
				);
				
				$email = $user->email;
				$new_user = self::insert($user);
                
                // keeping the userbookPermission updated with this new registered email
                if (intval($vars['perm_id']) > 0)
                {
                    $db = new db();
                    $questions = $db->query( $sql );
                    $db->query("UPDATE user_book_permissions SET email='$email' WHERE id=".intval($vars['perm_id']));
                }
                
				
				$return['email'] = $email;
				$return['status'] = true;
				$return['message'] = 'Your account has been created! Please check your email for our confirmation email. Remember to check your junk mail or spam folder.';
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
