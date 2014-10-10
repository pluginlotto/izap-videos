<?php

/*
 *    This file is part of izap-videos plugin for Elgg.
 *
 *    izap-videos for Elgg is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    izap-videos for Elgg is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with izap-videos for Elgg.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Database setup
 * 
 * @version 5.0
 */
class IzapSqlite {

	private $db_connection;
	private $db_file_location;
	private $query;
	private $statement;
	private $err_code_msg = array(23000 => 'Already existing API.');
	public $message;
	public $code;
	public $rows_affected = 0;

	/*
	 * If set to false (e.g. due to PDOException), all queues and arrays will behave like
	 * empty arrays, and no PDO queries will be made.
	 * 
	 * @version 5.0
	 */
	public static $pdoSupport = true;

	/**
	 * @param string  $file_location
	 * 
	 * @version 5.0
	 */
	function __construct($file_location) {
		try {
			$this->db_file_location = $file_location;
			$this->db_connection = $this->connect();
			$this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$this->exception_formater($e);
		}
	}

	/**
	 * 
	 * @param string  $qry
	 * @param array   $params
	 * @param boolean $fetch_all
	 * @param type    $fetch_style
	 * 
	 * @return boolean
	 * 
	 * @version 5.0
	 */
	public function execute($qry, $params = array(), $fetch_all = true, $fetch_style = PDO::FETCH_ASSOC) {
		if (!$this->db_connection) {
			return FALSE;
		}
		$this->query = $qry;
		try {
			$this->statement = $statement = $this->db_connection->prepare($this->query);
			$stmt_result = $statement->execute($params);
			$this->rows_affected = $statement->rowCount();
			preg_match('/SELECT/i', $this->query, $matches);
			switch (strtoupper($matches[0])) {
				case 'SELECT':
					return $fetch_all ? $statement->fetchAll($fetch_style) : $statement->fetch($fetch_style);
					break;
				default:
					return $stmt_result;
					break;
			}
		} catch (PDOException $e) {
			$this->exception_formater($e);
			return false;
		}
	}

	/**
	 * Debug the statement
	 * 
	 * @return array
	 * 
	 * @version 5.0
	 */
	public function debug() {
		return $this->statement->debugDumpParams();
	}

	/**
	 * To inport sql file
	 * 
	 * @param string $sql_file_path
	 * 
	 * @version 5.0
	 */
	public function import_db($sql_file_path) {
		
	}

	/**
	 * Private function to connect database
	 * 
	 * @return \PDO
	 * 
	 * @version 5.0
	 */
	private function connect() {
		return new PDO('sqlite:' . $this->db_file_location);
	}

	/**
	 * Private function to format exception
	 * 
	 * @param PDOException $e
	 * 
	 * @version 5.0
	 */
	private function exception_formater(PDOException $e) {
		if (strstr($e->getMessage(), 'SQLSTATE[')) {
			preg_match('/SQLSTATE\[(\w+)\]: /', $e->getMessage(), $matches);
			$this->code = ($matches[1] == 'HT000' ) ? $matches[2] : $matches[1];
		}
		$this->message = isset($this->err_code_msg[$this->code]) ? $this->err_code_msg[$this->code] : $e->getMessage();
	}

}
