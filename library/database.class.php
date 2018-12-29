<?php 
class Database{
	private $instance;
	private $sql;

	public function __construct(){

		require_once ROOT . DS . 'library' . DS . 'resultset.class.php';
		$this->instance = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

		if (mysqli_connect_errno()) {

			echo "Failed to connect to MYSQL: " .
					mysqli_connect_error();

		}
	}

	public function query($sql){
		$this->sql = $sql;
	}

	public function getAll ($tableName) {

		$this->sql = "SELECT*FROM " . $tableName;
		return $this->execute();
	}

	public function getWhere($tableName, $where  = array()) {

		$this->sql = "SELECT*FROM" . $tableName;

		if(is_array($where)){

		$this->sql .= " WHERE ";
		$i = 0;
		foreach($where as $key => $value) {
			$i++;
			$this->sql .= $key . "='" . $value . "' ";

			if($i < count($where)) $this->sql .= " AND ";
		}

	}

	return $this->execute();
}

	public function delete($tableName, $where = array()) {

		$this->sql = "DELETE FROM " . $tableName;

		if(is_array($where)) {

			$this->sql .= " WHERE ";
			$i = 0;
			foreach ($where as $key => $value) {
				$i++;
				$this->sql .= $key . "='" . $value . "' ";

				if($i < count($where)) $this->sql .= " AND ";
			}
		}

	return $this->execute();
}

	public function insert($tableName, $params = array()) {

		$this->sql= "INSERT INTO " . $tableName . "(";

		$total = count($params);
		$i = 0;

		foreach ($params as $key => $value) {
			$i++;

			$this->sql = $this->sql . $key;

			if ($i < $total) {
				$this->sql = $this->sql . ',';
			}
		}

		$this->sql = $this->sql . ") VALUES (";

		$i = 0;
		foreach ($params as $key => $value) {
			$i++;

			$this->sql = $this->sql . "'" . $value . "'";

			if($i < $total) {
				$this->sql = $this->sql . ',';
			}
		}
	$this->sql = $this->sql . ')';
	return $this->execute();
}

	public function update($tableName, $data = array(),$where = array()) {
		$this->sql = "UPDATE " . $tableName . " SET ";

		$total = count($data);
		$i = 0;

		foreach ($data as $key => $value) {
			$i++;

			$this->sql = $this->sql . $key . " = '" . $value . "'";

				if ($i < $total) {
					$this->sql = $this->sql . ',';
				}

		}

		if(is_array($where) && count($where) > 0) {

			$this->sql .= " WHERE ";
			$i = 0;
			foreach ($where as $key => $value) {
				$i++;
				$this->sql .= $key . "='" . $value . "' ";

				if($i < count($where)) $this->sql .= " AND ";
			}
		}
		return $this->execute();

}

	public function bindParams($values) {
		if(is_array($values)) {
			foreach ($values as $v) {
				$this->replaceParam($v);
			}
		}
		else{
			$this->replaceParam($values);
		}
}
	public function execute(){
		$query = mysqli_query($this->instance, $this->sql);
		return new ResultSet($query);
	}

	private function replaceParam($v){
		for ($i = 0; $i < strlen($this->sql); $i++){
			if($this->sql[$i] == '?'){
				$this->sql = substr_replace($this->sql,mysql_escape_string($v),$i, 1);
				break;
			}
		}

	}
}
?>