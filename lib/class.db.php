<?php
class Db{
	
	static private $db_instance = null;
	private $mysqli = null;
	
	public function conectarse($db_host, $db_user, $db_pass, $db_name){
		$this->mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
		if (mysqli_connect_errno()) {
			printf("Error conectandose a la base de datos ", mysqli_connect_error());
			exit();
		}
		$this->mysqli->set_charset('utf8');
	}
	
	private function __construct(){}
	
	public function __clone() {
		trigger_error('Cloning <em>mysqli_database</em> is forbidden.', E_USER_ERROR);
	}
	public function __destruct() {
		$this->mysqli->close();
	}
	static public function getInstance(){
		if (!self::$db_instance) {
			self::$db_instance = new Db();
		}
		return self::$db_instance;
	}
	public function real_escape_string ($input){
		return $this->mysqli->real_escape_string($input);
	}
	public function prepare ($sql){
		return $this->mysqli->prepare($sql);
	}
	public function query ($sql){
		if (!$result = $this->mysqli->query($sql)) {
			return false;
		} else {
			return $result;
		}
	}
	public function fetchArray ($stmt) {
		$data = mysqli_stmt_result_metadata($stmt);
        $fields = array();
        $out = array();

        $fields[0] = &$stmt;
        $count = 1;

        while($field = mysqli_fetch_field($data)) {
            $fields[$count] = &$out[$field->name];
            $count++;
        }
       
        call_user_func_array(mysqli_stmt_bind_result, $fields);
        mysqli_stmt_fetch($stmt);
        return (count($out) == 0) ? false : $out;
	
	}
	public function num_rows($result){
		return $this->mysqli->num_rows($result);
	}
	public function fetch_array($result){
		return $this->mysqli->fetch_array($result);
		//return row
	}
	public function insert_id(){
		return $this->mysqli->insert_id;
	}
	
}
?>