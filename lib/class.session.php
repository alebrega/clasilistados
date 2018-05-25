<?php
class Session{
	
	private $ps=null;
	
	public function __construct(){
		// Include the class source.
	  require_once ($_SERVER["DOCUMENT_ROOT"]."/classes/private_sessions.class.php");
	  //ini_set("session.cookie_lifetime","36000"); 
	  global $db_host_delete,$db_user_delete,$db_pass_delete,$db_name_delete,$t_sesiones;
	  // Create an object.
	  $this->ps = new private_sessions();
	
	  // Store session data in MySQL database.
	  $this->ps->save_to_db = true;
			
	  // MySQL access parameters.
	  $this->ps->db_host = $db_host_delete;
	  $this->ps->db_uname = $db_user_delete;
	  $this->ps->db_passwd = $db_pass_delete;
	  $this->ps->db_name = $db_name_delete;
	
	  // The name of the table used to save session data.
	  $this->ps->save_table = $t_sesiones;
	  
	  // Set up session handlers.
	  $this->ps->set_handler();
	  $this->setSessionCookieDuration(21600);
	  // That's all! Proceed to use sessions normally.
	  if (session_id() == "") session_start();
	}	
	public function set($key,$value) {
		$_SESSION[$key] = $value;
	}
	public function get($key){
		return $_SESSION[$key];
	}
	public function remove($key){
		unset($_SESSION[$key]);
	}
	//6 horas = 21600 segundos
	public function setSessionCookieDuration($seconds){
		session_set_cookie_params($seconds, "/");
	}
}
?>