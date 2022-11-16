<?php namespace Src\Modules;

use Exception;
use mysqli;

/*
 *
 * db class
 * for use this class you can create instance and use other functions like following example:
 * $db = \Src\Modules\DB::getInstance();
 * $user = $db->select('users', '*')
 * ->where('email', 'iman@gmfail.com')
 * ->orWhere('email', 'iman@gmail.org')
 * ->count();
 *
 *
 * for use Update or Delete or Insert method you can use commit() on the end of query
 * Update
 * $db->update('users', ['token' => 'test', 'password' => 1233555])
 * ->where('email', 'iman@gmail.com')
 * ->orWhere('email', 'iman2@gmail.com')
 * ->commit();
 *
 * Delete
 * $db->delete('users')
 * ->where('email', 'iman@gmfail.com')
 * ->commit();
 *
 */


class DB
{
	
	private $conn;
	
	private static DB|null $instance = null;
	
	
	private  string $query = '';
	
	
	private string $tableName;
	
	
	private bool $userWhere = false;
	
	// The constructor is private
	// to prevent initiation with outer code.
	private function __construct()
	{
		try {
			$this->conn = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME );
			$this->conn->set_charset( "utf8" );
		} catch ( Exception $e ) {
			die( 'Unable to connect to database' );
		}
	}
	
	
	public static function getInstance() : ?DB
	{
		if (self::$instance == null) {
			self::$instance = new DB();
		}
		return self::$instance;
	}
	
	
	public function getConnection() : mysqli
	{
		return $this->conn;
	}
	
	
	
	public function __destruct()
	{
		$this->conn?->close();
	}
	
	
	/**
	 * @param string $tableName
	 * @return DB
	 */
	public function table( string $tableName) : self
	{
		$this->tableName = $tableName;
		$this->userWhere = false;
		return $this;
	}
	
	
	/**
	 * @param string|array $columns
	 * @return $this
	 */
	public function select(string|array $columns = '*') : self
	{
		if (empty($columns)) $columns = '*';
		
		$column = is_array($columns) ? implode(', ', $columns) : $columns;
		$this->query = 'SELECT ' . $column . ' FROM ' . $this->tableName . ' ';
		
		return $this;
	}
	
	
	/**
	 * @param array $params
	 * @return $this
	 */
	public function update(array $params) : self
	{
		$rows = '';
		foreach($params as $key => $param) {
			$rows .= '' . $key . '="' . $param . '",';
		}
		
		$this->query = 'UPDATE ' . $this->tableName . ' SET ' . substr($rows, 0, -1) . $this->query;
		return $this;
	}
	
	
	/**
	 * delete from Db
	 *
	 * @return $this
	 */
	public function delete() : self
	{
		$this->query = 'DELETE FROM ' . $this->tableName . ' ' .  $this->query;
		return $this;
	}
	
	
	/**
	 * @param array $params
	 * @return $this
	 */
	public function insert(array $params) : self
	{
		$keys = '';
		$values = '';
		
		foreach ($params as $key => $param) {
			$keys .= $key . ',';
			$values .= '"' . $param . '",';
		}
		
		$this->query = 'INSERT INTO ' . $this->tableName .
			' ('. substr($keys, 0, -1) .
			') VALUES (' . substr($values, 0, -1) . ')';
		
		return $this;
	}
	
	/**
	 * where query
	 * @param string $key
	 * @param string $value
	 * @param string $operator
	 * @return $this
	 */
	public function where( string $key, string $value, string $operator = '=') : self
	{
		if (!$this->userWhere) {
			$this->query .= ' WHERE ' . $key . ' ' . $operator . ' "' . $value . '" ';
		} else {
			$this->query .= ' AND ' . $key . ' ' . $operator . ' "' . $value . '" ';
		}
		
		$this->userWhere = true;
		return $this;
	}
	
	
	/**
	 * or where query
	 * @param string $key
	 * @param string $value
	 * @param string $operator
	 * @return $this
	 */
	public function orWhere( string $key, string $value, string $operator = '=') : self
	{
		if (!$this->userWhere) {
			$this->query .= ' WHERE ' . $key . ' ' . $operator . ' "' . $value . '" ';
		} else {
			$this->query .= ' OR ' . $key . ' ' . $operator . ' "' . $value . '" ';
		}
		
		$this->userWhere = true;
		return $this;
	}
	
	
	/**
	 * return one row
	 * @return array|null
	 */
	public function first() : ? array
	{
		$result = mysqli_query($this->conn, $this->query);
		$row = $result->fetch_assoc();
		mysqli_free_result($result);
		$this->query = '';
		return  $row ?? null;
	}
	
	
	/**
	 * return all rows
	 * @return array|null
	 */
	public function get() : ? array
	{
		$result = $this->conn->query($this->query);
		$this->query = '';
		return $result->fetch_all(MYSQLI_ASSOC) ?? null;
	}
	
	
	public function paginate(int $count = 10) : ? array
	{
		if (!isset ($_GET['page']) ) {
			$page = 1;
		} else {
			$page = $_GET['page'];
		}
		
		
		$totalPage = ($page-1) * $count;
		$this->query .= ' LIMIT ' . $totalPage . ',' .$count;
		
		
		$result = $this->conn->query($this->query);
		$this->query = '';
		return $result->fetch_all(MYSQLI_ASSOC) ?? null;
	}
	
	
	/**
	 * @param string $column
	 * @param string $orderType
	 * @return $this
	 */
	public function orderBy( string $column, string $orderType = 'asc') : self
	{
		$this->query .= ' ORDER BY ' . $column . ' ' . $orderType . ' ';
		return $this;
	}
	
	/**
	 * return all rows
	 * @return int
	 */
	public function count() : int
	{
		$result = mysqli_query($this->conn, $this->query);
		return mysqli_num_rows($result);
	}
	
	
	/**
	 * commit insert, update and delete method
	 * @return \mysqli_result|bool
	 */
	public function commit() : \mysqli_result|bool
	{
		return mysqli_query($this->conn, $this->query);
	}
	
	
	/**
	 * @return void
	 */
	public function beginTransaction() : void
	{
		$this->conn->autocommit(FALSE);
	}
	
	
	/**
	 * @return void
	 */
	public function commitTransaction() : void
	{
		$this->conn->commit();
	}
	
	
	/**
	 * @return void
	 */
	function rollbackTransaction() : void
	{
		$this->conn->rollback();
	}
	
}