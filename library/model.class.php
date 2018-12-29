<?php 

	class Model{

		public $db;
		protected $tableName;

		public function __construct(){
			$this->db = new Database();
		}

		public function model($modelName) {

			$sql = "SELECT*FROM " . $this->tableName;

			if(is_array($params)) {
				if(isset($params["limit"])) {

					$sql .= " LIMIT " . $params["limit"];
				}
			}

			$this->db->query($sql);

			return $this->db->execute()->toObject();
		}

		public function rows(){
			return $this->db->getAll($this->tableName)->numRows();
		}

		public function getWhere($params) {

			return $this->db->getWhere($this->tableName, $params)->toObject();
		}

		public function delete($where = array()) {
			return $this->db->delete($this->tableName, $where);
		}

		public function getJoin($tableJoin, $params, $join = "JOIN", $where = ""){

			$sql = "SELECT*FROM " . $this->tableName;

			if(is_array($tableJoin)) {

				foreach ($tableJoin as $table) {
					$sql .= " ". $join ." ". $table . " ";
				}
			}else {
				$sql .= " ". $join ." ". $tableJoin . "";
			}

			foreach ($params as $key => $value) {
				$sql .=" ON " . $key . " = " . $value . " ";
			}

			if($where && is_array($where)) {

				$sql .= " WHERE ";
				$i = 0;

				foreach ($where as $key => $value) {
					
					$sql .= " " . $key . "='" . $value . "' ";

					$i++;
					if($i < count($where)){

						$sql .=" AND ";
					}
				}
			}

			$this->db->query($sql);

			return $this->db->execute()->toObject();
		}

		public function insert($data = array()) {

			$insert = $this->db->insert($this->tableName, $data);

			if($insert) {
				return true;
			}
			return false;
		}
		public function update($data = array(), $where = array()) {

			$update = $this->db->update($this->tableName, $data, $where);

				if($update) {
					return true;
				}

				return false;
		}
	}

?>