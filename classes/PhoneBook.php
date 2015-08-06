<?php

class PhoneBook {
	private $contactsContainer = array();
	private $PDO = null;
	
	public function __construct() {
		$this->__connectPDO();
	}
	
	

	public function getContacts($term = null) {
		$query = sprintf("SELECT * FROM %s", DB_TABLE);
		if($term !== null)
		{
			$term = str_replace(" ", "", $term);
			$query = sprintf("%s WHERE CONCAT(first_name, last_name, number) LIKE '%%{$term}%%' OR CONCAT(last_name, first_name, number) LIKE '%%{$term}%%'", $query);
		}
		$query = sprintf("%s ORDER BY first_name ASC", $query);
		
		$statement = $this->PDO->prepare($query);
		//$statement->bindValue(1, $term, PDO::PARAM_STR);
		$statement->execute(  );
		foreach($statement->fetchAll() as $value)
		{
			$this->contactsContainer[$value['id']] = new Contact($value);
		}
		return $this->contactsContainer;
		
	}
	
	
	
	private function __connectPDO() {
		try {
			$driverDetails = sprintf('mysql:dbname: %s ; host: %s', DB_NAME, DB_HOST);
			$this->PDO = new PDO ($driverDetails, DB_USER, DB_PASS);
			
		}
		catch (PDOException $e) {
		die($e->getMessage());
		}
	}
	
}

?>