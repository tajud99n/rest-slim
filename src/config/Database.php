<?php 

    class Database
	{
		private $_dbName = "slimapp";
		private $_dbUser = "root";
		private $_dbPass = "";
		private $_pdo,
				$_query, 
                $_error = false, 
                $_results, 
                $_count = 0;

		public function __construct()
		{
			try{

    			$this->_pdo = new PDO('mysql:host=localhost;dbname='.$this->_dbName, $this->_dbUser, $this->_dbPass);

    			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

		    } catch(PDOException $err) {

		        echo $err->getMessage();
    		}
			
		}

		public function query($sql, array $params = null)
        {
            $this->_error = false;

            if ($this->_query = $this->_pdo->prepare($sql)) {
                $x = 1;

                if ($params) {
                    foreach ($params as $param) {
                        $this->_query->bindValue($x, $param);
                        $x++;
                    }
                }

                if ($this->_query->execute()) {
                    $this->_results = $this->_query->fetchALL(PDO::FETCH_OBJ);
                    $this->_count = $this->_query->rowCount();
                } else {
                    $this->_error = true;
                }
            }
            return $this;            
        }

		public function insert($table, array $fields)
		{
			if (count($fields)) {
				$keys = array_keys($fields);
				$values = null;
				$x = 1;

				foreach ($fields as $field) {
					$values .= '?';
					if ($x < count($fields)) {
						$values .= ', ';
					}
					$x++;
				}
				$sql = "INSERT INTO $table (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

				if (!$this->query($sql, $fields)->error()) {
					return true;
				}
			}

			return false;
		}

		public function update($table, $id, $fields)
		{
			$set = '';
			$x = 1;

			foreach ($fields as $name => $value) {
				$set .= "{$name} = ?";
				if ($x < count($fields)) {
					$set .= ', ';
				}

				$x++;
			}

			$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

			if (!$this->query($sql, $fields)->error()) {
				return true;
			}

			return false;
		}

		public function action($action, $table, array $where)
		{
			if (count($where) === 3) {
				$operators = array('=', '>', '<', '>=', '<=');

				$field = $where[0];
				$operator = $where[1];
				$value = $where[2];

				if (in_array($operator, $operators)) {
					$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

					if (!$this->query($sql, array($value))->error()) {
						return $this;
					}
				}
			}
			return false;
		}
	
		public function delete($table, $where)
		{
			return $this->action('DELETE', $table, $where);
		}

		public function error()
		{
			return $this->_error;
		}

		public function results()
		{
			return $this->_results;
		}

		public function count()
		{
			return $this->_count;
		}

		
	}